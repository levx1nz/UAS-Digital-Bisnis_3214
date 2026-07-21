@extends('layouts.app')
@section('content')

@if(session('error'))
    <div class="max-w-7xl mx-auto mt-6 px-6">
        <div class="bg-red-50 text-red-600 p-4 rounded-xl font-bold text-sm border border-red-100 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            {{ session('error') }}
        </div>
    </div>
@endif

    <section class="max-w-7xl mx-auto px-6 py-20 flex flex-col md:flex-row items-center gap-12">
        <div class="flex-1 space-y-8">
            <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider">#1 Event Platform</span>
            <h1 class="text-5xl md:text-7xl font-extrabold leading-tight">
                Temukan & Pesan <span class="text-indigo-600">Tiket Event</span> Impianmu.
            </h1>
            <p class="text-lg text-slate-500 max-w-lg leading-relaxed">
                Dari konser musik hingga workshop teknologi, semua ada di genggamanmu. Pesan aman & cepat dengan Midtrans.
            </p>
            <div class="flex gap-4">
                <a href="#events" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-200 hover:scale-105 transition-transform">
                    Mulai Jelajah
                </a>
                <a href="#" class="px-8 py-4 border-2 border-slate-200 rounded-2xl font-bold text-lg hover:border-indigo-600 hover:text-indigo-600 transition">
                    Cara Pesan
                </a>
            </div>
        </div>
        <div class="flex-1 relative">
            <div class="absolute -top-10 -left-10 w-64 h-64 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute -bottom-10 -right-10 w-64 h-64 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <img src="{{ asset('assets/concert.png') }}" alt="Concert" class="rounded-[2rem] shadow-2xl relative z-10 w-full object-cover aspect-[4/5] object-center">

            <div class="absolute -bottom-6 -left-6 glass p-6 rounded-2xl shadow-xl z-20 border border-white">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-bold uppercase">Terverifikasi</p>
                        <p class="font-bold">Pembayaran Aman via Midtrans</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="events" class="max-w-7xl mx-auto px-6 py-20">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-10 gap-6">
    
    <div>
        <h2 class="text-3xl font-black text-slate-900">Event Terdekat</h2>
        <p class="text-slate-500 font-medium">Jangan sampai ketinggalan acara seru minggu ini!</p>
    </div>

    <div class="flex flex-wrap gap-2">
        <a href="/" 
           class="px-5 py-2 text-sm font-bold rounded-full transition-all duration-300 
                  {{ !request('category') ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
            Semua
        </a>

        @foreach($categories as $cat)
        <a href="/?category={{ $cat->slug }}"
           class="px-5 py-2 text-sm font-bold rounded-full transition-all duration-300 shadow-sm
                  {{ request('category') == $cat->slug ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
            {{ $cat->name }}
        </a>
        @endforeach
    </div>
</div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($events as $event)
        <div class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col">
            <div class="relative overflow-hidden aspect-video"> 
                <img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) ? asset('storage/' . $event->poster_path) : 'https://placehold.co/200x600' }}" 
                    alt="{{ $event->title }}"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur rounded-lg text-xs font-bold uppercase text-indigo-600 shadow-sm">
                    {{ $event->category->name }}
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
                        
                        <span class="font-black text-sm text-slate-700">
                            {{ number_format($event->reviews_avg_rating, 1) }}
                        </span>
                        
                        <span class="text-xs font-bold text-slate-400 ml-1">
                            ({{ $event->reviews_count }})
                        </span>
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
        @endforeach
    </div>
</section>

<section id="partners" class="max-w-7xl mx-auto px-6 py-20 border-t border-slate-100">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-black text-slate-900">Didukung Oleh Mitra Kami</h2>
            <p class="text-slate-500 font-medium mt-2">Platform ini didukung oleh partner terbaik untuk pengalaman event yang maksimal.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6 items-center justify-center">
            @forelse($partners as $partner)
            <div class="group bg-white flex flex-col items-center justify-center p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-indigo-100 transition-all duration-300">
                <img src="{{ asset('storage/' . $partner->logo_url) }}" 
                     alt="{{ $partner->name }}" 
                     class="h-16 w-full object-contain mb-4 grayscale group-hover:grayscale-0 transition-all duration-300">
                <span class="text-sm font-bold text-slate-700 text-center">{{ $partner->name }}</span>
            </div>
            @empty
            <div class="col-span-full text-center text-slate-500 bg-slate-50 py-8 rounded-2xl border border-dashed border-slate-200">
                Belum ada data partner yang ditambahkan.
            </div>
            @endforelse
        </div>
    </section>
@endsection