<?php

namespace App\Http\Controllers;

use App\Models\ArcTeam;
use Illuminate\Http\Request;

class ArcTeamController extends Controller
{
    public function get(string $code): ArcTeam
    {
        $team = ArcTeam::where('code', $code)->firstOrfail();
        if ($team->users->count() >= 5) {
            return abort(404);
        }

        return $team;
    }
}
