<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Advertising;
use App\Models\Appointment;
use App\Models\DoctorAdvertise;
use App\Models\DoctorChamber;
use App\Models\User;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $data['totalAppointment'] = Appointment::where('doctor_id', auth()->id())->get()->count();
        // return
        $data['todaysAppointments'] = Appointment::where('doctor_id', auth()->id())
            ->whereDate('created_at', now())
            ->count();
        $data['totalCompounder'] = User::where('doctor_id', auth()->id())->get()->count();
        $data['totalChamber'] = DoctorChamber::where('user_id', auth()->id())->get()->count();

        $data['ongoingAds'] = DoctorAdvertise::with(['advertise' => function($query) {
                                        $query->with(['created_by']);
                                    }])
                                    ->where('doctor_id', auth()->id())
                                    ->where('status', 'approved')
                                    ->whereDate('end_at', '>=', date('Y-m-d'))
                                    ->get();
                                    // return $data['ongoingAds'];
        return Inertia::render('Doctor/Dashboard/Index', $data);
    }
}
