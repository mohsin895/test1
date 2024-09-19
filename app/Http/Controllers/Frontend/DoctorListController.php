<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Degree;
use App\Models\Designation;
use App\Models\Specialization;
use App\Models\User;

class DoctorListController extends Controller
{
    public function doctorList()
    {
        // return
        $doctors = User::with(['doctor_details', 'designations' => function ($query) {
            return $query->with('doctor_designation');
        }])->where('role', 'doctor')->orderBy('id', 'desc')->paginate(12);
        return view('frontend.doctorList', compact('doctors'));

    }
}
