<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class VendorMoreThanFiftyNaRule implements Rule
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
        $count_all = count($value);
        $NA_counter = 0;
        for ($i = 0; $i < count($value); $i++) {
            if ($value[$i] == 5 || $value[$i] == 10 || $value[$i] == 15) {
                $NA_counter++;
            }
        }
        $filtered_value = array_filter($value);
        $filtered_value_count = count($filtered_value);
        return $filtered_value_count ? !($NA_counter / $filtered_value_count > 0.5) : true;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'More than Fifty percent should not be NA';
    }
}
