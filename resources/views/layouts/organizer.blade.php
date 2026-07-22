<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penyelenggara - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-slate-900 flex min-h-screen">
    <aside class="w-64 bg-indigo-900 text-indigo-100 flex flex-col p-6 space-y-8 sticky top-0 h-screen">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-900 font-bold text-xl">AH</div>
            <div>
                <span class="block text-sm font-bold text-white tracking-tight leading-tight">AmikomEventHub</span>
                <span class="block text-[10px] uppercase tracking-widest text-indigo-400">Panel Penyelenggara</span>
            </div>
        </div>
        <nav class="flex-1 space-y-2">
            <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-400 mb-4 px-2">Menu</p>
            <a href="{{ route('organizer.dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('organizer.dashboard') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800' }} rounded-xl font-bold transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            <a href="{{ route('organizer.events.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('organizer.events.*') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800' }} rounded-xl font-bold transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Event Saya
            </a>
            <a href="{{ route('organizer.transactions.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('organizer.transactions.*') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800' }} rounded-xl font-bold transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Transaksi
            </a>
            <a href="{{ route('organizer.reports.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('organizer.reports.*') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800' }} rounded-xl font-bold transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Laporan Pendapatan
            </a>
            <a href="{{ route('organizer.coupons.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('organizer.coupons.*') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800' }} rounded-xl font-bold transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z"></path></svg>
                Kupon & Voucher
            </a>
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-indigo-800 rounded-xl font-bold transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Lihat Situs Publik
            </a>
        </nav>
        <div class="pt-6 border-t border-indigo-800">
            <div class="px-2 mb-4">
                <p class="font-bold text-white text-sm truncate">{{ auth()->user()->organizer_name ?? auth()->user()->name }}</p>
                <p class="text-xs text-indigo-400 truncate">{{ auth()->user()->email }}</p>
            </div>
            <form action="{{ route('user.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-indigo-300 hover:text-white transition font-medium text-left">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>
    <main class="flex-1 p-10 overflow-y-auto w-full">
        <header class="flex justify-between items-center mb-10 w-full">
            <div>
                <h1 class="text-3xl font-black">@yield('page_title', 'Dashboard')</h1>
                <p class="text-slate-500 font-medium">@yield('page_subtitle', 'Kelola event dan pantau pendapatan Anda.')</p>
            </div>
            <span class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-xl text-xs font-black uppercase tracking-widest">Penyelenggara</span>
        </header>

        @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-6 font-bold text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-6 font-bold text-sm">{{ session('error') }}</div>
        @endif
        @if($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-6 text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        @yield('content')
    </main>
</body>
</html>