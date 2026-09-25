@extends('layouts.admin')

@section('title', 'Undian Doorprize Pemilih')

@section('content')
<div class="space-y-8">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">Modul Doorprize</span>
                <h2 class="text-2xl font-extrabold text-slate-900">Undian Doorprize Anggota</h2>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Undian acak khusus anggota yang telah menunaikan hak suara sah (Pilih = T).</p>
        </div>

        <div class="flex items-center space-x-2.5 self-start sm:self-auto">
            <span class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold border border-slate-200">
                Pool Sah: <strong class="text-slate-900 font-mono">{{ $totalEligible }}</strong> Anggota
            </span>
        </div>
    </div>

    <!-- PANGGUNG MESIN UNDIAN DIGITAL (10 DETIK DRUMROLL SUSPENSE) -->
    <div class="p-6 sm:p-10 rounded-3xl bg-gradient-to-br from-amber-500/10 via-white to-amber-500/5 border-2 border-amber-300 shadow-md relative overflow-hidden text-center">
        <!-- Background decorative ambient circles -->
        <div class="absolute -top-16 -right-16 w-56 h-56 rounded-full bg-amber-200/40 blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 w-56 h-56 rounded-full bg-yellow-200/40 blur-2xl pointer-events-none"></div>

        <div class="max-w-xl mx-auto relative z-10">

            <!-- STATE 1: AWAL RENDER (KOSONG, BELUM DIUNDI) -->
            <div id="doorprize-stage-initial" class="{{ $totalEligible > 0 ? '' : 'hidden' }}">
                <div class="w-20 h-20 mx-auto rounded-3xl bg-amber-100 border-2 border-amber-300 text-amber-700 flex items-center justify-center text-4xl mb-4 shadow-sm animate-bounce">
                    🎁
                </div>
                <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-amber-100 text-amber-900 border border-amber-300 mb-2">
                    Siap Memulai Undian
                </span>
                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mb-2">Panggung Doorprize Anggota</h3>
                <p class="text-xs sm:text-sm text-slate-600 mb-6">
                    Tekan tombol di bawah untuk memulai undian digital selama 10 detik dengan efek drumroll bertegangan tinggi.
                </p>

                <button 
                    id="btn-start-draw" 
                    type="button" 
                    onclick="start10SecDraw()"
                    class="px-8 py-4 rounded-2xl bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-slate-950 font-black text-base sm:text-lg shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 transition-all cursor-pointer inline-flex items-center space-x-3"
                >
                    <span class="text-2xl">🎲</span>
                    <span>Mulai Putar Undian (10 Detik)</span>
                </button>
            </div>

            <!-- STATE 2: ANIMASI SPINNING 10 DETIK (ROULETTE DRUMROLL) -->
            <div id="doorprize-stage-spinning" class="hidden space-y-5">
                <div class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full bg-rose-100 text-rose-800 border border-rose-300 text-xs font-black animate-pulse">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-600 animate-ping"></span>
                    <span>SEDANG MENGUNDI... DRUM ROLL BERPUTAR!</span>
                </div>

                <!-- Big Countdown Display -->
                <div>
                    <span class="text-xs uppercase font-extrabold tracking-widest text-slate-400 block mb-1">Waktu Tersisa</span>
                    <span id="draw-countdown" class="text-5xl sm:text-6xl font-black text-amber-600 font-mono tracking-tight">10.0s</span>
                </div>

                <!-- 10-Second Progress Bar -->
                <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden shadow-inner">
                    <div id="draw-progress-bar" class="bg-gradient-to-r from-amber-500 to-rose-500 h-full w-0 transition-all duration-100"></div>
                </div>

                <!-- Rapid Slot Machine Card -->
                <div class="p-6 rounded-2xl bg-white border-2 border-amber-400 shadow-xl min-h-[140px] flex flex-col justify-center items-center">
                    <span class="text-[11px] font-bold text-amber-600 uppercase tracking-widest block mb-1">Mencari Calon Pemenang...</span>
                    <h4 id="slot-name" class="text-2xl sm:text-3xl font-black text-slate-900 transition-all">Memuat Daftar...</h4>
                    <p id="slot-dept" class="text-sm font-bold text-slate-600 mt-1 font-mono">Bagian: -</p>
                    <span id="slot-nik" class="text-xs text-slate-400 font-mono mt-0.5">NIK: -</span>
                </div>
            </div>

            <!-- STATE 3: PEMENANG TERPILIH (REVEAL WITH CONFETTI & CONGRATS) -->
            <div id="doorprize-stage-winner" class="hidden space-y-4">
                <div class="inline-flex items-center space-x-1.5 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-amber-200 text-amber-900 border border-amber-300 shadow-xs">
                    <span>🏆</span>
                    <span>SELAMAT! PEMENANG DOORPRIZE RESMI</span>
                </div>

                <div class="p-6 sm:p-8 rounded-3xl bg-white border-3 border-amber-400 shadow-2xl relative overflow-hidden">
                    <div class="w-20 h-20 mx-auto rounded-3xl bg-amber-500 text-white flex items-center justify-center text-4xl mb-3 shadow-md">
                        🎉
                    </div>

                    <h3 id="winner-name" class="text-3xl sm:text-4xl font-black text-slate-900 mb-1">Nama Pemenang</h3>
                    <p id="winner-meta" class="text-base text-amber-800 font-bold mb-3 font-mono">NIK: - • Bagian: -</p>

                    <div class="inline-flex items-center px-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 font-medium">
                        <span id="winner-time">Waktu Memilih: -</span>
                    </div>
                </div>

                <div class="pt-2">
                    <button 
                        type="button" 
                        onclick="resetDraw()"
                        class="px-6 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs sm:text-sm shadow-md transition cursor-pointer inline-flex items-center space-x-2"
                    >
                        <span>↻ Undi Pemenang Lainnya</span>
                    </button>
                </div>
            </div>

            <!-- EMPTY POOL STATE -->
            @if($totalEligible === 0)
                <div class="p-6 text-center text-slate-400">
                    <p class="font-medium text-sm">Belum ada anggota yang memberikan suara (Pool undian masih kosong).</p>
                </div>
            @endif

        </div>
    </div>

    <!-- Eligible Pool Table -->
    <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-2xs">
        <h3 class="text-base font-bold text-slate-900 mb-4">Daftar Anggota Berhak Undian (Pool: {{ $eligibleVoters->total() }} Pemilih)</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                        <th class="py-3 px-3">NIK</th>
                        <th class="py-3 px-3">Nama Anggota</th>
                        <th class="py-3 px-3">Departemen</th>
                        <th class="py-3 px-3 text-right">Waktu Memilih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($eligibleVoters as $v)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-3 font-mono text-slate-700 font-bold">{{ $v->nik }}</td>
                            <td class="py-3 px-3 font-bold text-slate-900">{{ $v->nama }}</td>
                            <td class="py-3 px-3 text-slate-600">{{ $v->dept }}</td>
                            <td class="py-3 px-3 text-right font-mono text-slate-500">{{ $v->voted_at ? $v->voted_at->format('H:i:s d/m/Y') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-400">Belum ada pemilih yang sah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-200">
            {{ $eligibleVoters->links() }}
        </div>
    </div>

</div>

<!-- Client-side Lottery Engine with Web Audio Synthesizer -->
<script>
    const eligiblePool = @json($eligibleList ?? []);
    let isDrawing = false;
    let slotInterval = null;
    let countdownInterval = null;

    function start10SecDraw() {
        if (isDrawing || !eligiblePool || eligiblePool.length === 0) {
            alert('Tidak ada anggota di dalam pool undian.');
            return;
        }

        isDrawing = true;

        // Toggle Views
        document.getElementById('doorprize-stage-initial').classList.add('hidden');
        document.getElementById('doorprize-stage-winner').classList.add('hidden');
        document.getElementById('doorprize-stage-spinning').classList.remove('hidden');

        // Play 10-Second Drumroll Audio Synthesizer
        if (window.SoundEffects) {
            window.SoundEffects.drumRoll(10.0);
        }

        const durationMs = 10000; // 10.0 seconds exact
        const startTime = Date.now();
        const endTime = startTime + durationMs;

        // Slot Machine Name Scroller (rapidly rotates random names)
        const slotName = document.getElementById('slot-name');
        const slotDept = document.getElementById('slot-dept');
        const slotNik = document.getElementById('slot-nik');
        const countdownEl = document.getElementById('draw-countdown');
        const progressBar = document.getElementById('draw-progress-bar');

        let currentIndex = 0;
        slotInterval = setInterval(() => {
            const randomCandidate = eligiblePool[Math.floor(Math.random() * eligiblePool.length)];
            slotName.innerText = randomCandidate.nama;
            slotDept.innerText = 'Bagian: ' + randomCandidate.dept;
            slotNik.innerText = 'NIK: ' + randomCandidate.nik;
        }, 40);

        // Countdown Timer
        countdownInterval = setInterval(() => {
            const now = Date.now();
            const remainingMs = Math.max(0, endTime - now);
            const secondsLeft = (remainingMs / 1000).toFixed(1);
            countdownEl.innerText = secondsLeft + 's';

            const elapsedMs = durationMs - remainingMs;
            const progressPct = Math.min(100, (elapsedMs / durationMs) * 100);
            progressBar.style.width = progressPct + '%';

            if (remainingMs <= 0) {
                clearInterval(countdownInterval);
                clearInterval(slotInterval);
                finishDraw();
            }
        }, 50);
    }

    function finishDraw() {
        isDrawing = false;

        // Pick genuine random winner from pool
        const winner = eligiblePool[Math.floor(Math.random() * eligiblePool.length)];

        // Stop drumroll, trigger crash cymbal & 3-second congrats chime
        if (window.SoundEffects) {
            window.SoundEffects.stopDrumRoll();
            window.SoundEffects.crash();
            setTimeout(() => {
                window.SoundEffects.success();
            }, 120);
        }

        // Fire fireworks confetti
        if (window.confetti) {
            window.confetti({
                particleCount: 160,
                spread: 90,
                origin: { y: 0.6 }
            });
            setTimeout(() => {
                window.confetti({
                    particleCount: 100,
                    angle: 60,
                    spread: 55,
                    origin: { x: 0 }
                });
                window.confetti({
                    particleCount: 100,
                    angle: 120,
                    spread: 55,
                    origin: { x: 1 }
                });
            }, 300);
        }

        // Fill Winner Card
        document.getElementById('winner-name').innerText = winner.nama;
        document.getElementById('winner-meta').innerText = 'NIK: ' + winner.nik + ' • Bagian: ' + winner.dept;
        document.getElementById('winner-time').innerText = 'Waktu Memilih: ' + (winner.waktu || '-');

        // Switch to Winner View
        document.getElementById('doorprize-stage-spinning').classList.add('hidden');
        document.getElementById('doorprize-stage-winner').classList.remove('hidden');
    }

    function resetDraw() {
        document.getElementById('doorprize-stage-winner').classList.add('hidden');
        document.getElementById('doorprize-stage-spinning').classList.add('hidden');
        document.getElementById('doorprize-stage-initial').classList.remove('hidden');
    }
</script>
@endsection
