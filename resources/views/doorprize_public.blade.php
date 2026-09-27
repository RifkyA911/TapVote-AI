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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

    <style>
        .stage-glow-pulse {
            animation: stage-glow 2.5s ease-in-out infinite;
        }
        @keyframes stage-glow {
            0%, 100% { box-shadow: 0 0 30px rgba(245,158,11,0.2), 0 0 60px rgba(245,158,11,0.1); }
            50% { box-shadow: 0 0 60px rgba(245,158,11,0.4), 0 0 120px rgba(245,158,11,0.2); }
        }
        .stage-reel-glow {
            animation: stage-reel-pulse 1s ease-in-out infinite;
        }
        @keyframes stage-reel-pulse {
            0%, 100% { box-shadow: 0 0 30px rgba(245,158,11,0.2); }
            50% { box-shadow: 0 0 60px rgba(245,158,11,0.5); }
        }
        .stage-winner-enter {
            animation: stage-winner-slide 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        @keyframes stage-winner-slide {
            0% { opacity: 0; transform: translateY(50px) scale(0.9); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        .stage-winner-name-glow {
            animation: name-glow 2s ease-in-out infinite;
        }
        @keyframes name-glow {
            0%, 100% { text-shadow: 0 0 20px rgba(245,158,11,0.3); }
            50% { text-shadow: 0 0 40px rgba(245,158,11,0.6), 0 0 80px rgba(245,158,11,0.3); }
        }
        .animate-marquee {
            animation: marquee 30s linear infinite;
        }
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
    </style>
</head>
<body class="min-h-full bg-slate-950 text-white font-sans antialiased overflow-x-hidden flex flex-col justify-between selection:bg-amber-500 selection:text-black">

    {{-- Three.js Full Screen 3D Canvas --}}
    <div id="stage-3d-canvas" class="fixed inset-0 z-0 pointer-events-none"></div>

    {{-- Ambient Glowing Background --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-40 -left-40 w-96 h-96 rounded-full bg-amber-500/10 blur-3xl animate-pulse" style="animation-duration: 6s;"></div>
        <div class="absolute top-1/3 -right-40 w-[500px] h-[500px] rounded-full bg-yellow-500/8 blur-3xl animate-pulse" style="animation-duration: 8s;"></div>
        <div class="absolute -bottom-40 left-1/3 w-[600px] h-[600px] rounded-full bg-indigo-500/8 blur-3xl"></div>
    </div>

    {{-- Top Cinema Stage App Bar --}}
    <header class="relative z-20 px-6 sm:px-12 py-5 flex items-center justify-between border-b border-white/10 backdrop-blur-md bg-slate-950/60">
        <div class="flex items-center space-x-3.5">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-amber-500 to-yellow-300 text-slate-950 flex items-center justify-center font-black text-xl shadow-lg shadow-amber-500/20">
                🎁
            </div>
            <div>
                <h1 class="text-lg sm:text-xl font-black text-white tracking-tight">TapVote AI • {{ __('Panggung Doorprize') }}</h1>
                <p class="text-[11px] text-amber-300/80 font-bold uppercase tracking-wider">Grand Lottery Stage & Award Presentation</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="relative">
                <select 
                    id="stage-prize-select" 
                    onchange="changeStagePrize(this.value)" 
                    class="px-4 py-2 rounded-xl bg-slate-900 border border-amber-500/40 text-amber-300 text-xs sm:text-sm font-black focus:outline-none focus:ring-2 focus:ring-amber-400 cursor-pointer"
                >
                    @foreach($doorprizes as $d)
                        <option value="{{ $d->id }}" data-title="{{ $d->title }}" data-slots="{{ $d->remaining_slots }}">
                            {{ $d->title }} ({{ $d->remaining_slots }} {{ __('Sisa') }})
                        </option>
                    @endforeach
                </select>
            </div>

            <button 
                onclick="toggleFullscreen()" 
                type="button" 
                class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/15 text-xs font-bold transition flex items-center space-x-1.5 cursor-pointer"
                title="{{ __('Layar Penuh (F)') }}"
            >
                <span id="fs-icon">⛶</span>
                <span class="hidden sm:inline">{{ __('Layar Penuh') }}</span>
            </button>
        </div>
    </header>

    {{-- Main Center Stage --}}
    <main class="relative z-10 flex-1 flex flex-col items-center justify-center p-6 sm:p-12 text-center max-w-4xl mx-auto w-full">

        {{-- Selected Reward Hero Card --}}
        <div class="mb-8">
            <span class="px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest bg-amber-500/20 text-amber-300 border border-amber-400/40 inline-block mb-3">
                ★ {{ __('Sedang Mengundi Hadiah') }} ★
            </span>
            <h2 id="stage-active-title" class="text-3xl sm:text-5xl lg:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-amber-100 to-amber-400 tracking-tight leading-tight">
                {{ $doorprizes->first()?->title ?? __('Pilih Hadiah') }}
            </h2>
            <p id="stage-active-meta" class="text-xs sm:text-sm text-slate-400 mt-2 font-medium">
                {{ __('Tersedia') }} <strong class="text-amber-300 font-mono">{{ $doorprizes->first()?->remaining_slots ?? 0 }}</strong> {{ __('unit untuk anggota yang sah') }}
            </p>
        </div>

        {{-- STATE 1: INITIAL READY STAGE --}}
        <div id="stage-initial" class="space-y-8 w-full max-w-lg">
            <div class="h-48 sm:h-56"></div> {{-- spacer for 3D canvas --}}

            <div>
                <button 
                    id="stage-draw-btn"
                    type="button" 
                    onclick="triggerStageDraw()" 
                    class="px-10 py-5 rounded-3xl bg-gradient-to-r from-amber-400 via-amber-500 to-yellow-500 hover:from-amber-300 hover:to-yellow-400 text-slate-950 font-black text-lg sm:text-2xl shadow-2xl hover:shadow-[0_0_50px_rgba(245,158,11,0.6)] hover:scale-105 active:scale-95 transition-all cursor-pointer inline-flex items-center space-x-3.5 stage-glow-pulse"
                >
                    <span class="text-3xl">🎰</span>
                    <span>{{ __('PUTAR UNDIAN SEKARANG') }}</span>
                </button>
                <span class="block text-xs text-slate-500 mt-3 font-mono">{{ __('Tekan [Spasi] pada keyboard untuk memulai') }}</span>
            </div>
        </div>

        {{-- STATE 2: HIGH-SPEED 3D ANIMATED SPINNING --}}
        <div id="stage-spinning" class="hidden space-y-8 w-full max-w-xl">
            <div class="h-48 sm:h-56"></div> {{-- spacer for 3D canvas --}}

            <div class="inline-flex items-center space-x-2 px-5 py-2 rounded-full bg-amber-500/20 text-amber-300 border border-amber-400/40 text-sm font-black animate-pulse backdrop-blur-sm">
                <span class="w-3 h-3 rounded-full bg-amber-400 animate-ping"></span>
                <span>{{ __('SILINDER DIGITAL MENGUNDI NAMA ANGGOTA...') }}</span>
            </div>

            {{-- Big Screen Dynamic Marquee Slot --}}
            <div class="p-8 sm:p-10 rounded-3xl bg-slate-900/90 border-2 border-amber-400/70 shadow-[0_0_50px_rgba(245,158,11,0.3)] backdrop-blur-md min-h-[160px] flex flex-col justify-center items-center stage-reel-glow">
                <h3 id="stage-slot-name" class="text-3xl sm:text-5xl font-black text-white tracking-tight">{{ __('Memutar...') }}</h3>
                <p id="stage-slot-dept" class="text-base sm:text-xl font-bold text-amber-300 mt-2 font-mono">{{ __('Bagian:') }} -</p>
                <span id="stage-slot-nik" class="text-xs sm:text-sm text-slate-400 font-mono mt-1">{{ __('NIK:') }} -</span>
            </div>
        </div>

        {{-- STATE 3: BIG SCREEN WINNER REVEAL --}}
        <div id="stage-winner" class="hidden space-y-6 w-full max-w-2xl">
            <div class="h-40 sm:h-48"></div> {{-- spacer for 3D canvas --}}

            <div class="inline-flex items-center space-x-2 px-6 py-2.5 rounded-full text-sm sm:text-base font-black uppercase tracking-widest bg-gradient-to-r from-amber-400 to-yellow-300 text-slate-950 shadow-2xl">
                <span>🏆</span>
                <span>{{ __('SELAMAT KEPADA PEMENANG!') }}</span>
            </div>

            <div class="p-8 sm:p-12 rounded-3xl bg-gradient-to-b from-slate-900 via-slate-900 to-indigo-950/90 border-3 border-amber-400 shadow-[0_0_80px_rgba(245,158,11,0.5)] backdrop-blur-md text-center relative overflow-hidden stage-winner-enter">
                <div class="w-24 h-24 sm:w-28 sm:h-28 mx-auto rounded-3xl bg-gradient-to-tr from-amber-400 to-yellow-300 text-slate-950 flex items-center justify-center text-5xl mb-4 shadow-xl">
                    🎉
                </div>

                <span id="stage-winner-prize-pill" class="px-4 py-1.5 rounded-full text-xs sm:text-sm font-extrabold bg-amber-500/20 text-amber-300 border border-amber-400/40 inline-block mb-3">
                    {{ __('Hadiah:') }} -
                </span>

                <h3 id="stage-winner-name" class="text-3xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight leading-tight mb-2 stage-winner-name-glow">
                    {{ __('Nama Pemenang') }}
                </h3>

                <p id="stage-winner-meta" class="text-lg sm:text-2xl text-amber-300 font-extrabold font-mono mb-4">
                    {{ __('NIK:') }} - • {{ __('Bagian:') }} -
                </p>

                <div class="inline-flex items-center px-5 py-2 rounded-2xl bg-white/10 border border-white/15 text-xs sm:text-sm text-slate-300 font-medium">
                    <span id="stage-winner-time">{{ __('Tercatat Resmi:') }} -</span>
                </div>
            </div>

            <div class="pt-4 flex justify-center gap-4">
                <button 
                    type="button" 
                    onclick="resetStageToInitial()"
                    class="px-8 py-4 rounded-2xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-black text-sm sm:text-base shadow-xl transition cursor-pointer inline-flex items-center space-x-2"
                >
                    <span>↻ {{ __('Undi Hadiah Selanjutnya') }}</span>
                </button>
            </div>
        </div>

    </main>

    {{-- Bottom Previous Winners Live Ticker Marquee --}}
    <footer class="relative z-10 py-3.5 px-6 bg-slate-950/80 border-t border-white/10 backdrop-blur-md">
        <div class="flex items-center space-x-4">
            <span class="text-xs font-black uppercase tracking-wider text-amber-400 shrink-0 flex items-center space-x-1.5">
                <span>✦</span>
                <span>{{ __('Pemenang Terundi:') }}</span>
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
                        <span class="text-slate-500">{{ __('Belum ada pemenang yang diundi hari ini.') }}</span>
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

        // ====== Three.js 3D Stage Scene ======
        (function() {
            const container = document.getElementById('stage-3d-canvas');
            if (!container || typeof THREE === 'undefined') return;

            let scene, camera, renderer, giftBox, particles;
            let currentMode = 'idle';
            const clock = new THREE.Clock();

            function init() {
                scene = new THREE.Scene();

                camera = new THREE.PerspectiveCamera(40, window.innerWidth / window.innerHeight, 0.1, 100);
                camera.position.set(0, 0.8, 6);
                camera.lookAt(0, 0, 0);

                renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
                renderer.setSize(window.innerWidth, window.innerHeight);
                renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
                renderer.setClearColor(0x000000, 0);
                container.appendChild(renderer.domElement);

                // Lighting
                scene.add(new THREE.AmbientLight(0xffffff, 0.4));
                const dirLight = new THREE.DirectionalLight(0xfbbf24, 1.5);
                dirLight.position.set(4, 5, 6);
                scene.add(dirLight);
                const pointLight = new THREE.PointLight(0xf59e0b, 1.0, 15);
                pointLight.position.set(-3, 3, 4);
                scene.add(pointLight);
                const backLight = new THREE.PointLight(0x6366f1, 0.4, 12);
                backLight.position.set(2, -2, -3);
                scene.add(backLight);

                // Gift box
                giftBox = new THREE.Group();

                const boxMat = new THREE.MeshPhongMaterial({
                    color: 0xf59e0b, specular: 0xfef3c7, shininess: 90,
                    emissive: 0x92400e, emissiveIntensity: 0.2
                });
                giftBox.add(new THREE.Mesh(new THREE.BoxGeometry(1.6, 1.2, 1.6), boxMat));

                const lidMat = new THREE.MeshPhongMaterial({
                    color: 0xfbbf24, specular: 0xfffbeb, shininess: 100,
                    emissive: 0x78350f, emissiveIntensity: 0.15
                });
                const lid = new THREE.Mesh(new THREE.BoxGeometry(1.8, 0.25, 1.8), lidMat);
                lid.position.y = 0.72;
                lid.name = 'lid';
                giftBox.add(lid);

                const ribbonMat = new THREE.MeshPhongMaterial({ color: 0xdc2626, specular: 0xfca5a5, shininess: 60 });
                giftBox.add(new THREE.Mesh(new THREE.BoxGeometry(0.18, 1.22, 1.62), ribbonMat));
                const ribbonH = new THREE.Mesh(new THREE.BoxGeometry(1.62, 0.18, 1.62), ribbonMat);
                ribbonH.position.y = 0.1;
                giftBox.add(ribbonH);

                const bowMat = new THREE.MeshPhongMaterial({ color: 0xdc2626, shininess: 70 });
                const bowGeo = new THREE.TorusGeometry(0.25, 0.07, 8, 16);
                const bow1 = new THREE.Mesh(bowGeo, bowMat);
                bow1.position.set(-0.18, 0.9, 0);
                bow1.rotation.y = Math.PI / 4;
                giftBox.add(bow1);
                const bow2 = new THREE.Mesh(bowGeo, bowMat);
                bow2.position.set(0.18, 0.9, 0);
                bow2.rotation.y = -Math.PI / 4;
                giftBox.add(bow2);

                scene.add(giftBox);

                // Particles
                const pCount = 80;
                const pGeo = new THREE.BufferGeometry();
                const pos = new Float32Array(pCount * 3);
                for (let i = 0; i < pCount; i++) {
                    pos[i * 3] = (Math.random() - 0.5) * 12;
                    pos[i * 3 + 1] = (Math.random() - 0.5) * 8;
                    pos[i * 3 + 2] = (Math.random() - 0.5) * 6;
                }
                pGeo.setAttribute('position', new THREE.BufferAttribute(pos, 3));
                particles = new THREE.Points(pGeo, new THREE.PointsMaterial({
                    color: 0xfbbf24, size: 0.08, transparent: true, opacity: 0.5,
                    blending: THREE.AdditiveBlending
                }));
                scene.add(particles);

                window.addEventListener('resize', () => {
                    camera.aspect = window.innerWidth / window.innerHeight;
                    camera.updateProjectionMatrix();
                    renderer.setSize(window.innerWidth, window.innerHeight);
                });

                animate();
            }

            function animate() {
                requestAnimationFrame(animate);
                const t = clock.getElapsedTime();

                if (giftBox) {
                    if (currentMode === 'idle') {
                        giftBox.rotation.y += 0.006;
                        giftBox.position.y = Math.sin(t * 1.0) * 0.2;
                        giftBox.rotation.x = Math.sin(t * 0.6) * 0.04;
                    } else if (currentMode === 'spin') {
                        giftBox.rotation.y += 0.15;
                        giftBox.rotation.x += 0.04;
                        giftBox.position.y = Math.sin(t * 4) * 0.12;
                        const s = 1 + Math.sin(t * 5) * 0.06;
                        giftBox.scale.set(s, s, s);
                    } else if (currentMode === 'reveal') {
                        giftBox.rotation.y += 0.004;
                        giftBox.position.y = Math.sin(t * 0.6) * 0.1;
                        const lid = giftBox.getObjectByName('lid');
                        if (lid && lid.rotation.x < 1.0) {
                            lid.rotation.x += 0.025;
                            lid.position.y += 0.01;
                            lid.position.z -= 0.006;
                        }
                    }
                }

                if (particles) {
                    const pos = particles.geometry.attributes.position.array;
                    const speed = currentMode === 'spin' ? 0.02 : 0.005;
                    for (let i = 0; i < pos.length; i += 3) {
                        pos[i + 1] += speed;
                        if (pos[i + 1] > 4) pos[i + 1] = -4;
                    }
                    particles.geometry.attributes.position.needsUpdate = true;
                    particles.rotation.y += 0.003;
                    particles.material.opacity = currentMode === 'spin' ? 0.9 : 0.4;
                    particles.material.size = currentMode === 'spin' ? 0.14 : 0.08;
                }

                renderer.render(scene, camera);
            }

            window.stage3D = {
                setMode(mode) {
                    currentMode = mode;
                    if (mode === 'idle' && giftBox) {
                        giftBox.scale.set(1, 1, 1);
                        giftBox.rotation.x = 0;
                        const lid = giftBox.getObjectByName('lid');
                        if (lid) { lid.rotation.x = 0; lid.position.y = 0.72; lid.position.z = 0; }
                    }
                }
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();

        // ====== Stage Logic ======
        function changeStagePrize(id) {
            if (isDrawing) return;
            const sel = document.getElementById('stage-prize-select');
            const opt = sel.options[sel.selectedIndex];
            activePrizeId = id;
            activePrizeTitle = opt.dataset.title;
            activePrizeSlots = parseInt(opt.dataset.slots || 0);

            document.getElementById('stage-active-title').innerText = activePrizeTitle;
            document.getElementById('stage-active-meta').innerHTML = `{{ __('Tersedia') }} <strong class="text-amber-300 font-mono">${activePrizeSlots}</strong> {{ __('unit untuk anggota yang sah') }}`;
        }

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
                alert('{{ __("Pilih hadiah terlebih dahulu.") }}');
                return;
            }
            if (activePrizeSlots <= 0) {
                alert('{{ __("Kuota hadiah ini sudah habis!") }}');
                return;
            }
            if (!eligiblePool || eligiblePool.length === 0) {
                alert('{{ __("Pool pemilih sah masih kosong.") }}');
                return;
            }

            isDrawing = true;

            document.getElementById('stage-initial').classList.add('hidden');
            document.getElementById('stage-winner').classList.add('hidden');
            document.getElementById('stage-spinning').classList.remove('hidden');

            if (window.stage3D) window.stage3D.setMode('spin');
            if (window.SoundEffects && window.SoundEffects.drumRoll) window.SoundEffects.drumRoll(5);

            let tickCounter = 0;
            slotInterval = setInterval(() => {
                const randomPick = eligiblePool[Math.floor(Math.random() * eligiblePool.length)];
                document.getElementById('stage-slot-name').innerText = randomPick.nama;
                document.getElementById('stage-slot-dept').innerText = '{{ __("Bagian:") }} ' + randomPick.dept;
                document.getElementById('stage-slot-nik').innerText = '{{ __("NIK:") }} ' + randomPick.nik;
                tickCounter++;
                if (tickCounter % 3 === 0) playSlotTick();
            }, 60);

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
                        alert(data.message || '{{ __("Gagal mengundi hadiah.") }}');
                        resetStageToInitial();
                        return;
                    }

                    document.getElementById('stage-slot-name').innerText = data.winner.nama;
                    document.getElementById('stage-slot-dept').innerText = '{{ __("Bagian:") }} ' + data.winner.dept;
                    document.getElementById('stage-slot-nik').innerText = '{{ __("NIK:") }} ' + data.winner.nik;

                    setTimeout(() => {
                        revealStageWinner(data);
                    }, 700);
                }, 3500);
            })
            .catch(err => {
                clearInterval(slotInterval);
                console.error(err);
                alert('{{ __("Terjadi kesalahan jaringan.") }}');
                resetStageToInitial();
            });
        }

        function revealStageWinner(data) {
            document.getElementById('stage-spinning').classList.add('hidden');
            document.getElementById('stage-winner').classList.remove('hidden');

            if (window.stage3D) window.stage3D.setMode('reveal');

            document.getElementById('stage-winner-name').innerText = data.winner.nama;
            document.getElementById('stage-winner-meta').innerText = `{{ __('NIK:') }} ${data.winner.nik} • {{ __('Bagian:') }} ${data.winner.dept}`;
            document.getElementById('stage-winner-time').innerText = `{{ __('Tercatat Resmi:') }} ${data.winner.won_at} WIB`;
            document.getElementById('stage-winner-prize-pill').innerText = `{{ __('Hadiah:') }} ${data.doorprize.title}`;

            activePrizeSlots = data.doorprize.remaining_slots;
            document.getElementById('stage-active-meta').innerHTML = `{{ __('Tersedia') }} <strong class="text-amber-300 font-mono">${activePrizeSlots}</strong> {{ __('unit untuk anggota yang sah') }}`;

            const marquee = document.getElementById('stage-marquee-content');
            if (marquee) {
                const item = document.createElement('span');
                item.className = 'mx-4 font-bold text-amber-300';
                item.innerHTML = `★ {{ __('BARU TERPILIH:') }} <strong class="text-white">${data.winner.nama}</strong> (${data.winner.dept}) → ${data.doorprize.title} •`;
                marquee.prepend(item);
            }

            if (window.SoundEffects) {
                if (window.SoundEffects.stopDrumRoll) window.SoundEffects.stopDrumRoll();
                if (window.SoundEffects.crash) window.SoundEffects.crash();
                setTimeout(() => { if (window.SoundEffects.congrats) window.SoundEffects.congrats(); }, 300);
            }
            if (window.confetti) {
                window.confetti({
                    particleCount: 100,
                    spread: 100,
                    origin: { y: 0.5 },
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

            if (window.stage3D) window.stage3D.setMode('idle');
        }
    </script>
</body>
</html>
