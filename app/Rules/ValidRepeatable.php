<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidRepeatable implements Rule
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
        return in_array($value, ['NONE', 'DAILY', 'WEEKLY', 'MONTHLY', 'INFINITE']);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This is not a valid repeatable type.';
    }
}
