<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeneralSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // check if table is empty
        if (DB::table('general_settings')->count() == 0) {

            // seed basic data to table
            $settingElements = [
                'company_name' => 'Acculance',
                'company_tagline' => 'Ultimate Sales, Inventory, Accounting Management System',
                'email_address' => 'support@codeshape.net',
                'phone_number' => '0170000000',
                'address' => 'Ground Floor, Road# 24, House# 339, New DOHS, Mohakhali, Dhaka - 1206, Bangladesh',
                'client_prefix' => 'AC',
                'supplier_prefix' => 'AS',
                'employee_prefix' => 'AE',
                'product_cat_prefix' => 'APC',
                'product_sub_cat_prefix' => 'APS',
                'product_prefix' => 'AP',
                'exp_cat_prefix' => 'AEC',
                'exp_sub_cat_prefix' => 'AES',
                'pur_prefix' => 'APP',
                'pur_return_prefix' => 'APR',
                'quotation_prefix' => 'APQ',
                'invoice_prefix' => 'AI',
                'invoice_return_prefix' => 'AIR',
                'adjustment_prefix' => 'AIA',
                'default_currency' => '1',
                'default_language' => 'en',
                'logo' => 'logo.png',
                'logo_black' => 'logo-black.png',
                'small_logo' => 'small-logo.png',
                'favicon' => 'favicon.png',
                'copyright' => '© Copyright 2023 Acculance',
                'default_client_slug'=> 'walking-customer',
                'default_account_slug'=> 'cash-0001',
                'default_vat_rate_slug'=> 'vat-0',
            ];

            foreach ($settingElements as $key => $value) {
                DB::table('general_settings')->insert([
                    [
                        'key' => $key,
                        'display_name' => ucwords(str_replace('_', ' ', $key)),
                        'value' => $value,
                    ],
                ]);
            }
        }
    }
}