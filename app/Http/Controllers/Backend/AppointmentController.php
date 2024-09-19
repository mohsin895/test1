<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Inertia\Inertia;

class AppointmentController extends Controller
{
    public function index()
    {
        $data = Appointment::orderBy('id', 'desc')->get();

        return Inertia::render('Admin/Appointment/Index', compact('data'));
    }

    public function delete(Appointment $appointment)
    {
        $appointment->update([
            'deleted_at' => now(),
        ]);

        // $appointment->delete();

        return redirect()->back()->with('message', 'Appointment deleted Successfully.');

    }
}
