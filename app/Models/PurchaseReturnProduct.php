<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnProduct extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'return_id', 'product_id', 'purchase_price', 'quantity',
    ];

    /**
     * Get the purchase return for this product.
     */
    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class, 'return_id');
    }

    /**
     * Get the product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
