@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.categories.index') }}" class="p-2 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h2 class="text-3xl font-black text-slate-900">Edit Kategori</h2>
            <p class="text-slate-500 font-medium mt-1">Perbarui nama kategori untuk pengelompokan jenis event.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT') <div class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Kategori</label>
                <input type="text" name="name" value="{{ $category->name }}" required placeholder="Contoh: Konser, Workshop, Seminar..."
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-600 focus:border-purple-600 transition-all outline-none text-sm font-medium">
            </div>

            <div class="flex justify-end gap-4 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.categories.index') }}" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 bg-yellow-500 text-slate-900 rounded-xl font-bold shadow-lg shadow-yellow-100 hover:scale-105 transition-transform flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.5"></path></svg>
                    Perbarui Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection