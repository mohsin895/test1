<?php

namespace App\Http\Controllers\Compounder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompounderDashboardController extends Controller
{
    public function index() {
        return Inertia::render('Compounder/Dashboard/Index');
    }
}
