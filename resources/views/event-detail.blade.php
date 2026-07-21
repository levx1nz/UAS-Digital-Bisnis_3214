@extends('layouts.app')
@section('content')
    <main class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-1">
            <div class="sticky top-32">
                <img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) ? asset('storage/' . $event->poster_path) : 'https://placehold.co/400x600' }}" alt="{{ $event->title }}"
                    class="w-full rounded-[2.5rem] shadow-2xl border-8 border-white object-cover aspect-[3/4]">
                <div class="mt-8 p-6 bg-white rounded-3xl border border-slate-100 shadow-sm">
                    @php
                        $organizer = $event->organizer;
                        $organizerName = $organizer->organizer_name ?? ($organizer->name ?? 'Tim Platform');

                        // Inisial nama penyelenggara untuk avatar.
                        $initials = collect(explode(' ', trim($organizerName)))
                            ->filter()->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode('');
                        $initials = strtoupper($initials ?: 'EO');

                        // Rating penyelenggara = rata-rata ulasan dari SEMUA event miliknya.
                        if ($organizer) {
                            $organizerEventIds = \App\Models\Event::where('organizer_id', $organizer->id)->pluck('id');
                            $totalOrganizerReviews = \App\Models\Review::whereIn('event_id', $organizerEventIds)->count();
                            $avgOrganizerRating = $totalOrganizerReviews > 0
                                ? \App\Models\Review::whereIn('event_id', $organizerEventIds)->avg('rating')
                                : 0;
                        } else {
                            $totalOrganizerReviews = 0;
                            $avgOrganizerRating = 0;
                        }
                    @endphp

                    <h4 class="font-bold mb-4 text-slate-800">Penyelenggara</h4>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-black text-xl">
                            {{ $initials }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 text-lg">{{ $organizerName }}</p>

                            <div class="flex items-center gap-3 mt-1.5">
                                <span class="text-[10px] text-indigo-600 bg-indigo-50 px-2 py-1 rounded-md font-bold uppercase tracking-wide border border-indigo-100">
                                    Verified
                                </span>

                                <div class="flex items-center gap-1 text-sm">
                                    @if($totalOrganizerReviews > 0)
                                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                        </svg>
                                        <span class="font-black text-slate-700">
                                            {{ number_format($avgOrganizerRating, 1) }}
                                        </span>
                                        <span class="text-slate-400 text-xs font-bold">
                                            ({{ $totalOrganizerReviews }} ulasan)
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs font-bold">Belum ada ulasan</span>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="lg:col-span-2 space-y-12">
            <div class="space-y-4">
                <span
                    class="px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider">{{ $event->category->name }}</span>
                <h1 class="text-4xl md:text-5xl font-black leading-tight">{{ $event->title }}</h1>
                <div class="flex flex-wrap gap-6 text-slate-500 font-medium">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span>{{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>{{ $event->location }}</span>
                    </div>
                </div>
            </div>

            <div class="prose prose-slate max-w-none">
                <h3 class="text-2xl font-bold mb-4">Deskripsi Event</h3>
                <p class="text-lg text-slate-600 leading-relaxed whitespace-pre-line">
                    {{ $event->description }}
                </p>
            </div>

            <div
                class="bg-indigo-600 rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl shadow-indigo-200 relative overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div>
                        <p class="text-indigo-200 font-bold uppercase tracking-widest text-sm mb-2">Harga Tiket</p>
                        <h2 class="text-5xl font-black">@if($event->currentPrice() <= 0)GRATIS @else Rp {{ number_format($event->currentPrice(), 0, ',', '.') }} <span class="text-lg font-medium text-indigo-200">/
                            orang</span>@endif</h2>
                        <p class="mt-4 text-indigo-100 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Sisa stok: <span class="font-bold underline">{{ $event->stock }} Tiket lagi!</span>
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('checkout.create', $event->id) }}"
                            class="inline-block px-10 py-5 bg-white text-indigo-600 rounded-2xl font-black text-xl hover:scale-105 transition-transform shadow-xl">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-white opacity-10 rounded-full"></div>
                <div class="absolute -left-10 -top-10 w-32 h-32 bg-indigo-400 opacity-20 rounded-full"></div>
            </div>

            <div class="space-y-4">
                <h3 class="text-xl font-bold">Kebijakan Tiket</h3>
                <ul class="space-y-3 text-slate-500">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        E-Ticket akan dikirimkan otomatis setelah pembayaran berhasil.
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Tiket dapat discan di pintu masuk (Check-in).
                    </li>
                    <li class="flex items-start gap-2 text-rose-500">
                        <svg class="w-5 h-5 text-rose-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tiket yang sudah dibeli tidak dapat direfund.
                    </li>
                </ul>
            </div>

            <div class="mt-16 pt-10 border-t-2 border-slate-100">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-2xl font-black text-slate-800">Ulasan Penonton</h3>
                        <p class="text-slate-500 mt-1">Apa kata mereka yang sudah hadir?</p>
                    </div>
                    <div class="text-right">
                        <div class="flex items-center gap-2 justify-end text-amber-400 mb-1">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <span class="text-3xl font-black text-slate-800">
                                {{ $event->reviews->count() > 0 ? number_format($event->reviews->avg('rating'), 1) : '0.0' }}
                            </span>
                        </div>
                        <p class="text-sm font-bold text-slate-400">Dari {{ $event->reviews->count() }} ulasan</p>
                    </div>
                </div>

                @php $reviewCount = $event->reviews->count(); @endphp
                    <div class="grid grid-cols-1 gap-6 {{ $reviewCount > 2 ? 'max-h-[26rem] overflow-y-auto pr-2 -mr-2' : '' }}">
                        @forelse($event->reviews()->latest()->get() as $review)
                        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-black text-lg">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h5 class="font-bold text-slate-800">{{ $review->user->name }}</h5>
                                        <span class="text-xs text-slate-400 font-medium">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                    </svg>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-slate-600 leading-relaxed">"{{ $review->comment }}"</p>
                        </div>
                    @empty
                        <div class="bg-slate-50 p-8 rounded-3xl text-center border-2 border-dashed border-slate-200">
                            <p class="text-slate-400 font-bold">Belum ada ulasan untuk event ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
@endsection