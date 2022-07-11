<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Http\Requests\SoreContactRequest;
use Illuminate\Http\Request;

class ContactController extends Controller {
    
    public function create(SoreContactRequest $request) : Contact {
        return Contact::create([
            "email" => $request->email,
            "message" => $request->message,
            "done" => false
        ]);
    }

}
