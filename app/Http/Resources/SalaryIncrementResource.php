<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SalaryIncrementResource extends JsonResource
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
            'employee' => new EmployeeResource($this->employee),
            'incrementAmount' => $this->increment_amount,
            'incrementDate' => $this->increment_date,
            'note' => $this->note,
            'status' => (int) $this->status,
            'user' => $this->user->name,
        ];
    }
}
