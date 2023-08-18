<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThirdPartyVendorAssignToRiderRequest extends FormRequest
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
            'third_party_vendor_id' => 'required'
        ];
    }
    public function messages(): array
    {
        return [
            'third_party_vendor_id.required' => 'Third Party Vendor field is required'
        ];
    }
}
