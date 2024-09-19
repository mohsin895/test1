<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function appointments() {
        $appointments = Appointment::where('phone', auth()->user()->phone)->get();
        return view('user.appointments', compact('appointments'));
    }
}
