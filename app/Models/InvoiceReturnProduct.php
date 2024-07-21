<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceReturnProduct extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'return_id', 'product_id', 'purchase_price', 'sale_price',  'quantity',
    ];

    /**
     * Get the invoice for this product.
     */
    public function invoiceReturn()
    {
        return $this->belongsTo(InvoiceReturn::class, 'return_id');
    }

    /**
     * Get the product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
