@extends('layouts.admin')
@section('title', 'Edit Pengurus')

@section('content')
<div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm max-w-lg">
    <h2 class="text-xl font-bold mb-6">Edit Data Pengurus</h2>
    <form action="{{ route('admin.pengurus.update', $pengurus->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Pengurus</label>
            <input type="text" name="nama_pengurus" value="{{ $pengurus->nama_pengurus }}" class="w-full px-4 py-2 border rounded-xl" required>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Jabatan</label>
            <select name="jabatan_id" class="w-full px-4 py-2 border rounded-xl bg-white" required>
                @foreach($jabatans as $jabatan)
                    <option value="{{ $jabatan->id }}" {{ $pengurus->jabatan_id == $jabatan->id ? 'selected' : '' }}>
                        {{ $jabatan->nama_jabatan }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Tugas</label>
            <textarea name="deskripsi" rows="3" class="w-full px-4 py-2 border rounded-xl">{{ $pengurus->deskripsi }}</textarea>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-bold text-slate-700 mb-2">Gaji (Rp)</label>
            <input type="number" name="gaji" value="{{ $pengurus->gaji }}" class="w-full px-4 py-2 border rounded-xl" required>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold">Update</button>
        <a href="{{ route('admin.pengurus.index') }}" class="ml-2 text-slate-500 font-bold">Batal</a>
    </form>
</div>
@endsection