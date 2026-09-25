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
            <!-- Stage Shortcut Button -->
            <a href="{{ route('doorprize.public') }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 border border-amber-500/40 text-xs font-bold transition flex items-center space-x-1.5 shadow-sm" title="Buka Panggung Penonton untuk Layar Proyektor">
                <span>🎪 Panggung Doorprize</span>
                <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>

            <!-- Manual Refresh -->
            <button onclick="fetchLatestData()" class="p-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/20 shadow-sm transition cursor-pointer" title="Refresh Metrik Manual">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
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
    <!-- (Lazy loaded / Trigger on demand via Google Gemini API)  -->
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
                        <span>🔑 API Key</span>
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

            <!-- Gemini API Key Configuration Drawer -->
            <div id="gemini-key-drawer" class="hidden p-5 rounded-2xl bg-indigo-950/80 border border-indigo-700/50 transition">
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
                    <div class="flex gap-2">
                        <input 
                            type="password" 
                            id="gemini-input" 
                            name="gemini_api_key" 
                            value="{{ $geminiKey }}" 
                            placeholder="AIzaSy... dari Google AI Studio"
                            class="flex-1 px-4 py-2.5 rounded-xl border border-indigo-700 bg-slate-900/90 text-xs font-mono text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        >
                        <button type="button" onclick="toggleGeminiVisibility()" class="px-3.5 py-2.5 rounded-xl bg-white/10 border border-white/20 text-white text-xs font-bold hover:bg-white/20 cursor-pointer">
                            <span id="gemini-eye-icon">👁</span>
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition shadow cursor-pointer">
                            Simpan API Key
                        </button>
                    </div>
                    <p class="text-[11px] text-indigo-300/70">
                        Kunci API disimpan secara aman. Saat terhubung, analisis pemilu dianalisis langsung oleh model neural Google Gemini.
                    </p>
                </form>
            </div>

            <!-- STATE A: INITIAL STATE (BELUM DI-HIT) -->
            <div id="ai-state-initial" class="p-8 rounded-2xl bg-white/5 border border-white/10 text-center space-y-3">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-indigo-500/20 border border-indigo-400/30 text-indigo-300 flex items-center justify-center text-2xl">
                    🤖
                </div>
                <h4 class="text-lg font-bold text-white">AI Engine Siap Melakukan Analisis</h4>
                <p class="text-xs sm:text-sm text-indigo-200/70 max-w-xl mx-auto">
                    Klik tombol <strong>"Analisis dengan Gemini AI"</strong> di atas untuk memanggil Google Gemini 2.0 Flash secara real-time guna mengevaluasi persebaran suara, stabilitas margin, dan proyeksi pemenang resmi.
                </p>
            </div>

            <!-- STATE B: LOADING SKELETON STATE (SEDANG MEMANGGIL GEMINI) -->
            <div id="ai-state-loading" class="hidden p-8 rounded-2xl bg-white/5 border border-white/10 text-center space-y-4 animate-pulse">
                <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-indigo-500/20 text-indigo-300 text-xs font-bold">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-400 animate-ping"></span>
                    <span>Menghubungi Google Gemini Generative API...</span>
                </div>
                <p class="text-xs text-indigo-200/80">Sedang mengevaluasi data suara masuk, memeriksa quorum, dan menghasilkan rekomendasi independen...</p>
                <div class="max-w-md mx-auto h-2 bg-indigo-900/60 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full w-2/3 animate-pulse"></div>
                </div>
            </div>

            <!-- STATE C: RESULT DISPLAY (SETELAH DI-HIT) -->
            <div id="ai-state-result" class="hidden space-y-6">
                <!-- Badges bar -->
                <div class="flex flex-wrap items-center justify-between gap-3 text-xs">
                    <div class="flex flex-wrap items-center gap-2">
                        <span id="ai-confidence-badge" class="px-3 py-1 rounded-full font-bold bg-indigo-500/30 text-indigo-300 border border-indigo-400/40">
                            - % Confidence
                        </span>
                        <span id="ai-quorum-badge" class="px-3 py-1 rounded-full font-bold bg-emerald-500/30 text-emerald-300 border border-emerald-400/40">
                            -
                        </span>
                    </div>
                    <div class="text-[11px] text-indigo-300/70 flex items-center space-x-1.5">
                        <span>Waktu Analisis:</span>
                        <strong id="ai-timestamp" class="text-white font-mono">-</strong>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Left: Executive Summary & Bullet Insights -->
                    <div class="lg:col-span-8 space-y-4">
                        <div class="p-5 rounded-2xl bg-white/10 border border-white/15">
                            <span class="text-[11px] uppercase tracking-wider font-extrabold text-indigo-300 block mb-2">Executive Summary:</span>
                            <p id="ai-summary-text" class="text-sm sm:text-base leading-relaxed text-indigo-50 font-medium">
                                -
                            </p>
                        </div>

                        <div class="space-y-2">
                            <span class="text-[11px] uppercase tracking-wider font-extrabold text-indigo-300 block">Poin-Poin Insight Strategis:</span>
                            <div id="ai-insights-container" class="space-y-2">
                                <!-- Dynamic Insights -->
                            </div>
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
    <!-- REAL-TIME CANDIDATE CHARTS (KETUA & PENGAWAS)            -->
    <!-- (Responsive for Mobile & iPad Mini: 1 col on mobile,     -->
    <!-- 2 col on tablet/desktop)                                 -->
    <!-- ======================================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">

        <!-- Card Section: Calon Ketua Koperasi -->
        <div class="p-5 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-200">
                    <div class="flex items-center space-x-3">
                        <span class="w-8 h-8 rounded-xl bg-blue-600 text-white font-black text-xs flex items-center justify-center shadow-xs">1</span>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Perolehan Suara: Calon Ketua</h3>
                            <p class="text-xs text-slate-500">Live tally surat suara masuk</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                        {{ count($ketuaResults) }} Kandidat
                    </span>
                </div>

                <!-- Doughnut Chart Container -->
                <div class="relative w-44 h-44 sm:w-56 sm:h-56 mx-auto mb-6">
                    <canvas id="adminChartKetua"></canvas>
                </div>

                <!-- Progress List -->
                <div class="space-y-3" id="admin-ketua-list">
                    @foreach($ketuaResults as $k)
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 flex items-center justify-between gap-3">
                            <div class="flex items-center space-x-3 min-w-0">
                                <span class="w-8 h-8 rounded-xl bg-blue-600 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-xs">
                                    {{ $k['nomor_urut'] }}
                                </span>
                                <div class="min-w-0">
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate">{{ $k['nama'] }}</h4>
                                    <span class="text-[11px] text-blue-700 font-mono font-bold">NIK: {{ $k['nik'] }}</span>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-sm font-extrabold text-blue-700 font-mono">{{ $k['suara'] }} Suara</span>
                                <span class="text-[11px] text-slate-500 block font-bold">({{ $k['persen'] }}%)</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Card Section: Calon Pengawas Koperasi -->
        <div class="p-5 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-200">
                    <div class="flex items-center space-x-3">
                        <span class="w-8 h-8 rounded-xl bg-emerald-600 text-white font-black text-xs flex items-center justify-center shadow-xs">2</span>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Perolehan Suara: Calon Pengawas</h3>
                            <p class="text-xs text-slate-500">Live tally surat suara masuk</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        {{ count($pengawasResults) }} Kandidat
                    </span>
                </div>

                <!-- Doughnut Chart Container -->
                <div class="relative w-44 h-44 sm:w-56 sm:h-56 mx-auto mb-6">
                    <canvas id="adminChartPengawas"></canvas>
                </div>

                <!-- Progress List -->
                <div class="space-y-3" id="admin-pengawas-list">
                    @foreach($pengawasResults as $p)
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 flex items-center justify-between gap-3">
                            <div class="flex items-center space-x-3 min-w-0">
                                <span class="w-8 h-8 rounded-xl bg-emerald-600 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-xs">
                                    {{ $p['nomor_urut'] }}
                                </span>
                                <div class="min-w-0">
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate">{{ $p['nama'] }}</h4>
                                    <span class="text-[11px] text-emerald-700 font-mono font-bold">NIK: {{ $p['nik'] }}</span>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-sm font-extrabold text-emerald-700 font-mono">{{ $p['suara'] }} Suara</span>
                                <span class="text-[11px] text-slate-500 block font-bold">({{ $p['persen'] }}%)</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <!-- Live Recent Activity Logs & Votes (Clean Light Theme) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
        <!-- Suara Baru Masuk -->
        <div class="p-5 sm:p-6 rounded-3xl bg-white border border-slate-200 shadow-sm">
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
        <div class="p-5 sm:p-6 rounded-3xl bg-white border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-200">
                <h3 class="text-sm font-bold text-slate-900">Audit Trail Keamanan</h3>
                <a href="{{ route('admin.logs.index') }}" class="text-xs font-bold text-blue-700 hover:underline">Log Lengkap →</a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($recentLogs as $log)
                    <div class="py-3 flex items-center justify-between text-xs">
                        <div class="min-w-0 pr-3">
                            <span class="font-bold text-slate-800 block truncate">{{ $log->description }}</span>
                            <span class="text-[11px] text-slate-400 font-mono">{{ $log->created_at->format('H:i:s d/m/Y') }} • IP: {{ $log->ip_address }}</span>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold shrink-0 {{ str_contains($log->action, 'FAIL') || str_contains($log->action, 'REJECT') ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-700' }}">
                            {{ $log->action }}
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
    const palette = ['#2563eb', '#059669', '#d97706', '#7c3aed', '#db2777', '#0891b2', '#ea580c'];

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Chart === 'undefined') return;

        // Chart Ketua
        const ctxK = document.getElementById('adminChartKetua')?.getContext('2d');
        if (ctxK) {
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
                    cutout: '68%'
                }
            });
        }

        // Chart Pengawas
        const ctxP = document.getElementById('adminChartPengawas')?.getContext('2d');
        if (ctxP) {
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
                    cutout: '68%'
                }
            });
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

    // Trigger AI Analysis on demand (REAL Gemini API call)
    function triggerAiAnalysis() {
        if (window.SoundEffects) window.SoundEffects.click();

        const btn = document.getElementById('btn-trigger-ai');
        const btnText = document.getElementById('ai-btn-text');
        const btnIcon = document.getElementById('ai-btn-icon');
        const stateInitial = document.getElementById('ai-state-initial');
        const stateLoading = document.getElementById('ai-state-loading');
        const stateResult = document.getElementById('ai-state-result');

        // Switch to loading
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

        if (adminChartKetua && data.ketua_results) {
            adminChartKetua.data.datasets[0].data = data.ketua_results.map(k => k.suara);
            adminChartKetua.update('none');
        }

        if (adminChartPengawas && data.pengawas_results) {
            adminChartPengawas.data.datasets[0].data = data.pengawas_results.map(p => p.suara);
            adminChartPengawas.update('none');
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
</script>
@endpush
@endsection
