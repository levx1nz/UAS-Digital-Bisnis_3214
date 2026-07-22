@extends('layouts.organizer')
@section('page_title', 'Dashboard Ringkasan')
@section('page_subtitle', 'Analitik pendapatan & tiket khusus event Anda.')

@section('content')
@php
    $statCards = [
        [
            'label' => 'Total Pendapatan', 'value' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),
            'change' => $revenueChange, 'spark' => 'spark1', 'color' => '#10b981',
            'iconBg' => 'bg-emerald-50 text-emerald-600',
            'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        [
            'label' => 'Tiket Terjual', 'value' => number_format($ticketsSold, 0, ',', '.'),
            'change' => $ticketsChange, 'spark' => 'spark2', 'color' => '#6366f1',
            'iconBg' => 'bg-indigo-50 text-indigo-600',
            'icon' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z',
        ],
        [
            'label' => 'Total Pembeli', 'value' => number_format($totalCustomers, 0, ',', '.'),
            'change' => null, 'spark' => 'spark3', 'color' => '#0ea5e9',
            'iconBg' => 'bg-sky-50 text-sky-600',
            'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
        ],
        [
            'label' => 'Pesanan Pending', 'value' => number_format($pendingOrders, 0, ',', '.'),
            'change' => null, 'spark' => 'spark4', 'color' => '#f59e0b',
            'iconBg' => 'bg-amber-50 text-amber-600',
            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
    ];
@endphp

