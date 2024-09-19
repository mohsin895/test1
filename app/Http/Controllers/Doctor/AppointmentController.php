<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\DoctorChamber;
use App\Models\PatientType;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AppointmentController extends Controller
{
    public function index()
    {
        $data = Appointment::with(['slot'])->where(['doctor_id' => auth()->id()])->orderBy('id', 'desc')->get();
        $doctor = User::with(['doctor_chambers' => function($query) {
                                return $query->with(['slots', 'chamber']);
                            }])->find(auth()->id());
        $tomorrow = now()->addDay()->format('m-d-Y');
        $today_name = strtolower(now()->format('l'));
        $patientTypes = PatientType::where('status', 1)->get();
        
        return Inertia::render('Doctor/Appointment/Index', compact('data', 'doctor', 'tomorrow', 'today_name', 'patientTypes'));
    }

    public function getAppointments(Request $request) {
        $query = Appointment::where('doctor_id', auth()->id());

        if ($request->type) {
            if ($request->type == 'today') {
                $query->whereDate('date', date('Y-m-d'));
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

    public function saveAppointments(Request $request) {
        $request->validate([
            'patient_name' => 'required',
            'phone' => 'required',
            'age' => 'required',
            'date' => 'required',
            'patient_type_id' => 'required',
        ]);
        $appointment = Appointment::find($request->id);
        if(!$appointment) {
            return back()->with('error', 'Opps! Appointment not found.');
        }

        $appointment->update([
            'patient_name' => $request->patient_name,
            'phone' => $request->phone,
            'age' => $request->age,
            'date' => $request->date,
            'patient_type_id' => $request->patient_type_id,
        ]);
        return back()->with('message', 'Appointment saved successfully.');
    }
    
    public function delete($id)
    {
        $hospital = Appointment::findOrFail($id);

        $hospital->update([
            'deleted_by' => auth()->id(),
            'deleted_at' => now(),
        ]);

        return redirect()->back()->with('message', 'Appontment deleted Successfully.');

    }
}
