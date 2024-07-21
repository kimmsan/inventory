<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuotationListResource extends JsonResource
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
            'quotationNo' => $this->quotation_no,
            'quotationLabel' => $this->quotation_no,
            'slug' => $this->slug,
            'reference' => $this->reference,
            'clientName' => $this->client->name,
            'clientID' => $this->client->client_id,
            'transport' => $this->transport,
            'discountType' => $this->discount_type,
            'discountPercentage' => $this->discountPercentage(),
            'discount' => $this->discount,
            'totalTax' => $this->total_tax,
            'subTotal' => $this->sub_total,
            'total' => $this->quotationTotal(),
            'date' => $this->quotation_date,
            'note' => $this->note,
            'status' => (int) $this->status,
            'createdBy' => $this->user->name,
        ];
    }
}
