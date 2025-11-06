<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'price_per_kilo' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0',
            ],

        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the product name.',
            'name.string' => 'The product name must be a valid text.',
            'name.max' => 'The product name may not exceed 255 characters.',

            'price_per_kilo.required' => 'Please enter the price per kilo.',
            'price_per_kilo.numeric' => 'The price per kilo must be numeric.',
            'price_per_kilo.regex' => 'The price per kilo must be a valid amount (up to 2 decimal places).',
            'price_per_kilo.min' => 'The price per kilo cannot be negative.',
        ];
    }
}
