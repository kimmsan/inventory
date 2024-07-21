<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoanPaymentResource;
use App\Models\AccountTransaction;
use App\Models\Loan;
use App\Models\LoanPayment;
use Exception;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class LoanPaymentController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:loan-payment-list', ['only' => ['index', 'search']]);
        $this->middleware('can:loan-payment-create', ['only' => ['create']]);
        $this->middleware('can:loan-payment-view', ['only' => ['show']]);
        $this->middleware('can:loan-payment-edit', ['only' => ['update']]);
        $this->middleware('can:loan-payment-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return LoanPaymentResource::collection(LoanPayment::with('loan.user', 'loanPaymentTransaction.cashbookAccount', 'loan.loanPayments.loanPaymentTransaction', 'loan.loanAuthority', 'loan.loanTransaction.cashbookAccount', 'user')->latest()->paginate($request->perPage));
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
            'referenceNo' => 'required|string|max:191',
            'loan' => 'required',
            'account' => 'required',
            'amount' => 'required|numeric|max:'.$request->availableBalance,
            'interest' => 'nullable|numeric|max:'.$request->availableBalance,
            'date' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ]);
        try {
            // get loan
            $loan = Loan::where('slug', $request->loan['slug'])->first();

            // upload thumbnail and set the name
            $imageName = '';
            if ($request->image) {
                $imageName = time().'.'.explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
                Image::make($request->image)->save(public_path('images/loan-payments/').$imageName);
            }

            $reason = '['.$request->loan['reference'].'] Loan Payment sent from ['.$request->account['accountNumber'].']';

            // store transaction
            $transaction = AccountTransaction::create([
                'account_id' => $request->account['id'],
                'amount' => $request->amount,
                'reason' => $reason,
                'type' => 0,
                'transaction_date' => $request->date,
                'created_by' => auth()->user()->id,
                'status' => $request->status,
            ]);

            // create loan payment
            $loanPayment = LoanPayment::create([
                'reference_no' => $request->referenceNo,
                'loan_id' => $loan->id,
                'transaction_id' => $transaction->id,
                'amount' => $request->amount,
                'interest' => $request->interest,
                'date' => $request->date,
                'created_by' => auth()->user()->id,
                'note' => $request->note,
                'image_path' => $imageName,
                'status' => $request->status,
            ]);

            // update loan
            if ($loan->totalDue() == 0 && $loanPayment->status == 1) {
                $loan->update([
                    'is_paid' => 1,
                ]);
            }

            return $this->responseWithSuccess('Loan payment added successfully');
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
            $loanPayment = LoanPayment::with('loanPaymentTransaction.cashbookAccount', 'loan.loanPayments.loanPaymentTransaction', 'loan.loanAuthority', 'loan.loanTransaction.cashbookAccount', 'user')->where('slug', $slug)->first();

            return new LoanPaymentResource($loanPayment);
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
        $loanPayment = LoanPayment::where('slug', $slug)->first();

        $maxAmount = $request->loan['due'] + $loanPayment->amount;
        $availableBalance = $request->account['availableBalance'];
        if ($maxAmount >= $availableBalance) {
            $maxAmount = $availableBalance;
            if ($loanPayment->loanPaymentTransaction->cashbookAccount->slug == $request->account['slug']) {
                $maxAmount = $availableBalance + $loanPayment->loanPaymentTransaction->amount;
            }
        }

        // validate request
        $this->validate($request, [
            'referenceNo' => 'required|string|max:191',
            'loan' => 'required',
            'account' => 'required',
            'amount' => 'required|numeric|max:'.$maxAmount,
            'date' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ]);

        try {
            // get loan
            $loan = Loan::where('slug', $request->loan['slug'])->first();
            // upload thumbnail and set the name
            $imageName = $loanPayment->image_path;
            if ($request->image) {
                if ($imageName) {
                    @unlink(public_path('images/loan-payments/'.$imageName));
                }
                $imageName = time().'.'.explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
                Image::make($request->image)->save(public_path('images/loan-payments/').$imageName);
            }

            $totalAmount = $request->amount + $request->interest;
            $reason = $request->loan['reference'].'Loan Paid';

            // update transaction
            $loanPayment->loanPaymentTransaction->update([
                'account_id' => $request->account['id'],
                'amount' => $totalAmount,
                'reason' => $reason,
                'transaction_date' => $request->date,
                'status' => $request->status,
            ]);

            // update loan payment
            $loanPayment->update([
                'reference_no' => $request->referenceNo,
                'date' => $request->date,
                'note' => $request->note,
                'amount' => $request->amount,
                'interest' => $request->interest,
                'image_path' => $imageName,
                'status' => $request->status,
            ]);

            // update loan
            if ($loan->totalDue() == 0 && $loanPayment->status == 1) {
                $loan->update([
                    'is_paid' => 1,
                ]);
            }

            return $this->responseWithSuccess('Loan payment updated successfully');
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
            $loanPayment = LoanPayment::where('slug', $slug)->first();
            // delete image from storage
            if ($loanPayment->image_path) {
                @unlink(public_path('images/loan-payments/'.$loanPayment->image_path));
            }
            $loanPayment->loanPaymentTransaction->delete();
            $loanPayment->delete();

            return $this->responseWithSuccess('Loan payment deleted successfully');
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
        $query = LoanPayment::with('loan', 'loanPaymentTransaction.cashbookAccount');

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('date', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($term) {
            $query->where('reference_no', 'LIKE', '%'.$term.'%')
                ->orWhereHas('loan', function ($newQuery) use ($term) {
                    $newQuery->where('reason', 'LIKE', '%'.$term.'%')->orWhere('reference_no', 'LIKE', '%'.$term.'%');
                })
                ->orWhereHas('loanPaymentTransaction', function ($newQuery) use ($term) {
                    $newQuery->where('amount', '=', $term)
                        ->orWhereHas('cashbookAccount', function ($newQuery) use ($term) {
                            $newQuery->where('account_number', 'LIKE', '%'.$term.'%')
                                ->orWhere('bank_name', 'LIKE', '%'.$term.'%');
                        });
                });
        });

        return LoanPaymentResource::collection($query->latest()->paginate($request->perPage));
    }
}
