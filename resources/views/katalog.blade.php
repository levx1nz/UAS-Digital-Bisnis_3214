@extends('layouts.app')

@section('title', 'Katalog Event - AmikomEventHub')

@section('content')
<main class="max-w-7xl mx-auto px-6 py-16">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
        <div>
            <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold uppercase tracking-wider mb-4">Jelajahi Semua</span>
            <h1 class="text-4xl md:text-5xl font-black text-slate-900 leading-tight">Katalog Event</h1>
            <p class="text-slate-500 font-medium mt-3 max-w-lg">Temukan seluruh event yang tersedia di AmikomEventHub, mulai dari workshop hingga konser.</p>
        </div>
        <p class="text-sm font-bold text-slate-400">{{ $events->count() }} event ditemukan</p>
    </div>

    {{-- Filter kategori --}}
    <div class="flex flex-wrap gap-2 mb-10">
        <a href="{{ route('katalog') }}"
           class="px-5 py-2 text-sm font-bold rounded-full transition-all duration-300 {{ !request('category') ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
            Semua
        </a>
        @foreach($categories as $cat)
        <a href="{{ route('katalog', ['category' => $cat->slug]) }}"
           class="px-5 py-2 text-sm font-bold rounded-full transition-all duration-300 {{ request('category') == $cat->slug ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
            {{ $cat->name }}
        </a>
        @endforeach
    </div>

    {{-- Grid event --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($events as $event)
        @php
            $poster = ($event->poster_path && Storage::disk('public')->exists($event->poster_path))
                ? asset('storage/' . $event->poster_path)
                : 'https://placehold.co/600x400?text=AmikomEventHub';
        @endphp
        <div class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col">
            <div class="relative overflow-hidden aspect-video">
                <img src="{{ $poster }}" alt="{{ $event->title }}"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur rounded-lg text-xs font-bold uppercase text-indigo-600 shadow-sm">
                    {{ $event->category->name ?? 'Umum' }}
                </div>
            </div>

            <div class="p-6 flex flex-col flex-grow">
                <h3 class="text-xl font-bold mb-2 group-hover:text-indigo-600 transition line-clamp-2">
                    {{ $event->title }}
                </h3>

                <div class="flex items-center gap-1 mb-4">
                    @if($event->reviews_count > 0)
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>
                        <span class="font-black text-sm text-slate-700">{{ number_format($event->reviews_avg_rating, 1) }}</span>
                        <span class="text-xs font-bold text-slate-400 ml-1">({{ $event->reviews_count }})</span>
                    @else
                        <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>
                        <span class="text-xs font-bold text-slate-400 ml-1">Belum ada ulasan</span>
                    @endif
                </div>

                <div class="flex items-center gap-2 text-slate-500 text-sm mb-6 mt-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}</span>
                </div>

                <div class="flex justify-between items-center pt-4 border-t">
                    <div class="flex flex-col">
                        <span class="text-xs text-slate-400 font-bold uppercase">Harga</span>
                        <span class="text-xl font-black text-indigo-600">
                            @if($event->currentPrice() <= 0)
                                <span class="text-emerald-600">GRATIS</span>
                            @else
                                Rp {{ number_format($event->currentPrice(), 0, ',', '.') }}
                            @endif
                        </span>
                    </div>
                    <a href="{{ route('events.show', $event->id) }}"
                        class="px-5 py-2.5 bg-indigo-50 text-indigo-600 rounded-xl font-bold hover:bg-indigo-600 hover:text-white transition-all text-sm">
                        Detail
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-20 bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200">
            <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <p class="text-slate-500 font-bold text-lg mb-4">Belum ada event pada kategori ini.</p>
            <a href="{{ route('katalog') }}" class="inline-block px-8 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 shadow-lg transition">Lihat Semua Event</a>
        </div>
        @endforelse
    </div>
</main>
@endsection