<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $organizerId = Auth::id();
        $paidStatuses = ['settlement', 'success'];
        $serviceFee = 5000;

        $eventIds = Event::where('organizer_id', $organizerId)->pluck('id');

        $totalRevenue = Transaction::whereIn('event_id', $eventIds)
            ->whereIn('status', $paidStatuses)->sum('total_price');
        $ticketsSold = Transaction::whereIn('event_id', $eventIds)
            ->whereIn('status', $paidStatuses)->count();
        $netRevenue = max($totalRevenue - ($serviceFee * $ticketsSold), 0);
        $avgTicketPrice = $ticketsSold > 0 ? (int) round($totalRevenue / $ticketsSold) : 0;

        $chartLabels = [];
        $revenuePerMonth = [];
        $ticketsPerMonth = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->startOfMonth()->subMonths($i);
            $chartLabels[] = $month->format('M Y');
            $base = Transaction::whereIn('event_id', $eventIds)
                ->whereIn('status', $paidStatuses)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month);
            $revenuePerMonth[] = (int) (clone $base)->sum('total_price');
            $ticketsPerMonth[] = (int) (clone $base)->count();
        }

        $revenuePerEvent = Event::where('organizer_id', $organizerId)
            ->withCount(['transactions as tickets_sold_count' => fn ($q) => $q->whereIn('status', $paidStatuses)])
            ->withCount(['transactions as pending_count' => fn ($q) => $q->whereIn('status', ['pending', 'Pending'])])
            ->withSum(['transactions as revenue_sum' => fn ($q) => $q->whereIn('status', $paidStatuses)], 'total_price')
            ->get()
            ->sortByDesc(fn ($e) => $e->revenue_sum ?? 0)
            ->values();

        $maxRevenue = (int) ($revenuePerEvent->max('revenue_sum') ?? 0);

        return view('organizer.reports.index', compact(
            'totalRevenue', 'ticketsSold', 'netRevenue', 'avgTicketPrice', 'serviceFee',
            'chartLabels', 'revenuePerMonth', 'ticketsPerMonth', 'revenuePerEvent', 'maxRevenue'
        ));
    }
}