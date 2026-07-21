@extends('layouts.app')

@section('title', 'Checkout - ' . $event->title)

@section('content')
@php
    $tier = $event->currentTier();
    $subtotal = $event->currentPrice();
    $serviceFee = 5000;
    $isFree = $subtotal <= 0;
@endphp
<main class="max-w-3xl mx-auto px-6 py-20">
    <div class="mb-12">
        <a href="{{ route('events.show', $event->id) }}" class="text-indigo-600 font-bold flex items-center gap-2 mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Event
        </a>
        <h1 class="text-4xl font-extrabold">Checkout</h1>
        <p class="text-slate-500 mt-2">Lengkapi data Anda untuk mendapatkan tiket.</p>
    </div>

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl font-bold">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 gap-8">
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
            <h3 class="text-xl font-bold mb-6 border-b pb-4">Pesanan Anda</h3>
            <div class="flex gap-6 items-start">
                <img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) ? asset('storage/' . $event->poster_path) : 'https://placehold.co/200x200' }}" alt="Event" class="w-24 h-24 rounded-2xl object-cover">
                <div>
                    <h4 class="font-extrabold text-lg">{{ $event->title }}</h4>
                    <p class="text-slate-500">{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }} • {{ $event->location }}</p>
                    <p class="text-indigo-600 font-bold mt-2">
                        1 x Rp {{ number_format($subtotal, 0, ',', '.') }}
                        @if($tier)<span class="text-xs bg-indigo-50 px-2 py-0.5 rounded-md align-middle">{{ $tier->name }}</span>@endif
                    </p>
                </div>
            </div>
            <div class="mt-8 pt-6 border-t space-y-3">
                <div class="flex justify-between text-slate-500">
                    <span>Harga Tiket @if($tier)({{ $tier->name }})@endif</span>
                    <span>@if($isFree)<span class="font-bold text-emerald-600">GRATIS</span>@else Rp {{ number_format($subtotal, 0, ',', '.') }} @endif</span>
                </div>
                @unless($isFree)
                <div id="rowDiscount" class="flex justify-between text-emerald-600 font-bold hidden">
                    <span>Diskon Kupon (<span id="couponLabel"></span>)</span>
                    <span>- Rp <span id="discountAmount">0</span></span>
                </div>
                <div id="rowServiceFee" class="flex justify-between text-slate-500">
                    <span>Biaya Layanan</span>
                    <span>Rp 5.000</span>
                </div>
                @endunless
                <div class="flex justify-between text-2xl font-black mt-4 pt-4 border-t">
                    <span>Total Bayar</span>
                    <span class="text-indigo-600">@if($isFree)GRATIS @else<span id="totalAmount">Rp {{ number_format($subtotal + $serviceFee, 0, ',', '.') }}</span>@endif</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
            <h3 class="text-xl font-bold mb-6 italic text-indigo-600 underline underline-offset-8">
                Data Pemesan
            </h3>

            <form action="{{ route('checkout.store', $event->id) }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Lengkap</label>
                    <input type="text" name="customer_name" placeholder="Masukkan nama sesuai identitas"
                           class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                           required
                           value="{{ Auth::check() ? Auth::user()->name : old('customer_name') }}">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Email Aktif</label>
                        <input type="email" name="customer_email" placeholder="masukkan email anda"
                               class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                               required
                               value="{{ Auth::check() ? Auth::user()->email : old('customer_email') }}">
                        <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase tracking-tighter">*E-Ticket akan dikirim ke email ini</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">No. WhatsApp</label>
                        <input type="tel" name="customer_phone" placeholder="Masukkan no WA anda"
                               class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                               required
                               value="{{ Auth::check() ? Auth::user()->no_hp : old('customer_phone') }}">
                    </div>
                </div>

                @unless($isFree)
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Kode Kupon (opsional)</label>
                    <div class="flex gap-3">
                        <input type="text" name="coupon_code" id="couponInput" value="{{ old('coupon_code') }}" placeholder="Masukkan kode kupon"
                               class="flex-1 px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium">
                        <button type="button" id="applyCoupon" class="px-6 py-4 bg-slate-800 text-white rounded-2xl font-bold hover:bg-slate-900 transition whitespace-nowrap">Terapkan</button>
                    </div>
                    <p id="couponMsg" class="text-xs font-bold mt-2 hidden"></p>
                </div>
                @else
                <div class="p-4 bg-emerald-50 text-emerald-700 rounded-2xl font-bold text-sm text-center">
                    Acara ini GRATIS — tidak perlu pembayaran. Klik tombol di bawah untuk langsung mendapatkan E-Ticket.
                </div>
                @endunless

                <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-2xl font-black text-xl shadow-xl shadow-indigo-200 hover:bg-indigo-700 active:scale-95 transition-all">
                    {{ $isFree ? 'Dapatkan Tiket Gratis' : 'Lanjut Pembayaran' }}
                </button>
                <p class="text-center text-xs text-slate-400">Dengan menekan tombol di atas, Anda menyetujui Syarat & Ketentuan kami.</p>
            </form>
        </div>
    </div>
</main>

<script>
    document.getElementById('applyCoupon')?.addEventListener('click', function () {
        const code = document.getElementById('couponInput').value.trim();
        const msg = document.getElementById('couponMsg');
        const rowDiscount = document.getElementById('rowDiscount');
        const baseTotal = "Rp {{ number_format($subtotal + $serviceFee, 0, ',', '.') }}";
        if (!code) { return; }

        fetch("{{ route('checkout.coupon', $event->id) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ coupon_code: code })
        })
        .then(r => r.json())
        .then(data => {
            msg.classList.remove('hidden', 'text-emerald-600', 'text-rose-600');
            const rowServiceFee = document.getElementById('rowServiceFee');
            const totalAmount   = document.getElementById('totalAmount');
            const couponLabel   = document.getElementById('couponLabel');
            const discountAmount = document.getElementById('discountAmount');
            if (data.valid) {
                msg.classList.add('text-emerald-600');
                msg.textContent = data.message;
                if (rowDiscount) rowDiscount.classList.remove('hidden');
                if (couponLabel) couponLabel.textContent = code.toUpperCase();
                if (discountAmount) discountAmount.textContent = new Intl.NumberFormat('id-ID').format(data.discount);
                if (data.free) {
                    if (totalAmount) totalAmount.textContent = 'GRATIS';
                    if (rowServiceFee) rowServiceFee.classList.add('hidden');
                } else {
                    if (totalAmount) totalAmount.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.total);
                    if (rowServiceFee) rowServiceFee.classList.remove('hidden');
                }
            } else {
                msg.classList.add('text-rose-600');
                msg.textContent = data.message;
                if (rowDiscount) rowDiscount.classList.add('hidden');
                if (rowServiceFee) rowServiceFee.classList.remove('hidden');
                if (totalAmount) totalAmount.textContent = baseTotal;
            }
        })
        .catch(() => {
            msg.classList.remove('hidden');
            msg.classList.add('text-rose-600');
            msg.textContent = 'Gagal memeriksa kupon. Coba lagi.';
        });
    });
</script>
@endsection