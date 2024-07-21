<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthorityLoansResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'email' => $this->email,
            'contactNumber' => $this->contact_number,

            'ccLimit' => $this->cc_limit,
            'availableAmount' => $this->availableCCLoan(),
            'ccLoanTaken' => $this->ccLoanTaken(),

            'totalLoan' => $this->totalLoan(),
            'totalPayable' => $this->totalPayable(),
            'totalPaid' => $this->totalLoanPaid(),
            'totalDue' => $this->due,

            'interestPaid' => $this->interestPaid(),
            'ccLoanInterestPaid' => $this->ccLoanInterestPaid(),
            'termLoanInterest' => $this->termLoanInterest(),

            'address' => $this->address,
            'loans' => LoanResource::collection($this->allLoans),
            'note' => $this->note,
            'createdAt' => $this->created_at,
            'status' => (int) $this->status,
        ];
    }
}
