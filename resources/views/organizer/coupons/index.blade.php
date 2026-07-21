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
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('organizer.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Hapus kupon {{ $coupon->code }}?')">
                            @csrf @method('DELETE')
                            <button class="text-rose-600 hover:bg-rose-50 px-3 py-1 rounded-lg font-bold text-xs">Hapus</button>
                        </form>
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