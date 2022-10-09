<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMemberRequest;
use App\Member;

class MemberController extends Controller
{
    public function create(CreateMemberRequest $request): Member
    {
        $skills = [];
        if ($request->skills !== null) {
            $skills = explode(',', $request->skills);
        }

        if ($request->hasFile('cv')) {
            $request->validate([
                'cv' => 'mimetypes:application/msword,application/pdf|max:5042',
            ]);
        }

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'mimetypes:image/jpeg,image/jpg,image/png|max:1042',
            ]);
        }
        
        $member = Member::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'birthdate' => $request->birthdate,
            'registration_number' => $request->registration_number,
            'is_usthb_student' => ($request->is_usthb_student === 'yes') ? true : false,
            'study_level' => $request->study_level,
            'study_field' => $request->study_field,
            'projects' => $request->projects,
            'intersted_in' => $request->intersted_in,
            'skills' => json_encode($skills, true),
            'other_clubs_experience' => $request->other_clubs_experience,
            'linked_in' => $request->linked_in,
            'motivation' => $request->motivation,
        ]);

        if ($request->hasFile('cv')) {
            $member->cv()->create(['path' => $request->file('cv')->store('cvs', 'public')]);
        }

        if ($request->hasFile('image')) {
            $member->image()->create(['path' => $request->file('image')->store('member_images', 'public')]);
        }

        return  $member;
    }
}
