<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\InvoiceReturn;
use App\Models\InvoicePayment;
use App\Models\NonInvoicePayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;
use App\Http\Resources\InvoiceResource;
use App\Http\Requests\ClientStoreRequest;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Http\Resources\ClientListResource;
use App\Notifications\WelcomeNotification;
use App\Http\Resources\InvoiceListResource;
use Illuminate\Support\Facades\Notification;
use App\Http\Resources\InvoicePaymentResource;
use Intervention\Image\Facades\Image as Image;
use App\Http\Resources\InvoiceForPaymentResource;
use App\Http\Resources\InvoiceReturnListResource;
use App\Http\Resources\NonInvoicePaymentListResource;
use App\Http\Resources\ClientWithInvoicePaymentResource;
use App\Http\Resources\ClientWithNonInvoicePaymentResource;

class ClientController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:client-list', ['only' => ['index', 'search']]);
        $this->middleware('can:client-create', ['only' => ['create']]);
        $this->middleware('can:client-view', ['only' => ['show']]);
        $this->middleware('can:client-edit', ['only' => ['update']]);
        $this->middleware('can:client-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return ClientListResource::collection(Client::orderBy('client_id', 'DESC')->paginate($request->perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientStoreRequest $request)
    {
        try {
            // generate code
            $code = 1;
            $lastClient = Client::latest()->first();
            if ($lastClient) {
                $code = $lastClient->client_id + 1;
            }

            // upload thumbnail and set the name
            $imageName = '';
            if ($request->image) {
                $imageName = time() . '.' . explode(
                    '/',
                    explode(':', substr($request->image, 0, strpos($request->image, ';')))[1]
                )[1];
                Image::make($request->image)->save(public_path('images/clients/') . $imageName);
            }

            // create client
            $userSchema = Client::create([
                'name' => $request->name,
                'client_id' => $code,
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
                    Notification::send($userSchema, new WelcomeNotification($userSchema, [
                        'isSendEmail' => $request->isSendEmail,
                        'isSendSMS' => $request->isSendSMS,
                    ]));
                }
            } catch (Exception $e) {
                //handle email error here if necessary
                throw new Exception($e);
            }

            return $this->responseWithSuccess('Client added successfully');
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
            $client = Client::where('slug', $slug)->first();

            return new ClientResource($client);
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
        // get client
        $client = Client::where('slug', $slug)->first();

        // validate request
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'phoneNumber' => 'required|string|max:20|min:3',
            'email' => 'nullable|email|max:255|min:3|unique:clients,email,' . $client->id,
            'companyName' => 'nullable|string|max:100|min:2',
            'address' => 'nullable|string|max:255',
        ]);
        try {

            // upload thumbnail and set the name
            $imageName = $client->image_path;
            if ($request->image) {
                if ($imageName) {
@unlink(public_path('images/clients/' . $imageName));
                }
                $imageName = time() . '.' . explode(
                    '/',
                    explode(':', substr($request->image, 0, strpos($request->image, ';')))[1]
                )[1];
                Image::make($request->image)->save(public_path('images/clients/') . $imageName);
            }

            // update client
            $client->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phoneNumber,
                'company_name' => $request->companyName,
                'type' => $request->supplierType,
                'address' => $request->address,
                'status' => $request->status,
                'image_path' => $imageName,
            ]);

            return $this->responseWithSuccess('Client updated successfully');
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
            $client = Client::where('slug', $slug)->first();

            $canDelete = true;
            if (count($client->clientInvoices) > 0 || count($client->clientNonInvoiceDues) > 0) {
                $canDelete = false;

                return $this->responseWithError('Sorry you can\'t delete this client!');
            }
            if ($canDelete) {
                //delete asset image
                if ($client->image_path) {
@unlink(public_path('images/clients/' . $client->image_path));
                }
                $client->delete();
            }

            return $this->responseWithSuccess('Asset deleted successfully');
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
        $query = Client::query();

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('created_at', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($term) {
            $query->where('name', 'Like', '%' . $term . '%')
                ->orWhere('client_id', 'Like', '%' . $term . '%')
                ->orWhere('email', 'Like', '%' . $term . '%')
                ->orWhere('phone', 'Like', '%' . $term . '%')
                ->orWhere('company_name', 'Like', '%' . $term . '%');
        });

        return ClientResource::collection($query->latest()->paginate($request->perPage));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allClients()
    {
        $clients = Client::where('status', 1)->latest()->get();

        return ClientListResource::collection($clients);
    }

    // return all clients for non invoice payments
    public function clientsForNonInvoicePayments()
    {
        $clients = Client::where('status', 1)->latest()->get();

        return ClientWithNonInvoicePaymentResource::collection($clients);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function clientInvoices($slug)
    {
        try {
            $client = Client::where('slug', $slug)->with('clientInvoices')->first();

            return InvoiceResource::collection($client->clientInvoices);
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function filterClientInvoices(Request $request)
    {
        $client = Client::where('slug', $request->clientSlug)->first();
        $products = [];
        $invoices = Invoice::with('client', 'invoiceProducts')->whereDoesntHave('invoiceReturn')->where(
            'client_id',
            $client->id
        );
        if (isset($request->products) && count($request->products) > 0) {
            // build the product array
            foreach ($request->products as $key => $product) {
                array_push($products, $product['id']);
            }
            // get the invoices
            $invoices = $invoices->whereHas('invoiceProducts', function ($firstQuery) use ($products) {
                $firstQuery->whereIn('product_id', $products);
            })->get();
        } else {
            // get the invoices
            $invoices = $invoices->get();
        }

        return InvoiceResource::collection($invoices);
    }

    // return client specific invoices
    public function specificClientInvoices($slug)
    {
        $client = Client::where('slug', $slug)->first();
        $invoices = Invoice::with(
            'client',
            'invoicePayments',
            'invoiceReturn',
            'invoiceTax',
            'invoiceProducts'
        )->where('client_id', $client->id)->get();

        return [
            'invoices' => InvoiceForPaymentResource::collection($invoices->where('calculated_due', '>', 0)),
            'client' => new ClientWithInvoicePaymentResource($client),
        ];
    }

    // return client all invoices
    public function clientAllInvoices(Request $request, $slug)
    {
        $client = Client::where('slug', $slug)->first();

        return InvoiceListResource::collection(Invoice::with(
            'client',
            'invoiceTax',
            'invoicePayments'
        )->where('client_id', $client->id)->latest()->paginate($request->perPage));
    }

    // search client invoices
    public function searchClientInvoices(Request $request, $slug)
    {
        $term = $request->term;

        $client = Client::where('slug', $slug)->first();

        $query = Invoice::with('client', 'invoiceTax', 'invoicePayments')->where('client_id', $client->id);

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('invoice_date', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($term) {
            $query->where(function ($query) use ($term) {
                $query->where('invoice_no', 'LIKE', '%' . $term . '%')
                    ->orWhere('sub_total', 'LIKE', '%' . $term . '%')
                    ->orWhere('po_reference', 'LIKE', '%' . $term . '%')
                    ->orWhere('payment_terms', 'LIKE', '%' . $term . '%')
                    ->orWhereHas('client', function ($newQuery) use ($term) {
                        $newQuery->where('name', 'LIKE', '%' . $term . '%')
                            ->orWhere('client_id', 'LIKE', '%' . $term . '%');
                    });
            });
        });

        return InvoiceListResource::collection($query->latest()->paginate($request->perPage));
    }

    // return client invoice returns
    public function clientInvoiceReturns(Request $request, $slug)
    {
        $client = Client::where('slug', $slug)->first();
        $invoices = InvoiceReturn::with('invoice.client', 'user')->whereHas(
            'invoice',
            function ($newQuery) use ($client) {
                $newQuery->where('client_id', $client->id);
            }
        );

        return InvoiceReturnListResource::collection($invoices->latest()->paginate($request->perPage));
    }

    // search client invoice returns
    public function searchClientInvoiceReturns(Request $request, $slug)
    {
        $term = $request->term;

        $client = Client::where('slug', $slug)->first();

        $query = InvoiceReturn::with('invoice.client', 'user');

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('date', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($term, $client) {
            $query->whereHas('invoice', function ($newQuery) use ($client) {
                $newQuery->where('client_id', $client->id);
            })->where(function ($query) use ($term) {
                $query->where('reason', 'LIKE', '%' . $term . '%')
                    ->orWhere('slug', 'LIKE', '%' . $term . '%')
                    ->orWhere('total_return', 'LIKE', '%' . $term . '%')
                    ->orWhereHas('invoice', function ($newQuery) use ($term) {
                        $newQuery->where('invoice_no', 'LIKE', '%' . $term . '%')
                            ->orWhere('po_reference', 'LIKE', '%' . $term . '%')
                            ->orWhereHas('client', function ($anotherQuery) use ($term) {
                                $anotherQuery->where('name', 'LIKE', '%' . $term . '%');
                            });
                    });
            });
        });

        return InvoiceReturnListResource::collection($query->latest()->paginate($request->perPage));
    }

    // return client invoice payments
    public function clientInvoicePayments(Request $request, $slug)
    {
        $client = Client::where('slug', $slug)->first();
        $payments = InvoicePayment::with(
            'invoice.client',
            'invoice.invoiceTax',
            'invoicePaymentTransaction.cashbookAccount',
            'user'
        )->whereHas(
            'invoice',
            function ($newQuery) use ($client) {
                $newQuery->whereHas('client', function ($anotherQuery) use ($client) {
                    $anotherQuery->where('client_id', $client->id);
                });
            }
        );

        return InvoicePaymentResource::collection($payments->latest()->paginate($request->perPage));
    }

    // search client invoice payments
    public function searchClientInvoicePayments(Request $request, $slug)
    {
        $term = $request->term;

        $client = Client::where('slug', $slug)->first();

        $query = InvoicePayment::with(
            'invoice.client',
            'invoice.invoiceTax',
            'invoicePaymentTransaction.cashbookAccount',
            'user'
        );

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('date', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($term, $client) {
            $query->whereHas('invoice', function ($newQuery) use ($client) {
                $newQuery->whereHas('client', function ($anotherQuery) use ($client) {
                    $anotherQuery->where('client_id', $client->id);
                });
            })->where(function ($query) use ($term) {
                $query->where('amount', '=', $term)
                    ->orWhereHas('invoice', function ($newQuery) use ($term) {
                        $newQuery->where('invoice_no', 'LIKE', '%' . $term . '%')
                            ->orWhere('po_reference', 'LIKE', '%' . $term . '%')
                            ->orWhereHas('client', function ($anotherQuery) use ($term) {
                                $anotherQuery->where('name', 'LIKE', '%' . $term . '%')
                                    ->orWhere('phone', 'LIKE', '%' . $term . '%');
                            });
                    })
                    ->orWhereHas('invoicePaymentTransaction', function ($newQuery) use ($term) {
                        $newQuery->where('cheque_no', 'LIKE', '%' . $term . '%')
                            ->orWhere('receipt_no', 'LIKE', '%' . $term . '%')
                            ->orWhereHas('cashbookAccount', function ($newQuery) use ($term) {
                                $newQuery->where('account_number', 'LIKE', '%' . $term . '%')
                                    ->orWhere('bank_name', 'LIKE', '%' . $term . '%');
                            });
                    });
            });
        });
        return InvoicePaymentResource::collection($query->latest()->paginate($request->perPage));
    }

    // return client non invoice payments
    public function clientNonInvoicePayments(Request $request, $slug)
    {
        $client = Client::where('slug', $slug)->first();
        $transactions = NonInvoicePayment::with('client', 'paymentTransaction.cashbookAccount')->where(
            'client_id',
            $client->id
        )->latest()->paginate($request->perPage);

        return NonInvoicePaymentListResource::collection($transactions);
    }

    // search non invoice payments
    public function searchClientNonInvoicePayments(Request $request, $slug)
    {
        $term = $request->term;

        $client = Client::where('slug', $slug)->first();

        $query = NonInvoicePayment::with('client', 'paymentTransaction.cashbookAccount');

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('date', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($term, $client) {
            $query->where('client_id', $client->id)->where(function ($query) use ($term) {
                $query->where('amount', 'LIKE', '%' . $term . '%')
                    ->orWhereHas('paymentTransaction', function ($newQuery) use ($term) {
                        $newQuery->where('cheque_no', 'LIKE', '%' . $term . '%')
                            ->orWhere('receipt_no', 'LIKE', '%' . $term . '%')->orWhereHas(
                                'cashbookAccount',
                                function ($newQuery) use ($term) {
                                    $newQuery->where('account_number', 'LIKE', '%' . $term . '%')
                                        ->orWhere('bank_name', 'LIKE', '%' . $term . '%');
                                }
                            );
                    });
            });
        });

        // return $query->toSql();

        return NonInvoicePaymentListResource::collection($query->latest()->paginate($request->perPage));
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
                'email' => 'nullable|email|max:255|min:3|unique:clients,email',
                'company_name' => 'nullable|string|max:100|min:2',
                'address' => 'nullable|string|max:255',
            ];

            foreach ($data as $key => $item) {
                $validator = Validator::make($item, $rules);
                if ($validator->passes()) {
                    Client::create(
                        $this->incrementClientId() +
                            $validator->validated()
                    );
                } else {
                    return response()->json([
                        'message' => $validator->errors()->first(),
                        'row_number' => $key + 1
                    ], 422);
                }
            }
            return response()->json([
                'message' => 'Clients imported successfully'
            ]);
        }
    }

    public function incrementClientId()
    {
        $clientId = 1;
        $lastClient = Client::latest('id')->first();
        if ($lastClient) {
            $clientId = (int) $lastClient->client_id + 1;
        }
        return [
            'client_id' => $clientId
        ];
    }

    // client
    public function specificClientLedger($slug)
    {
        $client = Client::where('slug', $slug)->first();
        $data = DB::select("SELECT date
	,particulars
	,slug
	,action_type
	,debit
	,discount
	,credit
    ,original_date
FROM (
	SELECT created_at date
		,CONCAT ('Invoice [ATI-',`invoice_no`, ']')  particulars
		,`slug`
		,'invoice' as action_type
		,IFNULL(sub_total, 0) + IFNULL((SELECT((vi.rate / 100 ) * ii.sub_total) FROM vat_rates vi WHERE vi.id = ii.tax_id ),0) - IFNULL(discount, 0) + IFNULL((SELECT sum(ci.total_return) FROM invoice_returns ci WHERE ii.id = ci.invoice_id),0) debit
		,`discount`
		,0 credit
        ,invoice_date as original_date
		,client_id

	FROM `invoices` ii

	UNION ALL

	SELECT ci.created_at date
		,CONCAT ('Invoice payment [ATI-', `invoice_id`, ']') particulars
		,ci.slug
		,'invoice-payment' as action_type
		,0 debit
		, ci.discount
		,IFNULL(ci.amount, 0) credit
        ,ci.date as original_date
		,ii.client_id

	FROM `invoices` ii
		,invoice_payments ci
	WHERE ii.id = ci.invoice_id

	UNION ALL

	SELECT ci.created_at date
		,CONCAT (
			'Invoice Return [ATI-'
			,`invoice_id`
			, ']') particulars
		,ci.slug
		,'invoice-return' as action_type
		,0 debit
		,0 discount
		,IFNULL(ci.total_return, 0) credit
        ,ci.date as original_date
		,ii.client_id
	FROM `invoices` ii
		,invoice_returns ci
	WHERE ii.id = ci.invoice_id
	) v
WHERE v.client_id = " . $client->id . "
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