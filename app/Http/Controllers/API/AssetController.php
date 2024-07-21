<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssetResource;
use App\Models\Asset;
use App\Models\AssetType;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class AssetController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:asset-list', ['only' => ['index', 'search']]);
        $this->middleware('can:asset-create', ['only' => ['create']]);
        $this->middleware('can:asset-view', ['only' => ['show']]);
        $this->middleware('can:asset-edit', ['only' => ['update']]);
        $this->middleware('can:asset-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return AssetResource::collection(Asset::with('assetType', 'user')->latest()->paginate($request->perPage));
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
            'name' => 'required|string|max:50|unique:assets,name',
            'assetType' => 'required',
            'assetCost' => 'required|numeric',
            'depreciation' => 'required',
            'salvageValue' => 'required|numeric|min:0|max:'.$request->assetCost,
            'usefulLife' => $request->depreciation == 1 ? 'required|numeric|min:.1' : '',
            'note' => 'nullable|string|max:255',
            'date' => $request->depreciation == 1 ? 'required|date|after_or_equal:today' : 'required',
        ]);
        try {
            // upload thumbnail and set the name
            $imageName = '';
            if ($request->image) {
                $imageName = time().'.'.explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
                Image::make($request->image)->save(public_path('images/assets/').$imageName);
            }

            // assign values
            $salvageValue = $request->salvageValue;
            $usefulLife = $request->usefulLife;
            if ($request->depreciation == 1 && $request->depreciationType == 'Month') {
                $depreciationType = 0;
                $duration = $request->usefulLife;
            } elseif ($request->depreciation == 1 && $request->depreciationType == 'Year') {
                $depreciationType = 1;
                $duration = $request->usefulLife * 12;
            } else {
                $depreciationType = $salvageValue = $usefulLife = $dailyDepreciation = $later = null;
            }

            // calculate daily depreciation
            if ($request->depreciation == 1) {
                $earlier = new DateTime($request->date);
                $later = Carbon::parse($earlier)->addMonths($duration);
                $abs_diff = $later->diff($earlier)->format('%a');
                $dailyDepreciation = $request->assetCost / $abs_diff;
            }

            // store asset
            Asset::create([
                'name' => $request->name,
                'cat_id' => $request->assetType['id'],
                'asset_cost' => $request->assetCost,
                'depreciation' => $request->depreciation,
                'depreciation_type' => $depreciationType,
                'salvage_value' => $salvageValue,
                'useful_life' => $usefulLife,
                'daily_depreciation' => $dailyDepreciation,
                'note' => clean($request->note),
                'image_path' => $imageName,
                'date' => $request->date,
                'expire_date' => $later,
                'created_by' => auth()->user()->id,
                'status' => $request->status,
            ]);

            return $this->responseWithSuccess('Asset added successfully');
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
            $asset = Asset::with('assetType')->where('slug', $slug)->first();

            return new AssetResource($asset);
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
        $asset = Asset::where('slug', $slug)->first();
        // validate request
        $this->validate($request, [
            'name' => 'required|string|max:50|unique:assets,name,'.$asset->id,
            'assetType' => 'required',
            'assetCost' => 'required|numeric',
            'depreciation' => 'required',
            'salvageValue' => 'nullable|numeric|min:0|max:'.$request->assetCost,
            'usefulLife' => $request->depreciation == 1 ? 'required|numeric|min:0' : '',
            'note' => 'nullable|string|max:255',
        ]);

        try {
            $assetType = AssetType::where('slug', $request->assetType['slug'])->first();
            // upload thumbnail and set the name
            $imageName = $asset->image_path;
            if ($request->image) {
                if ($imageName) {
                    @unlink(public_path('images/assets/'.$imageName));
                }
                $imageName = time().'.'.explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
                Image::make($request->image)->save(public_path('images/assets/').$imageName);
            }

            // assign values
            $salvageValue = $request->salvageValue;
            $usefulLife = $request->usefulLife;

            if ($request->depreciation == 1 && $request->depreciationType == 'Month') {
                $depreciationType = 0;
                $duration = $request->usefulLife;
            } elseif ($request->depreciation == 1 && $request->depreciationType == 'Year') {
                $depreciationType = 1;
                $duration = $request->usefulLife * 12;
            } else {
                $depreciationType = $salvageValue = $usefulLife = $dailyDepreciation = $later = null;
            }

            // calculate daily depreciation
            if ($request->depreciation == 1) {
                $earlier = new DateTime($request->date);
                $later = Carbon::parse($earlier)->addMonths($duration);
                $abs_diff = $later->diff($earlier)->format('%a');
                $dailyDepreciation = $request->assetCost / $abs_diff;
            }

            // update asset
            $asset->update([
                'name' => $request->name,
                'cat_id' => $assetType->id,
                'asset_cost' => $request->assetCost,
                'depreciation' => $request->depreciation,
                'depreciation_type' => $depreciationType,
                'salvage_value' => $salvageValue,
                'useful_life' => $usefulLife,
                'daily_depreciation' => $dailyDepreciation,
                'expire_date' => $later,
                'note' => clean($request->note),
                'image_path' => $imageName,
                'date' => $request->date,
                'created_by' => auth()->user()->id,
                'status' => $request->status,
            ]);

            return $this->responseWithSuccess('Asset updated successfully');
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
            $asset = Asset::where('slug', $slug)->first();
            //delete asset image
            if ($asset->image_path) {
                @unlink(public_path('images/assets/'.$asset->image_path));
            }
            $asset->delete();

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
        $query = Asset::with('assetType');

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('date', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($term) {
            $query->where('name', 'LIKE', '%'.$term.'%')
                ->orWhere('asset_cost', 'LIKE', '%'.$term.'%')
                ->orWhere('salvage_value', 'LIKE', '%'.$term.'%')
                ->orWhere('useful_life', 'LIKE', '%'.$term.'%')
                ->orWhereHas('assetType', function ($newQuery) use ($term) {
                    $newQuery->where('name', 'LIKE', '%'.$term.'%');
                });
        });

        return AssetResource::collection($query->latest()->paginate($request->perPage));
    }
}
