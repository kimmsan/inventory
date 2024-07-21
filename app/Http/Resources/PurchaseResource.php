<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
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
            'code' => $this->purchase_no,
            'slug' => $this->slug,
            'purchaseNo' => config('config.purchasePrefix').'-'.$this->purchase_no,
            'supplier' => new SupplierListResource($this->supplier),
            'totalDiscount' => $this->discount,
            'transport' => $this->transport,
            'tax' => $this->total_tax,
            'taxRate' => $this->purchaseTax->rate,
            'subTotal' => $this->sub_total,
            'purchaseTotal' => $this->purchaseTotal(),
            'totalPaid' => $this->purchaseTotalPaid(),
            'due' => $this->totalDue(),
            'poReference' => $this->po_reference,
            'paymentTerms' => $this->payment_terms,
            'poDate' => $this->po_date,
            'purchaseDate' => $this->purchase_date,
            'payments' => $this->purchasePayments,
            'purchaseProducts' => PurchaseProductResource::collection($this->purchaseProducts),
            'note' => $this->note,
            'status' => (int) $this->status,
            'createdBy' => $this->user->name,
        ];
    }
}
