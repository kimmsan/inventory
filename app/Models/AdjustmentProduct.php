<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdjustmentProduct extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'adjustment_id', 'product_id', 'type', 'purchase_price', 'quantity',
    ];

    /**
     * Get the order for this product.
     */
    public function inventoryAdjustment()
    {
        return $this->belongsTo(InventoryAdjustment::class, 'adjustment_id');
    }

    /**
     * Get the product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
