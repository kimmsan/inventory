<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MinOne implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $hasQty = false;
        foreach ($value as $key => $item) {
            if ($item['returnQty'] > 0) {
                $hasQty = true;
            }
        }

        return $hasQty;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Minimum one product returned qty is required!';
    }
}
