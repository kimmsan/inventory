<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductSelectResource;
use App\Http\Resources\ProductSubCategoryResource;

class ProSubCatController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:product-sub-category-list', ['only' => ['index', 'search']]);
        $this->middleware('can:product-sub-category-create', ['only' => ['create']]);
        $this->middleware('can:product-sub-category-edit', ['only' => ['update']]);
        $this->middleware('can:product-sub-category-create', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return ProductSubCategoryResource::collection(ProductSubCategory::with('category')->latest()->paginate($request->perPage));
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
            'name' => 'required|string|max:50|unique:product_sub_categories',
            'category' => 'required',
            'note' => 'nullable|string|max:255',
        ]);
        try {
            // generate code
            $code = 1;
            $prevCode = ProductSubCategory::latest()->first();
            if ($prevCode) {
                $code = $prevCode->code + 1;
            }
            // store sub category
            ProductSubCategory::create([
                'name' => $request->name,
                'code' => $code,
                'cat_id' => $request->category['id'],
                'note' => clean($request->note),
                'status' => $request->status,
            ]);

            return $this->responseWithSuccess('Sub category added successfully');
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
            $subCategory = ProductSubCategory::with('category')->where('slug', $slug)->first();

            return new ProductSubCategoryResource($subCategory);
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
        $subCategory = ProductSubCategory::where('slug', $slug)->first();
        // validate request
        $this->validate($request, [
            'name' => 'required|string|max:50|unique:product_sub_categories,name,'.$subCategory->id,
            'category' => 'required',
            'note' => 'nullable|string|max:255',
        ]);

        try {
            // update sub category
            $subCategory->update([
                'name' => $request->name,
                'cat_id' => $request->category['id'],
                'note' => clean($request->note),
                'status' => $request->status,
            ]);

            return $this->responseWithSuccess('Sub category updated successfully');
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
            $subCategory = ProductSubCategory::where('slug', $slug)->first();
            $subCategory->delete();

            return $this->responseWithSuccess('Sub category deleted successfully');
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

        $query = ProductSubCategory::with('category')->where('name', 'LIKE', '%'.$term.'%')
            ->orWhere('note', 'LIKE', '%'.$term.'%')
            ->orWhereHas('category', function ($newQuery) use ($term) {
                $newQuery->where('name', 'LIKE', '%'.$term.'%');
            })->latest()->paginate($request->perPage);

        return ProductSubCategoryResource::collection($query);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allSubCategories()
    {
        $subCategories = ProductSubCategory::where('status', 1)->latest()->get();

        return ProductSubCategoryResource::collection($subCategories);
    }

    // retun subcategories by category
    public function subCategoriesByCategory($slug)
    {
        if ($slug == 'all') {
            $subCategories = ProductSubCategory::latest()->get();
            $products = Product::latest()->get();
        } else {
            $category = ProductCategory::where('slug', $slug)->first();
            $subCategories = ProductSubCategory::where('cat_id', $category->id)->latest()->get();
            $products = Product::with('proSubCategory.category')->whereHas('proSubCategory', function ($newQuery) use ($category) {
                $newQuery->whereHas('category', function ($newQuery) use ($category) {
                    $newQuery->where('id', $category->id);
                });
            })->get();
        }

        return [
            'cats' => ProductSubCategoryResource::collection($subCategories),
            'products' => ProductResource::collection($products),
        ];
    }

    // retun subcategories by category
    public function allSubCategoriesByCategory($slug)
    {
        if ($slug == 'all') {
            $subCategories = ProductSubCategory::latest()->get();
            $products = Product::latest()->paginate(5);
        } else {
            $category = ProductCategory::where('slug', $slug)->first();
            $subCategories = ProductSubCategory::where('cat_id', $category->id)->latest()->get();
            $products = Product::with('proSubCategory.category')->whereHas('proSubCategory', function ($newQuery) use ($category) {
                $newQuery->whereHas('category', function ($newQuery) use ($category) {
                    $newQuery->where('id', $category->id);
                });
            })->paginate(5);
        }

        return [
            'cats' => ProductSubCategoryResource::collection($subCategories),
            'products' => ProductSelectResource::collection($products),
        ];
    }
}