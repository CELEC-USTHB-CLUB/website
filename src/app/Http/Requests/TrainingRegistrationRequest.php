<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrainingRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'fullname' => 'required',
            // 'email' => 'required|unique:training_registrations,email',
            'registration_number' => 'required',
            // 'phone' => 'required|unique:training_registrations,phone',
            'is_celec_memeber' => 'required',
            'study_level' => 'required',
            'study_field' => 'required',
            'course_goals' => 'required',
        ];
    }
}
