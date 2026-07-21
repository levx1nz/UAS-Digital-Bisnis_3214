@extends('layouts.admin')
@section('title', 'Data Pengurus')

@section('content')
<div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold">Kelola Pengurus</h2>
        <a href="{{ route('admin.pengurus.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-indigo-700">+ Tambah Pengurus</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4 font-bold">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full text-left border-collapse">
        <thead class="bg-slate-50 text-slate-400 uppercase text-xs font-black">
            <tr>
                <th class="p-4 border-b">No</th>
                <th class="p-4 border-b">Nama Pengurus</th>
                <th class="p-4 border-b">Jabatan</th>
                <th class="p-4 border-b">Deskripsi</th>
                <th class="p-4 border-b">Gaji</th>
                <th class="p-4 border-b text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penguruses as $item)
            <tr class="hover:bg-slate-50">
                <td class="p-4 border-b">{{ $loop->iteration }}</td>
                <td class="p-4 border-b font-bold">{{ $item->nama_pengurus }}</td>
                <td class="p-4 border-b">
                    <span class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded-md text-xs font-bold">
                        {{ $item->jabatan->nama_jabatan }}
                    </span>
                </td>
                <td class="p-4 border-b">{{ $item->deskripsi ?? '-' }}</td>
                <td class="p-4 border-b font-bold text-green-600">Rp {{ number_format($item->gaji, 0, ',', '.') }}</td>
                <td class="p-4 border-b">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.pengurus.edit', $item->id) }}" title="Edit" class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <form action="{{ route('admin.pengurus.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus pengurus ini?');">
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
            @endforeach
        </tbody>
    </table>
</div>
@endsection