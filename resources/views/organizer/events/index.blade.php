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
                                <button class="px-3 py-1 rounded-lg font-bold text-xs transition {{ $event->is_published ? 'text-slate-500 hover:bg-slate-100' : 'text-indigo-600 hover:bg-indigo-50' }}">
                                    {{ $event->is_published ? 'Sembunyikan' : 'Publikasikan' }}
                                </button>
                            </form>
                            <a href="{{ route('organizer.events.edit', $event) }}" class="text-indigo-600 hover:bg-indigo-50 px-3 py-1 rounded-lg font-bold text-xs transition">Edit</a>
                            <form action="{{ route('organizer.events.destroy', $event) }}" method="POST" onsubmit="return confirm('Hapus event {{ $event->title }}?');">
                                @csrf @method('DELETE')
                                <button class="text-rose-600 hover:bg-rose-50 px-3 py-1 rounded-lg font-bold text-xs transition">Hapus</button>
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