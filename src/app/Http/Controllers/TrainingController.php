<?php

namespace App\Http\Controllers;

use App\Actions\TrainingRegistrationAction;
use App\Http\Requests\TrainingRegistrationRequest;
use App\Http\Resources\TrainingResource;
use App\Training;
use App\TrainingRegistration;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function all(Request $request)
    {
        if ($request->has('filter') and count($request->filter) > 0) {
            return TrainingResource::collection(Training::with('image')->whereJsonContains('tags', $request->filter)->orderByDesc('id')->paginate(12));
        }

        return TrainingResource::collection(Training::with('image')->orderByDesc('id')->paginate(12));
    }

    public function get(Training $training)
    {
        return new TrainingResource($training);
    }

    public function register(
        int $trainingId,
        TrainingRegistrationRequest $request,
        TrainingRegistrationAction $trainingRegistrationAction
    ): TrainingRegistration {
        $training = Training::findOrFail($trainingId);
        if ($training->isClosed()) {
            abort(403, 'Registration expired');
        }

        return $trainingRegistrationAction->handle(
            $training,
            $request->fullname,
            $request->email,
            $request->registration_number,
            $request->phone,
            $request->is_celec_memeber,
            $request->study_level,
            $request->study_field,
            $request->course_goals,
        );
    }
}
