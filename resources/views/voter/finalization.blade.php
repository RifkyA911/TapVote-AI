@extends('layouts.app')

@section('title', 'Suara Berhasil Dicatat - TapVote AI')

@section('content')
<div class="flex-1 flex flex-col items-center justify-center p-6 text-center max-w-xl mx-auto w-full relative z-20">

    <!-- Fluid Animated Checkmark Box -->
    <div class="relative mb-8">
        <div class="radar-ring w-40 h-40" style="border-color: rgba(16, 185, 129, 0.4);"></div>
        <div class="radar-ring w-40 h-40" style="border-color: rgba(16, 185, 129, 0.2); animation-delay: 0.8s;"></div>

        <div class="relative z-10 w-28 h-28 rounded-full bg-gradient-to-tr from-emerald-600 to-teal-400 p-1 shadow-2xl shadow-emerald-500/40 flex items-center justify-center transform animate-bounce">
            <div class="w-full h-full rounded-full bg-slate-950 flex items-center justify-center">
                <svg class="w-14 h-14 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>
    </div>

    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-widest bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 mb-4">
        Voting Berhasil & Terverifikasi
    </span>

    <h2 class="text-3xl sm:text-4xl font-black text-white mb-3">
        Terima Kasih Atas Partisipasi Anda!
    </h2>

    <p class="text-slate-300 text-sm leading-relaxed mb-8 max-w-md mx-auto">
        Pilihan suara Anda untuk <strong class="text-white">Ketua</strong> dan <strong class="text-white">Pengawas Koperasi</strong> telah berhasil disimpan secara aman dan terenkripsi dalam sistem.
    </p>

    <!-- Auto Logout Countdown Widget (5 Detik) -->
    <div class="w-full p-6 rounded-3xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl shadow-2xl">
        <div class="flex items-center justify-between text-xs text-slate-400 mb-3">
            <span>Sesi bilik suara akan ditutup otomatis:</span>
            <span class="font-mono font-bold text-emerald-400 text-sm"><span id="countdown">5</span> Detik</span>
        </div>

        <!-- Animated Progress Bar -->
        <div class="w-full h-2.5 rounded-full bg-slate-800 overflow-hidden relative">
            <div id="countdown-bar" class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 transition-all duration-1000 ease-linear" style="width: 100%;"></div>
        </div>

        <div class="mt-5 flex items-center justify-center space-x-3">
            <a href="{{ route('voter.tap') }}" class="px-6 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold text-xs transition">
                Selesai & Logout Sekarang
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Trigger Confetti Celebration Fluid Animation
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.confetti === 'function') {
            const count = 200;
            const defaults = {
                origin: { y: 0.7 }
            };

            function fire(particleRatio, opts) {
                window.confetti(Object.assign({}, defaults, opts, {
                    particleCount: Math.floor(count * particleRatio)
                }));
            }

            fire(0.25, {
                spread: 26,
                startVelocity: 55,
            });
            fire(0.2, {
                spread: 60,
            });
            fire(0.35, {
                spread: 100,
                decay: 0.91,
                scalar: 0.8
            });
            fire(0.1, {
                spread: 120,
                startVelocity: 25,
                decay: 0.92,
                scalar: 1.2
            });
            fire(0.1, {
                spread: 120,
                startVelocity: 45,
            });
        }

        // 5 Seconds Countdown & Auto Logout
        let secondsLeft = 5;
        const countdownEl = document.getElementById('countdown');
        const barEl = document.getElementById('countdown-bar');

        const interval = setInterval(function() {
            secondsLeft--;
            if (countdownEl) countdownEl.innerText = secondsLeft;
            if (barEl) {
                const pct = (secondsLeft / 5) * 100;
                barEl.style.width = pct + '%';
            }

            if (secondsLeft <= 0) {
                clearInterval(interval);
                window.location.href = "{{ route('voter.tap') }}";
            }
        }, 1000);
    });
</script>
@endpush
@endsection
