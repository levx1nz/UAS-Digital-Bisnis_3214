@extends('layouts.admin')
@section('title', 'Detail Penyelenggara')
@section('page_title', $organizer->organizer_name ?? $organizer->name)
@section('page_subtitle', 'Detail & analitik pendapatan penyelenggara.')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.organizers.index') }}" class="text-indigo-600 font-bold hover:underline text-sm">&larr; Kembali ke daftar</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Total Pendapatan</p>
        <h3 class="text-2xl font-black text-indigo-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
    </div>
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Tiket Terjual</p>
        <h3 class="text-2xl font-black">{{ number_format($ticketsSold, 0, ',', '.') }}</h3>
    </div>
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Status Kelayakan</p>
        <h3 class="text-lg font-black uppercase
            @if($organizer->account_status === 'approved') text-green-600
            @elseif($organizer->account_status === 'rejected') text-rose-600
            @else text-amber-600 @endif">{{ $organizer->account_status }}</h3>
    </div>
</div>

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 mb-8">
    <h3 class="font-black text-lg mb-4">Informasi Akun</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        <div><span class="text-slate-400 font-bold uppercase text-xs block">Nama Penyelenggara</span> {{ $organizer->organizer_name ?? '-' }}</div>
        <div><span class="text-slate-400 font-bold uppercase text-xs block">PIC</span> {{ $organizer->name }}</div>
        <div><span class="text-slate-400 font-bold uppercase text-xs block">Email</span> {{ $organizer->email }}</div>
        <div><span class="text-slate-400 font-bold uppercase text-xs block">No HP</span> {{ $organizer->no_hp ?? '-' }}</div>
    </div>

    @if(auth()->user()->isSuperadmin())
    <div class="flex gap-3 mt-6 pt-6 border-t">
        @if($organizer->account_status !== 'approved')
        <form action="{{ route('admin.organizers.approve', $organizer) }}" method="POST">
            @csrf @method('PATCH')
            <button class="px-5 py-2 bg-green-600 text-white rounded-xl font-bold text-sm hover:bg-green-700 transition">Setujui Penyelenggara</button>
        </form>
        @endif
        @if($organizer->account_status !== 'rejected')
        <form action="{{ route('admin.organizers.reject', $organizer) }}" method="POST" onsubmit="return confirm('Tolak penyelenggara ini?');">
            @csrf @method('PATCH')
            <button class="px-5 py-2 bg-rose-600 text-white rounded-xl font-bold text-sm hover:bg-rose-700 transition">Tolak Penyelenggara</button>
        </form>
        @endif
    </div>
    @endif
</div>

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-8 border-b"><h3 class="font-black text-xl">Event Milik Penyelenggara</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4">Event</th>
                    <th class="px-8 py-4">Kategori</th>
                    <th class="px-8 py-4">Tanggal</th>
                    <th class="px-8 py-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                @forelse($events as $event)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-5 font-bold text-slate-700 max-w-xs truncate">{{ $event->title }}</td>
                    <td class="px-8 py-5 text-slate-500">{{ $event->category->name ?? '-' }}</td>
                    <td class="px-8 py-5 text-slate-500 text-sm">{{ $event->date?->format('d M Y') }}</td>
                    <td class="px-8 py-5 text-center">
                        @if($event->is_published)
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold uppercase">Publik</span>
                        @else
                        <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-lg text-xs font-bold uppercase">Draft</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-8 py-10 text-center text-slate-400 font-bold">Belum ada event.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection