<?php

namespace App\Http\Requests;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CollectionGroupCreateRequest extends FormRequest
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
            'rider_id'            => 'required',
            // 'total_amount'        => 'required',
            'assigned_date'       => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'rider_id.required'               => 'Rider field is required',
            // 'total_amount.required'               => 'Total Amount field is required',
            'assigned_date.required'               => 'Assigned Date field is required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->flash($this->all());
        parent::failedValidation($validator);
    }
}
