<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceListResource extends JsonResource
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
            'invoiceNo' => $this->invoice_no,
            'invoiceLabel' => config('config.invoicePrefix').'-'.$this->invoice_no,
            'slug' => $this->slug,
            'reference' => $this->reference,
            'client' => $this->client->name,
            'transport' => $this->transport,
            'subTotal' => $this->sub_total,
            'invoiceTotal' => $this->invoiceTotal(),
            'discountType' => $this->discount_type,
            'discount' => $this->discount,
            'discountPercentage' => $this->discountPercentage(),
            'tax' => $this->taxAmount(),
            'taxRate' => $this->invoiceTax->rate,
            'totalPaid' => $this->invoiceTotalPaid(),
            'due' => $this->totalDue(),
            'poReference' => $this->po_reference,
            'paymentTerms' => $this->payment_terms,
            'deliveryPlace' => $this->delivery_place,
            'invoiceDate' => $this->invoice_date,
            'note' => $this->note,
            'status' => (int) $this->status,
        ];
    }
}
