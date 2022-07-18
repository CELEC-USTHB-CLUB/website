<?php

use App\Http\Controllers\TrainingRegistrationsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('admin/trainings/exportRegistrations/{training}', [TrainingRegistrationsController::class, 'export'])->middleware('auth');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
