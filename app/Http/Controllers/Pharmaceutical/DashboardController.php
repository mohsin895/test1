<?php

namespace App\Http\Controllers\Pharmaceutical;

use App\Http\Controllers\Controller;
use App\Models\Advertising;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index() {
        return Inertia::render('Pharmaceutical/Dashboard/Index');
    }
}
