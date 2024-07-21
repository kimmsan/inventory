<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseResource;
use App\Models\AccountTransaction;
use App\Models\Expense;
use Exception;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ExpenseController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:expense-list', ['only' => ['index', 'search']]);
        $this->middleware('can:expense-create', ['only' => ['create']]);
        $this->middleware('can:expense-view', ['only' => ['show']]);
        $this->middleware('can:expense-edit', ['only' => ['update']]);
        $this->middleware('can:expense-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return ExpenseResource::collection(Expense::with('expSubCategory.expCategory', 'expTransaction.cashbookAccount', 'user')->latest()->paginate($request->perPage));
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
            'reason' => 'required|string|max:255',
            'subCategory' => 'required',
            'account' => 'required',
            'amount' => 'required|numeric|max:'.$request->availableBalance,
            'chequeNo' => 'nullable|string|max:255',
            'voucherNo' => 'nullable|string|max:255',
            'date' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ]);
        try {
            // upload thumbnail and set the name
            $imageName = '';
            if ($request->image) {
                $imageName = time().'.'.explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
                Image::make($request->image)->save(public_path('images/expenses/').$imageName);
            }

            $userId = auth()->user()->id;
            $reason = '['.config('config.expSubCatPrefix').'-'.$request->subCategory['code'].'] Expense payment';

            // store transaction
            $transaction = AccountTransaction::create([
                'account_id' => $request->account['id'],
                'amount' => $request->amount,
                'reason' => $reason,
                'type' => 0,
                'transaction_date' => $request->date,
                'cheque_no' => $request->chequeNo,
                'receipt_no' => $request->voucherNo,
                'created_by' => $userId,
                'status' => $request->status,
            ]);

            // create expense
            Expense::create([
                'reason' => $request->reason,
                'sub_cat_id' => $request->subCategory['id'],
                'transaction_id' => $transaction->id,
                'date' => $request->date,
                'created_by' => $userId,
                'note' => clean($request->note),
                'image_path' => $imageName,
                'status' => $request->status,
            ]);

            return $this->responseWithSuccess('Expense added successfully');
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
            $expense = Expense::with('expSubCategory', 'expTransaction.cashbookAccount', 'user')->where('slug', $slug)->first();

            return new ExpenseResource($expense);
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
        $expense = Expense::with('expSubCategory', 'expTransaction.cashbookAccount')->where('slug', $slug)->first();
        $availableBalance = 99999999;
        if (isset($request->account['availableBalance'])) {
            $availableBalance = $expense->expTransaction->amount + $request->account['availableBalance'];
        }

        // validate request
        $this->validate($request, [
            'reason' => 'required|string|max:255',
            'subCategory' => 'required',
            'account' => 'required',
            'amount' => isset($request->account) ? 'required|numeric|max:'.$availableBalance : 'nullable',
            'chequeNo' => 'nullable|string|max:255',
            'voucherNo' => 'nullable|string|max:255',
            'date' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ]);
        try {

            // upload thumbnail and set the name
            $imageName = $expense->image_path;
            if ($request->image) {
                if ($imageName) {
                    @unlink(public_path('images/expenses/'.$imageName));
                }
                $imageName = time().'.'.explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
                Image::make($request->image)->save(public_path('images/expenses/').$imageName);
            }

            // update transaction
            $transaction = $expense->expTransaction;
            $transaction->update([
                'account_id' => $request->account['id'],
                'amount' => $request->amount,
                'transaction_date' => $request->date,
                'status' => $request->status,
                'cheque_no' => $request->chequeNo,
                'receipt_no' => $request->voucherNo,
            ]);

            // update expense
            $expense->update([
                'reason' => $request->reason,
                'sub_cat_id' => $request->subCategory['id'],
                'transaction_id' => $transaction->id,
                'date' => $request->date,
                'note' => clean($request->note),
                'image_path' => $imageName,
                'status' => $request->status,
            ]);

            return $this->responseWithSuccess('Expense updated successfully');
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
            $expense = Expense::where('slug', $slug)->first();
            $expense->delete();

            return $this->responseWithSuccess('Expense deleted successfully');
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * search resource from storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function search(Request $request)
    {
        $term = $request->term;
        $query = Expense::with('expSubCategory.expCategory', 'expTransaction.cashbookAccount', 'user');

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('date', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($term) {
            $query->where('reason', 'LIKE', '%'.$term.'%')
            ->orWhereHas('expSubCategory', function ($newQuery) use ($term) {
                $newQuery->where('name', 'LIKE', '%'.$term.'%')
                    ->orWhereHas('expCategory', function ($newQuery) use ($term) {
                        $newQuery->where('name', 'LIKE', '%'.$term.'%');
                    });
            })
            ->orWhereHas('expTransaction', function ($newQuery) use ($term) {
                $newQuery->where('amount', 'LIKE', '%'.$term.'%')
                    ->orWhereHas('cashbookAccount', function ($newQuery) use ($term) {
                        $newQuery->where('account_number', 'LIKE', '%'.$term.'%');
                    });
            });
        });

        return ExpenseResource::collection($query->latest()->paginate($request->perPage));
    }
}
