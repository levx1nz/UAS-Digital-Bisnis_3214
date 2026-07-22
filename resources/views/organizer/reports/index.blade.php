@extends('layouts.organizer')
@section('page_title', 'Laporan Pendapatan')
@section('page_subtitle', 'Analitik pendapatan & performa event Anda.')

@section('content')
<div class="space-y-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Total Pendapatan</p>
            <h3 class="text-2xl font-black text-slate-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-gradient-to-br from-indigo-600 to-purple-600 text-white p-6 rounded-3xl shadow-sm">
            <p class="text-indigo-100 text-xs font-bold uppercase tracking-widest mb-1">Estimasi Bersih</p>
            <h3 class="text-2xl font-black">Rp {{ number_format($netRevenue, 0, ',', '.') }}</h3>
            <p class="text-indigo-200 text-[11px] mt-1">Setelah biaya layanan Rp {{ number_format($serviceFee, 0, ',', '.') }}/tiket</p>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Tiket Terjual</p>
            <h3 class="text-2xl font-black text-slate-900">{{ number_format($ticketsSold, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Rata-rata Harga Tiket</p>
            <h3 class="text-2xl font-black text-slate-900">Rp {{ number_format($avgTicketPrice, 0, ',', '.') }}</h3>
        </div>
    </div>

    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h3 class="font-black text-xl text-slate-900">Tren 12 Bulan</h3>
                <p class="text-slate-400 text-sm">Perkembangan pendapatan &amp; tiket terjual</p>
            </div>
            <div class="flex bg-slate-100 rounded-xl p-1 text-xs font-bold">
                <button type="button" onclick="switchReport('pendapatan', this)" class="report-tab px-3 py-1.5 rounded-lg bg-indigo-600 text-white transition">Pendapatan</button>
                <button type="button" onclick="switchReport('tiket', this)" class="report-tab px-3 py-1.5 rounded-lg text-slate-500 transition">Tiket</button>
            </div>
        </div>
        <div class="h-80"><canvas id="reportChart"></canvas></div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 pb-5">
            <h3 class="font-black text-xl text-slate-900">Rincian per Event</h3>
            <p class="text-slate-400 text-sm">Semua event Anda, diurutkan dari pendapatan tertinggi</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                    <tr>
                        <th class="px-8 py-4">#</th>
                        <th class="px-8 py-4">Event</th>
                        <th class="px-8 py-4 text-center">Terjual</th>
                        <th class="px-8 py-4 text-center">Pending</th>
                        <th class="px-8 py-4">Kontribusi</th>
                        <th class="px-8 py-4 text-right">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y border-t">
                    @forelse($revenuePerEvent as $i => $ev)
                    @php $rev = $ev->revenue_sum ?? 0; $bar = $maxRevenue > 0 ? round($rev / $maxRevenue * 100) : 0; @endphp
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-8 py-5 text-slate-400 font-bold">{{ $i + 1 }}</td>
                        <td class="px-8 py-5 font-bold text-slate-700 max-w-xs truncate">{{ $ev->title }}</td>
                        <td class="px-8 py-5 text-center text-slate-600 font-medium">{{ $ev->tickets_sold_count }}</td>
                        <td class="px-8 py-5 text-center text-amber-600 font-medium">{{ $ev->pending_count }}</td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-2">
                                <div class="h-1.5 w-28 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-purple-500" style="width: {{ $bar }}%"></div>
                                </div>
                                <span class="text-[11px] text-slate-400 font-medium">{{ $bar }}%</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-right font-black text-indigo-600 whitespace-nowrap">Rp {{ number_format($rev, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-8 py-10 text-center text-slate-400 font-bold">Belum ada event.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
(function () {
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#94a3b8';
    const series = {
        pendapatan: { data: @json($revenuePerMonth), color: '#6366f1', label: 'Pendapatan (Rp)' },
        tiket:      { data: @json($ticketsPerMonth), color: '#10b981', label: 'Tiket Terjual' },
    };
    const ctx = document.getElementById('reportChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: { labels: @json($chartLabels), datasets: [{ label: series.pendapatan.label, data: series.pendapatan.data, backgroundColor: series.pendapatan.color, borderRadius: 8, maxBarThickness: 28 }] },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { padding: 12, cornerRadius: 12 } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }
        }
    });
    window.switchReport = function (key, btn) {
        const s = series[key];
        chart.data.datasets[0].data = s.data;
        chart.data.datasets[0].label = s.label;
        chart.data.datasets[0].backgroundColor = s.color;
        chart.update();
        document.querySelectorAll('.report-tab').forEach(function (b) { b.classList.remove('bg-indigo-600', 'text-white'); b.classList.add('text-slate-500'); });
        btn.classList.add('bg-indigo-600', 'text-white'); btn.classList.remove('text-slate-500');
    };
})();
</script>
@endsection