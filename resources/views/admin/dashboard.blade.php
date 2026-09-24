@extends('layouts.admin')

@section('title', 'Live Dashboard E-Voting')

@section('content')
<div class="space-y-8">

    <!-- Top Dashboard Header with Live SSE Status & Language Switcher -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Live Monitoring Hasil Pemilihan</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Streaming realtime perolehan suara Ketua & Pengawas Koperasi melalui Server-Sent Events (SSE).
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <!-- Language Switcher -->
            <div class="inline-flex rounded-lg border border-slate-200 bg-white p-0.5 text-xs font-bold shadow-2xs">
                <a href="{{ route('lang.switch', 'en') }}" class="px-2.5 py-1 rounded-md transition {{ app()->getLocale() === 'en' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-blue-600' }}">EN</a>
                <a href="{{ route('lang.switch', 'id') }}" class="px-2.5 py-1 rounded-md transition {{ app()->getLocale() === 'id' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-blue-600' }}">ID</a>
            </div>

            <span id="sse-indicator" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                <span class="w-2 h-2 mr-2 rounded-full bg-emerald-600 animate-ping"></span>
                <span id="sse-status-text">SSE Live Connected</span>
            </span>

            <button onclick="fetchLatestData()" class="p-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 shadow-2xs transition" title="Refresh Data Manual">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </button>
        </div>
    </div>

    <!-- 4 High-Level Metric Cards (Clean Light Theme) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Metric 1: Total DPT -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-extrabold tracking-wider text-slate-500">Total DPT</span>
                <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <div class="mt-3">
                <span id="metric-total-voters" class="text-3xl font-black text-slate-900 font-mono">{{ $totalVoters }}</span>
                <span class="text-xs text-slate-500 block mt-1">Pemilih Terdaftar</span>
            </div>
        </div>

        <!-- Metric 2: Total Suara Masuk -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-extrabold tracking-wider text-emerald-700">Suara Masuk</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-3">
                <span id="metric-total-voted" class="text-3xl font-black text-emerald-600 font-mono">{{ $totalVoted }}</span>
                <span class="text-xs text-slate-500 block mt-1">Suara Sah Tercatat</span>
            </div>
        </div>

        <!-- Metric 3: Persentase Partisipasi -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-extrabold tracking-wider text-indigo-700">Partisipasi (Turnout)</span>
                <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
            <div class="mt-3">
                <span id="metric-turnout-pct" class="text-3xl font-black text-indigo-600 font-mono">{{ $turnoutPct }}%</span>
                <div class="w-full bg-slate-100 h-2 rounded-full mt-2 overflow-hidden">
                    <div id="metric-turnout-bar" class="h-full bg-indigo-600 rounded-full transition-all duration-500" style="width: {{ $turnoutPct }}%;"></div>
                </div>
            </div>
        </div>

        <!-- Metric 4: Sisa Belum Memilih -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-extrabold tracking-wider text-amber-700">Sisa Pemilih</span>
                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-3">
                <span id="metric-remaining" class="text-3xl font-black text-amber-600 font-mono">{{ $remaining }}</span>
                <span class="text-xs text-slate-500 block mt-1">Belum Menggunakan Hak</span>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- AI ELECTION CONCLUSION & ANALYTICS WIDGET                -->
    <!-- ======================================================== -->
    <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 mb-5 border-b border-slate-200 gap-3">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shadow-2xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <h3 class="text-lg font-extrabold text-slate-900">AI Election Conclusion & Insight Analytics</h3>
                        <span id="ai-confidence-badge" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                            {{ $aiConclusion['confidence_score'] }}% Confidence
                        </span>
                    </div>
                    <p class="text-xs text-slate-500">Analisis prediktif otomatis berbasis statistical margin & dinamika turnout</p>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <span id="ai-quorum-badge" class="px-3 py-1 rounded-full text-xs font-bold {{ str_contains($aiConclusion['quorum_status'], 'Terpenuhi') ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-amber-100 text-amber-800 border border-amber-300' }}">
                    {{ $aiConclusion['quorum_status'] }}
                </span>
                <button type="button" onclick="refreshAiConclusion()" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center space-x-1">
                    <span>↻ Refresh AI</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Executive Summary Text -->
            <div class="lg:col-span-8 space-y-3">
                <p id="ai-summary-text" class="text-sm text-slate-700 leading-relaxed font-medium bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    {{ $aiConclusion['summary'] }}
                </p>

                <!-- Bullet Insights -->
                <div class="space-y-2 pt-1" id="ai-insights-container">
                    @foreach($aiConclusion['insights'] as $insight)
                        <div class="flex items-start space-x-2 text-xs text-slate-600">
                            <span class="text-indigo-600 font-bold shrink-0">✦</span>
                            <span>{{ $insight }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Proyeksi Pemenang Sementara -->
            <div class="lg:col-span-4 p-4 rounded-2xl bg-indigo-50/60 border border-indigo-100 flex flex-col justify-between">
                <div>
                    <span class="text-[11px] font-black uppercase tracking-wider text-indigo-900 block mb-2">Proyeksi Pemenang AI</span>
                    
                    <div class="space-y-3">
                        <div class="bg-white p-3 rounded-xl border border-indigo-100">
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Kandidat Ketua Terunggul</span>
                            <strong id="ai-leader-ketua" class="text-sm font-extrabold text-blue-700 block truncate">{{ $aiConclusion['leader_ketua'] }}</strong>
                            <span id="ai-margin-ketua" class="text-[11px] text-slate-500">Margin: +{{ $aiConclusion['margin_ketua'] }} Suara</span>
                        </div>

                        <div class="bg-white p-3 rounded-xl border border-indigo-100">
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Kandidat Pengawas Terunggul</span>
                            <strong id="ai-leader-pengawas" class="text-sm font-extrabold text-emerald-700 block truncate">{{ $aiConclusion['leader_pengawas'] }}</strong>
                            <span id="ai-margin-pengawas" class="text-[11px] text-slate-500">Margin: +{{ $aiConclusion['margin_pengawas'] }} Suara</span>
                        </div>
                    </div>
                </div>

                <div class="mt-3 pt-2 border-t border-indigo-100/60 flex items-center justify-between text-[11px] text-slate-400">
                    <span>Generated AI Engine</span>
                    <span id="ai-timestamp">{{ $aiConclusion['generated_at'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Charts Grid: Ketua & Pengawas (Clean Light Theme) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Chart Section: Calon Ketua Koperasi -->
        <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-200">
                    <div class="flex items-center space-x-3">
                        <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 font-extrabold text-sm flex items-center justify-center">1</span>
                        <h3 class="text-base font-bold text-slate-900">Perolehan Suara: Calon Ketua</h3>
                    </div>
                    <span class="text-xs font-semibold text-slate-500">Live Realtime</span>
                </div>

                <!-- Doughnut Chart Container -->
                <div class="relative w-48 h-48 sm:w-56 sm:h-56 mx-auto mb-6">
                    <canvas id="adminChartKetua"></canvas>
                </div>

                <!-- Progress List -->
                <div class="space-y-3" id="admin-ketua-list">
                    @foreach($ketuaResults as $k)
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-4">
                            <div class="flex items-center space-x-3 min-w-0">
                                <span class="w-8 h-8 rounded-lg bg-blue-600 text-white font-black text-xs flex items-center justify-center shrink-0">
                                    {{ $k['nomor_urut'] }}
                                </span>
                                <div class="min-w-0">
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate">{{ $k['nama'] }}</h4>
                                    <span class="text-[11px] text-slate-500 font-medium">Calon No. {{ $k['nomor_urut'] }}</span>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-sm font-extrabold text-blue-700 font-mono">{{ $k['suara'] }} Suara</span>
                                <span class="text-xs text-slate-500 block font-bold">({{ $k['persen'] }}%)</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Chart Section: Calon Pengawas Koperasi -->
        <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-200">
                    <div class="flex items-center space-x-3">
                        <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 font-extrabold text-sm flex items-center justify-center">2</span>
                        <h3 class="text-base font-bold text-slate-900">Perolehan Suara: Calon Pengawas</h3>
                    </div>
                    <span class="text-xs font-semibold text-slate-500">Live Realtime</span>
                </div>

                <!-- Doughnut Chart Container -->
                <div class="relative w-48 h-48 sm:w-56 sm:h-56 mx-auto mb-6">
                    <canvas id="adminChartPengawas"></canvas>
                </div>

                <!-- Progress List -->
                <div class="space-y-3" id="admin-pengawas-list">
                    @foreach($pengawasResults as $p)
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-4">
                            <div class="flex items-center space-x-3 min-w-0">
                                <span class="w-8 h-8 rounded-lg bg-emerald-600 text-white font-black text-xs flex items-center justify-center shrink-0">
                                    {{ $p['nomor_urut'] }}
                                </span>
                                <div class="min-w-0">
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate">{{ $p['nama'] }}</h4>
                                    <span class="text-[11px] text-slate-500 font-medium">Calon No. {{ $p['nomor_urut'] }}</span>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-sm font-extrabold text-emerald-700 font-mono">{{ $p['suara'] }} Suara</span>
                                <span class="text-xs text-slate-500 block font-bold">({{ $p['persen'] }}%)</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <!-- Live Recent Activity Logs & Votes (Clean Light Theme) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Suara Baru Masuk -->
        <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-2xs">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-200">
                <h3 class="text-sm font-bold text-slate-900">Suara Terakhir Masuk</h3>
                <a href="{{ route('admin.voters.index') }}" class="text-xs font-bold text-blue-700 hover:underline">Lihat Semua DPT →</a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($recentVotes as $rv)
                    <div class="py-3 flex items-center justify-between text-xs">
                        <div>
                            <span class="font-bold text-slate-900 block">{{ $rv['nama'] }}</span>
                            <span class="text-[11px] text-slate-500 font-mono">NIK: {{ $rv['nik'] }} • Dept: {{ $rv['dept'] }}</span>
                        </div>
                        <span class="font-mono text-slate-500 font-bold">{{ $rv['waktu'] }}</span>
                    </div>
                @empty
                    <div class="py-6 text-center text-xs text-slate-400">Belum ada suara masuk.</div>
                @endforelse
            </div>
        </div>

        <!-- Audit Trail Logs -->
        <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-2xs">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-200">
                <h3 class="text-sm font-bold text-slate-900">Audit Trail Keamanan</h3>
                <a href="{{ route('admin.logs.index') }}" class="text-xs font-bold text-blue-700 hover:underline">Log Lengkap →</a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($recentLogs as $log)
                    <div class="py-3 flex items-center justify-between text-xs">
                        <div class="min-w-0 pr-3">
                            <span class="font-bold text-slate-800 block truncate">{{ $log->keterangan }}</span>
                            <span class="text-[11px] text-slate-400 font-mono">{{ $log->created_at->format('H:i:s d/m/Y') }} • IP: {{ $log->ip_address }}</span>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold shrink-0 {{ str_contains($log->aksi, 'FAIL') || str_contains($log->aksi, 'REJECT') ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-700' }}">
                            {{ $log->aksi }}
                        </span>
                    </div>
                @empty
                    <div class="py-6 text-center text-xs text-slate-400">Belum ada aktivitas audit.</div>
                @endforelse
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    let adminChartKetua = null;
    let adminChartPengawas = null;
    const initialKetua = @json($ketuaResults);
    const initialPengawas = @json($pengawasResults);
    const palette = ['#2563eb', '#059669', '#d97706', '#7c3aed', '#db2777', '#0891b2'];

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Chart === 'undefined') return;

        // Chart Ketua
        const ctxK = document.getElementById('adminChartKetua').getContext('2d');
        adminChartKetua = new Chart(ctxK, {
            type: 'doughnut',
            data: {
                labels: initialKetua.map(k => k.nama),
                datasets: [{
                    data: initialKetua.map(k => k.suara),
                    backgroundColor: palette.slice(0, initialKetua.length),
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '65%'
            }
        });

        // Chart Pengawas
        const ctxP = document.getElementById('adminChartPengawas').getContext('2d');
        adminChartPengawas = new Chart(ctxP, {
            type: 'doughnut',
            data: {
                labels: initialPengawas.map(p => p.nama),
                datasets: [{
                    data: initialPengawas.map(p => p.suara),
                    backgroundColor: palette.slice(0, initialPengawas.length),
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '65%'
            }
        });

        initAdminSSE();
    });

    function initAdminSSE() {
        if (typeof EventSource !== 'undefined') {
            const es = new EventSource("{{ route('admin.stream.results') }}");
            es.onmessage = function(e) {
                try {
                    const data = JSON.parse(e.data);
                    updateAdminDashboard(data);
                } catch(err) {
                    console.error(err);
                }
            };
        }
    }

    function fetchLatestData() {
        if (window.SoundEffects) window.SoundEffects.click();
        fetch("{{ route('admin.api.live-results') }}")
            .then(res => res.json())
            .then(data => updateAdminDashboard(data));
    }

    function refreshAiConclusion() {
        if (window.SoundEffects) window.SoundEffects.click();
        fetch("{{ route('admin.api.ai-conclusion') }}")
            .then(res => res.json())
            .then(ai => updateAiWidget(ai));
    }

    function updateAdminDashboard(data) {
        if (!data || !data.metrics) return;

        document.getElementById('metric-total-voters').innerText = data.metrics.total_voters;
        document.getElementById('metric-total-voted').innerText = data.metrics.total_voted;
        document.getElementById('metric-turnout-pct').innerText = data.metrics.turnout_percentage + '%';
        document.getElementById('metric-turnout-bar').style.width = data.metrics.turnout_percentage + '%';
        document.getElementById('metric-remaining').innerText = data.metrics.remaining_voters;

        if (adminChartKetua && data.ketua_results) {
            adminChartKetua.data.datasets[0].data = data.ketua_results.map(k => k.suara);
            adminChartKetua.update('none');
        }

        if (adminChartPengawas && data.pengawas_results) {
            adminChartPengawas.data.datasets[0].data = data.pengawas_results.map(p => p.suara);
            adminChartPengawas.update('none');
        }

        if (data.ai_conclusion) {
            updateAiWidget(data.ai_conclusion);
        }
    }

    function updateAiWidget(ai) {
        if (!ai) return;
        document.getElementById('ai-confidence-badge').innerText = ai.confidence_score + '% Confidence';
        document.getElementById('ai-summary-text').innerText = ai.summary;
        document.getElementById('ai-quorum-badge').innerText = ai.quorum_status;
        document.getElementById('ai-leader-ketua').innerText = ai.leader_ketua;
        document.getElementById('ai-leader-pengawas').innerText = ai.leader_pengawas;
        document.getElementById('ai-margin-ketua').innerText = 'Margin: +' + ai.margin_ketua + ' Suara';
        document.getElementById('ai-margin-pengawas').innerText = 'Margin: +' + ai.margin_pengawas + ' Suara';
        document.getElementById('ai-timestamp').innerText = ai.generated_at;

        if (ai.insights && ai.insights.length) {
            let insightsHtml = '';
            ai.insights.forEach(ins => {
                insightsHtml += `
                    <div class="flex items-start space-x-2 text-xs text-slate-600">
                        <span class="text-indigo-600 font-bold shrink-0">✦</span>
                        <span>${ins}</span>
                    </div>
                `;
            });
            document.getElementById('ai-insights-container').innerHTML = insightsHtml;
        }
    }
</script>
@endpush
@endsection
