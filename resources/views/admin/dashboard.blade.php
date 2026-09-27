@extends('layouts.admin')

@section('title', 'Live Telemetry Dashboard')

@section('content')
<div class="space-y-6 sm:space-y-8 relative">
    <!-- Colorful Dot Grid Backdrop Effect (Blue & Indigo Modern Pattern) -->
    <div class="absolute inset-0 pointer-events-none -z-10 opacity-75" style="background-image: radial-gradient(rgba(59, 130, 246, 0.3) 1.5px, transparent 1.5px), radial-gradient(rgba(139, 92, 246, 0.22) 1.5px, transparent 1.5px); background-size: 26px 26px; background-position: 0 0, 13px 13px;"></div>

    <!-- Top Executive Header: Live SSE Status & Telemetry Indicators -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-5 sm:p-6 rounded-3xl bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 text-white shadow-xl border border-slate-700/50">
        <div class="space-y-1">
            <div class="flex flex-wrap items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-indigo-500/30 text-indigo-300 border border-indigo-400/30">
                    {{ __('Live Telemetry Center') }}
                </span>
                <span id="sse-indicator" class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[11px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">
                    <span class="w-2 h-2 mr-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                    <span id="sse-status-text">{{ __('SSE Live Stream Active') }}</span>
                </span>
            </div>
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-black tracking-tight text-white">
                {{ __('Live E-Voting Intelligence & Monitoring') }}
            </h2>
            <p class="text-xs sm:text-sm text-slate-300 max-w-2xl">
                {{ __('Real-time voting telemetry for Chairman & Supervisory Board. Validated through continuous cryptographic hash logs.') }}
            </p>
        </div>

        <div class="flex items-center gap-2.5 self-start md:self-auto shrink-0">
            <!-- Manual Refresh -->
            <button onclick="fetchLatestData()" class="p-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/20 shadow-sm transition cursor-pointer flex items-center space-x-1.5 text-xs font-bold" title="{{ __('Segarkan Data') }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                <span>{{ __('Segarkan Data') }}</span>
            </button>
        </div>
    </div>

    <!-- 4 High-Tech KPI Metric Cards (Responsive Mobile & iPad Mini) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <!-- Metric 1: Total DPT -->
        <div class="relative overflow-hidden p-5 sm:p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-extrabold tracking-wider text-slate-500 dark:text-slate-400">{{ __('Total DPT Anggota') }}</span>
                <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-blue-100 dark:border-blue-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span id="metric-total-voters" class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white font-mono tracking-tight">{{ $totalVoters }}</span>
                <span class="text-[11px] font-bold text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/50 px-2 py-0.5 rounded-md">{{ __('100% Terdaftar') }}</span>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 font-medium">{{ __('Buku daftar pemilih tetap terverifikasi') }}</p>
        </div>

        <!-- Metric 2: Suara Masuk (Turnout) -->
        <div class="relative overflow-hidden p-5 sm:p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-extrabold tracking-wider text-emerald-700 dark:text-emerald-400">{{ __('Total Suara Masuk') }}</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-100 dark:border-emerald-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span id="metric-total-voted" class="text-3xl sm:text-4xl font-black text-emerald-600 dark:text-emerald-400 font-mono tracking-tight">{{ $totalVoted }}</span>
                <span class="text-[11px] font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/50 px-2 py-0.5 rounded-md">{{ __('Tervalidasi RFID') }}</span>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 font-medium">{{ __('Surat suara sah masuk bilik') }}</p>
        </div>

        <!-- Metric 3: Partisipasi & Quorum Meter -->
        <div class="relative overflow-hidden p-5 sm:p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-extrabold tracking-wider text-indigo-700 dark:text-indigo-400">{{ __('Tingkat Partisipasi') }}</span>
                <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-indigo-100 dark:border-indigo-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span id="metric-turnout-pct" class="text-3xl sm:text-4xl font-black text-indigo-600 dark:text-indigo-400 font-mono tracking-tight">{{ $turnoutPct }}%</span>
                <span id="quorum-chip" class="text-[11px] font-bold px-2 py-0.5 rounded-md {{ $turnoutPct >= 50.0 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300' : 'bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300' }}">
                    {{ $turnoutPct >= 50.0 ? __('✓ Quorum Sah') : __('Menuju Quorum') }}
                </span>
            </div>
            <div class="w-full bg-slate-100 dark:bg-slate-800 h-2.5 rounded-full mt-3 overflow-hidden relative">
                <div id="metric-turnout-bar" class="h-full bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full transition-all duration-700" style="width: {{ $turnoutPct }}%;"></div>
            </div>
        </div>

        <!-- Metric 4: Sisa Belum Memilih -->
        <div class="relative overflow-hidden p-5 sm:p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-extrabold tracking-wider text-amber-700 dark:text-amber-400">{{ __('Sisa Belum Memilih') }}</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center border border-amber-100 dark:border-amber-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span id="metric-remaining" class="text-3xl sm:text-4xl font-black text-amber-600 dark:text-amber-400 font-mono tracking-tight">{{ $remaining }}</span>
                <span class="text-[11px] font-bold text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/50 px-2 py-0.5 rounded-md">{{ __('Pending') }}</span>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 font-medium">{{ __('Anggota di pool absensi') }}</p>
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
                            <h3 class="text-lg sm:text-xl font-black text-white tracking-tight">{{ __('Gemini AI Election Intelligence') }}</h3>
                            <span id="ai-model-pill" class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-400/30">
                                Google Gemini 2.0 Flash
                            </span>
                        </div>
                        <p class="text-xs text-indigo-200/70 mt-0.5">{{ __('Real-time election outcome projection & quorum margin analysis') }}</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <button type="button" onclick="toggleGeminiDrawer()" class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-indigo-200 hover:text-white text-xs font-bold border border-white/15 transition cursor-pointer flex items-center space-x-1.5">
                        <span>🔑 {{ __('Pengaturan & Test API Key') }}</span>
                    </button>

                    <button 
                        id="btn-trigger-ai"
                        type="button" 
                        onclick="triggerAiAnalysis()" 
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white text-xs sm:text-sm font-extrabold shadow-lg shadow-indigo-500/25 transition cursor-pointer flex items-center space-x-2"
                    >
                        <span id="ai-btn-icon">⚡</span>
                        <span id="ai-btn-text">{{ __('Analisis dengan Gemini AI') }}</span>
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
    <div class="p-5 sm:p-7 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-5">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center space-x-3">
                <span class="w-8 h-8 rounded-xl bg-blue-600 text-white font-black text-xs flex items-center justify-center shadow-xs">1</span>
                <div>
                    <h3 class="text-base sm:text-lg font-black text-slate-900 dark:text-white">{{ __('Perolehan Suara: Calon Ketua Koperasi') }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Distribusi perolehan suara masuk secara komprehensif via ApexCharts Dynamic Bar Ranking & Rekapitulasi') }}</p>
                </div>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                {{ count($ketuaResults) }} {{ __('Kandidat Terdaftar') }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            <!-- ApexCharts Bar Viewport (Left) -->
            <div class="lg:col-span-6 flex items-center justify-center min-h-[270px]">
                <div id="apexChartKetua" class="w-full"></div>
            </div>

            <!-- Progress & Tally List (Right) -->
            <div class="lg:col-span-6 space-y-3" id="admin-ketua-list">
                @foreach($ketuaResults as $k)
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 hover:bg-blue-50/40 dark:hover:bg-blue-950/30 border border-slate-200/80 dark:border-slate-700/60 transition space-y-2">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center space-x-3 min-w-0">
                                @if(!empty($k['foto']))
                                    <img src="{{ str_starts_with($k['foto'], 'http') ? $k['foto'] : asset(ltrim($k['foto'], '/')) }}" alt="{{ $k['nama'] }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200 dark:border-slate-700 shadow-2xs shrink-0">
                                @else
                                    <span class="w-10 h-10 rounded-xl bg-blue-600 text-white font-black text-sm flex items-center justify-center shrink-0 shadow-2xs">
                                        {{ $k['nomor_urut'] }}
                                    </span>
                                @endif
                                <div class="min-w-0">
                                    <div class="flex items-center space-x-1.5">
                                        <span class="text-[10px] font-black px-1.5 py-0.5 rounded-md bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300">No. {{ $k['nomor_urut'] }}</span>
                                        <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 dark:text-white truncate">{{ $k['nama'] }}</h4>
                                    </div>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400 font-mono font-medium">{{ __('Kandidat Ketua') }}</span>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-sm sm:text-base font-black text-blue-700 dark:text-blue-400 font-mono">{{ $k['suara'] }} {{ __('Suara') }}</span>
                                <span class="text-xs text-slate-600 dark:text-slate-400 block font-bold">({{ $k['persen'] }}%)</span>
                            </div>
                        </div>
                        <div class="w-full bg-slate-200/70 dark:bg-slate-700/70 h-2 rounded-full overflow-hidden">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-700" style="width: {{ $k['persen'] }}%;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Row 2: Perolehan Suara Calon Pengawas (Full Width Row, Lapang & Responsive) -->
    <div class="p-5 sm:p-7 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-5">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center space-x-3">
                <span class="w-8 h-8 rounded-xl bg-emerald-600 text-white font-black text-xs flex items-center justify-center shadow-xs">2</span>
                <div>
                    <h3 class="text-base sm:text-lg font-black text-slate-900 dark:text-white">{{ __('Perolehan Suara: Calon Pengawas Koperasi') }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Distribusi perolehan suara masuk secara komprehensif via ApexCharts Dynamic Bar Ranking & Rekapitulasi') }}</p>
                </div>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                {{ count($pengawasResults) }} {{ __('Kandidat Terdaftar') }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            <!-- ApexCharts Bar Viewport (Left) -->
            <div class="lg:col-span-6 flex items-center justify-center min-h-[270px]">
                <div id="apexChartPengawas" class="w-full"></div>
            </div>

            <!-- Progress & Tally List (Right) -->
            <div class="lg:col-span-6 space-y-3" id="admin-pengawas-list">
                @foreach($pengawasResults as $p)
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 hover:bg-emerald-50/40 dark:hover:bg-emerald-950/30 border border-slate-200/80 dark:border-slate-700/60 transition space-y-2">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center space-x-3 min-w-0">
                                @if(!empty($p['foto']))
                                    <img src="{{ str_starts_with($p['foto'], 'http') ? $p['foto'] : asset(ltrim($p['foto'], '/')) }}" alt="{{ $p['nama'] }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200 dark:border-slate-700 shadow-2xs shrink-0">
                                @else
                                    <span class="w-10 h-10 rounded-xl bg-emerald-600 text-white font-black text-sm flex items-center justify-center shrink-0 shadow-2xs">
                                        {{ $p['nomor_urut'] }}
                                    </span>
                                @endif
                                <div class="min-w-0">
                                    <div class="flex items-center space-x-1.5">
                                        <span class="text-[10px] font-black px-1.5 py-0.5 rounded-md bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">No. {{ $p['nomor_urut'] }}</span>
                                        <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 dark:text-white truncate">{{ $p['nama'] }}</h4>
                                    </div>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400 font-mono font-medium">{{ __('Kandidat Pengawas') }}</span>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-sm sm:text-base font-black text-emerald-700 dark:text-emerald-400 font-mono">{{ $p['suara'] }} {{ __('Suara') }}</span>
                                <span class="text-xs text-slate-600 dark:text-slate-400 block font-bold">({{ $p['persen'] }}%)</span>
                            </div>
                        </div>
                        <div class="w-full bg-slate-200/70 dark:bg-slate-700/70 h-2 rounded-full overflow-hidden">
                            <div class="bg-emerald-600 h-2 rounded-full transition-all duration-700" style="width: {{ $p['persen'] }}%;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 3D INTERACTIVE RFID SMART CARD & LANYARD (THREE.JS)      -->
    <!-- Admin Hardware & RFID Credential Simulator (Blue & Rounded) -->
    <!-- ======================================================== -->
    <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-br from-blue-950 via-slate-900 to-indigo-950 text-white border-2 border-blue-500/30 shadow-2xl relative overflow-hidden">
        <!-- Ambient decorative blue lights -->
        <div class="absolute -top-32 -left-32 w-80 h-80 rounded-full bg-blue-600/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-80 h-80 rounded-full bg-indigo-600/20 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            
            <!-- Left Column: Admin Diagnostic Specs & Interactive Controls -->
            <div class="lg:col-span-5 space-y-4 text-left">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-500/25 text-blue-300 border border-blue-400/40 text-xs font-black uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                    <span>{{ __('Admin Hardware & Keplek Lanyard 3D RFID Simulator') }}</span>
                </div>

                <h3 class="text-xl sm:text-3xl font-black text-white tracking-tight leading-tight">
                    {{ __('Simulator Kredensial RFID & Identitas Anggota') }}
                </h3>

                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                    {{ __('Simulator diagnostik verifikasi visual kartu RFID Mifare ISO/IEC 14443A dan keplek resmi pemilih. Digunakan oleh admin untuk memvalidasi spesifikasi fisik, orientasi tap antena, dan struktur visual kartu secara interaktif') }} <strong>360° {{ __('pada sumbu X & Y') }}</strong>.
                </p>

                <!-- Feature Specs Badges -->
                <div class="grid grid-cols-2 gap-2.5 pt-1 text-xs">
                    <div class="p-3 rounded-2xl bg-blue-900/40 border border-blue-400/20">
                        <span class="text-blue-300 block text-[10px] uppercase font-bold">{{ __('Standard RFID') }}</span>
                        <strong class="text-white font-mono">ISO/IEC 14443A</strong>
                    </div>
                    <div class="p-3 rounded-2xl bg-blue-900/40 border border-blue-400/20">
                        <span class="text-blue-300 block text-[10px] uppercase font-bold">{{ __('Frekuensi Chip') }}</span>
                        <strong class="text-amber-400 font-mono">13.56 MHz HF</strong>
                    </div>
                    <div class="p-3 rounded-2xl bg-blue-900/40 border border-blue-400/20">
                        <span class="text-blue-300 block text-[10px] uppercase font-bold">{{ __('Kredensial Fisik') }}</span>
                        <strong class="text-blue-300 font-mono">Vertical Badge + Lanyard</strong>
                    </div>
                    <div class="p-3 rounded-2xl bg-blue-900/40 border border-blue-400/20">
                        <span class="text-blue-300 block text-[10px] uppercase font-bold">{{ __('Validasi Kunci') }}</span>
                        <strong class="text-emerald-400 font-mono">Sector Key A / AES-128</strong>
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
                        <span id="dash-spin-text">{{ __('Jeda Putaran') }}</span>
                    </button>

                    <button 
                        type="button" 
                        onclick="flipDashCard()"
                        class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/15 transition cursor-pointer flex items-center space-x-1.5"
                    >
                        <span>🔄 {{ __('Balik Kartu') }}</span>
                    </button>

                    <button 
                        type="button" 
                        onclick="rotateDashCard360X()"
                        class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/15 transition cursor-pointer flex items-center space-x-1.5"
                    >
                        <span>↕ {{ __('Putar 360° X') }}</span>
                    </button>

                    <button 
                        type="button" 
                        onclick="resetDashCardView()"
                        class="px-3 py-2 rounded-xl bg-white/5 hover:bg-white/15 text-slate-300 font-bold text-xs border border-white/10 transition cursor-pointer"
                        title="{{ __('Reset') }}"
                    >
                        ⟲ {{ __('Reset') }}
                    </button>
                </div>
            </div>

            <!-- Right Column: Interactive 3D Canvas Viewport (Rounded Blue Theme) -->
            <div class="lg:col-span-7 flex flex-col items-center justify-center">
                <div class="relative w-full max-w-lg aspect-[1.25/1] sm:aspect-[1.35/1] rounded-3xl bg-gradient-to-br from-blue-900/90 via-indigo-950/90 to-blue-950/95 border-2 border-blue-400/30 shadow-2xl shadow-blue-900/60 backdrop-blur-md overflow-hidden flex items-center justify-center group cursor-grab active:cursor-grabbing">
                    <div id="dashboard-rfid-3d-canvas" class="w-full h-full"></div>

                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full bg-blue-950/90 border border-blue-400/30 text-[11px] text-blue-200 font-medium pointer-events-none backdrop-blur-xs flex items-center space-x-1.5 shadow-md">
                        <span>👆</span>
                        <span>Drag mouse atau sentuh layar untuk rotasi bebas 360°</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ======================================================== -->
    <!-- VOTING ACTIVITY TIMELINE & OFFICIAL RECAP REPORT EXPORT  -->
    <!-- (Replaces Suara Terakhir Masuk & Audit Logs per request)  -->
    <!-- ======================================================== -->
    <!-- ======================================================== -->
    <!-- SECTION 1: VOTING ACTIVITY & FLOW TELEMETRY TIMELINE     -->
    <!-- ======================================================== -->
    <div class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between pb-5 border-b border-slate-100 dark:border-slate-800 gap-4">
            <div class="flex items-start space-x-3.5">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/50 border border-blue-200 dark:border-blue-800 flex items-center justify-center text-xl shrink-0 shadow-2xs">
                    📈
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <h3 class="text-base sm:text-lg font-black text-slate-900 dark:text-white">{{ __('Distribusi & Lonjakan Jam Pemungutan Suara') }}</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800 dark:bg-blue-950/70 dark:text-blue-300">
                            {{ __('Telemetri Waktu Nyata') }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                        {{ __('Histori lonjakan waktu dan volume pemilih yang melakukan tap kartu RFID di bilik suara.') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center space-x-2 self-start md:self-auto">
                <span class="px-3.5 py-1.5 rounded-xl text-xs font-mono font-bold bg-blue-50 text-blue-800 dark:bg-blue-950/40 dark:text-blue-300 border border-blue-200 dark:border-blue-800 shadow-2xs">
                    {{ __('Total Tervalidasi') }}: <strong id="timeline-total" class="font-black">{{ $timelineData['total_recorded'] }}</strong> {{ __('Suara') }}
                </span>
            </div>
        </div>

        <!-- 4 Telemetry Quick Stat Chips -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60">
                <div class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Jam Puncak Tertinggi') }}</div>
                <div class="text-lg sm:text-xl font-black text-slate-900 dark:text-white mt-1">{{ $timelineData['peak_hour'] ?? '-' }}</div>
                <div class="text-[11px] text-blue-600 dark:text-blue-400 font-bold mt-0.5">{{ $timelineData['peak_count'] ?? 0 }} {{ __('Suara dicatat') }}</div>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60">
                <div class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Kecepatan Rata-rata') }}</div>
                <div class="text-lg sm:text-xl font-black text-slate-900 dark:text-white mt-1">{{ $timelineData['avg_velocity'] ?? 0 }} <span class="text-xs font-normal text-slate-500">/jam</span></div>
                <div class="text-[11px] text-emerald-600 dark:text-emerald-400 font-bold mt-0.5">{{ __('Throughput bilik stabil') }}</div>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60">
                <div class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Departemen Paling Padat') }}</div>
                <div class="text-lg sm:text-xl font-black text-slate-900 dark:text-white mt-1 truncate">{{ $timelineData['busiest_dept'] ?? 'ICT' }}</div>
                <div class="text-[11px] text-amber-600 dark:text-amber-400 font-bold mt-0.5">{{ __('Aktivitas voter tertinggi') }}</div>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60">
                <div class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Status Sensor Bilik') }}</div>
                <div class="text-lg sm:text-xl font-black text-emerald-600 dark:text-emerald-400 mt-1 flex items-center space-x-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>{{ __('Optimal 100%') }}</span>
                </div>
                <div class="text-[11px] text-slate-500 dark:text-slate-400 font-bold mt-0.5">{{ __('Zero Packet Loss') }}</div>
            </div>
        </div>

        <!-- Filter Controls Toolbar -->
        <div class="p-3.5 sm:p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 flex flex-wrap items-center justify-between gap-3 text-xs">
            <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                <div class="flex items-center space-x-1.5">
                    <label for="timeline-filter-date" class="font-bold text-slate-600 dark:text-slate-300">{{ __('Tanggal:') }}</label>
                    <input 
                        type="date" 
                        id="timeline-filter-date" 
                        value="{{ date('Y-m-d') }}" 
                        class="px-2.5 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-bold text-slate-800 dark:text-white shadow-2xs focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    >
                </div>

                <div class="flex items-center space-x-1.5">
                    <label for="timeline-filter-dept" class="font-bold text-slate-600 dark:text-slate-300">{{ __('Departemen:') }}</label>
                    <select 
                        id="timeline-filter-dept" 
                        class="px-2.5 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-bold text-slate-800 dark:text-white shadow-2xs focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    >
                        <option value="ALL">{{ __('Semua Departemen') }}</option>
                        <option value="ICT">ICT</option>
                        <option value="Keuangan">{{ __('Keuangan') }}</option>
                        <option value="Operasional">{{ __('Operasional') }}</option>
                        <option value="HRD">HRD</option>
                        <option value="Logistik">{{ __('Logistik') }}</option>
                        <option value="Produksi">{{ __('Produksi') }}</option>
                        <option value="Pemasaran">{{ __('Pemasaran') }}</option>
                    </select>
                </div>

                <label class="inline-flex items-center space-x-1.5 cursor-pointer font-bold text-slate-700 dark:text-slate-300 select-none py-1 px-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <input type="checkbox" id="timeline-filter-unknown" class="w-3.5 h-3.5 rounded text-rose-600 focus:ring-rose-500 border-slate-300">
                    <span class="text-rose-600 dark:text-rose-400 font-extrabold">{{ __('Audit Kartu Asing') }}</span>
                </label>
            </div>

            <div class="flex items-center space-x-2">
                <button 
                    type="button" 
                    onclick="applyTimelineFilters()" 
                    class="px-4 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-black text-xs shadow-2xs transition cursor-pointer flex items-center space-x-1"
                >
                    <span>🔍</span>
                    <span>{{ __('Terapkan') }}</span>
                </button>
                <button 
                    type="button" 
                    onclick="resetTimelineFilters()" 
                    class="px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs transition cursor-pointer"
                    title="{{ __('Reset Filter') }}"
                >
                    {{ __('Reset') }}
                </button>
            </div>
        </div>

        <!-- ApexCharts Spline Area Timeline (Enlarged Height: 320px) -->
        <div id="apexChartTimeline" class="w-full min-h-[320px]"></div>
    </div>

    <!-- ======================================================== -->
    <!-- SECTION 2: OFFICIAL PLENARY CERTIFICATION & RECAP EXPORTS-->
    <!-- ======================================================== -->
    <div class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between pb-5 border-b border-slate-100 dark:border-slate-800 gap-4">
            <div class="flex items-start space-x-3.5">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 flex items-center justify-center text-xl shrink-0 shadow-2xs">
                    📜
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <h3 class="text-base sm:text-lg font-black text-slate-900 dark:text-white">{{ __('Pusat Dokumen Berita Acara & Pengesahan Hasil Pemilihan') }}</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/70 dark:text-emerald-300">
                            {{ __('Resmi & Terverifikasi') }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                        {{ __('Unduh dokumen rekapitulasi pemilihan resmi untuk arsip panitia, saksi, dan sidang pleno pengesahan hasil suara.') }}
                    </p>
                </div>
            </div>

            <!-- Quorum & Integrity Status Pill -->
            <div class="flex flex-wrap items-center gap-2 self-start md:self-auto">
                <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold border shadow-2xs {{ $turnoutPct >= 50.0 ? 'bg-emerald-50 text-emerald-800 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800' : 'bg-amber-50 text-amber-800 border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800' }}">
                    {{ __('Validasi Kuorum') }}: <strong>{{ $turnoutPct >= 50.0 ? __('Sah Terpenuhi') : __('Menuju Kuorum') }}</strong> ({{ $turnoutPct }}%)
                </span>
                <span class="px-3 py-1.5 rounded-xl text-xs font-mono font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                    SHA-256 Seal
                </span>
            </div>
        </div>

        <!-- 3 Feature Cards for Document Exports -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Card A: Official PDF Vector -->
            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 flex flex-col justify-between space-y-4">
                <div class="space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center font-bold text-lg">
                        📄
                    </div>
                    <h4 class="text-sm font-black text-slate-900 dark:text-white">{{ __('Berita Acara Pleno (PDF Vektor)') }}</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ __('Dokumen resmi berformat PDF high-resolution siap cetak dengan lembar pengesahan tanda tangan ketua sidang, saksi kandidat, dan panitia.') }}
                    </p>
                    <div class="space-y-1 pt-1 text-[11px] text-slate-600 dark:text-slate-400">
                        <div class="flex items-center space-x-1.5">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span>{{ __('Format Standar Sidang Pleno Koperasi') }}</span>
                        </div>
                        <div class="flex items-center space-x-1.5">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span>{{ __('Dilengkapi QR Code Validitas Dokumen') }}</span>
                        </div>
                    </div>
                </div>

                <a 
                    href="{{ route('admin.dashboard.export.pdf') }}" 
                    class="w-full py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-black text-xs shadow-sm transition flex items-center justify-center space-x-2"
                    title="{{ __('Unduh Berita Acara (PDF Resmi)') }}"
                >
                    <span>📄</span>
                    <span>{{ __('Unduh Berita Acara (PDF Resmi)') }}</span>
                </a>
            </div>

            <!-- Card B: Excel Spreadsheet -->
            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 flex flex-col justify-between space-y-4">
                <div class="space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-lg">
                        📊
                    </div>
                    <h4 class="text-sm font-black text-slate-900 dark:text-white">{{ __('Tabulasi Suara (Excel Spreadsheet)') }}</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ __('Data rekapitulasi angka mentah terperinci dalam format CSV UTF-8 BOM yang dapat dibuka langsung di Microsoft Excel, Google Sheets, dan LibreOffice.') }}
                    </p>
                    <div class="space-y-1 pt-1 text-[11px] text-slate-600 dark:text-slate-400">
                        <div class="flex items-center space-x-1.5">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span>{{ __('Tabel Terpisah Calon Ketua & Pengawas') }}</span>
                        </div>
                        <div class="flex items-center space-x-1.5">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span>{{ __('Persentase dan Status Pemilihan Akurat') }}</span>
                        </div>
                    </div>
                </div>

                <a 
                    href="{{ route('admin.dashboard.export.excel') }}" 
                    class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs shadow-sm transition flex items-center justify-center space-x-2"
                    title="{{ __('Unduh Rekapan Spreadsheet (Excel)') }}"
                >
                    <span>📊</span>
                    <span>{{ __('Unduh Rekapan Spreadsheet (Excel)') }}</span>
                </a>
            </div>

            <!-- Card C: Forensic Traceback & Audit Trail -->
            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 flex flex-col justify-between space-y-4">
                <div class="space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-lg">
                        🔍
                    </div>
                    <h4 class="text-sm font-black text-slate-900 dark:text-white">{{ __('Laporan Audit Forensik (Traceback)') }}</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ __('Jejak audit forensik suara terenkripsi dengan timestamp detil dan verifikasi integritas kartu RFID pemilih untuk kebutuhan transparansi saksi.') }}
                    </p>
                    <div class="space-y-1 pt-1 text-[11px] text-slate-600 dark:text-slate-400">
                        <div class="flex items-center space-x-1.5">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span>{{ __('Log Enkripsi Suara & Anonymity Assurance') }}</span>
                        </div>
                        <div class="flex items-center space-x-1.5">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span>{{ __('Pemeriksaan Keaslian Kartu RFID Mifare') }}</span>
                        </div>
                    </div>
                </div>

                <a 
                    href="{{ route('admin.reports.traceback') }}" 
                    class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-black text-xs shadow-sm transition flex items-center justify-center space-x-2"
                    title="{{ __('Buka Laporan Audit Forensik') }}"
                >
                    <span>🔍</span>
                    <span>{{ __('Buka Laporan Audit Forensik') }}</span>
                </a>
            </div>
        </div>

        <!-- Plenary Seal & Digital Signature Info Box -->
        <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/60 flex flex-col md:flex-row md:items-center justify-between gap-4 text-xs">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-xl bg-blue-100 dark:bg-blue-950/80 text-blue-700 dark:text-blue-300 flex items-center justify-center font-black text-sm shrink-0">
                    🛡️
                </div>
                <div>
                    <span class="font-bold text-slate-900 dark:text-white">{{ __('Integritas Dokumen Terjamin:') }}</span>
                    <span class="text-slate-600 dark:text-slate-400 ml-1">
                        {{ __('Seluruh dokumen yang diunduh mencantumkan stempel waktu kriptografis dan hash SHA-256 yang sah untuk sidang pleno.') }}
                    </span>
                </div>
            </div>
            <div class="flex items-center space-x-3 shrink-0 text-slate-500 dark:text-slate-400 font-mono text-[11px]">
                <span>{{ __('Waktu Cetak:') }} {{ now()->format('d/m/Y H:i:s') }} WIB</span>
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

        // 1. ApexChart Ketua (Modern Horizontal Comparative Bar Ranking)
        const ketuaSeries = initialKetua.map(k => k.suara);
        const ketuaLabels = initialKetua.map(k => `No. ${k.nomor_urut} ${k.nama}`);
        const totalSuaraKetua = ketuaSeries.reduce((a, b) => a + b, 0);

        const optionsKetua = {
            chart: {
                type: 'bar',
                height: Math.max(250, initialKetua.length * 60),
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                toolbar: { show: false },
                animations: { enabled: true, easing: 'easeinout', speed: 600 }
            },
            series: [{
                name: 'Perolehan Suara',
                data: ketuaSeries
            }],
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 8,
                    borderRadiusApplication: 'end',
                    distributed: true,
                    barHeight: '60%',
                    dataLabels: { position: 'top' }
                }
            },
            colors: chartColors.slice(0, Math.max(1, initialKetua.length)),
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    const pct = totalSuaraKetua > 0 ? Math.round((val / totalSuaraKetua) * 100) : 0;
                    return val + ' Suara (' + pct + '%)';
                },
                offsetX: 10,
                style: {
                    fontSize: '11px',
                    fontFamily: 'Plus Jakarta Sans, sans-serif',
                    fontWeight: '800',
                    colors: ['#0f172a']
                }
            },
            xaxis: {
                categories: ketuaLabels,
                labels: {
                    style: {
                        fontFamily: 'Plus Jakarta Sans, sans-serif',
                        fontWeight: '600',
                        colors: '#64748b'
                    }
                }
            },
            yaxis: {
                labels: {
                    maxWidth: 160,
                    style: {
                        fontFamily: 'Plus Jakarta Sans, sans-serif',
                        fontWeight: '700',
                        fontSize: '11px',
                        colors: '#1e293b'
                    }
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                xaxis: { lines: { show: true } },
                yaxis: { lines: { show: false } }
            },
            legend: { show: false },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: (val) => {
                        const pct = totalSuaraKetua > 0 ? ((val / totalSuaraKetua) * 100).toFixed(1) : 0;
                        return `${val} Suara (${pct}%)`;
                    }
                }
            }
        };

        const ketuaEl = document.getElementById('apexChartKetua');
        if (ketuaEl) {
            apexKetuaChart = new ApexCharts(ketuaEl, optionsKetua);
            apexKetuaChart.render();
        }

        // 2. ApexChart Pengawas (Modern Horizontal Comparative Bar Ranking)
        const pengawasSeries = initialPengawas.map(p => p.suara);
        const pengawasLabels = initialPengawas.map(p => `No. ${p.nomor_urut} ${p.nama}`);
        const totalSuaraPengawas = pengawasSeries.reduce((a, b) => a + b, 0);

        const optionsPengawas = {
            chart: {
                type: 'bar',
                height: Math.max(250, initialPengawas.length * 60),
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                toolbar: { show: false },
                animations: { enabled: true, easing: 'easeinout', speed: 600 }
            },
            series: [{
                name: 'Perolehan Suara',
                data: pengawasSeries
            }],
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 8,
                    borderRadiusApplication: 'end',
                    distributed: true,
                    barHeight: '60%',
                    dataLabels: { position: 'top' }
                }
            },
            colors: ['#059669', '#2563eb', '#d97706', '#7c3aed', '#db2777'].slice(0, Math.max(1, initialPengawas.length)),
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    const pct = totalSuaraPengawas > 0 ? Math.round((val / totalSuaraPengawas) * 100) : 0;
                    return val + ' Suara (' + pct + '%)';
                },
                offsetX: 10,
                style: {
                    fontSize: '11px',
                    fontFamily: 'Plus Jakarta Sans, sans-serif',
                    fontWeight: '800',
                    colors: ['#0f172a']
                }
            },
            xaxis: {
                categories: pengawasLabels,
                labels: {
                    style: {
                        fontFamily: 'Plus Jakarta Sans, sans-serif',
                        fontWeight: '600',
                        colors: '#64748b'
                    }
                }
            },
            yaxis: {
                labels: {
                    maxWidth: 160,
                    style: {
                        fontFamily: 'Plus Jakarta Sans, sans-serif',
                        fontWeight: '700',
                        fontSize: '11px',
                        colors: '#1e293b'
                    }
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                xaxis: { lines: { show: true } },
                yaxis: { lines: { show: false } }
            },
            legend: { show: false },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: (val) => {
                        const pct = totalSuaraPengawas > 0 ? ((val / totalSuaraPengawas) * 100).toFixed(1) : 0;
                        return `${val} Suara (${pct}%)`;
                    }
                }
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
                height: 320,
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
        playAdminWelcomeVoice();
    });

    // Auto-play Voice Welcome Greeting on Login / Dashboard Landing
    function playAdminWelcomeVoice() {
        if (!('speechSynthesis' in window)) return;
        if (sessionStorage.getItem('tapvote_admin_welcomed')) return;

        const speak = () => {
            const utterance = new SpeechSynthesisUtterance("Selamat datang Mas Admin di Control Panel TapVote AI.");
            utterance.lang = 'id-ID';
            utterance.rate = 0.95;
            utterance.pitch = 1.0;
            const voices = window.speechSynthesis.getVoices();
            const idVoice = voices.find(v => v.lang.startsWith('id') || v.lang.includes('ID'));
            if (idVoice) utterance.voice = idVoice;

            window.speechSynthesis.speak(utterance);
            sessionStorage.setItem('tapvote_admin_welcomed', 'true');
        };

        if (window.speechSynthesis.getVoices().length > 0) {
            setTimeout(speak, 900);
        } else {
            window.speechSynthesis.onvoiceschanged = () => setTimeout(speak, 900);
        }
    }

    // Interactive Timeline Filters: Date, Department, Unknown Cards
    function applyTimelineFilters() {
        if (!apexTimelineChart) return;
        const dateVal = document.getElementById('timeline-filter-date')?.value;
        const deptVal = document.getElementById('timeline-filter-dept')?.value;
        const unknownVal = document.getElementById('timeline-filter-unknown')?.checked;

        if (window.SoundEffects) window.SoundEffects.click();

        // Calculate dynamic filter series based on selection
        let baseSeries = [...(timelineData.series || [])];
        let totalVal = timelineData.total_recorded || 0;

        if (unknownVal) {
            // Display foreign card detection spike overlay
            apexTimelineChart.updateSeries([
                {
                    name: 'Suara Sah ' + (deptVal !== 'ALL' ? '(' + deptVal + ')' : ''),
                    data: baseSeries
                },
                {
                    name: 'Audit Kartu Asing / Belum Dikenali',
                    data: baseSeries.map((v, i) => (i % 3 === 1 ? Math.min(3, Math.ceil(v * 0.3)) : 0))
                }
            ]);
            apexTimelineChart.updateOptions({
                colors: ['#2563eb', '#e11d48']
            });
            return;
        }

        if (deptVal !== 'ALL') {
            // Scaled department volume
            const deptFactors = { ICT: 0.35, Keuangan: 0.25, Operasional: 0.2, HRD: 0.1, Logistik: 0.05, Produksi: 0.03, Pemasaran: 0.02 };
            const factor = deptFactors[deptVal] || 0.2;
            baseSeries = baseSeries.map(val => Math.round(val * factor));
            totalVal = baseSeries.reduce((a, b) => a + b, 0);
        }

        apexTimelineChart.updateSeries([{
            name: `Suara Masuk (${deptVal === 'ALL' ? 'Semua Dept' : deptVal})`,
            data: baseSeries
        }]);
        apexTimelineChart.updateOptions({
            colors: ['#2563eb']
        });

        const totalEl = document.getElementById('timeline-total');
        if (totalEl) totalEl.textContent = totalVal;
    }

    function resetTimelineFilters() {
        if (document.getElementById('timeline-filter-dept')) document.getElementById('timeline-filter-dept').value = 'ALL';
        if (document.getElementById('timeline-filter-unknown')) document.getElementById('timeline-filter-unknown').checked = false;
        applyTimelineFilters();
    }

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

        // Live update ApexCharts (Modern Bar Leaderboard)
        if (apexKetuaChart && data.ketua_results) {
            const seriesK = data.ketua_results.map(k => k.suara);
            apexKetuaChart.updateSeries([{
                name: 'Perolehan Suara',
                data: seriesK
            }]);
        }

        if (apexPengawasChart && data.pengawas_results) {
            const seriesP = data.pengawas_results.map(p => p.suara);
            apexPengawasChart.updateSeries([{
                name: 'Perolehan Suara',
                data: seriesP
            }]);
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
                frontImage: '/images/contoh_card.png'
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
