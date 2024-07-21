<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccountTransactionResource;
use App\Models\AccountTransaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:transaction-history', ['only' => ['allTransactions', 'searchTransactions']]);
    }

    //return all transactions
    public function allTransactions(Request $request)
    {
        $transactions = AccountTransaction::with('cashbookAccount', 'user')->latest()->paginate($request->perPage);

        return AccountTransactionResource::collection($transactions);
    }

    // search and return transactions
    public function searchTransactions(Request $request)
    {
        $term = $request->term;
        $query = AccountTransaction::with('cashbookAccount', 'user');

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('transaction_date', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($term) {
            $query->where('reason', 'LIKE', '%'.$term.'%')
                ->orWhere('amount', 'LIKE', '%'.$term.'%')
                ->orWhereHas('cashbookAccount', function ($newQuery) use ($term) {
                    $newQuery->where('bank_name', 'LIKE', '%'.$term.'%')
                        ->orWhere('branch_name', 'LIKE', '%'.$term.'%')
                        ->orWhere('account_number', 'LIKE', '%'.$term.'%');
                });
        });

        return AccountTransactionResource::collection($query->latest()->paginate($request->perPage));
    }
}
