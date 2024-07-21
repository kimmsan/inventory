<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Purchase;

class PurchaseObserver
{
    /**
     * Handle the Purchase "created" event.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return void
     */
    public function created(Purchase $purchase)
    {
        //
    }

    /**
     * Handle the Purchase "updated" event.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return void
     */
    public function updated(Purchase $purchase)
    {
        //
    }

    /**
     * Handle the Purchase "deleted" event.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return void
     */
    public function deleted(Purchase $purchase)
    {
        // update product purchase price and stock
        foreach ($purchase->purchaseProducts as $key => $purchaseProduct) {
            $product = Product::findOrFail($purchaseProduct->product_id);
            // calculate new purchase price
            $currentStockPrice = $product->inventory_count * $product->purchase_price;
            $newStockPrice = $purchaseProduct->quantity * $purchaseProduct->unit_cost;

            $totalStockPrice = $currentStockPrice - $newStockPrice;
            $totalQty = $product->inventory_count - $purchaseProduct->quantity;
            $unitCost = null;
            if ($totalQty > 0) {
                $unitCost = $totalStockPrice / $totalQty;
            }
            $product->update([
                'purchase_price' => $unitCost,
                'inventory_count' => $totalQty,
            ]);
        }
        // delete purchase product and payments
        $purchase->purchaseProducts->each->delete();
        $purchase->purchasePayments->each->delete();
    }

    /**
     * Handle the Purchase "restored" event.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return void
     */
    public function restored(Purchase $purchase)
    {
        //
    }

    /**
     * Handle the Purchase "force deleted" event.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return void
     */
    public function forceDeleted(Purchase $purchase)
    {
        //
    }
}
