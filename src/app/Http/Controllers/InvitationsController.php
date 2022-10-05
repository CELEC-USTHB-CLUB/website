<?php

namespace App\Http\Controllers;

use App\Events\TrainingAcceptedUsersImported;
use App\Training;
use Illuminate\Http\Request;

class InvitationsController extends Controller
{
    public function generate(Request $request)
    {
        $training       =   Training::findOrFail($request->id);
        $validatedData  =   $request->validate(['file' => ['required', 'mimes:xlsx']]);
        $path           =   $validatedData['file']->store('uploaded-accepted-users');
        TrainingAcceptedUsersImported::dispatch($training, $path);
        // return redirect()->back()->with(['suc' => 'You file will be available to be downloaded here']);
    }
}
