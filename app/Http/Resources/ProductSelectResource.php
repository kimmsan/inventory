<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductSelectResource extends JsonResource
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
            'label' => $this->name.' ['.$this->code.']',
            'slug' => $this->slug,
            'code' => $this->code,
            'itemModel' => $this->model,
            'avgPurchasePrice' => $this->purchase_price,
            'regularPrice' => $this->regular_price,
            'priceWithDiscount' => $this->priceWithDiscount(),
            'sellingPrice' => $this->sellingPrice(),
            'taxAmount' => $this->taxAmount(),
            'taxType' => $this->tax_type,
            'taxRate' => $this->productTax->rate,
            'inventoryCount' => $this->inventory_count > 0 ? $this->inventory_count : 0,
            'image' => $this->image_path ? asset('images/products/'.$this->image_path) : '',
        ];
    }
}