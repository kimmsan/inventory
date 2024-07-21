<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Sluggable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'code', 'model', 'barcode_symbology', 'sub_cat_id', 'brand_id', 'unit_id', 'tax_id', 'tax_type', 'purchase_price', 'regular_price', 'discount', 'inventory_count', 'alert_qty', 'note', 'status', 'image_path',
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

    // return product discount amount
    public function discountAmount()
    {
        $discount = 0;
        if ($this->discount > 0) {
            $discount = ($this->discount / 100) * $this->regular_price;
        }

        return $discount;
    }

    // return product tax
    public function taxAmount()
    {
        $totalTax = $tax = 0;
        $currentPrice = $this->regular_price - $this->discountAmount();
        $productTax = $this->productTax;
        if ($productTax->rate > 0) {
            $tax = ($productTax->rate / 100);
        }

        if ($this->tax_type == 'Exclusive') {
            $totalTax = $currentPrice * $tax;
        } else {
            $totalTax = $currentPrice - ($currentPrice / (1 + $tax));
        }

        return round($totalTax, 2);
    }

    // return product  price with discount
    public function priceWithDiscount()
    {
        return round($this->regular_price - $this->discountAmount(), 2);
    }

    // return product selling price
    public function sellingPrice()
    {
        $price = 0;
        if ($this->tax_type == 'Exclusive') {
            $price = $this->regular_price - $this->discountAmount() + $this->taxAmount();
        } else {
            $price = (($this->regular_price - $this->discountAmount()) / (1 + $this->productTax->rate / 100)) + $this->taxAmount();
        }

        return round($price, 2);
    }

    // return purchase qty
    public function purchaseQty()
    {
        $purchaseProducts = $this->purchaseProducts;

        return isset($purchaseProducts) ? $purchaseProducts->sum('quantity') : 0;
    }

    // return invoice qty
    public function invoiceQty()
    {
        $invoiceProducts = $this->invoiceProducts;

        return isset($invoiceProducts) ? $invoiceProducts->sum('quantity') : 0;
    }

    // return invoice return qty
    public function invoiceReturnQty()
    {
        $invoiceReturnProducts = $this->invoiceReturnProducts;

        return isset($invoiceReturnProducts) ? $invoiceReturnProducts->sum('quantity') : 0;
    }

    // return increment adjustments
    public function incrementAdjustments()
    {
        $incrementAdjustments = $this->adjustmentProducts;

        return isset($incrementAdjustments) ? $incrementAdjustments->where('type', 1)->sum('quantity') : 0;
    }

    // return decrement adjustments
    public function decrementAdjustments()
    {
        $decrementAdjustments = $this->adjustmentProducts;

        return isset($decrementAdjustments) ? $decrementAdjustments->where('type', 0)->sum('quantity') : 0;
    }

    /**
     * Return relation with PurchaseProduct Model
     */
    public function purchaseProducts()
    {
        return $this->hasMany(PurchaseProduct::class, 'product_id');
    }

    /**
     * Return relation with InvoiceProduct Model
     */
    public function invoiceProducts()
    {
        return $this->hasMany(InvoiceProduct::class, 'product_id');
    }

    /**
     * Return relation with InvoiceReturnProduct Model
     */
    public function invoiceReturnProducts()
    {
        return $this->hasMany(InvoiceReturnProduct::class, 'product_id');
    }

    /**
     * Return relation with AdjustmentProduct Model
     */
    public function adjustmentProducts()
    {
        return $this->hasMany(AdjustmentProduct::class, 'product_id');
    }

    /**
     * Get the category that owns the product.
     */
    public function proSubCategory()
    {
        return $this->belongsTo(ProductSubCategory::class, 'sub_cat_id');
    }

    /**
     * Get the unit.
     */
    public function productUnit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    /**
     * Get the brand.
     */
    public function productBrand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /**
     * Get the tax.
     */
    public function productTax()
    {
        return $this->belongsTo(VatRate::class, 'tax_id');
    }
}
