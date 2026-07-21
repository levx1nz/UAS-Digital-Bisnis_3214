<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function show(\App\Models\Event $event)
    {
        $user = Auth::user();
        $isOwner = $user && $event->organizer_id && $event->organizer_id === $user->id;
        $isStaff = $user && $user->isPlatformStaff();

        if (!$isOwner && !$isStaff) {
            $visible = Event::publiclyVisible()->whereKey($event->id)->exists();
            abort_unless($visible, 404);
        }

        $categories = \App\Models\Category::all();
        return view('event-detail', compact('categories', 'event'));
    }

    public function checkout()
    {
        return view('checkout');
    }

    public function ticket(\App\Models\Transaction $transaction)
    {
        $transaction->load('event');
        
        return view('ticket', compact('transaction'));
    }

    public function myTickets()
    {
        $transactions = \App\Models\Transaction::where('customer_email', \Illuminate\Support\Facades\Auth::user()->email)
            ->whereIn('status', ['success', 'settlement'])
            ->latest()
            ->get();

        return view('my-tickets', compact('transactions'));
    }
}