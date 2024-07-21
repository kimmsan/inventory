<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use Sluggable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'purchase_no', 'slug', 'supplier_id', 'discount', 'transport', 'sub_total', 'tax_id', 'po_reference', 'payment_terms', 'po_date', 'purchase_date', 'created_by', 'note', 'status', 'is_paid',
    ];

    protected $appends = ['calculated_due', 'calculated_tax', 'calculated_total'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'purchase_no',
            ],
        ];
    }

    /**
     * Get the purchase due.
     *
     * @return string
     */
    public function getCalculatedDueAttribute()
    {
        return $this->totalDue();
    }

    /**
     * Get the purchase tax.
     *
     * @return string
     */
    public function getCalculatedTaxAttribute()
    {
        return $this->taxAmount();
    }

    /**
     * Get the purchase total.
     *
     * @return string
     */
    public function getCalculatedTotalAttribute()
    {
        return $this->purchaseTotal();
    }

    // calculate tax
    public function taxAmount()
    {
        $taxRate = $this->purchaseTax;
        $totalTax = 0;
        $subTotal = $this->sub_total;
        if (isset($taxRate) && $taxRate->rate > 0) {
            if (isset($this->purchaseReturn)) {
                $subTotal = $this->sub_total - $this->purchaseReturn->total_return;
            }
            $totalTax = ($taxRate->rate / 100) * $subTotal;
        }

        return $totalTax;
    }

    // purchase total
    public function purchaseTotal()
    {
        $costOfProductReturn = isset($this->purchaseReturn) ? $this->purchaseReturn->total_return : 0;

        return $this->sub_total + $this->taxAmount() + $this->transport - $this->discount - $costOfProductReturn;
    }

    // purchase total paid
    public function purchaseTotalPaid()
    {
        $totalPaid = $this->purchasePayments->sum('amount');

        return $totalPaid;
    }

    // purchase total due
    public function totalDue()
    {
        $due = $this->purchaseTotal() - $this->purchaseTotalPaid();

        return $due >= 0 ? $due : 0;
    }

    /**
     * Get the purchase products.
     */
    public function purchaseProducts()
    {
        return $this->hasMany(PurchaseProduct::class, 'purchase_id');
    }

    /**
     * Get the purchase payments.
     */
    public function purchasePayments()
    {
        return $this->hasMany(PurchasePayment::class, 'purchase_id');
    }

    /**
     * Get the purchase returns.
     */
    public function purchaseReturn()
    {
        return $this->hasOne(PurchaseReturn::class, 'purchase_id');
    }

    /**
     * Get the supplier for this purchase.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * Get the tax for this purchase.
     */
    public function purchaseTax()
    {
        return $this->belongsTo(VatRate::class, 'tax_id');
    }

    /**
     * Get the user who had created this purchase.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}