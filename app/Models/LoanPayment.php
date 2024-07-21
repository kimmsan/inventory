<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model
{
    use Sluggable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reference_no', 'slug', 'loan_id', 'transaction_id', 'amount', 'interest', 'date', 'created_by', 'note', 'status', 'image_path',
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
                'source' => 'reference_no',
            ],
        ];
    }

    /**
     * Get the Loan for this loan payment.
     */
    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }

    /**
     * Get the  tansaction for this loan payment.
     */
    public function loanPaymentTransaction()
    {
        return $this->belongsTo(AccountTransaction::class, 'transaction_id');
    }

    /**
     * Get the user who had created this expense.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
