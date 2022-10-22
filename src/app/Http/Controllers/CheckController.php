<?php

namespace App\Http\Controllers;

use App\Models\Check;
use App\Models\Signature;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CheckController extends Controller
{
    public function checkin(Request $request): Check
    {
        $request->validate([
            'signature' => 'required',
        ]);
        $signature = Signature::where('checkin_code', $request->signature)->firstOrFail();

        // if (! $signature->member()->exists()) {
        //     return abort(404);
        // }

        return Check::create([
            'invitation_id' => $signature->invitation_id,
            'training_id' => $signature->invitation->training_id,
            'checkedIn_at' => Carbon::now(),
            'member_id' => $signature->member_id
        ]);
    }

    public function checkout(Request $request): Check
    {
        $request->validate([
            'signature' => 'required',
        ]);
        $signature = Signature::where('checkout_code', $request->signature)->firstOrFail();

        // if (! $signature->member()->exists()) {
        //     return abort(404);
        // }
        // $last_checkin = $signature->member->checks()->latest()->first();
        $last_checkin = Check::where('member_id', $signature->member_id)->latest()->first();
        if ($last_checkin === null) {
            return abort(404);
        }
        $last_checkin->update(['checkedOut_at' => Carbon::now()]);

        return $last_checkin;
    }
}
