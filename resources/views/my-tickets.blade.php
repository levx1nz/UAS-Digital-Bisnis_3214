@extends('layouts.app')

@section('title', 'Tiket Saya - AmikomEventHub')

@section('content')
<main class="max-w-7xl mx-auto px-6 py-12">
    <div class="mb-10">
        <h1 class="text-3xl font-black text-slate-800">Tiket Saya</h1>
        <p class="text-slate-500 mt-2">Daftar semua e-ticket dari event yang telah Anda beli.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($transactions as $trx)
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col hover:shadow-xl transition-shadow duration-300 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-12 h-12 bg-indigo-50 rounded-full"></div>
                
                <p class="text-indigo-600 font-bold uppercase tracking-widest text-xs mb-2">Order ID: {{ $trx->order_id }}</p>
                <h3 class="font-black text-xl mb-2 text-slate-800 leading-tight">{{ $trx->event->title ?? 'Event Tidak Tersedia' }}</h3>
                
                <div class="flex items-center gap-2 text-slate-500 text-sm mb-6">
                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>
                        @if($trx->event)
                            {{ \Carbon\Carbon::parse($trx->event->date)->format('d M Y, H:i') }}
                        @else
                            -
                        @endif
                    </span>
                </div>

                <a href="{{ route('ticket', $trx->id) }}" class="mt-auto block text-center bg-indigo-50 text-indigo-600 font-black py-4 rounded-2xl hover:bg-indigo-600 hover:text-white active:scale-95 transition-all">
                    Lihat E-Ticket
                </a>
            </div>
        @empty
            <div class="col-span-full text-center py-20 bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                </div>
                <p class="text-slate-500 font-bold text-lg mb-4">Anda belum memiliki tiket event apa pun.</p>
                <a href="{{ route('home') }}" class="inline-block px-8 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 shadow-lg transition">Jelajahi Event</a>
            </div>
        @endforelse
    </div>
</main>
@endsection