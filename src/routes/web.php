<?php

use App\Models\Certification;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvitationsController;
use App\Http\Controllers\CertificationController;
use App\Http\Controllers\TrainingRegistrationsController;

Route::get('/', function () {
    return 'Welcome to CELEC API';
});

Route::get('admin/trainings/exportRegistrations/{training}', [TrainingRegistrationsController::class, 'export'])->middleware('auth');
Route::post('admin/trainings/exportRegistrations/{training}', [TrainingRegistrationsController::class, 'export'])->middleware('auth');
Route::post('admin/trainings/invitations', [InvitationsController::class, 'generate'])->middleware('auth');

Route::get('certification', [CertificationController::class, 'get']);

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
