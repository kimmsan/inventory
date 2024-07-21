<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasePayment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'purchase_id', 'slug', 'transaction_id', 'amount', 'date', 'note', 'status', 'created_by',
    ];

    /**
     * Get the purchase for this payment.
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    /**
     * Get the tansaction for this payroll.
     */
    public function purchasePaymentTransaction()
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
