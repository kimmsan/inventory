<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Models\Product;

class InvoiceObserver
{
    /**
     * Handle the Invoice "created" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function created(Invoice $invoice)
    {
        //
    }

    /**
     * Handle the Invoice "updated" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function updated(Invoice $invoice)
    {
        //
    }

    /**
     * Handle the Invoice "deleted" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function deleted(Invoice $invoice)
    {
        // update product inventory count
        foreach ($invoice->invoiceProducts as $key => $invoiceProduct) {
            $product = Product::findOrFail($invoiceProduct->product_id);
            $totalQty = $product->inventory_count + $invoiceProduct->quantity;
            $product->update([
                'inventory_count' => $totalQty,
            ]);
        }

        // delete payments
        if ($invoice->invoicePayments) {
            $invoice->invoicePayments->each->delete();
        }
        // delete invoice product
        $invoice->invoiceProducts->each->delete();
    }

    /**
     * Handle the Invoice "restored" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function restored(Invoice $invoice)
    {
        //
    }

    /**
     * Handle the Invoice "force deleted" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function forceDeleted(Invoice $invoice)
    {
        //
    }
}
