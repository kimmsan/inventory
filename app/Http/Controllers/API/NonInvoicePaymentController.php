<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\NonInvoicePaymentListResource;
use App\Http\Resources\NonInvoicePaymentResource;
use App\Models\AccountTransaction;
use App\Models\NonInvoicePayment;
use Exception;
use Illuminate\Http\Request;

class NonInvoicePaymentController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:non-invoice-payment-list', ['only' => ['index', 'search']]);
        $this->middleware('can:non-invoice-payment-create', ['only' => ['create']]);
        $this->middleware('can:non-invoice-payment-view', ['only' => ['show']]);
        $this->middleware('can:non-invoice-payment-edit', ['only' => ['update']]);
        $this->middleware('can:non-invoice-payment-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return NonInvoicePaymentListResource::collection(NonInvoicePayment::with('client', 'paymentTransaction.cashbookAccount')->latest()->paginate($request->perPage));
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
            'client' => 'required',
            'type' => 'required',
            'account' => $request->type == 1 ? 'required' : 'nullable',
            'amount' => 'required|numeric|min:1|max:'.$request->max,
            'chequeNo' => 'nullable|string|max:255',
            'voucherNo' => 'nullable|string|max:255',
            'paymentDate' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ]);
        try {
            $userId = auth()->user()->id;

            if ($request->type == 1) {
                $reason = $reason = '['.config('config.clientPrefix').'-'.$request->client['id'].'] Non inovice payment added to ['.$request->account['accountNumber'].']';
                // store transaction
                $transaction = AccountTransaction::create([
                    'account_id' => $request->account['id'],
                    'amount' => $request->amount,
                    'reason' => $reason,
                    'type' => 1,
                    'transaction_date' => $request->paymentDate,
                    'cheque_no' => $request->chequeNo,
                    'receipt_no' => $request->receiptNo,
                    'created_by' => $userId,
                    'status' => $request->status,
                ]);
            }

            // store payment
            NonInvoicePayment::create([
                'slug' => uniqid(),
                'client_id' => $request->client['id'],
                'amount' => $request->amount,
                'type' => $request->type,
                'transaction_id' => isset($transaction) ? $transaction->id : null,
                'date' => $request->paymentDate,
                'note' => $request->note,
                'status' => $request->status,
                'created_by' => $userId,
            ]);

            return $this->responseWithSuccess('Non invoice payment added successfully');
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
            $payment = NonInvoicePayment::where('slug', $slug)->first();

            return new NonInvoicePaymentResource($payment);
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
        $payment = NonInvoicePayment::where('slug', $slug)->first();
        // validate request
        $this->validate($request, [

            'client' => 'required',
            'type' => 'required',
            'account' => $request->type == 1 ? 'required' : 'nullable',
            'paidAmount' => 'required|numeric|min:1|max:'.$request->max,
            'chequeNo' => 'nullable|string|max:255',
            'voucherNo' => 'nullable|string|max:255',
            'paymentDate' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ]);

        try {
            $payment->update([
                'amount' => $request->paidAmount,
                'date' => $request->paymentDate,
                'note' => $request->note,
                'status' => $request->status,
            ]);

            if ($request->type == 1) {
                // store transaction
                $payment->paymentTransaction->update([
                    'account_id' => $request->account['id'],
                    'amount' => $request->paidAmount,
                    'type' => 1,
                    'cheque_no' => $request->chequeNo,
                    'receipt_no' => $request->receiptNo,
                    'transaction_date' => $request->paymentDate,
                    'status' => $request->status,
                ]);
            }

            return $this->responseWithSuccess('Payment updated successfully');
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
            $payment = NonInvoicePayment::where('slug', $slug)->first();

            // check if the payment can be delete
            $canDelete = true;

            if ($payment->type == 1) {
                if (($payment->client->nonInvoiceTotalDue() < $payment->client->nonInvoicePaid()) || $payment->paymentTransaction->cashbookAccount->availableBalance() < $payment->amount) {
                    $canDelete = false;
                }
            } else {
                if ($payment->amount > $payment->client->nonInvoiceCurrentDue()) {
                    $canDelete = false;
                }
            }

            if ($canDelete) {
                if ($payment->type == 1) {
                    $payment->paymentTransaction->delete();
                }
                $payment->delete();
            } else {
                return $this->responseWithError('Sorry you can\'t delete this invoice!');
            }

            return $this->responseWithSuccess('Payment deleted successfully');
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
        $query = NonInvoicePayment::with('client', 'paymentTransaction.cashbookAccount');

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('date', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($term) {
            $query->where('amount', 'LIKE', '%'.$term.'%')
                ->orWhereHas('client', function ($newQuery) use ($term) {
                    $newQuery->where('name', 'LIKE', '%'.$term.'%')
                        ->orWhere('email', 'LIKE', '%'.$term.'%')
                        ->orWhere('company_name', 'LIKE', '%'.$term.'%')
                        ->orWhere('phone', 'LIKE', '%'.$term.'%');
                })
                ->orWhereHas('paymentTransaction', function ($newQuery) use ($term) {
                    $newQuery->where('cheque_no', 'LIKE', '%'.$term.'%')
                        ->orWhere('receipt_no', 'LIKE', '%'.$term.'%')->orWhereHas('cashbookAccount', function ($newQuery) use ($term) {
                            $newQuery->where('account_number', 'LIKE', '%'.$term.'%')
                                ->orWhere('bank_name', 'LIKE', '%'.$term.'%');
                        });
                });
        });

        return NonInvoicePaymentListResource::collection($query->latest()->paginate($request->perPage));
    }
}
