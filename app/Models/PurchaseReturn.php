<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    use Sluggable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reason', 'slug', 'code', 'purchase_id', 'transaction_id', 'total_return', 'date', 'note', 'status', 'created_by',
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
                'source' => 'reason',
            ],
        ];
    }

    /**
     * Get the purchase for this return.
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    /**
     * Get the return products.
     */
    public function purchaseReturnProducts()
    {
        return $this->hasMany(PurchaseReturnProduct::class, 'return_id');
    }

    /**
     * Get the transaction for this return.
     */
    public function returnTransaction()
    {
        return $this->belongsTo(AccountTransaction::class, 'transaction_id');
    }

    /**
     * Get the user who had created this return.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}