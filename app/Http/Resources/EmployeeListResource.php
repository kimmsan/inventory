<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'empID' => $this->prefix,
            'slug' => $this->slug,
            'department' => $this->department,
            'designation' => $this->designation,
            'salary' => $this->salary,
            'totalSalary' => $this->totalSalary(),
            'commission' => $this->commission,
            'mobileNumber' => $this->mobile_number,
            'birthDate' => $this->birth_date,
            'gender' => $this->gender,
            'bloodGroup' => $this->blood_group,
            'religion' => $this->religion,
            'appointmentDate' => $this->appointment_date,
            'joiningDate' => $this->joining_date,
            'address' => $this->address,
            'user' => isset($this->user_id) ? $this->user : null,
            'allowLogin' => isset($this->user_id) ? true : false,
            'email' => isset($this->user_id) ? $this->user->email : null,
            'role' => isset($this->user_id) ? $this->user->roles[0] : '',
            'status' => (int) $this->status,
            'image' => $this->image_path ? asset('/images/employees/'.$this->image_path) : '',
        ];
    }
}
