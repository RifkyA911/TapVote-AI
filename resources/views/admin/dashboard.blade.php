@extends('layouts.admin')

@section('title', 'Live Dashboard E-Voting')

@section('content')
<div class="space-y-8">

    <!-- Top Dashboard Header with Live SSE Status -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl sm:text-3xl font-black text-white">Live Monitoring Hasil Pemilihan</h2>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">
                Streaming realtime perolehan suara Ketua & Pengawas Koperasi melalui Server-Sent Events (SSE).
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <span id="sse-indicator" class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                <span class="w-2.5 h-2.5 mr-2 rounded-full bg-emerald-400 animate-ping"></span>
                <span id="sse-status-text">SSE Live Connected</span>
            </span>

            <button onclick="fetchLatestData()" class="p-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 transition" title="Refresh Data Manual">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </button>
        </div>
    </div>

    <!-- 4 High-Level Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Metric 1: Total DPT -->
        <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl shadow-lg relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-extrabold tracking-wider text-slate-400">Total DPT</span>
                <div class="w-8 h-8 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <div class="mt-3">
                <span id="metric-total-voters" class="text-3xl font-black text-white font-mono">{{ $totalVoters }}</span>
                <span class="text-xs text-slate-400 block mt-1">Pemilih Terdaftar</span>
            </div>
        </div>

        <!-- Metric 2: Total Suara Masuk -->
        <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl shadow-lg relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-extrabold tracking-wider text-emerald-400">Suara Masuk</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-3">
                <span id="metric-total-voted" class="text-3xl font-black text-emerald-400 font-mono">{{ $totalVoted }}</span>
                <span class="text-xs text-slate-400 block mt-1">Suara Sah Tercatat</span>
            </div>
        </div>

        <!-- Metric 3: Persentase Partisipasi -->
        <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl shadow-lg relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-extrabold tracking-wider text-indigo-400">Partisipasi (Turnout)</span>
                <div class="w-8 h-8 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
            <div class="mt-3">
                <span id="metric-turnout-pct" class="text-3xl font-black text-indigo-400 font-mono">{{ $turnoutPct }}%</span>
                <div class="w-full bg-slate-800 h-1.5 rounded-full mt-2 overflow-hidden">
                    <div id="metric-turnout-bar" class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full" style="width: {{ $turnoutPct }}%;"></div>
                </div>
            </div>
        </div>

        <!-- Metric 4: Sisa Belum Memilih -->
        <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl shadow-lg relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-extrabold tracking-wider text-amber-400">Sisa Pemilih</span>
                <div class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-3">
                <span id="metric-remaining" class="text-3xl font-black text-amber-400 font-mono">{{ $remaining }}</span>
                <span class="text-xs text-slate-400 block mt-1">Belum Menggunakan Suara</span>
            </div>
        </div>
    </div>

    <!-- Live Charts Grid: Ketua & Pengawas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Chart Section: Calon Ketua Koperasi -->
        <div class="p-6 rounded-3xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl shadow-xl flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-800">
                    <div class="flex items-center space-x-3">
                        <span class="w-7 h-7 rounded-lg bg-blue-600/20 text-blue-400 font-bold text-xs flex items-center justify-center border border-blue-500/30">1</span>
                        <h3 class="text-lg font-bold text-white">Hasil Suara: Kandidat Ketua</h3>
                    </div>
                    <span class="text-xs font-semibold text-slate-400">Live Breakdown</span>
                </div>

                <!-- Canvas Chart.js -->
                <div class="h-64 relative mb-6">
                    <canvas id="ketuaChart"></canvas>
                </div>
            </div>

            <!-- List Breakdown Calon Ketua -->
            <div id="ketua-breakdown-container" class="space-y-3 pt-4 border-t border-slate-800/80">
                @foreach($ketuaResults as $k)
                    <div class="p-3.5 rounded-2xl bg-slate-950/60 border border-slate-800/80 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="w-8 h-8 rounded-xl bg-blue-600/20 text-blue-400 font-mono font-bold text-xs flex items-center justify-center">
                                No. {{ $k['nomor_urut'] }}
                            </span>
                            <div>
                                <h4 class="text-xs font-bold text-white">{{ $k['nama'] }}</h4>
                                <span class="text-[11px] text-slate-400 font-mono">NIK: {{ $k['nik'] }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-blue-400 font-mono">{{ $k['suara'] }} Suara</span>
                            <span class="text-[11px] text-slate-400 block font-mono">({{ $k['persen'] }}%)</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


        <!-- Chart Section: Calon Pengawas Koperasi -->
        <div class="p-6 rounded-3xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl shadow-xl flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-800">
                    <div class="flex items-center space-x-3">
                        <span class="w-7 h-7 rounded-lg bg-emerald-600/20 text-emerald-400 font-bold text-xs flex items-center justify-center border border-emerald-500/30">2</span>
                        <h3 class="text-lg font-bold text-white">Hasil Suara: Kandidat Pengawas</h3>
                    </div>
                    <span class="text-xs font-semibold text-slate-400">Live Breakdown</span>
                </div>

                <!-- Canvas Chart.js -->
                <div class="h-64 relative mb-6">
                    <canvas id="pengawasChart"></canvas>
                </div>
            </div>

            <!-- List Breakdown Calon Pengawas -->
            <div id="pengawas-breakdown-container" class="space-y-3 pt-4 border-t border-slate-800/80">
                @foreach($pengawasResults as $p)
                    <div class="p-3.5 rounded-2xl bg-slate-950/60 border border-slate-800/80 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="w-8 h-8 rounded-xl bg-emerald-600/20 text-emerald-400 font-mono font-bold text-xs flex items-center justify-center">
                                No. {{ $p['nomor_urut'] }}
                            </span>
                            <div>
                                <h4 class="text-xs font-bold text-white">{{ $p['nama'] }}</h4>
                                <span class="text-[11px] text-slate-400 font-mono">NIK: {{ $p['nik'] }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-emerald-400 font-mono">{{ $p['suara'] }} Suara</span>
                            <span class="text-[11px] text-slate-400 block font-mono">({{ $p['persen'] }}%)</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Bottom Row: Recent Votes Stream & Activity Logs -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Live Vote Ticker -->
        <div class="p-6 rounded-3xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl shadow-xl">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-800">
                <h3 class="text-sm font-bold text-white flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span>Suara Masuk Terbaru</span>
                </h3>
                <span class="text-[11px] text-slate-500 font-mono">Realtime Feed</span>
            </div>

            <div id="recent-votes-list" class="space-y-3">
                @forelse($recentVotes as $v)
                    <div class="p-3 rounded-xl bg-slate-950/60 border border-slate-800 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-white block">{{ $v['nama'] }}</span>
                            <span class="text-[11px] text-slate-400">{{ $v['dept'] }} • NIK {{ $v['nik'] }}</span>
                        </div>
                        <span class="text-[11px] text-emerald-400 font-mono font-semibold">{{ $v['waktu'] }}</span>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 text-center py-6">Belum ada suara masuk.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Audit Log Trail -->
        <div class="lg:col-span-2 p-6 rounded-3xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl shadow-xl">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-800">
                <h3 class="text-sm font-bold text-white">Log Aktivitas & Audit Database Terkini</h3>
                <a href="{{ route('admin.logs.index') }}" class="text-xs text-blue-400 hover:text-blue-300 font-medium">Lihat Semua Log →</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-800 text-slate-400">
                            <th class="py-2.5 px-3">Waktu</th>
                            <th class="py-2.5 px-3">Aksi</th>
                            <th class="py-2.5 px-3">Modul</th>
                            <th class="py-2.5 px-3">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300">
                        @foreach($recentLogs as $log)
                            <tr>
                                <td class="py-2.5 px-3 font-mono text-[11px] text-slate-400">{{ $log->created_at->format('H:i:s') }}</td>
                                <td class="py-2.5 px-3">
                                    <span class="px-2 py-0.5 rounded font-mono text-[10px] font-bold bg-slate-800 text-slate-200">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 font-semibold text-slate-400">{{ $log->module }}</td>
                                <td class="py-2.5 px-3 truncate max-w-xs" title="{{ $log->description }}">{{ $log->description }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let ketuaChartInstance = null;
    let pengawasChartInstance = null;

    // Inisialisasi Chart.js
    document.addEventListener('DOMContentLoaded', function() {
        const ctxKetua = document.getElementById('ketuaChart').getContext('2d');
        const ctxPengawas = document.getElementById('pengawasChart').getContext('2d');

        // Initial Data dari Blade
        const initialKetua = @json($ketuaResults);
        const initialPengawas = @json($pengawasResults);

        ketuaChartInstance = new Chart(ctxKetua, {
            type: 'bar',
            data: {
                labels: initialKetua.map(k => 'No. ' + k.nomor_urut + ' ' + k.nama.split(' ')[0]),
                datasets: [{
                    label: 'Perolehan Suara',
                    data: initialKetua.map(k => k.suara),
                    backgroundColor: ['rgba(59, 130, 246, 0.7)', 'rgba(99, 102, 241, 0.7)', 'rgba(14, 165, 233, 0.7)'],
                    borderColor: ['#3b82f6', '#6366f1', '#0ea5e9'],
                    borderWidth: 1.5,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94a3b8', stepSize: 1 }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8' }
                    }
                }
            }
        });

        pengawasChartInstance = new Chart(ctxPengawas, {
            type: 'bar',
            data: {
                labels: initialPengawas.map(p => 'No. ' + p.nomor_urut + ' ' + p.nama.split(' ')[0]),
                datasets: [{
                    label: 'Perolehan Suara',
                    data: initialPengawas.map(p => p.suara),
                    backgroundColor: ['rgba(16, 185, 129, 0.7)', 'rgba(20, 184, 166, 0.7)', 'rgba(5, 150, 105, 0.7)'],
                    borderColor: ['#10b981', '#14b8a6', '#059669'],
                    borderWidth: 1.5,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94a3b8', stepSize: 1 }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8' }
                    }
                }
            }
        });

        // Hubungkan ke Realtime Server-Sent Events (SSE)
        initSSE();
    });

    // Setup Server-Sent Events (SSE)
    function initSSE() {
        const streamUrl = "{{ route('admin.stream.results') }}";
        const indicator = document.getElementById('sse-indicator');
        const statusText = document.getElementById('sse-status-text');

        if (typeof EventSource !== 'undefined') {
            const eventSource = new EventSource(streamUrl);

            eventSource.onopen = function() {
                if (statusText) statusText.innerText = 'SSE Live Connected';
                if (indicator) {
                    indicator.className = 'inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30';
                }
            };

            eventSource.onmessage = function(event) {
                try {
                    const data = JSON.parse(event.data);
                    updateDashboardUI(data);
                } catch (e) {
                    console.error('Error parsing SSE payload:', e);
                }
            };

            eventSource.onerror = function() {
                if (statusText) statusText.innerText = 'SSE Reconnecting... (Fallback Active)';
                if (indicator) {
                    indicator.className = 'inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/30';
                }
                // Fallback polling jika koneksi terputus
                fetchLatestData();
            };
        } else {
            console.warn('Browser does not support SSE, using interval fallback');
            setInterval(fetchLatestData, 3000);
        }

        // Periodic Polling Fallback (setiap 4 detik) untuk memastikan 100% konsistensi
        setInterval(fetchLatestData, 4000);
    }

    // Update Dashboard DOM dengan data terbaru
    function updateDashboardUI(data) {
        if (!data || !data.metrics) return;

        // Update Top Metrics
        document.getElementById('metric-total-voters').innerText = data.metrics.total_voters;
        document.getElementById('metric-total-voted').innerText = data.metrics.total_voted;
        document.getElementById('metric-remaining').innerText = data.metrics.remaining_voters;
        document.getElementById('metric-turnout-pct').innerText = data.metrics.turnout_percentage + '%';
        document.getElementById('metric-turnout-bar').style.width = data.metrics.turnout_percentage + '%';

        // Update Charts
        if (ketuaChartInstance && data.ketua_results) {
            ketuaChartInstance.data.labels = data.ketua_results.map(k => 'No. ' + k.nomor_urut + ' ' + k.nama.split(' ')[0]);
            ketuaChartInstance.data.datasets[0].data = data.ketua_results.map(k => k.suara);
            ketuaChartInstance.update();
        }

        if (pengawasChartInstance && data.pengawas_results) {
            pengawasChartInstance.data.labels = data.pengawas_results.map(p => 'No. ' + p.nomor_urut + ' ' + p.nama.split(' ')[0]);
            pengawasChartInstance.data.datasets[0].data = data.pengawas_results.map(p => p.suara);
            pengawasChartInstance.update();
        }

        // Update Breakdown Lists
        if (data.ketua_results) {
            const ketuaListEl = document.getElementById('ketua-breakdown-container');
            ketuaListEl.innerHTML = data.ketua_results.map(k => `
                <div class="p-3.5 rounded-2xl bg-slate-950/60 border border-slate-800/80 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="w-8 h-8 rounded-xl bg-blue-600/20 text-blue-400 font-mono font-bold text-xs flex items-center justify-center">
                            No. ${k.nomor_urut}
                        </span>
                        <div>
                            <h4 class="text-xs font-bold text-white">${k.nama}</h4>
                            <span class="text-[11px] text-slate-400 font-mono">NIK: ${k.nik}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-bold text-blue-400 font-mono">${k.suara} Suara</span>
                        <span class="text-[11px] text-slate-400 block font-mono">(${k.persen}%)</span>
                    </div>
                </div>
            `).join('');
        }

        if (data.pengawas_results) {
            const pengawasListEl = document.getElementById('pengawas-breakdown-container');
            pengawasListEl.innerHTML = data.pengawas_results.map(p => `
                <div class="p-3.5 rounded-2xl bg-slate-950/60 border border-slate-800/80 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="w-8 h-8 rounded-xl bg-emerald-600/20 text-emerald-400 font-mono font-bold text-xs flex items-center justify-center">
                            No. ${p.nomor_urut}
                        </span>
                        <div>
                            <h4 class="text-xs font-bold text-white">${p.nama}</h4>
                            <span class="text-[11px] text-slate-400 font-mono">NIK: ${p.nik}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-bold text-emerald-400 font-mono">${p.suara} Suara</span>
                        <span class="text-[11px] text-slate-400 block font-mono">(${p.persen}%)</span>
                    </div>
                </div>
            `).join('');
        }

        // Update Recent Votes Feed
        if (data.recent_votes && data.recent_votes.length > 0) {
            const feedEl = document.getElementById('recent-votes-list');
            feedEl.innerHTML = data.recent_votes.map(v => `
                <div class="p-3 rounded-xl bg-slate-950/60 border border-slate-800 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-white block">${v.nama}</span>
                        <span class="text-[11px] text-slate-400">${v.dept} • NIK ${v.nik}</span>
                    </div>
                    <span class="text-[11px] text-emerald-400 font-mono font-semibold">${v.waktu}</span>
                </div>
            `).join('');
        }
    }

    // Manual Fetch
    function fetchLatestData() {
        fetch("{{ route('admin.api.live-results') }}")
            .then(res => res.json())
            .then(data => updateDashboardUI(data))
            .catch(err => console.error('Fetch live-results error:', err));
    }
</script>
@endpush
@endsection
