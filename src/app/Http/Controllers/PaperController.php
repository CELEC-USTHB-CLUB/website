<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Signature;

class PaperController extends Controller
{
    public function check(Request $request): Signature
    {
        $request->validate([
            'signature' => 'required'
        ]);
        return Signature::where('paper_code', $request->signature)->firstOrFail();
    }
}
