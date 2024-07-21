<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\API\LoanController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\UnitController;
use App\Http\Controllers\API\AssetController;
use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\BalanceController;
use App\Http\Controllers\API\ExpenseController;
use App\Http\Controllers\API\GeneralController;
use App\Http\Controllers\API\InvoiceController;
use App\Http\Controllers\API\PayrollController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\VatRateController;
use App\Http\Controllers\API\CurrencyController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\PurchaseController;
use App\Http\Controllers\API\SupplierController;
use App\Http\Controllers\API\AssetTypeController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\ExpSubCatController;
use App\Http\Controllers\API\InventoryController;
use App\Http\Controllers\API\ProSubCatController;
use App\Http\Controllers\API\QuotationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\API\DepartmentController;
use App\Http\Controllers\API\ExpenseCatController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\LoanPaymentController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\InvoiceReturnController;
use App\Http\Controllers\API\LoanAuthorityController;
use App\Http\Controllers\API\PaymentMethodController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\API\InvoicePaymentController;
use App\Http\Controllers\API\PurchaseReturnController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\API\EmpSalIncrementController;
use App\Http\Controllers\API\ProductCategoryController;
use App\Http\Controllers\API\PurchasePaymentController;
use App\Http\Controllers\API\TransferBalanceController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\API\NonInvoicePaymentController;
use App\Http\Controllers\API\NonPurchasePaymentController;
use App\Http\Controllers\API\InventoryAdjustmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// get general settings info
Route::get('general-settings', [GeneralController::class, 'getGeneralSettings']);
// command route
Route::get('server', [ServerController::class, 'runCommand']);
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [LoginController::class, 'logout']);

    // Dashboard stats
    Route::get('/dashboard-summery/{summeryType}', [DashboardController::class, 'dashboardSummery']);
    // Dashboard top selling products
    Route::get('dashboard/top-selling-products', [DashboardController::class, 'topSellingProducts']);
    // Activities routes
    Route::get('dashboard/recent-invoices', [DashboardController::class, 'recentInvoices']);
    Route::get('dashboard/recent-purchases', [DashboardController::class, 'recentPurchases']);
    Route::get('dashboard/recent-expenses', [DashboardController::class, 'recentExpenses']);
    Route::get('dashboard/recent-transactions', [DashboardController::class, 'recentTransactions']);
    // Dashboard monthly sales and purchases
    Route::get('dashboard/monthly-payment-sent-received', [DashboardController::class, 'monthlyPaymentSentAndReceived']);
    // Dashboard top clients
    Route::get('dashboard/top-clients', [DashboardController::class, 'topClients']);
    // Dashboard stock alert
    Route::get('dashboard/stock-alert', [DashboardController::class, 'stockAlert']);
    // Dashboard monthly sales and purchases
    Route::get('dashboard/monthly-sales-purchases', [DashboardController::class, 'monthlySalesAndPurchases']);
    // Stock notification for navbar
    Route::get('dashboard/stock-notification', [DashboardController::class, 'stockNotification']);
    Route::get('stock-alert-products', [DashboardController::class, 'stockAlertProducts']);
    Route::get('stock-alert-products/search', [DashboardController::class, 'searchStockAlertProducts']);

    // get current user
    Route::get('user', [UserController::class, 'current']);

    // general settings
    Route::post('update-settings', [GeneralController::class, 'updateGeneralSettings']);

    // Email routes
    Route::get('mail-configuration', [GeneralController::class, 'getMailConfiguration']);
    Route::post('update-mail-configuration', [GeneralController::class, 'updateMailConfiguration']);

    // SMS routes
    Route::get('sms-configuration', [GeneralController::class, 'getSMSConfiguration']);
    Route::post('update-sms-configuration', [GeneralController::class, 'updateSMSConfiguration']);

    // Permission routes
    Route::get('/permissions/search', [PermissionController::class, 'search']);
    Route::get('/all-permissions', [PermissionController::class, 'allPermissions']);
    Route::apiResource('permissions', PermissionController::class);

    // Role routes
    Route::get('/roles/search', [RoleController::class, 'search']);
    Route::get('/all-roles', [RoleController::class, 'allRoles']);
    Route::apiResource('roles', RoleController::class);

    // Unit routes
    Route::get('/units/search', [UnitController::class, 'search']);
    Route::get('/all-units', [UnitController::class, 'allUnits']);
    Route::apiResource('units', UnitController::class);

    // Currency routes
    Route::get('/currencies/search', [CurrencyController::class, 'search']);
    Route::get('/all-currencies', [CurrencyController::class, 'allCurrencies']);
    Route::apiResource('currencies', CurrencyController::class);

    // VAT-RATE routes
    Route::get('/vat-rates/search', [VatRateController::class, 'search']);
    Route::get('/all-vat-rates', [VatRateController::class, 'allVatRates']);
    Route::apiResource('vat-rates', VatRateController::class);

    // Brand routes
    Route::get('/brands/search', [BrandController::class, 'search']);
    Route::get('/all-brands', [BrandController::class, 'allBrands']);
    Route::apiResource('brands', BrandController::class);

    // Payment method routes
    Route::get('/payment-methods/search', [PaymentMethodController::class, 'search']);
    Route::get('/all-payment-methods', [PaymentMethodController::class, 'allMethods']);
    Route::apiResource('payment-methods', PaymentMethodController::class);

    // Expense categories routes
    Route::get('/expense-categories/search', [ExpenseCatController::class, 'search']);
    Route::get('/all-expense-categories', [ExpenseCatController::class, 'allCategories']);
    Route::apiResource('expense-categories', ExpenseCatController::class);

    // Expense sub categories routes
    Route::get('/expense-sub-categories/search', [ExpSubCatController::class, 'search']);
    Route::get('/all-expense-sub-categories', [ExpSubCatController::class, 'allSubCategories']);
    Route::get('/sub-categories-by-category/{slug}', [ExpSubCatController::class, 'subCategoriesByCategory']);
    Route::apiResource('expense-sub-categories', ExpSubCatController::class);

    // Expense routes
    Route::get('/expenses/search/', [ExpenseController::class, 'search']);
    Route::apiResource('expenses', ExpenseController::class);

    // Purchase routes
    Route::get('/purchases/search', [PurchaseController::class, 'search']);
    Route::get('/due-purchases', [PurchaseController::class, 'duePurchases']);
    Route::post('/purchase/notify/{slug}', [PurchaseController::class, 'notifySupplier']);
    Route::post('/purchase-pay', [PurchaseController::class, 'storePurchasePayment']);
    Route::apiResource('purchases', PurchaseController::class);

    // Purchase return routes
    Route::post('/supplier/filter-purchases', [SupplierController::class, 'filterSupplierPurchases']);
    Route::get('/purchase-returns/search', [PurchaseReturnController::class, 'search']);
    Route::apiResource('purchase-returns', PurchaseReturnController::class);

    // Quotation routes
    Route::get('/quotations/search', [QuotationController::class, 'search']);
    Route::post('/quotation/notify/{slug}', [QuotationController::class, 'notifyCustomer']);
    Route::get('/all-quotations', [QuotationController::class, 'allQuotations']);
    Route::apiResource('quotations', QuotationController::class);

    // Invoice routes
    Route::get('/invoices/search', [InvoiceController::class, 'search']);
    Route::get('/due-invoices', [InvoiceController::class, 'allDueInvoices']);
    Route::post('/invoices-pay', [InvoiceController::class, 'storeInvoicePayments']);
    Route::post('/invoice/notify/{slug}', [InvoiceController::class, 'notifyCustomer']);
    Route::apiResource('invoices', InvoiceController::class);

    // Invoice return routes
    Route::get('/invoice-returns/search', [InvoiceReturnController::class, 'search']);
    Route::apiResource('invoice-returns', InvoiceReturnController::class);

    // Account routes
    Route::get('/accounts/search', [AccountController::class, 'search']);
    Route::get('/all-accounts', [AccountController::class, 'allAccounts']);
    Route::get('/accounts/transactions/{slug}', [AccountController::class, 'accountTransactions']);
    Route::get('/accounts/transactions/{slug}/search', [AccountController::class, 'searchTransactions']);
    Route::apiResource('accounts', AccountController::class);

    // Balance routes
    Route::get('/balances/search', [BalanceController::class, 'search']);
    Route::get('/all-balances', [BalanceController::class, 'allBalances']);
    Route::apiResource('balances', BalanceController::class);

    // Transfer balance routes
    Route::get('/balance-transfers/search', [TransferBalanceController::class, 'search']);
    Route::get('/all-balance-transfers', [TransferBalanceController::class, 'allBalances']);
    Route::apiResource('balance-transfers', TransferBalanceController::class);

    // Balance transfers routes
    Route::get('/transactions/search', [TransactionController::class, 'searchTransactions']);
    Route::get('/transactions', [TransactionController::class, 'allTransactions']);

    // Client invoice payment routes
    Route::get('/payments/invoice/search', [InvoicePaymentController::class, 'search']);
    Route::apiResource('/payments/invoice', InvoicePaymentController::class);

    // Client non invoice payment routes
    Route::get('/payments/non-invoice/search', [NonInvoicePaymentController::class, 'search']);
    Route::apiResource('/payments/non-invoice', NonInvoicePaymentController::class);

    // Supplier payment routes
    Route::get('/supplier/{slug}/purchases', [SupplierController::class, 'specificSupplierPurchases']);
    Route::get('/payments/purchase/search', [PurchasePaymentController::class, 'search']);
    Route::apiResource('payments/purchase', PurchasePaymentController::class);

    // Supplier non purchase payment routes
    Route::get('/suppliers-for-nonpurchase-payments', [SupplierController::class, 'suppliersForNonPurchasePayments']);
    Route::get('/payments/non-purchase/search', [NonPurchasePaymentController::class, 'search']);
    Route::apiResource('/payments/non-purchase', NonPurchasePaymentController::class);

    // Loan authorities routes
    Route::get('/loan-authorities/search', [LoanAuthorityController::class, 'search']);
    Route::get('/all-loan-authorities', [LoanAuthorityController::class, 'allAuthorities']);
    Route::apiResource('loan-authorities', LoanAuthorityController::class);

    // Loan routes
    Route::get('/loans/search', [LoanController::class, 'search']);
    Route::get('/all-loans', [LoanController::class, 'allLoans']);
    Route::apiResource('loans', LoanController::class);

    // Loan payment routes
    Route::get('/loan-payments/search', [LoanPaymentController::class, 'search']);
    Route::apiResource('loan-payments', LoanPaymentController::class);

    // Asset types routes
    Route::get('/asset-types/search', [AssetTypeController::class, 'search']);
    Route::get('/all-asset-types', [AssetTypeController::class, 'allAssets']);
    Route::apiResource('asset-types', AssetTypeController::class);

    // Asset routes
    Route::get('/assets/search', [AssetController::class, 'search']);
    Route::apiResource('assets', AssetController::class);

    // Employee payroll routes
    Route::get('/payroll/search', [PayrollController::class, 'search']);
    Route::get('/all-payroll', [PayrollController::class, 'allPayroll']);
    Route::apiResource('payroll', PayrollController::class);

    // Client routes
    Route::get('/clients/search', [ClientController::class, 'search']);
    Route::get('/all-clients', [ClientController::class, 'allClients']);
    Route::get('/clients-for-noninvoice-payments', [ClientController::class, 'clientsForNonInvoicePayments']);
    Route::get('/client/invoices/{slug}', [ClientController::class, 'clientInvoices']);
    Route::post('/client/filter-invoices', [ClientController::class, 'filterClientInvoices']);
    Route::get('/client/{slug}/invoices', [ClientController::class, 'specificClientInvoices']);
    Route::get('/client/{slug}/ledger', [ClientController::class, 'specificClientLedger']);
    Route::apiResource('clients', ClientController::class);

    // client import csv routes
    Route::post('/client-import', [ClientController::class, 'import']);

    // client invoice routes
    Route::get('/client/{slug}/all-invoices', [ClientController::class, 'clientAllInvoices']);
    Route::get('/client/{slug}/all-invoices/search', [ClientController::class, 'searchClientInvoices']);
    // client invoice return routes
    Route::get('/client/{slug}/invoice-returns', [ClientController::class, 'clientInvoiceReturns']);
    Route::get('/client/{slug}/invoice-returns/search', [ClientController::class, 'searchClientInvoiceReturns']);
    // client invoice payment routes
    Route::get('/client/{slug}/invoice-payments', [ClientController::class, 'clientInvoicePayments']);
    Route::get('/client/{slug}/invoice-payments/search', [ClientController::class, 'searchClientInvoicePayments']);
    // Client non invoice payment routes
    Route::get('/client/{slug}/non-invoice-payments', [ClientController::class, 'clientNonInvoicePayments']);
    Route::get('/client/{slug}/non-invoice-payments/search', [ClientController::class, 'searchClientNonInvoicePayments']);

    // Supplier routes
    Route::get('/suppliers/search', [SupplierController::class, 'search']);
    Route::get('/all-suppliers', [SupplierController::class, 'allSuppliers']);
    Route::get('/supplier/purchases/{slug}', [SupplierController::class, 'supplierPurchases']);
    Route::get('/supplier/{slug}/ledger', [SupplierController::class, 'specificSupplierLedger']);
    Route::apiResource('suppliers', SupplierController::class);

    // client import csv routes
    Route::post('/supplier-import', [SupplierController::class, 'import']);

    // Supplier purchases
    Route::get('/purchases/supplier/{slug}', [SupplierController::class, 'purchasesBySupplier']);
    Route::get('/purchases/supplier/{slug}/search', [SupplierController::class, 'searchPurchasesBySupplier']);

    // Supplier purchase returns
    Route::get('/purchase-returns/supplier/{slug}', [SupplierController::class, 'purchaseReturnsBySupplier']);
    Route::get('/purchase-returns/supplier/{slug}/search', [SupplierController::class, 'searchPurchaseReturnsBySupplier']);

    // Supplier purchase payments
    Route::get('/payments/supplier/{slug}', [SupplierController::class, 'paymentsForSupplier']);
    Route::get('/payments/supplier/{slug}/search', [SupplierController::class, 'searchPaymentsForSupplier']);

    // Supplier non purchase transactions
    Route::get('/non-purchases/supplier/{slug}', [SupplierController::class, 'nonPurchaseTransForSupplier']);
    Route::get('/non-purchases/supplier/{slug}/search', [SupplierController::class, 'searchNonPurchaseTransForSupplier']);

    // Departments routes
    Route::get('/departments/search', [DepartmentController::class, 'search']);
    Route::get('/all-departments', [DepartmentController::class, 'allDepartments']);
    Route::apiResource('departments', DepartmentController::class);

    // Employee routes
    Route::get('/employees/search', [EmployeeController::class, 'search']);
    Route::get('/all-employees', [EmployeeController::class, 'allEmployees']);
    Route::apiResource('employees', EmployeeController::class);

    // Specific employee payroll
    Route::get('/employee-payroll/{slug}', [EmployeeController::class, 'employeePayroll']);
    Route::get('/employee-payroll/{slug}/search', [EmployeeController::class, 'searchEmployeePayroll']);

    // Specific employee salary increments
    Route::get('/employee-increments/{slug}', [EmployeeController::class, 'employeeIncrements']);
    Route::get('/employee-increments/{slug}/search', [EmployeeController::class, 'searchEmployeeIncrements']);

    // Employee salary increment routes
    Route::get('/increments/search', [EmpSalIncrementController::class, 'search']);
    Route::get('/all-increments', [EmpSalIncrementController::class, 'allIncrements']);
    Route::apiResource('increments', EmpSalIncrementController::class);

    // Product categories routes
    Route::get('/product-categories/search', [ProductCategoryController::class, 'search']);
    Route::get('/all-product-categories', [ProductCategoryController::class, 'allCategories']);
    Route::apiResource('product-categories', ProductCategoryController::class);

    // Product sub categories routes
    Route::get('/product-sub-categories/search', [ProSubCatController::class, 'search']);
    Route::get('/all-product-sub-categories', [ProSubCatController::class, 'allSubCategories']);
    Route::get('/pro-sub-categories-by-category/{slug}', [ProSubCatController::class, 'subCategoriesByCategory']);
    Route::get('/all-pro-sub-categories-by-category/{slug}', [ProSubCatController::class, 'allSubCategoriesByCategory']);
    Route::apiResource('product-sub-categories', ProSubCatController::class);

    // Product routes
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::get('/products/search-from-pos', [ProductController::class, 'searchFromPos']);
    Route::get('/all-products', [ProductController::class, 'allProducts']);
    Route::get('/all-products-paginated', [ProductController::class, 'allProductsPaginated']);
    Route::get('/all-products-for-select', [ProductController::class, 'allProductsForSelect']);
    Route::get('/generate-itemcode', [ProductController::class, 'generateItemCode']);
    Route::get('/products-by-sub-categories/{catSlug}/{subCatSlug}', [ProductController::class, 'productsBySubCategory']);
    Route::get('/all-products-by-sub-categories/{catSlug}/{subCatSlug}', [ProductController::class, 'allProductsBySubCategory']);
    Route::apiResource('products', ProductController::class);
    Route::post('/product-import', [ProductController::class, 'import']);
    Route::get('/product-import-template', [ProductController::class, 'importTemplate']);

    // Inventory route
    Route::get('/inventory', [InventoryController::class, 'allInventory']);
    Route::get('/inventory/search', [InventoryController::class, 'searchInventory']);
    Route::get('/non-zero-inventory', [InventoryController::class, 'nonZeroInventory']);
    Route::get('/non-zero-inventory/search', [InventoryController::class, 'searchNonZeroInventory']);
    Route::get('/inventory-history/{slug}', [InventoryController::class, 'inventoryHistoryByItem']);

    // Inventory adjustment routes
    Route::get('/inventory-adjustments/search', [InventoryAdjustmentController::class, 'search']);
    Route::apiResource('/inventory-adjustments', InventoryAdjustmentController::class);

    // Report routes
    Route::get('/reports/balance-sheet', [ReportController::class, 'balanceSheet']);
    Route::post('/reports/summery', [ReportController::class, 'summeryReport']);
    Route::post('/reports/profit-loss', [ReportController::class, 'profitLossReport']);
    Route::post('/reports/expenses', [ReportController::class, 'expenseReport']);
    Route::post('/reports/items', [ReportController::class, 'itemsReport']);
    Route::post('/reports/inventory', [ReportController::class, 'inventoryReport']);

    // update profile
    Route::post('/update-profile', [DashboardController::class, 'updateProfile']);
    // database backup
    Route::post('/backup', [DashboardController::class, 'databaseBackup']);
});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);

    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('password/reset', [ResetPasswordController::class, 'reset']);

    Route::post('email/verify/{user}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('email/resend', [VerificationController::class, 'resend']);

    Route::post('oauth/{driver}', [OAuthController::class, 'redirect']);
    Route::get('oauth/{driver}/callback', [OAuthController::class, 'handleCallback'])->name('oauth.callback');
});