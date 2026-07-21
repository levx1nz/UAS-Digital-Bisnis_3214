<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu.');
        }
        if (Auth::user()->isPlatformStaff()) {
            return $next($request);
        }
        return redirect('/')->with('error', 'Akses ditolak! Khusus staf platform.');
    }
}