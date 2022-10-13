<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMemberRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'fullname' => 'required|max:100',
            'email' => 'required|unique:members|max:120|email:rfc,dns',
            'birthdate' => ['required'],
            'registration_number' => 'required|unique:members|numeric',
            'is_usthb_student' => ['required'],
            'study_level' => ['required'],
            'study_field' => ['required'],
            'motivation' => ['required'],
            'image' => ['required', 'mimetypes:image/jpeg,image/jpg,image/png|max:5042']
        ];
    }
}
