<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Product;
use App\Rules\MinTotal;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\PurchasePayment;
use App\Models\PurchaseProduct;
use App\Rules\PurchaseTotalPaid;
use App\Models\AccountTransaction;
use App\Http\Controllers\Controller;
use App\Notifications\PurchaseNotification;
use App\Http\Resources\PurchaseListResource;
use App\Http\Resources\PurchaseProductsResource;
use App\Notifications\PurchasePaymentNotification;

class PurchaseController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:purchase-list', ['only' => ['index', 'search']]);
        $this->middleware('can:purchase-create', ['only' => ['create']]);
        $this->middleware('can:purchase-view', ['only' => ['show']]);
        $this->middleware('can:purchase-edit', ['only' => ['update']]);
        $this->middleware('can:purchase-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return PurchaseListResource::collection(Purchase::with('supplier', 'purchasePayments', 'purchaseTax')->latest()->paginate($request->perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // validate request
        $this->validate($request, [
            'supplier' => 'required',
            'selectedProducts' => 'required|array|min:1',
            'selectedProducts.*' => 'required|distinct',
            'discount' => 'nullable|numeric|min:1|max:'.$request->subTotal,
            'transportCost' => 'nullable|numeric|min:1',
            'orderTax' => 'required',
            'netTotal' => 'required|numeric|min:1',
            'poReference' => 'nullable|string|max:255',
            'paymentTerms' => 'nullable|string|max:255',
            'account' => $request->addPayment == true ? 'required' : 'nullable',
            'availableBalance' => $request->addPayment == true ? 'required|numeric' : 'nullable',
            'totalPaid' => [$request->addPayment != true ? 'nullable' : 'required', new PurchaseTotalPaid($request->availableBalance)],
            'chequeNo' => 'nullable|string|max:255',
            'receiptNo' => 'nullable|string|max:255',
            'purchaseDate' => 'nullable|date_format:Y-m-d',
            'poDate' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ]);

        try {
            // generate code
            $code = 1;
            $prevCode = Purchase::latest()->first();
            if ($prevCode) {
                $code = $prevCode->purchase_no + 1;
            }

            // get logged in user id
            $userId = auth()->user()->id;

            // create purchase
            $purchase = Purchase::create([
                'purchase_no' => $code,
                'slug' => uniqid(),
                'supplier_id' => $request->supplier['id'],
                'discount' => $request->discount,
                'transport' => $request->transportCost,
                'tax_id' => $request->orderTax['id'],
                'sub_total' => $request->subTotal,
                'po_reference' => $request->poReference,
                'payment_terms' => $request->paymentTerms,
                'po_date' => $request->poDate,
                'purchase_date' => $request->purchaseDate,
                'note' => clean($request->note),
                'status' => $request->status,
                'created_by' => $userId,
            ]);

            // store purchase products
            foreach ($request->selectedProducts as $key => $selectedProduct) {
                $product = Product::where('slug', $selectedProduct['slug'])->first();

                // calculate new purchase price
                $currentStockPrice = $product->inventory_count * $product->purchase_price;
                $newStockPrice = $selectedProduct['qty'] * $selectedProduct['unitCost'];
                $totalStockPrice = $currentStockPrice + $newStockPrice;
                $totalQty = $product->inventory_count + $selectedProduct['qty'];
                $unitCost = $totalStockPrice / $totalQty;

                // update product stock purchase price
                $product->update([
                    'purchase_price' => $unitCost,
                    'inventory_count' => $product->inventory_count + $selectedProduct['qty'],
                ]);

                PurchaseProduct::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'quantity' => $selectedProduct['qty'],
                    'purchase_price' => $selectedProduct['unitPrice'],
                    'unit_cost' => $selectedProduct['unitCost'],
                    'tax_amount' => $selectedProduct['productTax'],
                ]);
            }

            // store transaction
            if ($request->addPayment == true) {
                $reason = '['.config('config.purchasePrefix').'-'.$purchase->purchase_no.'] Purchase Payment sent from ['.$request->account['accountNumber'].']';

                // create transaction
                $transaction = AccountTransaction::create([
                    'account_id' => $request->account['id'],
                    'amount' => $request->totalPaid,
                    'reason' => $reason,
                    'type' => 0,
                    'transaction_date' => $request->purchaseDate,
                    'cheque_no' => $request->chequeNo,
                    'receipt_no' => $request->receiptNo,
                    'created_by' => $userId,
                    'status' => $request->status,
                ]);

                // store purchase payment record
                PurchasePayment::create([
                    'slug' => uniqid(),
                    'purchase_id' => $purchase->id,
                    'transaction_id' => $transaction->id,
                    'amount' => $request->totalPaid,
                    'date' => $request->purchaseDate,
                    'note' => clean($request->note),
                    'created_by' => $userId,
                    'status' => $request->status,
                ]);
            }
            // update purchase
            if ($purchase->totalDue() == 0) {
                $purchase->update([
                    'is_paid' => 1,
                ]);
            }

            //send notification
            if ($request->isSendEmail || $request->isSendSMS) {
                $this->notifySupplier($purchase->slug, $request);
            }

            return $this->responseWithSuccess('Purchase added successfully', [
                'slug' => $purchase->slug,
            ]);
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
            $purchase = Purchase::with('supplier', 'purchaseProducts.purchase', 'purchaseReturn', 'purchasePayments.purchasePaymentTransaction.cashbookAccount', 'purchaseProducts.product.productUnit', 'purchaseProducts.product.productTax', 'purchaseProducts.product.proSubCategory.category', 'user')->where('slug', $slug)->first();
            return new PurchaseProductsResource($purchase);
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $slug)
    {
        $purchase = Purchase::where('slug', $slug)->with('purchaseProducts.product')->first();
        $totalPaid = $purchase->purchaseTotalPaid();
        $minAmount = ! isset($purchase->purchaseReturn) ? $totalPaid : $totalPaid - $purchase->purchaseReturn->returnTransaction->amount;

        // validate request
        $this->validate($request, [
            'supplier' => 'required',
            'selectedProducts' => 'required|array|min:1',
            'selectedProducts.*' => 'required|distinct',
            'discount' => 'nullable|numeric|min:1|max:'.$request->rowSubTotal,
            'transportCost' => 'nullable|numeric|min:1',
            'orderTax' => 'required',
            'netTotal' => ['required', 'numeric', new MinTotal($minAmount, $request->netTotal)],
            'poReference' => 'nullable|string|max:255',
            'paymentTerms' => 'nullable|string|max:255',
            'purchaseDate' => 'nullable|date_format:Y-m-d',
            'poDate' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ]);

        try {
            // delete current products
            $purchase->purchaseProducts->each->delete();
            // store purchase products
            foreach ($request->selectedProducts as $key => $selectedProduct) {
                $product = Product::where('slug', $selectedProduct['slug'])->first();

                // calculate new purchase price
                $currentStockPrice = $product->inventory_count * $product->purchase_price;
                $newStockPrice = $selectedProduct['qty'] * $selectedProduct['unitCost'];
                $totalStockPrice = $currentStockPrice + $newStockPrice;
                $totalQty = $product->inventory_count + $selectedProduct['qty'];
                $unitCost = $totalStockPrice / $totalQty;

                $newInventory = $product->inventory_count - $selectedProduct['oldQty'] + $selectedProduct['qty'];

                // update product purchase price
                $product->update([
                    'purchase_price' => $unitCost,
                    'inventory_count' => $newInventory,
                ]);

                // store products
                PurchaseProduct::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'quantity' => $selectedProduct['qty'],
                    'purchase_price' => $selectedProduct['unitPrice'],
                    'unit_cost' => $selectedProduct['unitCost'],
                    'tax_amount' => $selectedProduct['productTax'],
                ]);
            }

            // update purchase
            $purchase->update([
                'supplier_id' => $request->supplier['id'],
                'discount' => $request->discount,
                'transport' => $request->transportCost,
                'tax_id' => $request->orderTax['id'],
                'sub_total' => $request->rowSubTotal,
                'po_reference' => $request->poReference,
                'payment_terms' => $request->paymentTerms,
                'po_date' => $request->poDate,
                'purchase_date' => $request->purchaseDate,
                'note' => clean($request->note),
                'status' => $request->status,
                'is_paid' => 1,
            ]);

            return $this->responseWithSuccess('Purchase updated successfully', [
                'slug' => $purchase->slug,
            ]);
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
            $purchase = Purchase::where('slug', $slug)->with('purchasePayments.purchasePaymentTransaction', 'purchaseProducts.product.productUnit', 'purchaseReturn.purchaseReturnProducts', 'purchaseReturn.returnTransaction')->first();

            // delete purchase return
            $purchaseReturn = $purchase->purchaseReturn;
            if (isset($purchaseReturn)) {
                if ($purchaseReturn->transaction_id != null) {
                    $purchaseReturn->returnTransaction->delete();
                }
                // update product inventory count
                foreach ($purchaseReturn->purchaseReturnProducts as $purchaseReturnProduct) {
                    $product = $purchaseReturnProduct->product;
                    $product->update([
                        'inventory_count' => $product->inventory_count + $purchaseReturnProduct->quantity,
                    ]);
                }
                // delete return products
                $purchaseReturn->purchaseReturnProducts->each->delete();
            }

            // delete purchase
            $purchase->delete();

            return $this->responseWithSuccess('Purchase deleted successfully!');
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
        $query = Purchase::with('supplier', 'purchasePayments', 'user');

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('purchase_date', [$request->startDate, $request->endDate]);
        }

        $query = $query->where(function ($query) use ($term) {
            $query->where('purchase_no', 'LIKE', '%'.$term.'%')
                ->orWhere('sub_total', 'LIKE', '%'.$term.'%')
                ->orWhere('transport', 'LIKE', '%'.$term.'%')
                ->orWhere('discount', 'LIKE', '%'.$term.'%')
                ->orWhere('po_reference', 'LIKE', '%'.$term.'%')
                ->orWhere('payment_terms', 'LIKE', '%'.$term.'%')
                ->orWhereHas('supplier', function ($newQuery) use ($term) {
                    $newQuery->where('name', 'LIKE', '%'.$term.'%')
                        ->orWhere('company_name', 'LIKE', '%'.$term.'%')
                        ->orWhere('phone', 'LIKE', '%'.$term.'%');
                });
        });

        return PurchaseListResource::collection($query->latest()->paginate($request->perPage));
    }

    // notify supplier
    public function notifySupplier($slug, Request $request){
        $purchase = Purchase::with('supplier', 'purchaseProducts.purchase', 'purchaseReturn', 'purchasePayments.purchasePaymentTransaction.cashbookAccount', 'purchaseProducts.product.productUnit', 'purchaseProducts.product.productTax', 'purchaseProducts.product.proSubCategory.category', 'user')->where('slug', $slug)->first();
        // send notification
        $purchase->supplier->notify(new PurchaseNotification($purchase, [
            'isSendEmail' => filter_var($request->isSendEmail, FILTER_VALIDATE_BOOLEAN),
            'isSendSMS' =>  filter_var($request->isSendSMS, FILTER_VALIDATE_BOOLEAN)
        ]));
        return 'Successfully Notified';
    }

    // store purchase payment
    public function storePurchasePayment(Request $request){

        $maxAmount = $request->selectedPurchase['due'] <= $request->account['availableBalance'] ?  $request->selectedPurchase['due']  :  $request->account['availableBalance'];
        // validate request
        $this->validate($request, [
            'selectedPurchase' => 'required|array|min:1',
            'paidAmount' => 'required|numeric|min:1|max:'.$maxAmount,
            'account' => 'required',
            'chequeNo' => 'nullable|string|max:255',
            'receiptNo' => 'nullable|string|max:255',
            'paymentDate' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ]);

        $purchase = Purchase::where('slug', $request->selectedPurchase['slug'])->first();
        $userId = auth()->user()->id;
        // store transaction
        $transactionID = null;
        $reason = '['.config('config.purchasePrefix').'-'.$purchase->purchase_no.'] Purchase Payment sent from ['.$request->account['accountNumber'].']';
        // create transaction
        $transaction = AccountTransaction::create([
            'account_id' => $request->account['id'],
            'amount' => $request->paidAmount,
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
            'purchase_id' => $purchase->id,
            'amount' => $request->paidAmount,
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


        if ($request->isSendEmail || $request->isSendEmail) {
            $purchase['amount_paid'] = $request->paidAmount;
            $purchase->supplier->notify(new PurchasePaymentNotification($purchase, [
                'isSendEmail' => filter_var($request->isSendEmail, FILTER_VALIDATE_BOOLEAN),
                'isSendSMS' =>  filter_var($request->isSendSMS, FILTER_VALIDATE_BOOLEAN)
            ]));
        }

        return $this->responseWithSuccess('Supplier payment added successfully!');
    }
}
