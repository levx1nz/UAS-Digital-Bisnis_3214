<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('event');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'LIKE', '%' . $search . '%')
                    ->orWhere('customer_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('customer_email', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('event', function ($e) use ($search) {
                        $e->where('title', 'LIKE', '%' . $search . '%');
                    });
            });
        }

        $transactions = $query->latest()->paginate(20)->withQueryString();

        return view('admin.transactions.index', compact('transactions'));
    }
}