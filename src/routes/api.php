<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\TrainingController;
use Illuminate\Support\Facades\Route;

Route::GROUP(['prefix' => 'member'], function () {
    Route::POST('create', [MemberController::class, 'create']);
});

Route::GROUP(['prefix' => 'trainer'], function () {
    Route::POST('create', [TrainerController::class, 'create']);
});

Route::GROUP(['prefix' => 'tags'], function () {
    Route::GET('get', [TagController::class, 'get']);
});

Route::GROUP(['prefix' => 'trainings'], function () {
    Route::GET('get', [TrainingController::class, 'all']);
    Route::GET('{training:slug}', [TrainingController::class, 'get']);
    Route::POST('{training:slug}/register', [TrainingController::class, 'register']);
});

Route::GROUP(['prefix' => 'team'], function () {
    Route::GET('all', [TeamController::class, 'all']);
});

Route::GROUP(['prefix' => 'contact'], function () {
    Route::POST('create', [ContactController::class, 'create']);
});

Route::GROUP(['prefix' => 'events'], function () {
    Route::GET('all', [EventController::class, 'all']);
});
