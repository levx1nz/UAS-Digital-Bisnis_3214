<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $paidStatuses = ['settlement', 'success'];

        $totalRevenue = Transaction::whereIn('status', $paidStatuses)->sum('total_price');
        $ticketsSold = Transaction::whereIn('status', $paidStatuses)->count();
        $activeEvents = Event::where('date', '>=', now())->count();
        $pendingOrders = Transaction::whereIn('status', ['pending', 'Pending'])->count();
        $recentTransactions = Transaction::with('event')->latest()->take(5)->get();
        $totalOrganizers = User::where('role', 'organizer')->count();
        $pendingOrganizers = User::where('role', 'organizer')->where('account_status', 'pending')->count();

        $users = User::latest()->get();

        $startWindow = now()->startOfMonth()->subMonths(5);
        $months = collect(range(5, 0))->map(fn ($i) => now()->startOfMonth()->subMonths($i));

        $chartLabels = $months->map(fn ($m) => $m->format('M Y'))->values();

        $recentUsers = User::where('created_at', '>=', $startWindow)->get(['created_at']);
        $recentEvents = Event::where('created_at', '>=', $startWindow)->get(['created_at']);

        $newUsersPerMonth = $months->map(
            fn ($m) => $recentUsers->filter(fn ($u) => $u->created_at->isSameMonth($m))->count()
        )->values();
        $newEventsPerMonth = $months->map(
            fn ($m) => $recentEvents->filter(fn ($e) => $e->created_at->isSameMonth($m))->count()
        )->values();

        $baseUsers = User::where('created_at', '<', $startWindow)->count();
        $baseEvents = Event::where('created_at', '<', $startWindow)->count();

        $runningU = $baseUsers;
        $cumulativeUsers = $newUsersPerMonth->map(function ($n) use (&$runningU) {
            $runningU += $n;
            return $runningU;
        })->values();

        $runningE = $baseEvents;
        $cumulativeEvents = $newEventsPerMonth->map(function ($n) use (&$runningE) {
            $runningE += $n;
            return $runningE;
        })->values();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'ticketsSold',
            'activeEvents',
            'pendingOrders',
            'recentTransactions',
            'totalOrganizers',
            'pendingOrganizers',
            'users',
            'chartLabels',
            'newUsersPerMonth',
            'newEventsPerMonth',
            'cumulativeUsers',
            'cumulativeEvents'
        ));
    }

    public function destroyUser(User $user)
    {
        if ($user->isPlatformStaff()) {
            return back()->with('error', 'Akun staf platform tidak dapat dihapus!');
        }
        $user->delete();
        return back()->with('success', 'Akun pengguna berhasil dihapus.');
    }
}