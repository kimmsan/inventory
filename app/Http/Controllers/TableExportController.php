<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Role;
use App\Models\Unit;
use App\Models\Asset;
use App\Models\Brand;
use App\Models\Client;
use App\Models\Account;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payroll;
use App\Models\Product;
use App\Models\VatRate;
use App\Models\Currency;
use App\Models\Employee;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\AssetType;
use App\Models\Quotation;
use App\Models\Department;
use App\Models\LoanPayment;
use App\Models\InvoiceReturn;
use App\Models\LoanAuthority;
use App\Models\PaymentMethod;
use App\Models\BalanceTansfer;
use App\Models\InvoicePayment;
use App\Models\PurchaseReturn;
use App\Models\ExpenseCategory;
use App\Models\ProductCategory;
use App\Models\PurchasePayment;
use App\Models\SalaryIncrement;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\NonInvoicePayment;
use App\Models\AccountTransaction;
use App\Models\ExpenseSubCategory;
use App\Models\NonPurchasePayment;
use App\Models\ProductSubCategory;
use App\Models\InventoryAdjustment;


class TableExportController extends Controller
{
    // return all brands pdf
    public function brandsPDF()
    {
        // retrieve all records from db
        $data = Brand::latest()->get()->toArray();
        // share data to view
        view()->share('brands', $data);
        $pdf = PDF::loadView('pdf.brands', $data);
        // download PDF file with download method
        return $pdf->download('brands-list.pdf');
    }

    // return all currencies pdf
    public function currenciesPDF()
    {
        // retreive all records from db
        $data = Currency::latest()->get()->toArray();
        // share data to view
        view()->share('currencies', $data);
        $pdf = PDF::loadView('pdf.currencies', $data);
        // download PDF file with download method
        return $pdf->download('currencies-list.pdf');
    }

    // return all units pdf
    public function unitsPDF()
    {
        // retreive all records from db
        $data = Unit::latest()->get()->toArray();
        // share data to view
        view()->share('units', $data);
        $pdf = PDF::loadView('pdf.units', $data);
        // download PDF file with download method
        return $pdf->download('units-list.pdf');
    }

    // return all vat rates pdf
    public function vatRatesPDF()
    {
        // retreive all records from db
        $data = VatRate::latest()->get()->toArray();
        // share data to view
        view()->share('vatRates', $data);
        $pdf = PDF::loadView('pdf.vat-rates', $data);
        // download PDF file with download method
        return $pdf->download('vat-rates-list.pdf');
    }

    // return all roles pdf
    public function rolesPDF()
    {
        // retreive all records from db
        $data = Role::latest()->get()->toArray();
        // share data to view
        view()->share('roles', $data);
        $pdf = PDF::loadView('pdf.roles', $data);
        // download PDF file with download method
        return $pdf->download('roles-list.pdf');
    }

    // return all payment methods pdf
    public function paymentMethodsPDF()
    {
        // retreive all records from db
        $data = PaymentMethod::latest()->get()->toArray();
        // share data to view
        view()->share('paymentMethods', $data);
        $pdf = PDF::loadView('pdf.payment-methods', $data);
        // download PDF file with download method
        return $pdf->download('payment-methods-list.pdf');
    }

    // return expense category pdf
    public function expCategoriesPDF()
    {
        // retreive all records from db
        $data = ExpenseCategory::latest()->get()->toArray();
        // share data to view
        view()->share('categories', $data);
        $pdf = PDF::loadView('pdf.exp-categories', $data);
        // download PDF file with download method
        return $pdf->download('exp-categories-list.pdf');
    }

    // return expense sub category pdf
    public function expSubCategoriesPDF()
    {
        // retreive all records from db
        $data = ExpenseSubCategory::with('expCategory')->latest()->get()->toArray();
        // share data to view
        view()->share('categories', $data);
        $pdf = PDF::loadView('pdf.exp-sub-categories', $data);
        // download PDF file with download method
        return $pdf->download('exp-sub-categories-list.pdf');
    }

