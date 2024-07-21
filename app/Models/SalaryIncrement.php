<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryIncrement extends Model
{
    use Sluggable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reason', 'empolyee_id', 'increment_amount', 'increment_date', 'note', 'status', 'created_by',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'reason',
            ],
        ];
    }

    /**
     * Get the employee who own this increment .
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'empolyee_id');
    }

    /**
     * Get the user who had created this increment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
