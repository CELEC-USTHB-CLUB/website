<?php

namespace App\Http\Controllers;

use App\Models\Signature;
use Illuminate\Http\Request;

class PaperController extends Controller
{
    public function check(Request $request): Signature
    {
        $request->validate([
            'signature' => 'required',
        ]);
        $signature = Signature::where('paper_code', $request->signature)->firstOrFail();
        if ($signature->invitation->training_id !== (int) substr($request->signature, -1)) {
            return abort(404);
        }

        return $signature;
    }
}
