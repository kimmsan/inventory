<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdjustmentListResource;
use App\Http\Resources\AdjustmentResource;
use App\Models\AdjustmentProduct;
use App\Models\InventoryAdjustment;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class InventoryAdjustmentController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:adjustment-list', ['only' => ['index', 'search']]);
        $this->middleware('can:adjustment-create', ['only' => ['create']]);
        $this->middleware('can:adjustment-view', ['only' => ['show']]);
        $this->middleware('can:adjustment-edit', ['only' => ['update']]);
        $this->middleware('can:adjustment-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return AdjustmentListResource::collection(InventoryAdjustment::latest()->paginate($request->perPage));
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
            'adjustmentReason' => 'required|string|max:255',
            'selectedProducts' => 'required|array|min:1',
            'selectedProducts.*' => 'required|distinct',
            'adjustmentDate' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ]);

        try {
            // generate code
            $code = 1;
            $prevCode = InventoryAdjustment::latest()->first();
            if ($prevCode) {
                $code = $prevCode->code + 1;
            }

            // get logged in user id
            $userId = auth()->user()->id;

            // create adjustment
            $adjustment = InventoryAdjustment::create([
                'reason' => $request->adjustmentReason,
                'code' => $code,
                'date' => $request->adjustmentDate,
                'note' => clean($request->note),
                'status' => $request->status,
                'created_by' => $userId,
            ]);

            // store adjustment products
            foreach ($request->selectedProducts as $key => $selectedProduct) {
                // get the product
                $product = Product::where('slug', $selectedProduct['slug'])->first();

                // update inventory count
                if ($selectedProduct['adjustType'] == 'Increment') {
                    $stock = $product->inventory_count + $selectedProduct['adjustQty'];
                } else {
                    $stock = $product->inventory_count - $selectedProduct['adjustQty'];
                }
                $purchasePrice = ($product->purchase_price * $stock) / $stock;

                // update product purchase price
                $product->update([
                    'purchase_price' => $purchasePrice,
                    'inventory_count' => $stock,
                ]);

                // store product
                AdjustmentProduct::create([
                    'adjustment_id' => $adjustment->id,
                    'product_id' => $product->id,
                    'type' => $selectedProduct['adjustType'] == 'Increment' ? 1 : 0,
                    'purchase_price' => $selectedProduct['purchasePrice'],
                    'quantity' => $selectedProduct['adjustQty'],
                ]);
            }

            return $this->responseWithSuccess('Adjustment added successfully');
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
            $adjustment = InventoryAdjustment::with('adjustmentProducts.product.productUnit', 'user')->where('slug', $slug)->first();

            return new AdjustmentResource($adjustment);
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
        // get the adjustment
        $adjustment = InventoryAdjustment::where('slug', $slug)->first();

        // validate request
        $this->validate($request, [
            'adjustmentReason' => 'required|string|max:255',
            'selectedProducts' => 'required|array|min:1',
            'selectedProducts.*' => 'required|distinct',
            'adjustmentDate' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ]);

        try {
            // update adjustment
            $adjustment->update([
                'reason' => $request->adjustmentReason,
                'date' => $request->adjustmentDate,
                'note' => clean($request->note),
                'status' => $request->status,
            ]);

            // delete the product adjustmens
            $adjustment->adjustmentProducts->each->delete();

            // store purchase products
            foreach ($request->selectedProducts as $key => $selectedProduct) {
                // get the product
                $product = Product::where('slug', $selectedProduct['slug'])->first();

                $productStock = $product->inventory_count;
                if ($request->oriAdjustType == 'Increment') {
                    $productStock = $product->inventory_count - $request->oriAdjustQty;
                } else {
                    $productStock = $product->inventory_count + $request->oriAdjustQty;
                }

                // update inventory count
                if ($selectedProduct['adjustType'] == 'Increment') {
                    $productStock = $productStock + $selectedProduct['adjustQty'];
                } else {
                    $productStock = $productStock - $selectedProduct['adjustQty'];
                }
                $purchasePrice = ($product->purchase_price * $productStock) / $productStock;

                // update product purchase price
                $product->update([
                    'purchase_price' => $purchasePrice,
                    'inventory_count' => $productStock,
                ]);

                // store product
                AdjustmentProduct::create([
                    'adjustment_id' => $adjustment->id,
                    'product_id' => $product->id,
                    'type' => $selectedProduct['adjustType'] == 'Increment' ? 1 : 0,
                    'purchase_price' => $selectedProduct['purchasePrice'],
                    'quantity' => $selectedProduct['adjustQty'],
                ]);
            }

            return $this->responseWithSuccess('Adjustment updated successfully');
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
            $adjustment = InventoryAdjustment::with('adjustmentProducts')->where('slug', $slug)->first();

            // check if the adjustment can be delete
            $canDelete = true;
            foreach ($adjustment->adjustmentProducts as $key => $adjustmentProduct) {
                if ($adjustmentProduct->type == 1 && $adjustmentProduct->product->inventory_count <= $adjustmentProduct->quantity) {
                    $canDelete = false;
                    break;
                }
            }

            if ($canDelete) {
                foreach ($adjustment->adjustmentProducts as $key => $adjustmentProduct) {
                    if ($adjustmentProduct->type == 1) {
                        $adjustmentProduct->product->update([
                            'inventory_count' => $adjustmentProduct->product->inventory_count - $adjustmentProduct->quantity,
                        ]);
                    } else {
                        $adjustmentProduct->product->update([
                            'inventory_count' => $adjustmentProduct->product->inventory_count + $adjustmentProduct->quantity,
                        ]);
                    }
                }
                $adjustment->delete();
            } else {
                return $this->responseWithError('Sorry you can\'t delete this adjustment!');
            }

            return $this->responseWithSuccess('Adjustment deleted successfully');
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
        $query = InventoryAdjustment::with('user');

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('date', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($term) {
            $query->where('reason', 'LIKE', '%'.$term.'%')->orWhere('code', 'LIKE', '%'.$term.'%');
        });

        return AdjustmentListResource::collection($query->latest()->paginate($request->perPage));
    }
}
