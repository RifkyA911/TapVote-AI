@extends('layouts.admin')

@section('title', 'Master & Undian Doorprize Anggota')

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- Header Actions & Shortcut to Stage View -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-6 rounded-3xl bg-gradient-to-r from-amber-500 via-amber-600 to-yellow-500 text-slate-950 shadow-lg">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-slate-950 text-amber-300">
                    Modul Undian & Master Hadiah
                </span>
                <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold bg-white/30 text-slate-950">
                    Pool Sah: <strong>{{ $totalEligible }}</strong> Anggota
                </span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-950 tracking-tight">Undian Doorprize Anggota</h2>
            <p class="text-xs sm:text-sm font-medium text-slate-900/80 mt-1 max-w-xl">
                Sistem undian digital acak dengan pencatatan log otomatis ke database, master data reward, dan panggung display penonton.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 self-start md:self-auto">
            <!-- Shortcut to Audience Stage View -->
            <a 
                href="{{ route('doorprize.public') }}" 
                target="_blank" 
                class="px-5 py-3 rounded-2xl bg-slate-950 hover:bg-slate-900 text-amber-300 hover:text-amber-200 text-xs sm:text-sm font-black shadow-lg transition flex items-center space-x-2"
                title="Buka Halaman Khusus Penonton untuk Layar Proyektor"
            >
                <span class="text-base">📺</span>
                <span>Buka Panggung Penonton (Stage View)</span>
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>

            <!-- Add Doorprize Reward Button -->
            <button 
                type="button" 
                onclick="openAddRewardModal()" 
                class="px-4 py-3 rounded-2xl bg-white hover:bg-slate-100 text-slate-900 text-xs sm:text-sm font-bold shadow-md transition flex items-center space-x-1.5 cursor-pointer"
            >
                <span>+ Tambah Hadiah</span>
            </button>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MASTER REWARDS SELECTION CARDS                           -->
    <!-- ======================================================== -->
    <div>
        <div class="flex items-center justify-between mb-3 px-1">
            <h3 class="text-sm font-extrabold uppercase tracking-wider text-slate-700">1. Pilih Hadiah yang Akan Diundi</h3>
            <span class="text-xs text-slate-500 font-medium">Klik salah satu kartu untuk mengundi reward</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" id="doorprize-cards-container">
            @forelse($doorprizes as $d)
                <div 
                    id="prize-card-{{ $d->id }}"
                    onclick="selectPrize({{ $d->id }}, '{{ addslashes($d->title) }}', '{{ $d->category }}', {{ $d->remaining_slots }})"
                    class="prize-card p-4 rounded-3xl bg-white border-2 {{ $loop->first ? 'border-amber-500 ring-4 ring-amber-400/20' : 'border-slate-200' }} shadow-sm hover:shadow-md cursor-pointer transition-all flex flex-col justify-between group relative overflow-hidden"
                >
                    <div class="flex items-start justify-between gap-2">
                        <div class="w-10 h-10 rounded-2xl bg-amber-50 group-hover:bg-amber-100 text-amber-700 flex items-center justify-center text-xl transition">
                            @if($d->icon === 'bike' || str_contains(strtolower($d->title), 'sepeda'))
                                🚲
                            @elseif($d->icon === 'tv' || str_contains(strtolower($d->title), 'tv'))
                                📺
                            @elseif($d->icon === 'voucher' || str_contains(strtolower($d->title), 'voucher'))
                                🎫
                            @elseif(str_contains(strtolower($d->title), 'mesin cuci'))
                                🧺
                            @else
                                🎁
                            @endif
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-700">
                            {{ $d->category }}
                        </span>
                    </div>

                    <div class="my-3">
                        <h4 class="text-sm sm:text-base font-extrabold text-slate-900 group-hover:text-amber-800 transition line-clamp-2">
                            {{ $d->title }}
                        </h4>
                        @if($d->sponsor)
                            <span class="text-[11px] text-slate-400 block mt-0.5">Sponsor: {{ $d->sponsor }}</span>
                        @endif
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Sisa Kuota:</span>
                        <strong id="prize-remaining-{{ $d->id }}" class="font-mono text-xs px-2.5 py-0.5 rounded-full {{ $d->remaining_slots > 0 ? 'bg-emerald-100 text-emerald-800 font-bold' : 'bg-rose-100 text-rose-800 font-bold' }}">
                            {{ $d->remaining_slots }} / {{ $d->quantity }} Unit
                        </strong>
                    </div>

                    <form action="{{ route('admin.reports.doorprize.destroy', $d->id) }}" method="POST" onsubmit="event.stopPropagation(); return confirm('Hapus reward {{ $d->title }}?');" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1 rounded-lg text-rose-400 hover:text-rose-600 hover:bg-rose-50" title="Hapus Hadiah">
                            ✕
                        </button>
                    </form>
                </div>
            @empty
                <div class="col-span-full p-8 text-center bg-white rounded-3xl border border-slate-200 text-slate-400 text-xs">
                    Belum ada master reward hadiah. Klik <strong>+ Tambah Hadiah</strong> untuk memasukkan doorprize.
                </div>
            @endforelse
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 2. ANIMATED SVG LOTTERY STAGE (NO COUNTDOWN TIMER!)       -->
    <!-- ======================================================== -->
    <div class="p-6 sm:p-10 rounded-3xl bg-slate-900 text-white border-2 border-slate-800 shadow-2xl relative overflow-hidden text-center">
        <!-- Ambient decorative background -->
        <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full bg-amber-500/15 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 rounded-full bg-yellow-500/10 blur-3xl pointer-events-none"></div>

        <div class="max-w-xl mx-auto relative z-10 space-y-6">

            <!-- Active Selected Prize Indicator -->
            <div class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-400/30 text-xs font-bold shadow-sm">
                <span>🎁 Target Hadiah:</span>
                <strong id="active-prize-title" class="text-white">{{ $doorprizes->first()?->title ?? 'Pilih Hadiah' }}</strong>
                <span id="active-prize-slots" class="text-amber-400 font-mono">({{ $doorprizes->first()?->remaining_slots ?? 0 }} Sisa)</span>
            </div>

            <!-- STATE 1: INITIAL READY STATE -->
            <div id="doorprize-stage-initial" class="{{ $totalEligible > 0 ? '' : 'hidden' }} space-y-4">
                <!-- Rich SVG Slot Tumbler Graphic -->
                <div class="w-28 h-28 sm:w-32 sm:h-32 mx-auto relative flex items-center justify-center">
                    <svg class="w-full h-full text-amber-400 drop-shadow-[0_0_20px_rgba(245,158,11,0.5)]" viewBox="0 0 100 100" fill="none">
                        <circle cx="50" cy="50" r="45" stroke="currentColor" stroke-width="3" stroke-dasharray="6 4" class="animate-spin" style="animation-duration: 20s;" />
                        <circle cx="50" cy="50" r="36" stroke="rgba(255,255,255,0.2)" stroke-width="2" />
                        <rect x="35" y="35" width="30" height="30" rx="8" fill="url(#goldGradient)" />
                        <path d="M42 45L50 38L58 45V60H42V45Z" fill="#1e293b" />
                        <circle cx="50" cy="52" r="3" fill="#f59e0b" />
                        <defs>
                            <linearGradient id="goldGradient" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="#f59e0b" />
                                <stop offset="100%" stop-color="#fbbf24" />
                            </linearGradient>
                        </defs>
                    </svg>
                </div>

                <h3 class="text-2xl sm:text-3xl font-black text-white">Mesin Undian Digital Anggota</h3>
                <p class="text-xs sm:text-sm text-slate-300 max-w-md mx-auto">
                    Tekan tombol di bawah untuk memutar silinder digital beranimasi tinggi. Pemenang sah akan otomatis tersimpan dalam database.
                </p>

                <div>
                    <button 
                        id="btn-start-draw" 
                        type="button" 
                        onclick="startSvgAnimationDraw()"
                        class="px-8 py-4 rounded-2xl bg-gradient-to-r from-amber-400 via-amber-500 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 text-slate-950 font-black text-base sm:text-lg shadow-xl hover:shadow-2xl hover:scale-105 active:scale-95 transition-all cursor-pointer inline-flex items-center space-x-3"
                    >
                        <span class="text-2xl">⚡</span>
                        <span>Putar Undian Sekarang</span>
                    </button>
                </div>
            </div>

            <!-- STATE 2: ANIMATED SVG SPINNING STATE (NO COUNTDOWN TIMER!) -->
            <div id="doorprize-stage-spinning" class="hidden space-y-6">
                <!-- Glowing Animated SVG Reel Spinner -->
                <div class="relative w-36 h-36 mx-auto flex items-center justify-center">
                    <svg class="w-full h-full text-amber-400 drop-shadow-[0_0_25px_rgba(245,158,11,0.8)]" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="46" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-dasharray="30 15" class="animate-spin" style="animation-duration: 0.6s;" />
                        <circle cx="50" cy="50" r="35" stroke="#38bdf8" stroke-width="3" stroke-dasharray="20 20" class="animate-spin" style="animation-duration: 0.9s; animation-direction: reverse;" />
                        <circle cx="50" cy="50" r="22" fill="#0f172a" stroke="#f59e0b" stroke-width="2" />
                        <polygon points="50,34 54,44 65,44 56,51 60,61 50,55 40,61 44,51 35,44 46,44" fill="#fbbf24" class="animate-pulse" />
                    </svg>
                </div>

                <div class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-400/40 text-xs font-black animate-pulse">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-ping"></span>
                    <span>SILINDER DIGITAL BERPUTAR CEPAT...</span>
                </div>

                <!-- Digital Reel Card -->
                <div class="p-6 rounded-3xl bg-slate-800/90 border-2 border-amber-400/60 shadow-2xl min-h-[140px] flex flex-col justify-center items-center backdrop-blur-xs">
                    <span class="text-[11px] font-extrabold text-amber-400 uppercase tracking-widest block mb-1">Mencari Nama Pemenang...</span>
                    <h4 id="slot-name" class="text-2xl sm:text-3xl font-black text-white transition-all">Memutar Data...</h4>
                    <p id="slot-dept" class="text-sm font-bold text-amber-200 mt-1 font-mono">Bagian: -</p>
                    <span id="slot-nik" class="text-xs text-slate-400 font-mono mt-0.5">NIK: -</span>
                </div>
            </div>

            <!-- STATE 3: WINNER REVEAL STATE -->
            <div id="doorprize-stage-winner" class="hidden space-y-5">
                <div class="inline-flex items-center space-x-2 px-5 py-2 rounded-full text-xs font-black uppercase tracking-wider bg-amber-400 text-slate-950 shadow-lg">
                    <span>🏆</span>
                    <span>SELAMAT! PEMENANG RESMI TERCATAT</span>
                </div>

                <div class="p-6 sm:p-8 rounded-3xl bg-slate-800/95 border-2 border-amber-400 shadow-2xl relative overflow-hidden backdrop-blur-xs">
                    <div class="w-20 h-20 mx-auto rounded-3xl bg-gradient-to-tr from-amber-400 to-yellow-300 text-slate-950 flex items-center justify-center text-4xl mb-3 shadow-lg">
                        🎉
                    </div>

                    <span id="winner-prize-badge" class="px-3 py-1 rounded-full text-xs font-extrabold bg-amber-500/20 text-amber-300 border border-amber-400/30 inline-block mb-2">
                        Hadiah: -
                    </span>

                    <h3 id="winner-name" class="text-3xl sm:text-4xl font-black text-white mb-1">Nama Pemenang</h3>
                    <p id="winner-meta" class="text-base text-amber-300 font-bold mb-3 font-mono">NIK: - • Bagian: -</p>

                    <div class="inline-flex items-center px-4 py-2 rounded-xl bg-slate-900/80 border border-slate-700 text-xs text-slate-300 font-medium">
                        <span id="winner-time">Tercatat: -</span>
                    </div>
                </div>

                <div class="pt-2 flex justify-center gap-3">
                    <button 
                        type="button" 
                        onclick="resetDrawStage()"
                        class="px-6 py-3 rounded-2xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-black text-xs sm:text-sm shadow-lg transition cursor-pointer inline-flex items-center space-x-2"
                    >
                        <span>↻ Undi Hadiah Lainnya</span>
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

    <!-- ======================================================== -->
    <!-- 3. LOG PEMENANG DOORPRIZE (DATABASE RECORD)              -->
    <!-- ======================================================== -->
    <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-200">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Log Pemenang Doorprize Resmi</h3>
                <p class="text-xs text-slate-500">Histori anggota yang telah mendapatkan reward secara sah dari database.</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 self-start sm:self-auto">
                Total Pemenang: <strong id="log-count">{{ count($winners) }}</strong>
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                        <th class="py-3 px-3">Waktu Undian</th>
                        <th class="py-3 px-3">Hadiah (Reward)</th>
                        <th class="py-3 px-3">Nama Pemenang</th>
                        <th class="py-3 px-3">NIK</th>
                        <th class="py-3 px-3">Departemen</th>
                        <th class="py-3 px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="winners-table-body" class="divide-y divide-slate-100">
                    @forelse($winners as $w)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-3 font-mono text-slate-500 text-xs">{{ $w->won_at->format('H:i:s d/m/Y') }}</td>
                            <td class="py-3 px-3 font-bold text-amber-800">
                                <span class="px-2.5 py-0.5 rounded-lg bg-amber-50 border border-amber-200">
                                    {{ $w->doorprize?->title ?? 'Hadiah Dihapus' }}
                                </span>
                            </td>
                            <td class="py-3 px-3 font-black text-slate-900">{{ $w->pemilih?->nama ?? '-' }}</td>
                            <td class="py-3 px-3 font-mono text-blue-700 font-bold">{{ $w->nik }}</td>
                            <td class="py-3 px-3 text-slate-600">{{ $w->pemilih?->dept ?? '-' }}</td>
                            <td class="py-3 px-3 text-right">
                                <form action="{{ route('admin.reports.doorprize.winner.destroy', $w->id) }}" method="POST" onsubmit="return confirm('Batalkan kemenangan {{ $w->pemilih?->nama }}? Kuota hadiah akan dikembalikan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 rounded-lg text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition" title="Batalkan Kemenangan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr id="empty-winners-row">
                            <td colspan="6" class="py-6 text-center text-slate-400">Belum ada pemenang yang diundi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Tambah Master Reward Baru -->
