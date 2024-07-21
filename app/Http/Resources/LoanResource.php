<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
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
            'reference' => $this->reference_no,
            'slug' => $this->slug,
            'authority' => new LoanAuthorityResource($this->loanAuthority),
            'transaction' => $this->loanTransaction,
            'account' => new AccountResource($this->loanTransaction->cashbookAccount),
            'loanType' => (int) $this->loan_type,
            'interestRate' => round($this->interest, 2),
            'interestAmount' => round($this->totalInterest(), 2),
            'interestPaid' => round($this->interestPaid(), 2),
            'payable' => round($this->payable, 2),
            'due' => round($this->totalDue(), 2),
            'totalPaid' => round($this->totalPaid(), 2),
            'loanWithInterest' => round($this->totalPaid() + $this->interestPaid(), 2),
            'installment' => $this->installmentStr(),
            'perMonth' => $this->montlyPayment(),
            'paymentType' => (int) $this->payment_type,
            'duration' => (int) $this->duration,
            'durationStr' => $this->formattedDuration(),
            'loanPayments' => $this->loanPayments,
            'date' => $this->date,
            'note' => $this->note,
            'status' => (int) $this->status,
            'paid' => (int) $this->is_paid,
            'createdBy' => $this->user->name,
            'image' => $this->image_path ? asset('/images/loans/'.$this->image_path) : '',
        ];
    }
}
