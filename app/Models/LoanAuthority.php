<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LoanAuthority extends Model
{
    use Sluggable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'slug', 'contact_number', 'address', 'cc_limit', 'note', 'status',
    ];

    protected $appends = ['due'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    /**
     * Get the total paid.
     *
     * @return string
     */
    public function getDueAttribute()
    {
        return round($this->totalPayable() - $this->totalLoanPaid(), 2);
    }

    /**
     * Calculate total loan amount
     */
    public function totalLoan()
    {
        $totalLoan = DB::table('account_transactions')
            ->join('loans', 'account_transactions.id', '=', 'loans.transaction_id')
            ->join('loan_authorities', 'loan_authorities.id', '=', 'loans.authority_id')
            ->where('loans.authority_id', $this->id)
            ->where('account_transactions.status', 1)
            ->where('loan_authorities.status', 1)
            ->sum('account_transactions.amount');

        return round($totalLoan, 2);
    }

    /**
     * Calculate total cc loan taken
     */
    public function ccLoanTaken()
    {
        $loans = $this->allLoans()->where('loan_type', 0);
        $loanTaken = $loans->sum('payable');

        return round($loanTaken, 2);
    }

    /**
     * Calculate available cc loan limit
     */
    public function availableCCLoan()
    {
        $loanTaken = $this->allLoans->where('loan_type', 0)->sum('payable');
        $ccLoanPayments = $this->load(['authorityLoanPayments' => function ($q) use (&$allLoans) {
            $allLoans = $q->where('loan_type', 0);
        }]);
        $loanPaid = $ccLoanPayments->authorityLoanPayments->where('loans.loan_type', 0)->where('status', 1)->sum('amount');
        $availableAmount = $this->cc_limit - $loanTaken + $loanPaid;

        return round($availableAmount, 2);
    }

    /**
     * Calculate interest paid
     */
    public function interestPaid()
    {
        $relation = $this->load('authorityLoanPayments');
        $interestPaid = $relation->authorityLoanPayments->where('status', 1)->sum('interest');

        return round($interestPaid, 0);
    }

    /**
     * Calculate cc loan interest
     */
    public function ccLoanInterestPaid()
    {
        $payments = $this->load(['authorityLoanPayments' => function ($q) use (&$allLoans) {
            $allLoans = $q->where('loan_type', 0);
        }]);
        $interest = $payments->authorityLoanPayments->where('loans.loan_type', 0)->where('status', 1)->sum('interest');

        return round($interest, 2);
    }

    /**
     * Calculate term loan interest
     */
    public function termLoanInterest()
    {
        return round($this->totalPayable() - $this->totalLoan(), 2);
    }

    /**
     * Calculate total payable
     */
    public function totalPayable()
    {
        return $this->allLoans->where('status', 1)->sum('payable');
    }

    /**
     * Calculate total paid
     */
    public function totalLoanPaid()
    {
        return $this->allLoans->where('status', 1)->sum('paid');
    }

    /**
     * Get the loans.
     */
    public function allLoans()
    {
        return $this->hasMany(Loan::class, 'authority_id');
    }

    /**
     * Get the loan payements for the authority.
     */
    public function authorityLoanPayments()
    {
        return $this->hasManyThrough(LoanPayment::class, Loan::class, 'authority_id', 'loan_id');
    }
}
