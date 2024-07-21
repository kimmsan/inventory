<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
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
            'type' => new AssetTypeResource($this->assetType),
            'amount' => round($this->asset_cost, 2),
            'depreciation' => (int) $this->depreciation,
            'depreciationType' => (int) $this->depreciation_type,
            'salvageValue' => round($this->salvage_value, 2),
            'usefulLife' => $this->useful_life,
            'depreciationExpense' => $this->depreciationExpense(),
            'depreciationExpenseTxt' => $this->depreciationExpenseTxt(),
            'deprecatedAmount' => $this->deprecatedAmount(),
            'usedLife' => $this->usedLife(),
            'currentValue' => $this->currentValue(),
            'image' => $this->image_path ? asset('/images/assets/'.$this->image_path) : '',
            'note' => $this->note,
            'status' => (int) $this->status,
            'date' => $this->date,
            'createdAt' => $this->created_at,
            'createdBy' => $this->user->name,
            'remainingLife' => $this->remainingLife(),
            'expireDate' => $this->expire_date,
        ];
    }
}
