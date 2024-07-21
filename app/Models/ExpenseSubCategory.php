<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseSubCategory extends Model
{
    use Sluggable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code', 'exp_id', 'slug', 'note', 'status',
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
                'source' => 'name',
            ],
        ];
    }

    /**
     * Get the category that owns the sub category.
     */
    public function expCategory()
    {
        return $this->belongsTo(ExpenseCategory::class, 'exp_id');
    }

    /**
     * Get all expenses.
     */
    public function allExpenses()
    {
        return $this->hasMany(Expense::class, 'sub_cat_id');
    }
}
