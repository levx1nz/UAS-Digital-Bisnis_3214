@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard Ringkasan')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                </path>
            </svg>
        </div>
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Total Pendapatan</p>
        <h3 class="text-2xl font-black">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
    </div>
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                </path>
            </svg>
        </div>
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Tiket Terjual</p>
        <h3 class="text-2xl font-black">{{ number_format($ticketsSold, 0, ',', '.') }}</h3>
    </div>
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Event Aktif</p>
        <h3 class="text-2xl font-black">{{ $activeEvents }} Event</h3>
    </div>
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Pesanan Pending</p>
        <h3 class="text-2xl font-black">{{ $pendingOrders }} Pesanan</h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h3 class="font-black text-xl text-slate-800">Pertumbuhan Pengguna</h3>
                    <p class="text-slate-400 text-sm mt-1">Pengguna baru per bulan &amp; total kumulatif (6 bulan terakhir)</p>
                </div>
                <span class="w-11 h-11 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4z"/></svg>
                </span>
            </div>
            <div class="h-72"><canvas id="userGrowthChart"></canvas></div>
        </div>

        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h3 class="font-black text-xl text-slate-800">Pertumbuhan Penyelenggaraan Event</h3>
                    <p class="text-slate-400 text-sm mt-1">Event baru per bulan &amp; total kumulatif (6 bulan terakhir)</p>
                </div>
                <span class="w-11 h-11 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </span>
            </div>
            <div class="h-72"><canvas id="eventGrowthChart"></canvas></div>
        </div>
