<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NonPurchasePaymentListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $transaction = $this->paymentTransaction;

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'supplierName' => $this->supplier->name,
            'account' => $transaction ? new AccountResource($transaction->cashbookAccount) : '',
            'transaction' => $this->paymentTransaction,
            'amount' => $this->amount,
            'type' => (int) $this->type,
            'date' => $this->date,
            'note' => $this->note,
            'status' => (int) $this->status,
        ];
    }
}