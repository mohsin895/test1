<?php

use App\Http\Controllers\Backend\AdvertiserController;
use App\Http\Controllers\Backend\AppointmentController;
use App\Http\Controllers\Backend\BookAppointmentController;
use App\Http\Controllers\Backend\ChamberController;
use App\Http\Controllers\Backend\ContactController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\DegreeController;
use App\Http\Controllers\Backend\DesignationsController;
use App\Http\Controllers\Backend\DoctorController;
use App\Http\Controllers\Backend\MedicalController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\SpecializationController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'app.', 'middleware' => ['auth', 'isAdmin'], 'prefix' => 'admin'], function () {
    Route::group(['as' => 'profile.', 'prefix' => 'profile'], function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::post('/change-profile-info', [ProfileController::class, 'changeProfileInfo'])->name('changeProfileInfo');
        Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('changePassword');
    });
});

Route::group(['as' => 'app.', 'middleware' => ['auth', 'isAdmin'], 'prefix' => 'admin'], function () {

    Route::group(['as' => 'roles.', 'prefix' => 'roles'], function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::post('/save', [RoleController::class, 'save'])->name('save');
        Route::group(['as' => 'sub_roles.', 'prefix' => 'sub_roles'], function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::post('/save', [RoleController::class, 'save'])->name('save');
        });
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/get-routes', [RoleController::class, 'getAllRoutes'])->name('get_routes');

    Route::group(['as' => 'doctors.', 'prefix' => 'doctors'], function () {
        Route::get('/', [DoctorController::class, 'index'])->name('index');
        Route::post('/save', [DoctorController::class, 'save'])->name('save');
    });

    Route::group(['as' => 'specializations.', 'prefix' => 'specializations'], function () {
        Route::get('/', [SpecializationController::class, 'index'])->name('index');
        Route::post('/save', [SpecializationController::class, 'save'])->name('save');
        Route::post('/{id}/delete', [SpecializationController::class, 'delete'])->name('delete');
    });

    Route::group(['as' => 'degrees.', 'prefix' => 'degrees'], function () {
        Route::get('/', [DegreeController::class, 'index'])->name('index');
        Route::post('/save', [DegreeController::class, 'save'])->name('save');
        Route::post('/{id}/delete', [DegreeController::class, 'delete'])->name('delete');
    });

    Route::group(['as' => 'medicals.', 'prefix' => 'medicals'], function () {
        Route::get('/', [MedicalController::class, 'index'])->name('index');
        Route::post('/save', [MedicalController::class, 'save'])->name('save');
        Route::post('/{id}/delete', [MedicalController::class, 'delete'])->name('delete');
    });

    Route::group(['as' => 'chambers.', 'prefix' => 'chambers'], function () {
        Route::get('/', [ChamberController::class, 'index'])->name('index');
        Route::post('/save', [ChamberController::class, 'save'])->name('save');
        Route::post('/{id}/delete', [ChamberController::class, 'delete'])->name('delete');
    });

    Route::group(['as' => 'designations.', 'prefix' => 'designations'], function () {
        Route::get('/', [DesignationsController::class, 'index'])->name('index');
        Route::post('/save', [DesignationsController::class, 'save'])->name('save');
        Route::post('/{id}/delete', [DesignationsController::class, 'delete'])->name('delete');
    });

    Route::group(['as' => 'settings.', 'prefix' => 'settings'], function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/save', [SettingController::class, 'save'])->name('save');
    });

    Route::group(['as' => 'pages.', 'prefix' => 'pages'], function () {
        Route::get('/', [PageController::class, 'index'])->name('index');
        Route::post('/save', [PageController::class, 'save'])->name('save');
        Route::post('/{page}/delete', [PageController::class, 'delete'])->name('delete');
    });

    Route::group(['as' => 'doctors.', 'prefix' => 'doctors'], function () {
        Route::get('/', [DoctorController::class, 'index'])->name('index');
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

    Route::group(['as' => 'contacts.', 'prefix' => 'contacts'], function () {
        Route::get('/', [ContactController::class, 'index'])->name('index');
        Route::post('/{contact}/delete', [ContactController::class, 'delete'])->name('delete');
    });

    Route::group(['as' => 'appointments.', 'prefix' => 'appointments'], function () {
        Route::get('/', [AppointmentController::class, 'index'])->name('index');
        Route::post('/{appointment}/delete', [AppointmentController::class, 'delete'])->name('delete');
    });

    Route::group(['as' => 'bookappointment.', 'prefix' => 'bookappointment'], function () {

        Route::get('/', [BookAppointmentController::class, 'index'])->name('index');
        Route::post('/filter-doctors', [BookAppointmentController::class, 'filterDoctors'])->name('filterDoctors');
        Route::post('/save', [BookAppointmentController::class, 'save'])->name('save');
        Route::post('/get-dates', [BookAppointmentController::class, 'getDates'])->name('getDates');
        Route::post('/get-slot/{id}', [BookAppointmentController::class, 'getSlots'])->name('getSlots');

    });

    Route::group(['as' => 'advertisers.', 'prefix' => 'advertisers'], function () {
        Route::get('/', [AdvertiserController::class, 'index'])->name('index');
        Route::post('/save', [AdvertiserController::class, 'save'])->name('save');
        Route::post('/{id}/delete', [AdvertiserController::class, 'delete'])->name('delete');

    });

});
