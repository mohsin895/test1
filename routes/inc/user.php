<?php

use App\Http\Controllers\User\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'appointments.', 'prefix' => 'appointments', 'middleware' => ['auth', 'isUser']], function () {
    Route::get('/', [AppointmentController::class, 'appointments'])->name('index');
});
