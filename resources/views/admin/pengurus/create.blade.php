@extends('layouts.admin')
@section('title', 'Tambah Pengurus')

@section('content')
<div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm max-w-lg">
    <h2 class="text-xl font-bold mb-6">Tambah Pengurus Baru</h2>
    <form action="{{ route('admin.pengurus.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Pengurus</label>
            <input type="text" name="nama_pengurus" class="w-full px-4 py-2 border rounded-xl" required>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Jabatan</label>
            <select name="jabatan_id" class="w-full px-4 py-2 border rounded-xl bg-white" required>
                <option value="">-- Pilih Jabatan --</option>
                @foreach($jabatans as $jabatan)
                    <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Tugas</label>
            <textarea name="deskripsi" rows="3" class="w-full px-4 py-2 border rounded-xl"></textarea>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-bold text-slate-700 mb-2">Gaji (Rp)</label>
            <input type="number" name="gaji" class="w-full px-4 py-2 border rounded-xl" required>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold">Simpan</button>
        <a href="{{ route('admin.pengurus.index') }}" class="ml-2 text-slate-500 font-bold">Batal</a>
    </form>
</div>
@endsection