<div class="space-y-8">

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
        @foreach($statCards as $c)
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col">
            <div class="flex items-center justify-between mb-3">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">{{ $c['label'] }}</p>
                <span class="w-10 h-10 rounded-2xl flex items-center justify-center {{ $c['iconBg'] }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $c['icon'] }}"/></svg>
                </span>
            </div>
            <h3 class="text-2xl font-black text-slate-900 leading-tight truncate">{{ $c['value'] }}</h3>
            @if(!is_null($c['change']))
            @php $up = $c['change'] >= 0; @endphp
            <div class="flex items-center gap-1.5 mt-2 text-xs">
                <span class="inline-flex items-center gap-0.5 font-bold {{ $up ? 'text-emerald-600' : 'text-rose-600' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $up ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/></svg>
                    {{ $up ? '+' : '' }}{{ $c['change'] }}%
                </span>
                <span class="text-slate-400">vs bulan lalu</span>
            </div>
            @else
            <p class="text-slate-400 text-xs mt-2">Total keseluruhan</p>
            @endif
            <div class="h-10 mt-3 -mx-1"><canvas id="{{ $c['spark'] }}"></canvas></div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h3 class="font-black text-xl text-slate-900">Tren 6 Bulan</h3>
                    <p class="text-slate-400 text-sm">Pendapatan &amp; tiket terjual dari event Anda</p>
                </div>
                <div class="flex bg-slate-100 rounded-xl p-1 text-xs font-bold">
                    <button type="button" onclick="switchTrend('pendapatan', this)" class="trend-tab px-3 py-1.5 rounded-lg bg-indigo-600 text-white transition">Pendapatan</button>
                    <button type="button" onclick="switchTrend('tiket', this)" class="trend-tab px-3 py-1.5 rounded-lg text-slate-500 transition">Tiket</button>
                </div>
            </div>
            <div class="h-72"><canvas id="trendChart"></canvas></div>
        </div>

        <div class="space-y-4">
            <div class="bg-gradient-to-br from-indigo-600 to-purple-600 text-white p-6 rounded-3xl shadow-sm">
                <p class="text-indigo-100 text-xs font-bold uppercase tracking-widest mb-1">Estimasi Pendapatan Bersih</p>
                <h3 class="text-2xl font-black">Rp {{ number_format($netRevenue, 0, ',', '.') }}</h3>
                <p class="text-indigo-200 text-[11px] mt-1">Setelah biaya layanan Rp {{ number_format($serviceFee, 0, ',', '.') }}/tiket</p>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Rata-rata Harga Tiket</p>
                <h3 class="text-xl font-black text-slate-900">Rp {{ number_format($avgTicketPrice, 0, ',', '.') }}</h3>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm">
                    <p class="text-slate-400 text-[11px] font-bold uppercase tracking-widest mb-1">Event Aktif</p>
                    <h3 class="text-lg font-black text-slate-900">{{ $activeEvents }}<span class="text-slate-400 text-sm font-bold">/{{ $totalEvents }}</span></h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $publishedEvents }} terbit</p>
                </div>
                <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm">
                    <p class="text-slate-400 text-[11px] font-bold uppercase tracking-widest mb-1">Dana Pending</p>
                    <h3 class="text-lg font-black text-amber-600">Rp {{ number_format($pendingRevenue, 0, ',', '.') }}</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $pendingOrders }} pesanan</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-8 pb-5 flex justify-between items-center">
                <div>
                    <h3 class="font-black text-xl text-slate-900">Pendapatan per Event</h3>
                    <p class="text-slate-400 text-sm">Diurutkan dari event terlaris</p>
                </div>
                <a href="{{ route('organizer.events.index') }}" class="text-indigo-600 font-bold hover:underline text-sm">Kelola</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                        <tr>
                            <th class="px-8 py-4">Event</th>
                            <th class="px-8 py-4 text-center">Terjual</th>
                            <th class="px-8 py-4 text-right">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y border-t">
                        @forelse($revenuePerEvent as $ev)
                        @php
                            $stock = (int) ($ev->stock ?? 0);
                            $sold = (int) $ev->tickets_sold_count;
                            $ratio = $stock > 0 ? min(100, round($sold / $stock * 100)) : 0;
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-8 py-5 max-w-xs">
                                <p class="font-bold text-slate-700 truncate">{{ $ev->title }}</p>
                                <div class="flex items-center gap-2 mt-1.5">
                                    <div class="h-1.5 w-24 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full bg-indigo-500" style="width: {{ $ratio }}%"></div>
                                    </div>
                                    <span class="text-[11px] text-slate-400 font-medium">{{ $stock > 0 ? $ratio . '%' : '-' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-center text-slate-600 font-medium">{{ $sold }}{{ $stock > 0 ? ' / ' . $stock : '' }}</td>
                            <td class="px-8 py-5 text-right font-black text-indigo-600 whitespace-nowrap">Rp {{ number_format($ev->revenue_sum ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-8 py-10 text-center text-slate-400 font-bold">Belum ada event. <a href="{{ route('organizer.events.create') }}" class="text-indigo-600 underline">Buat sekarang</a></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-8 pb-5">
                <h3 class="font-black text-xl text-slate-900">Transaksi Terakhir</h3>
                <p class="text-slate-400 text-sm">Pesanan terbaru untuk event Anda</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                        <tr>
                            <th class="px-8 py-4">Pembeli</th>
                            <th class="px-8 py-4">Status</th>
                            <th class="px-8 py-4 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y border-t">
                        @forelse($recentTransactions as $trx)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-8 py-5">
                                <p class="font-bold text-sm text-slate-700 truncate max-w-[150px]">{{ $trx->customer_name }}</p>
                                <p class="text-xs text-slate-400 truncate max-w-[150px]">{{ $trx->event->title ?? '-' }}</p>
                            </td>
                            <td class="px-8 py-5">
                                @if(in_array($trx->status, ['settlement','success']))
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold uppercase">Success</span>
                                @elseif(strtolower($trx->status) === 'pending')
                                <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-lg text-xs font-bold uppercase">Pending</span>
                                @else
                                <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-lg text-xs font-bold uppercase">{{ $trx->status }}</span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-right font-black text-indigo-600 whitespace-nowrap">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-8 py-10 text-center text-slate-400 font-bold">Belum ada transaksi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 pb-5 flex justify-between items-center">
            <div>
                <h3 class="font-black text-xl text-slate-900">Event Mendatang</h3>
                <p class="text-slate-400 text-sm">Jadwal event yang akan datang</p>
            </div>
            <a href="{{ route('organizer.events.create') }}" class="text-indigo-600 font-bold hover:underline text-sm">+ Buat Event</a>
        </div>
        <div class="divide-y border-t">
            @forelse($upcomingEvents as $ev)
            <div class="flex items-center gap-4 px-8 py-4 hover:bg-slate-50 transition">
                <div class="w-12 h-12 shrink-0 rounded-2xl bg-indigo-50 text-indigo-600 flex flex-col items-center justify-center">
                    <span class="text-base font-black leading-none">{{ $ev->date->format('d') }}</span>
                    <span class="text-[10px] font-bold uppercase">{{ $ev->date->format('M') }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-slate-700 truncate">{{ $ev->title }}</p>
                    <p class="text-xs text-slate-400">{{ $ev->date->format('d M Y, H:i') }} &middot; {{ $ev->tickets_sold_count }} tiket terjual</p>
                </div>
                <span class="px-3 py-1 rounded-lg text-[11px] font-bold uppercase {{ $ev->is_published ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">{{ $ev->is_published ? 'Terbit' : 'Draft' }}</span>
                <a href="{{ route('organizer.events.edit', $ev) }}" class="text-indigo-600 hover:text-indigo-800 transition" title="Edit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
            </div>
            @empty
            <div class="px-8 py-10 text-center text-slate-400 font-bold">Tidak ada event mendatang. <a href="{{ route('organizer.events.create') }}" class="text-indigo-600 underline">Buat event baru</a></div>
            @endforelse
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
        { id: 'spark2', data: @json($ticketsPerMonth), color: '#6366f1' },
        { id: 'spark3', data: @json($ticketsPerMonth), color: '#0ea5e9' },
        { id: 'spark4', data: @json($revenuePerMonth), color: '#f59e0b' },
    ];
    sparks.forEach(function (s) {
        const el = document.getElementById(s.id);
        if (!el) return;
        const ctx = el.getContext('2d');
        const g = ctx.createLinearGradient(0, 0, 0, 40);
        g.addColorStop(0, s.color + '44');
        g.addColorStop(1, s.color + '00');
        new Chart(el, {
            type: 'line',
            data: { labels: s.data.map(function (_, i) { return i; }), datasets: [{ data: s.data, borderColor: s.color, backgroundColor: g, borderWidth: 2, fill: true, tension: 0.4, pointRadius: 0 }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { enabled: false } }, scales: { x: { display: false }, y: { display: false } } }
        });
    });

    const trendSeries = {
        pendapatan: { data: @json($revenuePerMonth), color: '#6366f1', label: 'Pendapatan (Rp)' },
        tiket:      { data: @json($ticketsPerMonth), color: '#10b981', label: 'Tiket Terjual' },
    };
    const tCtx = document.getElementById('trendChart').getContext('2d');
    function gradientFor(color) {
        const g = tCtx.createLinearGradient(0, 0, 0, 288);
        g.addColorStop(0, color + '33');
        g.addColorStop(1, color + '00');
        return g;
    }
    const trendChart = new Chart(tCtx, {
        type: 'line',
        data: { labels: @json($chartLabels), datasets: [{ label: trendSeries.pendapatan.label, data: trendSeries.pendapatan.data, borderColor: trendSeries.pendapatan.color, backgroundColor: gradientFor(trendSeries.pendapatan.color), borderWidth: 3, fill: true, tension: 0.4, pointRadius: 3, pointBackgroundColor: trendSeries.pendapatan.color }] },
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
    window.switchTrend = function (key, btn) {
        const s = trendSeries[key];
        trendChart.data.datasets[0].data = s.data;
        trendChart.data.datasets[0].label = s.label;
        trendChart.data.datasets[0].borderColor = s.color;
        trendChart.data.datasets[0].backgroundColor = gradientFor(s.color);
        trendChart.data.datasets[0].pointBackgroundColor = s.color;
        trendChart.update();
        document.querySelectorAll('.trend-tab').forEach(function (b) { b.classList.remove('bg-indigo-600', 'text-white'); b.classList.add('text-slate-500'); });
        btn.classList.add('bg-indigo-600', 'text-white'); btn.classList.remove('text-slate-500');
    };
})();
</script>
@endsection