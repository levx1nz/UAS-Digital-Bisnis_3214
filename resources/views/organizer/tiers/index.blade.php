@extends('layouts.organizer')
@section('page_title', 'Tiket Berjenjang')
@section('page_subtitle', 'Atur harga bertahap: Early Bird, Presale, hingga Regular.')

@section('content')
<a href="{{ route('organizer.events.index') }}" class="text-indigo-600 font-bold text-sm mb-6 inline-flex items-center gap-2">&larr; Kembali ke Event</a>

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 mb-8">
    <h3 class="font-black text-lg text-slate-800">{{ $event->title }}</h3>
    <p class="text-slate-500 text-sm mt-1">Harga aktif saat ini:
        <span class="font-bold text-indigo-600">Rp {{ number_format($event->currentPrice(), 0, ',', '.') }}</span>
        @if($event->currentTier())
            <span class="ml-1 text-xs bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-md font-bold">{{ $event->currentTier()->name }}</span>
        @else
            <span class="ml-1 text-xs bg-slate-100 text-slate-500 px-2 py-0.5 rounded-md font-bold">Harga dasar (belum ada tier)</span>
        @endif
    </p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4 text-center">Harga</th>
                    <th class="px-6 py-4 text-center">Periode</th>
                    <th class="px-6 py-4 text-center">Kuota</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                @forelse($tiers as $tier)
                <tr class="{{ $tier->isActiveNow() && $tier->hasQuota() ? 'bg-indigo-50/50' : '' }}">
                    <td class="px-6 py-4 font-bold text-slate-800">
                        {{ $tier->name }}
                        @if($tier->isActiveNow() && $tier->hasQuota())
                            <span class="ml-1 text-[10px] bg-indigo-600 text-white px-2 py-0.5 rounded-md uppercase">Aktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center text-slate-600 whitespace-nowrap">Rp {{ number_format($tier->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center text-xs text-slate-500 whitespace-nowrap">
                        {{ $tier->starts_at?->format('d M') ?? '—' }} s/d {{ $tier->ends_at?->format('d M Y') ?? '—' }}
                    </td>
                    <td class="px-6 py-4 text-center text-slate-600">{{ $tier->quota ? ($tier->sold . '/' . $tier->quota) : 'Tak terbatas' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <form action="{{ route('organizer.events.tiers.destroy', [$event, $tier]) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ $tier->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" title="Hapus" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-10 text-center text-slate-400 font-bold">Belum ada kategori tiket. Tambahkan di samping.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
        <h4 class="font-black text-slate-800 mb-4">Tambah Kategori</h4>
        <form action="{{ route('organizer.events.tiers.store', $event) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Kategori</label>
                <input type="text" name="name" placeholder="Early Bird / Presale 1 / Regular" required class="w-full px-4 py-2.5 bg-slate-50 border-2 border-slate-100 rounded-xl outline-none focus:border-indigo-600 transition text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Harga (Rp)</label>
                <input type="number" name="price" min="0" required class="w-full px-4 py-2.5 bg-slate-50 border-2 border-slate-100 rounded-xl outline-none focus:border-indigo-600 transition text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kuota (opsional)</label>
                <input type="number" name="quota" min="1" placeholder="Kosong = tak terbatas" class="w-full px-4 py-2.5 bg-slate-50 border-2 border-slate-100 rounded-xl outline-none focus:border-indigo-600 transition text-sm">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Mulai</label>
                    <input type="datetime-local" name="starts_at" class="w-full px-3 py-2.5 bg-slate-50 border-2 border-slate-100 rounded-xl outline-none focus:border-indigo-600 transition text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Selesai</label>
                    <input type="datetime-local" name="ends_at" class="w-full px-3 py-2.5 bg-slate-50 border-2 border-slate-100 rounded-xl outline-none focus:border-indigo-600 transition text-xs">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Urutan Tampil</label>
                <input type="number" name="sort_order" value="{{ $tiers->count() }}" class="w-full px-4 py-2.5 bg-slate-50 border-2 border-slate-100 rounded-xl outline-none focus:border-indigo-600 transition text-sm">
            </div>
            <button class="w-full py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">Tambah Kategori</button>
        </form>
        <p class="text-[11px] text-slate-400 mt-4 leading-relaxed">Tip: isi rentang tanggal tiap tier berurutan (mis. Early Bird 1&ndash;7 Agu, Presale 8&ndash;20 Agu, Regular 21 Agu&ndash;hari-H). Sistem otomatis memakai harga tier yang periodenya sedang berjalan.</p>
    </div>
</div>
@endsection