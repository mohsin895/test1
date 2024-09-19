<?php

namespace App\Http\Controllers\Compounder;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\CompounderDetails;
use App\Models\DoctorChamber;
use App\Models\PatientType;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AppointmentController extends Controller
{
    public function index()
    {
        $compounder = User::find(auth()->id());

        $slots = CompounderDetails::where('compounder_id', auth()->id())->get()->pluck('slot_id');
        $data = Appointment::with(['slot'])->where(['doctor_id' => $compounder->doctor_id])->orderBy('id', 'desc')->get();
        $doctor = User::with(['doctor_chambers' => function($query) use($slots) {
                                return $query->with(['slots' =>function($q) use($slots) {
                                    return $q->whereIn('id', $slots ?? []);
                                }, 'chamber']);
                            }])->find($compounder->doctor_id);
        $tomorrow = now()->addDay()->format('m-d-Y');
        $today_name = strtolower(now()->format('l'));
        $patientTypes = PatientType::where('status', 1)->get();
        
        return Inertia::render('Compounder/Appointment/Index', compact('data', 'doctor', 'tomorrow', 'today_name', 'patientTypes'));
    }

    public function getAppointments(Request $request) {
        $compounder = auth()->user();
        $query = Appointment::where('doctor_id', $compounder->doctor_id);

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
    
    public function delete(Appointment $appointment)
    {
        $appointment->update([
            'deleted_at' => now(),
        ]);

         $appointment->delete();

        return redirect()->back()->with('message', 'Appointment deleted Successfully.');

    }
}
