<?php

use App\Http\Controllers\CertificationController;
use App\Http\Controllers\EventRegistrationsController;
use App\Http\Controllers\InvitationsController;
use App\Http\Controllers\TrainingRegistrationsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Welcome to CELEC API';
});

Route::get('admin/trainings/exportRegistrations/{training}', [TrainingRegistrationsController::class, 'export'])->middleware('auth');
Route::post('admin/trainings/exportRegistrations/{training}', [TrainingRegistrationsController::class, 'export'])->middleware('auth');
Route::post('admin/trainings/invitations', [InvitationsController::class, 'generate'])->middleware('auth');

Route::get('admin/events/exportRegistrations/{event}', [EventRegistrationsController::class, 'export'])->middleware('auth');

Route::get('certification', [CertificationController::class, 'get']);

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
