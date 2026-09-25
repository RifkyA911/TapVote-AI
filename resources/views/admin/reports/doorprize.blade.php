@extends('layouts.admin')

@section('title', 'Master & Undian Doorprize Anggota')

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- Header Actions & Shortcut to Stage View (Light & Gold Gradient) -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-6 sm:p-7 rounded-3xl bg-gradient-to-r from-amber-400 via-amber-500 to-yellow-500 text-slate-950 shadow-md">
        <div>
            <div class="flex items-center space-x-2 mb-1.5">
                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-slate-950 text-amber-300">
                    Modul Undian & Master Hadiah
                </span>
                <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold bg-white/40 text-slate-950">
                    Pool Sah: <strong>{{ $totalEligible }}</strong> Anggota
                </span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-950 tracking-tight">Undian Doorprize Anggota</h2>
            <p class="text-xs sm:text-sm font-semibold text-slate-900/85 mt-1 max-w-xl">
                Sistem undian digital acak dengan status serah terima hadiah (Diterima / Ditolak), upload foto hadiah, dan display panggung penonton.
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
                class="px-4 py-3 rounded-2xl bg-white hover:bg-slate-50 text-slate-900 text-xs sm:text-sm font-bold shadow-md transition flex items-center space-x-1.5 cursor-pointer"
            >
                <span>+ Tambah Hadiah Baru</span>
            </button>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 1. MASTER REWARDS DATATABLE (REPLACED FROM CARDS GRID)   -->
    <!-- ======================================================== -->
    <div class="p-5 sm:p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-200">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">1. Master Pilihan Hadiah Doorprize</h3>
                <p class="text-xs text-slate-500">Daftar inventaris hadiah. Klik tombol <strong>🎯 Jadikan Target Undian</strong> untuk memilih reward yang diundi di mesin.</p>
            </div>
            <div class="inline-flex items-center space-x-2">
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-900 border border-amber-200">
                    Total Hadiah: <strong>{{ count($doorprizes) }}</strong> Item
                </span>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
            <div class="relative flex-1">
                <input 
                    type="text" 
                    id="doorprize-search" 
                    placeholder="Cari nama hadiah atau sponsor..." 
                    oninput="filterDoorprizeTable()"
                    class="w-full pl-9 pr-8 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-amber-400 outline-none"
                >
                <svg class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <select id="doorprize-category-filter" onchange="filterDoorprizeTable()" class="h-9 px-3 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-700 focus:bg-white focus:border-amber-400 outline-none cursor-pointer">
                <option value="">Semua Kategori</option>
                <option value="Elektronik">Elektronik</option>
                <option value="Peralatan Rumah">Peralatan Rumah</option>
                <option value="Gadget">Gadget & Smartphone</option>
                <option value="Voucher">Voucher</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-200">
            <table class="w-full text-left text-xs sm:text-sm datatable" id="doorprizes-datatable">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-extrabold uppercase text-[10px] tracking-wider">
                        <th class="py-3 px-3">Foto / Ikon</th>
                        <th class="py-3 px-3">Nama Hadiah & Deskripsi</th>
                        <th class="py-3 px-3">Kategori</th>
                        <th class="py-3 px-3">Total Qty</th>
                        <th class="py-3 px-3">Sisa Kuota</th>
                        <th class="py-3 px-3">Sponsor</th>
                        <th class="py-3 px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($doorprizes as $d)
                        <tr id="prize-row-{{ $d->id }}" class="hover:bg-amber-50/40 transition {{ $loop->first ? 'bg-amber-50/70 font-semibold' : '' }}">
                            <td class="py-2.5 px-3">
                                @if($d->image)
                                    <img src="{{ $d->image_url }}" alt="{{ $d->title }}" class="w-12 h-12 rounded-xl object-cover border border-slate-300 shadow-xs">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-amber-100 border border-amber-300 text-amber-800 flex items-center justify-center text-2xl shadow-xs">
                                        @if(str_contains(strtolower($d->title), 'sepeda')) 🚲
                                        @elseif(str_contains(strtolower($d->title), 'tv')) 📺
                                        @elseif(str_contains(strtolower($d->title), 'kulkas') || str_contains(strtolower($d->title), 'mesin')) 🧺
                                        @elseif(str_contains(strtolower($d->title), 'voucher')) 🎫
                                        @elseif(str_contains(strtolower($d->title), 'hp') || str_contains(strtolower($d->title), 'smartphone')) 📱
                                        @else 🎁
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="py-2.5 px-3">
                                <strong class="text-slate-900 block text-sm">{{ $d->title }}</strong>
                                @if($d->description)
                                    <span class="text-xs text-slate-500 block line-clamp-1">{{ $d->description }}</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $d->category }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 font-mono font-bold text-slate-700">
                                {{ $d->quantity }} Unit
                            </td>
                            <td class="py-2.5 px-3">
                                <span id="prize-remaining-{{ $d->id }}" class="px-2.5 py-1 rounded-full text-xs font-mono font-bold {{ $d->remaining_slots > 0 ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : 'bg-rose-100 text-rose-900 border border-rose-300' }}">
                                    {{ $d->remaining_slots }} / {{ $d->quantity }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-slate-600">
                                {{ $d->sponsor ?: '-' }}
                            </td>
                            <td class="py-2.5 px-3 text-right">
                                <div class="inline-flex items-center space-x-1.5">
                                    <button 
                                        type="button" 
                                        onclick="selectPrize({{ $d->id }}, '{{ addslashes($d->title) }}', '{{ $d->category }}', {{ $d->remaining_slots }})"
                                        class="px-3 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-black text-xs shadow-xs transition cursor-pointer flex items-center space-x-1"
                                        title="Pilih reward ini untuk diundi pada mesin di bawah"
                                    >
                                        <span>🎯 Pilih</span>
                                    </button>

                                    <form action="{{ route('admin.reports.doorprize.destroy', $d->id) }}" method="POST" onsubmit="return confirm('Hapus reward {{ $d->title }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-xl text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition" title="Hapus Hadiah">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-400">Belum ada master hadiah doorprize. Klik <strong>+ Tambah Hadiah Baru</strong> di atas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 2. ANIMATED SVG LOTTERY STAGE (LIGHT THEME MATCHING!)     -->
    <!-- ======================================================== -->
    <div class="p-6 sm:p-10 rounded-3xl bg-gradient-to-br from-amber-50/80 via-white to-yellow-50/60 text-slate-900 border-2 border-amber-300 shadow-lg relative overflow-hidden text-center">
        <!-- Ambient decorative accents -->
        <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full bg-amber-400/15 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 rounded-full bg-yellow-400/15 blur-3xl pointer-events-none"></div>

        <div class="max-w-xl mx-auto relative z-10 space-y-6">

            <!-- Active Selected Prize Indicator Badge -->
            <div class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full bg-amber-100 text-amber-950 border border-amber-300 text-xs font-black shadow-xs">
                <span>🎁 Target Hadiah:</span>
                <strong id="active-prize-title" class="text-slate-900 font-extrabold">{{ $doorprizes->first()?->title ?? 'Pilih Hadiah' }}</strong>
                <span id="active-prize-slots" class="text-amber-800 font-mono">({{ $doorprizes->first()?->remaining_slots ?? 0 }} Sisa)</span>
            </div>

            <!-- STATE 1: INITIAL READY STATE -->
            <div id="doorprize-stage-initial" class="{{ $totalEligible > 0 ? '' : 'hidden' }} space-y-4">
                <!-- Rich SVG Slot Tumbler Graphic -->
                <div class="w-28 h-28 sm:w-32 sm:h-32 mx-auto relative flex items-center justify-center">
                    <svg class="w-full h-full text-amber-500 drop-shadow-[0_4px_16px_rgba(245,158,11,0.35)]" viewBox="0 0 100 100" fill="none">
                        <circle cx="50" cy="50" r="45" stroke="currentColor" stroke-width="3" stroke-dasharray="6 4" class="animate-spin" style="animation-duration: 20s;" />
                        <circle cx="50" cy="50" r="36" stroke="rgba(245,158,11,0.3)" stroke-width="2" />
                        <rect x="35" y="35" width="30" height="30" rx="8" fill="url(#goldGradient)" />
                        <path d="M42 45L50 38L58 45V60H42V45Z" fill="#1e293b" />
                        <circle cx="50" cy="52" r="3" fill="#ffffff" />
                        <defs>
                            <linearGradient id="goldGradient" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="#f59e0b" />
                                <stop offset="100%" stop-color="#fbbf24" />
                            </linearGradient>
                        </defs>
                    </svg>
                </div>

                <h3 class="text-2xl sm:text-3xl font-black text-slate-950">Mesin Undian Digital Anggota</h3>
                <p class="text-xs sm:text-sm text-slate-600 max-w-md mx-auto font-medium">
                    Pilih hadiah pada tabel di atas, lalu tekan tombol di bawah untuk memutar silinder undian digital. Pemenang sah akan otomatis tersimpan dalam database.
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
                <div class="relative w-32 h-32 sm:w-36 sm:h-36 mx-auto flex items-center justify-center">
                    <svg class="w-full h-full text-amber-500 drop-shadow-[0_4px_20px_rgba(245,158,11,0.5)]" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="46" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-dasharray="30 15" class="animate-spin" style="animation-duration: 0.6s;" />
                        <circle cx="50" cy="50" r="35" stroke="#3b82f6" stroke-width="3" stroke-dasharray="20 20" class="animate-spin" style="animation-duration: 0.9s; animation-direction: reverse;" />
                        <circle cx="50" cy="50" r="22" fill="#ffffff" stroke="#f59e0b" stroke-width="2" />
                        <polygon points="50,34 54,44 65,44 56,51 60,61 50,55 40,61 44,51 35,44 46,44" fill="#f59e0b" class="animate-pulse" />
                    </svg>
                </div>

                <div class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full bg-amber-100 text-amber-950 border border-amber-300 text-xs font-black animate-pulse">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping"></span>
                    <span>SILINDER DIGITAL BERPUTAR CEPAT...</span>
                </div>

                <!-- Digital Reel Card in Light Theme -->
                <div class="p-6 rounded-3xl bg-white border-2 border-amber-400 shadow-xl min-h-[140px] flex flex-col justify-center items-center">
                    <span class="text-[11px] font-extrabold text-amber-700 uppercase tracking-widest block mb-1">Mencari Nama Pemenang...</span>
                    <h4 id="slot-name" class="text-2xl sm:text-3xl font-black text-slate-950 transition-all">Memutar Data...</h4>
                    <p id="slot-dept" class="text-sm font-bold text-amber-900 mt-1 font-mono">Bagian: -</p>
                    <span id="slot-nik" class="text-xs text-slate-500 font-mono mt-0.5">NIK: -</span>
                </div>
            </div>

            <!-- STATE 3: WINNER REVEAL STATE (LIGHT THEME MATCHING!) -->
            <div id="doorprize-stage-winner" class="hidden space-y-5">
                <div class="inline-flex items-center space-x-2 px-5 py-2 rounded-full text-xs font-black uppercase tracking-wider bg-amber-400 text-slate-950 shadow-md">
                    <span>🏆</span>
                    <span>SELAMAT! PEMENANG RESMI TERCATAT</span>
                </div>

                <div class="p-6 sm:p-8 rounded-3xl bg-white border-2 border-amber-400 shadow-2xl relative overflow-hidden">
                    <div class="w-20 h-20 mx-auto rounded-3xl bg-gradient-to-tr from-amber-400 to-yellow-300 text-slate-950 flex items-center justify-center text-4xl mb-3 shadow-md">
                        🎉
                    </div>

                    <span id="winner-prize-badge" class="px-3 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-900 border border-amber-300 inline-block mb-2">
                        Hadiah: -
                    </span>

                    <h3 id="winner-name" class="text-3xl sm:text-4xl font-black text-slate-950 mb-1">Nama Pemenang</h3>
                    <p id="winner-meta" class="text-base text-amber-800 font-bold mb-3 font-mono">NIK: - • Bagian: -</p>

                    <div class="inline-flex items-center px-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 font-medium">
                        <span id="winner-time">Tercatat: -</span>
                    </div>
                </div>

                <div class="pt-2 flex justify-center gap-3">
                    <button 
                        type="button" 
                        onclick="resetDrawStage()"
                        class="px-6 py-3 rounded-2xl bg-amber-400 hover:bg-amber-500 text-slate-950 font-black text-xs sm:text-sm shadow-md transition cursor-pointer inline-flex items-center space-x-2"
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
    <!-- 3. LOG PEMENANG DOORPRIZE & STATUS KLAIM HADIAH          -->
    <!-- ======================================================== -->
    <div class="p-5 sm:p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-200">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">3. Log Pemenang & Status Klaim Doorprize</h3>
                <p class="text-xs text-slate-500">Pencatatan resmi pemenang undian, status serah terima (Diterima / Ditolak), dan alasan.</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 self-start sm:self-auto">
                Total Pemenang: <strong id="log-count">{{ count($winners) }}</strong>
            </span>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
            <div class="relative flex-1">
                <input 
                    type="text" 
                    id="winner-search" 
                    placeholder="Cari pemenang, NIK, atau hadiah..." 
                    oninput="filterWinnerTable()"
                    class="w-full pl-9 pr-8 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-amber-400 outline-none"
                >
                <svg class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <select id="winner-status-filter" onchange="filterWinnerTable()" class="h-9 px-3 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-700 focus:bg-white focus:border-amber-400 outline-none cursor-pointer">
                <option value="">Semua Status Klaim</option>
                <option value="Sudah Diterima">Sudah Diterima (accepted)</option>
                <option value="Ditolak">Ditolak (rejected)</option>
                <option value="Belum Diambil">Belum Diambil (pending)</option>
                <option value="Alasan Lain">Alasan Lain (other)</option>
            </select>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-200">
            <table class="w-full text-left text-xs sm:text-sm datatable" id="winners-datatable">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-extrabold uppercase text-[10px] tracking-wider">
                        <th class="py-3 px-3">Waktu Undian</th>
                        <th class="py-3 px-3">Hadiah (Reward)</th>
                        <th class="py-3 px-3">Nama Pemenang</th>
                        <th class="py-3 px-3">NIK</th>
                        <th class="py-3 px-3">Departemen</th>
                        <th class="py-3 px-3">Status Klaim Hadiah</th>
                        <th class="py-3 px-3">Catatan / Alasan</th>
                        <th class="py-3 px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="winners-table-body" class="divide-y divide-slate-100 font-medium">
                    @forelse($winners as $w)
                        <tr id="winner-row-{{ $w->id }}" class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-3 font-mono text-slate-500 text-xs">{{ $w->won_at->format('H:i:s d/m/Y') }}</td>
                            <td class="py-3 px-3 font-bold text-amber-900">
                                <span class="px-2.5 py-1 rounded-lg bg-amber-50 border border-amber-200 inline-flex items-center space-x-1.5">
                                    <span>🎁</span>
                                    <span>{{ $w->doorprize?->title ?? 'Hadiah Dihapus' }}</span>
                                </span>
                            </td>
                            <td class="py-3 px-3 font-black text-slate-900">{{ $w->pemilih?->nama ?? '-' }}</td>
                            <td class="py-3 px-3 font-mono text-blue-700 font-bold">{{ $w->nik }}</td>
                            <td class="py-3 px-3 text-slate-600">{{ $w->pemilih?->dept ?? '-' }}</td>
                            <td class="py-3 px-3">
                                @if($w->status === 'accepted')
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[11px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        <span>✓</span>
                                        <span>Sudah Diterima</span>
                                    </span>
                                    @if($w->received_at)
                                        <span class="block text-[10px] text-slate-400 font-mono mt-0.5">{{ $w->received_at->format('d/m/Y H:i') }}</span>
                                    @endif
                                @elseif($w->status === 'rejected')
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[11px] font-black bg-rose-100 text-rose-800 border border-rose-300">
                                        <span>✕</span>
                                        <span>Ditolak</span>
                                    </span>
                                @elseif($w->status === 'other')
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[11px] font-black bg-slate-100 text-slate-800 border border-slate-300">
                                        <span>ℹ</span>
                                        <span>Lainnya</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[11px] font-black bg-amber-100 text-amber-900 border border-amber-300">
                                        <span>⏳</span>
                                        <span>Belum Diambil</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-slate-600 text-xs max-w-xs truncate" title="{{ $w->status_note }}">
                                {{ $w->status_note ?: '-' }}
                            </td>
                            <td class="py-3 px-3 text-right">
                                <div class="inline-flex items-center space-x-1.5">
                                    <button 
                                        type="button" 
                                        onclick="openUpdateStatusModal({{ $w->id }}, '{{ $w->status }}', '{{ addslashes($w->status_note ?? '') }}', '{{ addslashes($w->pemilih?->nama ?? $w->nik) }}', '{{ addslashes($w->doorprize?->title ?? '') }}')"
                                        class="px-2.5 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-800 border border-blue-200 text-xs font-bold transition cursor-pointer"
                                        title="Ubah Status Serah Terima Hadiah"
                                    >
                                        Ubah Status
                                    </button>

                                    <form action="{{ route('admin.reports.doorprize.winner.destroy', $w->id) }}" method="POST" onsubmit="return confirm('Batalkan kemenangan {{ $w->pemilih?->nama }}? Kuota hadiah akan dikembalikan.')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-xl text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition" title="Batalkan Kemenangan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="empty-winners-row">
                            <td colspan="8" class="py-6 text-center text-slate-400">Belum ada pemenang yang diundi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Tambah Master Reward Baru (Dengan Upload Gambar & Deskripsi) -->
<div id="add-reward-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white border-2 border-slate-200 rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl relative space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-200">
            <h3 class="text-lg font-black text-slate-900">Tambah Master Hadiah Doorprize</h3>
            <button onclick="closeAddRewardModal()" type="button" class="text-slate-400 hover:text-slate-700 text-xl font-bold p-1 rounded-xl">✕</button>
        </div>

        <form action="{{ route('admin.reports.doorprize.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Hadiah / Reward <span class="text-rose-500">*</span></label>
                <input type="text" name="title" required placeholder="Contoh: Sepeda Listrik Smart e-Bike" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 focus:border-amber-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Singkat / Spesifikasi</label>
                <textarea name="description" rows="2" placeholder="Contoh: Garansi resmi 1 tahun, baterai lithium 48V..." class="w-full px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 focus:border-amber-500 outline-none"></textarea>
            </div>

            <!-- Upload Gambar Hadiah -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Upload Foto Hadiah (Opsional)</label>
                <input type="file" name="image" accept="image/png,image/jpeg,image/webp" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-extrabold file:bg-amber-100 file:text-amber-900 hover:file:bg-amber-200 cursor-pointer">
                <span class="text-[11px] text-slate-400 block mt-1">Format: JPG, PNG, atau WebP (Maks. 4 MB)</span>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Hadiah</label>
                    <select name="category" class="w-full px-3 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 focus:border-amber-500 outline-none font-semibold">
                        <option value="Grand Prize">Grand Prize</option>
                        <option value="Utama">Utama</option>
                        <option value="Elektronik">Elektronik</option>
                        <option value="Hiburan">Hiburan</option>
                        <option value="Voucher">Voucher</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Unit (Qty) <span class="text-rose-500">*</span></label>
                    <input type="number" name="quantity" required min="1" max="1000" value="1" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 focus:border-amber-500 outline-none font-mono">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Sponsor / Donatur (Opsional)</label>
                <input type="text" name="sponsor" placeholder="Contoh: Bank Mitra / Koperasi Bersama" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 focus:border-amber-500 outline-none">
            </div>

            <div class="pt-3 border-t border-slate-200 flex justify-end space-x-2">
                <button type="button" onclick="closeAddRewardModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-black shadow-md cursor-pointer">Simpan Hadiah</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Update Status Serah Terima Pemenang (Diterima / Ditolak / Alasan Lain) -->
<div id="update-winner-status-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white border-2 border-slate-200 rounded-3xl max-w-md w-full p-6 shadow-2xl relative space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-200">
            <div>
                <h3 class="text-base font-black text-slate-900">Ubah Status Klaim Hadiah</h3>
                <p id="modal-winner-info" class="text-xs text-slate-500">Pemenang: -</p>
            </div>
            <button onclick="closeUpdateStatusModal()" type="button" class="text-slate-400 hover:text-slate-700 text-xl font-bold p-1 rounded-xl">✕</button>
        </div>

        <form id="update-status-form" method="POST" onsubmit="handleWinnerStatusSubmit(event)" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Status Hadiah</label>
                <select name="status" id="modal-winner-status-select" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 font-bold focus:border-blue-600 outline-none">
                    <option value="pending">⏳ Belum Diambil (Pending)</option>
                    <option value="accepted">✓ Sudah Diterima</option>
                    <option value="rejected">✕ Ditolak (Tidak Hadir / Membatalkan)</option>
                    <option value="other">ℹ Lainnya / Alasan Khusus</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Catatan / Alasan Penyerahan (Opsional)</label>
                <textarea name="status_note" id="modal-winner-note-input" rows="3" placeholder="Contoh: Diterima langsung di panggung / Tidak hadir setelah dipanggil 3 kali..." class="w-full px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 focus:border-blue-600 outline-none"></textarea>
            </div>

            <div class="pt-3 border-t border-slate-200 flex justify-end space-x-2">
                <button type="button" onclick="closeUpdateStatusModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold">Batal</button>
                <button type="submit" id="btn-save-status" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-black shadow-md cursor-pointer">Simpan Status</button>
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

    let activeEditWinnerId = null;

    function openUpdateStatusModal(winnerId, currentStatus, currentNote, winnerName, prizeTitle) {
        activeEditWinnerId = winnerId;
        document.getElementById('modal-winner-info').innerText = `${winnerName} (${prizeTitle})`;
        document.getElementById('modal-winner-status-select').value = currentStatus || 'pending';
        document.getElementById('modal-winner-note-input').value = currentNote || '';
        document.getElementById('update-winner-status-modal').classList.remove('hidden');
        if (window.SoundEffects) window.SoundEffects.modal();
    }

    function closeUpdateStatusModal() {
        document.getElementById('update-winner-status-modal').classList.add('hidden');
        activeEditWinnerId = null;
    }

    async function handleWinnerStatusSubmit(e) {
        e.preventDefault();
        if (!activeEditWinnerId) return;

        const btn = document.getElementById('btn-save-status');
        btn.disabled = true;
        btn.innerText = 'Menyimpan...';

        const statusVal = document.getElementById('modal-winner-status-select').value;
        const noteVal = document.getElementById('modal-winner-note-input').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        try {
            const url = `{{ url('admin/reports/doorprize/winners') }}/${activeEditWinnerId}/status`;
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    status: statusVal,
                    status_note: noteVal
                })
            });

            const data = await res.json();
            if (res.ok && data.success) {
                closeUpdateStatusModal();
                window.location.reload();
            } else {
                alert(data.message || 'Gagal menyimpan status pemenang.');
            }
        } catch (err) {
            console.error('Error saving status:', err);
            alert('Terjadi kesalahan jaringan.');
        } finally {
            btn.disabled = false;
            btn.innerText = 'Simpan Status';
        }
    }

    function selectPrize(id, title, category, slots) {
        if (isDrawing) return;
        selectedPrizeId = id;
        selectedPrizeTitle = title;
        selectedPrizeSlots = slots;

        document.getElementById('active-prize-title').innerText = title;
        document.getElementById('active-prize-slots').innerText = `(${slots} Sisa)`;

        // Highlight selected row in DataTable
        document.querySelectorAll('#doorprizes-datatable tbody tr').forEach(r => {
            r.classList.remove('bg-amber-100/70', 'font-bold');
        });
        const activeRow = document.getElementById('prize-row-' + id);
        if (activeRow) {
            activeRow.classList.add('bg-amber-100/70', 'font-bold');
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
            alert('Pilih hadiah pada tabel di atas terlebih dahulu.');
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
            // Keep spinning for 3.2 seconds of excitement before decelerating
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
                }, 600);
            }, 3000);
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

        // Update remaining slots in table & state
        selectedPrizeSlots = data.doorprize.remaining_slots;
        const remEl = document.getElementById('prize-remaining-' + data.doorprize.id);
        if (remEl) {
            remEl.innerText = `${data.doorprize.remaining_slots} Unit`;
            if (data.doorprize.remaining_slots <= 0) {
                remEl.className = 'px-2.5 py-1 rounded-full text-xs font-mono font-bold bg-rose-100 text-rose-900 border border-rose-300';
            }
        }
        document.getElementById('active-prize-slots').innerText = `(${data.doorprize.remaining_slots} Sisa)`;

        // Append to logs table dynamically
        appendWinnerToTable(data.winner, data.doorprize);

        // Celebratory sound and gentle confetti behind
        if (window.SoundEffects && window.SoundEffects.success) window.SoundEffects.success();
        if (window.confetti) {
            window.confetti({
                particleCount: 60,
                spread: 75,
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
        tr.className = 'hover:bg-slate-50 transition bg-amber-50/60 font-medium';
        tr.innerHTML = `
            <td class="py-3 px-3 font-mono text-slate-500 text-xs">${winner.won_at}</td>
            <td class="py-3 px-3 font-bold text-amber-900">
                <span class="px-2.5 py-1 rounded-lg bg-amber-50 border border-amber-200 inline-flex items-center space-x-1.5">
                    <span>🎁</span>
                    <span>${prize.title}</span>
                </span>
            </td>
            <td class="py-3 px-3 font-black text-slate-900">${winner.nama}</td>
            <td class="py-3 px-3 font-mono text-blue-700 font-bold">${winner.nik}</td>
            <td class="py-3 px-3 text-slate-600">${winner.dept}</td>
            <td class="py-3 px-3">
                <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[11px] font-black bg-amber-100 text-amber-900 border border-amber-300">
                    <span>⏳</span>
                    <span>Belum Diambil</span>
                </span>
            </td>
            <td class="py-3 px-3 text-slate-400 text-xs">-</td>
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

    function filterDoorprizeTable() {
        const query = (document.getElementById('doorprize-search')?.value || '').toLowerCase().trim();
        const cat = (document.getElementById('doorprize-category-filter')?.value || '').toLowerCase().trim();
        const rows = document.querySelectorAll('#doorprizes-datatable tbody tr');

        rows.forEach(r => {
            if (r.cells.length < 3) return; // skip empty placeholder
            const text = r.innerText.toLowerCase();
            const catCell = (r.cells[2]?.innerText || '').toLowerCase();
            const matchesQuery = !query || text.includes(query);
            const matchesCat = !cat || catCell.includes(cat);

            r.style.display = (matchesQuery && matchesCat) ? '' : 'none';
        });
    }

    function filterWinnerTable() {
        const query = (document.getElementById('winner-search')?.value || '').toLowerCase().trim();
        const status = (document.getElementById('winner-status-filter')?.value || '').toLowerCase().trim();
        const rows = document.querySelectorAll('#winners-datatable tbody tr');

        rows.forEach(r => {
            if (r.cells.length < 5) return; // skip empty placeholder
            const text = r.innerText.toLowerCase();
            const statusCell = (r.cells[5]?.innerText || '').toLowerCase();
            const matchesQuery = !query || text.includes(query);
            const matchesStatus = !status || statusCell.includes(status);

            r.style.display = (matchesQuery && matchesStatus) ? '' : 'none';
        });
    }
</script>
@endpush
@endsection
