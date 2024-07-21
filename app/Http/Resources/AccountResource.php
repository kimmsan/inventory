<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
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
            'slug' => $this->slug,
            'bankName' => $this->bank_name,
            'branchName' => $this->branch_name,
            'accountNumber' => $this->account_number,
            'label' => $this->bank_name.'['.$this->account_number.']',
            'availableBalance' => round($this->availableBalance(), 2),
            'totalCredits' => round($this->totalCredits(), 2),
            'totalDebits' => round($this->totalDebits(), 2),
            'date' => $this->date,
            'note' => $this->note,
            'status' => (int) $this->status,
        ];
    }
}
