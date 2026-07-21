@extends('layouts.organizer')
@section('page_title', 'Dashboard Ringkasan')
@section('page_subtitle', 'Analitik pendapatan & tiket khusus event Anda.')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Pendapatan Saya</p>
        <h3 class="text-2xl font-black">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
    </div>
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Tiket Terjual</p>
        <h3 class="text-2xl font-black">{{ number_format($ticketsSold, 0, ',', '.') }}</h3>
    </div>
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Total Event</p>
        <h3 class="text-2xl font-black">{{ $totalEvents }} Event</h3>
    </div>
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Pesanan Pending</p>
        <h3 class="text-2xl font-black">{{ $pendingOrders }} Pesanan</h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b flex justify-between items-center">
            <h3 class="font-black text-xl">Pendapatan per Event</h3>
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
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-8 py-5 font-bold text-slate-700 max-w-xs truncate">{{ $ev->title }}</td>
                        <td class="px-8 py-5 text-center text-slate-600">{{ $ev->tickets_sold_count }}</td>
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
        <div class="p-8 border-b"><h3 class="font-black text-xl">Transaksi Terakhir</h3></div>
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
                            <p class="font-bold text-sm truncate max-w-[150px]">{{ $trx->customer_name }}</p>
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
@endsection