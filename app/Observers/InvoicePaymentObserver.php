<?php

namespace App\Observers;

use App\Models\InvoicePayment;

class InvoicePaymentObserver
{
    /**
     * Handle the InvoicePayment "created" event.
     *
     * @param  \App\Models\InvoicePayment  $invoicePayment
     * @return void
     */
    public function created(InvoicePayment $invoicePayment)
    {
        //
    }

    /**
     * Handle the InvoicePayment "updated" event.
     *
     * @param  \App\Models\InvoicePayment  $invoicePayment
     * @return void
     */
    public function updated(InvoicePayment $invoicePayment)
    {
        //
    }

    /**
     * Handle the InvoicePayment "deleted" event.
     *
     * @param  \App\Models\InvoicePayment  $invoicePayment
     * @return void
     */
    public function deleted(InvoicePayment $invoicePayment)
    {
        // delete or update account receivable
        if (isset($invoicePayment->invoice->invoiceReturn)) {
            if (isset($invoicePayment->invoice->invoiceReturn->returnTransaction) && $invoicePayment->amount > $invoicePayment->invoice->invoiceReturn->total_return) {
                $invoicePayment->invoice->invoiceReturn->returnTransaction->delete();
            }
        }

        // delete payment transaction
        $invoicePayment->invoicePaymentTransaction->delete();
    }

    /**
     * Handle the InvoicePayment "restored" event.
     *
     * @param  \App\Models\InvoicePayment  $invoicePayment
     * @return void
     */
    public function restored(InvoicePayment $invoicePayment)
    {
        //
    }

    /**
     * Handle the InvoicePayment "force deleted" event.
     *
     * @param  \App\Models\InvoicePayment  $invoicePayment
     * @return void
     */
    public function forceDeleted(InvoicePayment $invoicePayment)
    {
        //
    }
}
