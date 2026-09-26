<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <title>403 Forbidden - Mau Ngapain Lu Kesini Tong? 😜</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800;900&family=JetBrains+Mono:wght@700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        @keyframes wobbleMock {
            0%, 100% { transform: rotate(0deg) scale(1); }
            20% { transform: rotate(-12deg) scale(1.1); }
            40% { transform: rotate(14deg) scale(1.1); }
            60% { transform: rotate(-8deg) scale(1.05); }
            80% { transform: rotate(10deg) scale(1.05); }
        }
        @keyframes radarSweep {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .animate-wobble-mock {
            animation: wobbleMock 1.8s infinite ease-in-out;
        }
        .animate-radar {
            animation: radarSweep 3s linear infinite;
        }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 bg-gradient-to-br from-slate-950 via-slate-900 to-rose-950 text-white font-sans overflow-hidden relative selection:bg-rose-500 selection:text-white">

    <!-- Ambient Glowing Orbs -->
    <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-rose-600/20 blur-3xl pointer-events-none animate-pulse"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-amber-600/20 blur-3xl pointer-events-none animate-pulse" style="animation-duration: 4s;"></div>

    <div class="max-w-lg w-full text-center relative z-10 space-y-6">

        <!-- Animated Mocking Mascot Avatar -->
        <div class="relative w-36 h-36 mx-auto">
            <!-- Radar Scanner Ring Behind -->
            <div class="absolute inset-0 rounded-full border border-rose-500/30 overflow-hidden">
                <div class="w-full h-full bg-gradient-to-tr from-transparent via-rose-500/20 to-transparent animate-radar origin-center"></div>
            </div>

            <!-- Funny Taunting Emoji Avatar with Wobble Animation -->
            <div class="relative z-10 w-full h-full rounded-full bg-rose-950/80 border-2 border-rose-500/60 shadow-2xl shadow-rose-900/50 flex items-center justify-center text-7xl select-none animate-wobble-mock cursor-pointer" onclick="playMockSound()">
                😜
            </div>

            <span class="absolute -bottom-2 -right-2 px-3 py-1 rounded-full bg-rose-600 text-white text-[11px] font-black uppercase tracking-wider border-2 border-slate-950 shadow-md">
                DENIED 403
            </span>
        </div>

        <!-- Taunting Headline -->
        <div class="space-y-2">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-rose-500/20 text-rose-300 border border-rose-500/30 text-xs font-black uppercase tracking-widest">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                <span>SECURITY FIREWALL DETECTED INTRUDER</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">
                Wkwkwk Mau Ngapain Lu Kesini Tong?! 🚫
            </h1>
            <p class="text-xs sm:text-sm text-slate-300 font-medium max-w-md mx-auto leading-relaxed">
                Halaman admin panel ini khusus diawasi ketat dan <strong class="text-rose-400">HARAM DIKUNJUNGI PUBLIK</strong>. IP lu bukan IP resmi Administrator TapVote AI!
            </p>
        </div>

        <!-- Technical Forensic Badge -->
        <div class="p-4 sm:p-5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md text-left space-y-2 font-mono text-xs shadow-xl">
            <div class="flex items-center justify-between text-slate-400 border-b border-white/10 pb-2">
                <span>INTRUDER_IP_ADDRESS:</span>
                <strong class="text-rose-400 font-black text-sm">{{ $clientIp ?? request()->ip() }}</strong>
            </div>
            <div class="flex items-center justify-between text-slate-400 border-b border-white/10 pb-2">
                <span>INCIDENT_TIME:</span>
                <span class="text-slate-200">{{ now()->format('Y-m-d H:i:s') }} WIB</span>
            </div>
            <div class="flex items-center justify-between text-slate-400">
                <span>STATUS_LOG:</span>
                <span class="text-amber-400 font-bold">RECORDED & REPORTED TO ADMIN</span>
            </div>
        </div>

        <!-- Back to Home Button -->
        <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a 
                href="{{ url('/') }}" 
                class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-gradient-to-r from-rose-600 via-rose-500 to-amber-600 hover:from-rose-500 hover:to-amber-500 text-white font-black text-sm shadow-xl hover:scale-105 active:scale-95 transition-all cursor-pointer flex items-center justify-center space-x-2"
            >
                <span>🏃‍♂️ Balik ke Beranda Umum Sekarang</span>
            </a>
            <a 
                href="{{ url('/voter') }}" 
                class="w-full sm:w-auto px-6 py-3.5 rounded-2xl bg-white/10 hover:bg-white/15 text-white font-bold text-xs border border-white/15 transition cursor-pointer flex items-center justify-center space-x-2"
            >
                <span>🗳️ Ke Bilik Suara</span>
            </a>
        </div>

        <p class="text-[11px] text-slate-500 font-mono">
            IP Firewall Protection • TapVote AI Sovereign Security Core
        </p>

    </div>

    <script>
        function playMockSound() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(350, ctx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(150, ctx.currentTime + 0.35);
                gain.gain.setValueAtTime(0.2, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.35);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.36);
            } catch(e) {}
        }
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(playMockSound, 200);
        });
    </script>
</body>
</html>
