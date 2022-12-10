<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use Illuminate\Http\Request;

class CertificationController extends Controller
{
    public function get(Request $request)
    {
        if (! $request->has('token')) {
            return abort(404);
        }
        $certification = Certification::where('signature', $request->query('token'))->latest()->first();
        if ($certification === null) {
            return abort(404);
        }
        return view('certification', ['certification' => $certification]);
    }
}
