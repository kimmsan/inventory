<?php

namespace App\Observers;

use App\Models\InvoiceReturn;

class InvoiceReturnObserver
{
    /**
     * Handle the InvoiceReturn "created" event.
     *
     * @param  \App\Models\InvoiceReturn  $invoiceReturn
     * @return void
     */
    public function created(InvoiceReturn $invoiceReturn)
    {
        //
    }

    /**
     * Handle the InvoiceReturn "updated" event.
     *
     * @param  \App\Models\InvoiceReturn  $invoiceReturn
     * @return void
     */
    public function updated(InvoiceReturn $invoiceReturn)
    {
        //
    }

    /**
     * Handle the InvoiceReturn "deleted" event.
     *
     * @param  \App\Models\InvoiceReturn  $invoiceReturn
     * @return void
     */
    public function deleted(InvoiceReturn $invoiceReturn)
    {
        // delete return transaction
        if ($invoiceReturn->transaction_id != null) {
            $invoiceReturn->returnTransaction->delete();
        }

        // update product inventory count
        foreach ($invoiceReturn->invoiceReturnProducts as $invoiceReturnProduct) {
            $product = $invoiceReturnProduct->product;
            $product->update([
                'inventory_count' => $product->inventory_count - $invoiceReturnProduct->quantity,
            ]);
        }

        // delete return proucts
        $invoiceReturn->invoiceReturnProducts->each->delete();
    }

    /**
     * Handle the InvoiceReturn "restored" event.
     *
     * @param  \App\Models\InvoiceReturn  $invoiceReturn
     * @return void
     */
    public function restored(InvoiceReturn $invoiceReturn)
    {
        //
    }

    /**
     * Handle the InvoiceReturn "force deleted" event.
     *
     * @param  \App\Models\InvoiceReturn  $invoiceReturn
     * @return void
     */
    public function forceDeleted(InvoiceReturn $invoiceReturn)
    {
        //
    }
}
