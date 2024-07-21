<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PurchaseTotalPaid implements Rule
{
    public $avalible;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($availableBalance)
    {
        $this->avalible = $availableBalance;
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
        if ($this->avalible != 0 && ($this->avalible < $value)) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Total paid amout can not more than avaliable balance ';
    }
}
