<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationProduct extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quotation_id', 'product_id', 'quantity', 'purchase_price', 'sale_price', 'unit_cost', 'tax_amount',
    ];

    /**
     * Get the quotation for this product.
     */
    public function quotation()
    {
        return $this->belongsTo(Order::class, 'quotation_id');
    }

    /**
     * Get the product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
