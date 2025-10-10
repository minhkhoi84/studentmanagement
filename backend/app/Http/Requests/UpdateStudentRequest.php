<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
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
        $studentId = $this->route('student')->id;

        return [
            // Basic Information
            'name' => 'required|string|max:255',
            'email' => [
                'nullable',
                'email:rfc,dns',
                'max:255',
                Rule::unique('students', 'email')->ignore($studentId)
            ],
            'student_code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('students', 'student_code')->ignore($studentId)
            ],
            'class' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'mobile' => 'required|string|max:20|regex:/^[0-9+\-\s()]{8,20}$/',
            'phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]{8,20}$/',
            'address' => 'required|string|max:255',
            'emergency_contact' => 'nullable|string|max:1000',

            // Academic Information
            'major' => 'nullable|string|max:150',
            'semester' => 'nullable|integer|min:1|max:20',
            'gpa' => 'nullable|numeric|min:0|max:4',
            'status' => 'nullable|in:active,inactive,graduated,suspended',

            // Personal Information
            'nationality' => 'nullable|string|max:120',
            'religion' => 'nullable|string|max:120',
            'medical_conditions' => 'nullable|string|max:2000',

            // Family Information
            'father_name' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]{8,20}$/',
            'father_occupation' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]{8,20}$/',
            'mother_occupation' => 'nullable|string|max:255',

            // Academic History
            'enrollment_date' => 'nullable|date|before_or_equal:today',
            'previous_school' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:2000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên sinh viên là bắt buộc.',
            'mobile.required' => 'Số điện thoại di động là bắt buộc.',
            'mobile.regex' => 'Số điện thoại không đúng định dạng.',
            'address.required' => 'Địa chỉ là bắt buộc.',
            'email.unique' => 'Email này đã được sử dụng.',
            'student_code.unique' => 'Mã sinh viên này đã tồn tại.',
            'date_of_birth.before' => 'Ngày sinh phải trước ngày hiện tại.',
            'gpa.max' => 'GPA không được vượt quá 4.0.',
            'semester.max' => 'Học kỳ không được vượt quá 20.',
        ];
    }
}










