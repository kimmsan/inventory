<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseProduct extends Model
{
    use HasFactory;

    protected $table = 'purchase_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'purchase_id', 'product_id', 'quantity', 'purchase_price', 'unit_cost', 'tax_amount',
    ];

    // get product total
    public function productTotal()
    {
        return $this->quantity * $this->purchase_price;
    }

    /**
     * Get the purchase for this product.
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    /**
     * Get the product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->orderBy('code', 'ASC');
    }
}
