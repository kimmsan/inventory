<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientWithNonInvoicePaymentResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'email' => $this->email,
            'phoneNumber' => $this->phone,
            'companyName' => $this->company_name,
            'address' => $this->address,
            'status' => (int) $this->status,
            'nonInvoiceDue' => $this->nonInvoiceTotalDue(),
            'nonInvoicePaid' => $this->nonInvoicePaid(),
            'nonInvoiceCurrentDue' => $this->nonInvoiceCurrentDue(),
        ];
    }
}
