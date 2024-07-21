<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\PurchaseReturn;

class PurchaseReturnObserver
{
    /**
     * Handle the PurchaseReturn "created" event.
     *
     * @param  \App\Models\PurchaseReturn  $purchaseReturn
     * @return void
     */
    public function created(PurchaseReturn $purchaseReturn)
    {
        //
    }

    /**
     * Handle the PurchaseReturn "updated" event.
     *
     * @param  \App\Models\PurchaseReturn  $purchaseReturn
     * @return void
     */
    public function updated(PurchaseReturn $purchaseReturn)
    {
        //
    }

    /**
     * Handle the PurchaseReturn "deleted" event.
     *
     * @param  \App\Models\PurchaseReturn  $purchaseReturn
     * @return void
     */
    public function deleted(PurchaseReturn $purchaseReturn)
    {
        // delete purchase transaction
        if ($purchaseReturn->transaction_id != null) {
            $purchaseReturn->returnTransaction->delete();
        }

        // update product inventory count
        foreach ($purchaseReturn->purchaseReturnProducts as $purchaseReturnProduct) {
            $product = Product::findOrFail($purchaseReturnProduct->product_id);
            $product->update([
                'inventory_count' => $product->inventory_count + $purchaseReturnProduct->quantity,
            ]);
        }

        // delete return proucts
        $purchaseReturn->purchaseReturnProducts->each->delete();
    }

    /**
     * Handle the PurchaseReturn "restored" event.
     *
     * @param  \App\Models\PurchaseReturn  $purchaseReturn
     * @return void
     */
    public function restored(PurchaseReturn $purchaseReturn)
    {
        //
    }

    /**
     * Handle the PurchaseReturn "force deleted" event.
     *
     * @param  \App\Models\PurchaseReturn  $purchaseReturn
     * @return void
     */
    public function forceDeleted(PurchaseReturn $purchaseReturn)
    {
        //
    }
}
