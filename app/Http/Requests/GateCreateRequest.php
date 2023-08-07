<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GateCreateRequest extends FormRequest
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
            'township_id'   => 'required|array|min:1',
            'city_id'       => 'required',
            'name'          => 'required',
            'address'       => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'township_id.required'  => 'Township is required.',
            'city_id.required'      => 'City is required.',
            'name.required'         => 'Name is required.',
            'address.required'      => 'Address is required.',
            'township_id.array'     => 'Please select at least one township.',
        ];
    }
}
