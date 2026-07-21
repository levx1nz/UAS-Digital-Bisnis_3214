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

<div class="mb-6 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
    <form action="{{ route('admin.organizers.index') }}" method="GET" class="flex flex-wrap gap-4 items-center">
        <div class="relative flex-1 min-w-[250px]">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama penyelenggara, PIC, atau email..." class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 transition-all outline-none text-sm font-medium">
        </div>
        <button type="submit" class="px-6 py-3 bg-slate-800 text-white rounded-xl font-bold hover:bg-slate-900 transition-colors shadow-md">Cari</button>
        @if(request('search'))
            <a href="{{ route('admin.organizers.index') }}" class="px-6 py-3 bg-red-50 text-red-600 border border-red-100 rounded-xl font-bold hover:bg-red-100 transition-colors">Reset</a>
        @endif
    </form>
</div>

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
                            <a href="{{ route('admin.organizers.show', $org) }}" title="Detail" class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            @if($isSuperadmin)
                                @if($org->account_status !== 'approved')
                                <form action="{{ route('admin.organizers.approve', $org) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" title="Approve" class="p-2.5 bg-green-50 text-green-600 rounded-xl hover:bg-green-600 hover:text-white transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                                @if($org->account_status !== 'rejected')
                                <form action="{{ route('admin.organizers.reject', $org) }}" method="POST" onsubmit="return confirm('Tolak penyelenggara ini?');">
                                    @csrf @method('PATCH')
                                    <button type="submit" title="Reject" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
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