@extends('layouts.admin')
@section('title', 'Kelola Penyelenggara')
@section('page_title', 'Kelola Penyelenggara')
@section('page_subtitle', 'Awasi kelayakan HIMA/Kepanitiaan di platform.')

@section('content')
@if(session('error'))
<div class="bg-red-100 text-red-700 p-4 rounded-xl mb-6 font-bold text-sm">{{ session('error') }}</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Menunggu Persetujuan</p>
        <h3 class="text-2xl font-black text-amber-600">{{ $pendingCount }}</h3>
    </div>
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Disetujui</p>
        <h3 class="text-2xl font-black text-green-600">{{ $approvedCount }}</h3>
    </div>
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Ditolak</p>
        <h3 class="text-2xl font-black text-rose-600">{{ $rejectedCount }}</h3>
    </div>
</div>

@php $isSuperadmin = auth()->user()->isSuperadmin(); @endphp

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4">Penyelenggara</th>
                    <th class="px-8 py-4">PIC / Email</th>
                    <th class="px-8 py-4 text-center">Jml Event</th>
                    <th class="px-8 py-4 text-center">Status</th>
                    <th class="px-8 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                @forelse($organizers as $org)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-5 font-bold text-slate-800">{{ $org->organizer_name ?? $org->name }}</td>
                    <td class="px-8 py-5">
                        <p class="text-sm font-medium text-slate-600">{{ $org->name }}</p>
                        <p class="text-xs text-slate-400">{{ $org->email }}</p>
                    </td>
                    <td class="px-8 py-5 text-center text-slate-600">{{ $org->events_count }}</td>
                    <td class="px-8 py-5 text-center">
                        @if($org->account_status === 'approved')
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold uppercase">Approved</span>
                        @elseif($org->account_status === 'rejected')
                        <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-lg text-xs font-bold uppercase">Rejected</span>
                        @else
                        <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-bold uppercase">Pending</span>
                        @endif
                    </td>
                    <td class="px-8 py-5">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.organizers.show', $org) }}" class="text-indigo-600 hover:bg-indigo-50 px-3 py-1 rounded-lg font-bold text-xs transition">Detail</a>
                            @if($isSuperadmin)
                                @if($org->account_status !== 'approved')
                                <form action="{{ route('admin.organizers.approve', $org) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="text-green-600 hover:bg-green-50 px-3 py-1 rounded-lg font-bold text-xs transition">Approve</button>
                                </form>
                                @endif
                                @if($org->account_status !== 'rejected')
                                <form action="{{ route('admin.organizers.reject', $org) }}" method="POST" onsubmit="return confirm('Tolak penyelenggara ini?');">
                                    @csrf @method('PATCH')
                                    <button class="text-rose-600 hover:bg-rose-50 px-3 py-1 rounded-lg font-bold text-xs transition">Reject</button>
                                </form>
                                @endif
                            @else
                            <span class="text-xs text-slate-300 font-bold italic">Hanya superadmin</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-8 py-12 text-center text-slate-400 font-bold">Belum ada penyelenggara terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection