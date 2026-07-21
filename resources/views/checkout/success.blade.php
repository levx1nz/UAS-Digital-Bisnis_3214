@extends('layouts.app')
@php
    $paid = in_array(strtolower($transaction->status), ['success', 'settlement', 'capture']);
@endphp
@section('title', $paid ? 'Pembayaran Berhasil' : 'Menunggu Pembayaran')
@section('content')
<main class="max-w-3xl mx-auto px-6 py-20 text-center">
    <div class="bg-white rounded-3xl border border-slate-200 p-12 shadow-sm inline-block w-full max-w-md">
        @if($paid)
            <div class="w-24 h-24 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-black mb-4">Terima Kasih!</h2>
            <p class="text-slate-500 mb-8 leading-relaxed">
                Pembayaran untuk pesanan <strong>{{ $transaction->order_id }}</strong> telah <strong class="text-green-600">berhasil</strong>. E-Ticket telah dikirim ke email Anda (<strong>{{ $transaction->customer_email }}</strong>).
            </p>
            <div class="flex flex-col gap-3">
                <a href="{{ route('my-tickets') }}" class="inline-block px-8 py-4 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">
                    Lihat Tiket Saya
                </a>
                <a href="{{ route('home') }}" class="inline-block px-8 py-4 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition">
                    Kembali ke Beranda
                </a>
            </div>
        @else
            <div class="w-24 h-24 bg-amber-100 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-black mb-4">Menunggu Pembayaran</h2>
            <p class="text-slate-500 mb-8 leading-relaxed">
                Pesanan <strong>{{ $transaction->order_id }}</strong> <strong class="text-amber-600">belum dibayar</strong>. E-Ticket baru diterbitkan setelah pembayaran lunas. Silakan selesaikan pembayaran Anda.
            </p>
            <div class="flex flex-col gap-3">
                <a href="{{ route('checkout.payment', $transaction->order_id) }}" class="inline-block px-8 py-4 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">
                    Lanjutkan Pembayaran
                </a>
                <a href="{{ route('home') }}" class="inline-block px-8 py-4 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition">
                    Kembali ke Beranda
                </a>
            </div>
        @endif
    </div>
</main>
@endsection