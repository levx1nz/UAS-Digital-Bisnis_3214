@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Ringkasan performa platform AmikomEventHub')

@section('content')
@php
    $palette = ['#6366f1', '#22d3ee', '#10b981', '#ec4899', '#f59e0b', '#8b5cf6', '#f43f5e', '#0ea5e9'];
    $catTotal = $categoryDistribution->sum('count');
    $catColors = $categoryDistribution->keys()->map(fn ($i) => $palette[$i % count($palette)])->values();

    $cards = [
        ['label' => 'Total Pendapatan', 'value' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'), 'change' => $revenueChange, 'spark' => 'spark1', 'iconBg' => 'bg-emerald-50 text-emerald-600', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label' => 'Tiket Terjual', 'value' => number_format($ticketsSold, 0, ',', '.'), 'change' => $ordersChange, 'spark' => 'spark2', 'iconBg' => 'bg-sky-50 text-sky-600', 'icon' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z'],
        ['label' => 'Total Pengguna', 'value' => number_format($totalUsers, 0, ',', '.'), 'change' => $usersChange, 'spark' => 'spark3', 'iconBg' => 'bg-purple-50 text-purple-600', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
        ['label' => 'Event Aktif', 'value' => number_format($activeEvents, 0, ',', '.'), 'change' => $eventsChange, 'spark' => 'spark4', 'iconBg' => 'bg-amber-50 text-amber-600', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
    ];

    $activityStyles = [
        'order'     => ['ring' => 'bg-emerald-50 text-emerald-600', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
        'user'      => ['ring' => 'bg-sky-50 text-sky-600', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        'organizer' => ['ring' => 'bg-purple-50 text-purple-600', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
        'review'    => ['ring' => 'bg-amber-50 text-amber-600', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
    ];
@endphp

<div class="space-y-6">

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        @foreach($cards as $card)
        @php $up = $card['change'] >= 0; @endphp
        <div class="bg-white border border-slate-100 shadow-sm rounded-2xl p-5 flex flex-col">
            <div class="flex items-center justify-between mb-3">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wide">{{ $card['label'] }}</p>
                <span class="w-9 h-9 rounded-xl flex items-center justify-center {{ $card['iconBg'] }}">
                    <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
                </span>
            </div>
            <h3 class="text-2xl font-black text-slate-900 leading-tight truncate">{{ $card['value'] }}</h3>
            <div class="flex items-center gap-1.5 mt-2 text-xs">
                <span class="inline-flex items-center gap-0.5 font-bold {{ $up ? 'text-emerald-600' : 'text-rose-600' }}">
                    <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $up ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/></svg>
                    {{ $up ? '+' : '' }}{{ $card['change'] }}%
                </span>
                <span class="text-slate-400">vs bulan lalu</span>
            </div>
            <div class="h-12 mt-3 -mx-1"><canvas id="{{ $card['spark'] }}"></canvas></div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white border border-slate-100 shadow-sm rounded-2xl p-6">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h3 class="text-slate-900 font-bold text-lg">Ringkasan</h3>
                    <p class="text-slate-400 text-sm">Performa 6 bulan terakhir</p>
                </div>
                <div class="flex bg-slate-100 rounded-xl p-1 text-xs font-bold">
                    <button type="button" onclick="switchOverview('pendapatan', this)" class="ov-tab px-3 py-1.5 rounded-lg bg-indigo-600 text-white transition">Pendapatan</button>
                    <button type="button" onclick="switchOverview('pesanan', this)" class="ov-tab px-3 py-1.5 rounded-lg text-slate-500 transition">Pesanan</button>
                    <button type="button" onclick="switchOverview('profit', this)" class="ov-tab px-3 py-1.5 rounded-lg text-slate-500 transition">Profit</button>
                </div>
            </div>
            <div class="h-72"><canvas id="overviewChart"></canvas></div>
        </div>

        <div class="space-y-6">
            <div class="bg-white border border-slate-100 shadow-sm rounded-2xl p-6">
                <h3 class="text-slate-900 font-bold text-lg mb-1">Distribusi Kategori</h3>
                <p class="text-slate-400 text-sm mb-4">Sebaran event per kategori</p>
                @if($catTotal > 0)
                <div class="relative h-40 mb-4">
                    <canvas id="categoryChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-2xl font-black text-slate-900">{{ $catTotal }}</span>
                        <span class="text-[10px] uppercase tracking-widest text-slate-400">Event</span>
                    </div>
                </div>
                <div class="space-y-2">
                    @foreach($categoryDistribution as $i => $cat)
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full" style="background: {{ $palette[$i % count($palette)] }}"></span>
                            <span class="text-slate-600">{{ $cat['name'] }}</span>
                        </div>
                        <span class="text-slate-500 font-bold">{{ round($cat['count'] / $catTotal * 100) }}%</span>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-slate-400 text-sm py-10 text-center">Belum ada data event.</p>
                @endif
            </div>

            <div class="bg-white border border-slate-100 shadow-sm rounded-2xl p-6">
                <h3 class="text-slate-900 font-bold text-lg mb-1">Target &amp; Rasio</h3>
                <p class="text-slate-400 text-sm mb-5">Indikator kesehatan platform</p>
                <div class="space-y-5">
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-slate-700 font-medium">Transaksi Sukses</span>
                            <span class="text-slate-900 font-bold">{{ $successRate }}%</span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-emerald-400" style="width: {{ $successRate }}%"></div>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">{{ number_format($ticketsSold, 0, ',', '.') }} transaksi berhasil</p>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-slate-700 font-medium">Event Terpublikasi</span>
                            <span class="text-slate-900 font-bold">{{ $publishRate }}%</span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-sky-500 to-cyan-400" style="width: {{ $publishRate }}%"></div>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">{{ $publishedEvents }} dari {{ $totalEvents }} event</p>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-slate-700 font-medium">Penyelenggara Disetujui</span>
                            <span class="text-slate-900 font-bold">{{ $approvedRate }}%</span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-purple-500 to-fuchsia-400" style="width: {{ $approvedRate }}%"></div>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">{{ $approvedOrganizers }} dari {{ $totalOrganizers }} penyelenggara</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Transaksi terbaru --}}
        <div class="lg:col-span-2 bg-white border border-slate-100 shadow-sm rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between p-6 pb-4">
                <div>
                    <h3 class="text-slate-900 font-bold text-lg">Transaksi Terbaru</h3>
                    <p class="text-slate-400 text-sm">Pesanan terakhir dari pembeli</p>
                </div>
                <a href="{{ route('admin.transactions.index') }}" class="text-indigo-600 text-sm font-bold hover:text-indigo-700 inline-flex items-center gap-1">
                    Lihat semua
                    <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-slate-400 text-[10px] uppercase tracking-widest border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-3 font-bold">Pembeli</th>
                            <th class="px-6 py-3 font-bold">Order ID</th>
                            <th class="px-6 py-3 font-bold">Event</th>
                            <th class="px-6 py-3 font-bold">Status</th>
                            <th class="px-6 py-3 font-bold text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentTransactions as $trx)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 text-white text-xs font-bold flex items-center justify-center shrink-0">{{ strtoupper(Str::substr($trx->customer_name ?: 'NA', 0, 2)) }}</span>
                                    <div class="min-w-0">
                                        <p class="text-slate-800 font-semibold truncate max-w-[140px]">{{ $trx->customer_name ?: '—' }}</p>
                                        <p class="text-slate-400 text-xs truncate max-w-[140px]">{{ $trx->customer_email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-500 font-mono text-xs">{{ $trx->order_id }}</td>
                            <td class="px-6 py-4 text-slate-600 max-w-[160px] truncate">{{ $trx->event->title ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if(in_array($trx->status, ['settlement', 'success']))
                                <span class="px-2.5 py-1 rounded-md text-[11px] uppercase font-bold bg-emerald-100 text-emerald-700">SUCCESS</span>
                                @elseif($trx->status === 'pending')
                                <span class="px-2.5 py-1 rounded-md text-[11px] uppercase font-bold bg-amber-100 text-amber-700">PENDING</span>
                                @else
                                <span class="px-2.5 py-1 rounded-md text-[11px] uppercase font-bold bg-rose-100 text-rose-700">{{ ucfirst($trx->status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-slate-900 whitespace-nowrap">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-slate-400">Belum ada transaksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white border border-slate-100 shadow-sm rounded-2xl p-6">
            <h3 class="text-slate-900 font-bold text-lg mb-1">Aktivitas Terbaru</h3>
            <p class="text-slate-400 text-sm mb-5">Kejadian terakhir di platform</p>
            <div class="space-y-5">
                @forelse($activities as $act)
                @php $st = $activityStyles[$act['type']] ?? $activityStyles['user']; @endphp
                <div class="flex gap-3">
                    <span class="w-9 h-9 shrink-0 rounded-xl flex items-center justify-center {{ $st['ring'] }}">
                        <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $st['icon'] }}"/></svg>
                    </span>
                    <div class="min-w-0">
                        <p class="text-slate-800 text-sm font-semibold">{{ $act['title'] }}</p>
                        <p class="text-slate-500 text-xs truncate">{{ $act['desc'] }}</p>
                        <p class="text-slate-400 text-[11px] mt-0.5">{{ $act['time']->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-slate-400 text-sm text-center py-6">Belum ada aktivitas.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
(function () {
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#94a3b8';

    const sparks = [
        { id: 'spark1', data: @json($revenuePerMonth), color: '#10b981' },
        { id: 'spark2', data: @json($ordersPerMonth), color: '#0ea5e9' },
        { id: 'spark3', data: @json($usersPerMonth), color: '#8b5cf6' },
        { id: 'spark4', data: @json($eventsPerMonth), color: '#f59e0b' },
    ];
    sparks.forEach(function (s) {
        const el = document.getElementById(s.id);
        if (!el) return;
        const ctx = el.getContext('2d');
        const g = ctx.createLinearGradient(0, 0, 0, 48);
        g.addColorStop(0, s.color + '44');
        g.addColorStop(1, s.color + '00');
        new Chart(el, {
            type: 'line',
            data: { labels: s.data.map(function (_, i) { return i; }), datasets: [{ data: s.data, borderColor: s.color, backgroundColor: g, borderWidth: 2, fill: true, tension: 0.4, pointRadius: 0 }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { enabled: false } }, scales: { x: { display: false }, y: { display: false } } }
        });
    });

    const overviewSeries = {
        pendapatan: { data: @json($revenuePerMonth), color: '#10b981', label: 'Pendapatan (Rp)' },
        pesanan:    { data: @json($ordersPerMonth), color: '#0ea5e9', label: 'Pesanan' },
        profit:     { data: @json($profitPerMonth), color: '#8b5cf6', label: 'Profit (Rp)' },
    };
    const ovCtx = document.getElementById('overviewChart').getContext('2d');
    function gradientFor(color) {
        const g = ovCtx.createLinearGradient(0, 0, 0, 288);
        g.addColorStop(0, color + '33');
        g.addColorStop(1, color + '00');
        return g;
    }
    const overviewChart = new Chart(ovCtx, {
        type: 'line',
        data: { labels: @json($chartLabels), datasets: [{ label: overviewSeries.pendapatan.label, data: overviewSeries.pendapatan.data, borderColor: overviewSeries.pendapatan.color, backgroundColor: gradientFor(overviewSeries.pendapatan.color), borderWidth: 3, fill: true, tension: 0.4, pointRadius: 3, pointBackgroundColor: overviewSeries.pendapatan.color }] },
        options: {
            responsive: true, maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { display: false }, tooltip: { padding: 12, cornerRadius: 12 } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false } }
            }
        }
    });
    window.switchOverview = function (key, btn) {
        const s = overviewSeries[key];
        overviewChart.data.datasets[0].data = s.data;
        overviewChart.data.datasets[0].label = s.label;
        overviewChart.data.datasets[0].borderColor = s.color;
        overviewChart.data.datasets[0].backgroundColor = gradientFor(s.color);
        overviewChart.data.datasets[0].pointBackgroundColor = s.color;
        overviewChart.update();
        document.querySelectorAll('.ov-tab').forEach(function (b) { b.classList.remove('bg-indigo-600', 'text-white'); b.classList.add('text-slate-500'); });
        btn.classList.add('bg-indigo-600', 'text-white'); btn.classList.remove('text-slate-500');
    };

    @if($catTotal > 0)
    const catEl = document.getElementById('categoryChart');
    if (catEl) {
        new Chart(catEl, {
            type: 'doughnut',
            data: { labels: @json($categoryDistribution->pluck('name')), datasets: [{ data: @json($categoryDistribution->pluck('count')), backgroundColor: @json($catColors), borderWidth: 0, cutout: '72%' }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { padding: 10, cornerRadius: 10 } } }
        });
    }
    @endif
})();
</script>
@endsection