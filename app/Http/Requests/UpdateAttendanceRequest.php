<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
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
            'employee_id'   => 'sometimes|exists:employees,id',
            'date'          => 'sometimes|date',
            'time_in'       => 'nullable|date_format:H:i',
            'time_out'      => 'nullable|date_format:H:i|after_or_equal:time_in',
            'status'        => 'required|in:present,absent,late,on_leave',
            'remarks'       => 'nullable|string|max:255',
        ];
    }
}
