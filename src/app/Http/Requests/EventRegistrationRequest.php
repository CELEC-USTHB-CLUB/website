<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventRegistrationRequest extends FormRequest
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
            'firstname' => ['required'],
            'lastname' => ['required'],
            'phone_number' => ['required', Rule::unique('event_registrations')->where(fn ($query) => $query->where('event_registrations.event_id', $this->route('event')->id))],
            'email' => ['required', Rule::unique('event_registrations')->where(fn ($query) => $query->where('event_registrations.event_id', $this->route('event')->id))],
            'id_card_number' => ['required', Rule::unique('event_registrations')->where(fn ($query) => $query->where('event_registrations.event_id', $this->route('event')->id))],
            'are_you_student' => ['required'],
            'motivation' => ['required'],
            'is_usthb' => ['required'],
            // study_field
            // fonction
        ];
    }
}
