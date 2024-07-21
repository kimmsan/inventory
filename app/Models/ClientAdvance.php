<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientAdvance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id', 'return_id', 'amount', 'note', 'status', 'created_by',
    ];

    /**
     * Get the client
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Get the return
     */
    public function invoiceReturn()
    {
        return $this->belongsTo(InvoiceReturn::class, 'return_id');
    }

    /**
     * Get the user who has created this account
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
