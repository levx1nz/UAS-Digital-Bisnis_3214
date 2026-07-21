@extends('layouts.admin')
@section('title', 'Kelola Pengguna')
@section('page_title', 'Kelola Pengguna')

@section('content')

<div class="mb-6 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
    <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap gap-4 items-center">
        <div class="relative flex-1 min-w-[250px]">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email pengguna..." class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 transition-all outline-none text-sm font-medium">
        </div>
        <button type="submit" class="px-6 py-3 bg-slate-800 text-white rounded-xl font-bold hover:bg-slate-900 transition-colors shadow-md">Cari</button>
        @if(request('search'))
            <a href="{{ route('admin.users.index') }}" class="px-6 py-3 bg-red-50 text-red-600 border border-red-100 rounded-xl font-bold hover:bg-red-100 transition-colors">Reset</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden mb-10">
    <div class="p-8 border-b flex items-center justify-between gap-4">
        <div>
            <h3 class="font-black text-xl">Daftar Pengguna Terdaftar</h3>
            <p class="text-slate-400 text-sm mt-1">Kelola seluruh akun pengguna platform</p>
        </div>
        <span class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl font-bold text-sm whitespace-nowrap">{{ $users->count() }} Pengguna</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse mt-4">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4">Nama Lengkap</th>
                    <th class="px-8 py-4">Email</th>
                    <th class="px-8 py-4">Role</th>
                    <th class="px-8 py-4">Tanggal Mendaftar</th>
                    <th class="px-8 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-6 font-bold text-slate-800">{{ $user->name }}</td>
                    <td class="px-8 py-6 text-slate-500">{{ $user->email }}</td>
                    <td class="px-8 py-6">
                        @if($user->role === 'superadmin')
                            <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-lg text-xs font-bold uppercase">Superadmin</span>
                        @elseif($user->role === 'admin')
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-xs font-bold uppercase">Admin</span>
                        @elseif($user->role === 'organizer')
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold uppercase">Organizer</span>
                        @else
                            <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold uppercase">User</span>
                        @endif
                    </td>
                    <td class="px-8 py-6 text-sm text-slate-500">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-8 py-6">
                        <div class="flex items-center justify-end gap-2">
                        @if(!$user->isPlatformStaff())
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Hapus Akun" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                        @else
                        <span title="Hapus terkunci untuk staf platform" class="p-2.5 bg-slate-100 text-slate-300 rounded-xl cursor-not-allowed inline-flex">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </span>
                        @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-12 text-center text-slate-400 font-bold">Belum ada pengguna terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection