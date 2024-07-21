<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentMethodResource;
use App\Models\PaymentMethod;
use Exception;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:payment-method-management', ['except' => ['allMethods']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return PaymentMethodResource::collection(PaymentMethod::latest()->paginate($request->perPage));
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
            'name' => 'required|string|max:50|unique:payment_methods',
            'shortCode' => 'required|string|max:50|unique:payment_methods,code',
            'note' => 'nullable|string|max:255',
        ]);
        // save payment method
        PaymentMethod::create([
            'name' => $request->name,
            'code' => $request->shortCode,
            'note' => $request->note,
            'status' => $request->status,
        ]);

        return $this->responseWithSuccess('Payment method added successfully');
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
            $method = PaymentMethod::where('slug', $slug)->first();

            return $method;
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
        $method = PaymentMethod::where('slug', $slug)->first();
        // validate request
        $this->validate($request, [
            'name' => 'required|string|max:50|unique:payment_methods,name,'.$method->id,
            'shortCode' => 'required|string|max:50|unique:payment_methods,code,'.$method->id,
            'note' => 'nullable|string|max:255',
        ]);

        try {
            // update payment method
            $method->update([
                'name' => $request->name,
                'code' => $request->shortCode,
                'note' => $request->note,
                'status' => $request->status,
            ]);

            return $this->responseWithSuccess('Payment method updated successfully');
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
            $method = PaymentMethod::where('slug', $slug)->first();
            $method->delete();

            return $this->responseWithSuccess('Payment method deleted successfully');
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * search resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $term = $request->term;

        $query = PaymentMethod::where('name', 'LIKE', '%'.$term.'%')
            ->orWhere('code', 'LIKE', '%'.$term.'%')
            ->orWhere('note', 'LIKE', '%'.$term.'%')
            ->latest()->paginate($request->perPage);

        return PaymentMethodResource::collection($query);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allMethods()
    {
        $methods = PaymentMethod::where('status', 1)->latest()->get();

        return PaymentMethodResource::collection($methods);
    }
}
