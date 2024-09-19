<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required',
        ]);

        $user = User::where('phone', $request->phone)->first();
        // if ($user) {
        //     return back()->withErrors(['phone' => 'Phone number already exist']);
        // }
        $code = rand(1000, 9999);
        Otp::create([
            'phone' => $request->phone,
            'type' => 'register',
            'otp' => $code,
            'expire_time' => now()->addMinutes(5),
        ]);

        sendMessage('+88'.$request->phone, 'SOMOYMOTO-তে আপনার OTP হচ্ছে '.$code.'. এটির মেয়াদ ৫ মিনিট পর্যন্ত।');

        return redirect()->route('verify');

        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        // ]);

        // event(new Registered($user));

        // Auth::login($user);

        // return redirect(RouteServiceProvider::HOME);
    }

    public function verify() {
        $csrf = csrf_token();
        return Inertia::render('Auth/Verify', compact('csrf'));
    }

    public function verifyProcess(Request $request) {
        $request->validate([
            'otp' => 'required'
        ]);

        $otp = Otp::where('otp', $request->otp)->where('expire_time', '>', now())->first();
        if (!$otp) {
            return back()->withErrors(['otp' => 'Invalid OTP']);
        }
        $user = User::where('phone', $request->phone)->first();
        $password = Hash::make($otp->phone);
        if(!$user) {
            $user = User::create([
                'name' => $otp->phone,
                'phone' => $otp->phone,
                'password' => $password,
                'role' => 'user'
            ]);
        }
        Otp::where('phone', $otp->phone)->delete();
        // send password and success notification to phone
        Auth::login($user);
        return redirect()->route('appointments.index');
    }
}
