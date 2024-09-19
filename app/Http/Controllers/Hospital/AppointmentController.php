<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Chamber;
use App\Models\DoctorChamber;
use App\Models\PatientType;
use App\Models\Slot;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AppointmentController extends Controller
{
    public function show($id)
    {
        $chamber = Chamber::where('user_id',auth()->id())->first();
        $doctor = User::where('id',$id)->first();
      
        // $doctor = User::with(['doctor_details','doctor_chambers','doctor_chambers.chamber'])->find(auth()->id());
        $appointmentList = Appointment::where('chamber_id',$chamber->id)->where('doctor_id',$id)->orderBy('id', 'desc')->get();
       

        return Inertia::render('Hospital/Appointments/List', compact('appointmentList', 'doctor', 'id','chamber'));
    }
    public function edit($id)
{
    $appointmentList = Appointment::where('id', $id)->first();
    $patientTypes = PatientType::where('status', 1)->get();
    $doctor = User::with(['doctor_details','doctor_chambers','doctor_chambers.chamber'])->where('id',$appointmentList->doctor_id)->first();

    return Inertia::render('Hospital/Appointments/Edit', [
        'appointmentList' => $appointmentList,
        'id' => $id,
        'patientTypes'=>$patientTypes,
        'doctor'=>$doctor,
    ]);
}

public function update(Request $request){
    $dataInfo = Appointment::where('id', $request->id)->first();
    $dataInfo->patient_name=$request->patient_name;
    $dataInfo->phone=$request->phone;
    $dataInfo->age = $request->age;
    $dataInfo->patient_type_id=$request->patient_type_id;
    $dataInfo->save();
    return redirect()->route('hospitals.appointments.index', ['id' => $dataInfo->doctor_id]);
}
    // public function getAppointments(Request $request, $id) {
    //     $query = Appointment::where('doctor_id', $id);

    //     if ($request->type) {
    //         if ($request->type == 'today') {
    //             $query->whereDate('date', now());
    //         }
    //         if ($request->type == 'upcoming') {
    //             $query->whereDate('date', '>', now());
    //         }
    //         if ($request->type == 'previous') {
    //             $query->whereDate('date', '<', now());
    //         }
    //         if ($request->type == 'tomorrow') {
    //             $query->whereDate('date', now()->addDay());
    //         }
    //     }
      

    //     if ($request->chamber_id) {
    //         $doctor_chamber = DoctorChamber::find($request->chamber_id);
    //         if ($doctor_chamber) {
    //             $query->where('chamber_id', $doctor_chamber->chamber_id);
    //         }
    //     }

    //     if ($request->slot_id) {
    //         $query->where('slot_id', $request->slot_id);
    //     }

    //     $query->with(['slot'])->orderBy('id', 'desc');

    //     $data = $query->get() ?? [];
        
    //     return response()->json([
    //         'data' => $query->get(),
    //         'length' => count($data)
    //     ]);
    // }

    public function getAppointments(Request $request ) {
        $chamber = Chamber::where('user_id',auth()->id())->first();
        $query = Appointment::where('chamber_id',$chamber->id)->where('doctor_id',$request->doctor_id)->orderBy('id', 'desc');

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
        if ($request->startAt) {
            $query->whereDate('date', '>=', $request->startAt);
        }
    
        if ($request->endAt) {
            $query->whereDate('date', '<=', $request->endAt);
        }
        if ($request->particularDate) {
            $query->whereDate('date', $request->particularDate);
        }
        if ($request->chamber_id) {
            $doctor_chamber = DoctorChamber::find($request->chamber_id);
            if ($doctor_chamber) {
                $query->where('chamber_id', $doctor_chamber->chamber_id);
            }
        }

        if ($request->slot_id) {
            $slot=Slot::find($request->slot_id);
            if($slot){
                $query->where('slot_id', $request->slot_id);
            }
           
        }
        

        $query->with(['slot','chamber'])->orderBy('id', 'desc');

        $data = $query->get() ?? [];
        
        return response()->json([
            'data' => $query->get(),
            'length' => count($data)
        ]);
    }

    public function slot(Request $request){
        $slots = Slot::where('doctor_chamber_id',$request->chamber_id)->get();
        return response()->json([
            'slots' => $slots,
           
        ]);
    }

    public function delete($id)
    {
        $hospital = Appointment::findOrFail($id);
        $hospital->delete();

        // $hospital->update([
        //     'deleted_by' => auth()->id(),
        //     'deleted_at' => now(),
        // ]);

        return redirect()->back()->with('message', 'Appontment deleted Successfully.');

    }
}
