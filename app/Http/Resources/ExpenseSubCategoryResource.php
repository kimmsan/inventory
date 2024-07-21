<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseSubCategoryResource extends JsonResource
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
            'code' => $this->code,
            'category' => new ExpenseCategoryResource($this->whenLoaded('expCategory')),
            'slug' => $this->slug,
            'note' => $this->note,
            'status' => (int) $this->status,
        ];
    }
}
