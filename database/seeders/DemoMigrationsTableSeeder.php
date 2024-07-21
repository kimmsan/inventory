<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoMigrationsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('migrations')->delete();

        \DB::table('migrations')->insert([
            0 => [
                'id' => 1,
                'migration' => '2014_10_12_000000_create_users_table',
                'batch' => 1,
            ],
            1 => [
                'id' => 2,
                'migration' => '2014_10_12_100000_create_password_resets_table',
                'batch' => 1,
            ],
            2 => [
                'id' => 3,
                'migration' => '2017_12_07_122845_create_oauth_providers_table',
                'batch' => 1,
            ],
            3 => [
                'id' => 4,
                'migration' => '2019_08_19_000000_create_failed_jobs_table',
                'batch' => 1,
            ],
            4 => [
                'id' => 5,
                'migration' => '2021_02_13_095716_create_roles_table',
                'batch' => 1,
            ],
            5 => [
                'id' => 6,
                'migration' => '2021_02_13_102015_create_permissions_table',
                'batch' => 1,
            ],
            6 => [
                'id' => 7,
                'migration' => '2021_02_13_104235_create_user_permission_table',
                'batch' => 1,
            ],
            7 => [
                'id' => 8,
                'migration' => '2021_02_13_104245_create_user_role_table',
                'batch' => 1,
            ],
            8 => [
                'id' => 9,
                'migration' => '2021_02_13_104407_create_role_permission_table',
                'batch' => 1,
            ],
            9 => [
                'id' => 10,
                'migration' => '2021_02_13_104412_create_currencies_table',
                'batch' => 1,
            ],
            10 => [
                'id' => 11,
                'migration' => '2021_02_13_104414_create_payment_methods_table',
                'batch' => 1,
            ],
            11 => [
                'id' => 12,
                'migration' => '2021_02_13_104418_create_units_table',
                'batch' => 1,
            ],
            12 => [
                'id' => 13,
                'migration' => '2021_02_13_104420_create_vat_rates_table',
                'batch' => 1,
            ],
            13 => [
                'id' => 14,
                'migration' => '2021_02_13_104424_create_general_settings_table',
                'batch' => 1,
            ],
            14 => [
                'id' => 15,
                'migration' => '2021_02_13_104428_create_brands_table',
                'batch' => 1,
            ],
            15 => [
                'id' => 16,
                'migration' => '2021_02_13_104430_create_product_categories_table',
                'batch' => 1,
            ],
            16 => [
                'id' => 17,
                'migration' => '2021_02_13_104432_create_product_sub_categories_table',
                'batch' => 1,
            ],
            17 => [
                'id' => 18,
                'migration' => '2021_02_13_104434_create_products_table',
                'batch' => 1,
            ],
            18 => [
                'id' => 19,
                'migration' => '2021_02_13_104437_create_accounts_table',
                'batch' => 1,
            ],
            19 => [
                'id' => 20,
                'migration' => '2021_02_13_104439_create_account_transactions_table',
                'batch' => 1,
            ],
            20 => [
                'id' => 21,
                'migration' => '2021_02_13_104440_create_balance_tansfers_table',
                'batch' => 1,
            ],
            21 => [
                'id' => 22,
                'migration' => '2021_02_13_104442_create_asset_types_table',
                'batch' => 1,
            ],
            22 => [
                'id' => 23,
                'migration' => '2021_02_13_104445_create_assets_table',
                'batch' => 1,
            ],
            23 => [
                'id' => 24,
                'migration' => '2021_02_13_104480_create_expense_categories_table',
                'batch' => 1,
            ],
            24 => [
                'id' => 25,
                'migration' => '2021_02_13_104485_create_expense_sub_categories_table',
                'batch' => 1,
            ],
            25 => [
                'id' => 26,
                'migration' => '2021_02_13_104485_create_expenses_table',
                'batch' => 1,
            ],
            26 => [
                'id' => 27,
                'migration' => '2021_02_13_104490_create_clients_table',
                'batch' => 1,
            ],
            27 => [
                'id' => 28,
                'migration' => '2021_02_13_104495_create_suppliers_table',
                'batch' => 1,
            ],
            28 => [
                'id' => 29,
                'migration' => '2021_03_03_080732_create_purchases_table',
                'batch' => 1,
            ],
            29 => [
                'id' => 30,
                'migration' => '2021_03_03_080734_create_purchase_products_table',
                'batch' => 1,
            ],
            30 => [
                'id' => 31,
                'migration' => '2021_03_03_080736_create_purchase_payments_table',
                'batch' => 1,
            ],
            31 => [
                'id' => 32,
                'migration' => '2021_03_03_080750_create_purchase_returns_table',
                'batch' => 1,
            ],
            32 => [
                'id' => 33,
                'migration' => '2021_03_03_080760_create_purchase_return_products_table',
                'batch' => 1,
            ],
            33 => [
                'id' => 34,
                'migration' => '2021_03_10_085743_create_loan_authorities_table',
                'batch' => 1,
            ],
            34 => [
                'id' => 35,
                'migration' => '2021_03_10_085748_create_loans_table',
                'batch' => 1,
            ],
            35 => [
                'id' => 36,
                'migration' => '2021_03_10_085751_create_loan_payments_table',
                'batch' => 1,
            ],
            36 => [
                'id' => 37,
                'migration' => '2021_03_10_191817_create_departments_table',
                'batch' => 1,
            ],
            37 => [
                'id' => 38,
                'migration' => '2021_05_02_170213_create_employees_table',
                'batch' => 1,
            ],
            38 => [
                'id' => 39,
                'migration' => '2021_05_03_120828_create_salary_increments_table',
                'batch' => 1,
            ],
            39 => [
                'id' => 40,
                'migration' => '2021_05_05_145647_create_payrolls_table',
                'batch' => 1,
            ],
            40 => [
                'id' => 41,
                'migration' => '2021_05_23_035457_create_quotations_table',
                'batch' => 1,
            ],
            41 => [
                'id' => 42,
                'migration' => '2021_05_23_170721_create_quotation_products_table',
                'batch' => 1,
            ],
            42 => [
                'id' => 43,
                'migration' => '2021_05_23_193319_create_invoices_table',
                'batch' => 1,
            ],
            43 => [
                'id' => 44,
                'migration' => '2021_05_23_193527_create_invoice_products_table',
                'batch' => 1,
            ],
            44 => [
                'id' => 45,
                'migration' => '2021_05_23_193535_create_invoice_payments_table',
                'batch' => 1,
            ],
            45 => [
                'id' => 46,
                'migration' => '2021_05_24_114000_create_invoice_returns_table',
                'batch' => 1,
            ],
            46 => [
                'id' => 47,
                'migration' => '2021_05_25_114159_create_invoice_return_products_table',
                'batch' => 1,
            ],
            47 => [
                'id' => 48,
                'migration' => '2021_07_29_132545_create_inventory_adjustments_table',
                'batch' => 1,
            ],
            48 => [
                'id' => 49,
                'migration' => '2021_07_29_133109_create_adjustment_products_table',
                'batch' => 1,
            ],
            49 => [
                'id' => 50,
                'migration' => '2022_01_02_164016_create_non_invoice_payments_table',
                'batch' => 1,
            ],
            50 => [
                'id' => 51,
                'migration' => '2022_01_02_164415_create_non_purchase_payments_table',
                'batch' => 1,
            ],
        ]);
    }
}
