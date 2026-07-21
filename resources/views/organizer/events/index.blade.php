@extends('layouts.organizer')
@section('page_title', 'Event Saya')
@section('page_subtitle', 'Kelola acara yang Anda selenggarakan.')

@section('content')
<div class="flex justify-end mb-6">
    <a href="{{ route('organizer.events.create') }}" class="px-5 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition inline-flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Buat Event
    </a>
</div>

<div class="mb-6 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
    <form action="{{ route('organizer.events.index') }}" method="GET" class="flex flex-wrap gap-4 items-center">
        <div class="relative flex-1 min-w-[250px]">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama event..." class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 transition-all outline-none text-sm font-medium">
        </div>
        <button type="submit" class="px-6 py-3 bg-slate-800 text-white rounded-xl font-bold hover:bg-slate-900 transition-colors shadow-md">Cari</button>
        @if(request('search'))
            <a href="{{ route('organizer.events.index') }}" class="px-6 py-3 bg-red-50 text-red-600 border border-red-100 rounded-xl font-bold hover:bg-red-100 transition-colors">Reset</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4">Event</th>
                    <th class="px-8 py-4">Kategori</th>
                    <th class="px-8 py-4">Tanggal</th>
                    <th class="px-8 py-4 text-center">Harga</th>
                    <th class="px-8 py-4 text-center">Stok</th>
                    <th class="px-8 py-4 text-center">Status</th>
                    <th class="px-8 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                @forelse($events as $event)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-5 font-bold text-slate-800 max-w-xs truncate">{{ $event->title }}</td>
                    <td class="px-8 py-5 text-slate-500">{{ $event->category->name ?? '-' }}</td>
                    <td class="px-8 py-5 text-slate-500 text-sm whitespace-nowrap">{{ $event->date?->format('d M Y - H:i') }}</td>
                    <td class="px-8 py-5 text-center text-slate-600 whitespace-nowrap">Rp {{ number_format($event->price, 0, ',', '.') }}</td>
                    <td class="px-8 py-5 text-center text-slate-600">{{ $event->stock }}</td>
                    <td class="px-8 py-5 text-center">
                        @if($event->is_published)
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold uppercase">Publik</span>
                        @else
                        <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-lg text-xs font-bold uppercase">Draft</span>
                        @endif
                    </td>
                    <td class="px-8 py-5">
                        <div class="flex items-center justify-end gap-2">
                            <form action="{{ route('organizer.events.publish', $event) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" title="{{ $event->is_published ? 'Sembunyikan' : 'Publikasikan' }}" class="p-2.5 rounded-xl transition {{ $event->is_published ? 'bg-slate-100 text-slate-500 hover:bg-slate-600 hover:text-white' : 'bg-green-50 text-green-600 hover:bg-green-600 hover:text-white' }}">
                                    @if($event->is_published)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                    @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    @endif
                                </button>
                            </form>
                            <a href="{{ route('organizer.events.tiers.index', $event) }}" title="Atur Tiket Berjenjang" class="p-2.5 bg-amber-50 text-amber-600 rounded-xl hover:bg-amber-600 hover:text-white transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </a>
                            <a href="{{ route('organizer.events.edit', $event) }}" title="Edit" class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('organizer.events.destroy', $event) }}" method="POST" onsubmit="return confirm('Hapus event {{ $event->title }}?');">
                                @csrf @method('DELETE')
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
                <tr><td colspan="7" class="px-8 py-12 text-center text-slate-400 font-bold">Belum ada event. Klik "Buat Event" untuk memulai.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $events->links() }}</div>
@endsection