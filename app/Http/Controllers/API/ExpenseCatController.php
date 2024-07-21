<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseCategoryResource;
use App\Models\ExpenseCategory;
use Exception;
use Illuminate\Http\Request;

class ExpenseCatController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:expense-category-list', ['only' => ['index', 'search']]);
        $this->middleware('can:expense-category-create', ['only' => ['store']]);
        $this->middleware('can:expense-category-edit', ['only' => ['update']]);
        $this->middleware('can:expense-category-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return ExpenseCategoryResource::collection(ExpenseCategory::latest()->paginate($request->perPage));
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
            'name' => 'required|string|max:50|unique:expense_categories',
            'note' => 'nullable|string|max:255',
        ]);

        try {
            // generate code
            $code = 1;
            $prevCode = ExpenseCategory::latest()->first();
            if ($prevCode) {
                $code = $prevCode->code + 1;
            }

            // save category
            ExpenseCategory::create([
                'name' => $request->name,
                'code' => $code,
                'note' => clean($request->note),
                'status' => $request->status,
            ]);

            return $this->responseWithSuccess('Category added successfully');
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
            $category = ExpenseCategory::where('slug', $slug)->first();

            return new ExpenseCategoryResource($category);
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
        $category = ExpenseCategory::where('slug', $slug)->first();
        // validate request
        $this->validate($request, [
            'name' => 'required|string|max:50|unique:expense_categories,name,'.$category->id,
            'note' => 'nullable|string|max:255',
        ]);

        try {
            // update category
            $category->update([
                'name' => $request->name,
                'note' => clean($request->note),
                'status' => $request->status,
            ]);

            return $this->responseWithSuccess('Category updated successfully');
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
            $category = ExpenseCategory::with('catAllExpenses')->where('slug', $slug)->first();
            $category->catAllExpenses->each->delete();
            $category->delete();

            return $this->responseWithSuccess('Category deleted successfully');
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

        $query = ExpenseCategory::where('name', 'LIKE', '%'.$term.'%')
            ->orWhere('slug', 'LIKE', '%'.$term.'%')
            ->orWhere('code', 'LIKE', '%'.$term.'%')
            ->orWhere('note', 'LIKE', '%'.$term.'%')
            ->latest()->paginate($request->perPage);

        return ExpenseCategoryResource::collection($query);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allCategories()
    {
        $categories = ExpenseCategory::where('status', 1)->latest()->get();

        return ExpenseCategoryResource::collection($categories);
    }
}
