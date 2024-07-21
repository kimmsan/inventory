<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\PurchasePayment;
use App\Models\AccountTransaction;
use App\Http\Controllers\Controller;
use App\Http\Resources\PurchasePaymentResource;
use App\Notifications\SupplierPurchasePaymentNotification;

class PurchasePaymentController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:purchase-payment-list', ['only' => ['index', 'search']]);
        $this->middleware('can:purchase-payment-create', ['only' => ['create']]);
        $this->middleware('can:purchase-payment-view', ['only' => ['show']]);
        $this->middleware('can:purchase-payment-edit', ['only' => ['update']]);
        $this->middleware('can:purchase-payment-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return PurchasePaymentResource::collection(PurchasePayment::with('purchase.supplier', 'purchase.purchaseTax', 'purchasePaymentTransaction.cashbookAccount', 'user')->latest()->paginate($request->perPage));
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
            'supplier' => 'required',
            'selectedPurchases' => 'required|array|min:1',
            'selectedPurchases.*' => 'required|distinct',
            'account' => 'required',
            'availableBalance' => 'required|numeric|min:'.$request->finalTotal,
            'chequeNo' => 'nullable|string|max:255',
            'voucherNo' => 'nullable|string|max:255',
            'paymentDate' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ]);

        try {
            // get the user id
            $userId = auth()->user()->id;
            $purchases = array();
            $supplier = Supplier::where('slug', $request['supplier']['slug'])->first();
            foreach ($request->selectedPurchases as $key => $selectedPurchase) {
                $purchase = Purchase::where('slug', $selectedPurchase['slug'])->first();
                // store transaction
                $transactionID = null;
                $reason = '['.config('config.purchasePrefix').'-'.$purchase->purchase_no.'] Purchase Payment sent from ['.$request->account['accountNumber'].']';
                // create transaction
                $transaction = AccountTransaction::create([
                    'account_id' => $request->account['id'],
                    'amount' => $selectedPurchase['paidAmount'],
                    'reason' => $reason,
                    'type' => 0,
                    'cheque_no' => $request->chequeNo,
                    'receipt_no' => $request->receiptNo,
                    'transaction_date' => $request->paymentDate,
                    'created_by' => $userId,
                    'status' => $request->status,
                ]);
                $transactionID = $transaction->id;

                // store purchase payment
                PurchasePayment::create([
                    'slug' => uniqid(),
                    'purchase_id' => $selectedPurchase['id'],
                    'amount' => $selectedPurchase['paidAmount'],
                    'transaction_id' => $transactionID,
                    'date' => $request->paymentDate,
                    'note' => clean($request->note),
                    'created_by' => $userId,
                    'status' => $request->status,
                ]);

                // update purchase
                $purchase->update([
                    'is_paid' => $purchase->totalDue() <= 0 ? 1 : 0,
                ]);

                $purchase['amount_paid'] = $selectedPurchase['paidAmount'];
                array_push($purchases, $purchase);
            }

            if ($request->isSendEmail || $request->isSendEmail) {
                $supplier->notify(new SupplierPurchasePaymentNotification($purchases, [
                    'isSendEmail' => filter_var($request->isSendEmail, FILTER_VALIDATE_BOOLEAN),
                    'isSendSMS' =>  filter_var($request->isSendSMS, FILTER_VALIDATE_BOOLEAN)
                ]));
            }
            return $this->responseWithSuccess('Supplier payment added successfully!');
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
            $purchasePayment = PurchasePayment::with('purchase.supplier', 'purchase.purchaseTax', 'purchasePaymentTransaction.cashbookAccount', 'user')->where('slug', $slug)->first();

            return new PurchasePaymentResource($purchasePayment);
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
        $purchasePayment = PurchasePayment::with('purchasePaymentTransaction')->where('slug', $slug)->first();

        // validate request
        $this->validate($request, [
            'purchaseNo' => 'required',
            'purchase' => 'required',
            'account' => 'required',
            'availableBalance' => 'required|numeric|min:'.$request->paidAmount,
            'paidAmount' => 'required|numeric|min:'.$request->minAmount.'|max:'.$request->maxAmount,
            'chequeNo' => 'nullable|string|max:255',
            'receiptNo' => 'nullable|string|max:255',
            'paymentDate' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ]);
        try {
            // update purchase payment record
            $purchasePayment->update([
                'amount' => $request->paidAmount,
                'date' => $request->paymentDate,
                'note' => clean($request->note),
                'status' => $request->status,
            ]);

            // update transaction
            $purchasePayment->purchasePaymentTransaction->update([
                'account_id' => $request->account['id'],
                'amount' => $request->paidAmount,
                'cheque_no' => $request->chequeNo,
                'receipt_no' => $request->receiptNo,
                'transaction_date' => $request->paymentDate,
                'status' => $request->status,
            ]);

            // update purchase
            $purchasePayment->purchase->update([
                'is_paid' => $purchasePayment->purchase->totalDue() == 0 ? 1 : 0,
            ]);

            return $this->responseWithSuccess('Purchase payment updated successfully');
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
            $purchasePayment = PurchasePayment::with('purchase.purchaseReturn.returnTransaction')->where('slug', $slug)->first();

            // update purchase
            if ($purchasePayment->purchase->totalDue() - $purchasePayment->amount <= 0) {
                $purchasePayment->purchase->update([
                    'is_paid' => 1,
                ]);
            }
            // delete payment
            $purchasePayment->delete();

            return $this->responseWithSuccess('Purchase payment deleted successfully');
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
        $query = PurchasePayment::with('purchase.supplier', 'purchasePaymentTransaction.cashbookAccount', 'user');

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('date', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($term) {
            $query->where('amount', '=', $term)
                ->orWhereHas('purchase', function ($newQuery) use ($term) {
                    $newQuery->where('purchase_no', 'LIKE', '%'.$term.'%')
                        ->orWhere('po_reference', 'LIKE', '%'.$term.'%')
                        ->orWhereHas('supplier', function ($newQuery) use ($term) {
                            $newQuery->where('name', 'LIKE', '%'.$term.'%')
                                ->orWhere('name', 'LIKE', '%'.$term.'%')
                                ->orWhere('phone', 'LIKE', '%'.$term.'%');
                        });
                })
                ->orWhereHas('purchasePaymentTransaction', function ($newQuery) use ($term) {
                    $newQuery->where('cheque_no', 'LIKE', '%'.$term.'%')
                        ->orWhere('receipt_no', 'LIKE', '%'.$term.'%')
                        ->orWhere('amount', 'LIKE', '%'.$term.'%')
                        ->orWhereHas('cashbookAccount', function ($newQuery) use ($term) {
                            $newQuery->where('account_number', 'LIKE', '%'.$term.'%')->orWhere('bank_name', 'LIKE', '%'.$term.'%');
                        });
                });
        });

        return PurchasePaymentResource::collection($query->latest()->paginate($request->perPage));
    }
}