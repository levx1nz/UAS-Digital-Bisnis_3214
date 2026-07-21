@extends('layouts.admin')
@section('title', 'Kelola Ulasan')
@section('page_title', 'Kelola Ulasan')

@section('content')

<div class="mb-6 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
    <form action="{{ route('admin.reviews.index') }}" method="GET" class="flex flex-wrap gap-4 items-center">
        <div class="relative flex-1 min-w-[250px]">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ulasan, pengguna, atau event..." class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 transition-all outline-none text-sm font-medium">
        </div>
        <button type="submit" class="px-6 py-3 bg-slate-800 text-white rounded-xl font-bold hover:bg-slate-900 transition-colors shadow-md">Cari</button>
        @if(request('search'))
            <a href="{{ route('admin.reviews.index') }}" class="px-6 py-3 bg-red-50 text-red-600 border border-red-100 rounded-xl font-bold hover:bg-red-100 transition-colors">Reset</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden mb-10">
    <div class="p-8 border-b flex items-center justify-between gap-4">
        <div>
            <h3 class="font-black text-xl">Semua Ulasan</h3>
            <p class="text-slate-400 text-sm mt-1">Seluruh ulasan pengguna terhadap event</p>
        </div>
        <span class="px-4 py-2 bg-amber-50 text-amber-700 rounded-xl font-bold text-sm whitespace-nowrap">{{ $reviews->count() }} Ulasan</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse mt-4">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4">Event</th>
                    <th class="px-8 py-4">Pengguna</th>
                    <th class="px-8 py-4">Rating</th>
                    <th class="px-8 py-4">Komentar</th>
                    <th class="px-8 py-4">Tanggal</th>
                    <th class="px-8 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                @forelse($reviews as $review)
                <tr class="hover:bg-slate-50 transition align-top">
                    <td class="px-8 py-6 font-bold text-slate-700">{{ $review->event?->title ?? 'Event dihapus' }}</td>
                    <td class="px-8 py-6 text-sm font-medium text-slate-600 whitespace-nowrap">
                        {{ $review->user?->name ?? 'Pengguna dihapus' }}
                        <div class="text-xs text-slate-400 mt-1">{{ $review->created_at->diffForHumans() }}</div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            @endfor
                        </div>
                    </td>
                    <td class="px-8 py-6 text-sm text-slate-500 italic max-w-md">"{{ $review->comment }}"</td>
                    <td class="px-8 py-6 text-sm text-slate-500 whitespace-nowrap">{{ $review->created_at->format('d M Y') }}</td>
                    <td class="px-8 py-6">
                        <div class="flex items-center justify-end gap-2">
                            <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-12 text-center text-slate-400 font-bold">Belum ada data ulasan yang masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection