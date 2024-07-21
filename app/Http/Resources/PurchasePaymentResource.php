<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchasePaymentResource extends JsonResource
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
            'slug' => $this->slug,
            'purchase' => new PurchaseListResource($this->purchase),
            'supplier' => new SupplierForPurchasePaymentResource($this->purchase->supplier),
            'account' => new AccountResource($this->purchasePaymentTransaction->cashbookAccount),
            'transaction' => $this->purchasePaymentTransaction,
            'amount' => $this->amount,
            'costOfReturn' => isset($this->purchase->purchaseReturn) ? $this->purchase->purchaseReturn->total_return : 0,
            'date' => $this->date,
            'note' => $this->note,
            'status' => (int) $this->status,
            'createdBy' => $this->user->name,
        ];
    }
}