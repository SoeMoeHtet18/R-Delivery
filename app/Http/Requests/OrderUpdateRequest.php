<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
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
            'customer_name'         => 'required',
            'customer_phone_number' => 'required',
            'city_id'               => 'required',
            'township_id'           => 'required',
            'shop_id'               => 'required',
            'total_amount'          => 'required',
            'delivery_fees'         => 'required',
            'delivery_type_id'      => 'required',
            'collection_method'     => 'required',
            'proof_of_payment'      => 'mimes:jpg,jpeg,webp,png,bmp'
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
            'customer_name.required'            => 'Customer Name field is required',
            'customer_phone_number.required'    => 'Customer Phone Number field is required',
            'city_id.required'                  => 'City field is required',
            'township_id.required'              => 'Township field is required',
            'shop_id.required'                  => 'Shop field is required',
            'quantity.required'                 => 'Quantity field is required',
            'total_amount.required'             => 'Total Amount field is required',
            'delivery_fees.required'            => 'Delivery Fees is required',
            'item_type.required'                => 'Item Type field is required',
            'delivery_type_id.required'         => 'Type field is required',
            'collection_method.required'        => 'Collection Method field is required',
            'proof_of_payment.mimes'            => 'The proof of payment must be in JPG, JPEG, WEBP, PNG or BMP format'
        ];
    }
}
