<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdjustmentProductsResource extends JsonResource
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
            'purchasePrice' => $this->purchase_price,
            'type' => (int) $this->type,
            'quantity' => $this->quantity,
            'productID' => $this->product->id,
            'productSlug' => $this->product->slug,
            'productCode' => $this->product->code,
            'productName' => $this->product->name,
            'productModel' => $this->product->model,
            'avgPurchasePrice' => round($this->product->purchase_price, 2),
            'productUnit' => $this->product->productUnit->code,
            'productStock' => $this->product->inventory_count,
        ];
    }
}
