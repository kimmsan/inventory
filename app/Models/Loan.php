<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use Sluggable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reason', 'slug', 'reference_no', 'authority_id', 'transaction_id', 'loan_type', 'interest', 'payable', 'payment_type', 'duration', 'date', 'created_by', 'note', 'status', 'is_paid', 'image_path',
    ];

    protected $appends = ['paid'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'reference_no',
            ],
        ];
    }

    /**
     * Get the total paid.
     *
     * @return string
     */
    public function getPaidAttribute()
    {
        return $this->totalPaid();
    }

    /**
     * Calculate total interest amount
     */
    public function totalInterest()
    {
        if ($this->loan_type == 1) {
            $numOfYears = 0;
            if ($this->payment_type == 0) {
                $numOfYears = $this->duration / 365;
            } elseif ($this->payment_type == 1) {
                $numOfYears = $this->duration / 12;
            } else {
                $numOfYears = $this->duration;
            }
            $totalInterestAmount = ($this->montlyPayment() * ($numOfYears * 12) - $this->loanTransaction->amount);

            return round($totalInterestAmount, 2);
        } else {
            $interest = 0;
            if (isset($this->loanPayments)) {
                $interest = $this->loanPayments->sum('interest');
            }

            return round($interest, 2);
        }
    }

    /**
     * Calculate total interest paid
     */
    public function interestPaid()
    {
        $interestPaid = 0;
        if (isset($this->loanPayments)) {
            $interestPaid = $this->loanPayments->sum('interest');
        }

        return round($interestPaid, 2);
    }

    /**
     * Calculate monthly payment
     */
    public function montlyPayment()
    {
        if ($this->loan_type == 1) {
            $monthlyPayment = 0;
            $interestRate = $this->interest / 100;
            $numOfYears = 0;
            if ($this->interest && $this->duration) {
                if ($this->payment_type == 0) {
                    $numOfYears = $this->duration / 365;
                    $paymetnType = 'Per Day';
                } elseif ($this->payment_type == 1) {
                    $numOfYears = $this->duration / 12;
                    $paymetnType = 'Per Month';
                } else {
                    $numOfYears = $this->duration;
                    $paymetnType = 'Per Year';
                }
                $monthlyPayment = (($interestRate / 12) * $this->loanTransaction->amount) / (1 - (pow(1 + ($interestRate / 12), ($numOfYears * -12))));
            }

            return round($monthlyPayment, 2);
        }

        return 'No';
    }

    /**
     * Calculate total paid amount for a loan
     */
    public function totalPaid()
    {
        $totalPaid = 0;
        if (isset($this->loanPayments)) {
            $totalPaid = $this->loanPayments->where('status', 1)->sum('amount');
        }

        return round($totalPaid, 2);
    }

    /**
     * Calculate total due amount
     */
    public function totalDue()
    {
        return round($this->payable - $this->totalPaid(), 2);
    }

    /**
     * Return formatted installment
     */
    public function installmentStr()
    {
        if ($this->loan_type == 1) {
            if ($this->payment_type == 0) {
                $paymetnType = ' Per Day';
            } elseif ($this->payment_type == 1) {
                $paymetnType = ' Per Month';
            } else {
                $paymetnType = ' Per Year';
            }

            if (config('config.currencyPosition') == 'left') {
                return config('config.currencySymbol').round($this->montlyPayment(), 2).$paymetnType.' X '.$this->duration;
            } else {
                return round($this->montlyPayment(), 2).config('config.currencySymbol').$paymetnType.' X '.$this->duration;
            }
        } else {
            return 'No';
        }
    }

    /**
     * Return formatted duration
     */
    public function formattedDuration()
    {
        $duration = $this->duration;
        $durationStr = '';
        if ($this->loan_type == 1) {
            if ($this->payment_type == 0) {
                $durationStr = $this->duration == 1 ? ' Day' : ' Days';
            } elseif ($this->payment_type == 1) {
                $durationStr = $this->duration == 1 ? ' Month' : ' Months';
            } else {
                $durationStr = $this->duration == 1 ? ' Year' : ' Years';
            }
        }

        return $duration.$durationStr;
    }

    /**
     * Get the loan payments.
     */
    public function loanPayments()
    {
        return $this->hasMany(LoanPayment::class, 'loan_id');
    }

    /**
     * Get the Loan Authority that owns the loan.
     */
    public function loanAuthority()
    {
        return $this->belongsTo(LoanAuthority::class, 'authority_id');
    }

    /**
     * Get the  tansaction for this loan.
     */
    public function loanTransaction()
    {
        return $this->belongsTo(AccountTransaction::class, 'transaction_id');
    }

    /**
     * Get the user who had created this loan.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
