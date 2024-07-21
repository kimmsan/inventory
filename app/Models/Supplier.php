<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use Sluggable, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'supplier_id', 'email', 'phone', 'company_name', 'address', 'status', 'image_path',
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

    // get the supplier purchase total
    public function purchaseTotal()
    {
        $total = 0;
        if (isset($this->purchases)) {
            $total = $this->purchases->where('status', 1)->sum('calculated_total');
        }
        return $total;
    }

    // get the supplier purchase return total
    public function purchaseReturnTotal()
    {
        $total = 0;
        if (isset($this->purchases)) {
            $total = $this->purchases->where('status', 1)->sum(function ($purchase) {
                $costOfReturn = isset($purchase->purchaseReturn) ? $purchase->purchaseReturn->total_return : 0;

                return  $costOfReturn;
            });
        }
        return $total;
    }

    // get the supplier purchase total discount
    public function purTotalDiscount()
    {
        $totalDiscount = $this->purchases->sum('discount');
        return $totalDiscount > 0 ? $totalDiscount : 0;
    }

    // get the supplier purchase total transport
    public function purTotalTransport()
    {
        $totalTransport = $this->purchases->sum('transport');
        return $totalTransport > 0 ? $totalTransport : 0;
    }

    // get the supplier purchase total tax
    public function purTotalTax()
    {
        $totalTax = $this->purchases->sum('calculated_tax');
        return $totalTax > 0 ? $totalTax : 0;
    }

    // get the supplier purchase total paid
    public function purchaseTotalPaid()
    {
        $totalPaid = 0;
        if ($this->purchasePayments) {
            $totalPaid = $this->purchasePayments->sum('amount');
        }
        return $totalPaid;
    }

    // Get the supplier due
    public function purchaseTotalDue()
    {
        $due = 0;
        if (isset($this->purchases)) {
            $due = $this->purchases->where('status', 1)->sum('calculated_due');
        }
        return $due;
    }

    // return supplier total non purchase paid
    public function nonPurchasePaid()
    {
        $totalPaid = 0;
        $paid = $this->supplierNonPurchasePayments;
        if (isset($paid)) {
            $totalPaid = $paid->where('status', 1)->sum('amount');
        }
        return $totalPaid;
    }

    // return supplier total non purchase due
    public function nonPurchaseTotalDue()
    {
        $totalDue = 0;
        $dues = $this->supplierNonPurchaseDues;
        if (isset($dues)) {
            $totalDue = $dues->where('status', 1)->sum('amount');
        }
        return $totalDue;
    }

    // return supplier total non purchase current due
    public function nonPurchaseCurrentDue()
    {
        return $this->nonPurchaseTotalDue() - $this->nonPurchasePaid();
    }

    /**
     * Get the purchases for the supplier.
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'supplier_id');
    }

    /**
     * Get the purchase payments for the supplier.
     */
    public function purchasePayments()
    {
        return $this->hasManyThrough(PurchasePayment::class, Purchase::class, 'supplier_id', 'purchase_id');
    }

    /**
     * Get the non purchase dues
     */
    public function supplierNonPurchaseDues()
    {
        return $this->hasMany(NonPurchasePayment::class, 'supplier_id')->where('type', 0);
    }

    /**
     * Get the non invoice payments
     */
    public function supplierNonPurchasePayments()
    {
        return $this->hasMany(NonPurchasePayment::class, 'supplier_id')->where('type', 1);
    }


    public function routeNotificationForTwilio()
    {
        return $this->phone;
    }
}