<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanPaymentResource extends JsonResource
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
            'reference' => $this->reference_no,
            'slug' => $this->slug,
            'loan' => new LoanResource($this->loan),
            'authority' => new LoanAuthorityResource($this->loan->loanAuthority),
            'account' => new AccountResource($this->loanPaymentTransaction->cashbookAccount),
            'transaction' => $this->loanPaymentTransaction,
            'amount' => round($this->amount, 2),
            'interest' => round($this->interest, 2),
            'date' => $this->date,
            'note' => $this->note,
            'status' => (int) $this->status,
            'paid' => $this->is_paid,
            'image' => $this->image_path ? asset('/images/loan-payments/'.$this->image_path) : '',
            'created_by' => $this->user->name,
        ];
    }
}
