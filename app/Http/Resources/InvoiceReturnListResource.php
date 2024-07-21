<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceReturnListResource extends JsonResource
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
            'returnNo' => $this->return_no,
            'slug' => $this->slug,
            'totalReturn' => $this->total_return,
            'invoiceNo' => $this->invoice->invoice_no,
            'invoiceSlug' => $this->invoice->slug,
            'clientName' => $this->invoice->client->name,
            'returnDate' => $this->date,
            'note' => $this->note,
            'status' => (int) $this->status,
            'createdBy' => $this->user,
        ];
    }
}
