<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PayrollResource;
use App\Models\AccountTransaction;
use App\Models\Payroll;
use Exception;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PayrollController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:payroll-list', ['only' => ['index', 'search']]);
        $this->middleware('can:payroll-create', ['only' => ['create']]);
        $this->middleware('can:payroll-view', ['only' => ['show']]);
        $this->middleware('can:payroll-edit', ['only' => ['update']]);
        $this->middleware('can:payroll-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return PayrollResource::collection(Payroll::with('employee.department', 'payrollTransaction.cashbookAccount', 'user')->latest()->paginate($request->perPage));
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
            'employee' => 'required',
            'account' => 'required',
            'salaryMonth' => 'nullable|string|max:255',
            'chequeNo' => 'nullable|string|max:255',
            'deductionAmount' => 'nullable|numeric|min:0',
            'deductionReason' => 'nullable|string|max:255',
            'mobileBill' => 'nullable|numeric|min:0',
            'foodBill' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'commission' => 'nullable|numeric|min:0',
            'festivalBonus' => 'nullable|numeric|min:0',
            'travelAllowance' => 'nullable|numeric|min:0',
            'others' => 'nullable|numeric|min:0',
            'advance' => 'nullable|numeric|min:0',
            'totalSalary' => 'required|numeric|min:0|max:'.$request->availableBalance,
            'salaryDate' => 'nullable|date|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ]);
        try {
            // upload thumbnail and set the name
            $imageName = '';
            if ($request->image) {
                $imageName = time().'.'.explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
                Image::make($request->image)->save(public_path('images/payroll/').$imageName);
            }

            $userID = auth()->user()->id;
            $reason = '['.config('config.employeePrefix').'-'.$request->employee['empID'].'] '.$request->salaryMonth.' Payroll sent from ['.$request->account['accountNumber'].']';

            // store transaction
            $transaction = AccountTransaction::create([
                'account_id' => $request->account['id'],
                'amount' => $request->totalSalary,
                'reason' => $reason,
                'type' => 0,
                'cheque_no' => $request->chequeNo,
                'transaction_date' => $request->salaryDate,
                'created_by' => auth()->user()->id,
                'status' => $request->status,
            ]);

            // store payroll
            Payroll::create([
                'slug' => uniqid(),
                'employee_id' => $request->employee['id'],
                'transaction_id' => $transaction->id,
                'salary_month' => $request->salaryMonth,
                'deduction_reason' => $request->deductionReason,
                'deduction_amount' => $request->deductionAmount,
                'mobile_bill' => $request->mobileBill,
                'food_bill' => $request->foodBill,
                'bonus' => $request->bonus,
                'commission' => $request->commission,
                'advance' => $request->advance,
                'festival_bonus' => $request->festivalBonus,
                'travel_allowance' => $request->travelAllowance,
                'others' => $request->others,
                'salary_date' => $request->salaryDate,
                'created_by' => $userID,
                'note' => $request->note,
                'status' => $request->status,
                'image_path' => $imageName,
            ]);

            return $this->responseWithSuccess('Payroll added successfully');
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
            $payroll = Payroll::with('employee', 'payrollTransaction.cashbookAccount')->where('slug', $slug)->first();

            return new PayrollResource($payroll);
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
        // get payroll
        $payroll = Payroll::where('slug', $slug)->first();

        // validate request
        $this->validate($request, [
            'employee' => 'required',
            'account' => 'required',
            'salaryMonth' => 'nullable|string|max:255',
            'chequeNo' => 'nullable|string|max:255',
            'deductionAmount' => 'nullable|numeric|min:0',
            'deductionReason' => 'nullable|string|max:255',
            'mobileBill' => 'nullable|numeric|min:0',
            'foodBill' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'commission' => 'nullable|numeric|min:0',
            'festivalBonus' => 'nullable|numeric|min:0',
            'travelAllowance' => 'nullable|numeric|min:0',
            'others' => 'nullable|numeric|min:0',
            'advance' => 'nullable|numeric|min:0',
            'totalSalary' => 'required|numeric|min:0|max:'.$request->availableBalance,
            'salaryDate' => 'nullable|date|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ]);
        try {
            // upload thumbnail and set the name
            $imageName = $payroll->image_path;
            if ($request->image) {
                if ($imageName) {
                    @unlink(public_path('images/payroll/'.$imageName));
                }
                $imageName = time().'.'.explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
                Image::make($request->image)->save(public_path('images/payroll/').$imageName);
            }

            // update transaction
            $payroll->payrollTransaction->update([
                'account_id' => $request->account['id'],
                'amount' => $request->totalSalary,
                'transaction_date' => $request->salaryDate,
                'status' => $request->status,
                'cheque_no' => $request->chequeNo,
            ]);

            // update payroll
            $payroll->update([
                'employee_id' => $request->employee['id'],
                'salary_month' => $request->salaryMonth,
                'deduction_reason' => $request->deductionReason,
                'deduction_amount' => $request->deductionAmount,
                'mobile_bill' => $request->mobileBill,
                'food_bill' => $request->foodBill,
                'bonus' => $request->bonus,
                'commission' => $request->commission,
                'advance' => $request->advance,
                'festival_bonus' => $request->festivalBonus,
                'travel_allowance' => $request->travelAllowance,
                'others' => $request->others,
                'salary_date' => $request->salaryDate,
                'note' => $request->note,
                'status' => $request->status,
                'image_path' => $imageName,
            ]);

            return $this->responseWithSuccess('Payroll updated successfully');
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
            $payroll = Payroll::with('payrollTransaction')->where('slug', $slug)->first();
            //delete image from storage
            if ($payroll->image_path) {
                @unlink(public_path('images/payroll/'.$payroll->image_path));
            }
            $payroll->payrollTransaction->delete();
            $payroll->delete();

            return $this->responseWithSuccess('Payroll deleted successfully');
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
        $query = Payroll::with('employee', 'payrollTransaction.cashbookAccount');

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('salary_date', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($term) {
            $query->where('salary_month', 'LIKE', '%'.$term.'%')
                ->orWhere('deduction_reason', 'LIKE', '%'.$term.'%')
                ->orWhereHas('employee', function ($newQuery) use ($term) {
                    $newQuery->where('name', 'LIKE', '%'.$term.'%')
                        ->orWhere('emp_id', 'LIKE', '%'.$term.'%')
                        ->orWhere('designation', 'LIKE', '%'.$term.'%')
                        ->orWhere('salary', 'LIKE', '%'.$term.'%');
                })
                ->orWhereHas('payrollTransaction', function ($newQuery) use ($term) {
                    $newQuery->where('cheque_no', 'LIKE', '%'.$term.'%')->orWhere('amount', '=', $term)
                        ->orWhereHas('cashbookAccount', function ($newQuery) use ($term) {
                            $newQuery->where('account_number', 'LIKE', '%'.$term.'%')
                                ->orWhere('bank_name', 'LIKE', '%'.$term.'%');
                        });
                });
        });

        return PayrollResource::collection($query->latest()->paginate($request->perPage));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allPayroll()
    {
        $allPayroll = Payroll::latest()->get();

        return PayrollResource::collection($allPayroll);
    }
}
