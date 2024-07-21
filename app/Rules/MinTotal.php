<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MinTotal implements Rule
{
    private $minAmount;

    private $netTotal;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($amount, $newTotal)
    {
        $this->minAmount = $amount;
        $this->netTotal = $newTotal;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->netTotal < $this->minAmount ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Net total must be greater than total paid amount '.$this->minAmount;
    }
}
