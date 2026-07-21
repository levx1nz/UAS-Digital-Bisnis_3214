<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OrganizerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $user = Auth::user();

        if ($user->isPlatformStaff()) {
            return $next($request); // staf boleh mengintip
        }
        if (!$user->isOrganizer()) {
            return redirect()->route('home')->with('error', 'Akses ditolak! Khusus Penyelenggara.');
        }
        if ($user->account_status !== 'approved') {
            return redirect()->route('organizer.pending');
        }
        return $next($request);
    }
}