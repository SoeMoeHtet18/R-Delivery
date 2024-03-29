<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ShopPaymentRequest extends FormRequest
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
            'shop_id'   => 'required|string',
            'amount'    => 'required',
            'type'      => 'required',
            'image'     => 'mimes:jpeg,jpg,webp,png,bmp'
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
            'shop_id.required'      => 'Name field is required',
            'amount.required'       => 'Amount field is required',
            'type.required'         => 'Type is required',
            'image.mimes'           => 'The image must be in JPG, JPEG, WEBP, PNG or BMP format'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->flash($this->all());
        parent::failedValidation($validator);
    }
}
