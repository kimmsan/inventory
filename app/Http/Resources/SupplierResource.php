<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
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
            'supplierID' => $this->supplier_id,
            'slug' => $this->slug,
            'email' => $this->email,
            'phoneNumber' => $this->phone,
            'companyName' => $this->company_name,
            'address' => $this->address,
            'status' => (int) $this->status,
            'image' => $this->image_path ? asset('/images/suppliers/'.$this->image_path) : '',
            'purchaseCount' => $this->purchases->count(),
            'purchaseTotal' => $this->purchaseTotal(),
            'purchaseReturnTotal' => $this->purchaseReturnTotal(),
            'purchaseTotalPaid' => $this->purchaseTotalPaid(),
            'purchaseTotalDue' => $this->purchaseTotalDue(),
            'purTotalDiscount' => $this->purTotalDiscount(),
            'nonPurchaseTotalDue' => $this->nonPurchaseTotalDue(),
            'nonPurchasePaid' => $this->nonPurchasePaid(),
            'nonPurchaseCurrentDue' => $this->nonPurchaseCurrentDue(),
        ];
    }
}
