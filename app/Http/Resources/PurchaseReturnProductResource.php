<?php

namespace App\Http\Resources;

use App\Models\PurchaseProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseReturnProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $purchaseProduct = PurchaseProduct::where('purchase_id', $this->purchaseReturn->purchase->id)->where('product_id', $this->product_id)->first();
        $purchasedQty = $purchaseProduct->quantity;

        return [
            'id' => $this->id,
            'returnQty' => $this->quantity,
            'purchasedQty' => $purchasedQty,
            'product' => new ProductListingResource($this->product),
            'purchasePrice' => $this->purchase_price,
        ];
    }
}