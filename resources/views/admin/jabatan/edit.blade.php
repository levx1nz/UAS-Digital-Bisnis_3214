@extends('layouts.admin')
@section('title', 'Edit Jabatan')

@section('content')
<div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm max-w-lg">
    <h2 class="text-xl font-bold mb-6">Edit Data Jabatan</h2>
    <form action="{{ route('admin.jabatan.update', $jabatan->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Jabatan</label>
            <input type="text" name="nama_jabatan" value="{{ $jabatan->nama_jabatan }}" class="w-full px-4 py-2 border rounded-xl" required>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold">Update</button>
        <a href="{{ route('admin.jabatan.index') }}" class="ml-2 text-slate-500 font-bold">Batal</a>
    </form>
</div>
@endsection