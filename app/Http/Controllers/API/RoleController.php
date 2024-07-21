<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:user-role', ['except' => ['allRoles']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return new RoleCollection(Role::orderBy('id', 'ASC')->paginate($request->perPage));
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
            'name' => 'required|string|max:255|unique:roles',
            'permission' => 'required',
        ]);

        // save role
        $role = Role::create([
            'name' => $request->name,
        ]);
        // give permission to the role
        $permissions = Permission::whereIn('slug', $request->input('permission'))->get()->pluck('id')->toArray();
        $role->permissions()->sync($permissions);

        return $this->responseWithSuccess('Role added successfully', $role);
    }

    /**
     * Display the specified resource.
     * Get role with permission ..
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        try {
            $role = new RoleResource(Role::where('slug', $slug)->firstOrfail());

            return $this->responseWithSuccess('Role get successfully', $role);
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        try {
            // get associate role with slug
            $role = Role::where('slug', $slug)->first();

            // Validate data
            $request->validate([
                'name' => 'required|max:30|unique:roles,name,'.$role->id,
                'permission' => 'required',
            ]);

            // update role
            $role->name = $request['name'];
            $role->save();

            // detach permission
            $permissions = Permission::whereIn('slug', $role->permissions->pluck('slug'))->get()->pluck('id')->toArray();
            $role->permissions()->detach($permissions);
            foreach ($role->users as $key => $user) {
                $user->permissions()->detach($permissions);
            }

            // sync permission
            $permissions = Permission::whereIn('slug', $request->input('permission'))->get()->pluck('id')->toArray();
            $role->permissions()->sync($permissions);
            foreach ($role->users as $key => $user) {
                $user->permissions()->sync($permissions);
            }

            return $this->responseWithSuccess('Role and permission updated successfully.', $role);
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
            $role = Role::where('slug', $slug)->first();
            $role->delete();

            return $this->responseWithSuccess('Role deleted successfully');
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

        $query = Role::where('name', 'LIKE', '%'.$term.'%')->latest()->paginate($request->perPage);

        return RoleResource::collection($query);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allRoles()
    {
        if (auth()->user()->roles[0]->slug != 'developer') {
            $allRoles = Role::with('permissions')->where('slug', '!=', 'developer')->where('slug', '!=', 'super-admin')->latest()->get();
        } else {
            $allRoles = Role::with('permissions')->latest()->get();
        }

        return RoleResource::collection($allRoles);
    }
}
