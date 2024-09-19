<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Advertising;
use App\Models\Appointment;
use App\Models\Chamber;
use App\Models\DoctorAdvertise;
use App\Models\DoctorChamber;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdvertisementController extends Controller
{
    public function show($id)
    {
        $doctor = User::findOrFail($id);
        // return
        $advertisements = DoctorAdvertise::with(['advertise' => function($query) {
                                return $query->with(['created_by']);
                            }])->where('doctor_id', $id)
                            ->orderBy('created_at', 'desc')
                            ->get();
        // $data = Appointment::with(['slot'])->where(['doctor_id' => $id])->orderBy('id', 'desc')->get();
        // $doctor = User::with(['doctor_chambers' => function($query) {
        //                         return $query->with(['slots', 'chamber']);
        //                     }])->find($id);
        // $tomorrow = now()->addDay()->format('m-d-Y');
        // $today_name = strtolower(now()->format('l'));

        return Inertia::render('Hospital/Appointments/Advertiseing', compact('advertisements', 'id', 'doctor'));
    }

    public function getAppointments(Request $request, $id) {
        $query = Appointment::where('doctor_id', $id);

        if ($request->type) {
            if ($request->type == 'today') {
                $query->whereDate('date', now());
            }
            if ($request->type == 'upcoming') {
                $query->whereDate('date', '>', now());
            }
            if ($request->type == 'previous') {
                $query->whereDate('date', '<', now());
            }
            if ($request->type == 'tomorrow') {
                $query->whereDate('date', now()->addDay());
            }
        }
        // dd($query->get());

        if ($request->chamber_id) {
            $doctor_chamber = DoctorChamber::find($request->chamber_id);
            if ($doctor_chamber) {
                $query->where('chamber_id', $doctor_chamber->chamber_id);
            }
        }

        if ($request->slot_id) {
            $query->where('slot_id', $request->slot_id);
        }

        $query->with(['slot'])->orderBy('id', 'desc');

        $data = $query->get() ?? [];
        
        return response()->json([
            'data' => $query->get(),
            'length' => count($data)
        ]);
    }
}
