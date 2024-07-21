<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceTansfer extends Model
{
    use Sluggable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reason', 'slug', 'debit_id', 'credit_id', 'amount', 'date', 'note', 'status', 'created_by',
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
     * Get the debit tansaction for this transfer.
     */
    public function debitTransaction()
    {
        return $this->belongsTo(AccountTransaction::class, 'debit_id');
    }

    /**
     * Get the credit tansaction for this transfer.
     */
    public function creditTransaction()
    {
        return $this->belongsTo(AccountTransaction::class, 'credit_id');
    }

    /**
     * Get the user who had created this transfer.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
