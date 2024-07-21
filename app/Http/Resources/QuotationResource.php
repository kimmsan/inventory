<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuotationResource extends JsonResource
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
            'quotationNo' => $this->quotation_no,
            'quotationLabel' => $this->quotation_no,
            'slug' => $this->slug,
            'reference' => $this->reference,
            'client' => new ClientListResource($this->client),
            'transport' => $this->transport,
            'discountType' => $this->discount_type,
            'discountPercentage' => $this->discountPercentage(),
            'discount' => $this->discount,
            'quotationTax' => $this->quotationTax,
            'totalTax' => $this->total_tax,
            'subTotal' => $this->sub_total,
            'total' => $this->quotationTotal(),
            'products' => QuotationProductListResource::collection($this->quotationProducts),
            'deliveryPlace' => $this->delivery_place,
            'date' => $this->quotation_date,
            'note' => $this->note,
            'status' => (int) $this->status,
            'createdBy' => $this->user->name,
        ];
    }
}
