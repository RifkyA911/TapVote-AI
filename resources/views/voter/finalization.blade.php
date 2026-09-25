@extends('layouts.app')

@section('title', 'Suara Berhasil Dicatat - TapVote AI')

@section('content')
<div class="flex-1 flex flex-col items-center justify-center p-6 text-center max-w-2xl mx-auto w-full min-h-screen">

    <div class="w-full bg-white border-2 border-slate-300 rounded-3xl p-8 sm:p-12 shadow-sm flex flex-col items-center">
        
        <!-- Icon Sukses Besar & Terang -->
        <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-emerald-100 border-4 border-emerald-500 text-emerald-700 flex items-center justify-center mb-6 shadow-sm">
            <svg class="w-14 h-14 sm:w-16 sm:h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <span class="inline-flex items-center px-5 py-2 rounded-full text-sm font-extrabold uppercase tracking-wider bg-emerald-100 text-emerald-900 border border-emerald-300 mb-4">
            ✓ Pemilihan Selesai & Terverifikasi
        </span>

        <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mb-3 tracking-tight">
            Terima Kasih Atas Partisipasi Anda!
        </h2>

        <p class="text-lg sm:text-xl text-slate-700 font-medium leading-relaxed mb-8 max-w-lg">
            Pilihan suara Anda untuk <strong class="text-slate-900">Ketua</strong> dan <strong class="text-slate-900">Pengawas Koperasi</strong> telah tersimpan aman dan sah di dalam sistem.
        </p>

        <!-- Hitung Mundur Sesi Kios -->
        <div class="w-full p-6 rounded-2xl bg-slate-50 border-2 border-slate-200">
            <div class="flex items-center justify-between text-base font-bold text-slate-700 mb-3">
                <span>Layar akan kembali ke awal dalam:</span>
                <span class="font-extrabold text-blue-700 text-xl"><span id="countdown">5</span> Detik</span>
            </div>

            <!-- Progress Bar Terang -->
            <div class="w-full h-3 rounded-full bg-slate-200 overflow-hidden mb-5">
                <div id="countdown-bar" class="h-full bg-blue-600 transition-all duration-1000 ease-linear" style="width: 100%;"></div>
            </div>

            <a href="{{ route('voter.tap') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-base shadow transition">
                Kembali ke Layar Awal Sekarang →
            </a>
        </div>

    </div>

</div>

@push('scripts')
<script>
    // Animasi Gebyar Meriah Confetti
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.confetti === 'function') {
            const end = Date.now() + 5000;
            const colors = ['#2563eb', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6', '#ef4444'];

            (function frame() {
                window.confetti({
                    particleCount: 5,
                    angle: 60,
                    spread: 60,
                    origin: { x: 0, y: 0.7 },
                    colors: colors
                });
                window.confetti({
                    particleCount: 5,
                    angle: 120,
                    spread: 60,
                    origin: { x: 1, y: 0.7 },
                    colors: colors
                });

                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                }
            }());
        }

        // 5 Detik Countdown & Auto Logout
        let secondsLeft = 5;
        const countdownEl = document.getElementById('countdown');
        const barEl = document.getElementById('countdown-bar');

        const interval = setInterval(function() {
            secondsLeft--;
            if (countdownEl) countdownEl.innerText = secondsLeft;
            if (barEl) barEl.style.width = (secondsLeft / 5 * 100) + '%';

            if (secondsLeft <= 0) {
                clearInterval(interval);
                window.location.href = "{{ route('voter.tap') }}";
            }
        }, 1000);
    });
</script>
@endpush
@endsection
