<?php

namespace App\Http\Controllers;

use App\Actions\ArcMemberRegistrationAction;
use App\Models\ArcTeam;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ArcRegistration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ArcRegistrationRequest;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\ArcRegistrationResource;

class ArcRegistrationController extends Controller
{

    public function get()
    {
        $user = Auth::user();
        $user->load('team');
        return new ArcRegistrationResource($user);
    }

    public function create(ArcRegistrationRequest $request, ArcMemberRegistrationAction $arcMemberRegistrationAction) : array
    {

        if (! $request->has('team_code')) {
            $team_code = Str::uuid();
            $request->validate([
                'team_title' => ['required', 'unique:arc_teams,title']
            ]);
            $team = ArcTeam::create([
                'title' => $request->get('team_title'),
                'code' => $team_code
            ]);
        } else {
            $team_code = $request->get('team_code');
            $team = ArcTeam::where('code', $team_code)->firstOrFail();
        }
        if ($team->members->count() >= 5) {
            return abort(404);
        }
        return $arcMemberRegistrationAction->handle(
            $team_code,
            $request->get('team_title'),
            $request->get('wilaya'),
            $request->get('fullname'),
            $request->get('email'),
            $request->get('phone'),
            $request->get('is_student'),
            $request->get('job'),
            $request->get('tshirt'),
            $request->get('linkedIn_github'),
            $request->file('id_card'),
            $request->get('need_hosting'),
            $request->get('skills'),
            $request->get('projects'),
            $request->get('motivation'),
            $request->get('password'),
            false
        );
    }


    public function auth(Request $request): array
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = ArcRegistration::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'The provided credentials are incorrect',
            ]);
        }

        if (! $user->is_accepted) {
            throw ValidationException::withMessages([
                'Your are not accepted.',
            ]);
        }

        return ['token' => $user->createToken($request->email)->plainTextToken, 'user' => $user];
    }
}
