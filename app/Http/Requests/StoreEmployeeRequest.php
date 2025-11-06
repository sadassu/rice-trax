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

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the employee’s name.',
            'name.min' => 'The name must be at least 2 characters long.',
            'contact.required' => 'Please enter a contact number.',
            'contact.min' => 'The contact is not a valid number',
            'contact.regex' => 'The contact number must be a valid Philippine mobile number.',
            'rate.required' => 'Please enter a rate.',
            'rate.numeric' => 'The rate must be a number.',
            'role.required' => 'Please select a role.',
            'role.in' => 'The selected role is invalid.',
        ];
    }
}
