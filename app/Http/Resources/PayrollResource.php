<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PayrollResource extends JsonResource
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
            'employee' => new EmployeeResource($this->employee),
            'transaction' => $this->payrollTransaction,
            'account' => new AccountResource($this->payrollTransaction->cashbookAccount),
            'salaryMonth' => $this->salary_month,
            'chequeNo' => $this->payrollTransaction->cheque_no,
            'deductionReason' => $this->deduction_reason,
            'deductionAmount' => $this->deduction_amount,
            'mobileBill' => $this->mobile_bill,
            'foodBill' => $this->food_bill,
            'bonus' => $this->bonus,
            'advance' => $this->advance,
            'festivalBonus' => $this->festival_bonus,
            'travelAllowance' => $this->travel_allowance,
            'commission' => $this->commission,
            'others' => $this->others,
            'salaryDate' => $this->salary_date,
            'note' => $this->note,
            'status' => (int) $this->status,
            'image' => $this->image_path ? asset('/images/payroll/'.$this->image_path) : '',
            'createdBy' => $this->user->name,
        ];
    }
}