<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if(config('app.is_demo_mode')){
            $this->call(DemoRolesTableSeeder::class);
            $this->call(DemoUsersTableSeeder::class);
            $this->call(DemoGeneralSettingsTableSeeder::class);
            $this->call(DemoCurrenciesTableSeeder::class);
            $this->call(DemoUserRoleTableSeeder::class);
            $this->call(DemoPermissionsTableSeeder::class);
            $this->call(DemoUserPermissionTableSeeder::class);
            $this->call(DemoRolePermissionTableSeeder::class);
        }
        // for beta
        // $this->call(UserPermissionSeeder::class);

        $this->call(DemoAccountsTableSeeder::class);
        $this->call(DemoAccountTransactionsTableSeeder::class);
        $this->call(DemoAssetTypesTableSeeder::class);
        $this->call(DemoAssetsTableSeeder::class);
        $this->call(DemoBalanceTansfersTableSeeder::class);
        $this->call(DemoBrandsTableSeeder::class);
        $this->call(DemoClientsTableSeeder::class);
        $this->call(DemoDepartmentsTableSeeder::class);
        $this->call(DemoEmployeesTableSeeder::class);
        $this->call(DemoExpenseCategoriesTableSeeder::class);
        $this->call(DemoExpenseSubCategoriesTableSeeder::class);
        $this->call(DemoExpensesTableSeeder::class);
        $this->call(DemoFailedJobsTableSeeder::class);
        $this->call(DemoInventoryAdjustmentsTableSeeder::class);
        $this->call(DemoLoanAuthoritiesTableSeeder::class);
        $this->call(DemoLoansTableSeeder::class);
        $this->call(DemoLoanPaymentsTableSeeder::class);
        $this->call(DemoMigrationsTableSeeder::class);
        $this->call(DemoNonInvoicePaymentsTableSeeder::class);
        $this->call(DemoOauthProvidersTableSeeder::class);
        $this->call(DemoPasswordResetsTableSeeder::class);
        $this->call(DemoPaymentMethodsTableSeeder::class);
        $this->call(DemoPayrollsTableSeeder::class);
        $this->call(DemoProductCategoriesTableSeeder::class);
        $this->call(DemoProductSubCategoriesTableSeeder::class);
        $this->call(DemoSuppliersTableSeeder::class);
        $this->call(DemoVatRatesTableSeeder::class);
        $this->call(DemoPurchasesTableSeeder::class);
        $this->call(DemoPurchasePaymentsTableSeeder::class);
        $this->call(DemoPurchaseReturnsTableSeeder::class);
        $this->call(DemoQuotationsTableSeeder::class);
        $this->call(DemoSalaryIncrementsTableSeeder::class);
        $this->call(DemoUnitsTableSeeder::class);
        $this->call(DemoInvoicesTableSeeder::class);
        $this->call(DemoInvoicePaymentsTableSeeder::class);
        $this->call(DemoInvoiceReturnsTableSeeder::class);
        $this->call(DemoNonPurchasePaymentsTableSeeder::class);
        $this->call(DemoProductsTableSeeder::class);
        $this->call(DemoPurchaseProductsTableSeeder::class);
        $this->call(DemoPurchaseReturnProductsTableSeeder::class);
        $this->call(DemoQuotationProductsTableSeeder::class);
        $this->call(DemoAdjustmentProductsTableSeeder::class);
        $this->call(DemoInvoiceProductsTableSeeder::class);
        $this->call(DemoInvoiceReturnProductsTableSeeder::class);
    }
}
