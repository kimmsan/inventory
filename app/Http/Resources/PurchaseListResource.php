<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseListResource extends JsonResource
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
            'code' => $this->purchase_no,
            'purchaseNo' => config('config.purchasePrefix').'-'.$this->purchase_no,
            'slug' => $this->slug,
            'supplierName' => $this->supplier->name,
            'supplierPhone' => $this->supplier->phone,
            'transport' => $this->transport > 0 ? $this->transport : 0,
            'tax' => round($this->taxAmount(), 2),
            'taxRate' => $this->purchaseTax->rate,
            'subTotal' => round($this->sub_total, 2),
            'purchaseTotal' => round($this->purchaseTotal(), 2),
            'totalDiscount' => round($this->discount > 0 ? $this->discount : 0, 2),
            'totalPaid' => round($this->purchaseTotalPaid(), 2),
            'due' => round($this->totalDue() > 0 ? $this->totalDue() : 0, 2),
            'purchaseDate' => $this->purchase_date,
            'accountReceivable' => isset($this->purchaseReturn->returnTransaction) ? $this->purchaseReturn->returnTransaction->amount : null,
            'note' => $this->note,
            'status' => (int) $this->status,
        ];
    }
}
