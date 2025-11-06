<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductBatchRequest extends FormRequest
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
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],
            'sack' => [
                'required',
                'numeric',
                'min:0',
            ],
            'price_per_sack' => [
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
            'product_id.required' => 'Please select a product.',
            'product_id.integer' => 'The product ID must be a valid number.',
            'product_id.exists' => 'The selected product does not exist.',

            'sack.required' => 'Please enter the number of sacks.',
            'sack.numeric' => 'The number of sacks must be numeric.',
            'sack.min' => 'The number of sacks cannot be negative.',

            'price_per_sack.required' => 'Please enter the price per sack.',
            'price_per_sack.numeric' => 'The price per sack must be numeric.',
            'price_per_sack.regex' => 'The price per sack must be a valid amount (up to 2 decimal places).',
            'price_per_sack.min' => 'The price per sack cannot be negative.',
        ];
    }
}
