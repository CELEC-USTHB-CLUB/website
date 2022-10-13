<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Check;
use App\Models\Signature;
use Illuminate\Http\Request;

class CheckController extends Controller
{
    public function checkin(Request $request): Check
    {
        $request->validate([
            'signature' => 'required'
        ]);
        $signature = Signature::where('checkin_code', $request->signature)->firstOrFail();
        if (! $signature->member()->exists()) {
            return abort(404);
        }
        return $signature->member->checks()->create([
            'invitation_id' => $signature->invitation_id,
            'training_id' => $signature->invitation->training_id,
            'checkedIn_at' => Carbon::now()
        ]);
    }
}
