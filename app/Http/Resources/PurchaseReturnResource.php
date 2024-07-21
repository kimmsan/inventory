<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseReturnResource extends JsonResource
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
            'reason' => $this->reason,
            'slug' => $this->slug,
            'returnNo' => $this->code,
            'totalReturn' => $this->total_return,
            'purchase' => new PurchaseListResource($this->purchase),
            'supplier' => new SupplierListResource($this->purchase->supplier),
            'returnProducts' => PurchaseReturnProductResource::collection($this->purchaseReturnProducts),
            'accountReceivable' => isset($this->returnTransaction) ? $this->returnTransaction : null,
            'account' => isset($this->returnTransaction) ? new AccountResource($this->returnTransaction->cashbookAccount) : null,
            'returnDate' => $this->date,
            'note' => $this->note,
            'status' => (int) $this->status,
            'createdBy' => $this->user->name,
        ];
    }
}