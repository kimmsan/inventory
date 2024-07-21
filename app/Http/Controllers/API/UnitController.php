<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use Exception;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:units-management', ['except' => ['allUnits']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return UnitResource::collection(Unit::latest()->paginate($request->perPage));
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
            'name' => 'required|string|max:50|unique:units',
            'code' => 'required|string|max:50|unique:units,code',
            'note' => 'nullable|string|max:255',
        ]);
        // save unit
        Unit::create([
            'name' => $request->name,
            'code' => $request->code,
            'note' => $request->note,
            'status' => $request->status,
        ]);

        return $this->responseWithSuccess('Unit added successfully');
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
            $unit = Unit::where('slug', $slug)->first();

            return new UnitResource($unit);
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
        $unit = Unit::where('slug', $slug)->first();
        // validate request
        $this->validate($request, [
            'name' => 'required|string|max:50|unique:units,name,'.$unit->id,
            'code' => 'required|string|max:50|unique:units,code,'.$unit->id,
            'note' => 'nullable|string|max:255',
        ]);

        try {
            // update unit
            $unit->update([
                'name' => $request->name,
                'code' => $request->code,
                'note' => $request->note,
                'status' => $request->status,
            ]);

            return $this->responseWithSuccess('Unit updated successfully');
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
            $unit = Unit::where('slug', $slug)->first();
            $unit->delete();

            return $this->responseWithSuccess('Unit deleted successfully');
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

        $query = Unit::where('name', 'LIKE', '%'.$term.'%')
            ->orWhere('code', 'LIKE', '%'.$term.'%')
            ->orWhere('note', 'LIKE', '%'.$term.'%')
            ->latest()->paginate($request->perPage);

        return UnitResource::collection($query);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allUnits()
    {
        $units = Unit::where('status', 1)->latest()->get();

        return UnitResource::collection($units);
    }
}
