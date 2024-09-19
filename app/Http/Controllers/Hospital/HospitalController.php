<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HospitalController extends Controller
{
    public function dashboard() {
        return Inertia::render('Hospital/Dashboard/Index');
    }
}
