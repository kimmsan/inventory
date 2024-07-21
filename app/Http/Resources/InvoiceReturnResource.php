<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceReturnResource extends JsonResource
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
            'returnNo' => $this->return_no,
            'reason' => $this->reason,
            'slug' => $this->slug,
            'totalReturn' => $this->total_return,
            'invoice' => new InvoiceListResource($this->invoice),
            'client' => new ClientListResource($this->invoice->client),
            'invoiceReturnProducts' => InvoiceReturnProductResource::collection($this->invoiceReturnProducts),
            'accountPayable' => isset($this->returnTransaction) ? $this->returnTransaction : null,
            'account' => isset($this->returnTransaction) ? new AccountResource($this->returnTransaction->cashbookAccount) : null,
            'returnDate' => $this->date,
            'note' => $this->note,
            'status' => (int) $this->status,
            'createdBy' => $this->user->name,
        ];
    }
}
