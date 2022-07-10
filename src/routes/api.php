<?php

use App\Http\Controllers\MemberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::GROUP(["prefix" => "member"], function() {
    Route::POST("create", [MemberController::class, "create"]);
});
