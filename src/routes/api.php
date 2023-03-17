<?php

use App\Models\ArcAnnouncement;
use App\Models\ArcRegistration;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PaperController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ArcTeamController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\ArcAnnouncementController;
use App\Http\Controllers\ArcRegistrationController;
use App\Http\Controllers\ArcAnnouncementConttroller;

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
    Route::GET('{event}', [EventController::class, 'get']);
    Route::POST('{event}/register', [EventController::class, 'register']);
});

Route::GROUP(['prefix' => 'invitation'], function () {
    Route::POST('signature/paper/check', [PaperController::class, 'check']);
    Route::POST('signature/paper/checkin', [CheckController::class, 'checkin']);
    Route::POST('signature/paper/checkout', [CheckController::class, 'checkout']);
});

Route::GROUP(['prefix' => 'arc'], function() {
    Route::POST('registration', [ArcRegistrationController::class, 'create']);
    Route::GROUP(['prefix' => 'team'], function() {
        Route::GET('check/{code}', [ArcTeamController::class, 'get']);
    });
    Route::POST('login', [ArcRegistrationController::class, 'auth']);
    Route::GET('announcements', [ArcAnnouncementController::class, 'all']);
    Route::GET('me', [ArcRegistrationController::class, 'get'])->middleware(['auth:sanctum']);
});