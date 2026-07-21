<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $paidStatuses = ['settlement', 'success'];
        $serviceFee = 5000;

        $totalRevenue  = Transaction::whereIn('status', $paidStatuses)->sum('total_price');
        $ticketsSold   = Transaction::whereIn('status', $paidStatuses)->count();
        $totalUsers    = User::count();
        $activeEvents  = Event::where('date', '>=', now())->count();
        $pendingOrders = Transaction::whereIn('status', ['pending', 'Pending'])->count();

        $totalOrganizers   = User::where('role', 'organizer')->count();
        $pendingOrganizers = User::where('role', 'organizer')->where('account_status', 'pending')->count();

        $recentTransactions = Transaction::with('event')->latest()->take(6)->get();

        $months      = collect(range(5, 0))->map(fn ($i) => now()->startOfMonth()->subMonths($i));
        $chartLabels = $months->map(fn ($m) => $m->format('M'))->values();
        $startWindow = now()->startOfMonth()->subMonths(5);

        $paidTrx = Transaction::whereIn('status', $paidStatuses)
            ->where('created_at', '>=', $startWindow)
            ->get(['total_price', 'created_at']);

        $revenuePerMonth = $months->map(
            fn ($m) => (int) $paidTrx->filter(fn ($t) => $t->created_at->isSameMonth($m))->sum('total_price')
        )->values();
        $ordersPerMonth = $months->map(
            fn ($m) => $paidTrx->filter(fn ($t) => $t->created_at->isSameMonth($m))->count()
        )->values();
        $profitPerMonth = $ordersPerMonth->map(fn ($n) => $n * $serviceFee)->values();

        $recentUsers  = User::where('created_at', '>=', $startWindow)->get(['created_at']);
        $recentEvents = Event::where('created_at', '>=', $startWindow)->get(['created_at']);
        $usersPerMonth  = $months->map(fn ($m) => $recentUsers->filter(fn ($u) => $u->created_at->isSameMonth($m))->count())->values();
        $eventsPerMonth = $months->map(fn ($m) => $recentEvents->filter(fn ($e) => $e->created_at->isSameMonth($m))->count())->values();

        $pct = function ($series) {
            $c = $series->count();
            if ($c < 2) {
                return 0.0;
            }
            $curr = (float) $series[$c - 1];
            $prev = (float) $series[$c - 2];
            if ($prev == 0.0) {
                return $curr > 0 ? 100.0 : 0.0;
            }
            return round((($curr - $prev) / $prev) * 100, 1);
        };
        $revenueChange = $pct($revenuePerMonth);
        $ordersChange  = $pct($ordersPerMonth);
        $usersChange   = $pct($usersPerMonth);
        $eventsChange  = $pct($eventsPerMonth);

        $categoryDistribution = Category::withCount('events')->get()
            ->filter(fn ($c) => $c->events_count > 0)
            ->map(fn ($c) => ['name' => $c->name, 'count' => $c->events_count])
            ->values();

        $totalTransactions = Transaction::count();
        $successRate = $totalTransactions > 0 ? (int) round(($ticketsSold / $totalTransactions) * 100) : 0;

        $totalEvents     = Event::count();
        $publishedEvents = Event::where('is_published', true)->count();
        $publishRate     = $totalEvents > 0 ? (int) round(($publishedEvents / $totalEvents) * 100) : 0;

        $approvedOrganizers = User::where('role', 'organizer')->where('account_status', 'approved')->count();
        $approvedRate       = $totalOrganizers > 0 ? (int) round(($approvedOrganizers / $totalOrganizers) * 100) : 0;

        $activities = collect();
        foreach (Transaction::with('event')->latest()->take(5)->get() as $t) {
            $activities->push([
                'type'  => 'order',
                'title' => 'Transaksi baru',
                'desc'  => ($t->customer_name ?: 'Seseorang') . ' memesan ' . ($t->event->title ?? 'event'),
                'time'  => $t->created_at,
            ]);
        }
        foreach (User::latest()->take(5)->get() as $u) {
            $isOrg = $u->role === 'organizer';
            $activities->push([
                'type'  => $isOrg ? 'organizer' : 'user',
                'title' => $isOrg ? 'Penyelenggara baru' : 'Pengguna baru terdaftar',
                'desc'  => ($u->organizer_name ?: $u->name) . ' membuat akun',
                'time'  => $u->created_at,
            ]);
        }
        foreach (Review::with(['user', 'event'])->latest()->take(5)->get() as $r) {
            $activities->push([
                'type'  => 'review',
                'title' => 'Ulasan ' . $r->rating . ' bintang diterima',
                'desc'  => ($r->user->name ?? 'Pengguna') . ': "' . Str::limit($r->comment, 40) . '"',
                'time'  => $r->created_at,
            ]);
        }
        $activities = $activities->sortByDesc('time')->take(7)->values();

        return view('admin.dashboard', compact(
            'totalRevenue', 'ticketsSold', 'totalUsers', 'activeEvents', 'pendingOrders',
            'totalOrganizers', 'pendingOrganizers', 'recentTransactions',
            'chartLabels', 'revenuePerMonth', 'ordersPerMonth', 'profitPerMonth',
            'usersPerMonth', 'eventsPerMonth',
            'revenueChange', 'ordersChange', 'usersChange', 'eventsChange',
            'categoryDistribution', 'successRate', 'publishRate', 'approvedRate',
            'publishedEvents', 'totalEvents', 'approvedOrganizers',
            'activities'
        ));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }

        $users = $query->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function reviews(Request $request)
    {
        $query = \App\Models\Review::with(['event', 'user']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('event', function ($e) use ($search) {
                        $e->where('title', 'LIKE', '%' . $search . '%');
                    });
            });
        }

        $reviews = $query->latest()->get();
        return view('admin.reviews.index', compact('reviews'));
    }

    public function destroyReview(\App\Models\Review $review)
    {
        $review->delete();
        return back()->with('success', 'Ulasan berhasil dihapus.');
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