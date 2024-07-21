<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseResource;
use App\Http\Resources\ProductResource;
use App\Models\Account;
use App\Models\AdjustmentProduct;
use App\Models\Asset;
use App\Models\BalanceTansfer;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\InvoiceProduct;
use App\Models\InvoiceReturnProduct;
use App\Models\LoanAuthority;
use App\Models\LoanPayment;
use App\Models\NonInvoicePayment;
use App\Models\NonPurchasePayment;
use App\Models\Payroll;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Models\PurchaseProduct;
use App\Models\PurchaseReturnProduct;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:balance-sheet', ['only' => ['balanceSheet']]);
        $this->middleware('can:summary-report', ['only' => ['summeryReport']]);
        $this->middleware('can:profit-loss', ['only' => ['profitLossReport']]);
        $this->middleware('can:expense-report', ['only' => ['expenseReport']]);
        $this->middleware('can:item-report', ['only' => ['itemsReport']]);
        $this->middleware('can:inventory-report', ['only' => ['inventoryReport']]);
    }

    // return balance sheet data
    public function balanceSheet()
    {
        // total assets
        $assets = Asset::where('status', 1)->get()->sum('calculated_value');

        // inventory value
        $inventoryValue = Product::where('status', 1)->get()->sum(function ($currentRow) {
            return $currentRow->purchase_price * $currentRow->inventory_count;
        });

        // client dues
        $sales = Invoice::where('status', 1)->sum('sub_total');
        $salesTransportCost = Invoice::where('status', 1)->sum('transport');
        $salesDiscount = Invoice::where('status', 1)->sum('discount');
        $salesTax = Invoice::where('status', 1)->get()->sum('calculated_tax');
        $totalSales = $sales - $salesDiscount + $salesTransportCost + $salesTax;

        // invoice due
        $invoiceTotalPaid = InvoicePayment::where('status', 1)->sum('amount');
        $invoiceDue = $totalSales - $invoiceTotalPaid;

        // non invoice due
        $nonInvoiceTotal = NonInvoicePayment::where('type', 0)->where('status', 1)->sum('amount');
        $nonInvoicePaid = NonInvoicePayment::where('type', 1)->where('status', 1)->sum('amount');
        $nonInvoiceDue = $nonInvoiceTotal - $nonInvoicePaid;

        $clientTotalDue = $invoiceDue + $nonInvoiceDue;

        // bank balance
        $bankBalance = Account::where('status', 1)->get()->sum('available_balance');

        // supplier dues
        $purchases = Purchase::where('status', 1)->sum('sub_total');
        $purchaseTransportCost = Purchase::where('status', 1)->sum('transport');
        $purchaseDiscount = Purchase::where('status', 1)->sum('discount');
        $purchaseTax = Purchase::where('status', 1)->get()->sum('calculated_tax');
        $totalPurchases = $purchases - $purchaseDiscount + $purchaseTransportCost + $purchaseTax;

        // purchase due
        $purchaseTotalPaid = PurchasePayment::where('status', 1)->sum('amount');
        $purchaseDue = $totalPurchases - $purchaseTotalPaid;

        // non purchase due
        $nonPurchaseTotal = NonPurchasePayment::where('type', 0)->where('status', 1)->sum('amount');
        $nonPurchasePaid = NonPurchasePayment::where('type', 1)->where('status', 1)->sum('amount');
        $nonPurchaseDue = $nonPurchaseTotal - $nonPurchasePaid;

        // supplier due
        $supplierTotalDue = $purchaseDue + $nonPurchaseDue;

        // loan due
        $loanDue = LoanAuthority::where('status', 1)->get()->sum('due');

        $buisnessTotal = $assets + $inventoryValue + $clientTotalDue + $bankBalance;
        $liabilities = $loanDue + $supplierTotalDue;
        $totalAsset = $buisnessTotal - $liabilities;

        return [
            'assets' => round($assets, 2),
            'inventoryValue' => round($inventoryValue, 2),
            'clientTotalDue' => round($clientTotalDue, 2),
            'bankBalance' => round($bankBalance, 2),
            'supplierDue' => round($supplierTotalDue, 2),
            'loanDue' => round($loanDue, 2),
            'buisnessTotal' => round($buisnessTotal, 2),
            'liabilities' => round($liabilities, 2),
            'totalAsset' => round($totalAsset, 2),
        ];
    }

    // return summery report data
    public function summeryReport(Request $request)
    {
        // validate request
        $this->validate($request, [
            'month' => 'required|integer|min:0|max:12',
            'year' => 'required|integer',
        ]);

        $month = $request->month;
        $year = $request->year;
        $dateObj = DateTime::createFromFormat('!m', $month);
        $monthName = $dateObj->format('F');

        // general expenses for a given month and year
        $expenses = Expense::select(DB::raw('SUM(account_transactions.amount) As expAmount'))
            ->leftJoin('account_transactions', 'account_transactions.id', '=', 'expenses.transaction_id')
            ->where('expenses.status', 1)
            ->whereYear('expenses.date', '=', $year)
            ->whereMonth('expenses.date', '=', $month)
            ->first();

        // payrolls for a given month and year
        $payrolls = Payroll::select(DB::raw('SUM(account_transactions.amount) As payrollAmount'))
            ->leftJoin('account_transactions', 'account_transactions.id', '=', 'payrolls.transaction_id')
            ->where('payrolls.status', 1)
            ->where('payrolls.salary_month', '=', $monthName)
            ->whereYear('payrolls.salary_date', '=', $year)
            ->first();

        // loan interests for a given month and year
        $loanInterest = LoanPayment::where('status', 1)->whereYear('date', '=', $year)->whereMonth('date', '=', $month)->sum('interest');

        $numOfDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $fromDate = $year.'-'.$month.'-01';
        $toDate = $year.'-'.$month.'-'.$numOfDays;
        // assets depreciation for a given month and year
        $assetDepriciation = DB::select('SELECT Sum( case when NumberOfDays > 0 then  new_assets.daily_depreciation * NumberOfDays else 0 end) as total_dep FROM ( SELECT daily_depreciation, ( CASE WHEN date < "'.$fromDate.'" && expire_date > "'.$toDate.'" THEN DATEDIFF("'.$toDate.'", "'.$fromDate.'") WHEN expire_date > "'.$fromDate.'" && expire_date < "'.$toDate.'" THEN DATEDIFF("'.$fromDate.'", expire_date) ELSE DATEDIFF("'.$toDate.'", date) END) AS NumberOfDays FROM assets WHERE depreciation = 1 AND status = 1 AND expire_date >= "'.$fromDate.'" ) AS new_assets');

        // Total purchases for a given month and year
        $purchases = Purchase::where('status', 1)->whereYear('purchase_date', '=', $year)->whereMonth('purchase_date', '=', $month)->get();
        $totalPurchase = $purchases->sum('sub_total') - $purchases->sum('discount') + $purchases->sum('transport') + $purchases->sum('total_tax');

        // opening balances for a given month and year
        $openingBalances = DB::select('SELECT A.account_number, A.bank_name, SUM(IF(`type`=1, `amount`, 0))-SUM(IF(`type`=0, `amount`, 0)) AS `current_balance`
        FROM `accounts`  as A
        LEFT  JOIN account_transactions as T ON A.id = T.account_id
        AND T.status = 1 AND DATE(T.transaction_date) < "'.$fromDate.'"  GROUP BY A.id');

        // closing balances for a given month and year
        $closingBalances = DB::select('SELECT A.account_number, A.bank_name, SUM(IF(`type`=1, `amount`, 0))-SUM(IF(`type`=0, `amount`, 0)) AS `current_balance`
        FROM `accounts`  as A
        LEFT  JOIN account_transactions as T ON A.id = T.account_id
        AND T.status = 1 AND DATE(T.transaction_date) < "'.$toDate.'"  GROUP BY A.id');

        // invoice salesfor a given month and year
        $invoiceSales = Invoice::where('status', 1)->whereYear('invoice_date', '=', $year)->whereMonth('invoice_date', '=', $month)->sum('sub_total');

        // invoice due a given month and year
        $invoiceTotalPaid = InvoicePayment::where('status', 1)->whereYear('date', '=', $year)->whereMonth('date', '=', $month)->sum('amount');
        $invoiceDue = $invoiceSales - $invoiceTotalPaid;

        // account collection balances for a given month and year
        $accountCollections = DB::select('SELECT accounts.account_number, accounts.bank_name, SUM(IF(`type`= 1, `amount`, 0)) AS `total_collection`
        FROM `account_transactions`
        JOIN accounts ON accounts.id = account_transactions.account_id
        WHERE account_transactions.status = 1 AND MONTH(transaction_date)= "'.$month.'" AND YEAR(transaction_date)="'.$year.'" GROUP BY account_transactions.account_id');

        // balance transfer
        $balanceTransfers = BalanceTansfer::with('debitTransaction.cashbookAccount', 'creditTransaction.cashbookAccount')->where('status', 1)->whereYear('date', '=', $year)->whereMonth('date', '=', $month)->get();

        return [
            'openingBalances' => $openingBalances,
            'closingBalances' => $closingBalances,
            'accountCollections' => $accountCollections,
            'balanceTransfers' => $balanceTransfers,
            'invoiceSales' => round($invoiceSales, 2),
            'invoiceDue' => round($invoiceDue, 2),
            'expenses' => round($expenses->expAmount, 2),
            'payrolls' => round($payrolls->payrollAmount, 2),
            'loanInterest' => round($loanInterest, 2),
            'assetDepriciation' => round($assetDepriciation[0]->total_dep, 2),
            'totalPurchase' => round($totalPurchase, 0),
            'monthName' => $monthName,
            'year' => $year,
        ];
    }

    // return profit loss report data
    public function profitLossReport(Request $request)
    {
        if ($request->reportType == 1) {
            $products = [];
            $inventoryOuts = InvoiceProduct::with('invoice', 'product')->whereHas('invoice', function ($newQuery) use ($request) {
                $newQuery->where('status', 1)->whereBetween('invoice_date', [$request->fromDate, $request->toDate]);
            })->groupBy('product_id')
                ->selectRaw('sum(quantity) as sumQty, product_id')
                ->selectRaw('sum(purchase_price * quantity) as purchasePrice, product_id')
                ->selectRaw('sum(sale_price * quantity) as salePrice, product_id')
                ->get();

            foreach ($inventoryOuts  as $key => $inventoryOut) {
                $returnQty = InvoiceReturnProduct::with('invoiceReturn.invoice')->whereHas('invoiceReturn', function ($newQuery) use ($request) {
                    $newQuery->whereHas('invoice', function ($anotherQuery) use ($request) {
                        $anotherQuery->where('status', 1)->whereBetween('invoice_date', [$request->fromDate, $request->toDate]);
                    });
                })->where('product_id', $inventoryOut->product->id)->sum('quantity');

                $currentQty = $inventoryOut->sumQty - $returnQty;
                $avgPurchasePrice = $inventoryOut->purchasePrice / $inventoryOut->sumQty;
                $avgSalePrice = $inventoryOut->salePrice / $inventoryOut->sumQty;
                $profitOrLoss = ($avgSalePrice * $currentQty) - ($avgPurchasePrice * $currentQty);

                $products[$key]['itemCode'] = $inventoryOut->product->code;
                $products[$key]['code'] = $inventoryOut->product->code;
                $products[$key]['itemName'] = $inventoryOut->product->name;
                $products[$key]['avgPurchasePrice'] = round($avgPurchasePrice, 2);
                $products[$key]['avgSalePrice'] = round($avgSalePrice, 2);
                $products[$key]['invoiceQty'] = $inventoryOut->sumQty;
                $products[$key]['currentQty'] = $currentQty;
                $products[$key]['returnQty'] = $returnQty > 0 ? $returnQty : 0;
                $products[$key]['profitOrLoss'] = round($profitOrLoss, 2);
            }

            return [
                'type' => 1,
                'reportData' => $products,
            ];
        } else {
            $fromDate = date_format((date_create($request->fromDate)), 'Y-m-d');
            $toDate = date_format((date_create($request->toDate)), 'Y-m-d');

            // total sales between a given date range
            $totalSales = Invoice::where('status', 1)->whereBetween('invoice_date', [$request->fromDate, $request->toDate])->sum('sub_total');

            // cost of goods sold between a given date range
            $invoicePurchasePrice = InvoiceProduct::with('invoice')->whereHas('invoice', function ($newQuery) use ($request) {
                $newQuery->where('status', 1)->whereBetween('invoice_date', [$request->fromDate, $request->toDate]);
            })->get()->sum(function ($row) {
                return $row->purchase_price * $row->quantity;
            });

            $returnPurchasePrice = InvoiceReturnProduct::with('invoiceReturn.invoice')->whereHas('invoiceReturn', function ($newQuery) use ($request) {
                $newQuery->whereHas('invoice', function ($anotherQuery) use ($request) {
                    $anotherQuery->where('status', 1)->whereBetween('invoice_date', [$request->fromDate, $request->toDate]);
                });
            })->get()->sum(function ($row) {
                return $row->purchase_price * $row->quantity;
            });
            $costOfGoodsSold = $invoicePurchasePrice - $returnPurchasePrice;

            // inventory positive adjustment
            $posAdjustment = AdjustmentProduct::with('inventoryAdjustment')->whereHas('inventoryAdjustment', function ($newQuery) use ($request) {
                $newQuery->where('status', 1)->where('type', 1)->whereBetween('date', [$request->fromDate, $request->toDate]);
            })->get()->sum(function ($row) {
                return $row->purchase_price * $row->quantity;
            });

            // inventory negative adjustment
            $negAdjustment = AdjustmentProduct::with('inventoryAdjustment')->whereHas('inventoryAdjustment', function ($newQuery) use ($request) {
                $newQuery->where('status', 1)->where('type', 0)->whereBetween('date', [$request->fromDate, $request->toDate]);
            })->get()->sum(function ($row) {
                return $row->purchase_price * $row->quantity;
            });

            // general expenses between a given date range
            $expenses = Expense::select(DB::raw('SUM(account_transactions.amount) As expAmount'))
                ->leftJoin('account_transactions', 'account_transactions.id', '=', 'expenses.transaction_id')
                ->where('expenses.status', 1)->whereBetween('expenses.date', [$request->fromDate, $request->toDate])
                ->first();

            // payrolls between a given date range
            $payrolls = Payroll::select(DB::raw('SUM(account_transactions.amount) As payrollAmount'))
                ->leftJoin('account_transactions', 'account_transactions.id', '=', 'payrolls.transaction_id')
                ->where('payrolls.status', 1)->whereBetween('payrolls.salary_date', [$request->fromDate, $request->toDate])
                ->first();

            // loan interests between a given date range
            $loanInterest = LoanPayment::where('status', 1)->whereBetween('date', [$request->fromDate, $request->toDate])->sum('interest');

            // assets depreciation between a given date range
            $assetDepriciation = DB::select('SELECT Sum(case when NumberOfDays > 0 then  new_assets.daily_depreciation * NumberOfDays else 0 end) as total_dep FROM ( SELECT daily_depreciation, ( CASE WHEN date < "'.$fromDate.'" && expire_date > "'.$toDate.'" THEN DATEDIFF("'.$toDate.'", "'.$fromDate.'") WHEN expire_date > "'.$fromDate.'" && expire_date < "'.$toDate.'" THEN DATEDIFF("'.$fromDate.'", expire_date) ELSE DATEDIFF("'.$toDate.'", date) END) AS NumberOfDays FROM assets WHERE depreciation = 1 AND status = 1 AND expire_date >= "'.$fromDate.'" ) AS new_assets');

            $grossProfitOrLoss = round(($totalSales + $posAdjustment - ($costOfGoodsSold + $negAdjustment)), 2);
            $totalExpense = round(($expenses->expAmount + $payrolls->payrollAmount + $loanInterest + $assetDepriciation[0]->total_dep), 2);
            $netProfitOrLoss = round(($grossProfitOrLoss - $totalExpense), 2);

            $data = [[
                'totalSales' => round($totalSales, 2),
                'costOfGoodsSold' => round($costOfGoodsSold, 2),
                'posAdjustment' => round($posAdjustment, 2),
                'negAdjustment' => round($negAdjustment, 2),
                'totalAdjustment' => round($posAdjustment - $negAdjustment, 2),
                'expenseAmount' => round($expenses->expAmount, 2),
                'payrollAmount' => round($payrolls->payrollAmount, 2),
                'loanInterest' => round($loanInterest, 2),
                'assetDepriciation' => round($assetDepriciation[0]->total_dep, 2),
                'grossProfitOrLoss' => round($grossProfitOrLoss, 2),
                'totalExpense' => round($totalExpense, 2),
                'netProfitOrLoss' => round($netProfitOrLoss, 2),
            ]];

            return [
                'type' => 0,
                'reportData' => $data,
            ];
        }
    }

    //return expense report data
    public function expenseReport(Request $request)
    {
        // validate request
        $this->validate($request, [
            'category' => 'required',
            'subCategory' => ($request->category && $request->category['id'] != 0) ? 'required' : 'nullable',
        ]);
        $expenses = '';
        if (isset($request->category) && isset($request->subCategory)) {
            if ($request->subCategory['id'] != 0) {
                $expenses = Expense::with('expSubCategory.expCategory', 'expTransaction.cashbookAccount', 'user')->where('sub_cat_id', $request->subCategory['id'])->whereBetween('date', [$request->fromDate, $request->toDate])->get();
            } else {
                $expenses = Expense::with('expSubCategory.expCategory', 'expTransaction.cashbookAccount')->whereBetween('date', [$request->fromDate, $request->toDate])
                    ->whereHas('expSubCategory', function ($newQuery) use ($request) {
                        $newQuery->whereHas('expCategory', function ($newQuery) use ($request) {
                            $newQuery->where('id', $request->category['id']);
                        });
                    })->get();
            }
        } else {
            $expenses = Expense::with('expSubCategory.expCategory', 'expTransaction.cashbookAccount', 'user')->whereBetween('date', [$request->fromDate, $request->toDate])->get();
        }

        return ExpenseResource::collection($expenses);
    }

    // return items report data
    public function itemsReport(Request $request)
    {
        // validate request
        $this->validate($request, [
            'productName' => 'required',
        ]);

        try {
            $product = Product::where('slug', $request->productName['slug'])->with('proSubCategory.category', 'productUnit')->first();

            // stock ins
            $purchaseIns = PurchaseProduct::with('purchase.supplier')->where('product_id', $product->id)->whereHas('purchase', function ($newQuery) use ($request) {
                $newQuery->whereBetween('purchase_date', [$request->fromDate, $request->toDate]);
            })->get();

            $invoiceReturnIns = InvoiceReturnProduct::with('invoiceReturn.invoice.client')->where('product_id', $product->id)->whereHas('invoiceReturn', function ($newQuery) use ($request) {
                $newQuery->whereBetween('date', [$request->fromDate, $request->toDate]);
            })->get();

            $adjutmentIns = AdjustmentProduct::with('inventoryAdjustment')->where('product_id', $product->id)->where('type', 1)->whereHas('inventoryAdjustment', function ($newQuery) use ($request) {
                $newQuery->whereBetween('date', [$request->fromDate, $request->toDate]);
            })->get();

            $stockIns = [];
            // purchases
            foreach ($purchaseIns as $key => $inventoryIn) {
                $stockIns[$key]['quantity'] = $inventoryIn->quantity;
                $stockIns[$key]['date'] = $inventoryIn->purchase->purchase_date;
                $stockIns[$key]['supplier'] = $inventoryIn->purchase->supplier->name;
                $stockIns[$key]['price'] = $inventoryIn->purchase_price;
                $stockIns[$key]['type'] = 'Purchase';
                $stockIns[$key]['purchaseNo'] = $inventoryIn->purchase->purchase_no;
                $stockIns[$key]['code'] = config('config.purchasePrefix').'-'.$inventoryIn->purchase->purchase_no;
            }

            $length = count($stockIns);
            // Invoice returns
            foreach ($invoiceReturnIns as $key => $inventoryIn) {
                $stockIns[$length]['quantity'] = $inventoryIn->quantity;
                $stockIns[$length]['date'] = $inventoryIn->invoiceReturn->date;
                $stockIns[$length]['client'] = $inventoryIn->invoiceReturn->invoice->client->name;
                $stockIns[$length]['price'] = $inventoryIn->purchase_price;
                $stockIns[$length]['type'] = 'Invoice Return';
                $stockIns[$length++]['code'] = config('config.invoiceReturnPrefix').'-'.$inventoryIn->invoiceReturn->return_no;
            }

            $length = count($stockIns);
            // Inventory adjustments
            foreach ($adjutmentIns as $key => $inventoryIn) {
                $stockIns[$length]['code'] = config('config.adjustmentPrefix').'-'.$inventoryIn->inventoryAdjustment->code;
                $stockIns[$length]['quantity'] = $inventoryIn->quantity;
                $stockIns[$length]['date'] = $inventoryIn->inventoryAdjustment->date;
                $stockIns[$length]['reason'] = $inventoryIn->inventoryAdjustment->reason;
                $stockIns[$length]['price'] = $inventoryIn->purchase_price;
                $stockIns[$length++]['type'] = 'Adjustment';
            }

            // stock outs
            $adjutmentOuts = AdjustmentProduct::with('inventoryAdjustment')->where('product_id', $product->id)->where('type', 0)->whereHas('inventoryAdjustment', function ($newQuery) use ($request) {
                $newQuery->whereBetween('date', [$request->fromDate, $request->toDate]);
            })->get();

            $inventoryOuts = InvoiceProduct::with('invoice.client')->where('product_id', $product->id)->whereHas('invoice', function ($newQuery) use ($request) {
                $newQuery->whereBetween('invoice_date', [$request->fromDate, $request->toDate]);
            })->get();

            $purchaseReturnOuts = PurchaseReturnProduct::with('purchaseReturn.purchase.supplier')->where('product_id', $product->id)->whereHas('purchaseReturn', function ($newQuery) use ($request) {
                $newQuery->whereBetween('date', [$request->fromDate, $request->toDate]);
            })->get();

            $stockOuts = [];
            // Invoice sales
            foreach ($inventoryOuts as $key => $inventoryOut) {
                $stockOuts[$key]['quantity'] = $inventoryOut->quantity;
                $stockOuts[$key]['invoiceNo'] = $inventoryOut->invoice->invoice_no;
                $stockOuts[$key]['date'] = $inventoryOut->invoice->invoice_date;
                $stockOuts[$key]['price'] = $inventoryOut->sale_price;
                $stockOuts[$key]['client'] = $inventoryOut->invoice->client->name;
                $stockOuts[$key]['code'] = config('config.invoicePrefix').'-'.$inventoryOut->invoice->invoice_no;
                $stockOuts[$key]['type'] = 'Invoice';
            }

            $length = count($stockOuts);
            // Inventory adjustments
            foreach ($adjutmentOuts as $key => $adjutmentOut) {
                $stockOuts[$length]['code'] = config('config.adjustmentPrefix').'-'.$adjutmentOut->inventoryAdjustment->code;
                $stockOuts[$length]['quantity'] = $adjutmentOut->quantity;
                $stockOuts[$length]['date'] = $adjutmentOut->inventoryAdjustment->date;
                $stockOuts[$length]['reason'] = $adjutmentOut->inventoryAdjustment->reason;
                $stockOuts[$length]['price'] = $adjutmentOut->purchase_price;
                $stockOuts[$length++]['type'] = 'Adjustment';
            }

            $length = count($stockOuts);
            // Purchase returns
            foreach ($purchaseReturnOuts as $key => $purchaseReturnOut) {
                $stockOuts[$length]['code'] = config('config.purchaseReturnPrefix').'-'.$purchaseReturnOut->purchaseReturn->code;
                $stockOuts[$length]['quantity'] = $purchaseReturnOut->quantity;
                $stockOuts[$length]['date'] = $purchaseReturnOut->purchaseReturn->date;
                $stockOuts[$length]['reason'] = $purchaseReturnOut->purchaseReturn->reason;
                $stockOuts[$length]['supplier'] = $purchaseReturnOut->purchaseReturn->purchase->supplier->name;
                $stockOuts[$length]['price'] = $purchaseReturnOut->purchase_price;
                $stockOuts[$length++]['type'] = 'Purchase Return';
            }

            return [
                'product' => new ProductResource($product),
                'stockIns' => $stockIns,
                'stockOuts' => $stockOuts,
            ];
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    // return inventory report data
    public function inventoryReport(Request $request)
    {
        // validate request
        $this->validate($request, [
            'category' => 'required',
            'subCategory' => 'required',
            'itemName' => 'required',
        ]);

        $allProducts = [];
        if (($request->category['slug'] == 'all' && $request->subCategory['slug'] == 'all' && $request->itemName['slug'] == 'all')) {
            $products = Product::orderBy('code', 'ASC')->get();
            $allProducts = $this->generateItemsArray($products, $request);
        } elseif (($request->category['slug'] != 'all' && $request->subCategory['slug'] == 'all' && $request->itemName['slug'] == 'all')) {
            $catId = $request->category['id'];
            $products = Product::with('proSubCategory.category')->whereHas('proSubCategory', function ($newQuery) use ($catId) {
                $newQuery->whereHas('category', function ($newQuery) use ($catId) {
                    $newQuery->where('id', $catId);
                });
            })->get();
            $allProducts = $this->generateItemsArray($products, $request);
        } elseif (($request->category['slug'] == 'all' && $request->subCategory['slug'] != 'all' && $request->itemName['slug'] == 'all') || ($request->category['slug'] != 'all' && $request->subCategory['slug'] != 'all' && $request->itemName['slug'] == 'all')) {
            $products = Product::where('sub_cat_id', $request->subCategory['id'])->orderBy('code', 'ASC')->get();
            $allProducts = $this->generateItemsArray($products, $request);
        } else {
            $products = Product::where('slug', $request->itemName['slug'])->with('proSubCategory.category', 'productUnit')->get();
            $allProducts = $this->generateItemsArray($products, $request);
        }

        return $allProducts;
    }

    // generate invetory items array
    public function generateItemsArray($products, $request)
    {
        $allProducts = [];
        foreach ($products as $key => $product) {
            // stock ins
            $purchaseIns = PurchaseProduct::with('purchase.supplier')->where('product_id', $product->id)->whereHas('purchase', function ($newQuery) use ($request) {
                $newQuery->whereBetween('purchase_date', [$request->fromDate, $request->toDate]);
            })->sum('quantity');

            $invoiceReturnIns = InvoiceReturnProduct::with('invoiceReturn.invoice.client')->where('product_id', $product->id)->whereHas('invoiceReturn', function ($newQuery) use ($request) {
                $newQuery->whereBetween('date', [$request->fromDate, $request->toDate]);
            })->sum('quantity');

            $adjutmentIns = AdjustmentProduct::with('inventoryAdjustment')->where('product_id', $product->id)->where('type', 1)->whereHas('inventoryAdjustment', function ($newQuery) use ($request) {
                $newQuery->whereBetween('date', [$request->fromDate, $request->toDate]);
            })->sum('quantity');

            // stock outs
            $adjutmentOuts = AdjustmentProduct::with('inventoryAdjustment')->where('product_id', $product->id)->where('type', 0)->whereHas('inventoryAdjustment', function ($newQuery) use ($request) {
                $newQuery->whereBetween('date', [$request->fromDate, $request->toDate]);
            })->sum('quantity');

            $inventoryOuts = InvoiceProduct::with('invoice.client')->where('product_id', $product->id)->whereHas('invoice', function ($newQuery) use ($request) {
                $newQuery->whereBetween('invoice_date', [$request->fromDate, $request->toDate]);
            })->sum('quantity');

            $purchaseReturnOuts = PurchaseReturnProduct::with('purchaseReturn.purchase.supplier')->where('product_id', $product->id)->whereHas('purchaseReturn', function ($newQuery) use ($request) {
                $newQuery->whereBetween('date', [$request->fromDate, $request->toDate]);
            })->sum('quantity');

            $stockIns = $purchaseIns + $invoiceReturnIns + $adjutmentIns;
            $stockOuts = $adjutmentOuts + $inventoryOuts + $purchaseReturnOuts;
            if ($purchaseIns > 0 || $invoiceReturnIns > 0 || $adjutmentIns > 0 || $adjutmentOuts > 0 || $inventoryOuts > 0 || $purchaseReturnOuts > 0) {
                $allProducts[$key]['productName'] = $product->name;
                $allProducts[$key]['productCode'] = $product->code;
                $allProducts[$key]['stockIn'] = $stockIns;
                $allProducts[$key]['stockOut'] = $stockOuts;
                $allProducts[$key]['availableStock'] = $product->inventory_count;
            }
        }

        return $allProducts;
    }
}
