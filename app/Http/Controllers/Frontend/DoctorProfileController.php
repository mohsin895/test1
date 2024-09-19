<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PatientType;
use App\Models\User;
use Illuminate\Http\Request;

class DoctorProfileController extends Controller
{
    public function doctorProfile($id)
    {

        // return
        $doctorProfile = User::with(['doctor_chambers', 'designations.doctor_designation', 'doctor_details', 'degrees' => function ($query) {
            return $query->with('doctor_degrees');
        }])->where('id', $id)->first();
        $doc_slug = str()->slug($doctorProfile->name);
        $patientTypes = PatientType::where('status', 1)->get();
        return view('frontend.doctorProfile', compact('doctorProfile', 'doc_slug', 'patientTypes'));

    }

    public function relatedDoctors(Request $request)
    {

        $specializationIds = explode(',', $request->specialization) ?? [];
        $relatedDoctors = User::whereHas('specializations', function ($query) use ($specializationIds) {
            $query->whereIn('specialization_id', $specializationIds);
        })
        ->get();

        return view('frontend.relatedDoctors', compact('relatedDoctors'));
    }

}
