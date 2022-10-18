<?php

namespace App\Http\Requests;

use App\Training;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
            'email' => ['required' , Rule::unique('training_registrations')->where(fn ($query) => $query->where('training_registrations.training_id', $this->route('training')->id))],
            'registration_number' => 'required',
            'phone' => ['required' , Rule::unique('training_registrations')->where(fn ($query) => $query->where('training_registrations.training_id', $this->route('training')->id))],
            'is_celec_memeber' => 'required',
            'study_level' => 'required',
            'study_field' => 'required',
            'course_goals' => 'required',
        ];
    }
}
