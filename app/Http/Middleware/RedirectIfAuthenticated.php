<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $guard = null): Response
    {
        $user = auth()->user();
        if ($user) {
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('app.dashboard');
                    break;
                case 'compounder':
                    return redirect()->route('compounder.dashboard');
                    break;
                case 'advertiser':
                    return redirect()->route('pharmaceutical.dashboard');
                    break;
                case 'user':
                    return redirect()->route('appointments.index');
                    break;
                case 'hospital':
                    return redirect()->route('hospitals.dashboard');
                    break;

                default:
                    return redirect()->route('doctor.dashboard');
                    break;
            }
        }

        return $next($request);
    }
}