</div>

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-8 border-b flex justify-between items-center">
        <h3 class="font-black text-xl">Transaksi Terakhir</h3>
        <a href="{{ route('admin.transactions.index') }}" class="text-indigo-600 font-bold hover:underline">Lihat Semua</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4 w-1/4">Tgl Transaksi</th>
                    <th class="px-8 py-4 w-1/4">Pembeli</th>
                    <th class="px-8 py-4 w-1/4">Event</th>
                    <th class="px-8 py-4 w-[10%]">Status</th>
                    <th class="px-8 py-4 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                @forelse($recentTransactions as $trx)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-6 text-sm text-slate-600 max-w-xs break-all">{{ $trx->created_at->format('d M y - H:i') }}<br><span class="text-xs text-slate-400">{{ $trx->order_id }}</span></td>
                    <td class="px-8 py-6">
                        <p class="font-bold uppercase tracking-wide text-sm truncate max-w-[150px]">{{ $trx->customer_name }}</p>
                        <p class="text-xs text-slate-400 truncate max-w-[150px]">{{ $trx->customer_email }}</p>
                    </td>
                    <td class="px-8 py-6 font-medium text-slate-600 max-w-xs truncate">{{ $trx->event->title ?? '-' }}</td>
                    <td class="px-8 py-6 whitespace-nowrap">
                        @if($trx->status === 'settlement' || $trx->status === 'success')
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold uppercase">Success</span>
                        @elseif($trx->status === 'pending')
                        <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-lg text-xs font-bold uppercase">Pending</span>
                        @else
                        <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-lg text-xs font-bold uppercase">{{ $trx->status }}</span>
                        @endif
                    </td>
                    <td class="px-8 py-6 font-black text-indigo-600 whitespace-nowrap text-right">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-10 text-center text-slate-500">Belum ada transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden mt-8">
    <div class="p-8 border-b">
        <h3 class="font-black text-xl">Daftar Pengguna Terdaftar</h3>
    </div>
    
    @if(session('error'))
    <div class="mx-8 mt-6 bg-red-100 text-red-700 p-4 rounded-xl font-bold text-sm">
        {{ session('error') }}
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse mt-4">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4">Nama Lengkap</th>
                    <th class="px-8 py-4">Email</th>
                    <th class="px-8 py-4">Role</th>
                    <th class="px-8 py-4">Tanggal Mendaftar</th>
                    <th class="px-8 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                @foreach($users as $user)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-6 font-bold text-slate-800">{{ $user->name }}</td>
                    <td class="px-8 py-6 text-slate-500">{{ $user->email }}</td>
                    <td class="px-8 py-6">
                        @if($user->role === 'superadmin')
                            <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-lg text-xs font-bold uppercase">Superadmin</span>
                        @elseif($user->role === 'admin')
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-xs font-bold uppercase">Admin</span>
                        @elseif($user->role === 'organizer')
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold uppercase">Organizer</span>
                        @else
                            <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold uppercase">User</span>
                        @endif
                    </td>
                    <td class="px-8 py-6 text-sm text-slate-500">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-8 py-6 text-right">
                        @if(!$user->isPlatformStaff())
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-600 hover:text-white hover:bg-rose-600 px-3 py-1 rounded-lg font-bold text-sm transition">
                                Hapus Akun
                            </button>
                        </form>
                        @else
                        <span class="text-slate-300 font-bold text-sm cursor-not-allowed">Hapus Terkunci</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden mt-8 mb-10">
    <div class="p-8 border-b">
        <h3 class="font-black text-xl">Ulasan Terbaru (Real-time)</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4">Event</th>
                    <th class="px-8 py-4">Pengguna</th>
                    <th class="px-8 py-4">Rating</th>
                    <th class="px-8 py-4">Komentar</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                @forelse(\App\Models\Review::with(['event', 'user'])->latest()->take(5)->get() as $review)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-6 font-bold text-slate-700">
                        {{ Str::limit($review->event->title, 30) }}
                    </td>
                    <td class="px-8 py-6 text-sm font-medium text-slate-600">
                        {{ $review->user->name }}
                        <div class="text-xs text-slate-400 mt-1">{{ $review->created_at->diffForHumans() }}</div>
                    </td>
                    <td class="px-8 py-6">
                        <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-lg text-xs font-black flex items-center w-max gap-1">
                            {{ $review->rating }}
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </span>
                    </td>
                    <td class="px-8 py-6 text-sm text-slate-500 italic max-w-sm truncate">
                        "{{ $review->comment }}"
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-10 text-center text-slate-400 font-bold">
                        Belum ada data ulasan yang masuk.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
    const growthLabels = @json($chartLabels);

    const growthCommonOpts = {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { labels: { usePointStyle: true, boxWidth: 8, font: { family: 'Plus Jakarta Sans', weight: '600' } } },
            tooltip: { padding: 12, cornerRadius: 12, titleFont: { family: 'Plus Jakarta Sans' }, bodyFont: { family: 'Plus Jakarta Sans' } }
        },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0, font: { family: 'Plus Jakarta Sans' } }, grid: { color: '#f1f5f9' } },
            x: { ticks: { font: { family: 'Plus Jakarta Sans' } }, grid: { display: false } }
        }
    };

    new Chart(document.getElementById('userGrowthChart'), {
        data: {
            labels: growthLabels,
            datasets: [
                { type: 'bar', label: 'Pengguna Baru', data: @json($newUsersPerMonth), backgroundColor: '#c7d2fe', borderRadius: 8, maxBarThickness: 34, order: 2 },
                { type: 'line', label: 'Total Kumulatif', data: @json($cumulativeUsers), borderColor: '#4f46e5', backgroundColor: '#4f46e5', borderWidth: 3, tension: 0.4, fill: false, pointRadius: 4, pointHoverRadius: 6, order: 1 }
            ]
        },
        options: growthCommonOpts
    });

    new Chart(document.getElementById('eventGrowthChart'), {
        data: {
            labels: growthLabels,
            datasets: [
                { type: 'bar', label: 'Event Baru', data: @json($newEventsPerMonth), backgroundColor: '#a7f3d0', borderRadius: 8, maxBarThickness: 34, order: 2 },
                { type: 'line', label: 'Total Kumulatif', data: @json($cumulativeEvents), borderColor: '#059669', backgroundColor: '#059669', borderWidth: 3, tension: 0.4, fill: false, pointRadius: 4, pointHoverRadius: 6, order: 1 }
            ]
        },
        options: growthCommonOpts
    });
</script>

@endsection