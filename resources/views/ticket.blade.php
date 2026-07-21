<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-indigo-600 text-white min-h-screen flex flex-col items-center justify-center p-6 py-12">

    <div class="max-w-md w-full">
        <!-- Header Success -->
        <div class="text-center mb-8">
            <div
                class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-black">Pembayaran Berhasil!</h1>
            <p class="text-indigo-100 mt-2">Tiket Anda telah terbit dan siap digunakan.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-400/20 border border-green-400 text-white rounded-2xl font-bold text-center backdrop-blur-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-rose-400/20 border border-rose-400 text-white rounded-2xl font-bold text-center backdrop-blur-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Kartu Tiket Utama -->
        <div class="bg-white text-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl relative">
            <div class="p-8 bg-indigo-50 border-b-4 border-dashed border-indigo-100 text-center relative">
                <p class="text-indigo-600 font-bold uppercase tracking-widest text-xs mb-2">E-Ticket Resmi</p>
                <h2 class="text-2xl font-black leading-tight">{{ $transaction->event->title }}</h2>

                <div class="absolute -left-4 -bottom-4 w-8 h-8 bg-indigo-600 rounded-full"></div>
                <div class="absolute -right-4 -bottom-4 w-8 h-8 bg-indigo-600 rounded-full"></div>
            </div>

            <div class="p-8 space-y-8">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase mb-1">Nama Pembeli</p>
                        <p class="font-bold text-lg">{{ $transaction->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase mb-1">Tanggal & Waktu</p>
                        <p class="font-bold text-lg">{{ \Carbon\Carbon::parse($transaction->event->date)->format('d M, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase mb-1">Order ID</p>
                        <p class="font-bold">{{ $transaction->order_id }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase mb-1">Lokasi</p>
                        <p class="font-bold">{{ $transaction->event->location }}</p>
                    </div>
                </div>

                <div class="bg-slate-100 p-6 rounded-3xl flex flex-col items-center">
                    <p class="text-slate-400 text-xs font-bold uppercase mb-4">Scan QR untuk Check-in</p>
                    <div class="w-48 h-48 bg-white p-4 rounded-xl shadow-inner flex items-center justify-center">
                        <div class="w-full h-full border-4 border-slate-900 flex flex-wrap p-1">
                            <!-- Dummy QR Pattern -->
                            <div class="w-1/4 h-1/4 bg-slate-900"></div><div class="w-1/4 h-1/4 bg-white"></div><div class="w-1/4 h-1/4 bg-slate-900"></div><div class="w-1/4 h-1/4 bg-white"></div>
                            <div class="w-1/4 h-1/4 bg-white"></div><div class="w-1/4 h-1/4 bg-slate-900"></div><div class="w-1/4 h-1/4 bg-white"></div><div class="w-1/4 h-1/4 bg-slate-900"></div>
                            <div class="w-1/4 h-1/4 bg-slate-900"></div><div class="w-1/4 h-1/4 bg-white"></div><div class="w-1/4 h-1/4 bg-slate-900"></div><div class="w-1/4 h-1/4 bg-white"></div>
                            <div class="w-1/4 h-1/4 bg-white"></div><div class="w-1/4 h-1/4 bg-slate-900"></div><div class="w-1/4 h-1/4 bg-white"></div><div class="w-1/4 h-1/4 bg-slate-900"></div>
                        </div>
                    </div>
                    <p class="mt-4 font-mono font-bold text-slate-800">{{ $transaction->order_id }}</p>
                </div>
            </div>

            <div class="px-8 pb-8">
                <button onclick="window.print()"
                    class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg hover:bg-indigo-700 transition">
                    Cetak / Simpan PDF
                </button>
                <a href="{{ route('home') }}"
                    class="block text-center mt-4 text-slate-500 font-bold hover:text-indigo-600">Kembali ke Beranda</a>
            </div>
        </div>

        @if(\Carbon\Carbon::parse($transaction->event->date)->isPast())
            
            @php
                $existingReview = \App\Models\Review::where('user_id', auth()->id())
                                                    ->where('event_id', $transaction->event->id)
                                                    ->first();
            @endphp

            @if($existingReview)
                <!-- Tampilan JIKA SUDAH memberikan ulasan -->
                <div class="mt-6 bg-white rounded-[2.5rem] p-8 shadow-2xl text-slate-900 relative text-center border-2 border-indigo-100">
                    <div class="w-16 h-16 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="font-black text-xl mb-2">Terima kasih atas ulasan Anda!</h3>
                    <p class="text-slate-500 text-sm mb-4">Ulasan Anda membantu penyelenggara menjadi lebih baik.</p>
                    
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <div class="flex justify-center items-center gap-1 mb-2">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-7 h-7 {{ $i <= $existingReview->rating ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                            </svg>
                            @endfor
                        </div>
                        <p class="text-slate-600 italic font-medium">"{{ $existingReview->comment }}"</p>
                    </div>
                </div>
            @else
                <!-- Tampilan JIKA BELUM memberikan ulasan (Form) -->
                <div class="mt-6 bg-white rounded-[2.5rem] p-8 shadow-2xl text-slate-900 relative">
                    <h3 class="font-black text-xl mb-4 text-center">Bagaimana pengalaman Anda?</h3>
                    <form action="{{ route('reviews.store', $transaction->event->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-slate-400 mb-2 text-center uppercase">Penilaian Bintang</label>
                            <div class="flex justify-center gap-2" id="star-container">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg data-value="{{ $i }}" class="star w-12 h-12 cursor-pointer text-slate-200 hover:scale-110 transition-transform duration-200" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                    </svg>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="rating-input" required>
                            <p class="text-xs text-rose-500 mt-2 hidden font-bold text-center" id="rating-error">*Silakan klik bintang terlebih dahulu!</p>
                        </div>

                        <div class="mb-4">
                            <textarea name="comment" rows="3" class="w-full px-5 py-4 rounded-2xl border-2 border-slate-100 outline-none focus:border-amber-400 bg-slate-50 font-medium transition" placeholder="Ceritakan keseruan acara ini..." required></textarea>
                        </div>

                        <button type="submit" class="w-full bg-amber-400 text-white font-black py-4 rounded-2xl hover:bg-amber-500 transition shadow-lg shadow-amber-200/50">
                            Kirim Ulasan
                        </button>
                    </form>
                </div>
            @endif
            
        @else
            <!-- Tampilan JIKA EVENT BELUM SELESAI -->
            <div class="mt-6 bg-white/10 backdrop-blur-md text-white p-4 rounded-2xl font-bold text-center text-sm border border-white/20 shadow-lg">
                ⭐ Fitur ulasan akan terbuka setelah event selesai diselenggarakan.
            </div>
        @endif

    </div>

    <!-- Script Animasi Bintang -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star');
            const ratingInput = document.getElementById('rating-input');
            const errorText = document.getElementById('rating-error');
            let currentRating = 0;

            if(stars.length > 0) {
                stars.forEach(star => {
                    star.addEventListener('mouseover', function() {
                        const value = this.getAttribute('data-value');
                        updateStars(value);
                    });

                    star.addEventListener('mouseout', function() {
                        updateStars(currentRating);
                    });

                    star.addEventListener('click', function() {
                        currentRating = this.getAttribute('data-value');
                        ratingInput.value = currentRating;
                        errorText.classList.add('hidden');
                        updateStars(currentRating);
                    });
                });

                function updateStars(value) {
                    stars.forEach(star => {
                        const starValue = star.getAttribute('data-value');
                        if (starValue <= value) {
                            star.classList.remove('text-slate-200');
                            star.classList.add('text-amber-400');
                        } else {
                            star.classList.remove('text-amber-400');
                            star.classList.add('text-slate-200');
                        }
                    });
                }
                
                const form = ratingInput.closest('form');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        if (currentRating === 0) {
                            e.preventDefault();
                            errorText.classList.remove('hidden');
                        }
                    });
                }
            }
        });
    </script>

</body>
</html>