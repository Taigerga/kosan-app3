<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Cek role user yang login
                $user = Auth::guard($guard)->user();
                
                if ($guard === 'penghuni' || (isset($user->role) && $user->role === 'penghuni')) {
                    return redirect()->route('penghuni.dashboard');
                }
                
                if ($guard === 'pemilik' || (isset($user->role) && $user->role === 'pemilik')) {
                    return redirect()->route('pemilik.dashboard');
                }
                
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}