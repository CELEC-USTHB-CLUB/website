<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTrainerRequest;
use App\Trainer;

class TrainerController extends Controller
{
    public function create(CreateTrainerRequest $request): Trainer
    {
        $trainer = Trainer::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'is_usthb_student' => ($request->is_usthb_student === 'yes') ? true : false,
            'study_level' => $request->study_level,
            'study_field' => $request->study_field,
            'projects' => $request->projects,
            'phone' => $request->phone,
            'course_title' => $request->course_title,
            'course_description' => $request->course_description,
            'linked_in' => $request->linked_in,
        ]);
        $trainer->cv()->create(['path' => $request->file('cv')->store('cvs', 'public')]);

        return $trainer;
    }
}
