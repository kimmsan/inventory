<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\PurchaseReturn;
use App\Models\PurchasePayment;
use App\Models\NonPurchasePayment;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;
use App\Http\Controllers\Controller;
use App\Http\Resources\PurchaseResource;
use App\Http\Resources\SupplierResource;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Notifications\WelcomeNotification;
use App\Http\Requests\SupplierStoreRequest;
use App\Http\Resources\PurchaseListResource;
use App\Http\Resources\SupplierListResource;
use Illuminate\Support\Facades\Notification;
use Intervention\Image\Facades\Image as Image;
use App\Http\Resources\PurchasePaymentResource;
use App\Http\Resources\PurchaseReturnListResource;
use App\Http\Resources\NonPurchasePaymentListResource;
use App\Http\Resources\SupplierForPurchasePaymentResource;
use App\Http\Resources\SupplierWithNonPurchasePaymentResource;

class SupplierController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:supplier-list', ['only' => ['index', 'search']]);
        $this->middleware('can:supplier-create', ['only' => ['create']]);
        $this->middleware('can:supplier-view', ['only' => ['show']]);
        $this->middleware('can:supplier-edit', ['only' => ['update']]);
        $this->middleware('can:supplier-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return SupplierListResource::collection(Supplier::latest()->paginate($request->perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierStoreRequest $request)
    {
        try {
            // generate code
            $code = 1;
            $prevSupplier = Supplier::latest()->first();
            if ($prevSupplier) {
                $code = ++$prevSupplier->supplier_id;
            }

            // upload thumbnail and set the name
            $imageName = '';
            if ($request->image) {
                $imageName = time() . '.' . explode(
                    '/',
                    explode(':', substr($request->image, 0, strpos($request->image, ';')))[1]
                )[1];
                Image::make($request->image)->save(public_path('images/suppliers/') . $imageName);
            }

            // create supplier
            $userSchema = Supplier::create([
                'name' => $request->name,
                'supplier_id' => $code,
                'email' => $request->email,
                'phone' => $request->phoneNumber,
                'company_name' => $request->companyName,
                'type' => $request->supplierType,
                'address' => $request->address,
                'status' => $request->status,
                'image_path' => $imageName,
            ]);

            //send welcome notification
            try {
                if ($request->isSendEmail || $request->isSendSMS) {
                    Notification::send($userSchema, new WelcomeNotification($userSchema,[
                            'isSendEmail' => $request->isSendEmail,
                            'isSendSMS' => $request->isSendSMS,
                    ]));
                }
            } catch (Exception $e) {
                //handle email error here if necessary
                throw new Exception($e);
            }
            return $this->responseWithSuccess('Supplier added successfully');
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
            $supplier = Supplier::where('slug', $slug)->first();

            return new SupplierResource($supplier);
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
        $supplier = Supplier::where('slug', $slug)->first();
        // validate request
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'phoneNumber' => 'required|string|max:20|min:3',
            'email' => 'nullable|email|max:255|min:3|unique:users,email,' . $supplier->email,
            'companyName' => 'nullable|string|max:100|min:2',
            'address' => 'nullable|string|max:255',
        ]);
        try {
            // upload thumbnail and set the name
            $imageName = $supplier->image_path;
            if ($request->image) {
                if ($imageName) {
                    @unlink(public_path('images/suppliers/' . $imageName));
                }
                $imageName = time() . '.' . explode(
                    '/',
                    explode(':', substr($request->image, 0, strpos($request->image, ';')))[1]
                )[1];
                Image::make($request->image)->save(public_path('images/suppliers/') . $imageName);
            }
            // update supplier
            $supplier->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phoneNumber,
                'company_name' => $request->companyName,
                'address' => $request->address,
                'status' => $request->status,
                'image_path' => $imageName,
            ]);

            return $this->responseWithSuccess('Supplier updated successfully');
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
            $supplier = Supplier::where('slug', $slug)->first();
            //delete image from storage
            if ($supplier->image_path) {
                @unlink(public_path('images/suppliers/' . $supplier->image_path));
            }
            $supplier->delete();

            return $this->responseWithSuccess('Supplier deleted successfully');
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
        $query = Supplier::query();

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('created_at', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($term) {
            $query->where('name', 'Like', '%' . $term . '%')
                ->orWhere('email', 'Like', '%' . $term . '%')
                ->orWhere('phone', 'Like', '%' . $term . '%')
                ->orWhere('company_name', 'Like', '%' . $term . '%');
        });

        return SupplierResource::collection($query->latest()->paginate($request->perPage));
    }

    // return all suppliers
    public function allSuppliers()
    {
        $suppliers = Supplier::where('status', 1)->latest()->get();

        return SupplierListResource::collection($suppliers);
    }

    // return all suppliers
    public function suppliersForNonPurchasePayments()
    {
        $suppliers = Supplier::where('status', 1)->latest()->get();

        return SupplierWithNonPurchasePaymentResource::collection($suppliers);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function supplierPurchases($slug)
    {
        try {
            $supplier = Supplier::where('slug', $slug)->with('purchases')->first();
            return PurchaseListResource::collection(Purchase::where('supplier_id', $supplier->id)->get());
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function filterSupplierPurchases(Request $request)
    {
        $supplier = Supplier::where('slug', $request->supplierSlug)->first();
        $products = [];
        $purchases = Purchase::with(
            'purchaseProducts.product.proSubCategory.category',
            'purchaseProducts.product.productUnit'
        );
        if (isset($request->products) && count($request->products) > 0) {
            // build the product array
            foreach ($request->products as $key => $product) {
                array_push($products, $product['id']);
            }
            // get the purchases
            $purchases = $purchases->where(
                'supplier_id',
                $supplier->id
            )->whereDoesntHave('purchaseReturn')->whereHas(
                'purchaseProducts',
                function ($secondQuery) use ($products) {
                    $secondQuery->whereIn('product_id', $products);
                }
            )->get();
        } else {
            // get the purchases
            $purchases = $purchases->where('supplier_id', $supplier->id)->whereDoesntHave('purchaseReturn')->get();
        }

        return PurchaseResource::collection($purchases);
    }

    // return client specific invoices
    public function specificSupplierPurchases($slug)
    {
        $supplier = Supplier::where('slug', $slug)->first();
        $purchases = Purchase::with('supplier', 'purchasePayments', 'purchaseTax')->where(
            'supplier_id',
            $supplier->id
        )->where('status', 1)->where('is_paid', 0)->get();

        return [
            'purchases' => PurchaseListResource::collection($purchases),
            'supplier' => new SupplierForPurchasePaymentResource($supplier),
        ];
    }

    // return purchases for a specific supplier
    public function purchasesBySupplier(Request $request, $slug)
    {
        $supplier = Supplier::where('slug', $slug)->first();
        $purchases = Purchase::with('supplier', 'purchasePayments', 'purchaseTax')->where(
            'supplier_id',
            $supplier->id
        )->with('supplier', 'user')->latest()->paginate($request->perPage);

        return PurchaseListResource::collection($purchases);
    }

    // search purchases for a specific supplier
    public function searchPurchasesBySupplier(Request $request, $slug)
    {
        $term = $request->term;

        $supplier = Supplier::where('slug', $slug)->first();

        $query = Purchase::with('supplier', 'purchasePayments', 'purchaseTax');

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('purchase_date', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($supplier) {
            $query->where('supplier_id', $supplier->id);
        })->where(function ($query) use ($term) {
            $query->orWhere('purchase_no', 'like', '%' . $term . '%')
                ->orWhere('sub_total', 'like', '%' . $term . '%')
                ->orWhere('transport', 'like', '%' . $term . '%')
                ->orWhere('discount', 'like', '%' . $term . '%')
                ->orWhere('po_reference', 'like', '%' . $term . '%')
                ->orWhere('payment_terms', 'like', '%' . $term . '%');
        });

        return PurchaseListResource::collection($query->latest()->paginate($request->perPage));
    }

    // return purchase returns for specific suppliers
    public function purchaseReturnsBySupplier(Request $request, $slug)
    {
        $supplier = Supplier::where('slug', $slug)->first();
        $returns = PurchaseReturn::with('purchase.supplier')->whereHas(
            'purchase',
            function ($newQuery) use ($supplier) {
                $newQuery->where('supplier_id', $supplier->id);
            }
        )->latest()->paginate($request->perPage);

        return PurchaseReturnListResource::collection($returns);
    }

    // search supplier purchase returns
    public function searchPurchaseReturnsBySupplier(Request $request, $slug)
    {
        $supplier = Supplier::where('slug', $slug)->first();

        $term = $request->term;
        $query = PurchaseReturn::with('purchase.supplier', 'user');

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('date', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($term, $supplier) {
            $query->whereHas('purchase', function ($newQuery) use ($supplier) {
                $newQuery->where('supplier_id', $supplier->id);
            })->where(function ($query) use ($term) {
                $query->where('slug', 'LIKE', '%' . $term . '%')
                    ->orWhere('code', 'LIKE', '%' . $term . '%')
                    ->orWhere('reason', 'LIKE', '%' . $term . '%')
                    ->orWhere('total_return', 'LIKE', '%' . $term . '%')
                    ->orWhereHas('purchase', function ($newQuery) use ($term) {
                        $newQuery->where('purchase_no', 'LIKE', '%' . $term . '%')
                            ->orWhere('po_reference', 'LIKE', '%' . $term . '%')
                            ->orWhereHas('supplier', function ($anotherQuery) use ($term) {
                                $anotherQuery->where('name', 'LIKE', '%' . $term . '%')
                                    ->orWhere('phone', 'LIKE', '%' . $term . '%');
                            });
                    });
            });
        });

        return PurchaseReturnListResource::collection($query->latest()->paginate($request->perPage));
    }

    // return purchase payments for a specific supplier
    public function paymentsForSupplier(Request $request, $slug)
    {
        $supplier = Supplier::where('slug', $slug)->first();
        $payments = PurchasePayment::with(
            'purchase.supplier',
            'purchase.purchaseTax',
            'purchasePaymentTransaction.cashbookAccount',
            'user'
        )
            ->whereHas('purchase', function ($newQuery) use ($supplier) {
                $newQuery->where('supplier_id', $supplier->id);
            })->latest()->paginate($request->perPage);

        return PurchasePaymentResource::collection($payments);
    }

    // serach purchase payments for a specific supplier
    public function searchPaymentsForSupplier(Request $request, $slug)
    {
        $supplier = Supplier::where('slug', $slug)->first();

        $term = $request->term;
        $query = PurchasePayment::with(
            'purchase.supplier',
            'purchase.purchaseTax',
            'purchasePaymentTransaction.cashbookAccount',
            'user'
        );

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('date', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($term, $supplier) {
            $query->whereHas('purchase', function ($newQuery) use ($supplier) {
                $newQuery->where('supplier_id', $supplier->id);
            })->where(function ($query) use ($term) {
                $query->orWhere('amount', 'LIKE', '%' . $term . '%')
                    ->orWhereHas('purchase', function ($newQuery) use ($term) {
                        $newQuery->where('purchase_no', 'LIKE', '%' . $term . '%')
                            ->orWhere('sub_total', 'LIKE', '%' . $term . '%')
                            ->orWhere('po_reference', 'LIKE', '%' . $term . '%');
                    })
                    ->orWhereHas('purchasePaymentTransaction', function ($newQuery) use ($term) {
                        $newQuery->where('amount', 'LIKE', '%' . $term . '%')
                            ->orWhereHas('cashbookAccount', function ($newQuery) use ($term) {
                                $newQuery->where('account_number', 'LIKE', '%' . $term . '%')->where(
                                    'bank_name',
                                    'LIKE',
                                    '%' . $term . '%'
                                );
                            });
                    });
            });
        });

        return PurchasePaymentResource::collection($query->latest()->paginate($request->perPage));
    }

    // return non purchase transactions for supplier
    public function nonPurchaseTransForSupplier(Request $request, $slug)
    {
        $supplier = Supplier::where('slug', $slug)->first();
        $transactions = NonPurchasePayment::with('supplier', 'paymentTransaction.cashbookAccount')->where(
            'supplier_id',
            $supplier->id
        )->latest()->paginate($request->perPage);

        return NonPurchasePaymentListResource::collection($transactions);
    }

    // search non purchase transactions for supplier
    public function searchNonPurchaseTransForSupplier(Request $request, $slug)
    {
        $supplier = Supplier::where('slug', $slug)->first();

        $term = $request->term;
        $query = NonPurchasePayment::with('supplier', 'paymentTransaction.cashbookAccount');

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('date', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($supplier) {
            $query->where('supplier_id', $supplier->id);
        })->where(function ($query) use ($term) {
            $query->orWhere('amount', 'LIKE', '%' . $term . '%')
                ->orWhereHas('paymentTransaction', function ($newQuery) use ($term) {
                    $newQuery->where('amount', 'LIKE', '%' . $term . '%')
                        ->orWhereHas('cashbookAccount', function ($newQuery) use ($term) {
                            $newQuery->where('account_number', 'LIKE', '%' . $term . '%')->where(
                                'bank_name',
                                'LIKE',
                                '%' . $term . '%'
                            );
                        });
                });
        });

        return NonPurchasePaymentListResource::collection($query->latest()->paginate($request->perPage));
    }


    // csv import
    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'mimes:csv', 'file'],
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $data = SimpleExcelReader::create($file, 'csv')->getRows();

            $rules = [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20|min:3',
                'email' => 'nullable|email|max:255|min:3|unique:suppliers,email',
                'company_name' => 'nullable|string|max:100|min:2',
                'address' => 'nullable|string|max:255',
            ];

            foreach ($data as $key => $item) {
                $validator = Validator::make($item, $rules);
                if ($validator->passes()) {
                    Supplier::create(
                        $this->incrementSupplierId() +
                            $validator->validated()
                    );
                } else {
                    return response()->json([
                        'message' => $validator->errors()->first(),
                        'row_number' => $key + 1
                    ], 422);
                }
                // if($key == 100) break;
            }
            return response()->json([
                'message' => 'Supplier imported successfully'
            ]);
        }
    }

    #[ArrayShape(['supplier_id' => "int"])] public function incrementSupplierId(): array
    {
        $supplier_id = 1;
        $lastClient = Supplier::latest('id')->first();
        if ($lastClient) {
            $supplier_id = (int) $lastClient->supplier_id + 1;
        }
        return [
            'supplier_id' => $supplier_id
        ];
    }

    public function specificSupplierLedger($slug)
    {
        $supplier = Supplier::where('slug', $slug)->first();

        $data = DB::select("SELECT DATE
	,particulars
	,slug
	,action_type
	,debit
	,discount
	,credit
	,original_date
FROM (
	SELECT created_at DATE
		,CONCAT (
			'Purchase [APP-'
			,`purchase_no`
			,']'
			) particulars
		,`slug`
		,'purchase' AS action_type
		,IFNULL(sub_total, 0) + IFNULL(transport, 0) - IFNULL(discount, 0) + IFNULL((SELECT ((vi.rate / 100) * (ii.sub_total - IFNULL((SELECT sum(ci.total_return) FROM purchase_returns ci WHERE ii.id = ci.purchase_id),0) )) FROM vat_rates vi WHERE vi.id = ii.tax_id), 0)  debit
		,`discount`
		,0 credit
		,purchase_date AS original_date
		,supplier_id
	FROM `purchases` ii


	UNION ALL


	SELECT ci.created_at DATE
		,CONCAT (
			'Purchase payment [APP-'
			,`purchase_id`
			,']'
			) particulars
		,ci.slug
		,'purchase-payment' AS action_type
		,0 debit
		,ci.discount
		,IFNULL(ci.amount, 0) credit
		,ci.DATE AS original_date
		,ii.supplier_id
	FROM `purchases` ii
		,purchase_payments ci
	WHERE ii.id = ci.purchase_id

	UNION ALL

	SELECT ci.created_at DATE
		,CONCAT (
			'Purchase Return [APP-'
			,`purchase_id`
			,']'
			) particulars
		,ci.slug
		,'purchase-return' AS action_type
		,0 debit
		,0 discount
		,IFNULL(ci.total_return, 0) credit
		,ci.DATE AS original_date
		,ii.supplier_id
	FROM `purchases` ii
		,purchase_returns ci
	WHERE ii.id = ci.purchase_id
	) v
WHERE v.supplier_id = " . $supplier->id . "
ORDER BY `date`");

        // if credit plus, debit minus balance, always discount minus
        $totalDiscount = $totalDebit = $totalCredit = $finalBalance = 0;
        $balance = 0;
        foreach ($data as &$ledger) {
            if ($ledger->credit == 0) {
                $ledger->balance = $balance + $ledger->debit;
                $balance = $ledger->balance;
            } else {
                $balance =  $ledger->balance = $balance - $ledger->credit - $ledger->discount;
                if($ledger->particulars == 'Invoice payment [ATI- (Using Balance)]')
                {
                    $balance =  $balance + $ledger->credit;
                }
            }
            $totalDiscount  += $ledger->discount;
            $totalDebit  += $ledger->debit;
            $totalCredit  += $ledger->credit;
        }

        $finalBalance = $totalDebit - $totalCredit;
        return [
            'items' => $data,
            'totalDiscount' => $totalDiscount,
            'totalDebit' => $totalDebit,
            'totalCredit' => $totalCredit,
            'finalBalance' => $finalBalance,
        ];

    }
}