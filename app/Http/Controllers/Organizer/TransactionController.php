<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $paidStatuses = ['settlement', 'success'];
        $pendingStatuses = ['pending', 'Pending'];

        $eventIds = Event::where('organizer_id', Auth::id())->pluck('id');

        $query = Transaction::with('event')->whereIn('event_id', $eventIds);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'LIKE', '%' . $search . '%')
                    ->orWhere('customer_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('customer_email', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('event', fn ($e) => $e->where('title', 'LIKE', '%' . $search . '%'));
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'success') {
                $query->whereIn('status', $paidStatuses);
            } elseif ($request->status === 'pending') {
                $query->whereIn('status', $pendingStatuses);
            } elseif ($request->status === 'failed') {
                $query->whereNotIn('status', array_merge($paidStatuses, $pendingStatuses));
            }
        }

        $baseAll = Transaction::whereIn('event_id', $eventIds);
        $sumRevenue = (clone $baseAll)->whereIn('status', $paidStatuses)->sum('total_price');
        $countSuccess = (clone $baseAll)->whereIn('status', $paidStatuses)->count();
        $countPending = (clone $baseAll)->whereIn('status', $pendingStatuses)->count();
        $countTotal = (clone $baseAll)->count();

        $transactions = $query->latest()->paginate(20)->withQueryString();

        return view('organizer.transactions.index', compact(
            'transactions', 'sumRevenue', 'countSuccess', 'countPending', 'countTotal'
        ));
    }
}