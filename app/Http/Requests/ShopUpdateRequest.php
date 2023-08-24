<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopUpdateRequest extends FormRequest
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
        $id = $this->input('id');
        return [
            'name' => 'required|string',
            'township_id' => 'required',
            'address' => 'required|string',
            'phone_number' => 'required|string|unique:shops,phone_number,' . $id
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
            'name.required' => 'Name field is required',
            'township_id.required' => 'Township field is required',
            'address.required' => 'Address field is required',
            'phone_number.required' => 'Phone Number is required',
            'phone_number.unique' => 'Phone Number already exists'
        ];
    }
}
