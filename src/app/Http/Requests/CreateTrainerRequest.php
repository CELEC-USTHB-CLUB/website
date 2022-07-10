<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTrainerRequest extends FormRequest
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
            "fullname"              =>  "required|max:100",
            "email"                 =>  "required|max:120|email:rfc,dns",
            "is_usthb_student"      =>  "required",
            "study_level"           =>  "required",
            "study_field"           =>  "required",
            "projects"              =>  "required",
            "phone"                 =>  "required",
            "course_title"          =>  "required|max:120",
            "course_description"    =>  "required",
            "cv"                    =>  "required|file|max:5120|mimes:pdf"
        ];
    }
}
