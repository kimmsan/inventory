<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupPurchaseResource extends JsonResource
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
            'purchaseNo' => $this->prefix,
            'slug' => $this->slug,
            'transport' => $this->transport,
            'subTotal' => $this->sub_total,
            'purchaseTotal' => $this->purchaseTotal(),
            'totalDiscount' => $this->discount,
            'totalPaid' => $this->purchaseTotalPaid(),
            'due' => $this->totalDue(),
            'poReference' => $this->po_reference,
            'paymentTerms' => $this->payment_terms,
            'poDate' => $this->po_date,
            'purchaseDate' => $this->purchase_date,
            'note' => $this->note,
            'status' => (int) $this->status,
            'createdBy' => $this->user,
        ];
    }
}
