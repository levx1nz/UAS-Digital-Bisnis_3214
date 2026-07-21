<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-6">
    <div class="max-w-md w-full bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-black text-slate-800">Selamat Datang!</h1>
            <p class="text-slate-500">Masuk untuk memesan tiket event kesukaanmu.</p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 text-red-600 p-4 rounded-xl mb-6 font-bold text-sm text-center">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('user.login.post') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                <input type="email" name="email" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-indigo-600 transition" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                <input type="password" name="password" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-indigo-600 transition" required>
            </div>
            <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-lg hover:bg-indigo-700 transition">Masuk</button>
            <!-- Garis Pemisah (Atau) -->
            <div class="mt-6 flex items-center justify-center space-x-2">
                <span class="h-px w-full bg-slate-200"></span>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">ATAU</span>
                <span class="h-px w-full bg-slate-200"></span>
            </div>

            <!-- Tombol Continue with Google -->
            <a href="{{ route('google.login') }}" class="mt-6 flex items-center justify-center w-full py-4 bg-white border-2 border-slate-200 rounded-2xl font-bold text-slate-700 hover:bg-slate-50 transition shadow-sm">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-6 h-6 mr-3" alt="Google">
                Continue with Google
            </a>
        </form>

        <p class="text-center mt-6 text-slate-500 text-sm">
            Belum punya akun? <a href="{{ route('user.register') }}" class="text-indigo-600 font-bold hover:underline">Daftar sekarang</a>
        </p>
        <div class="mt-4 pt-4 border-t border-slate-100 text-center">
            <p class="text-slate-500 text-sm">Punya HIMA / Kepanitiaan?</p>
            <a href="{{ route('organizer.register') }}" class="inline-block mt-1 text-emerald-600 font-bold hover:underline text-sm">Daftar sebagai Penyelenggara &rarr;</a>
        </div>
    </div>
</body>
</html>