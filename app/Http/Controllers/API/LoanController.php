<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoanResource;
use App\Models\AccountTransaction;
use App\Models\Loan;
use Exception;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class LoanController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:loan-list', ['only' => ['index', 'search']]);
        $this->middleware('can:loan-create', ['only' => ['create']]);
        $this->middleware('can:loan-view', ['only' => ['show']]);
        $this->middleware('can:loan-edit', ['only' => ['update']]);
        $this->middleware('can:loan-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return LoanResource::collection(Loan::with('loanAuthority', 'loanPayments', 'loanTransaction.cashbookAccount', 'user')->latest()->paginate($request->perPage));
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
            'reason' => 'required|string|max:191',
            'referenceNo' => 'required|string|max:191|unique:loans,reference_no',
            'authority' => 'required',
            'account' => 'required',
            'amount' => 'required|numeric|min:1',
            'interest' => $request->loanType == 1 ? 'required|min:1|max:100' : 'nullable|numeric|min:0',
            'duration' => $request->loanType == 1 ? 'required|min:1' : 'nullable',
            'date' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ]);

        try {
            $userID = auth()->user()->id;

            // upload thumbnail and set the name
            $imageName = '';
            if ($request->image) {
                $imageName = time().'.'.explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
                Image::make($request->image)->save(public_path('images/loans/').$imageName);
            }

            $reason = '['.$request->referenceNo.'] Loan added to ['.$request->account['accountNumber'].']';

            // store transaction
            $transaction = AccountTransaction::create([
                'account_id' => $request->account['id'],
                'amount' => $request->amount,
                'reason' => $reason,
                'type' => 1,
                'transaction_date' => $request->date,
                'created_by' => $userID,
                'status' => $request->status,
            ]);

            // create loan
            Loan::create([
                'reason' => $request->reason,
                'reference_no' => $request->referenceNo,
                'authority_id' => $request->authority['id'],
                'transaction_id' => $transaction->id,
                'interest' => $request->interest,
                'payable' => $request->rowPayableAMount,
                'loan_type' => $request->loanType,
                'payment_type' => $request->paymentType,
                'duration' => $request->duration,
                'date' => $request->date,
                'created_by' => $userID,
                'note' => clean($request->note),
                'image_path' => $imageName,
                'status' => $request->status,
            ]);

            return $this->responseWithSuccess('Loan added successfully');
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
            $loan = Loan::with('loanAuthority', 'loanPayments.loanPaymentTransaction.cashbookAccount', 'loanTransaction.cashbookAccount', 'user')->where('slug', $slug)->first();

            return new LoanResource($loan);
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
        $loan = Loan::where('slug', $slug)->first();
        $minAmount = $loan->totalPaid();

        // validate request
        $this->validate($request, [
            'reason' => 'required|string|max:191',
            'referenceNo' => 'required|string|max:191|unique:loans,reference_no,'.$loan->id,
            'authority' => 'required',
            'account' => 'required',
            'amount' => 'required|numeric|min:'.$minAmount,
            'interest' => $request->loanType == 1 ? 'required|min:1|max:100' : 'nullable|numeric|min:0',
            'duration' => $request->loanType == 1 ? 'required|min:1' : 'nullable',
            'date' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ]);

        try {
            // upload thumbnail and set the name
            $imageName = $loan->image_path;
            if ($request->image) {
                if ($imageName) {
                    @unlink(public_path('images/loans/'.$imageName));
                }
                $imageName = time().'.'.explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
                Image::make($request->image)->save(public_path('images/loans/').$imageName);
            }

            // store transaction
            $loan->loanTransaction->update([
                'amount' => $request->amount,
                'transaction_date' => $request->date,
                'status' => $request->status,
            ]);

            // update loan
            $loan->update([
                'reason' => $request->reason,
                'reference_no' => $request->referenceNo,
                'authority_id' => $request->authority['id'],
                'interest' => $request->interest,
                'payable' => $request->rowPayableAMount,
                'payment_type' => $request->paymentType,
                'duration' => $request->duration,
                'date' => $request->date,
                'note' => clean($request->note),
                'image_path' => $imageName,
                'status' => $request->status,
            ]);

            return $this->responseWithSuccess('Loan updated successfully');
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
            $loan = Loan::with('loanPayments')->where('slug', $slug)->first();
            if ($loan->payable < $loan->loanTransaction->cashbookAccount->availableBalance()) {
                // delete image from storage
                if ($loan->image_path) {
                    @unlink(public_path('images/loans/'.$loan->image_path));
                }
                // delete loan payment images
                if ($loan->loanPayments) {
                    foreach ($loan->loanPayments as $loanPayment) {
                        if ($loanPayment->image_path) {
                            @unlink(public_path('images/loan-payments/'.$loanPayment->image_path));
                            $loanPayment->loanPaymentTransaction->delete();
                        }
                    }
                }
                $loan->loanTransaction->delete();
                $loan->delete();

                return $this->responseWithSuccess('Loan deleted successfully');
            }

            return $this->responseWithError('Loan can\'t be remove');
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
        $query = Loan::with('loanAuthority', 'loanTransaction.cashbookAccount', 'user');

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('date', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($term) {
            $query->where('reason', 'LIKE', '%'.$term.'%')
                ->orWhere('reference_no', 'LIKE', '%'.$term.'%')
                ->orWhereHas('loanAuthority', function ($newQuery) use ($term) {
                    $newQuery->where('name', 'LIKE', '%'.$term.'%');
                })
                ->orWhereHas('loanTransaction', function ($newQuery) use ($term) {
                    $newQuery->where('amount', '=', $term)
                        ->orWhereHas('cashbookAccount', function ($newQuery) use ($term) {
                            $newQuery->where('account_number', 'LIKE', '%'.$term.'%')->orWhere('bank_name', 'LIKE', '%'.$term.'%')
                                ->orWhere('bank_name', 'LIKE', '%'.$term.'%');
                        });
                });
        });

        return LoanResource::collection($query->latest()->paginate($request->perPage));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allLoans()
    {
        $loans = Loan::with('loanAuthority', 'loanPayments', 'loanTransaction.cashbookAccount', 'user')->where('status', 1)->where('is_paid', 0)->latest()->get();

        return LoanResource::collection($loans);
    }
}
