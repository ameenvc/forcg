<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ContentTypeOfFileRule implements Rule
{
    protected $type;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($type)
    {
        $this->type = $type;
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
        $validation = "";

        switch ($this->type) {
            case 'pdf':
                $validation = 'required|mimes:pdf|max:10000';
                break;
            case 'audio':
                $validation = 'required|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav';
                break;
            case 'video':
                $validation = 'required|mimes:mp4,mov,ogg,qt | max:20000';
                break;
        }

        return !Validator::make(
            [
                "{$attribute}" => $validation
            ],
            [

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
        return 'Type of file mismatch the file selected';
    }
}
