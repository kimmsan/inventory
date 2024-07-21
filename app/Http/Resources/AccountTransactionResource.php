<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $balance = 0;
        if($this->type == 1){
            $balance = $balance + $this->amount;
        }
        else{
           $balance = $balance - $this->amount;
        }

        return [
            'id' => $this->id,
            'account' => new AccountResource($this->cashbookAccount),
            'reason' => $this->reason,
            'slug' => $this->slug,
            'amount' => round($this->amount, 2),
            'balance' => round($balance, 2),
            'type' => (int) $this->type,
            'transactionDate' => date_format(date_create($this->transaction_date), 'Y-m-d'),
            'note' => $this->note,
            'status' => (int) $this->status,
            'user' => $this->user,
        ];
    }
}