<div id="add-reward-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl max-w-md w-full p-6 shadow-2xl">
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-200">
            <h3 class="text-lg font-black text-slate-900">Tambah Master Hadiah Doorprize</h3>
            <button onclick="closeAddRewardModal()" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>

        <form action="{{ route('admin.reports.doorprize.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Hadiah / Reward</label>
                <input type="text" name="title" required placeholder="Contoh: Sepeda Listrik Smart e-Bike" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 focus:border-amber-500 outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori</label>
                    <select name="category" class="w-full px-3 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 focus:border-amber-500 outline-none">
                        <option value="Grand Prize">Grand Prize</option>
                        <option value="Utama">Utama</option>
                        <option value="Hiburan">Hiburan</option>
                        <option value="Elektronik">Elektronik</option>
                        <option value="Voucher">Voucher</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Unit (Qty)</label>
                    <input type="number" name="quantity" required min="1" max="1000" value="1" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 focus:border-amber-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Sponsor / Donatur (Opsional)</label>
                <input type="text" name="sponsor" placeholder="Contoh: Bank Mitra / Koperasi" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 focus:border-amber-500 outline-none">
            </div>

            <div class="pt-3 border-t border-slate-200 flex justify-end space-x-2">
                <button type="button" onclick="closeAddRewardModal()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-black shadow">Simpan Hadiah</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const eligiblePool = @json($eligibleList);
    let selectedPrizeId = {{ $doorprizes->first()?->id ?? 'null' }};
    let selectedPrizeTitle = "{{ addslashes($doorprizes->first()?->title ?? '') }}";
    let selectedPrizeSlots = {{ $doorprizes->first()?->remaining_slots ?? 0 }};
    let isDrawing = false;
    let slotInterval = null;

    function openAddRewardModal() {
        if (window.SoundEffects) window.SoundEffects.modal();
        document.getElementById('add-reward-modal').classList.remove('hidden');
    }

    function closeAddRewardModal() {
        document.getElementById('add-reward-modal').classList.add('hidden');
    }

    function selectPrize(id, title, category, slots) {
        if (isDrawing) return;
        selectedPrizeId = id;
        selectedPrizeTitle = title;
        selectedPrizeSlots = slots;

        document.getElementById('active-prize-title').innerText = title;
        document.getElementById('active-prize-slots').innerText = `(${slots} Sisa)`;

        // Highlight selected card
        document.querySelectorAll('.prize-card').forEach(c => {
            c.classList.remove('border-amber-500', 'ring-4', 'ring-amber-400/20');
            c.classList.add('border-slate-200');
        });
        const card = document.getElementById('prize-card-' + id);
        if (card) {
            card.classList.remove('border-slate-200');
            card.classList.add('border-amber-500', 'ring-4', 'ring-amber-400/20');
        }
        if (window.SoundEffects) window.SoundEffects.click();
    }

    // Play synthetic digital slot tick
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

    function startSvgAnimationDraw() {
        if (isDrawing) return;
        if (!selectedPrizeId) {
            alert('Pilih hadiah terlebih dahulu.');
            return;
        }
        if (selectedPrizeSlots <= 0) {
            alert('Kuota hadiah ini sudah habis! Pilih hadiah lain.');
            return;
        }
        if (!eligiblePool || eligiblePool.length === 0) {
            alert('Pool pemilih yang sah masih kosong.');
            return;
        }

        isDrawing = true;

        const stageInitial = document.getElementById('doorprize-stage-initial');
        const stageSpinning = document.getElementById('doorprize-stage-spinning');
        const stageWinner = document.getElementById('doorprize-stage-winner');

        stageInitial.classList.add('hidden');
        stageWinner.classList.add('hidden');
        stageSpinning.classList.remove('hidden');

        // Rapid name slot simulation with audio tick
        let tickCounter = 0;
        slotInterval = setInterval(() => {
            const randomPick = eligiblePool[Math.floor(Math.random() * eligiblePool.length)];
            document.getElementById('slot-name').innerText = randomPick.nama;
            document.getElementById('slot-dept').innerText = 'Bagian: ' + randomPick.dept;
            document.getElementById('slot-nik').innerText = 'NIK: ' + randomPick.nik;
            tickCounter++;
            if (tickCounter % 3 === 0) playSlotTick();
        }, 60);

        // Fetch backend to determine real random winner from DB and record log
        fetch("{{ route('admin.reports.doorprize.draw') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ doorprize_id: selectedPrizeId })
        })
        .then(res => res.json())
        .then(data => {
            // Keep spinning for 3.5 seconds of high excitement before decelerating
            setTimeout(() => {
                clearInterval(slotInterval);

                if (!data.success) {
                    alert(data.message || 'Gagal mengundi hadiah.');
                    resetDrawStage();
                    return;
                }

                // Decelerate & reveal winner
                document.getElementById('slot-name').innerText = data.winner.nama;
                document.getElementById('slot-dept').innerText = 'Bagian: ' + data.winner.dept;
                document.getElementById('slot-nik').innerText = 'NIK: ' + data.winner.nik;

                setTimeout(() => {
                    revealWinner(data);
                }, 700);
            }, 3200);
        })
        .catch(err => {
            clearInterval(slotInterval);
            console.error(err);
            alert('Terjadi kesalahan jaringan saat mengundi.');
            resetDrawStage();
        });
    }

    function revealWinner(data) {
        const stageSpinning = document.getElementById('doorprize-stage-spinning');
        const stageWinner = document.getElementById('doorprize-stage-winner');

        stageSpinning.classList.add('hidden');
        stageWinner.classList.remove('hidden');

        document.getElementById('winner-name').innerText = data.winner.nama;
        document.getElementById('winner-meta').innerText = `NIK: ${data.winner.nik} • Bagian: ${data.winner.dept}`;
        document.getElementById('winner-time').innerText = `Tercatat Sah: ${data.winner.won_at} WIB`;
        document.getElementById('winner-prize-badge').innerText = `Hadiah: ${data.doorprize.title}`;

        // Update remaining slots in card & state
        selectedPrizeSlots = data.doorprize.remaining_slots;
        const remEl = document.getElementById('prize-remaining-' + data.doorprize.id);
        if (remEl) {
            remEl.innerText = `${data.doorprize.remaining_slots} Unit`;
            if (data.doorprize.remaining_slots <= 0) {
                remEl.className = 'font-mono text-xs px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-800 font-bold';
            }
        }
        document.getElementById('active-prize-slots').innerText = `(${data.doorprize.remaining_slots} Sisa)`;

        // Append to logs table dynamically
        appendWinnerToTable(data.winner, data.doorprize);

        // Celebratory sound and gentle confetti behind
        if (window.SoundEffects && window.SoundEffects.congrats) window.SoundEffects.congrats();
        if (window.confetti) {
            window.confetti({
                particleCount: 50,
                spread: 70,
                origin: { y: 0.6 },
                zIndex: 90
            });
        }

        isDrawing = false;
    }

    function appendWinnerToTable(winner, prize) {
        const emptyRow = document.getElementById('empty-winners-row');
        if (emptyRow) emptyRow.remove();

        const tbody = document.getElementById('winners-table-body');
        const countEl = document.getElementById('log-count');
        if (countEl) countEl.innerText = parseInt(countEl.innerText || 0) + 1;

        const tr = document.createElement('tr');
        tr.className = 'hover:bg-slate-50 transition bg-amber-50/40';
        tr.innerHTML = `
            <td class="py-3 px-3 font-mono text-slate-500 text-xs">${winner.won_at}</td>
            <td class="py-3 px-3 font-bold text-amber-800">
                <span class="px-2.5 py-0.5 rounded-lg bg-amber-50 border border-amber-200">
                    ${prize.title}
                </span>
            </td>
            <td class="py-3 px-3 font-black text-slate-900">${winner.nama}</td>
            <td class="py-3 px-3 font-mono text-blue-700 font-bold">${winner.nik}</td>
            <td class="py-3 px-3 text-slate-600">${winner.dept}</td>
            <td class="py-3 px-3 text-right">
                <span class="text-xs text-emerald-600 font-bold">Baru Terpilih ✓</span>
            </td>
        `;
        tbody.prepend(tr);
    }

    function resetDrawStage() {
        isDrawing = false;
        if (slotInterval) clearInterval(slotInterval);
        document.getElementById('doorprize-stage-spinning').classList.add('hidden');
        document.getElementById('doorprize-stage-winner').classList.add('hidden');
        document.getElementById('doorprize-stage-initial').classList.remove('hidden');
    }
</script>
@endpush
@endsection
