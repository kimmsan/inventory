<?php

namespace App\Providers;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\InvoiceReturn;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Models\PurchaseReturn;
use App\Observers\ExpenseObserver;
use App\Observers\InvoiceObserver;
use App\Observers\InvoicePaymentObserver;
use App\Observers\InvoiceReturnObserver;
use App\Observers\PurchaseObserver;
use App\Observers\PurchasePaymentObserver;
use App\Observers\PurchaseReturnObserver;
use DB;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // regsiter observers
        if (DB::connection()->getDatabaseName()) {
            Expense::observe(ExpenseObserver::class);
            Purchase::observe(PurchaseObserver::class);
            PurchasePayment::observe(PurchasePaymentObserver::class);
            PurchaseReturn::observe(PurchaseReturnObserver::class);
            Invoice::observe(InvoiceObserver::class);
            InvoicePayment::observe(InvoicePaymentObserver::class);
            InvoiceReturn::observe(InvoiceReturnObserver::class);
        }
    }
}
