<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccountResource;
use App\Http\Resources\AccountTransactionResource;
use App\Models\Account;
use App\Models\AccountTransaction;
use Exception;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:account-list', ['only' => ['index', 'search']]);
        $this->middleware('can:account-create', ['only' => ['create']]);
        $this->middleware('can:account-view', ['only' => ['show']]);
        $this->middleware('can:account-edit', ['only' => ['update']]);
        $this->middleware('can:account-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return AccountResource::collection(Account::latest()->paginate($request->perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate request
        $this->validate($request, [
            'bankName' => 'required|string|max:100',
            'branchName' => 'nullable|string|max:100',
            'accountNumber' => 'required|string|max:100|unique:accounts,account_number',
            'date' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ]);
        try {
            // store account
            $account = Account::create([
                'bank_name' => $request->bankName,
                'branch_name' => $request->branchName,
                'account_number' => $request->accountNumber,
                'date' => $request->date,
                'created_by' => auth()->user()->id,
                'note' => clean($request->note),
                'status' => $request->status,
            ]);

            return $this->responseWithSuccess('Account added successfully!');
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        try {
            $account = Account::where('slug', $slug)->with('balanceTransactions.user', 'user')->first();

            return new AccountResource($account);
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $account = Account::where('slug', $slug)->first();
        // validate request
        $this->validate($request, [
            'bankName' => 'required|string|max:100',
            'branchName' => 'required|string|max:100',
            'accountNumber' => 'required|string|max:100|unique:accounts,account_number,'.$account->id,
            'date' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ]);

        try {
            // update account
            $account->update([
                'bank_name' => $request->bankName,
                'branch_name' => $request->branchName,
                'account_number' => $request->accountNumber,
                'date' => $request->date,
                'note' => clean($request->note),
                'status' => $request->status,
            ]);

            return $this->responseWithSuccess('Account updated successfully');
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        try {
            $account = Account::where('slug', $slug)->first();
            if (isset($account->balanceTransactions) && count($account->balanceTransactions) > 0) {
                return $this->responseWithError('Sorry you can\'t remove this account!');
            } else {
                $account->delete();

                return $this->responseWithSuccess('Account deleted successfully');
            }
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * search resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function search(Request $request)
    {
        $term = $request->term;
        $query = Account::query();

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('date', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($term) {
            $query->where('bank_name', 'Like', '%'.$term.'%')
                ->orWhere('branch_name', 'Like', '%'.$term.'%')
                ->orWhere('account_number', 'Like', '%'.$term.'%');
        });

        return AccountResource::collection($query->latest()->paginate($request->perPage));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allAccounts()
    {
        $accounts = Account::where('status', 1)->latest()->get();

        return AccountResource::collection($accounts);
    }

    // return account transactions
    public function accountTransactions($slug)
    {
        $account = Account::where('slug', $slug)->first();
        $transactions = AccountTransaction::with('user', 'cashbookAccount')->where('account_id', $account->id)->orderBy('created_at', 'asc')->get();
        return AccountTransactionResource::collection($transactions);
    }

    // return search transactions
    public function searchTransactions(Request $request, $slug)
    {
        $term = $request->term;

        $account = Account::where('slug', $slug)->first();
        $transactions = AccountTransaction::with('user', 'cashbookAccount')->where('account_id', $account->id)->where(function ($query) use ($term) {
            $query->orWhere('reason', 'LIKE', '%'.$term.'%')->orWhere('amount', 'LIKE', '%'.$term.'%');
        })->latest()->paginate($request->perPage);

        return AccountTransactionResource::collection($transactions);
    }
}