<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BalanceTransferResource extends JsonResource
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
            'reason' => $this->reason,
            'slug' => $this->slug,
            'fromAccount' => new AccountResource($this->debitTransaction->cashbookAccount),
            'toAccount' => new AccountResource($this->creditTransaction->cashbookAccount),
            'debitTransaction' => $this->debitTransaction,
            'creditTransaction' => $this->creditTransaction,
            'amount' => $this->amount,
            'date' => $this->date,
            'note' => $this->note,
            'status' => (int) $this->status,
            'createdBy' => $this->user->name,
        ];
    }
}