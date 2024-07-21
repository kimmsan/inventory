<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use Exception;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:user-permission', ['except' => ['allPermissions']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return PermissionResource::collection(Permission::orderBy('name', 'ASC')->paginate($request->perPage));
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
            'name' => 'required|string|max:50|unique:permissions,name',
            'guardName' => 'required|string|max:255',
        ]);

        try {
            // save permission
            Permission::create([
                'name' => $request->name,
                'guard_name' => $request->guardName,

            ]);

            return $this->responseWithSuccess('Permission added successfully');
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
            $permission = Permission::where('slug', $slug)->first();

            return new PermissionResource($permission);
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
        $permission = Permission::where('slug', $slug)->first();
        // validate request
        $this->validate($request, [
            'name' => 'required|string|max:50',
            'slug' => 'required|string|max:50|unique:permissions,slug,'.$permission->id,
            'guardName' => 'required|string|max:255',
        ]);

        try {
            // update permission
            $permission->update([
                'name' => $request->name,
                'guard_name' => $request->guardName,
                'slug' => $request->slug,
            ]);

            return $this->responseWithSuccess('Permission updated successfully');
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
            $permission = Permission::where('slug', $slug)->first();
            $permission->delete();

            return $this->responseWithSuccess('Permission deleted successfully');
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

        $query = Permission::where('name', 'LIKE', '%'.$term.'%')
            ->orWhere('guard_name', 'LIKE', '%'.$term.'%')
            ->orWhere('slug', 'LIKE', '%'.$term.'%')
            ->latest()->paginate($request->perPage);

        return PermissionResource::collection($query);
    }

    // return all permissions
    public function allPermissions()
    {
        return [
            'data' => Permission::all()->sortBy('name')->groupBy('guard_name'),
            'meta' => '',
        ];
    }
}