    // return expense pdf
    public function expensesPDF()
    {
        // retreive all records from db
        $data = Expense::with('expSubCategory.expCategory', 'expTransaction.cashbookAccount')->latest()->get()->toArray();
        // share data to view
        view()->share('expenses', $data);
        $pdf = PDF::loadView('pdf.expenses', $data)->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('expenses-list.pdf');
    }

    // return purchases pdf
    public function purchasesPDF()
    {
        // retreive all records from db
        $data = Purchase::with('supplier')->latest()->get()->toArray();
        // share data to view
        view()->share('purchases', $data);
        $pdf = PDF::loadView('pdf.purchases', $data)->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('purchases-list.pdf');
    }

    // return purchase returns pdf
    public function purchaseReturnsPDF()
    {
        // retreive all records from db
        $data = PurchaseReturn::with('purchase.supplier')->latest()->get()->toArray();
        // share data to view
        view()->share('returns', $data);
        $pdf = PDF::loadView('pdf.purchase-returns', $data)->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('purchase-returns-list.pdf');
    }

    // return quotation pdf
    public function quotationsPDF()
    {
        // retreive all records from db
        $data = Quotation::with('client')->latest()->get()->toArray();
        // share data to view
        view()->share('quotations', $data);
        $pdf = PDF::loadView('pdf.quotations', $data);
        // download PDF file with download method
        return $pdf->download('quotation-list.pdf');
    }

    // return invoice pdf
    public function invoicePDF()
    {
        // retreive all records from db
        $data = Invoice::with('client', 'invoicePayments')->latest()->get()->toArray();
        // share data to view
        view()->share('invoices', $data);
        $pdf = PDF::loadView('pdf.invoices', $data)->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('invoice-list.pdf');
    }

    // return invoice pdf
    public function invoiceReturnPDF()
    {
        // retreive all records from db
        $data = InvoiceReturn::with('invoice.client')->latest()->get()->toArray();
        // share data to view
        view()->share('returns', $data);
        $pdf = PDF::loadView('pdf.invoice-returns', $data)->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('invoice-return-list.pdf');
    }

    // return accounts pdf
    public function accountsPDF()
    {
        // retreive all records from db
        $data = Account::with('balanceTransactions')->latest()->get()->toArray();
        // share data to view
        view()->share('accounts', $data);
        $pdf = PDF::loadView('pdf.accounts', $data)->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('account-list.pdf');
    }

    // return account transaction pdf
    public function accountTransactionsPDF($slug)
    {
        $account = Account::where('slug', $slug)->first();
        $data = AccountTransaction::with('cashbookAccount', 'user')->where('account_id', $account->id)->orderBy('created_at', 'asc')->get()->toArray();
        // share data to view
        view()->share('transactions', $data);
        $pdf = PDF::loadView('pdf.transactions', $data)->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download($account->account_number.'-ledger.pdf');
    }

    // return non invoice add balances pdf
    public function nonInvoiceBalancesPDF()
    {
        $data = AccountTransaction::with('cashbookAccount')->where('reason', 'LIKE', 'Non invoice balance added%')->latest()->get()->toArray();
        // share data to view
        view()->share('balances', $data);
        $pdf = PDF::loadView('pdf.non-invoice-balances', $data)->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('account-transaction-list.pdf');
    }

    // return transfer balances pdf
    public function transferBalancesPDF()
    {
        $data = BalanceTansfer::with('debitTransaction.cashbookAccount', 'creditTransaction.cashbookAccount', 'user')->latest()->get()->toArray();
        // share data to view
        view()->share('transfers', $data);
        $pdf = PDF::loadView('pdf.transfer-balances', $data)->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('balance-transfer-list.pdf');
    }

