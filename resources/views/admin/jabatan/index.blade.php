@extends('layouts.admin')
@section('title', 'Data Jabatan')

@section('content')
<div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold">Kelola Jabatan</h2>
        <a href="{{ route('admin.jabatan.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-indigo-700">+ Tambah Jabatan</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4 font-bold">{{ session('success') }}</div>
    @endif

    <table class="w-full text-left border-collapse">
        <thead class="bg-slate-50 text-slate-400 uppercase text-xs font-black">
            <tr>
                <th class="p-4 border-b">No</th>
                <th class="p-4 border-b">Nama Jabatan</th>
                <th class="p-4 border-b text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jabatans as $item)
            <tr class="hover:bg-slate-50">
                <td class="p-4 border-b">{{ $loop->iteration }}</td>
                <td class="p-4 border-b font-bold">{{ $item->nama_jabatan }}</td>
                <td class="p-4 border-b text-right flex justify-end gap-2">
                    <a href="{{ route('admin.jabatan.edit', $item->id) }}" class="bg-amber-400 text-white px-3 py-1 rounded-lg font-bold">Edit</a>
                    <form action="{{ route('admin.jabatan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jabatan ini?');">
                        @csrf @method('DELETE')
                        <button class="bg-rose-600 text-white px-3 py-1 rounded-lg font-bold">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection