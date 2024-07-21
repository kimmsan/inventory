<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MinItem implements Rule
{
    private $type;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($module)
    {
        $this->type = $module;
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
        return count($value) > 0 ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Plese select at least one '.$this->type;
    }
}