    // return transactions PDF
    public function transactionsPDF()
    {
        $data = AccountTransaction::with('cashbookAccount', 'user')->latest()->get()->toArray();
        // share data to view
        view()->share('transactions', $data);
        $pdf = PDF::loadView('pdf.all-transactions', $data)->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('transaction-list.pdf');
    }

    // return client non invoice payment pdf
    public function nonInvoicePaymentsPDF()
    {
        $data = NonInvoicePayment::with('client', 'paymentTransaction.cashbookAccount')->latest()->get()->toArray();
        // share data to view
        view()->share('payments', $data);
        $pdf = PDF::loadView('pdf.non-invoice-payments', $data)->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('non-invoice-payment-list.pdf');
    }

    // return client invoice payment pdf
    public function invoicePaymentsPDF()
    {
        $data = InvoicePayment::with('invoice.client', 'invoicePaymentTransaction.cashbookAccount', 'user')->latest()->get()->toArray();
        // share data to view
        view()->share('payments', $data);
        $pdf = PDF::loadView('pdf.invoice-payments', $data)->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('invoice-payment-list.pdf');
    }

    // return supplier non purchase payment pdf
    public function nonPurchasePaymentsPDF()
    {
        $data = NonPurchasePayment::with('supplier', 'paymentTransaction.cashbookAccount')->latest()->get()->toArray();
        // share data to view
        view()->share('payments', $data);
        $pdf = PDF::loadView('pdf.non-purchase-payments', $data)->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('non-purchase-payment-list.pdf');
    }

    // return supplier purchase payment pdf
    public function purchasePaymentsPDF()
    {
        $data = PurchasePayment::with('purchase.supplier', 'purchasePaymentTransaction.cashbookAccount', 'user')->latest()->get()->toArray();
        // share data to view
        view()->share('payments', $data);
        $pdf = PDF::loadView('pdf.purchase-payments', $data)->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('purchase-payments-list.pdf');
    }

    // return loan authorities pdf
    public function loanAuthoritiesPDF()
    {
        // retreive all records from db
        $data = LoanAuthority::latest()->get()->toArray();
        // share data to view
        view()->share('loanAuthorities', $data);
        $pdf = PDF::loadView('pdf.loan-authorities', $data)->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('loan-authority-list.pdf');
    }

    // return loans pdf
    public function loansPDF()
    {
        // retreive all records from db
        $data = Loan::with('loanAuthority', 'loanPayments', 'loanTransaction.cashbookAccount')->latest()->get();
        // share data to view
        view()->share('loans', $data);
        $pdf = PDF::loadView('pdf.loans', compact('data'))->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('loan-list.pdf');
    }

    // return loan payments pdf
    public function loanPaymentsPDF()
    {
        // retreive all records from db
        $data = LoanPayment::with('loan.loanAuthority', 'loanPaymentTransaction.cashbookAccount')->latest()->get();
        // share data to view
        view()->share('loanPayments', $data);
        $pdf = PDF::loadView('pdf.loan-payments', compact('data'))->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('loan-payment-list.pdf');
    }

    // return asset types pdf
    public function assetTypesPDF()
    {
        // retreive all records from db
        $data = AssetType::latest()->get()->toArray();
        // share data to view
        view()->share('assetTypes', $data);
        $pdf = PDF::loadView('pdf.asset-types', $data);
        // download PDF file with download method
        return $pdf->download('asset-type-list.pdf');
    }

    // return assets pdf
    public function assetsPDF()
    {
        // retreive all records from db
        $data = Asset::with('assetType')->latest()->get()->toArray();
        // share data to view
        view()->share('assets', $data);
        $pdf = PDF::loadView('pdf.assets', $data);
        // download PDF file with download method
        return $pdf->download('asset-list.pdf');
    }

    // return payroll pdf
    public function payrollPDF()
    {
        // retreive all records from db
        $data = Payroll::with('employee.department', 'payrollTransaction.cashbookAccount')->latest()->get()->toArray();
        // share data to view
        view()->share('allPayroll', $data);
        $pdf = PDF::loadView('pdf.payroll', $data)->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('payroll-list.pdf');
    }

