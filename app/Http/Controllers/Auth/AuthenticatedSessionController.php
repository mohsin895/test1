<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        // dd($request->all());
        $request->authenticate();

        $request->session()->regenerate();

        $user = auth()->user();
        
        if ($user->role == 'admin') {
            return redirect()->route('app.dashboard');
        }
        
        if ($user->role == 'advertiser') {
            return redirect()->route('pharmaceutical.dashboard');
        }
        if ($user->role == 'compounder') {
            return redirect()->route('compounder.dashboard');
        }
        if ($user->role == 'hospital') {
            return redirect()->route('hospitals.dashboard');
        }

        return redirect()->route('doctor.dashboard');

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
