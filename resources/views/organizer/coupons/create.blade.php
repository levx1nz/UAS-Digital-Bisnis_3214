@extends('layouts.organizer')
@section('page_title', 'Buat Kupon')
@section('page_subtitle', 'Tentukan kode, jenis potongan, dan masa berlaku.')

@section('content')
<a href="{{ route('organizer.coupons.index') }}" class="text-indigo-600 font-bold text-sm mb-6 inline-flex items-center gap-2">&larr; Kembali</a>

<form action="{{ route('organizer.coupons.store') }}" method="POST" class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 max-w-2xl space-y-6">
    @csrf
    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Kode Kupon</label>
        <input type="text" name="code" value="{{ old('code') }}" placeholder="Masukkan kode kupon" required class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-indigo-600 transition font-medium">
        <p class="text-xs text-slate-400 mt-1">Huruf & angka saja, tanpa spasi.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Jenis Potongan</label>
            <select name="type" class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-indigo-600 transition">
                <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>Persen (%)</option>
                <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Nilai Potongan</label>
            <input type="number" name="value" value="{{ old('value') }}" min="1" required placeholder="" class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-indigo-600 transition">
            <p class="text-xs text-slate-400 mt-1">Contoh: 50 = 50% (persen) atau Rp 50.000 (nominal).</p>
        </div>
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Berlaku untuk Event</label>
        <select name="event_id" class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-indigo-600 transition">
            <option value="">Semua event saya</option>
            @foreach($events as $ev)
                <option value="{{ $ev->id }}" {{ old('event_id') == $ev->id ? 'selected' : '' }}>{{ $ev->title }}</option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Min. Pembelian</label>
            <input type="number" name="min_purchase" value="{{ old('min_purchase', 0) }}" min="0" class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-indigo-600 transition">
        </div>
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Batas Pemakaian</label>
            <input type="number" name="max_usage" value="{{ old('max_usage') }}" min="1" placeholder="Kosong = tak terbatas" class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-indigo-600 transition">
        </div>
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Kedaluwarsa</label>
            <input type="date" name="expires_at" value="{{ old('expires_at') }}" class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-indigo-600 transition">
        </div>
    </div>

    <label class="flex items-center gap-3">
        <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 rounded accent-indigo-600">
        <span class="font-bold text-slate-700">Aktifkan kupon sekarang</span>
    </label>

    <button class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-lg hover:bg-indigo-700 transition">Simpan Kupon</button>
</form>
@endsection