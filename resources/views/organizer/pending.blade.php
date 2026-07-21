<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menunggu Persetujuan - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-6">
    <div class="max-w-lg w-full bg-white rounded-3xl p-10 shadow-sm border border-slate-100 text-center">
        @php $status = auth()->user()->account_status ?? 'pending'; @endphp
        @if($status === 'rejected')
            <h1 class="text-2xl font-black text-slate-800 mb-2">Pendaftaran Ditolak</h1>
            <p class="text-slate-500 mb-6">Mohon maaf, pengajuan akun penyelenggara <strong>{{ auth()->user()->organizer_name }}</strong> belum memenuhi kelayakan. Silakan hubungi admin platform.</p>
        @else
            <h1 class="text-2xl font-black text-slate-800 mb-2">Menunggu Persetujuan</h1>
            <p class="text-slate-500 mb-6">Terima kasih sudah mendaftar sebagai penyelenggara <strong>{{ auth()->user()->organizer_name }}</strong>. Akun Anda sedang ditinjau kelayakannya oleh Superadmin.</p>
        @endif
        <form action="{{ route('user.logout') }}" method="POST">
            @csrf
            <button type="submit" class="px-6 py-3 bg-slate-800 text-white rounded-2xl font-bold hover:bg-slate-900 transition">Keluar</button>
        </form>
    </div>
</body>
</html>