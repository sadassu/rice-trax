<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
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
