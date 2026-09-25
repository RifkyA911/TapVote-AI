@extends('layouts.admin')

@section('title', 'Live Telemetry Dashboard')

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- Top Executive Header: Live SSE Status & Telemetry Indicators -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-5 sm:p-6 rounded-3xl bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 text-white shadow-xl border border-slate-700/50">
        <div class="space-y-1">
            <div class="flex flex-wrap items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-indigo-500/30 text-indigo-300 border border-indigo-400/30">
                    Live Telemetry Center
                </span>
                <span id="sse-indicator" class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[11px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">
                    <span class="w-2 h-2 mr-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                    <span id="sse-status-text">SSE Live Stream Active</span>
                </span>
            </div>
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-black tracking-tight text-white">
                Live E-Voting Intelligence & Monitoring
            </h2>
            <p class="text-xs sm:text-sm text-slate-300 max-w-2xl">
                Real-time voting telemetry for Chairman & Supervisory Board. Validated through continuous cryptographic hash logs.
            </p>
        </div>

        <div class="flex items-center gap-2.5 self-start md:self-auto shrink-0">
            <!-- Manual Refresh -->
            <button onclick="fetchLatestData()" class="p-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/20 shadow-sm transition cursor-pointer flex items-center space-x-1.5 text-xs font-bold" title="Refresh Metrik Manual">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                <span>Segarkan Data</span>
            </button>
        </div>
    </div>

    <!-- 4 High-Tech KPI Metric Cards (Responsive Mobile & iPad Mini) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <!-- Metric 1: Total DPT -->
        <div class="relative overflow-hidden p-5 sm:p-6 rounded-3xl bg-white border border-slate-200 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-extrabold tracking-wider text-slate-500">Total DPT Anggota</span>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span id="metric-total-voters" class="text-3xl sm:text-4xl font-black text-slate-900 font-mono tracking-tight">{{ $totalVoters }}</span>
                <span class="text-[11px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md">100% Terdaftar</span>
            </div>
            <p class="text-xs text-slate-500 mt-1.5 font-medium">Buku daftar pemilih tetap terverifikasi</p>
        </div>

        <!-- Metric 2: Suara Masuk (Turnout) -->
        <div class="relative overflow-hidden p-5 sm:p-6 rounded-3xl bg-white border border-slate-200 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-extrabold tracking-wider text-emerald-700">Total Suara Masuk</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span id="metric-total-voted" class="text-3xl sm:text-4xl font-black text-emerald-600 font-mono tracking-tight">{{ $totalVoted }}</span>
                <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">Tervalidasi RFID</span>
            </div>
            <p class="text-xs text-slate-500 mt-1.5 font-medium">Surat suara sah masuk bilik</p>
        </div>

        <!-- Metric 3: Partisipasi & Quorum Meter -->
        <div class="relative overflow-hidden p-5 sm:p-6 rounded-3xl bg-white border border-slate-200 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-extrabold tracking-wider text-indigo-700">Tingkat Partisipasi</span>
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span id="metric-turnout-pct" class="text-3xl sm:text-4xl font-black text-indigo-600 font-mono tracking-tight">{{ $turnoutPct }}%</span>
                <span id="quorum-chip" class="text-[11px] font-bold px-2 py-0.5 rounded-md {{ $turnoutPct >= 50.0 ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                    {{ $turnoutPct >= 50.0 ? '✓ Quorum Sah' : 'Menuju Quorum' }}
                </span>
            </div>
            <div class="w-full bg-slate-100 h-2.5 rounded-full mt-3 overflow-hidden relative">
                <div id="metric-turnout-bar" class="h-full bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full transition-all duration-700" style="width: {{ $turnoutPct }}%;"></div>
            </div>
        </div>

        <!-- Metric 4: Sisa Belum Memilih -->
        <div class="relative overflow-hidden p-5 sm:p-6 rounded-3xl bg-white border border-slate-200 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-extrabold tracking-wider text-amber-700">Sisa Belum Memilih</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span id="metric-remaining" class="text-3xl sm:text-4xl font-black text-amber-600 font-mono tracking-tight">{{ $remaining }}</span>
                <span class="text-[11px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md">Pending</span>
            </div>
            <p class="text-xs text-slate-500 mt-1.5 font-medium">Anggota di pool absensi</p>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- AI ELECTION CONCLUSION & INTELLIGENCE CENTER             -->
    <!-- (Real Google Gemini API with Live Latency Test Tool)     -->
    <!-- ======================================================== -->
    <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white border border-indigo-900/60 shadow-xl relative overflow-hidden">
        <!-- Ambient decorative glow -->
        <div class="absolute -right-24 -top-24 w-80 h-80 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-24 -bottom-24 w-80 h-80 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-6">
            <!-- Header bar of AI Card -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-5 border-b border-indigo-800/40 gap-4">
                <div class="flex items-center space-x-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-500 to-blue-500 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="text-lg sm:text-xl font-black text-white tracking-tight">Gemini AI Election Intelligence</h3>
                            <span id="ai-model-pill" class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-400/30">
                                Google Gemini 2.0 Flash
                            </span>
                        </div>
                        <p class="text-xs text-indigo-200/70 mt-0.5">Real-time election outcome projection & quorum margin analysis</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <button type="button" onclick="toggleGeminiDrawer()" class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-indigo-200 hover:text-white text-xs font-bold border border-white/15 transition cursor-pointer flex items-center space-x-1.5">
                        <span>🔑 Pengaturan & Test API Key</span>
                    </button>

                    <button 
                        id="btn-trigger-ai"
                        type="button" 
                        onclick="triggerAiAnalysis()" 
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white text-xs sm:text-sm font-extrabold shadow-lg shadow-indigo-500/25 transition cursor-pointer flex items-center space-x-2"
                    >
                        <span id="ai-btn-icon">⚡</span>
                        <span id="ai-btn-text">Analisis dengan Gemini AI</span>
                    </button>
                </div>
            </div>

            <!-- Gemini API Key Configuration Drawer with Test API Tool -->
            <div id="gemini-key-drawer" class="hidden p-5 rounded-2xl bg-indigo-950/90 border border-indigo-700/60 space-y-4">
                <form action="{{ route('admin.settings.gemini-key') }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                        <label for="gemini-input" class="text-xs font-black uppercase tracking-wider text-indigo-200 flex items-center space-x-1.5">
                            <span>Google AI Studio Gemini API Key</span>
                        </label>
                        <a href="https://aistudio.google.com/app/apikey" target="_blank" class="text-xs text-indigo-300 hover:text-white font-bold underline">
                            Dapatkan API Key Gratis di Google AI Studio ↗
                        </a>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <div class="flex-1 relative">
                            <input 
                                type="password" 
                                id="gemini-input" 
                                name="gemini_api_key" 
                                value="{{ $geminiKey }}" 
                                placeholder="AIzaSy... dari Google AI Studio"
                                class="w-full px-4 py-2.5 pr-10 rounded-xl border border-indigo-700 bg-slate-900 text-xs font-mono text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                            >
                            <button type="button" onclick="toggleGeminiVisibility()" class="absolute right-2 top-2 text-slate-400 hover:text-white p-1 text-sm">
                                <span id="gemini-eye-icon">👁</span>
                            </button>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <!-- Real Test API Key Button -->
                            <button 
                                type="button" 
                                onclick="testGeminiConnection()" 
                                id="btn-test-gemini"
                                class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-black transition cursor-pointer flex items-center space-x-1.5 shadow-sm"
                                title="Uji coba ping koneksi langsung ke Google Gemini API"
                            >
                                <span id="gemini-test-icon">🔌</span>
                                <span id="gemini-test-text">Test API Key</span>
                            </button>

                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition shadow cursor-pointer">
                                Simpan Key
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Feedback Test Result Box -->
                <div id="gemini-test-result" class="hidden p-3.5 rounded-xl text-xs font-medium border transition-all"></div>
            </div>

            <!-- STATE 1: INITIAL STATE (READY TO ANALYZE) -->
            <div id="ai-state-initial" class="text-center py-4 space-y-3">
                <p class="text-xs sm:text-sm text-indigo-200/90 max-w-xl mx-auto leading-relaxed">
                    Tekan tombol <strong class="text-white">"Analisis dengan Gemini AI"</strong> untuk mengaktifkan deep reasoning AI. Gemini akan mengevaluasi marjin suara, proyeksi putaran kedua, dan kepatuhan kuorum berdasarkan statistik langsung dari bilik suara.
                </p>
            </div>

            <!-- STATE 2: LOADING STATE -->
            <div id="ai-state-loading" class="hidden text-center py-8 space-y-4">
                <div class="w-12 h-12 mx-auto rounded-full border-3 border-indigo-400/30 border-t-indigo-400 animate-spin"></div>
                <div>
                    <h4 class="text-sm font-extrabold text-white">Menghubungi Google Gemini 2.0 Flash...</h4>
                    <p class="text-xs text-indigo-300/80 mt-1">Menganalisis probabilitas pemilu dan marjin suara kandidat...</p>
                </div>
            </div>

            <!-- STATE 3: RESULTS DISPLAY -->
            <div id="ai-state-result" class="hidden space-y-6">
                <!-- Badges Bar -->
                <div class="flex flex-wrap items-center justify-between gap-3 text-xs">
                    <div class="flex items-center space-x-2">
                        <span id="ai-confidence-badge" class="px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 border border-blue-400/30 font-mono font-bold">
                            98.2% Confidence
                        </span>
                        <span id="ai-quorum-badge" class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 font-bold">
                            Quorum Evaluated
                        </span>
                    </div>
                    <span id="ai-timestamp" class="text-[11px] text-indigo-300/70 font-mono">Diproses: Baru Saja</span>
                </div>

                <!-- 2 Columns Grid: Summary & Duel Projections -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Left: Narrative Summary & Insights -->
                    <div class="lg:col-span-8 space-y-4">
                        <div class="p-4 sm:p-5 rounded-2xl bg-white/5 border border-white/10 space-y-2">
                            <span class="text-[10px] font-black uppercase tracking-wider text-indigo-300">Ringkasan Eksekutif AI:</span>
                            <p id="ai-summary-text" class="text-xs sm:text-sm text-slate-100 font-medium leading-relaxed"></p>
                        </div>

                        <!-- Strategic Bullet Insights -->
                        <div>
                            <span class="text-[11px] uppercase tracking-wider font-extrabold text-indigo-300 block mb-2">Poin Analisis Strategis:</span>
                            <div id="ai-insights-container" class="space-y-2"></div>
                        </div>
                    </div>

                    <!-- Right: Winner Projections Duel Cards -->
                    <div class="lg:col-span-4 space-y-3">
                        <span class="text-[11px] uppercase tracking-wider font-extrabold text-indigo-300 block">Proyeksi Pemenang Sementara:</span>
                        
                        <div class="p-4 rounded-2xl bg-blue-500/15 border border-blue-400/30 space-y-1">
                            <span class="text-[10px] font-bold text-blue-300 uppercase block">Kandidat Ketua Terunggul</span>
                            <h5 id="ai-leader-ketua" class="text-base font-black text-white truncate">-</h5>
                            <span id="ai-margin-ketua" class="text-xs text-blue-200 block font-mono">Margin: -</span>
                        </div>

                        <div class="p-4 rounded-2xl bg-emerald-500/15 border border-emerald-400/30 space-y-1">
                            <span class="text-[10px] font-bold text-emerald-300 uppercase block">Kandidat Pengawas Terunggul</span>
                            <h5 id="ai-leader-pengawas" class="text-base font-black text-white truncate">-</h5>
                            <span id="ai-margin-pengawas" class="text-xs text-emerald-200 block font-mono">Margin: -</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ======================================================== -->
    <!-- REAL-TIME CANDIDATE CHARTS (POWERED BY APEXCHARTS!)      -->
    <!-- ======================================================== -->
    <!-- Row 1: Perolehan Suara Calon Ketua (Full Width Row, Lapang & Responsive) -->
    <div class="p-5 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-5">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center space-x-3">
                <span class="w-8 h-8 rounded-xl bg-blue-600 text-white font-black text-xs flex items-center justify-center shadow-xs">1</span>
                <div>
                    <h3 class="text-base sm:text-lg font-black text-slate-900">Perolehan Suara: Calon Ketua Koperasi</h3>
                    <p class="text-xs text-slate-500">Distribusi suara masuk secara komprehensif via ApexCharts Donut & Rekapitulasi</p>
                </div>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                {{ count($ketuaResults) }} Kandidat Terdaftar
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
            <!-- ApexCharts Donut Viewport (Left) -->
            <div class="md:col-span-5 flex items-center justify-center min-h-[270px]">
                <div id="apexChartKetua" class="w-full"></div>
            </div>

            <!-- Progress & Tally List (Right) -->
            <div class="md:col-span-7 space-y-3" id="admin-ketua-list">
                @foreach($ketuaResults as $k)
                    <div class="p-4 rounded-2xl bg-slate-50 hover:bg-blue-50/40 border border-slate-200/80 transition space-y-2">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center space-x-3 min-w-0">
                                <span class="w-8 h-8 rounded-xl bg-blue-600 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-2xs">
                                    {{ $k['nomor_urut'] }}
                                </span>
                                <div class="min-w-0">
                                    <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 truncate">{{ $k['nama'] }}</h4>
                                    <span class="text-[11px] text-slate-500 font-mono font-medium">Kandidat Ketua</span>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-sm sm:text-base font-black text-blue-700 font-mono">{{ $k['suara'] }} Suara</span>
                                <span class="text-xs text-slate-600 block font-bold">({{ $k['persen'] }}%)</span>
                            </div>
                        </div>
                        <div class="w-full bg-slate-200/70 h-2 rounded-full overflow-hidden">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-700" style="width: {{ $k['persen'] }}%;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Row 2: Perolehan Suara Calon Pengawas (Full Width Row, Lapang & Responsive) -->
    <div class="p-5 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-5">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center space-x-3">
                <span class="w-8 h-8 rounded-xl bg-emerald-600 text-white font-black text-xs flex items-center justify-center shadow-xs">2</span>
                <div>
                    <h3 class="text-base sm:text-lg font-black text-slate-900">Perolehan Suara: Calon Pengawas Koperasi</h3>
                    <p class="text-xs text-slate-500">Distribusi suara masuk secara komprehensif via ApexCharts Donut & Rekapitulasi</p>
                </div>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                {{ count($pengawasResults) }} Kandidat Terdaftar
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
            <!-- ApexCharts Donut Viewport (Left) -->
            <div class="md:col-span-5 flex items-center justify-center min-h-[270px]">
                <div id="apexChartPengawas" class="w-full"></div>
            </div>

            <!-- Progress & Tally List (Right) -->
            <div class="md:col-span-7 space-y-3" id="admin-pengawas-list">
                @foreach($pengawasResults as $p)
                    <div class="p-4 rounded-2xl bg-slate-50 hover:bg-emerald-50/40 border border-slate-200/80 transition space-y-2">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center space-x-3 min-w-0">
                                <span class="w-8 h-8 rounded-xl bg-emerald-600 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-2xs">
                                    {{ $p['nomor_urut'] }}
                                </span>
                                <div class="min-w-0">
                                    <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 truncate">{{ $p['nama'] }}</h4>
                                    <span class="text-[11px] text-slate-500 font-mono font-medium">Kandidat Pengawas</span>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-sm sm:text-base font-black text-emerald-700 font-mono">{{ $p['suara'] }} Suara</span>
                                <span class="text-xs text-slate-600 block font-bold">({{ $p['persen'] }}%)</span>
                            </div>
                        </div>
                        <div class="w-full bg-slate-200/70 h-2 rounded-full overflow-hidden">
                            <div class="bg-emerald-600 h-2 rounded-full transition-all duration-700" style="width: {{ $p['persen'] }}%;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 3D INTERACTIVE RFID SMART CARD & LANYARD (THREE.JS)      -->
    <!-- Replicated from UBS ID Card & Red Ribbon Lanyard Ref     -->
    <!-- ======================================================== -->
    <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 text-white border border-slate-800 shadow-2xl relative overflow-hidden">
        <!-- Ambient decorative lights -->
        <div class="absolute -top-32 -left-32 w-80 h-80 rounded-full bg-blue-600/15 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-80 h-80 rounded-full bg-indigo-600/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            
            <!-- Left Column: Specs & Interactive Controls -->
            <div class="lg:col-span-5 space-y-4 text-left">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 border border-blue-400/30 text-xs font-black uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                    <span>Three.js WebGL Interactive 3D Model</span>
                </div>

                <h3 class="text-xl sm:text-3xl font-black text-white tracking-tight leading-tight">
                    Kartu RFID Mifare & Keplek Lanyard 3D
                </h3>

                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                    Visualisasi kartu pintar dan keplek identitas resmi pemilih lengkap dengan pita lanyard merah dan penjepit. Putar kartu bebas <strong>360° pada sumbu X & Y</strong> secara interaktif.
                </p>

                <!-- Feature Specs Badges -->
                <div class="grid grid-cols-2 gap-2.5 pt-1 text-xs">
                    <div class="p-3 rounded-2xl bg-white/5 border border-white/10">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Standard</span>
                        <strong class="text-white font-mono">ISO/IEC 14443A</strong>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/5 border border-white/10">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Frequency</span>
                        <strong class="text-amber-400 font-mono">13.56 MHz HF</strong>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/5 border border-white/10">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Credential Form</span>
                        <strong class="text-blue-400 font-mono">Vertical Badge + Ribbon</strong>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/5 border border-white/10">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Encryption</span>
                        <strong class="text-emerald-400 font-mono">Crypto-1 / AES</strong>
                    </div>
                </div>

                <!-- Interactive 3D Action Buttons -->
                <div class="flex flex-wrap items-center gap-2 pt-2">
                    <button 
                        type="button" 
                        id="dash-btn-spin"
                        onclick="toggleDashCardSpin()"
                        class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-md transition cursor-pointer flex items-center space-x-1.5"
                    >
                        <span id="dash-spin-icon">⏸</span>
                        <span id="dash-spin-text">Jeda Putaran</span>
                    </button>

                    <button 
                        type="button" 
                        onclick="flipDashCard()"
                        class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/15 transition cursor-pointer flex items-center space-x-1.5"
                    >
                        <span>🔄 Balik Kartu</span>
                    </button>

                    <button 
                        type="button" 
                        onclick="rotateDashCard360X()"
                        class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/15 transition cursor-pointer flex items-center space-x-1.5"
                    >
                        <span>↕ Putar 360° X</span>
                    </button>

                    <button 
                        type="button" 
                        onclick="resetDashCardView()"
                        class="px-3 py-2 rounded-xl bg-white/5 hover:bg-white/15 text-slate-300 font-bold text-xs border border-white/10 transition cursor-pointer"
                        title="Reset Sudut"
                    >
                        ⟲ Reset
                    </button>
                </div>
            </div>

            <!-- Right Column: Interactive 3D Canvas Viewport -->
            <div class="lg:col-span-7 flex flex-col items-center justify-center">
                <div class="relative w-full max-w-lg aspect-[1.25/1] sm:aspect-[1.35/1] rounded-3xl bg-slate-900/70 border border-white/10 shadow-2xl backdrop-blur-md overflow-hidden flex items-center justify-center group cursor-grab active:cursor-grabbing">
                    <div id="dashboard-rfid-3d-canvas" class="w-full h-full"></div>

                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full bg-slate-950/80 border border-white/15 text-[11px] text-slate-300 font-medium pointer-events-none backdrop-blur-xs flex items-center space-x-1.5 shadow-md">
                        <span>👆</span>
                        <span>Drag mouse atau geser layar untuk memutar 360°</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ======================================================== -->
    <!-- VOTING ACTIVITY TIMELINE & OFFICIAL RECAP REPORT EXPORT  -->
    <!-- (Replaces Suara Terakhir Masuk & Audit Logs per request)  -->
    <!-- ======================================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8">
        
        <!-- Left: ApexCharts Voting Activity by Hour / Day -->
        <div class="lg:col-span-8 p-5 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-100 gap-2">
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="text-lg">📈</span>
                        <h3 class="text-base font-extrabold text-slate-900">Distribusi & Lonjakan Jam Pemungutan Suara</h3>
                    </div>
                    <p class="text-xs text-slate-500">Histori lonjakan waktu dan volume pemilih yang melakukan tap kartu RFID di bilik suara.</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-mono font-bold bg-blue-50 text-blue-800 border border-blue-200 self-start sm:self-auto">
                    Total Tervalidasi: <strong id="timeline-total">{{ $timelineData['total_recorded'] }}</strong> Suara
                </span>
            </div>

            <!-- ApexCharts Spline Area Timeline -->
            <div id="apexChartTimeline" class="w-full min-h-[280px]"></div>
        </div>

        <!-- Right: Official Recap Report Card with Real Vector PDF & Excel Export -->
        <div class="lg:col-span-4 p-5 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-sm flex flex-col justify-between space-y-5">
            <div>
                <div class="flex items-center space-x-2 pb-3 mb-4 border-b border-slate-100">
                    <span class="text-lg">📋</span>
                    <h3 class="text-base font-extrabold text-slate-900">Rekapitulasi & Berita Acara</h3>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed mb-4">
                    Unduh dokumen rekapitulasi pemilihan resmi untuk arsip panitia, saksi, dan sidang pleno pengesahan hasil suara.
                </p>

                <div class="space-y-2.5 p-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 font-medium">Format Dokumen:</span>
                        <span class="font-bold text-slate-900">PDF (Vector) & Excel</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 font-medium">Validasi Quorum:</span>
                        <span class="font-bold {{ $turnoutPct >= 50.0 ? 'text-emerald-700' : 'text-amber-700' }}">{{ $turnoutPct >= 50.0 ? 'Sah Terpenuhi' : 'Menuju Quorum' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 font-medium">Keamanan Hash:</span>
                        <span class="font-mono text-[10px] text-blue-700 font-bold">SHA-256 Verified</span>
                    </div>
                </div>
            </div>

            <div class="space-y-2.5 pt-2">
                <!-- Export to Official Vector PDF via DomPDF -->
                <a 
                    href="{{ route('admin.dashboard.export.pdf') }}" 
                    class="w-full py-3 px-4 rounded-2xl bg-gradient-to-r from-rose-600 via-red-600 to-rose-700 hover:from-rose-700 hover:to-red-800 text-white font-black text-xs sm:text-sm shadow-md transition flex items-center justify-center space-x-2"
                    title="Cetak Berita Acara Resmi Format PDF (DomPDF Vector Library)"
                >
                    <span class="text-base">📄</span>
                    <span>Unduh Berita Acara (PDF Resmi)</span>
                </a>

                <!-- Export to Excel (CSV UTF-8 BOM) -->
                <a 
                    href="{{ route('admin.dashboard.export.excel') }}" 
                    class="w-full py-3 px-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs sm:text-sm shadow-md transition flex items-center justify-center space-x-2"
                    title="Unduh Tabel Rekapitulasi Suara Format Excel Spreadsheet"
                >
                    <span class="text-base">📊</span>
                    <span>Unduh Rekapan Spreadsheet (Excel)</span>
                </a>
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script>
    let apexKetuaChart = null;
    let apexPengawasChart = null;
    let apexTimelineChart = null;

    const initialKetua = @json($ketuaResults);
    const initialPengawas = @json($pengawasResults);
    const timelineData = @json($timelineData);

    const chartColors = ['#2563eb', '#059669', '#d97706', '#7c3aed', '#db2777', '#0891b2', '#ea580c'];

    document.addEventListener('DOMContentLoaded', function() {
        initDashRfid3D();
        if (typeof ApexCharts === 'undefined') return;

        // 1. ApexChart Ketua (Donut)
        const ketuaSeries = initialKetua.map(k => k.suara);
        const ketuaLabels = initialKetua.map(k => `No. ${k.nomor_urut} ${k.nama}`);
        const totalSuaraKetua = ketuaSeries.reduce((a, b) => a + b, 0);

        const optionsKetua = {
            chart: {
                type: 'donut',
                height: 250,
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                animations: { enabled: true, easing: 'easeinout', speed: 600 }
            },
            series: totalSuaraKetua > 0 ? ketuaSeries : [1],
            labels: totalSuaraKetua > 0 ? ketuaLabels : ['Belum Ada Suara'],
            colors: totalSuaraKetua > 0 ? chartColors.slice(0, initialKetua.length) : ['#e2e8f0'],
            dataLabels: { enabled: totalSuaraKetua > 0 },
            legend: { position: 'bottom', fontSize: '11px', fontWeight: 600 },
            plotOptions: {
                pie: {
                    donut: {
                        size: '68%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total Suara',
                                fontSize: '12px',
                                fontWeight: 700,
                                color: '#64748b',
                                formatter: () => `${totalSuaraKetua} Suara`
                            }
                        }
                    }
                }
            },
            tooltip: {
                enabled: totalSuaraKetua > 0,
                y: { formatter: (val) => `${val} Suara` }
            }
        };

        const ketuaEl = document.getElementById('apexChartKetua');
        if (ketuaEl) {
            apexKetuaChart = new ApexCharts(ketuaEl, optionsKetua);
            apexKetuaChart.render();
        }

        // 2. ApexChart Pengawas (Donut)
        const pengawasSeries = initialPengawas.map(p => p.suara);
        const pengawasLabels = initialPengawas.map(p => `No. ${p.nomor_urut} ${p.nama}`);
        const totalSuaraPengawas = pengawasSeries.reduce((a, b) => a + b, 0);

        const optionsPengawas = {
            chart: {
                type: 'donut',
                height: 250,
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                animations: { enabled: true, easing: 'easeinout', speed: 600 }
            },
            series: totalSuaraPengawas > 0 ? pengawasSeries : [1],
            labels: totalSuaraPengawas > 0 ? pengawasLabels : ['Belum Ada Suara'],
            colors: totalSuaraPengawas > 0 ? chartColors.slice(0, initialPengawas.length) : ['#e2e8f0'],
            dataLabels: { enabled: totalSuaraPengawas > 0 },
            legend: { position: 'bottom', fontSize: '11px', fontWeight: 600 },
            plotOptions: {
                pie: {
                    donut: {
                        size: '68%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total Suara',
                                fontSize: '12px',
                                fontWeight: 700,
                                color: '#64748b',
                                formatter: () => `${totalSuaraPengawas} Suara`
                            }
                        }
                    }
                }
            },
            tooltip: {
                enabled: totalSuaraPengawas > 0,
                y: { formatter: (val) => `${val} Suara` }
            }
        };

        const pengawasEl = document.getElementById('apexChartPengawas');
        if (pengawasEl) {
            apexPengawasChart = new ApexCharts(pengawasEl, optionsPengawas);
            apexPengawasChart.render();
        }

        // 3. ApexChart Voting Activity Timeline (Smooth Spline Area)
        const optionsTimeline = {
            chart: {
                type: 'area',
                height: 270,
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                toolbar: { show: false },
                animations: { enabled: true, easing: 'easeinout', speed: 600 }
            },
            series: [{
                name: 'Suara Masuk',
                data: timelineData.series || []
            }],
            xaxis: {
                categories: timelineData.categories || [],
                labels: { style: { fontSize: '11px', fontWeight: 600, colors: '#64748b' } }
            },
            yaxis: {
                min: 0,
                forceNiceScale: true,
                labels: { style: { fontSize: '11px', fontWeight: 600, colors: '#64748b' } }
            },
            colors: ['#2563eb'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [0, 95, 100]
                }
            },
            stroke: { curve: 'smooth', width: 3 },
            dataLabels: { enabled: false },
            tooltip: {
                theme: 'dark',
                y: { formatter: (val) => `${val} Pemilih` }
            }
        };

        const timelineEl = document.getElementById('apexChartTimeline');
        if (timelineEl) {
            apexTimelineChart = new ApexCharts(timelineEl, optionsTimeline);
            apexTimelineChart.render();
        }

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

    function toggleGeminiDrawer() {
        const el = document.getElementById('gemini-key-drawer');
        el.classList.toggle('hidden');
    }

    function toggleGeminiVisibility() {
        const inp = document.getElementById('gemini-input');
        const ico = document.getElementById('gemini-eye-icon');
        if (inp.type === 'password') {
            inp.type = 'text';
            ico.innerText = '🙈';
        } else {
            inp.type = 'password';
            ico.innerText = '👁';
        }
    }

    // Real Test Connection to Google Gemini API
    async function testGeminiConnection() {
        const btn = document.getElementById('btn-test-gemini');
        const icon = document.getElementById('gemini-test-icon');
        const text = document.getElementById('gemini-test-text');
        const resultBox = document.getElementById('gemini-test-result');
        const inputKey = document.getElementById('gemini-input').value.trim();

        btn.disabled = true;
        icon.innerText = '⏳';
        text.innerText = 'Testing API...';
        resultBox.className = 'p-3.5 rounded-xl text-xs font-semibold border bg-blue-50 text-blue-900 border-blue-200 block animate-pulse';
        resultBox.innerText = 'Sedang mengirim request ping ke Google Generative AI Studio...';

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch("{{ route('admin.settings.gemini-test') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ gemini_api_key: inputKey })
            });

            const data = await res.json();
            if (res.ok && data.success) {
                resultBox.className = 'p-3.5 rounded-xl text-xs font-semibold border bg-emerald-50 text-emerald-900 border-emerald-300 block';
                resultBox.innerHTML = `
                    <div class="flex items-center space-x-2 mb-1">
                        <span class="text-emerald-700 text-sm font-bold">✓</span>
                        <strong class="text-emerald-950 font-black">Google Gemini API Connected!</strong>
                    </div>
                    <p class="text-emerald-800 text-[11px] leading-relaxed">${data.message}</p>
                    <div class="mt-1.5 pt-1.5 border-t border-emerald-200 flex items-center justify-between text-[10px] text-emerald-700 font-mono">
                        <span>Model: ${data.model}</span>
                        <span>Respon: "${data.reply}"</span>
                    </div>
                `;
            } else {
                resultBox.className = 'p-3.5 rounded-xl text-xs font-semibold border bg-rose-50 text-rose-900 border-rose-300 block';
                resultBox.innerHTML = `
                    <div class="flex items-center space-x-2 mb-1">
                        <span class="text-rose-700 text-sm font-bold">✕</span>
                        <strong class="text-rose-950 font-black">Koneksi API Gagal</strong>
                    </div>
                    <p class="text-rose-800 text-[11px] leading-relaxed">${data.message || 'Periksa kembali API Key Google AI Studio Anda.'}</p>
                `;
            }
        } catch (err) {
            console.error(err);
            resultBox.className = 'p-3.5 rounded-xl text-xs font-semibold border bg-rose-50 text-rose-900 border-rose-300 block';
            resultBox.innerText = 'Terjadi kesalahan koneksi jaringan saat menguji Gemini API.';
        } finally {
            btn.disabled = false;
            icon.innerText = '🔌';
            text.innerText = 'Test API Key';
        }
    }

    // Trigger AI Analysis on demand (REAL Gemini API call)
    function triggerAiAnalysis() {
        if (window.SoundEffects) window.SoundEffects.click();

        const btn = document.getElementById('btn-trigger-ai');
        const btnText = document.getElementById('ai-btn-text');
        const btnIcon = document.getElementById('ai-btn-icon');
        const stateInitial = document.getElementById('ai-state-initial');
        const stateLoading = document.getElementById('ai-state-loading');
        const stateResult = document.getElementById('ai-state-result');

        btn.disabled = true;
        btn.classList.add('opacity-75');
        btnText.innerText = 'Menganalisis...';
        btnIcon.innerText = '⏳';

        stateInitial.classList.add('hidden');
        stateResult.classList.add('hidden');
        stateLoading.classList.remove('hidden');

        fetch("{{ route('admin.api.ai-conclusion') }}")
            .then(res => res.json())
            .then(ai => {
                updateAiWidget(ai);
                stateLoading.classList.add('hidden');
                stateResult.classList.remove('hidden');
                btn.disabled = false;
                btn.classList.remove('opacity-75');
                btnText.innerText = '↻ Perbarui Analisis AI';
                btnIcon.innerText = '⚡';
            })
            .catch(err => {
                console.error(err);
                stateLoading.classList.add('hidden');
                stateInitial.classList.remove('hidden');
                btn.disabled = false;
                btn.classList.remove('opacity-75');
                btnText.innerText = 'Analisis dengan Gemini AI';
                btnIcon.innerText = '⚡';
                alert('Gagal mengambil analisis AI. Periksa koneksi internet atau Gemini API Key.');
            });
    }

    function updateAdminDashboard(data) {
        if (!data || !data.metrics) return;

        document.getElementById('metric-total-voters').innerText = data.metrics.total_voters;
        document.getElementById('metric-total-voted').innerText = data.metrics.total_voted;
        document.getElementById('metric-turnout-pct').innerText = data.metrics.turnout_percentage + '%';
        document.getElementById('metric-turnout-bar').style.width = data.metrics.turnout_percentage + '%';
        document.getElementById('metric-remaining').innerText = data.metrics.remaining_voters;

        const qChip = document.getElementById('quorum-chip');
        if (qChip) {
            if (data.metrics.turnout_percentage >= 50.0) {
                qChip.className = 'text-[11px] font-bold px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700';
                qChip.innerText = '✓ Quorum Sah';
            } else {
                qChip.className = 'text-[11px] font-bold px-2 py-0.5 rounded-md bg-amber-50 text-amber-700';
                qChip.innerText = 'Menuju Quorum';
            }
        }

        // Live update ApexCharts
        if (apexKetuaChart && data.ketua_results) {
            const seriesK = data.ketua_results.map(k => k.suara);
            const totalK = seriesK.reduce((a, b) => a + b, 0);
            if (totalK > 0) {
                apexKetuaChart.updateSeries(seriesK);
            }
        }

        if (apexPengawasChart && data.pengawas_results) {
            const seriesP = data.pengawas_results.map(p => p.suara);
            const totalP = seriesP.reduce((a, b) => a + b, 0);
            if (totalP > 0) {
                apexPengawasChart.updateSeries(seriesP);
            }
        }
    }

    function updateAiWidget(ai) {
        if (!ai) return;

        document.getElementById('ai-confidence-badge').innerText = (ai.confidence_score || '95') + '% Confidence';
        document.getElementById('ai-summary-text').innerText = ai.summary || '-';
        document.getElementById('ai-quorum-badge').innerText = ai.quorum_status || 'Quorum Evaluation';
        document.getElementById('ai-leader-ketua').innerText = ai.leader_ketua || 'Belum Ada';
        document.getElementById('ai-leader-pengawas').innerText = ai.leader_pengawas || 'Belum Ada';
        document.getElementById('ai-margin-ketua').innerText = 'Margin: +' + (ai.margin_ketua ?? 0) + ' Suara';
        document.getElementById('ai-margin-pengawas').innerText = 'Margin: +' + (ai.margin_pengawas ?? 0) + ' Suara';
        document.getElementById('ai-timestamp').innerText = ai.generated_at || '-';

        const modelPill = document.getElementById('ai-model-pill');
        if (modelPill && ai.ai_source) {
            modelPill.innerText = ai.ai_source;
        }

        if (ai.insights && ai.insights.length) {
            let insightsHtml = '';
            ai.insights.forEach(ins => {
                insightsHtml += `
                    <div class="flex items-start space-x-2.5 text-xs text-indigo-100/90 bg-white/5 p-2.5 rounded-xl border border-white/5">
                        <span class="text-blue-400 font-bold shrink-0">✦</span>
                        <span class="leading-relaxed">${ins}</span>
                    </div>
                `;
            });
            document.getElementById('ai-insights-container').innerHTML = insightsHtml;
        }
    }

    // Three.js 3D Interactive Keplek Showcase Controllers
    let rfid3dDashInstance = null;

    function initDashRfid3D() {
        const container = document.getElementById('dashboard-rfid-3d-canvas');
        if (!container) return;
        if (typeof window.initRfid3DCard === 'function') {
            rfid3dDashInstance = window.initRfid3DCard('dashboard-rfid-3d-canvas', {
                autoRotate: true,
                frontImage: '/images/id_card_ref.jpeg'
            });
        }
    }

    function toggleDashCardSpin() {
        if (!rfid3dDashInstance) return;
        const isSpinning = rfid3dDashInstance.toggleAutoRotate();
        const icon = document.getElementById('dash-spin-icon');
        const text = document.getElementById('dash-spin-text');
        if (icon && text) {
            icon.innerText = isSpinning ? '⏸' : '▶';
            text.innerText = isSpinning ? 'Jeda Putaran' : 'Mulai Putar';
        }
    }

    function flipDashCard() {
        if (rfid3dDashInstance) rfid3dDashInstance.flipCard();
    }

    function rotateDashCard360X() {
        if (rfid3dDashInstance) rfid3dDashInstance.rotateX360();
    }

    function resetDashCardView() {
        if (rfid3dDashInstance) {
            rfid3dDashInstance.resetAngle();
            const icon = document.getElementById('dash-spin-icon');
            const text = document.getElementById('dash-spin-text');
            if (icon && text) {
                icon.innerText = '⏸';
                text.innerText = 'Jeda Putaran';
            }
        }
    }
</script>
@endpush
@endsection
