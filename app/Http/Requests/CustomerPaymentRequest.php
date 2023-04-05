<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CustomerPaymentRequest extends FormRequest
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
            'order_id'            => 'required',
            'amount'              => 'required',
            'type'                => 'required', 
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'order_id.required'          => 'Order field is required',
            'amount.required'            => 'Amount field is required',
            'type.required'              => 'Customer Phone Number field is required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->flash($this->all());
        parent::failedValidation($validator);
    }
}
