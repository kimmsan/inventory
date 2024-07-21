<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseReturnListResource extends JsonResource
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
            'totalReturn' => $this->total_return,
            'purReturnNo' => $this->code,
            'purchaseNo' => $this->purchase->purchase_no,
            'supplierName' => $this->purchase->supplier->name,
            'supplierPhone' => $this->purchase->supplier->phone,
            'returnDate' => $this->date,
            'note' => $this->note,
            'status' => (int) $this->status,
        ];
    }
}