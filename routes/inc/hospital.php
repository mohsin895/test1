<?php

use App\Http\Controllers\Hospital\BookappointmentController;
use App\Http\Controllers\Hospital\AppointmentController;
use App\Http\Controllers\Hospital\DoctorController;
use App\Http\Controllers\Hospital\HospitalController;
use App\Http\Controllers\Hospital\AdvertisementController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'hospitals.', 'prefix' => 'hospitals', 'middleware' => ['auth', 'isHospital']], function () {
    Route::get('/dashboard', [HospitalController::class, 'dashboard'])->name('dashboard');
   

    Route::group(['as' => 'doctors.', 'prefix' => 'doctors'], function () {
        Route::get('/', [DoctorController::class, 'index'])->name('index');
        Route::post('/filter', [DoctorController::class, 'filter'])->name('filter');
        Route::get('/{id}/configurations', [DoctorController::class, 'configurations'])->name('configurations');
        Route::get('/{id}/calender', [DoctorController::class, 'calender'])->name('calender');
        Route::post('/{id}/save_calender', [DoctorController::class, 'save_calender'])->name('save_calender');
        Route::post('/{id}/save-configurations', [DoctorController::class, 'save_configurations'])->name('save_configurations');
        Route::post('/{id}/save-specializations', [DoctorController::class, 'save_specializations'])->name('save_specializations');
        Route::post('/{id}/save-degrees', [DoctorController::class, 'save_degrees'])->name('save_degrees');
        Route::post('/{id}/save-weekdays', [DoctorController::class, 'save_weekdays'])->name('save_weekdays');
        Route::post('/{id}/save-appointment-control', [DoctorController::class, 'save_appointment_control'])->name('save_appointment_control');
        Route::post('/{id}/save-chamber', [DoctorController::class, 'save_chamber'])->name('save_chamber');
        Route::post('/{id}/save-fees', [DoctorController::class, 'save_fees'])->name('save_fees');
        Route::post('/{id}/save-designations', [DoctorController::class, 'save_designations'])->name('save_designations');
        Route::post('/save', [DoctorController::class, 'save'])->name('save');
        Route::post('/{id}/delete', [DoctorController::class, 'delete'])->name('delete');
    });

    Route::group(['as' => 'appointments.', 'prefix' => 'appointments'], function () {
        Route::get('/{id}/show', [AppointmentController::class, 'show'])->name('index');
        Route::get('/{id}/edit', [AppointmentController::class, 'edit'])->name('edit');
        Route::post('/update', [AppointmentController::class, 'update'])->name('update');
        Route::post('/{id}/delete', [AppointmentController::class, 'delete'])->name('delete');
        Route::post('/get-appointments', [AppointmentController::class, 'getAppointments'])->name('getAppointments');
        Route::post('/get-appointments/date', [AppointmentController::class, 'getAppointmentsDateWise'])->name('getAppointments.date');
        Route::post('/get-slot', [AppointmentController::class, 'slot'])->name('slot');

    });

    Route::group(['as' => 'bookappointment.', 'prefix' => 'bookappointment'], function () {

        Route::get('/', [BookappointmentController::class, 'index'])->name('index');
        Route::post('/filter-doctors', [BookappointmentController::class, 'filterDoctors'])->name('filterDoctors');
        Route::post('/save', [BookappointmentController::class, 'save'])->name('save');
        Route::post('/get-dates', [BookappointmentController::class, 'getDates'])->name('getDates');
        Route::post('/get-slot/{id}', [BookappointmentController::class, 'getSlots'])->name('getSlots');

    });
    Route::group(['as' => 'advertisements.', 'prefix' => 'advertisements'], function () {
        Route::get('/{id}/show', [AdvertisementController::class, 'show'])->name('index');
        Route::post('/{id}/get-advertisements', [AdvertisementController::class, 'getAppointments'])->name('getAppointments');
    });


});
