<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArcRegistrationRequest extends FormRequest
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
            'wilaya'    => ['required'],
            'fullname'  => ['required'],
            'email'     => ['required', 'unique:arc_registrations', 'max:120','email:rfc,dns'],
            'phone'     => ['required', 'unique:arc_registrations','max:120'],
            'is_student' => ['required'],
            'need_hosting' => ['required'],
            'skills' => ['required'],
            'projects' => ['required'],
            'motivation' => ['required'],
            'password' => ['required'],
            'tshirt' => ['required'],
            'id_card' => ['required', 'mimetypes:image/jpeg,image/jpg,image/png|max:15042']
        ];
    }
}
