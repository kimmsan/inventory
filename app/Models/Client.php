<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use Sluggable, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'client_id', 'email', 'phone', 'company_name', 'address', 'status', 'image_path',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    // return client invoices total
    public function clientInvoiceTotal()
    {
        $invoiceTotal = 0;
        $invoices = $this->clientInvoices;
        if ($invoices) {
            $invoiceTotal = $invoices->where('status', 1)->sum('calculated_total');
        }
        return $invoiceTotal;
    }

    // return client total paid
    public function clientTotalPaid()
    {
        $totalPaid = 0;
        if (isset($this->invoicePayments)) {
            $totalPaid = $this->invoicePayments->where('status', 1)->sum('amount');
        }
        return $totalPaid;
    }

    // return client due
    public function clientDue()
    {
        $due = $this->clientInvoices->sum('calculated_due');
        return $due;
    }

    // return client sub total
    public function clientSubTotal()
    {
        $subTotal = 0;
        $invoices = $this->clientInvoices;
        if ($invoices) {
            $subTotal = $invoices->sum('sub_total');
        }

        return $subTotal;
    }

    // return client total discount
    public function clientInvoiceDiscount()
    {
        $discount = 0;
        $invoices = $this->clientInvoices;
        if ($invoices) {
            $discount = $invoices->sum('discount');
        }
        return $discount;
    }

    // return client total transport cost
    public function clientInvoiceTransportCost()
    {
        $transportCost = 0;
        $invoices = $this->clientInvoices;
        if ($invoices) {
            $transportCost = $invoices->sum('transport');
        }
        return $transportCost;
    }

    // return client total non invoice due
    public function nonInvoiceTotalDue()
    {
        $totalDue = 0;
        $dues = $this->clientNonInvoiceDues;
        if (isset($dues)) {
            $totalDue = $dues->where('status', 1)->sum('amount');
        }
        return $totalDue;
    }

    // return client total non invoice paid
    public function nonInvoicePaid()
    {
        $totalPaid = 0;
        $paid = $this->clientNonInvoicePayments;
        if (isset($paid)) {
            $totalPaid = $paid->where('status', 1)->sum('amount');
        }
        return $totalPaid;
    }

    // return client total non invoice current due
    public function nonInvoiceCurrentDue()
    {
        return $this->nonInvoiceTotalDue() - $this->nonInvoicePaid();
    }

    /**
     * Get the non invoice dues
     */
    public function clientNonInvoiceDues()
    {
        return $this->hasMany(NonInvoicePayment::class, 'client_id')->where('type', 0);
    }

    /**
     * Get the non invoice payments
     */
    public function clientNonInvoicePayments()
    {
        return $this->hasMany(NonInvoicePayment::class, 'client_id')->where('type', 1);
    }

    /**
     * Get the invoices.
     */
    public function clientInvoices()
    {
        return $this->hasMany(Invoice::class, 'client_id');
    }

    /**
     * Get the invoice payments for the client.
     */
    public function invoicePayments()
    {
        return $this->hasManyThrough(InvoicePayment::class, Invoice::class, 'client_id', 'invoice_id');
    }

    public function routeNotificationForTwilio()
    {
        return $this->phone;
    }
}