<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use Sluggable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bank_name', 'branch_name', 'account_number', 'date',  'created_by', 'note', 'status',
    ];

    protected $appends = ['available_balance'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'account_number',
            ],
        ];
    }

    /**
     * Get the code with prefix.
     *
     * @return string
     */
    public function getAvailableBalanceAttribute()
    {
        return $this->availableBalance();
    }

    /**
     * Get the available balance
     */
    public function availableBalance()
    {
        return $this->totalCredits() - $this->totalDebits();
    }

    /**
     * Get the total credits
     */
    public function totalCredits()
    {
        return $this->balanceTransactions->where('status', 1)->where('type', 1)->sum('amount');
    }

    /**
     * Get the all debits
     */
    public function totalDebits()
    {
        return $this->balanceTransactions->where('status', 1)->where('type', 0)->sum('amount');
    }

    /**
     * Get the balance transactions
     */
    public function balanceTransactions()
    {
        return $this->hasMany(AccountTransaction::class, 'account_id');
    }

    /**
     * Get the user who has created this account
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
