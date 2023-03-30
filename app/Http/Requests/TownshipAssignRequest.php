<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TownshipAssignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'township_id' => 'required'
        ];
    }
    public function messages(): array
    {
        return [
            'township_id.required' => 'Township field is required' 
        ];      
    }
}
