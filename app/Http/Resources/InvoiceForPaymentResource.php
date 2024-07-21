<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceForPaymentResource extends JsonResource
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
            'label' => config('config.invoicePrefix').'-'.$this->invoice_no,
            'slug' => $this->slug,
            'reference' => $this->reference,
            'discountType' => $this->discount_type,
            'discount' => $this->discount,
            'discountPercentage' => $this->discountPercentage(),
            'transport' => $this->transport,
            'taxRate' => $this->invoiceTax,
            'tax' => $this->total_tax,
            'subTotal' => $this->sub_total,
            'invoiceTotal' => $this->invoiceTotal(),
            'totalPaid' => $this->invoiceTotalPaid(),
            'due' => $this->totalDue(),
            'totalInvoiceReturn' => isset($this->invoiceReturn) ? $this->invoiceReturn->total_return : 0,
        ];
    }
}
