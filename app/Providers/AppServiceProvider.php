<?php

namespace App\Providers;

use DB;
use App\Models\Currency;
use App\Models\GeneralSetting;
use Laravel\Dusk\DuskServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // define default string length
        Schema::defaultStringLength(191);

        // if table is not empty then get setting items
        if (DB::connection()->getDatabaseName()) {
            if (Schema::hasTable('general_settings') && Schema::hasTable('currencies')) {
                $allSettings = GeneralSetting::get();
                $currency = Currency::first();
                // define global variables
                if (count($allSettings) > 0) {
                    config(['config.clientPrefix' => $allSettings->where('key', 'client_prefix')->first()?->value]);
                    config(['config.employeePrefix' => $allSettings->where('key', 'employee_prefix')->first()?->value]);
                    config(['config.supplierPrefix' => $allSettings->where('key', 'supplier_prefix')->first()?->value]);
                    config(['config.expCatPrefix' => $allSettings->where('key', 'exp_cat_prefix')->first()?->value]);
                    config([
                        'config.expSubCatPrefix' => $allSettings->where('key', 'exp_sub_cat_prefix')->first()?->value
                    ]);
                    config([
                        'config.proCatPrefix' => $allSettings->where('key', 'product_cat_prefix')->first()?->value
                    ]);
                    config([
                        'config.proSubCatPrefix' => $allSettings->where(
                            'key',
                            'product_sub_cat_prefix'
                        )->first()?->value
                    ]);
                    config(['config.productPrefix' => $allSettings->where('key', 'product_prefix')->first()?->value]);
                    config(['config.purchasePrefix' => $allSettings->where('key', 'pur_prefix')->first()?->value]);
                    config([
                        'config.purchaseReturnPrefix' => $allSettings->where(
                            'key',
                            'pur_return_prefix'
                        )->first()?->value
                    ]);
                    config([
                        'config.quotationPrefix' => $allSettings->where('key', 'quotation_prefix')->first()?->value
                    ]);
                    config(['config.invoicePrefix' => $allSettings->where('key', 'invoice_prefix')->first()?->value]);
                    config([
                        'config.invoiceReturnPrefix' => $allSettings->where(
                            'key',
                            'invoice_return_prefix'
                        )->first()?->value
                    ]);
                    config([
                        'config.adjustmentPrefix' => $allSettings->where('key', 'adjustment_prefix')->first()?->value
                    ]);
                    config(['config.currencySymbol' => $currency->symbol ?? '$']);
                    config(['config.currencyPosition' => $currency->position ?? 'left']);
                    config(['config.favicon' => $allSettings->where('key', 'favicon')->first()?->value]);
                    config(['config.companyName' => $allSettings->where('key', 'company_name')->first()?->value]);
                    config(['config.companyPhoneNumber' => $allSettings->where('key', 'phone_number')->first()?->value]);
                    config(['config.companyEmail' => $allSettings->where('key', 'email_address')->first()?->value]);
                    config(['config.logo' => $allSettings->where('key', 'logo')->first()?->value]);
                    config(['config.logoBlack' => $allSettings->where('key', 'logo_black')->first()?->value]);
                    config(['config.address' => $allSettings->where('key', 'address')->first()?->value]);
                }
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing') && class_exists(DuskServiceProvider::class)) {
            $this->app->register(DuskServiceProvider::class);
        }
    }
}