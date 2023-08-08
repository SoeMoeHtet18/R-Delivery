<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class CustomerCollectionRequest extends FormRequest
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
            'shop_id' => 'required',
            'customer_name' => 'required',
            'customer_phone_number' => 'required',
            'paid_amount' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'shop_id.required'                  => 'Shop field is required',
            'customer_name.required'            => 'Customer Name field is required',
            'customer_phone_number.required'    => 'Customer Phone Number field is required',
            'paid_amount.required'              => 'Paid Amount field is required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->flash($this->all());
        parent::failedValidation($validator);
    }
}
