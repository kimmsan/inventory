<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
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
            'category' => new ExpenseCategoryResource($this->expSubCategory->expCategory),
            'subCategory' => new ExpenseSubCategoryResource($this->whenLoaded('expSubCategory')),
            'account' => new AccountResource($this->expTransaction->cashbookAccount),
            'transaction' => $this->expTransaction,
            'date' => $this->date,
            'note' => $this->note,
            'status' => (int) $this->status,
            'createdBy' => $this->user->name,
            'image' => $this->image_path ? asset('/images/expenses/'.$this->image_path) : '',
        ];
    }
}
