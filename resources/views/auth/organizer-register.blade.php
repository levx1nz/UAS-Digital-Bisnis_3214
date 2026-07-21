<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Penyelenggara - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-6">
    <div class="max-w-md w-full bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
        <div class="text-center mb-8">
            <span class="inline-block px-4 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-black uppercase tracking-widest mb-3">Penyelenggara</span>
            <h1 class="text-2xl font-black text-slate-800">Daftar sebagai Penyelenggara</h1>
            <p class="text-slate-500">Untuk HIMA / Kepanitiaan yang ingin membuka & menjual tiket event sendiri.</p>
        </div>

        @if($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-6 text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('organizer.register.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Penyelenggara (HIMA/Kepanitiaan)</label>
                <input type="text" name="organizer_name" value="{{ old('organizer_name') }}" placeholder="mis. HIMA Informatika" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-emerald-600 transition" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama PIC / Penanggung Jawab</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-emerald-600 transition" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-emerald-600 transition" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nomor HP / WhatsApp</label>
                <input type="number" name="no_hp" value="{{ old('no_hp') }}" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-emerald-600 transition" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                <input type="password" name="password" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-emerald-600 transition" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-emerald-600 transition" required>
            </div>
            <button type="submit" class="w-full py-4 bg-emerald-600 text-white rounded-2xl font-black text-lg hover:bg-emerald-700 transition mt-4">Ajukan Pendaftaran</button>
        </form>

        <p class="text-center mt-4 text-xs text-slate-400">Akun akan aktif setelah disetujui (kelayakan) oleh Superadmin.</p>
        <p class="text-center mt-4 text-slate-500 text-sm">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-emerald-600 font-bold hover:underline">Masuk</a>
        </p>
    </div>
</body>
</html>