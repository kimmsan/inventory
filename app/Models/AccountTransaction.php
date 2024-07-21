<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountTransaction extends Model
{
    use Sluggable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id', 'reason', 'amount', 'type', 'transaction_date', 'cheque_no', 'receipt_no',  'created_by', 'note', 'status',
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
     * Get the total credits
     */
    public function totalCredits()
    {
        return $this->where('status', 1)->where('type', 1)->sum('amount');
    }

    /**
     * Get the all debits
     */
    public function totalDebits()
    {
        return $this->where('status', 1)->where('type', 0)->sum('amount');
    }

    /**
     * Get the available balance
     */
    public function availableBalance()
    {
        return $this->totalCredits() - $this->totalDebits();
    }

    /**
     * Get the account that owns this transaction
     */
    public function cashbookAccount()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    /**
     * Get the user who has created this transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
