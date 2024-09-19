<?php

use App\Http\Controllers\Pharmaceutical\AdvertisingController;
use App\Http\Controllers\Pharmaceutical\DashboardController;
use App\Http\Controllers\Pharmaceutical\PrescriptionController;
use Illuminate\Support\Facades\Route;

Route::group([
        'as' => 'pharmaceutical.', 
        'middleware' => ['auth', 'isPharmaceutical'], 
        'prefix' => 'advertiser'
    ], 
    function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['as' => 'advertisings.', 'prefix' => 'advertisings'], function () {
        Route::get('/', [AdvertisingController::class, 'index'])->name('index');
        Route::post('/filter-doctors', [AdvertisingController::class, 'filterDoctors'])->name('filterDoctors');
        Route::post('/send-request', [AdvertisingController::class, 'sendRequest'])->name('sendRequest');
        Route::get('/advertising-list', [AdvertisingController::class, 'getAllRequest'])->name('show');
        Route::get('/prescription-list/{id}', [AdvertisingController::class, 'getDoctorPrescriptionList'])->name('doctor-prescription-list');

    });
});
