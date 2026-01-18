<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPenghuni
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('penghuni')->check()) {
            return redirect()->route('login')->with('error', 'Anda harus login sebagai penghuni.');
        }

        return $next($request);
    }
}