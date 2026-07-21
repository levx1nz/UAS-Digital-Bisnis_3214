@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.categories.index') }}" class="p-2 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h2 class="text-3xl font-black text-slate-900">Tambah Kategori Baru</h2>
            <p class="text-slate-500 font-medium mt-1">Masukkan nama kategori baru untuk pengelompokan event.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            
            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Kategori</label>
                <input type="text" name="name" required placeholder="Contoh: Konser, Workshop, Seminar..."
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-600 focus:border-purple-600 transition-all outline-none text-sm font-medium">
            </div>

            <div class="flex justify-end gap-4 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.categories.index') }}" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 bg-purple-600 text-white rounded-xl font-bold shadow-lg shadow-purple-200 hover:scale-105 transition-transform flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection