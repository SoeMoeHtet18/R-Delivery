<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThirdPartyVendorRequest extends FormRequest
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
            'name'          => 'required',
            'address'       => 'required',
            'type'       => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => 'Name is required.',
            'address.required'      => 'Address is required.',
            'type.required'         => 'Type is required.',
        ];
    }
}
