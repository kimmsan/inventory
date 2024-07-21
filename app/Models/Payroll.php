<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug', 'employee_id', 'transaction_id', 'salary_month', 'deduction_reason', 'deduction_amount', 'mobile_bill', 'food_bill', 'bonus', 'commission', 'advance', 'festival_bonus', 'travel_allowance', 'others', 'salary_date', 'created_by', 'image_path', 'status', 'note',
    ];

    /**
     * Get the employee for this payroll.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Get the  tansaction for this payroll.
     */
    public function payrollTransaction()
    {
        return $this->belongsTo(AccountTransaction::class, 'transaction_id');
    }

    /**
     * Get the user who had created this payroll.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
