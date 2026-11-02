<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Rule;

class CommaSeparatedEmailsRule implements Rule
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
        return !Validator::make(
            [
                "{$attribute}" => explode(',', $value),
            ],
            [
                "{$attribute}.*" => 'nullable|email',
            ]
        )->fails();

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must have valid email addresses.';
    }
}
