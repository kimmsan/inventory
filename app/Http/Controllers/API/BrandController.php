<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Exception;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class BrandController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:brands-management', ['except' => ['allBrands']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return BrandResource::collection(Brand::latest()->paginate($request->perPage));
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
            'name' => 'required|string|max:255|unique:brands,name',
            'shortCode' => 'required|string|max:50|unique:brands,code',
            'note' => 'nullable|string|max:255',
        ]);
        try {
            // upload brand logo and set the name
            $imageName = '';
            if ($request->image) {
                $imageName = time().'.'.explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
                Image::make($request->image)->save(public_path('images/brands/').$imageName);
            }

            // store brand
            Brand::create([
                'name' => $request->name,
                'code' => $request->shortCode,
                'image' => $imageName,
                'note' => clean($request->note),
                'status' => $request->status,
            ]);

            return $this->responseWithSuccess('Brand added successfully');
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
            $brand = Brand::where('slug', $slug)->first();

            return new BrandResource($brand);
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
        $brand = Brand::where('slug', $slug)->first();

        // validate request
        $this->validate($request, [
            'name' => 'required|string|max:255|unique:brands,name,'.$brand->id,
            'shortCode' => 'required|string|max:50|unique:brands,name,'.$brand->id,
            'note' => 'nullable|string|max:255',
        ]);

        try {
            // delete the old logo and upload the new one
            $imageName = $brand->image;
            if ($request->image) {
                if ($imageName) {
                    @unlink(public_path('images/brands/'.$imageName));
                }
                $imageName = time().'.'.explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
                Image::make($request->image)->save(public_path('images/brands/').$imageName);
            }

            // update brand
            $brand->update([
                'name' => $request->name,
                'code' => $request->shortCode,
                'image' => $imageName,
                'note' => clean($request->note),
                'status' => $request->status,
            ]);

            return $this->responseWithSuccess('Brand updated successfully');
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
            $brand = Brand::where('slug', $slug)->first();

            // delete the old logo and upload the new one
            $imageName = $brand->image;
            if ($imageName) {
                if ($imageName) {
                    @unlink(public_path('images/brands/'.$imageName));
                }
            }

            $brand->delete();

            return $this->responseWithSuccess('Brand deleted successfully');
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

        $query = Brand::where('name', 'LIKE', '%'.$term.'%')
            ->orWhere('code', 'LIKE', '%'.$term.'%')
            ->orWhere('note', 'LIKE', '%'.$term.'%')
            ->latest()->paginate($request->perPge);

        return BrandResource::collection($query);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allBrands()
    {
        $brands = Brand::where('status', 1)->latest()->get();

        return BrandResource::collection($brands);
    }
}
