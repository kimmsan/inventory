<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\SalaryIncrementResource;
use App\Models\Employee;
use App\Models\SalaryIncrement;
use Exception;
use Illuminate\Http\Request;

class EmpSalIncrementController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:increment-list', ['only' => ['index', 'search']]);
        $this->middleware('can:increment-create', ['only' => ['create']]);
        $this->middleware('can:increment-view', ['only' => ['show']]);
        $this->middleware('can:increment-edit', ['only' => ['update']]);
        $this->middleware('can:increment-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return SalaryIncrementResource::collection(SalaryIncrement::with('employee.department')->latest()->paginate($request->perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $date = date('Y-m-d');
        // get employee
        if (! empty($request->employee)) {
            $employee = Employee::where('slug', $request->employee['slug'])->first();
            $date = $employee->joining_date;
        }
        // validate request
        $this->validate($request, [
            'reason' => 'required|string|max:255',
            'employee' => 'required',
            'incrementAmount' => 'required|numeric',
            'incrementDate' => 'nullable|date|date_format:Y-m-d|after_or_equal:'.$date,
            'note' => 'nullable|string|max:255',
        ]);
        try {
            // create increment
            SalaryIncrement::create([
                'reason' => $request->reason,
                'empolyee_id' => $employee->id,
                'increment_amount' => $request->incrementAmount,
                'increment_date' => $request->incrementDate ? $request->incrementDate : date('Y-m-d'),
                'note' => $request->note,
                'status' => $request->status,
                'created_by' => auth()->user()->id,
            ]);

            return $this->responseWithSuccess('Increment added successfully');
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
            $increment = SalaryIncrement::with('employee.department', 'user')->where('slug', $slug)->first();

            return new SalaryIncrementResource($increment);
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
        // get employee
        if (! empty($request->employee)) {
            $employee = Employee::where('slug', $request->employee['slug'])->first();
            $date = $employee->joining_date;
        }
        // validate request
        $this->validate($request, [
            'reason' => 'required|string|max:255',
            'employee' => 'required',
            'incrementAmount' => 'required|numeric',
            'incrementDate' => 'nullable|date|date_format:Y-m-d|after_or_equal:'.$date,
            'note' => 'nullable|string|max:255',
        ]);
        try {
            $increment = SalaryIncrement::with('employee.department')->where('slug', $slug)->first();
            // update increment
            $increment->update([
                'reason' => $request->reason,
                'empolyee_id' => $employee->id,
                'increment_amount' => $request->incrementAmount,
                'increment_date' => $request->incrementDate,
                'note' => $request->note,
                'status' => $request->status,
            ]);

            return $this->responseWithSuccess('Increment updated successfully');
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
            $increment = SalaryIncrement::where('slug', $slug)->first();
            $increment->delete();

            return $this->responseWithSuccess('Increment deleted successfully');
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
        $query = SalaryIncrement::query();

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('increment_date', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($term) {
            $query->where('reason', 'LIKE', '%'.$term.'%')
                ->orWhere('increment_amount', 'LIKE', '%'.$term.'%')
                ->orWhereHas('employee', function ($newQuery) use ($term) {
                    $newQuery->where('name', 'LIKE', '%'.$term.'%')
                        ->orWhere('name', 'LIKE', '%'.$term.'%')
                        ->orWhere('emp_id', 'LIKE', '%'.$term.'%')
                        ->orWhere('designation', 'LIKE', '%'.$term.'%')
                        ->orWhere('salary', 'LIKE', '%'.$term.'%');
                });
        });

        return SalaryIncrementResource::collection($query->latest()->paginate($request->perPage));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allIncrements()
    {
        $allIncrements = SalaryIncrement::latest()->get();

        return SalaryIncrementResource::collection($allIncrements);
    }
}
