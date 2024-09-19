<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Specialization;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function specialization() {
        $specializations = Specialization::all();

        return view('frontend.specialization', compact('specializations'));
    }
    
    public function join_as_doctor() {
        return view('frontend.join_as_doctor');
    }
    public function terms_conditions() {
        return view('frontend.terms_conditions');
    }
    public function privacy_policy() {
        return view('frontend.privacy_policy');
    }
    public function how_it_works() {
        return view('frontend.how_it_works');
    }
}
