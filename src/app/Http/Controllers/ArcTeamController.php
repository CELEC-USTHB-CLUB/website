<?php

namespace App\Http\Controllers;

use App\Models\ArcTeam;
use Illuminate\Http\Request;

class ArcTeamController extends Controller
{
    public function get(string $code): ArcTeam
    {
        return ArcTeam::where('code', $code)->firstOrfail();
    }
}
