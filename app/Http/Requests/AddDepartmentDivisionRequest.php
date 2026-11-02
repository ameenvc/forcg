<?php

namespace App\Http\Requests;

//use App\ModenaDisable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use stdClass;
//use Closure;

class AddDepartmentDivisionRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    // public function handle($request, Closure $next)
    // {
    //     $this->middleware('preventRedirects');
    //     return $next($request);
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'owner_id' => ['required', 'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    return $query->where('is_email_verified', true)->where('approved', true);
                }),
            ],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
        ];
    }
    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return \Illuminate\Http\JsonResponse
     */
    public function withValidator($validator)
    {
        //$models = ModenaDisable::First();

        if ($validator->fails()) {
            $json = new stdClass();
            $json->success = false;
            $json->errors = $validator->errors();
        } else {
            $json = new stdClass();
            $json->success = true;
        }

        return response()->json($json);
    }
}
