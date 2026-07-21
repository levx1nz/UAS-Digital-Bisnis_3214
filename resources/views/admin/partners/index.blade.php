@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-900">Manajemen Partner</h2>
            <p class="text-slate-500 font-medium mt-1">Kelola daftar mitra dan sponsor pendukung event.</p>
        </div>
        <a href="{{ route('admin.partners.create') }}" 
           class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-200 hover:scale-105 transition-transform flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Partner Baru
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 px-6 py-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3 shadow-sm">
        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <div class="mb-6 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
        <form action="{{ route('admin.partners.index') }}" method="GET" class="flex flex-wrap gap-4 items-center">
            <div class="relative flex-1 min-w-[250px]">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama partner..." 
                       class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 transition-all outline-none text-sm font-medium">
            </div>
            <button type="submit" class="px-6 py-3 bg-slate-800 text-white rounded-xl font-bold hover:bg-slate-900 transition-colors shadow-md">Cari</button>
            @if(request('search'))
                <a href="{{ route('admin.partners.index') }}" class="px-6 py-3 bg-red-50 text-red-600 border border-red-100 rounded-xl font-bold hover:bg-red-100 transition-colors">Reset</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">No</th>
                        <th class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider ">Logo Partner</th>
                        <th class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Partner</th>
                        <th class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Ditambahkan</th>
                        <th class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($partners as $partner)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4 text-sm font-bold text-slate-900 text-center">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            <div class="w-16 h-16 bg-white border border-slate-100 rounded-xl p-2 flex items-center justify-center shadow-sm group-hover:shadow transition-all">
                                <img src="{{ asset('storage/' . $partner->logo_url) }}" alt="{{ $partner->name }}" class="max-w-full max-h-full object-contain">
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-slate-700">{{ $partner->name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500 font-medium">{{ $partner->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.partners.edit', $partner->id) }}" class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>

                                <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus partner ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition">
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
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </div>
                                <span class="text-slate-500 font-medium">Belum ada data partner yang ditambahkan.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection