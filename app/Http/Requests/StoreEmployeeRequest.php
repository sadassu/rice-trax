<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                'min:2',
            ],
            'contact' => [
                'required',
                'string',
                'min:7',
                'regex:/^((\+63)|0)9\d{2}[-. ]?\d{3}[-. ]?\d{4}$/'
            ],
            'contact.regex' => 'The contact number must be a valid Philippine mobile number.',
            'rate' => [
                'required',
                'numeric',
                'min:0',
            ],
            'role' => [
                'required',
                'in:Cashier,Helper,Manager',
            ],
        ];
    }
}
