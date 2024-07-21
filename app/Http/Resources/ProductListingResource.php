<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductListingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'label' => $this->name . ' [' . $this->code . ']',
            'slug' => $this->slug,
            'subCategory' => new ProductSubCategoryResource($this->whenLoaded('proSubCategory')),
            'category' => new ProductCategoryResource($this->proSubCategory->category),
            'code' => $this->code,
            'itemModel' => $this->model,
            'itemUnit' => new UnitResource($this->productUnit),
            'inventoryCount' => $this->inventory_count,
            'alertQty' => $this->alert_qty,
            'regularPrice' => $this->regular_price,
            'sellingPrice' => $this->sellingPrice(),
            'discount' => $this->discount,
            'discountAmount' => $this->discountAmount(),
            'taxAmount' => $this->taxAmount(),
            'note' => $this->note,
            'status' => (int) $this->status,
            'image' => $this->image_path ? asset('/images/products/' . $this->image_path) : '',
        ];
    }
}