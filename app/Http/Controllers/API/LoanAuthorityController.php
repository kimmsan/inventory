<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorityLoansResource;
use App\Http\Resources\LoanAuthorityResource;
use App\Models\LoanAuthority;
use Exception;
use Illuminate\Http\Request;

class LoanAuthorityController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:loan-authority-list', ['only' => ['index', 'search']]);
        $this->middleware('can:loan-authority-create', ['only' => ['create']]);
        $this->middleware('can:loan-authority-view', ['only' => ['show']]);
        $this->middleware('can:loan-authority-edit', ['only' => ['update']]);
        $this->middleware('can:loan-authority-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return LoanAuthorityResource::collection(LoanAuthority::latest()->paginate($request->perPage));
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
            'name' => 'required|string|max:150|unique:loan_authorities',
            'contactNumber' => 'required|string|max:15',
            'email' => 'nullable|email|max:100',
            'ccLoanLimit' => 'required|numeric|min:100000|max:999999999999',
            'address' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:255',
        ]);
        // save loan authority
        LoanAuthority::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->contactNumber,
            'cc_limit' => $request->ccLoanLimit,
            'address' => clean($request->address),
            'note' => clean($request->note),
            'status' => $request->status,
        ]);

        return $this->responseWithSuccess('Loan authority added successfully');
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
            $loanAuthority = LoanAuthority::with('allLoans.loanAuthority', 'allLoans.loanPayments', 'allLoans.user', 'allLoans.loanTransaction.cashbookAccount')->where('slug', $slug)->first();

            return new AuthorityLoansResource(($loanAuthority));
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
        $loanAuthority = LoanAuthority::where('slug', $slug)->first();
        // validate request
        $this->validate($request, [
            'name' => 'required|string|max:150|unique:loan_authorities,name,'.$loanAuthority->id,
            'contactNumber' => 'required|string|max:15',
            'email' => 'nullable|email|max:100',
            'ccLoanLimit' => 'required|numeric|min:100000|max:999999999999',
            'address' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:255',
        ]);
        try {
            // update loan authority
            $loanAuthority->update([
                'name' => $request->name,
                'email' => $request->email,
                'contact_number' => $request->contactNumber,
                'cc_limit' => $request->ccLoanLimit,
                'address' => clean($request->address),
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
            $loanAuthority = LoanAuthority::where('slug', $slug)->first();
            if (($loanAuthority->allLoans) && count($loanAuthority->allLoans) > 0) {
                return $this->responseWithError('Loan authority can\'t be remove');
            }
            $loanAuthority->delete();

            return $this->responseWithSuccess('Loan authority deleted successfully');
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

        $query = LoanAuthority::where('name', 'LIKE', '%'.$term.'%')
            ->orWhere('slug', 'LIKE', '%'.$term.'%')
            ->orWhere('cc_limit', 'LIKE', '%'.$term.'%')
            ->orWhere('email', 'LIKE', '%'.$term.'%')
            ->orWhere('contact_number', 'LIKE', '%'.$term.'%')
            ->latest()->paginate($request->perPage);

        return LoanAuthorityResource::collection($query);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allAuthorities()
    {
        $assetTypes = LoanAuthority::where('status', 1)->latest()->get();

        return LoanAuthorityResource::collection($assetTypes);
    }
}
