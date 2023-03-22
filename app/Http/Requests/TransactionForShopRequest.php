<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionForShopRequest extends FormRequest
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
            'paid_by'   => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'shop_id.required'      => 'Name field is required',
            'amount.required'       => 'Amount field is required',
            'type.required'         => 'Type is required',
            'paid_by.required'      => 'This Field is required',
        ];
    }
}
