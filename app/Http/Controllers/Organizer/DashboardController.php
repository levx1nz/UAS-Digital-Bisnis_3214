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
        $pendingStatuses = ['pending', 'Pending'];
        $serviceFee = 5000;

        $eventIds = Event::where('organizer_id', $organizerId)->pluck('id');

        $totalRevenue = Transaction::whereIn('event_id', $eventIds)
            ->whereIn('status', $paidStatuses)->sum('total_price');

        $ticketsSold = Transaction::whereIn('event_id', $eventIds)
            ->whereIn('status', $paidStatuses)->count();

        $totalEvents = $eventIds->count();

        $publishedEvents = Event::where('organizer_id', $organizerId)
            ->where('is_published', true)->count();

        $activeEvents = Event::where('organizer_id', $organizerId)
            ->where('date', '>=', now())->count();

        $pendingOrders = Transaction::whereIn('event_id', $eventIds)
            ->whereIn('status', $pendingStatuses)->count();

        $pendingRevenue = Transaction::whereIn('event_id', $eventIds)
            ->whereIn('status', $pendingStatuses)->sum('total_price');

        $totalCustomers = Transaction::whereIn('event_id', $eventIds)
            ->whereIn('status', $paidStatuses)
            ->distinct('customer_email')->count('customer_email');

        $avgTicketPrice = $ticketsSold > 0 ? (int) round($totalRevenue / $ticketsSold) : 0;
        $netRevenue = max($totalRevenue - ($serviceFee * $ticketsSold), 0);

        $chartLabels = [];
        $revenuePerMonth = [];
        $ticketsPerMonth = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->startOfMonth()->subMonths($i);
            $chartLabels[] = $month->format('M');
            $base = Transaction::whereIn('event_id', $eventIds)
                ->whereIn('status', $paidStatuses)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month);
            $revenuePerMonth[] = (int) (clone $base)->sum('total_price');
            $ticketsPerMonth[] = (int) (clone $base)->count();
        }

        $pct = function (array $series) {
            $n = count($series);
            if ($n < 2) {
                return 0.0;
            }
            $curr = $series[$n - 1];
            $prev = $series[$n - 2];
            if ($prev == 0) {
                return $curr > 0 ? 100.0 : 0.0;
            }
            return round((($curr - $prev) / $prev) * 100, 1);
        };
        $revenueChange = $pct($revenuePerMonth);
        $ticketsChange = $pct($ticketsPerMonth);

        $revenuePerEvent = Event::where('organizer_id', $organizerId)
            ->withCount(['transactions as tickets_sold_count' => fn ($q) => $q->whereIn('status', $paidStatuses)])
            ->withSum(['transactions as revenue_sum' => fn ($q) => $q->whereIn('status', $paidStatuses)], 'total_price')
            ->get()
            ->sortByDesc(fn ($e) => $e->revenue_sum ?? 0)
            ->take(8)
            ->values();

        $recentTransactions = Transaction::with('event')
            ->whereIn('event_id', $eventIds)->latest()->take(6)->get();

        $upcomingEvents = Event::where('organizer_id', $organizerId)
            ->where('date', '>=', now())
            ->withCount(['transactions as tickets_sold_count' => fn ($q) => $q->whereIn('status', $paidStatuses)])
            ->orderBy('date')
            ->take(5)->get();

        return view('organizer.dashboard', compact(
            'totalRevenue', 'ticketsSold', 'totalEvents', 'publishedEvents',
            'activeEvents', 'pendingOrders', 'pendingRevenue', 'totalCustomers',
            'avgTicketPrice', 'netRevenue', 'serviceFee',
            'chartLabels', 'revenuePerMonth', 'ticketsPerMonth',
            'revenueChange', 'ticketsChange',
            'revenuePerEvent', 'recentTransactions', 'upcomingEvents'
        ));
    }

    public function pending()
    {
        return view('organizer.pending');
    }
}