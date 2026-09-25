@extends('layouts.admin')

@section('title', 'Analytics - TapVote AI')

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- Top Analytics Header -->
    <div class="p-6 sm:p-7 rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white border border-indigo-900/60 shadow-xl relative overflow-hidden">
        <div class="absolute -right-24 -top-24 w-80 h-80 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-24 -bottom-24 w-80 h-80 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center space-x-2 mb-2">
                    <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-indigo-500/30 text-indigo-300 border border-indigo-400/30">
                        🛰️ Telemetry Analytics
                    </span>
                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-mono font-bold bg-white/10 text-emerald-400 border border-white/15">
                        Live Database Stream
                    </span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Analytics</h2>
                <p class="text-xs sm:text-sm text-indigo-200/80 mt-1 max-w-2xl font-medium">
                    Pusat observasi komprehensif metrik pemilu: lonjakan partisipasi per departemen, histori kecepatan voting, audit keaslian kartu RFID, dan deteksi anomali.
                </p>
            </div>

            <div class="flex items-center gap-2.5 self-start md:self-auto">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/15 transition flex items-center space-x-1.5 cursor-pointer">
                    <span>← Kembali ke Dashboard</span>
                </a>
                <button type="button" onclick="window.location.reload()" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs shadow-md transition flex items-center space-x-1.5 cursor-pointer">
                    <span>↻ Segarkan Data</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 4 High-Altitude KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <!-- Metric 1: Quorum & Turnout -->
        <div class="p-5 rounded-3xl bg-white border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-extrabold tracking-wider text-slate-500">Tingkat Partisipasi</span>
                <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase {{ $quorumMet ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                    {{ $quorumMet ? 'Quorum Sah' : 'Menuju Quorum' }}
                </span>
            </div>
            <div class="my-3 flex items-baseline space-x-2">
                <span class="text-3xl sm:text-4xl font-black text-blue-600 font-mono">{{ $turnoutPct }}%</span>
                <span class="text-xs text-slate-500 font-semibold">Target: {{ $quorumThreshold }}%</span>
            </div>
            <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                <div class="h-full bg-blue-600 rounded-full transition-all duration-700" style="width: {{ min(100, $turnoutPct) }}%;"></div>
            </div>
        </div>

        <!-- Metric 2: Total Suara Sah vs Pending -->
        <div class="p-5 rounded-3xl bg-white border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-extrabold tracking-wider text-emerald-700">Total Suara Masuk</span>
                <span class="w-7 h-7 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">✓</span>
            </div>
            <div class="my-3 flex items-baseline space-x-2">
                <span class="text-3xl sm:text-4xl font-black text-emerald-600 font-mono">{{ $totalVoted }}</span>
                <span class="text-xs text-slate-500 font-semibold">/ {{ $totalVoters }} Anggota</span>
            </div>
            <span class="text-xs text-slate-500 font-medium">Sisa belum memilih: <strong class="text-amber-600 font-mono">{{ $remaining }}</strong></span>
        </div>

        <!-- Metric 3: Peak Voting Hour -->
        <div class="p-5 rounded-3xl bg-white border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-extrabold tracking-wider text-indigo-700">Jam Lonjakan Puncak</span>
                <span class="text-lg">⚡</span>
            </div>
            <div class="my-3 flex items-baseline space-x-2">
                <span class="text-3xl sm:text-4xl font-black text-indigo-600 font-mono">{{ $peakHourLabel }}</span>
                <span class="text-xs text-slate-500 font-semibold">WIB</span>
            </div>
            <span class="text-xs text-slate-500 font-medium">Volume tertinggi: <strong class="text-indigo-700 font-mono">{{ $peakHourVotes }} suara/jam</strong></span>
        </div>

        <!-- Metric 4: RFID Pass Rate -->
        <div class="p-5 rounded-3xl bg-white border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-extrabold tracking-wider text-slate-500">Integritas Kartu RFID</span>
                <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase bg-blue-100 text-blue-800">Mifare 13.56M</span>
            </div>
            <div class="my-3 flex items-baseline space-x-2">
                <span class="text-3xl sm:text-4xl font-black text-slate-900 font-mono">{{ $rfidAuthenticityRate }}%</span>
                <span class="text-xs text-slate-500 font-semibold">Pass Rate</span>
            </div>
            <span class="text-xs text-slate-500 font-medium">Kartu asing dicegah: <strong class="text-rose-600 font-mono">{{ $unknownCardAttempts }} kali</strong></span>
        </div>
    <!-- AI DEEP TELEMETRY & REASONING CENTER -->
    <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white border border-indigo-900/60 shadow-xl relative overflow-hidden">
        <div class="absolute -right-24 -top-24 w-80 h-80 rounded-full bg-indigo-500/15 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-24 -bottom-24 w-80 h-80 rounded-full bg-blue-500/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-5 border-b border-indigo-800/40 gap-4">
                <div class="flex items-center space-x-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-500 to-blue-500 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="text-lg sm:text-xl font-black text-white tracking-tight">AI Deep Telemetry Reasoning & Disparity Analysis</h3>
                            <span id="analytics-ai-engine-badge" class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-400/30">
                                Google Gemini 2.0 Flash / Heuristic
                            </span>
                        </div>
                        <p class="text-xs text-indigo-200/70 mt-0.5">Penalaran mendalam kurva kecepatan voting, disparitas antar departemen, dan proyeksi kuorum</p>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    <button 
                        id="btn-run-analytics-ai" 
                        type="button" 
                        onclick="runAnalyticsAiReasoning()" 
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white text-xs sm:text-sm font-extrabold shadow-lg shadow-indigo-500/25 transition cursor-pointer flex items-center space-x-2"
                    >
                        <span id="analytics-ai-btn-icon">⚡</span>
                        <span id="analytics-ai-btn-text">Analisis dengan AI Reasoning</span>
                    </button>
                </div>
            </div>

            <!-- AI Output Dynamic Container -->
            <div id="analytics-ai-container" class="space-y-4">
                <!-- Initial State -->
                <div class="p-5 rounded-2xl bg-indigo-950/60 border border-indigo-800/40 text-center py-8">
                    <span class="text-3xl block mb-2">🧠</span>
                    <h4 class="text-sm font-black text-white">Deep AI Telemetry Reasoning Siap Dijalankan</h4>
                    <p class="text-xs text-indigo-200/70 max-w-md mx-auto mt-1">
                        Klik tombol di atas untuk menganalisis data telemetri, disparitas tingkat absensi departemen, lonjakan voting, dan rekomendasi strategis panitia pemilihan.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row: 24-Hour Velocity & Department Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8">
        <!-- Left: Hourly Velocity Histogram -->
        <div class="lg:col-span-7 p-5 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base sm:text-lg font-black text-slate-900">Distribusi Kecepatan Suara per Jam</h3>
                    <p class="text-xs text-slate-500">Histori ritme kedatangan anggota di bilik suara dari waktu ke waktu</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-mono font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    24-Hour Spectrum
                </span>
            </div>

            <div id="chartHourlyVelocity" class="w-full min-h-[300px]"></div>
        </div>

        <!-- Right: Department Turnout Ranking -->
        <div class="lg:col-span-5 p-5 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base sm:text-lg font-black text-slate-900">Peringkat Partisipasi Departemen</h3>
                    <p class="text-xs text-slate-500">Persentase suara masuk di tiap unit kerja koperasi</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-mono font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                    {{ count($deptStats) }} Dept
                </span>
            </div>

            <div id="chartDeptRanking" class="w-full min-h-[300px]"></div>
        </div>
    </div>

    <!-- Department Detail Breakdown Table -->
    <div class="p-5 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base sm:text-lg font-black text-slate-900">Matriks Partisipasi Seluruh Departemen</h3>
                <p class="text-xs text-slate-500">Data kuota absensi, suara sah tercatat, dan sisa belum memilih per departemen</p>
            </div>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-200">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-extrabold uppercase text-[10px] tracking-wider">
                        <th class="py-3 px-4">Nama Departemen</th>
                        <th class="py-3 px-4 text-center">Total Anggota</th>
                        <th class="py-3 px-4 text-center">Suara Masuk</th>
                        <th class="py-3 px-4 text-center">Belum Memilih</th>
                        <th class="py-3 px-4 text-center">Partisipasi</th>
                        <th class="py-3 px-4 w-44">Progress Bar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($deptStats as $dept)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-bold text-slate-900">{{ $dept['dept'] }}</td>
                            <td class="py-3 px-4 text-center font-mono font-bold text-slate-700">{{ $dept['total'] }}</td>
                            <td class="py-3 px-4 text-center font-mono font-bold text-emerald-700">{{ $dept['voted'] }}</td>
                            <td class="py-3 px-4 text-center font-mono font-bold text-amber-700">{{ $dept['pending'] }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-mono font-black {{ $dept['pct'] >= 50 ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $dept['pct'] }}%
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="w-full bg-slate-200 h-2.5 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full" style="width: {{ $dept['pct'] }}%;"></div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Security & Hardware Anomaly Audit Log -->
    <div class="p-5 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base sm:text-lg font-black text-slate-900">Audit Forensik Keamanan & Hardware Scanner</h3>
                <p class="text-xs text-slate-500">Log insiden kartu tidak dikenal, pencegahan double voting, dan histori anomali sistem</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-mono font-bold bg-rose-50 text-rose-800 border border-rose-200">
                {{ count($recentSecurityEvents) }} Event Terdeteksi
            </span>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-200">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-extrabold uppercase text-[10px] tracking-wider">
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4">Aksi / Event</th>
                        <th class="py-3 px-4">Aktor</th>
                        <th class="py-3 px-4">Rincian Deskripsi</th>
                        <th class="py-3 px-4 font-mono">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($recentSecurityEvents as $ev)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-mono text-xs text-slate-500">{{ $ev->created_at->format('d/m H:i:s') }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-100 text-rose-800 border border-rose-300">
                                    {{ $ev->action }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-bold text-slate-800">{{ $ev->actor }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $ev->description }}</td>
                            <td class="py-3 px-4 font-mono text-xs text-slate-500">{{ $ev->ip_address ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-400">Tidak ada anomali atau ancaman keamanan yang terdeteksi. Sistem berjalan 100% normal.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Hourly Velocity Spline Area Chart
        const hourlyOptions = {
            series: [{
                name: 'Volume Suara Masuk',
                data: @json($hoursValues)
            }],
            chart: {
                type: 'area',
                height: 300,
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                toolbar: { show: false },
                animations: { enabled: true, easing: 'easeinout', speed: 800 }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2.5, colors: ['#4f46e5'] },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.5,
                    opacityTo: 0.05,
                    stops: [0, 95, 100],
                    colorStops: [
                        { offset: 0, color: '#4f46e5', opacity: 0.5 },
                        { offset: 100, color: '#4f46e5', opacity: 0.0 }
                    ]
                }
            },
            colors: ['#4f46e5'],
            xaxis: {
                categories: @json($hoursLabels),
                labels: { style: { colors: '#64748b', fontSize: '11px', fontWeight: 600 } },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: { style: { colors: '#64748b', fontSize: '11px', fontWeight: 600 } },
                min: 0,
                forceNiceScale: true
            },
            grid: { borderColor: '#f1f5f9' },
            tooltip: {
                theme: 'dark',
                y: { formatter: val => val + ' Suara' }
            }
        };
        new ApexCharts(document.querySelector("#chartHourlyVelocity"), hourlyOptions).render();

        // 2. Department Turnout Bar Chart
        const deptNames = @json($deptStats->pluck('dept'));
        const deptPcts = @json($deptStats->pluck('pct'));
        const deptOptions = {
            series: [{
                name: 'Partisipasi (%)',
                data: deptPcts
            }],
            chart: {
                type: 'bar',
                height: 300,
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    horizontal: true,
                    barHeight: '55%',
                    distributed: false
                }
            },
            colors: ['#059669'],
            dataLabels: {
                enabled: true,
                formatter: val => val + '%',
                style: { fontSize: '11px', fontWeight: 'bold', colors: ['#ffffff'] }
            },
            xaxis: {
                categories: deptNames,
                max: 100,
                labels: { style: { colors: '#64748b', fontSize: '11px', fontWeight: 600 } }
            },
            yaxis: {
                labels: { style: { colors: '#1e293b', fontSize: '11px', fontWeight: 700 } }
            },
            grid: { borderColor: '#f1f5f9' },
            tooltip: {
                theme: 'dark',
                y: { formatter: val => val + '%' }
            }
        };
        new ApexCharts(document.querySelector("#chartDeptRanking"), deptOptions).render();
    });

    async function runAnalyticsAiReasoning() {
        const btn = document.getElementById('btn-run-analytics-ai');
        const icon = document.getElementById('analytics-ai-btn-icon');
        const text = document.getElementById('analytics-ai-btn-text');
        const container = document.getElementById('analytics-ai-container');
        const engineBadge = document.getElementById('analytics-ai-engine-badge');

        if (!btn || !container) return;

        btn.disabled = true;
        btn.classList.add('opacity-70', 'cursor-not-allowed');
        icon.innerHTML = '<span class="inline-block animate-spin">⚙️</span>';
        text.innerText = 'Menalar Data Telemetri...';

        container.innerHTML = `
            <div class="p-6 rounded-2xl bg-indigo-950/60 border border-indigo-800/40 text-center py-10 space-y-3">
                <div class="inline-block animate-spin text-3xl">🌀</div>
                <h4 class="text-sm font-bold text-indigo-200">AI sedang memproses telemetri 24 jam & disparitas partisipasi...</h4>
                <p class="text-xs text-indigo-300/60">Mengevaluasi kecepatan aliran surat suara, anomali lonjakan, dan gap antar departemen</p>
            </div>
        `;

        try {
            const res = await fetch("{{ route('admin.analytics.ai') }}", {
                headers: { 'Accept': 'application/json' }
            });
            const payload = await res.json();

            if (res.ok && payload.success && payload.data) {
                const d = payload.data;
                if (engineBadge && d.engine) {
                    engineBadge.innerText = `${d.engine} • ${d.timestamp || ''}`;
                }

                const riskColor = d.risk_level === 'LOW' 
                    ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30' 
                    : (d.risk_level === 'MEDIUM' ? 'bg-amber-500/20 text-amber-300 border-amber-500/30' : 'bg-rose-500/20 text-rose-300 border-rose-500/30');

                let recsHtml = '';
                if (Array.isArray(d.strategic_recommendations)) {
                    recsHtml = d.strategic_recommendations.map(r => `
                        <li class="flex items-start space-x-2 text-xs text-slate-200">
                            <span class="text-emerald-400 font-bold shrink-0">✓</span>
                            <span>${r}</span>
                        </li>
                    `).join('');
                }

                container.innerHTML = `
                    <div class="space-y-4 animate-fadeIn">
                        <!-- Top Reasoning Summary & Score -->
                        <div class="p-5 rounded-2xl bg-indigo-900/40 border border-indigo-700/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <span class="text-[10px] font-black uppercase tracking-widest text-indigo-300">Executive Analytical Summary</span>
                                <p class="text-xs sm:text-sm text-slate-100 font-medium leading-relaxed">${d.executive_summary || '-'}</p>
                            </div>
                            <div class="flex items-center space-x-3 shrink-0">
                                <div class="p-3 rounded-xl bg-white/5 border border-white/10 text-center">
                                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Quorum Confidence</span>
                                    <span class="text-lg font-black text-indigo-400 font-mono">${d.quorum_confidence_score || 95}%</span>
                                </div>
                                <div class="px-3 py-2 rounded-xl border text-xs font-black uppercase ${riskColor}">
                                    Risk: ${d.risk_level || 'LOW'}
                                </div>
                            </div>
                        </div>

                        <!-- 3 Pillars of Deep Telemetry Analysis -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="p-4 rounded-2xl bg-white/5 border border-white/10 space-y-1.5">
                                <div class="flex items-center space-x-2 text-xs font-extrabold text-blue-300">
                                    <span>📈</span>
                                    <span>Kurva Kecepatan Suara</span>
                                </div>
                                <p class="text-xs text-slate-300 leading-relaxed">${d.turnout_velocity_reasoning || '-'}</p>
                            </div>

                            <div class="p-4 rounded-2xl bg-white/5 border border-white/10 space-y-1.5">
                                <div class="flex items-center space-x-2 text-xs font-extrabold text-emerald-300">
                                    <span>🏢</span>
                                    <span>Disparitas Departemen</span>
                                </div>
                                <p class="text-xs text-slate-300 leading-relaxed">${d.department_disparity_analysis || '-'}</p>
                            </div>

                            <div class="p-4 rounded-2xl bg-white/5 border border-white/10 space-y-1.5">
                                <div class="flex items-center space-x-2 text-xs font-extrabold text-amber-300">
                                    <span>⚡</span>
                                    <span>Jam Sibuk & Pola Anomali</span>
                                </div>
                                <p class="text-xs text-slate-300 leading-relaxed">${d.peak_hours_anomaly_assessment || '-'}</p>
                            </div>
                        </div>

                        <!-- Strategic Recommendations -->
                        <div class="p-4 rounded-2xl bg-slate-900/60 border border-indigo-900/60 space-y-2">
                            <h5 class="text-xs font-black uppercase tracking-wider text-indigo-300">Rekomendasi Strategis Panitia Pemilihan:</h5>
                            <ul class="space-y-1.5">${recsHtml}</ul>
                        </div>
                    </div>
                `;
            } else {
                container.innerHTML = `
                    <div class="p-4 rounded-2xl bg-rose-950/60 border border-rose-800 text-xs text-rose-200">
                        Gagal memuat analisis AI: ${payload.message || 'Terjadi kesalahan sistem.'}
                    </div>
                `;
            }
        } catch (err) {
            console.error(err);
            container.innerHTML = `
                <div class="p-4 rounded-2xl bg-rose-950/60 border border-rose-800 text-xs text-rose-200">
                    Koneksi ke endpoint AI Reasoning terputus. Silakan coba lagi.
                </div>
            `;
        } finally {
            btn.disabled = false;
            btn.classList.remove('opacity-70', 'cursor-not-allowed');
            icon.innerText = '⚡';
            text.innerText = 'Analisis Ulang dengan AI';
        }
    }
</script>
@endpush
@endsection
