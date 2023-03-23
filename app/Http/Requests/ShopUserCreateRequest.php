<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopUserCreateRequest extends FormRequest
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
            'name'              => 'required|string',
            'phone_number'      => 'required|string|unique:shop_users',
            'email'             => 'unique:shop_users',
            'password'          => 'required|min:8',
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
            'name.required'              => 'Name field is required',
            'phone_number.required'      => 'Phone Number is required',
            'phone_number.unique'        => 'Phone Number already exists',
            'email'                      => 'Email already exists',
            'password.required'          => 'Password is required',
            'password.min'               => 'Password should be a minimum of 8 characters.',
        ];
    }
}