<?php

use App\Http\Controllers\Compounder\CompounderDashboardController;
use App\Http\Controllers\Compounder\AppointmentController;
use App\Http\Controllers\Compounder\ScheduleController;
use App\Http\Controllers\Compounder\BookAppoinmentController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'compounder.', 'middleware' => ['auth', 'isCompounder'], 'prefix' => 'compounder'], function () {

    Route::get('/dashboard', [CompounderDashboardController::class, 'index'])->name('dashboard');

    Route::group(['as' => 'appointments.', 'prefix' => 'appointments'], function () {
        Route::get('/', [AppointmentController::class, 'index'])->name('index');
        Route::post('/get-appointments', [AppointmentController::class, 'getAppointments'])->name('getAppointments');
        Route::post('/get-save', [AppointmentController::class, 'saveAppointments'])->name('saveAppointments');
        Route::post('/{appointment}/delete', [AppointmentController::class, 'delete'])->name('delete');
    });

    Route::group(['as' => 'schedule.', 'prefix' => 'schedule'], function () {
        Route::get('/', [ScheduleController::class, 'index'])->name('index');
        Route::post('update', [ScheduleController::class, 'update'])->name('update');
        Route::get('/visitation/{id}', [ScheduleController::class, 'visitation'])->name('visitation');
        Route::post('/visitation/{id}/start', [ScheduleController::class, 'start'])->name('start');
        Route::post('/visitation/update_status', [ScheduleController::class, 'update_status'])->name('update_status');
        Route::post('/visitation/save_break', [ScheduleController::class, 'save_break'])->name('save_break');
        Route::post('/prescription-upload/{id}', [ScheduleController::class, 'prescriptionUpload'])->name('prescription-upload');
    });

    Route::group(['as' => 'bookNew.', 'prefix' => 'bookNew'], function () {
        Route::get('/', [BookAppoinmentController::class, 'index'])->name('index');
        Route::get('/list', [BookAppoinmentController::class, 'appontmentList'])->name('list');
        Route::post('/get-appointments', [BookAppoinmentController::class, 'getAppointments'])->name('getAppointments');
        Route::post('/get-slot', [BookAppoinmentController::class, 'slot'])->name('slot');
        Route::post('/get-dates', [BookAppoinmentController::class, 'getDates'])->name('getDates');
        Route::post('/get-slot/{id}', [BookAppoinmentController::class, 'getSlots'])->name('getSlots');
        Route::post('/save', [BookAppoinmentController::class, 'save'])->name('save');
        // Route::post('/get-bookNew', [BookAppoinmentController::class, 'getbookNew'])->name('getbookNew');
        // Route::post('/get-save-bookNew', [BookAppoinmentController::class, 'savebookNew'])->name('savebookNew');
        // Route::post('/{bookNew}/delete', [BookAppoinmentController::class, 'delete'])->name('delete');
    });

});
