<?php

namespace App\Http\Requests\Document;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use stdClass;
class UpdateDraftContentRichTextRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Check if using new change tracking format
        $usingChangeTracking = $this->has('changed_records');
        
        $rules = [
            'content_id' => 'required|integer',
            'document_id' => 'required|integer',
            'title' => 'required|string|max:255',
        ];
        
        // Add conditional validation based on format
        if ($usingChangeTracking) {
            $rules['changed_records'] = 'nullable|string';
            $rules['has_changes'] = 'nullable|string';
        } else {
            // Legacy format
            $rules['rich_text'] = 'nullable|array';
        }
        
        return $rules;
    }
    /**
     * Configure the validator instance.
     *
     * @param  Validator  $validator
     * @return JsonResponse
     */
    public function withValidator(Validator $validator)
    {
        $json = new stdClass();
        if ($validator->fails()) {
            $json->success = false;
            $json->errors = $validator->errors();
        } else {
            $json->success = true;
        }
        return response()->json($json);
    }
}
