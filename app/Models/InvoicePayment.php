<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePayment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_id', 'slug',  'amount', 'transaction_id', 'date', 'note', 'status', 'created_by',
    ];

    /**
     * Get the invoice for this  payment.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    /**
     * Get the  tansaction for this payroll.
     */
    public function invoicePaymentTransaction()
    {
        return $this->belongsTo(AccountTransaction::class, 'transaction_id');
    }

    /**
     * Get the user who had created this payment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
