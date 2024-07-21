<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'label' => $this->name.' ['.$this->code.']',
            'slug' => $this->slug,
            'code' => str_pad($this->code, 5, '0', STR_PAD_LEFT),
            'itemModel' => $this->model,
            'symbology' => $this->barcode_symbology,
            'subCategory' => new ProductSubCategoryResource($this->whenLoaded('proSubCategory')),
            'category' => new ProductCategoryResource($this->proSubCategory->category),
            'itemUnit' => new UnitResource($this->productUnit),
            'itemBrand' => new BrandResource($this->productBrand),
            'itemTax' => new VatRateResource($this->productTax),
            'taxType' => $this->tax_type,
            'taxAmount' => $this->taxAmount(),
            'avgPurchasePrice' => $this->purchase_price,
            'regularPrice' => $this->regular_price,
            'sellingPrice' => $this->sellingPrice(),
            'discount' => $this->discount,
            'discountAmount' => $this->discountAmount(),
            'availableQty' => $this->inventory_count > 0 ? $this->inventory_count : 0,
            'alertQty' => $this->alert_qty,
            'note' => $this->note,
            'status' => (int) $this->status,
            'image' => $this->image_path ? asset('/images/products/'.$this->image_path) : '',
        ];
    }
}
