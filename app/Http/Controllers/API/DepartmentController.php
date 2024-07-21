<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Exception;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:department-list', ['only' => ['index', 'search']]);
        $this->middleware('can:department-create', ['only' => ['create']]);
        $this->middleware('can:department-edit', ['only' => ['update']]);
        $this->middleware('can:department-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return DepartmentResource::collection(Department::latest()->paginate($request->perPage));
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
            'name' => 'required|string|max:50|unique:departments',
            'note' => 'nullable|string|max:255',
        ]);
        // save department
        Department::create([
            'name' => $request->name,
            'note' => $request->note,
            'status' => $request->status,
        ]);

        return $this->responseWithSuccess('Department added successfully');
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
            $department = Department::where('slug', $slug)->first();

            return $department;
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
        $department = Department::where('slug', $slug)->first();
        // validate request
        $this->validate($request, [
            'name' => 'required|string|max:50|unique:departments,name,'.$department->id,
            'note' => 'nullable|string|max:255',
        ]);

        try {
            // update department
            $department->update([
                'name' => $request->name,
                'note' => $request->note,
                'status' => $request->status,
            ]);

            return $this->responseWithSuccess('Department updated successfully');
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
            $department = Department::where('slug', $slug)->first();
            $department->delete();

            return $this->responseWithSuccess('Department deleted successfully');
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

        $query = Department::where('name', 'LIKE', '%'.$term.'%')->orWhere('note', 'LIKE', '%'.$term.'%')->latest()->paginate($request->perPage);

        return DepartmentResource::collection($query);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allDepartments()
    {
        $assetTypes = Department::where('status', 1)->latest()->get();

        return DepartmentResource::collection($assetTypes);
    }
}