    // return clients pdf
    public function clientsPDF()
    {
        // retreive all records from db
        $data = Client::latest()->get()->toArray();
        // share data to view
        view()->share('clients', $data);
        $pdf = PDF::loadView('pdf.clients', $data)->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('client-list.pdf');
    }

    // return suppliers pdf
    public function suppliersPDF()
    {
        // retreive all records from db
        $data = Supplier::latest()->get()->toArray();
        // share data to view
        view()->share('suppliers', $data);
        $pdf = PDF::loadView('pdf.suppliers', $data)->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('supplier-list.pdf');
    }

    // return departments pdf
    public function departmentsPDF()
    {
        // retreive all records from db
        $data = Department::latest()->get()->toArray();
        // share data to view
        view()->share('departments', $data);
        $pdf = PDF::loadView('pdf.departments', $data);
        // download PDF file with download method
        return $pdf->download('department-list.pdf');
    }

    // return employees pdf
    public function employeesPDF()
    {
        // retreive all records from db
        $data = Employee::with('department', 'user')->latest()->get()->toArray();
        // share data to view
        view()->share('employees', $data);
        $pdf = PDF::loadView('pdf.employees', $data)->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('employee-list.pdf');
    }

    // return increments pdf
    public function incrementsPDF()
    {
        // retreive all records from db
        $data = SalaryIncrement::with('employee.department')->latest()->get()->toArray();
        // share data to view
        view()->share('salIncrements', $data);
        $pdf = PDF::loadView('pdf.increments', $data)->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('increment-list.pdf');
    }

    // return product categories pdf
    public function productCategoriesPDF()
    {
        // retreive all records from db
        $data = ProductCategory::latest()->get()->toArray();
        // share data to view
        view()->share('productCategories', $data);
        $pdf = PDF::loadView('pdf.product-categories', $data);
        // download PDF file with download method
        return $pdf->download('product-category-list.pdf');
    }

    // return product sub categories pdf
    public function productSubCategoriesPDF()
    {
        // retreive all records from db
        $data = ProductSubCategory::with('category')->latest()->get()->toArray();
        // share data to view
        view()->share('productsubCategories', $data);
        $pdf = PDF::loadView('pdf.product-sub-categories', $data);
        // download PDF file with download method
        return $pdf->download('product-sub-category-list.pdf');
    }

    // return product sub categories pdf
    public function productsPDF()
    {
        // retreive all records from db
        $data = Product::with('proSubCategory.category', 'productUnit')->latest()->get();
        // share data to view
        view()->share('products', $data);
        $pdf = PDF::loadView('pdf.products', compact('data'))->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('product-list.pdf');
    }

    // return inventoryAdjustments PDF
    public function inventoryAdjustmentsPDF()
    {
        // retreive all records from db
        $data = InventoryAdjustment::latest()->get();
        // share data to view
        view()->share('adjustments', $data);
        $pdf = PDF::loadView('pdf.adjustments', compact('data'))->setPaper('a4', 'landscape');
        // download PDF file with download method
        return $pdf->download('inventory-adjustment-list.pdf');
    }

    // return non zero inventory products
    public function nonZeroInventoryPDF()
    {
        // retreive all records from db
        $data = [];
        Product::where('inventory_count', '>', 0)
        ->with('proSubCategory.category', 'productUnit')
        ->orderBy('code', 'ASC')
        ->chunk(200, function ($products) use (&$data) {
            foreach ($products as $product) {
                // Process each product and add it to the $data array
                $data[] = $product;
            }
        });

        // share data to view
        view()->share('products', $data);
        $pdf = PDF::loadView('pdf.non-zero-inventory', compact('data'))->setPaper('a4', 'landscape');
        // download PDF file with download method

        return $pdf->stream('non-zero-inventory-list.pdf');
    }
}
