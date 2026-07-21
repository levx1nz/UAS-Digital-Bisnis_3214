@extends('layouts.organizer')
@section('page_title', 'Kupon & Voucher')
@section('page_subtitle', 'Buat kode promo seperti MAHASISWA50 untuk pembeli.')

@section('content')
<div class="flex justify-end mb-6">
    <a href="{{ route('organizer.coupons.create') }}" class="px-5 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition inline-flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Buat Kupon
    </a>
</div>

<div class="mb-6 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
    <form action="{{ route('organizer.coupons.index') }}" method="GET" class="flex flex-wrap gap-4 items-center">
        <div class="relative flex-1 min-w-[250px]">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode kupon atau event..." class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 transition-all outline-none text-sm font-medium">
        </div>
        <button type="submit" class="px-6 py-3 bg-slate-800 text-white rounded-xl font-bold hover:bg-slate-900 transition-colors shadow-md">Cari</button>
        @if(request('search'))
            <a href="{{ route('organizer.coupons.index') }}" class="px-6 py-3 bg-red-50 text-red-600 border border-red-100 rounded-xl font-bold hover:bg-red-100 transition-colors">Reset</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-6 py-4">Kode</th>
                    <th class="px-6 py-4">Potongan</th>
                    <th class="px-6 py-4">Berlaku untuk</th>
                    <th class="px-6 py-4 text-center">Pemakaian</th>
                    <th class="px-6 py-4 text-center">Kedaluwarsa</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                @forelse($coupons as $coupon)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $coupon->code }}</td>
                    <td class="px-6 py-4 text-slate-600">
                        @if($coupon->type === 'percent') {{ $coupon->value }}% @else Rp {{ number_format($coupon->value, 0, ',', '.') }} @endif
                    </td>
                    <td class="px-6 py-4 text-slate-500 text-sm">{{ $coupon->event->title ?? 'Semua event saya' }}</td>
                    <td class="px-6 py-4 text-center text-slate-600">{{ $coupon->used_count }}{{ $coupon->max_usage ? '/' . $coupon->max_usage : '' }}</td>
                    <td class="px-6 py-4 text-center text-slate-500 text-sm">{{ $coupon->expires_at?->format('d M Y') ?? 'Selamanya' }}</td>
                    <td class="px-6 py-4 text-center">
                        <form action="{{ route('organizer.coupons.toggle', $coupon) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="px-3 py-1 rounded-lg text-xs font-bold {{ $coupon->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $coupon->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <form action="{{ route('organizer.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Hapus kupon {{ $coupon->code }}?')">
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
                <tr><td colspan="7" class="px-6 py-12 text-center text-slate-400 font-bold">Belum ada kupon. Klik "Buat Kupon" untuk memulai.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection