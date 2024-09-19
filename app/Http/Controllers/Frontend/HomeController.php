<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Chamber;
use App\Models\DoctorSpecialization;
use App\Models\Specialization;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $doctors = User::with(['doctor_chambers'])->where('role', DOCTOR)->take(4)->get();
        $specializations = Specialization::all();
        $featured_hospitals = Chamber::where('featured', 1)->limit(4)->get();

        $doctors = collect($doctors)->map(function($item) {
            $weekdays = collect($item->doctor_chambers)->flatMap(function ($item) {
                $shortenedDays = array_map(function ($day) {
                    $date = new DateTime($day);
                    return $date->format("D");
                }, $item->weekdays ?? []);
                return $shortenedDays;
            })->toArray();
            $item->weekdays = $weekdays ?? [];
            return $item;
        });

        return view('frontend.home', compact('doctors', 'specializations', 'featured_hospitals'));
    }

    public function getSameSpecialtyDoctors(Request $request, $id)
    {

        $specialization = Specialization::findOrFail($id);
        // return
        $specialized_doctors = DoctorSpecialization::where('specialization_id', $specialization->id)
            ->with('doctor.designations.doctor_designation')
            ->paginate(12);

        return view('frontend.specilializedDoctorList', compact('specialized_doctors', 'specialization'));
    }


    public function hospitals(Request $request) {
        $selected_divisions = $request->divisions ?? [];
        $doctors = User::with(['doctor_details', 'designations' => function ($query) {
            return $query->with('doctor_designation');
        }])->where('role', 'doctor')->orderBy('id', 'desc')->paginate(12);

        $query = Chamber::with('hospital');
        if (count($selected_divisions)) {
            foreach ($selected_divisions as $key => $item) {
                if ($key == 0) {
                    $query->orWhere('division', 'like', '%'.$item.'%');
                } else {
                    $query->orWhere('division', 'like', '%'.$item.'%');
                }
            }
        }
        // dd($query);
        $hospitals = $query->paginate(12);
        
        return view('frontend.hospitals', compact('hospitals', 'selected_divisions'));
    }

    public function hospital_details($id) {
        $chamber = Chamber::findOrFail($id);
        $doctors = User::whereHas('doctor_chambers', function($query) use($chamber) {
            return $query->where('chamber_id', $chamber->id);
        })->paginate(20);
        
        return view('frontend.hospital_details', compact('doctors', 'chamber'));
        
    }
}
