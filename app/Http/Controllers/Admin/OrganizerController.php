<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class OrganizerController extends Controller
{
    public function index()
    {
        $organizers = User::where('role', 'organizer')
            ->withCount('events')->latest()->get();

        $pendingCount  = $organizers->where('account_status', 'pending')->count();
        $approvedCount = $organizers->where('account_status', 'approved')->count();
        $rejectedCount = $organizers->where('account_status', 'rejected')->count();

        return view('admin.organizers.index', compact('organizers', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    public function show(User $organizer)
    {
        abort_unless($organizer->role === 'organizer', 404);

        $paidStatuses = ['settlement', 'success'];
        $eventIds = $organizer->events()->pluck('id');

        $totalRevenue = \App\Models\Transaction::whereIn('event_id', $eventIds)
            ->whereIn('status', $paidStatuses)->sum('total_price');
        $ticketsSold = \App\Models\Transaction::whereIn('event_id', $eventIds)
            ->whereIn('status', $paidStatuses)->count();
        $events = $organizer->events()->with('category')->latest()->get();

        return view('admin.organizers.show', compact('organizer', 'totalRevenue', 'ticketsSold', 'events'));
    }

    public function approve(User $organizer)
    {
        abort_unless($organizer->role === 'organizer', 404);
        $organizer->update(['account_status' => 'approved']);
        return back()->with('success', 'Penyelenggara "' . ($organizer->organizer_name ?? $organizer->name) . '" disetujui.');
    }

    public function reject(User $organizer)
    {
        abort_unless($organizer->role === 'organizer', 404);
        $organizer->update(['account_status' => 'rejected']);
        return back()->with('success', 'Penyelenggara "' . ($organizer->organizer_name ?? $organizer->name) . '" ditolak.');
    }
}