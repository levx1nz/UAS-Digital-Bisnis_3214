<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $organizerId = Auth::id();
        $paidStatuses = ['settlement', 'success'];

        $eventIds = Event::where('organizer_id', $organizerId)->pluck('id');

        $totalRevenue = Transaction::whereIn('event_id', $eventIds)
            ->whereIn('status', $paidStatuses)->sum('total_price');

        $ticketsSold = Transaction::whereIn('event_id', $eventIds)
            ->whereIn('status', $paidStatuses)->count();

        $totalEvents = $eventIds->count();

        $activeEvents = Event::where('organizer_id', $organizerId)
            ->where('date', '>=', now())->count();

        $pendingOrders = Transaction::whereIn('event_id', $eventIds)
            ->whereIn('status', ['pending', 'Pending'])->count();

        $recentTransactions = Transaction::with('event')
            ->whereIn('event_id', $eventIds)->latest()->take(5)->get();

        $revenuePerEvent = Event::where('organizer_id', $organizerId)
            ->withCount(['transactions as tickets_sold_count' => fn($q) => $q->whereIn('status', $paidStatuses)])
            ->withSum(['transactions as revenue_sum' => fn($q) => $q->whereIn('status', $paidStatuses)], 'total_price')
            ->latest()->take(10)->get();

        return view('organizer.dashboard', compact(
            'totalRevenue', 'ticketsSold', 'totalEvents',
            'activeEvents', 'pendingOrders', 'recentTransactions', 'revenuePerEvent'
        ));
    }

    public function pending()
    {
        return view('organizer.pending');
    }
}