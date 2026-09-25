<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panggung Doorprize - TapVote AI</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-slate-950 text-white font-sans antialiased overflow-x-hidden flex flex-col justify-between selection:bg-amber-500 selection:text-black">

    <!-- Ambient Glowing Background Elements -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-40 -left-40 w-96 h-96 rounded-full bg-amber-500/15 blur-3xl animate-pulse" style="animation-duration: 6s;"></div>
        <div class="absolute top-1/3 -right-40 w-[500px] h-[500px] rounded-full bg-yellow-500/10 blur-3xl animate-pulse" style="animation-duration: 8s;"></div>
        <div class="absolute -bottom-40 left-1/3 w-[600px] h-[600px] rounded-full bg-indigo-500/10 blur-3xl"></div>
    </div>

    <!-- Top Cinema Stage App Bar -->
    <header class="relative z-10 px-6 sm:px-12 py-5 flex items-center justify-between border-b border-white/10 backdrop-blur-md bg-slate-950/60">
        <div class="flex items-center space-x-3.5">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-amber-500 to-yellow-300 text-slate-950 flex items-center justify-center font-black text-xl shadow-lg shadow-amber-500/20">
                🎁
            </div>
            <div>
                <h1 class="text-lg sm:text-xl font-black text-white tracking-tight">TapVote AI • Panggung Doorprize</h1>
                <p class="text-[11px] text-amber-300/80 font-bold uppercase tracking-wider">Grand Lottery Stage & Award Presentation</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <!-- Active Reward Picker Dropdown for Stage Operator -->
            <div class="relative">
                <select 
                    id="stage-prize-select" 
                    onchange="changeStagePrize(this.value)" 
                    class="px-4 py-2 rounded-xl bg-slate-900 border border-amber-500/40 text-amber-300 text-xs sm:text-sm font-black focus:outline-none focus:ring-2 focus:ring-amber-400 cursor-pointer"
                >
                    @foreach($doorprizes as $d)
                        <option value="{{ $d->id }}" data-title="{{ $d->title }}" data-slots="{{ $d->remaining_slots }}">
                            {{ $d->title }} ({{ $d->remaining_slots }} Sisa)
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Fullscreen Button -->
            <button 
                onclick="toggleFullscreen()" 
                type="button" 
                class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/15 text-xs font-bold transition flex items-center space-x-1.5 cursor-pointer"
                title="Layar Penuh (F)"
            >
                <span id="fs-icon">⛶</span>
                <span class="hidden sm:inline">Layar Penuh</span>
            </button>
        </div>
    </header>

    <!-- Main Center Stage -->
    <main class="relative z-10 flex-1 flex flex-col items-center justify-center p-6 sm:p-12 text-center max-w-4xl mx-auto w-full">

        <!-- Selected Reward Hero Card -->
        <div class="mb-8">
            <span class="px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest bg-amber-500/20 text-amber-300 border border-amber-400/40 inline-block mb-3">
                ★ Sedang Mengundi Hadiah ★
            </span>
            <h2 id="stage-active-title" class="text-3xl sm:text-5xl lg:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-amber-100 to-amber-400 tracking-tight leading-tight">
                {{ $doorprizes->first()?->title ?? 'Pilih Hadiah' }}
            </h2>
            <p id="stage-active-meta" class="text-xs sm:text-sm text-slate-400 mt-2 font-medium">
                Tersedia <strong class="text-amber-300 font-mono">{{ $doorprizes->first()?->remaining_slots ?? 0 }}</strong> unit untuk anggota yang sah
            </p>
        </div>

        <!-- STATE 1: INITIAL READY STAGE -->
        <div id="stage-initial" class="space-y-8 w-full max-w-lg">
            <!-- Animated SVG Centerpiece -->
            <div class="relative w-40 h-40 sm:w-48 sm:h-48 mx-auto flex items-center justify-center">
                <svg class="w-full h-full text-amber-400 drop-shadow-[0_0_35px_rgba(245,158,11,0.6)]" viewBox="0 0 100 100" fill="none">
                    <circle cx="50" cy="50" r="46" stroke="currentColor" stroke-width="2" stroke-dasharray="8 6" class="animate-spin" style="animation-duration: 25s;" />
                    <circle cx="50" cy="50" r="38" stroke="rgba(251,191,36,0.3)" stroke-width="2" />
                    <circle cx="50" cy="50" r="30" fill="url(#heroGrad)" />
                    <polygon points="50,28 55,42 70,42 58,51 63,65 50,56 37,65 42,51 30,42 45,42" fill="#0f172a" />
                    <defs>
                        <linearGradient id="heroGrad" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#f59e0b" />
                            <stop offset="100%" stop-color="#fef08a" />
                        </linearGradient>
                    </defs>
                </svg>
            </div>

            <div>
                <button 
                    id="stage-draw-btn"
                    type="button" 
                    onclick="triggerStageDraw()" 
                    class="px-10 py-5 rounded-3xl bg-gradient-to-r from-amber-400 via-amber-500 to-yellow-500 hover:from-amber-300 hover:to-yellow-400 text-slate-950 font-black text-lg sm:text-2xl shadow-2xl hover:shadow-[0_0_50px_rgba(245,158,11,0.6)] hover:scale-105 active:scale-95 transition-all cursor-pointer inline-flex items-center space-x-3.5"
                >
                    <span class="text-3xl">🎰</span>
                    <span>PUTAR UNDIAN SEKARANG</span>
                </button>
                <span class="block text-xs text-slate-500 mt-3 font-mono">Tekan [Spasi] pada keyboard untuk memulai</span>
            </div>
        </div>

        <!-- STATE 2: HIGH-SPEED SVG ANIMATED SPINNING -->
        <div id="stage-spinning" class="hidden space-y-8 w-full max-w-xl">
            <!-- High Speed Rotating SVG Gears -->
            <div class="relative w-44 h-44 sm:w-52 sm:h-52 mx-auto flex items-center justify-center">
                <svg class="w-full h-full text-amber-400 drop-shadow-[0_0_40px_rgba(245,158,11,0.9)]" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="47" stroke="currentColor" stroke-width="5" stroke-linecap="round" stroke-dasharray="35 20" class="animate-spin" style="animation-duration: 0.5s;" />
                    <circle cx="50" cy="50" r="36" stroke="#38bdf8" stroke-width="4" stroke-dasharray="25 25" class="animate-spin" style="animation-duration: 0.8s; animation-direction: reverse;" />
                    <circle cx="50" cy="50" r="24" fill="#020617" stroke="#f59e0b" stroke-width="3" />
                    <polygon points="50,30 55,43 68,43 57,52 61,64 50,57 39,64 43,52 32,43 45,43" fill="#fbbf24" class="animate-ping" style="animation-duration: 0.4s;" />
                </svg>
            </div>

            <div class="inline-flex items-center space-x-2 px-5 py-2 rounded-full bg-amber-500/20 text-amber-300 border border-amber-400/40 text-sm font-black animate-pulse">
                <span class="w-3 h-3 rounded-full bg-amber-400 animate-ping"></span>
                <span>SILINDER DIGITAL MENGUNDI NAMA ANGGOTA...</span>
            </div>

            <!-- Big Screen Dynamic Marquee Slot -->
            <div class="p-8 sm:p-10 rounded-3xl bg-slate-900/90 border-2 border-amber-400/70 shadow-[0_0_50px_rgba(245,158,11,0.3)] backdrop-blur-md min-h-[160px] flex flex-col justify-center items-center">
                <h3 id="stage-slot-name" class="text-3xl sm:text-5xl font-black text-white tracking-tight">Memutar...</h3>
                <p id="stage-slot-dept" class="text-base sm:text-xl font-bold text-amber-300 mt-2 font-mono">Bagian: -</p>
                <span id="stage-slot-nik" class="text-xs sm:text-sm text-slate-400 font-mono mt-1">NIK: -</span>
            </div>
        </div>

        <!-- STATE 3: BIG SCREEN WINNER REVEAL -->
        <div id="stage-winner" class="hidden space-y-6 w-full max-w-2xl">
            <div class="inline-flex items-center space-x-2 px-6 py-2.5 rounded-full text-sm sm:text-base font-black uppercase tracking-widest bg-gradient-to-r from-amber-400 to-yellow-300 text-slate-950 shadow-2xl">
                <span>🏆</span>
                <span>SELAMAT KEPADA PEMENANG!</span>
            </div>

            <div class="p-8 sm:p-12 rounded-3xl bg-gradient-to-b from-slate-900 via-slate-900 to-indigo-950/90 border-3 border-amber-400 shadow-[0_0_80px_rgba(245,158,11,0.5)] backdrop-blur-md text-center relative overflow-hidden">
                <div class="w-24 h-24 sm:w-28 sm:h-28 mx-auto rounded-3xl bg-gradient-to-tr from-amber-400 to-yellow-300 text-slate-950 flex items-center justify-center text-5xl mb-4 shadow-xl">
                    🎉
                </div>

                <span id="stage-winner-prize-pill" class="px-4 py-1.5 rounded-full text-xs sm:text-sm font-extrabold bg-amber-500/20 text-amber-300 border border-amber-400/40 inline-block mb-3">
                    Hadiah: -
                </span>

                <h3 id="stage-winner-name" class="text-3xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight leading-tight mb-2">
                    Nama Pemenang
                </h3>

                <p id="stage-winner-meta" class="text-lg sm:text-2xl text-amber-300 font-extrabold font-mono mb-4">
                    NIK: - • Bagian: -
                </p>

                <div class="inline-flex items-center px-5 py-2 rounded-2xl bg-white/10 border border-white/15 text-xs sm:text-sm text-slate-300 font-medium">
                    <span id="stage-winner-time">Tercatat Resmi: -</span>
                </div>
            </div>

            <div class="pt-4 flex justify-center gap-4">
                <button 
                    type="button" 
                    onclick="resetStageToInitial()"
                    class="px-8 py-4 rounded-2xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-black text-sm sm:text-base shadow-xl transition cursor-pointer inline-flex items-center space-x-2"
                >
                    <span>↻ Undi Hadiah Selanjutnya</span>
                </button>
            </div>
        </div>

    </main>

    <!-- Bottom Previous Winners Live Ticker Marquee -->
    <footer class="relative z-10 py-3.5 px-6 bg-slate-950/80 border-t border-white/10 backdrop-blur-md">
        <div class="flex items-center space-x-4">
            <span class="text-xs font-black uppercase tracking-wider text-amber-400 shrink-0 flex items-center space-x-1.5">
                <span>✦</span>
                <span>Pemenang Terundi:</span>
            </span>
            <div class="overflow-hidden whitespace-nowrap flex-1 text-xs text-slate-300" id="stage-marquee-container">
                <div class="inline-block animate-marquee" id="stage-marquee-content">
                    @forelse($winners as $w)
                        <span class="mx-4 font-medium">
                            <strong class="text-white">{{ $w->pemilih?->nama ?? $w->nik }}</strong> 
                            ({{ $w->pemilih?->dept ?? '-' }}) 
                            → <span class="text-amber-300 font-bold">{{ $w->doorprize?->title }}</span>
                        </span>
                        •
                    @empty
                        <span class="text-slate-500">Belum ada pemenang yang diundi hari ini.</span>
                    @endforelse
                </div>
            </div>
        </div>
    </footer>

    <script>
        const eligiblePool = @json($eligibleList);
        let activePrizeId = {{ $doorprizes->first()?->id ?? 'null' }};
        let activePrizeTitle = "{{ addslashes($doorprizes->first()?->title ?? '') }}";
        let activePrizeSlots = {{ $doorprizes->first()?->remaining_slots ?? 0 }};
        let isDrawing = false;
        let slotInterval = null;

        function changeStagePrize(id) {
            if (isDrawing) return;
            const sel = document.getElementById('stage-prize-select');
            const opt = sel.options[sel.selectedIndex];
            activePrizeId = id;
            activePrizeTitle = opt.dataset.title;
            activePrizeSlots = parseInt(opt.dataset.slots || 0);

            document.getElementById('stage-active-title').innerText = activePrizeTitle;
            document.getElementById('stage-active-meta').innerHTML = `Tersedia <strong class="text-amber-300 font-mono">${activePrizeSlots}</strong> unit untuk anggota yang sah`;
        }

        // Fullscreen toggle helper
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {});
                document.getElementById('fs-icon').innerText = '✕';
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                    document.getElementById('fs-icon').innerText = '⛶';
                }
            }
        }

        // Keyboard hotkeys
        document.addEventListener('keydown', (e) => {
            if (e.code === 'Space' && !isDrawing) {
                e.preventDefault();
                triggerStageDraw();
            } else if (e.key === 'f' || e.key === 'F') {
                toggleFullscreen();
            }
        });

        function playSlotTick() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(600 + Math.random() * 300, ctx.currentTime);
                gain.gain.setValueAtTime(0.08, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.05);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.06);
            } catch(e) {}
        }

        function triggerStageDraw() {
            if (isDrawing) return;
            if (!activePrizeId) {
                alert('Pilih hadiah terlebih dahulu.');
                return;
            }
            if (activePrizeSlots <= 0) {
                alert('Kuota hadiah ini sudah habis!');
                return;
            }
            if (!eligiblePool || eligiblePool.length === 0) {
                alert('Pool pemilih sah masih kosong.');
                return;
            }

            isDrawing = true;

            const stageInitial = document.getElementById('stage-initial');
            const stageSpinning = document.getElementById('stage-spinning');
            const stageWinner = document.getElementById('stage-winner');

            stageInitial.classList.add('hidden');
            stageWinner.classList.add('hidden');
            stageSpinning.classList.remove('hidden');

            let tickCounter = 0;
            slotInterval = setInterval(() => {
                const randomPick = eligiblePool[Math.floor(Math.random() * eligiblePool.length)];
                document.getElementById('stage-slot-name').innerText = randomPick.nama;
                document.getElementById('stage-slot-dept').innerText = 'Bagian: ' + randomPick.dept;
                document.getElementById('stage-slot-nik').innerText = 'NIK: ' + randomPick.nik;
                tickCounter++;
                if (tickCounter % 3 === 0) playSlotTick();
            }, 60);

            // Send draw request to backend
            fetch("{{ route('admin.reports.doorprize.draw') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ doorprize_id: activePrizeId })
            })
            .then(res => res.json())
            .then(data => {
                setTimeout(() => {
                    clearInterval(slotInterval);

                    if (!data.success) {
                        alert(data.message || 'Gagal mengundi hadiah.');
                        resetStageToInitial();
                        return;
                    }

                    document.getElementById('stage-slot-name').innerText = data.winner.nama;
                    document.getElementById('stage-slot-dept').innerText = 'Bagian: ' + data.winner.dept;
                    document.getElementById('stage-slot-nik').innerText = 'NIK: ' + data.winner.nik;

                    setTimeout(() => {
                        revealStageWinner(data);
                    }, 700);
                }, 3500);
            })
            .catch(err => {
                clearInterval(slotInterval);
                console.error(err);
                alert('Terjadi kesalahan jaringan.');
                resetStageToInitial();
            });
        }

        function revealStageWinner(data) {
            const stageSpinning = document.getElementById('stage-spinning');
            const stageWinner = document.getElementById('stage-winner');

            stageSpinning.classList.add('hidden');
            stageWinner.classList.remove('hidden');

            document.getElementById('stage-winner-name').innerText = data.winner.nama;
            document.getElementById('stage-winner-meta').innerText = `NIK: ${data.winner.nik} • Bagian: ${data.winner.dept}`;
            document.getElementById('stage-winner-time').innerText = `Tercatat Sah: ${data.winner.won_at} WIB`;
            document.getElementById('stage-winner-prize-pill').innerText = `Hadiah: ${data.doorprize.title}`;

            // Update local remaining slots
            activePrizeSlots = data.doorprize.remaining_slots;
            document.getElementById('stage-active-meta').innerHTML = `Tersedia <strong class="text-amber-300 font-mono">${activePrizeSlots}</strong> unit untuk anggota yang sah`;

            // Append to bottom marquee
            const marquee = document.getElementById('stage-marquee-content');
            if (marquee) {
                const item = document.createElement('span');
                item.className = 'mx-4 font-bold text-amber-300';
                item.innerHTML = `★ BARU TERPILIH: <strong class="text-white">${data.winner.nama}</strong> (${data.winner.dept}) → ${data.doorprize.title} •`;
                marquee.prepend(item);
            }

            // Sound and celebratory fireworks
            if (window.SoundEffects && window.SoundEffects.congrats) window.SoundEffects.congrats();
            if (window.confetti) {
                window.confetti({
                    particleCount: 80,
                    spread: 90,
                    origin: { y: 0.6 },
                    zIndex: 90
                });
            }

            isDrawing = false;
        }

        function resetStageToInitial() {
            isDrawing = false;
            if (slotInterval) clearInterval(slotInterval);
            document.getElementById('stage-spinning').classList.add('hidden');
            document.getElementById('stage-winner').classList.add('hidden');
            document.getElementById('stage-initial').classList.remove('hidden');
        }
    </script>
</body>
</html>
