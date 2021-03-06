<?php

namespace App\Http\Controllers;

use App\Http\Resources\TeamResource;
use App\Team;

class TeamController extends Controller
{
    public function all()
    {
        return TeamResource::collection(Team::with('image')->get());
    }
}
