<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssetTypeResource;
use App\Models\AssetType;
use Exception;
use Illuminate\Http\Request;

class AssetTypeController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:asset-type-list', ['only' => ['index', 'search']]);
        $this->middleware('can:asset-type-create', ['only' => ['create']]);
        $this->middleware('can:asset-type-edit', ['only' => ['update']]);
        $this->middleware('can:asset-type-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return AssetTypeResource::collection(AssetType::latest()->paginate($request->perPage));
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
            'name' => 'required|string|max:50|unique:asset_types',
            'note' => 'nullable|string|max:255',
        ]);
        // save asset type
        AssetType::create([
            'name' => $request->name,
            'note' => clean($request->note),
            'status' => $request->status,
        ]);

        return $this->responseWithSuccess('Asset type added successfully');
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
            $assetType = AssetType::where('slug', $slug)->first();

            return $assetType;
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
        $assetType = AssetType::where('slug', $slug)->first();
        // validate request
        $this->validate($request, [
            'name' => 'required|string|max:50|unique:asset_types,name,'.$assetType->id,
            'note' => 'nullable|string|max:255',
        ]);

        try {
            // update asset type
            $assetType->update([
                'name' => $request->name,
                'note' => clean($request->note),
                'status' => $request->status,
            ]);

            return $this->responseWithSuccess('Asset Type updated successfully');
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
            $assetType = AssetType::where('slug', $slug)->first();
            $assetType->delete();

            return $this->responseWithSuccess('Asset type deleted successfully');
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

        $query = AssetType::where('name', 'LIKE', '%'.$term.'%')
            ->orWhere('slug', 'LIKE', '%'.$term.'%')
            ->orWhere('note', 'LIKE', '%'.$term.'%')
            ->latest()->paginate($request->perPage);

        return AssetTypeResource::collection($query);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allAssets()
    {
        $assetTypes = AssetType::where('status', 1)->latest()->get();

        return AssetTypeResource::collection($assetTypes);
    }
}
