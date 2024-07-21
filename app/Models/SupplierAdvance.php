<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierAdvance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'supplier_id', 'return_id', 'amount', 'note', 'status', 'created_by',
    ];

    /**
     * Get the supplier
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * Get the return
     */
    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class, 'return_id');
    }

    /**
     * Get the user who has created this advance
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
