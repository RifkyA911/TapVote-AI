@extends('layouts.app')

@section('title', __('Live Count Cooperative Election'))

@section('content')
<div class="flex-1 flex flex-col p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full">

    <!-- Top Navigation & Live Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 mb-6 border-b border-slate-200">
        <div class="flex items-center space-x-3.5">
            <div class="w-11 h-11 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-sm shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div>
                <div class="flex items-center space-x-2.5">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">{{ __('Live Count Cooperative Election') }}</h1>
                    <span id="sse-status-badge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                        <span class="w-2 h-2 mr-1.5 rounded-full bg-emerald-600 animate-ping"></span>
                        <span id="sse-status-text">Live SSE</span>
                    </span>
                </div>
                <p class="text-xs text-slate-500 font-medium">
                    {{ __('Real-time Updates') }} • <span id="last-updated-text" class="font-semibold text-slate-700">{{ $metrics['last_updated'] }}</span>
                </p>
            </div>
        </div>

        <!-- Quick Access Buttons & Language Switcher -->
        <div class="flex items-center gap-3">
            <!-- Language Switcher Pill -->
            <div class="inline-flex rounded-lg border border-slate-200 bg-white p-0.5 text-xs font-bold shadow-2xs">
                <a href="{{ route('lang.switch', 'en') }}" class="px-2.5 py-1 rounded-md transition {{ app()->getLocale() === 'en' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-blue-600' }}">EN</a>
                <a href="{{ route('lang.switch', 'id') }}" class="px-2.5 py-1 rounded-md transition {{ app()->getLocale() === 'id' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-blue-600' }}">ID</a>
            </div>

            <a href="{{ route('voter.tap') }}" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm shadow-sm transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004 11m0 0a8 8 0 00.99 7.132"></path></svg>
                <span>{{ __('Open Voter Kiosk (Tap Card)') }}</span>
            </a>
            <a href="{{ route('admin.login') }}" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 font-semibold text-xs sm:text-sm border border-slate-200 transition flex items-center space-x-1.5">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                <span>{{ __('Admin Panel') }}</span>
            </a>
        </div>
    </header>

    <!-- Executive Public Election Overview Banner -->
    <div class="p-5 sm:p-6 rounded-3xl bg-white border border-slate-200 shadow-2xs mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
            
            <!-- Turnout Percentage & Progress -->
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">{{ __('Voter Participation Rate') }}</span>
                <div class="flex items-baseline space-x-2">
                    <span id="stat-turnout-pct" class="text-3xl sm:text-4xl font-black text-blue-600">{{ $metrics['turnout_pct'] }}%</span>
                    <span class="text-xs text-slate-500 font-semibold">(<span id="stat-total-voted">{{ $metrics['total_voted'] }}</span> / <span id="stat-total-voters">{{ $metrics['total_voters'] }}</span> {{ __('Votes') }})</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2.5 mt-2.5 overflow-hidden">
                    <div id="stat-turnout-bar" class="bg-blue-600 h-2.5 rounded-full transition-all duration-700 ease-out" style="width: {{ $metrics['turnout_pct'] }}%;"></div>
                </div>
            </div>

            <!-- Election Status & Security -->
            <div class="border-t md:border-t-0 md:border-l border-slate-200 pt-4 md:pt-0 md:pl-6">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">{{ __('Election Status') }}</span>
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-lg font-bold text-slate-800">{{ __('Active & Verified') }}</span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Dual Ballot Kiosk • RFID Mifare ISO 14443A</p>
            </div>

            <!-- Frontrunners Spotlight -->
            <div class="border-t md:border-t-0 md:border-l border-slate-200 pt-4 md:pt-0 md:pl-6">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">Kandidat Unggul Sementara</span>
                <div class="space-y-1">
                    <div class="text-xs text-slate-700 flex items-center justify-between">
                        <span class="font-medium">Ketua:</span>
                        <strong id="leader-ketua-text" class="text-blue-700 truncate max-w-[170px]">{{ $metrics['leader_ketua'] ?: '(Belum Ada Suara)' }}</strong>
                    </div>
                    <div class="text-xs text-slate-700 flex items-center justify-between">
                        <span class="font-medium">Pengawas:</span>
                        <strong id="leader-pengawas-text" class="text-emerald-700 truncate max-w-[170px]">{{ $metrics['leader_pengawas'] ?: '(Belum Ada Suara)' }}</strong>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ======================================================== -->
    <!-- ADVANCED COMPARATIVE BAR CHART SECTION                   -->
    <!-- ======================================================== -->
    <div class="mb-10 p-5 sm:p-6 rounded-3xl bg-white border border-slate-200 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 mb-6 border-b border-slate-200 gap-3">
            <div>
                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">Grafik Komparasi Hasil Suara Seluruh Kandidat</h2>
                <p class="text-xs text-slate-500 font-medium">Perbandingan perolehan suara Ketua & Pengawas secara komprehensif</p>
            </div>

            <!-- Filter Tabs for Chart Comparison -->
            <div class="inline-flex rounded-xl bg-slate-100 p-1 text-xs font-bold self-start sm:self-auto">
                <button type="button" onclick="switchChartMode('all')" id="btn-chart-all" class="px-3 py-1.5 rounded-lg bg-white text-slate-900 shadow-2xs transition">Semua Calon</button>
                <button type="button" onclick="switchChartMode('ketua')" id="btn-chart-ketua" class="px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition">Ketua Saja</button>
                <button type="button" onclick="switchChartMode('pengawas')" id="btn-chart-pengawas" class="px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition">Pengawas Saja</button>
            </div>
        </div>

        <!-- Comparative Bar Chart Container -->
        <div class="relative w-full h-80 sm:h-96">
            <canvas id="comparisonChart"></canvas>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- KANDIDAT KETUA KOPERASI SHOWCASE                         -->
    <!-- ======================================================== -->
    <section class="mb-10">
        <div class="flex items-center justify-between pb-3 mb-5 border-b border-slate-200">
            <div class="flex items-center space-x-3">
                <span class="w-8 h-8 rounded-lg bg-blue-600 text-white font-extrabold flex items-center justify-center text-sm shadow-2xs">1</span>
                <h3 class="text-lg font-bold text-slate-900">{{ __('Chairman Election Comparison') }}</h3>
            </div>
            <span class="text-xs font-bold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-full border border-blue-200">
                Total: <strong id="total-ketua-votes">{{ $metrics['total_suara_ketua'] }}</strong> {{ __('Votes') }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" id="ketua-cards-grid">
            @foreach($ketuaResults as $ketua)
                <div class="p-5 rounded-2xl bg-white border-2 border-slate-200 hover:border-blue-400 shadow-2xs transition flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="w-8 h-8 rounded-lg bg-blue-600 text-white font-black text-sm flex items-center justify-center shadow-2xs">
                                {{ $ketua['nomor_urut'] }}
                            </span>
                            <span id="ketua-leader-badge-{{ $ketua['nik'] }}" class="{{ $ketua['is_leader'] && $ketua['suara'] > 0 ? '' : 'hidden' }} px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-900 border border-amber-300">
                                ⭐ {{ __('Leading') }}
                            </span>
                        </div>

                        <div class="w-full h-44 rounded-xl overflow-hidden bg-slate-100 mb-3 border border-slate-200">
                            <img src="{{ $ketua['foto'] }}" alt="{{ $ketua['nama'] }}" class="w-full h-full object-cover object-top hover:scale-105 transition duration-300">
                        </div>

                        <h4 class="text-base font-bold text-slate-900 mb-1 leading-snug truncate" title="{{ $ketua['nama'] }}">{{ $ketua['nama'] }}</h4>
                        <p class="text-xs text-slate-500 line-clamp-2 mb-3">{{ $ketua['deskripsi'] ?: 'Kandidat resmi Pemilihan Ketua Koperasi.' }}</p>
                    </div>

                    <div>
                        <!-- Votes & Percentage Bar -->
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 mb-3">
                            <div class="flex items-baseline justify-between text-xs font-bold mb-1">
                                <span id="ketua-vote-count-{{ $ketua['nik'] }}" class="text-slate-800">{{ $ketua['suara'] }} {{ __('Votes') }}</span>
                                <span id="ketua-pct-val-{{ $ketua['nik'] }}" class="text-blue-700 font-extrabold text-sm">{{ $ketua['persen'] }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                <div id="ketua-bar-fill-{{ $ketua['nik'] }}" class="bg-blue-600 h-2 rounded-full transition-all duration-700 ease-out" style="width: {{ $ketua['persen'] }}%;"></div>
                            </div>
                        </div>

                        <!-- Button View Details Modal -->
                        <button 
                            type="button" 
                            onclick="openCandidateModal('Ketua Koperasi', '{{ addslashes($ketua['nama']) }}', '{{ $ketua['nomor_urut'] }}', `{{ addslashes($ketua['visi']) }}`, `{{ addslashes($ketua['misi']) }}`, `{{ addslashes($ketua['deskripsi']) }}`, '{{ $ketua['foto'] }}', '{{ $ketua['suara'] }}', '{{ $ketua['persen'] }}%')"
                            class="w-full py-2 px-3 rounded-xl bg-slate-100 hover:bg-blue-50 text-blue-700 font-bold text-xs border border-slate-200 transition text-center flex items-center justify-center space-x-1.5"
                        >
                            <span>{{ __('View Profile & Manifesto') }}</span>
                            <span>→</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- ======================================================== -->
    <!-- KANDIDAT PENGAWAS KOPERASI SHOWCASE                      -->
    <!-- ======================================================== -->
    <section class="mb-10">
        <div class="flex items-center justify-between pb-3 mb-5 border-b border-slate-200">
            <div class="flex items-center space-x-3">
                <span class="w-8 h-8 rounded-lg bg-emerald-600 text-white font-extrabold flex items-center justify-center text-sm shadow-2xs">2</span>
                <h3 class="text-lg font-bold text-slate-900">{{ __('Supervisory Board Comparison') }}</h3>
            </div>
            <span class="text-xs font-bold text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                Total: <strong id="total-pengawas-votes">{{ $metrics['total_suara_pengawas'] }}</strong> {{ __('Votes') }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" id="pengawas-cards-grid">
            @foreach($pengawasResults as $pengawas)
                <div class="p-5 rounded-2xl bg-white border-2 border-slate-200 hover:border-emerald-400 shadow-2xs transition flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="w-8 h-8 rounded-lg bg-emerald-600 text-white font-black text-sm flex items-center justify-center shadow-2xs">
                                {{ $pengawas['nomor_urut'] }}
                            </span>
                            <span id="pengawas-leader-badge-{{ $pengawas['nik'] }}" class="{{ $pengawas['is_leader'] && $pengawas['suara'] > 0 ? '' : 'hidden' }} px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-900 border border-amber-300">
                                ⭐ {{ __('Leading') }}
                            </span>
                        </div>

                        <div class="w-full h-44 rounded-xl overflow-hidden bg-slate-100 mb-3 border border-slate-200">
                            <img src="{{ $pengawas['foto'] }}" alt="{{ $pengawas['nama'] }}" class="w-full h-full object-cover object-top hover:scale-105 transition duration-300">
                        </div>

                        <h4 class="text-base font-bold text-slate-900 mb-1 leading-snug truncate" title="{{ $pengawas['nama'] }}">{{ $pengawas['nama'] }}</h4>
                        <p class="text-xs text-slate-500 line-clamp-2 mb-3">{{ $pengawas['deskripsi'] ?: 'Kandidat resmi Pemilihan Pengawas Koperasi.' }}</p>
                    </div>

                    <div>
                        <!-- Votes & Percentage Bar -->
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 mb-3">
                            <div class="flex items-baseline justify-between text-xs font-bold mb-1">
                                <span id="pengawas-vote-count-{{ $pengawas['nik'] }}" class="text-slate-800">{{ $pengawas['suara'] }} {{ __('Votes') }}</span>
                                <span id="pengawas-pct-val-{{ $pengawas['nik'] }}" class="text-emerald-700 font-extrabold text-sm">{{ $pengawas['persen'] }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                <div id="pengawas-bar-fill-{{ $pengawas['nik'] }}" class="bg-emerald-600 h-2 rounded-full transition-all duration-700 ease-out" style="width: {{ $pengawas['persen'] }}%;"></div>
                            </div>
                        </div>

                        <!-- Button View Details Modal -->
                        <button 
                            type="button" 
                            onclick="openCandidateModal('Pengawas Koperasi', '{{ addslashes($pengawas['nama']) }}', '{{ $pengawas['nomor_urut'] }}', `{{ addslashes($pengawas['visi']) }}`, `{{ addslashes($pengawas['misi']) }}`, `{{ addslashes($pengawas['deskripsi']) }}`, '{{ $pengawas['foto'] }}', '{{ $pengawas['suara'] }}', '{{ $pengawas['persen'] }}%')"
                            class="w-full py-2 px-3 rounded-xl bg-slate-100 hover:bg-emerald-50 text-emerald-800 font-bold text-xs border border-slate-200 transition text-center flex items-center justify-center space-x-1.5"
                        >
                            <span>{{ __('View Profile & Manifesto') }}</span>
                            <span>→</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

</div>

<!-- ======================================================== -->
<!-- MODAL DETAIL LENGKAP KANDIDAT & VISI MISI                -->
<!-- ======================================================== -->
<div id="candidate-detail-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl max-w-xl w-full p-6 shadow-2xl relative max-h-[85vh] overflow-y-auto">
        <button onclick="closeCandidateModal()" class="absolute top-4 right-4 text-slate-500 hover:text-slate-800 p-2 rounded-xl bg-slate-100 text-lg font-bold">✕</button>

        <div class="flex items-center space-x-4 mb-5 pb-4 border-b border-slate-200">
            <img id="detail-modal-foto" src="" alt="Foto" class="w-16 h-16 rounded-2xl object-cover object-top border-2 border-slate-200 shrink-0">
            <div>
                <div class="flex items-center space-x-2">
                    <span id="detail-modal-badge" class="px-2 py-0.5 rounded text-[10px] font-black bg-blue-100 text-blue-800 uppercase">Calon Ketua</span>
                    <span id="detail-modal-no" class="text-xs font-bold text-slate-500">No. 01</span>
                </div>
                <h3 id="detail-modal-nama" class="text-lg sm:text-xl font-extrabold text-slate-900 mt-0.5">Nama Kandidat</h3>
                <span id="detail-modal-tally" class="text-xs font-bold text-blue-600 block mt-0.5">0 Suara (0%)</span>
            </div>
        </div>

        <div class="space-y-4">
            <!-- Profil Singkat / Track Record -->
            <div>
                <h4 class="text-xs uppercase font-bold tracking-wider text-slate-500 mb-1.5">Latar Belakang / Profil Singkat:</h4>
                <p id="detail-modal-deskripsi" class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 text-xs sm:text-sm leading-relaxed font-medium"></p>
            </div>

            <!-- Visi -->
            <div>
                <h4 class="text-xs uppercase font-bold tracking-wider text-slate-500 mb-1.5">{{ __('Vision') }}:</h4>
                <div id="detail-modal-visi" class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 text-xs sm:text-sm leading-relaxed whitespace-pre-line font-medium"></div>
            </div>

            <!-- Misi -->
            <div>
                <h4 class="text-xs uppercase font-bold tracking-wider text-slate-500 mb-1.5">{{ __('Mission') }}:</h4>
                <div id="detail-modal-misi" class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 text-xs sm:text-sm leading-relaxed whitespace-pre-line font-medium"></div>
            </div>
        </div>

        <div class="mt-6 pt-3 border-t border-slate-200 flex justify-end">
            <button onclick="closeCandidateModal()" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition">
                {{ __('Close') }}
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let rawKetuaData = @json($ketuaResults);
    let rawPengawasData = @json($pengawasResults);
    let currentChartMode = 'all'; // 'all', 'ketua', 'pengawas'
    let comparisonChart = null;

    document.addEventListener('DOMContentLoaded', function() {
        initComparisonChart();
        initLiveSSE();
    });

    // 1. Inisialisasi Advanced Comparative Bar Chart (Chart.js)
    function initComparisonChart() {
        if (typeof Chart === 'undefined') return;
        const ctx = document.getElementById('comparisonChart').getContext('2d');

        const { labels, datasets } = generateChartData(currentChartMode);

        comparisonChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 800,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { family: 'Plus Jakarta Sans', weight: 'bold', size: 12 },
                            usePointStyle: true,
                            boxWidth: 8
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleFont: { family: 'Plus Jakarta Sans', weight: 'bold', size: 13 },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                        padding: 12,
                        cornerRadius: 10,
                        callbacks: {
                            label: function(context) {
                                return ` ${context.dataset.label}: ${context.parsed.y} Suara`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { family: 'Plus Jakarta Sans', weight: 'bold' }
                        },
                        grid: {
                            color: '#f1f5f9'
                        }
                    },
                    x: {
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', weight: 'bold', size: 11 }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    function generateChartData(mode) {
        if (mode === 'ketua') {
            return {
                labels: rawKetuaData.map(k => 'No. ' + k.nomor_urut + ' ' + k.nama),
                datasets: [{
                    label: 'Perolehan Suara Calon Ketua',
                    data: rawKetuaData.map(k => k.suara),
                    backgroundColor: 'rgba(37, 99, 235, 0.85)',
                    borderColor: '#2563eb',
                    borderWidth: 2,
                    borderRadius: 8,
                }]
            };
        } else if (mode === 'pengawas') {
            return {
                labels: rawPengawasData.map(p => 'No. ' + p.nomor_urut + ' ' + p.nama),
                datasets: [{
                    label: 'Perolehan Suara Calon Pengawas',
                    data: rawPengawasData.map(p => p.suara),
                    backgroundColor: 'rgba(5, 150, 105, 0.85)',
                    borderColor: '#059669',
                    borderWidth: 2,
                    borderRadius: 8,
                }]
            };
        } else {
            // Mode All (Side-by-Side Comparative)
            const maxLen = Math.max(rawKetuaData.length, rawPengawasData.length);
            const labels = [];
            for (let i = 1; i <= maxLen; i++) {
                labels.push('Kandidat Nomor ' + i);
            }

            return {
                labels: labels,
                datasets: [
                    {
                        label: 'Calon Ketua Koperasi',
                        data: rawKetuaData.map(k => k.suara),
                        backgroundColor: 'rgba(37, 99, 235, 0.85)',
                        borderColor: '#2563eb',
                        borderWidth: 2,
                        borderRadius: 8,
                    },
                    {
                        label: 'Calon Pengawas Koperasi',
                        data: rawPengawasData.map(p => p.suara),
                        backgroundColor: 'rgba(5, 150, 105, 0.85)',
                        borderColor: '#059669',
                        borderWidth: 2,
                        borderRadius: 8,
                    }
                ]
            };
        }
    }

    function switchChartMode(mode) {
        if (window.SoundEffects) window.SoundEffects.click();
        currentChartMode = mode;

        document.getElementById('btn-chart-all').className = mode === 'all' ? 'px-3 py-1.5 rounded-lg bg-white text-slate-900 shadow-2xs transition' : 'px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition';
        document.getElementById('btn-chart-ketua').className = mode === 'ketua' ? 'px-3 py-1.5 rounded-lg bg-white text-slate-900 shadow-2xs transition' : 'px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition';
        document.getElementById('btn-chart-pengawas').className = mode === 'pengawas' ? 'px-3 py-1.5 rounded-lg bg-white text-slate-900 shadow-2xs transition' : 'px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition';

        if (comparisonChart) {
            const { labels, datasets } = generateChartData(mode);
            comparisonChart.data.labels = labels;
            comparisonChart.data.datasets = datasets;
            comparisonChart.update();
        }
    }

    // 2. Realtime SSE Handler
    function initLiveSSE() {
        const badge = document.getElementById('sse-status-badge');
        const text = document.getElementById('sse-status-text');

        if (typeof EventSource !== 'undefined') {
            const source = new EventSource("{{ route('live.stream') }}");

            source.onopen = function() {
                badge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300';
                text.innerText = 'Live SSE';
            };

            source.onmessage = function(event) {
                try {
                    const data = JSON.parse(event.data);
                    updateLiveDisplay(data);
                } catch (e) {
                    console.error('SSE Error:', e);
                }
            };

            source.onerror = function() {
                badge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300';
                text.innerText = 'Polling';
                source.close();
                setInterval(pollData, 5000);
            };
        } else {
            setInterval(pollData, 5000);
        }
    }

    function pollData() {
        fetch("{{ route('live.data') }}")
            .then(res => res.json())
            .then(data => updateLiveDisplay(data))
            .catch(err => console.error('Poll failed:', err));
    }

    function updateLiveDisplay(data) {
        if (!data || !data.metrics) return;

        // Update High-Level Public Metrics
        document.getElementById('stat-turnout-pct').innerText = data.metrics.turnout_pct + '%';
        document.getElementById('stat-total-voted').innerText = data.metrics.total_voted;
        document.getElementById('stat-total-voters').innerText = data.metrics.total_voters;
        document.getElementById('stat-turnout-bar').style.width = data.metrics.turnout_pct + '%';
        document.getElementById('last-updated-text').innerText = data.metrics.last_updated;

        if (data.metrics.leader_ketua) {
            document.getElementById('leader-ketua-text').innerText = data.metrics.leader_ketua;
        }
        if (data.metrics.leader_pengawas) {
            document.getElementById('leader-pengawas-text').innerText = data.metrics.leader_pengawas;
        }

        document.getElementById('total-ketua-votes').innerText = data.metrics.total_suara_ketua;
        document.getElementById('total-pengawas-votes').innerText = data.metrics.total_suara_pengawas;

        // Update Ketua Data
        if (data.ketuaResults) {
            rawKetuaData = data.ketuaResults;
            rawKetuaData.forEach(k => {
                const countEl = document.getElementById('ketua-vote-count-' + k.nik);
                const pctEl = document.getElementById('ketua-pct-val-' + k.nik);
                const barEl = document.getElementById('ketua-bar-fill-' + k.nik);
                const leaderBadge = document.getElementById('ketua-leader-badge-' + k.nik);

                if (countEl) countEl.innerText = k.suara + ' {{ __("Votes") }}';
                if (pctEl) pctEl.innerText = k.persen + '%';
                if (barEl) barEl.style.width = k.persen + '%';
                if (leaderBadge) {
                    if (k.is_leader && k.suara > 0) {
                        leaderBadge.classList.remove('hidden');
                    } else {
                        leaderBadge.classList.add('hidden');
                    }
                }
            });
        }

        // Update Pengawas Data
        if (data.pengawasResults) {
            rawPengawasData = data.pengawasResults;
            rawPengawasData.forEach(p => {
                const countEl = document.getElementById('pengawas-vote-count-' + p.nik);
                const pctEl = document.getElementById('pengawas-pct-val-' + p.nik);
                const barEl = document.getElementById('pengawas-bar-fill-' + p.nik);
                const leaderBadge = document.getElementById('pengawas-leader-badge-' + p.nik);

                if (countEl) countEl.innerText = p.suara + ' {{ __("Votes") }}';
                if (pctEl) pctEl.innerText = p.persen + '%';
                if (barEl) barEl.style.width = p.persen + '%';
                if (leaderBadge) {
                    if (p.is_leader && p.suara > 0) {
                        leaderBadge.classList.remove('hidden');
                    } else {
                        leaderBadge.classList.add('hidden');
                    }
                }
            });
        }

        // Update Chart
        if (comparisonChart) {
            const { labels, datasets } = generateChartData(currentChartMode);
            comparisonChart.data.labels = labels;
            comparisonChart.data.datasets = datasets;
            comparisonChart.update('none');
        }
    }

    // 3. Modal Detail Calon
    function openCandidateModal(kategori, nama, nomor, visi, misi, deskripsi, foto, suara, persen) {
        if (window.SoundEffects) window.SoundEffects.modal();

        document.getElementById('detail-modal-foto').src = foto;
        document.getElementById('detail-modal-nama').innerText = nama;
        document.getElementById('detail-modal-no').innerText = 'No. ' + nomor;
        document.getElementById('detail-modal-badge').innerText = kategori;
        document.getElementById('detail-modal-badge').className = kategori.includes('Ketua') ? 'px-2 py-0.5 rounded text-[10px] font-black bg-blue-100 text-blue-800 uppercase' : 'px-2 py-0.5 rounded text-[10px] font-black bg-emerald-100 text-emerald-800 uppercase';
        document.getElementById('detail-modal-tally').innerText = `${suara} Suara (${persen})`;

        document.getElementById('detail-modal-deskripsi').innerText = deskripsi || 'Calon terdaftar resmi.';
        document.getElementById('detail-modal-visi').innerText = visi || '-';
        document.getElementById('detail-modal-misi').innerText = misi || '-';

        document.getElementById('candidate-detail-modal').classList.remove('hidden');
    }

    function closeCandidateModal() {
        if (window.SoundEffects) window.SoundEffects.click();
        document.getElementById('candidate-detail-modal').classList.add('hidden');
    }
</script>
@endpush
@endsection
