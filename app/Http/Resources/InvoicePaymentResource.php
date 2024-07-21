<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoicePaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $transaction = $this->invoicePaymentTransaction;

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'invoice' => new InvoiceListResource($this->invoice),
            'client' => new ClientListResource($this->invoice->client),
            'account' => isset($transaction) ? new AccountResource($transaction->cashbookAccount) : null,
            'transaction' => isset($transaction) ? $transaction : null,
            'type' => isset($transaction) ? 1 : 0,
            'amount' => $this->amount,
            'costOfReturn' => isset($this->invoice->invoiceReturn) ? $this->invoice->invoiceReturn->total_return : 0,
            'accountPayable' => isset($this->invoice->invoiceReturn->returnTransaction) ? $this->invoice->invoiceReturn->returnTransaction->amount : null,
            'date' => $this->date,
            'note' => $this->note,
            'status' => (int) $this->status,
            'createdBy' => $this->user->name,
        ];
    }
}