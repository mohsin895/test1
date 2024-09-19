<?php

use App\Http\Controllers\Doctor\AdvertisingController;
use App\Http\Controllers\Doctor\AppointmentController;
use App\Http\Controllers\Doctor\CompounderController;
use App\Http\Controllers\Doctor\BookAppoinmentController;
use App\Http\Controllers\Doctor\ConfigurationController;
use App\Http\Controllers\Doctor\DashboardController;
use App\Http\Controllers\Doctor\ReportController;
use App\Http\Controllers\ScheduleController;
use App\Models\Chamber;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'doctor.', 'middleware' => ['auth', 'isDoctor'], 'prefix' => 'doctor'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/configurations', [ConfigurationController::class, 'configurations'])->name('configurations');
    Route::get('/calender', [ConfigurationController::class, 'calender'])->name('calender');
    Route::post('/save_calender', [ConfigurationController::class, 'save_calender'])->name('save_calender');
    Route::post('/save-configurations', [ConfigurationController::class, 'save_configurations'])->name('save_configurations');
    Route::post('/save-specializations', [ConfigurationController::class, 'save_specializations'])->name('save_specializations');
    Route::post('/save-degrees', [ConfigurationController::class, 'save_degrees'])->name('save_degrees');
    Route::post('/save-weekdays', [ConfigurationController::class, 'save_weekdays'])->name('save_weekdays');
    Route::post('/save-appointment-control', [ConfigurationController::class, 'save_appointment_control'])->name('save_appointment_control');
    Route::post('/save-chamber', [ConfigurationController::class, 'save_chamber'])->name('save_chamber');
    Route::post('/save-fees', [ConfigurationController::class, 'save_fees'])->name('save_fees');
    Route::post('/save-designations', [ConfigurationController::class, 'save_designations'])->name('save_designations');

    Route::group(['as' => 'appointments.', 'prefix' => 'appointments'], function () {
        Route::get('/', [AppointmentController::class, 'index'])->name('index');
        Route::post('/get-appointments', [AppointmentController::class, 'getAppointments'])->name('getAppointments');
        Route::post('/get-save', [AppointmentController::class, 'saveAppointments'])->name('saveAppointments');
        Route::post('/{appointment}/delete', [AppointmentController::class, 'delete'])->name('delete');
        
    });
    Route::group(['as' => 'bookNew.', 'prefix' => 'bookNew'], function () {
        Route::get('/list', [BookAppoinmentController::class, 'appontmentList'])->name('list');
        Route::get('/', [BookAppoinmentController::class, 'index'])->name('index');
        Route::post('/get-dates', [BookAppoinmentController::class, 'getDates'])->name('getDates');
        Route::post('/get-slot/{id}', [BookAppoinmentController::class, 'getSlots'])->name('getSlots');
        Route::post('/save', [BookAppoinmentController::class, 'save'])->name('save');
        Route::post('/get-appointments', [BookAppoinmentController::class, 'getAppointments'])->name('getAppointments');
        Route::post('/get-appointments/date', [BookAppoinmentController::class, 'getAppointmentsDateWise'])->name('getAppointments.date');
        Route::post('/get-slot', [BookAppoinmentController::class, 'slot'])->name('slot');
        Route::post('/{id}/delete', [BookAppoinmentController::class, 'delete'])->name('delete');
        Route::get('/{id}/edit', [BookAppoinmentController::class, 'edit'])->name('edit');
        Route::post('/update', [BookAppoinmentController::class, 'update'])->name('update');
        // Route::post('/get-bookNew', [BookAppoinmentController::class, 'getbookNew'])->name('getbookNew');
        // Route::post('/get-save-bookNew', [BookAppoinmentController::class, 'savebookNew'])->name('savebookNew');
        // Route::post('/{bookNew}/delete', [BookAppoinmentController::class, 'delete'])->name('delete');
    });

    Route::group(['as' => 'schedule.', 'prefix' => 'schedule'], function () {
        Route::get('/', [ScheduleController::class, 'index'])->name('index');
        Route::post('update', [ScheduleController::class, 'update'])->name('update');
        Route::get('/visitation/{id}', [ScheduleController::class, 'visitation'])->name('visitation');
        Route::post('/visitation/{id}/start', [ScheduleController::class, 'start'])->name('start');
        Route::post('/visitation/update_status', [ScheduleController::class, 'update_status'])->name('update_status');
        Route::post('/visitation/save_break', [ScheduleController::class, 'save_break'])->name('save_break');
        Route::post('/visitation/end_break', [ScheduleController::class, 'end_break'])->name('end_break');
        Route::post('/visitation/save_appointment_all', [ScheduleController::class, 'save_appointment_all'])->name('save_appointment_all');
        Route::post('/visitation/update_appointment_all', [ScheduleController::class, 'update_appointment_all'])->name('update_appointment_all');
        Route::post('/visitation/save_appointment_report', [ScheduleController::class, 'save_appointment_report'])->name('save_appointment_report');
        Route::post('/visitation/save_new_appointment', [ScheduleController::class, 'save_new_appointment'])->name('save_new_appointment');
        Route::post('/prescription-upload/{id}', [ScheduleController::class, 'prescriptionUpload'])->name('prescription-upload');
    });
    Route::group(['as' => 'advertising.', 'prefix' => 'advertising'], function () {
        Route::get('/', [AdvertisingController::class, 'index'])->name('index');
        Route::post('/advertising-approved/{id}', [AdvertisingController::class, 'AdvertisingApproved'])->name('AdvertisingApproved');
        Route::post('/advertising-reject/{id}', [AdvertisingController::class, 'AdvertisingReject'])->name('AdvertisingReject');
        Route::post('/filtered-by-status', [AdvertisingController::class, 'FilteredByStatus'])->name('FilteredByStatus');
    });
    Route::group(['as' => 'compounder.', 'prefix' => 'compounder'], function () {
        Route::get('/', [CompounderController::class, 'index'])->name('index');
        Route::post('/save', [CompounderController::class, 'save'])->name('save');
        Route::post('/{id}/delete', [CompounderController::class, 'delete'])->name('delete');
    });

    Route::group(['as' => 'report.', 'prefix' => 'report'], function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::post('/get', [ReportController::class, 'report'])->name('get');

        
    });

});

// create user for hospital
Route::get('/create-hospital', function() {
    return Chamber::with(['hospital'])->get();
});