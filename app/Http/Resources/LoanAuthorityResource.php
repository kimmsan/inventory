<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanAuthorityResource extends JsonResource
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
            'contactNumber' => $this->contact_number,
            'email' => $this->email,
            'address' => $this->address,
            'ccLimit' => $this->cc_limit,
            'availableCCLoan' => $this->availableCCLoan(),
            'note' => $this->note,
            'status' => (int) $this->status,
            'createdAt' => $this->created_at,
        ];
    }
}
