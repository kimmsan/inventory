<?php

namespace App\Observers;

use App\Models\PurchasePayment;

class PurchasePaymentObserver
{
    /**
     * Handle the PurchasePayment "created" event.
     *
     * @param  \App\Models\PurchasePayment  $purchasePayment
     * @return void
     */
    public function created(PurchasePayment $purchasePayment)
    {
        //
    }

    /**
     * Handle the PurchasePayment "updated" event.
     *
     * @param  \App\Models\PurchasePayment  $purchasePayment
     * @return void
     */
    public function updated(PurchasePayment $purchasePayment)
    {
    }

    /**
     * Handle the PurchasePayment "deleted" event.
     *
     * @param  \App\Models\PurchasePayment  $purchasePayment
     * @return void
     */
    public function deleted(PurchasePayment $purchasePayment)
    {
        // delete or update account receivable
        if (isset($purchasePayment->purchase->purchaseReturn)) {
            if (isset($purchasePayment->purchase->purchaseReturn->returnTransaction) && $purchasePayment->amount > $purchasePayment->purchase->purchaseReturn->total_return) {
                $purchasePayment->purchase->purchaseReturn->returnTransaction->delete();
            }
        }
        // delete payment transaction
        $purchasePayment->purchasePaymentTransaction->delete();
    }

    /**
     * Handle the PurchasePayment "restored" event.
     *
     * @param  \App\Models\PurchasePayment  $purchasePayment
     * @return void
     */
    public function restored(PurchasePayment $purchasePayment)
    {
        //
    }

    /**
     * Handle the PurchasePayment "force deleted" event.
     *
     * @param  \App\Models\PurchasePayment  $purchasePayment
     * @return void
     */
    public function forceDeleted(PurchasePayment $purchasePayment)
    {
        //
    }
}