<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_no', 'slug', 'reference', 'client_id', 'discount_type', 'discount', 'transport', 'sub_total', 'po_reference', 'payment_terms', 'delivery_place', 'tax_id', 'invoice_date', 'created_by', 'note', 'status', 'is_paid',
    ];

    protected $appends = ['calculated_due', 'calculated_tax', 'calculated_total'];

    /**
     * Get the invoice due.
     *
     * @return string
     */
    public function getCalculatedDueAttribute()
    {
        return $this->totalDue();
    }

    /**
     * Get the invoice tax.
     *
     * @return string
     */
    public function getCalculatedTaxAttribute()
    {
        return $this->taxAmount();
    }

    /**
     * Get the invoice total.
     *
     * @return string
     */
    public function getCalculatedTotalAttribute()
    {
        return $this->invoiceTotal();
    }

    // calculate tax
    public function taxAmount()
    {
        $taxRate = $this->invoiceTax;
        $totalTax = 0;
        $subTotal = $this->sub_total;
        if (isset($taxRate) && $taxRate->rate > 0) {
            if (isset($this->invoiceReturn)) {
                $subTotal = $this->sub_total - $this->invoiceReturn->total_return;
            }
            $totalTax = ($taxRate->rate / 100) * $subTotal;
        }

        return $totalTax;
    }

    // return discount percentage
    public function discountPercentage()
    {
        $percentage = null;
        if ($this->discount_type == 1) {
            $costOfReturn = isset($this->invoiceReturn) ? $this->invoiceReturn->total_return : 0;
            $percentage = ($this->discount * 100) / ($this->sub_total - $costOfReturn);
        }

        return (int) $percentage;
    }

    // invoice total
    public function invoiceTotal()
    {
        $costOfProductReturn = isset($this->invoiceReturn) ? $this->invoiceReturn->total_return : 0;
        return $this->sub_total + $this->transport + +$this->taxAmount() - $this->discount - $costOfProductReturn;
    }

    // purchase total paid
    public function invoiceTotalPaid()
    {
        $totalPaid = 0;
        // payments
        $invoicePayments = $this->invoicePayments;
        // total paid
        if (isset($invoicePayments)) {
            $totalPaid = $invoicePayments->sum('amount');
        }

        return $totalPaid;
    }

    // invoice total due
    public function totalDue()
    {
        $due = $this->invoiceTotal() - $this->invoiceTotalPaid();
        return $due >= 0 ? $due : 0;
    }

    /**
     * Get the invoice products.
     */
    public function invoiceProducts()
    {
        return $this->hasMany(InvoiceProduct::class, 'invoice_id', 'id')->orderBy('product_id');
    }

    /**
     * Get the invoice payments.
     */
    public function invoicePayments()
    {
        return $this->hasMany(InvoicePayment::class, 'invoice_id');
    }

    /**
     * Get the invoice returns.
     */
    public function invoiceReturn()
    {
        return $this->hasOne(InvoiceReturn::class, 'invoice_id');
    }

    /**
     * Get the invoice return products.
     */
    public function invoiceReturnProducts()
    {
        return $this->hasManyThrough(InvoiceReturnProduct::class, InvoiceReturn::class, 'invoice_id', 'return_id')->orderBy('product_id');
    }

    /**
     * Get the client for this quotation.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Get the tax for this invoice.
     */
    public function invoiceTax()
    {
        return $this->belongsTo(VatRate::class, 'tax_id');
    }

    /**
     * Get the user who had created this invoice.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}