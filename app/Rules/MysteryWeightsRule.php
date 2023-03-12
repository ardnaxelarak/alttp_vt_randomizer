<?php

namespace ALttP\Rules;

use Illuminate\Contracts\Validation\Rule;

class MysteryWeightsRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return is_array($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Value of :attribute must be an array!';
    }
}

