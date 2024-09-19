<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\DoctorListController;
use App\Http\Controllers\Frontend\DoctorProfileController;
use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/specialized-doctors/{id}', [HomeController::class, 'getSameSpecialtyDoctors'])->name('specialized.doctors');

Route::group(['as' => 'contact.', 'prefix' => 'contact'], function () {
    Route::get('/', [ContactController::class, 'index'])->name('index');
    Route::post('/save', [ContactController::class, 'save'])->name('save');
});

Route::group(['as' => 'doctor-profile.', 'prefix' => 'doctor-profile'], function () {
    Route::get('/{id}', [DoctorProfileController::class, 'doctorProfile'])->name('doctorProfile');
    
});
Route::get('/related-doctors', [DoctorProfileController::class, 'relatedDoctors'])->name('related-doctors');

Route::group(['as' => 'doctors.', 'prefix' => 'doctors'], function () {
    Route::get('/', [DoctorListController::class, 'doctorList'])->name('index');
});

Route::group(['as' => 'book-appointment.', 'prefix' => 'book-appointment'], function () {
    Route::get('/get-dates/{id}', [AppointmentController::class, 'getDates'])->name('getDates');
    Route::any('/get-slot/{id}', [AppointmentController::class, 'getSlots'])->name('getSlots');
    Route::post('/confirm/{id}', [AppointmentController::class, 'storeAppointment'])->name('store');
    Route::get('/doctor/{id}/{doc_slug}', [AppointmentController::class, 'index'])->name('index');
});

Route::get('/bookAppointment', function () {
    return view('frontend.bookAppointment');
});
Route::get('hospitals', [HomeController::class, 'hospitals'])->name('hospitals');
Route::get('how-it-works', [FrontendController::class, 'how_it_works'])->name('how_it_works');
Route::get('specialization', [FrontendController::class, 'specialization'])->name('specialization');
Route::get('join-as-doctor', [FrontendController::class, 'join_as_doctor'])->name('join_as_doctor');
Route::get('terms-conditions', [FrontendController::class, 'terms_conditions'])->name('terms_conditions');
Route::get('privacy-policy', [FrontendController::class, 'privacy_policy'])->name('privacy_policy');
Route::get('hospital/{id}/{slug?}', [HomeController::class, 'hospital_details'])->name('hospital_details');
Route::get('/track-appointment/{code}', [AppointmentController::class, 'appointmentTrack'])->name('appointmentTrack');
Route::get('/track-appointment-ajax/{code}', [AppointmentController::class, 'appointmentTrackAjax'])->name('appointmentTrackAjax');
Route::get('/t/{code}', function($code=null) {
    if ($code) {
        return redirect()->route('appointmentTrack', $code);
    }
    abort(404);
})->name('track_redirect');

Route::post('/get-distance', [AppointmentController::class, 'getDistance'])->name('getDistance');
// Route::get('/map-distance/{userLat}/{userLng}/{destinationLat}/{destinationLng}', [AppointmentController::class, 'mapDistance'])->name('mapDistance');

Route::get('/about', function () {
    return view('frontend.about');
})->name('about');

Route::get('/password', function () {
    return bcrypt('password');
});

Route::get('/fresh-01722600730', function () {
    Artisan::call('migrate:fresh --seed');
    Artisan::call('db:seed');
    echo 'Fresh seed run successfully';
});
