@extends('layouts.app')

@section('title', __('Live Count Cooperative Election'))

@section('content')
<div class="flex-1 flex flex-col p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full">

    <!-- Top Navigation & Live Header -->
    <header class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 mb-8 border-b border-slate-200">
        <div class="flex items-center space-x-3.5">
            <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-sm shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">{{ __('Live Count Cooperative Election') }}</h1>
                <p class="text-xs text-slate-500 font-medium mt-1">
                    {{ __('Pembaruan Terakhir') }} • <span id="last-updated-text" class="font-bold text-slate-800">{{ $metrics['last_updated'] ?? (now()->timezone('Asia/Jakarta')->format('H:i:s') . ' WIB') }}</span>
                </p>
            </div>
        </div>

        <!-- Quick Access Buttons & Language Switcher -->
        <div class="flex items-center justify-between sm:justify-end gap-2.5 sm:gap-3 w-full sm:w-auto">
            <!-- Language Switcher Pill -->
            <div class="inline-flex rounded-xl border border-slate-200 bg-white p-1 text-xs font-bold shadow-2xs">
                <a href="{{ route('lang.switch', 'en') }}" class="px-2.5 py-1 rounded-md transition {{ app()->getLocale() === 'en' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-blue-600' }}">EN</a>
                <a href="{{ route('lang.switch', 'id') }}" class="px-2.5 py-1 rounded-md transition {{ app()->getLocale() === 'id' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-blue-600' }}">ID</a>
            </div>

            <a href="{{ route('voter.tap') }}" class="flex-1 sm:flex-none justify-center px-6 py-3 sm:px-7 sm:py-3.5 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 hover:from-blue-700 hover:to-indigo-800 text-white font-black text-sm sm:text-base shadow-lg shadow-blue-600/30 hover:shadow-xl hover:shadow-blue-600/40 transition-all transform hover:-translate-y-0.5 flex items-center space-x-3 cursor-pointer">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                    <rect x="3" y="5" width="18" height="14" rx="3" stroke-width="2.2" />
                    <path stroke-linecap="round" d="M7 15h3M7 11h2" stroke-width="2" />
                    <path stroke-linecap="round" d="M16 9a3 3 0 0 1 0 6m2.5-8a6 6 0 0 1 0 10" stroke-width="2.2" />
                </svg>
                <span class="tracking-wide">{{ __('Tap RFID Card to Vote') }}</span>
            </a>
        </div>
    </header>

    <!-- Executive Public Election Overview Banner (Boomer-friendly, Large Typography) -->
    <div class="p-6 sm:p-8 rounded-3xl bg-white border border-slate-200 shadow-sm mb-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8 items-stretch">
            
            <!-- Turnout Percentage & Progress -->
            <div class="flex flex-col justify-between">
                <span class="text-sm sm:text-base font-extrabold uppercase tracking-wider text-slate-600 block mb-2">{{ __('Voter Participation Rate') }}</span>
                <div class="flex items-baseline space-x-2 my-auto">
                    <span id="stat-turnout-pct" class="text-4xl sm:text-5xl font-black text-blue-600 font-mono tracking-tight">{{ $metrics['turnout_pct'] }}%</span>
                </div>
                <div class="mt-2">
                    <span class="text-sm sm:text-base text-slate-700 font-bold block mb-2">
                        <span id="stat-total-voted" class="text-blue-700">{{ $metrics['total_voted'] }}</span> / <span id="stat-total-voters">{{ $metrics['total_voters'] }}</span> {{ __('Votes') }}
                    </span>
                    <div class="w-full bg-slate-100 rounded-full h-3.5 overflow-hidden">
                        <div id="stat-turnout-bar" class="bg-gradient-to-r from-blue-600 to-indigo-600 h-3.5 rounded-full transition-all duration-700 ease-out" style="width: {{ $metrics['turnout_pct'] }}%;"></div>
                    </div>
                </div>
            </div>

            <!-- Election Status & Security -->
            <div class="border-t sm:border-t-0 sm:border-l border-slate-200 pt-5 sm:pt-0 sm:pl-6 lg:pl-8 flex flex-col justify-between">
                <span class="text-sm sm:text-base font-extrabold uppercase tracking-wider text-slate-600 block mb-2">{{ __('Election Status') }}</span>
                <div class="flex items-center space-x-3 my-auto py-2">
                    <span class="w-4 h-4 rounded-full bg-emerald-500 shadow-md shadow-emerald-500/50 animate-pulse shrink-0"></span>
                    <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ __('Active & Verified') }}</span>
                </div>
                <p class="text-xs sm:text-sm text-slate-600 font-bold mt-2">
                    ✓ {{ __('Bilik Suara Siap Digunakan • Aman & Terverifikasi') }}
                </p>
            </div>

            <!-- Frontrunners Spotlight (Redesigned & Premium Leaderboard) -->
            <div class="border-t lg:border-t-0 lg:border-l border-slate-200 pt-5 lg:pt-0 lg:pl-6 lg:pl-8 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm sm:text-base font-extrabold uppercase tracking-wider text-slate-700 flex items-center space-x-1.5">
                        <span>🏆</span>
                        <span>{{ __('Leading Frontrunners') }}</span>
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-amber-100 text-amber-900 border border-amber-300">Live Top</span>
                </div>
                <div class="space-y-2.5 my-auto">
                    <!-- Chairman Leader Capsule -->
                    <div class="p-2.5 sm:p-3 rounded-2xl bg-rose-50/90 border border-rose-200/80 flex items-center justify-between shadow-2xs">
                        <div class="flex items-center space-x-2.5 min-w-0 pr-2">
                            <span class="w-6 h-6 rounded-lg bg-red-600 text-white font-black text-xs flex items-center justify-center shrink-0">1</span>
                            <div class="min-w-0">
                                <span class="text-[10px] font-black uppercase text-red-700 block tracking-wider leading-none mb-0.5">{{ __('Chairman') }}</span>
                                <strong id="leader-ketua-text" class="text-sm sm:text-base font-black text-slate-900 truncate block">{{ $metrics['leader_ketua'] ?: '(' . __('No Votes Yet') . ')' }}</strong>
                            </div>
                        </div>
                        <span class="px-2 py-1 rounded-lg text-[10px] font-extrabold uppercase bg-red-600 text-white shadow-2xs shrink-0">Unggul</span>
                    </div>

                    <!-- Supervisor Leader Capsule -->
                    <div class="p-2.5 sm:p-3 rounded-2xl bg-emerald-50/90 border border-emerald-200/80 flex items-center justify-between shadow-2xs">
                        <div class="flex items-center space-x-2.5 min-w-0 pr-2">
                            <span class="w-6 h-6 rounded-lg bg-emerald-600 text-white font-black text-xs flex items-center justify-center shrink-0">2</span>
                            <div class="min-w-0">
                                <span class="text-[10px] font-black uppercase text-emerald-800 block tracking-wider leading-none mb-0.5">{{ __('Supervisor') }}</span>
                                <strong id="leader-pengawas-text" class="text-sm sm:text-base font-black text-slate-900 truncate block">{{ $metrics['leader_pengawas'] ?: '(' . __('No Votes Yet') . ')' }}</strong>
                            </div>
                        </div>
                        <span class="px-2 py-1 rounded-lg text-[10px] font-extrabold uppercase bg-emerald-600 text-white shadow-2xs shrink-0">Unggul</span>
                    </div>
                </div>
            </div>

            <!-- Voting Deadline Countdown -->
            <div id="deadline-container" class="border-t lg:border-t-0 lg:border-l border-slate-200 pt-5 lg:pt-0 lg:pl-6 lg:pl-8 flex flex-col justify-between {{ empty($metrics['voting_deadline']) ? 'hidden' : '' }}">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm sm:text-base font-extrabold uppercase tracking-wider text-rose-700 flex items-center space-x-1.5">
                        <span>⏰</span>
                        <span>Batas Waktu Vote</span>
                    </span>
                    <span id="deadline-status-pill" class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-rose-100 text-rose-800 border border-rose-300">Countdown</span>
                </div>
                <div class="flex items-center space-x-1.5 font-mono text-xl sm:text-2xl font-black text-slate-900 my-auto py-1" id="deadline-countdown-timer">
                    <span id="deadline-days" class="bg-slate-100 border border-slate-200 px-2 py-1 rounded-xl">00d</span>
                    <span>:</span>
                    <span id="deadline-hours" class="bg-slate-100 border border-slate-200 px-2 py-1 rounded-xl">00h</span>
                    <span>:</span>
                    <span id="deadline-mins" class="bg-slate-100 border border-slate-200 px-2 py-1 rounded-xl">00m</span>
                    <span>:</span>
                    <span id="deadline-secs" class="bg-slate-100 border border-slate-200 px-2 py-1 rounded-xl text-rose-600">00s</span>
                </div>
                <p id="deadline-info-text" class="text-xs sm:text-sm text-slate-600 mt-2 font-bold truncate">
                    Batas: <strong id="deadline-formatted-text" class="text-slate-900 font-extrabold">{{ $metrics['deadline_formatted'] ?? '-' }}</strong>
                </p>
            </div>

        </div>
    </div>

    <!-- ======================================================== -->
    <!-- ADVANCED COMPARATIVE BAR CHART SECTION                   -->
    <!-- ======================================================== -->
    <div class="mb-10 p-6 sm:p-8 rounded-3xl bg-white/95 backdrop-blur-md border border-slate-200 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-5 mb-5 border-b border-slate-200 gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">{{ __('Comparative Bar Chart of All Candidates') }}</h2>
                <p class="text-sm sm:text-base text-slate-600 font-semibold mt-1">{{ __('Real-time vote count distribution and percentage for Chairman and Supervisory Board') }}</p>
            </div>

            <!-- Filter Tabs for Chart Comparison (Larger Text, Boomer-Friendly) -->
            <div class="inline-flex overflow-x-auto max-w-full rounded-2xl bg-slate-100 p-1.5 text-sm sm:text-base font-extrabold self-start sm:self-auto shadow-inner">
                <button type="button" onclick="switchChartMode('all')" id="btn-chart-all" class="px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl bg-white text-slate-900 shadow-sm transition cursor-pointer whitespace-nowrap font-black">{{ __('All Candidates') }}</button>
                <button type="button" onclick="switchChartMode('ketua')" id="btn-chart-ketua" class="px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl text-slate-600 hover:text-slate-900 transition cursor-pointer whitespace-nowrap font-extrabold">{{ __('Chairman Candidates') }}</button>
                <button type="button" onclick="switchChartMode('pengawas')" id="btn-chart-pengawas" class="px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl text-slate-600 hover:text-slate-900 transition cursor-pointer whitespace-nowrap font-extrabold">{{ __('Supervisor Candidates') }}</button>
            </div>
        </div>

        <!-- Comparative Bar Chart Container (Enlarged, Minimalist Glass, Generous Height) -->
        <div class="relative w-full max-w-6xl mx-auto h-[420px] sm:h-[480px] md:h-[520px] px-2 sm:px-4 pt-3 pb-6">
            <canvas id="comparisonChart"></canvas>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- ELECTION CATEGORIES & PROCEDURAL GUIDE                   -->
    <!-- (Full Width 1-Column, Boomer-Friendly, Simple, Bilingual)-->
    <!-- ======================================================== -->
    <div class="mb-10 p-6 sm:p-8 rounded-3xl bg-slate-50 border border-slate-200">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 mb-6 border-b border-slate-200">
            <div class="flex items-center space-x-3.5">
                <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black shadow-sm shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">{{ __('Voting Guide & Leadership Roles') }}</h3>
                    <p class="text-sm sm:text-base text-slate-600 font-semibold mt-0.5">{{ __('Simple voting instructions and summary of cooperative leadership roles') }}</p>
                </div>
            </div>
            <span class="inline-flex items-center space-x-2 px-4 py-2 rounded-2xl text-xs sm:text-sm font-extrabold bg-white text-slate-800 border border-slate-200 shadow-2xs self-start md:self-auto">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>{{ __('Direct, Free & Confidential') }}</span>
            </span>
        </div>

        <!-- Single Column Full Width Stacked Cards -->
        <div class="grid grid-cols-1 gap-4 sm:gap-5">
            <!-- Row 1: How to Vote (Tata Cara Memilih - 3 Langkah Mudah) -->
            <div class="p-5 sm:p-7 rounded-2xl bg-white border border-blue-200/80 shadow-2xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 mb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-2.5">
                        <span class="px-3 py-1 rounded-xl text-xs font-black uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200">
                            {{ __('Procedures') }}
                        </span>
                        <h4 class="text-base sm:text-xl font-black text-slate-900">{{ __('How to Vote in 3 Easy Steps') }}</h4>
                    </div>
                    <span class="text-xs sm:text-sm font-bold text-blue-700">1 {{ __('Anggota') }} = 1 {{ __('Suara Sah') }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-start space-x-3.5">
                        <div class="w-8 h-8 rounded-xl bg-blue-600 text-white font-black text-sm flex items-center justify-center shrink-0">1</div>
                        <div>
                            <strong class="text-sm sm:text-base font-black text-slate-900 block mb-1">{{ __('1. Tap Member RFID Card') }}</strong>
                            <p class="text-xs sm:text-sm text-slate-600 font-medium leading-relaxed">{{ __('Scan your member card at the voting booth terminal to activate ballot.') }}</p>
                        </div>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-start space-x-3.5">
                        <div class="w-8 h-8 rounded-xl bg-blue-600 text-white font-black text-sm flex items-center justify-center shrink-0">2</div>
                        <div>
                            <strong class="text-sm sm:text-base font-black text-slate-900 block mb-1">{{ __('2. Select Candidates') }}</strong>
                            <p class="text-xs sm:text-sm text-slate-600 font-medium leading-relaxed">{{ __('Touch the photo of 1 Chairman candidate and 1 Supervisory candidate.') }}</p>
                        </div>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-start space-x-3.5">
                        <div class="w-8 h-8 rounded-xl bg-blue-600 text-white font-black text-sm flex items-center justify-center shrink-0">3</div>
                        <div>
                            <strong class="text-sm sm:text-base font-black text-slate-900 block mb-1">{{ __('3. Submit Vote') }}</strong>
                            <p class="text-xs sm:text-sm text-slate-600 font-medium leading-relaxed">{{ __('Review your selections and tap the Submit Vote button to complete.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 2: Category 1 - Ketua Koperasi (Chairman) -->
            <div class="p-5 sm:p-7 rounded-2xl bg-white border border-rose-200/80 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center space-x-2.5 mb-2">
                        <span class="px-3 py-1 rounded-xl text-xs font-black uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200">
                            {{ __('Category 1') }}
                        </span>
                        <h4 class="text-base sm:text-xl font-black text-slate-900">{{ __('Category 1: Cooperative Chairman') }}</h4>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-black uppercase bg-red-100 text-red-800 border border-red-200">
                            {{ __('1 Vote Required') }}
                        </span>
                    </div>
                    <p class="text-sm sm:text-base text-slate-700 font-medium leading-relaxed max-w-4xl">
                        {{ __('Chief executive leader responsible for setting strategic vision, managing business operations, and advancing the welfare of all cooperative members.') }}
                    </p>
                </div>
                <div class="flex items-center md:flex-col md:items-end justify-between border-t md:border-t-0 md:border-l border-slate-100 pt-3 md:pt-0 md:pl-6 shrink-0">
                    <span class="text-base sm:text-lg font-black text-red-700 font-mono">{{ count($ketuaResults) }} {{ __('Registered Candidates') }}</span>
                    <span class="text-xs text-slate-500 font-bold uppercase">{{ __('Wajib Memilih 1 Calon') }}</span>
                </div>
            </div>

            <!-- Row 3: Category 2 - Badan Pengawas (Supervisory Board) -->
            <div class="p-5 sm:p-7 rounded-2xl bg-white border border-emerald-200/80 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center space-x-2.5 mb-2">
                        <span class="px-3 py-1 rounded-xl text-xs font-black uppercase tracking-wider bg-emerald-50 text-emerald-800 border border-emerald-200">
                            {{ __('Category 2') }}
                        </span>
                        <h4 class="text-base sm:text-xl font-black text-slate-900">{{ __('Category 2: Supervisory Board') }}</h4>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-black uppercase bg-emerald-100 text-emerald-800 border border-emerald-200">
                            {{ __('1 Vote Required') }}
                        </span>
                    </div>
                    <p class="text-sm sm:text-base text-slate-700 font-medium leading-relaxed max-w-4xl">
                        {{ __('Independent oversight committee responsible for financial audits, regulatory compliance, and organizational governance.') }}
                    </p>
                </div>
                <div class="flex items-center md:flex-col md:items-end justify-between border-t md:border-t-0 md:border-l border-slate-100 pt-3 md:pt-0 md:pl-6 shrink-0">
                    <span class="text-base sm:text-lg font-black text-emerald-800 font-mono">{{ count($pengawasResults) }} {{ __('Registered Candidates') }}</span>
                    <span class="text-xs text-slate-500 font-bold uppercase">{{ __('Wajib Memilih 1 Calon') }}</span>
                </div>
            </div>
        </div>
    </div>



    <!-- ======================================================== -->
    <!-- KANDIDAT KETUA KOPERASI SHOWCASE (PALET MERAH)           -->
    <!-- ======================================================== -->
    <section class="mb-10">
        <div class="flex items-center justify-between pb-3 mb-5 border-b border-slate-200">
            <div class="flex items-center space-x-3">
                <span class="w-8 h-8 rounded-lg bg-red-600 text-white font-extrabold flex items-center justify-center text-sm shadow-2xs">1</span>
                <h3 class="text-lg font-bold text-slate-900">{{ __('Chairman Election Comparison') }}</h3>
            </div>
            <span class="text-xs font-bold text-red-700 bg-red-50 px-2.5 py-1 rounded-full border border-red-200">
                Total: <strong id="total-ketua-votes">{{ $metrics['total_suara_ketua'] }}</strong> {{ __('Votes') }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-7" id="ketua-cards-grid">
            @foreach($ketuaResults as $ketua)
                <div class="rounded-3xl bg-white border-2 border-slate-200 hover:border-red-400 overflow-hidden shadow-2xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <!-- Foto Card: Tinggi Pas 300px Sesuai Permintaan -->
                        <div class="relative w-full h-[300px] max-h-[300px] bg-slate-900 overflow-hidden">
                            <img 
                                src="{{ $ketua['foto'] }}" 
                                alt="{{ $ketua['nama'] }}" 
                                class="w-full h-full object-cover object-top hover:scale-105 transition-transform duration-500 ease-out"
                            >
                            <!-- Gradient Tipis untuk Kontras -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-black/20 pointer-events-none"></div>

                            <!-- Badge Nomor Urut Absolute di Kiri Atas: Background Putih, Teks Hitam Kontras -->
                            <div class="absolute top-3.5 left-3.5 z-10">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-white text-slate-950 border-2 border-slate-300 shadow-xl flex items-center justify-center text-xl sm:text-2xl font-black ring-4 ring-black/15">
                                    {{ $ketua['nomor_urut'] }}
                                </div>
                            </div>

                            <!-- Leading Star Badge di Kanan Atas -->
                            <div class="absolute top-3.5 right-3.5 z-10">
                                <span id="ketua-leader-badge-{{ $ketua['nik'] }}" class="{{ $ketua['is_leader'] && $ketua['suara'] > 0 ? '' : 'hidden' }} px-3 py-1 rounded-full text-xs font-black bg-amber-400 text-amber-950 border border-amber-300 shadow-md">
                                    ⭐ {{ __('Memimpin') }}
                                </span>
                            </div>
                        </div>

                        <!-- Identitas Calon: Font Elegan, Besar & Kontras -->
                        <div class="p-5 pb-3">
                            <h3 class="text-xl sm:text-2xl font-black text-slate-950 tracking-tight leading-snug">{{ $ketua['nama'] }}</h3>
                            <div class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-lg bg-red-50 border border-red-200 text-xs sm:text-sm font-mono font-bold text-red-800 mt-1.5">
                                <span>NIK:</span>
                                <span>{{ $ketua['nik'] }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 pt-0">
                        <!-- Votes & Percentage Bar -->
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 mb-3.5">
                            <div class="flex items-baseline justify-between text-xs font-bold mb-1.5">
                                <span id="ketua-vote-count-{{ $ketua['nik'] }}" class="text-slate-800 font-extrabold text-sm">{{ $ketua['suara'] }} {{ __('Suara') }}</span>
                                <span id="ketua-pct-val-{{ $ketua['nik'] }}" class="text-red-700 font-black text-base">{{ $ketua['persen'] }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                                <div id="ketua-bar-fill-{{ $ketua['nik'] }}" class="bg-red-600 h-2.5 rounded-full transition-all duration-700 ease-out" style="width: {{ $ketua['persen'] }}%;"></div>
                            </div>
                        </div>

                        <!-- Button View Details Modal (BG Gradient Red) -->
                        <button 
                            type="button" 
                            onclick="openCandidateModal('Ketua Koperasi', '{{ addslashes($ketua['nama']) }}', '{{ $ketua['nomor_urut'] }}', `{{ addslashes($ketua['visi']) }}`, `{{ addslashes($ketua['misi']) }}`, `{{ addslashes($ketua['deskripsi']) }}`, '{{ $ketua['foto'] }}', '{{ $ketua['suara'] }}', '{{ $ketua['persen'] }}%')"
                            class="w-full py-3 px-4 rounded-2xl bg-gradient-to-r from-red-600 via-rose-600 to-red-700 hover:from-red-700 hover:to-rose-800 text-white font-extrabold text-xs sm:text-sm shadow-md hover:shadow-lg transition-all flex items-center justify-center space-x-2 cursor-pointer"
                        >
                            <span>{{ __('Lihat Profil & Visi Misi') }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-7" id="pengawas-cards-grid">
            @foreach($pengawasResults as $pengawas)
                <div class="rounded-3xl bg-white border-2 border-slate-200 hover:border-emerald-400 overflow-hidden shadow-2xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <!-- Foto Card: Tinggi Pas 300px Sesuai Permintaan -->
                        <div class="relative w-full h-[300px] max-h-[300px] bg-slate-900 overflow-hidden">
                            <img 
                                src="{{ $pengawas['foto'] }}" 
                                alt="{{ $pengawas['nama'] }}" 
                                class="w-full h-full object-cover object-top hover:scale-105 transition-transform duration-500 ease-out"
                            >
                            <!-- Gradient Tipis untuk Kontras -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-black/20 pointer-events-none"></div>

                            <!-- Badge Nomor Urut Absolute di Kiri Atas: Background Putih, Teks Hitam Kontras -->
                            <div class="absolute top-3.5 left-3.5 z-10">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-white text-slate-950 border-2 border-slate-300 shadow-xl flex items-center justify-center text-xl sm:text-2xl font-black ring-4 ring-black/15">
                                    {{ $pengawas['nomor_urut'] }}
                                </div>
                            </div>

                            <!-- Leading Star Badge di Kanan Atas -->
                            <div class="absolute top-3.5 right-3.5 z-10">
                                <span id="pengawas-leader-badge-{{ $pengawas['nik'] }}" class="{{ $pengawas['is_leader'] && $pengawas['suara'] > 0 ? '' : 'hidden' }} px-3 py-1 rounded-full text-xs font-black bg-amber-400 text-amber-950 border border-amber-300 shadow-md">
                                    ⭐ {{ __('Memimpin') }}
                                </span>
                            </div>
                        </div>

                        <!-- Identitas Calon: Font Elegan, Besar & Kontras -->
                        <div class="p-5 pb-3">
                            <h3 class="text-xl sm:text-2xl font-black text-slate-950 tracking-tight leading-snug">{{ $pengawas['nama'] }}</h3>
                            <div class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 border border-emerald-200 text-xs sm:text-sm font-mono font-bold text-emerald-800 mt-1.5">
                                <span>NIK:</span>
                                <span>{{ $pengawas['nik'] }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 pt-0">
                        <!-- Votes & Percentage Bar -->
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 mb-3.5">
                            <div class="flex items-baseline justify-between text-xs font-bold mb-1.5">
                                <span id="pengawas-vote-count-{{ $pengawas['nik'] }}" class="text-slate-800 font-extrabold text-sm">{{ $pengawas['suara'] }} {{ __('Suara') }}</span>
                                <span id="pengawas-pct-val-{{ $pengawas['nik'] }}" class="text-emerald-700 font-black text-base">{{ $pengawas['persen'] }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                                <div id="pengawas-bar-fill-{{ $pengawas['nik'] }}" class="bg-emerald-600 h-2.5 rounded-full transition-all duration-700 ease-out" style="width: {{ $pengawas['persen'] }}%;"></div>
                            </div>
                        </div>

                        <!-- Button View Details Modal (BG Gradient Emerald) -->
                        <button 
                            type="button" 
                            onclick="openCandidateModal('Pengawas Koperasi', '{{ addslashes($pengawas['nama']) }}', '{{ $pengawas['nomor_urut'] }}', `{{ addslashes($pengawas['visi']) }}`, `{{ addslashes($pengawas['misi']) }}`, `{{ addslashes($pengawas['deskripsi']) }}`, '{{ $pengawas['foto'] }}', '{{ $pengawas['suara'] }}', '{{ $pengawas['persen'] }}%')"
                            class="w-full py-3 px-4 rounded-2xl bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 hover:from-emerald-700 hover:to-teal-800 text-white font-extrabold text-xs sm:text-sm shadow-md hover:shadow-lg transition-all flex items-center justify-center space-x-2 cursor-pointer"
                        >
                            <span>{{ __('Lihat Profil & Visi Misi') }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

</div>

<!-- ======================================================== -->
<!-- MODAL DETAIL LENGKAP KANDIDAT & VISI MISI (BOOMER FRIENDLY) -->
<!-- ======================================================== -->
<div id="candidate-detail-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="boomer-modal-dialog bg-white border-2 border-slate-200 rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl relative max-h-[88vh] overflow-y-auto">
        <button onclick="closeCandidateModal()" class="absolute top-4 right-4 text-slate-500 hover:text-slate-800 p-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-lg font-black transition cursor-pointer">✕</button>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-5 border-b-2 border-slate-100">
            <div class="flex items-center space-x-4">
                <img id="detail-modal-foto" src="" alt="Foto" class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl object-cover object-top border-2 border-slate-300 shadow-md shrink-0">
                <div>
                    <div class="flex items-center space-x-2">
                        <span id="detail-modal-badge" class="px-2.5 py-0.5 rounded-md text-xs font-black bg-blue-100 text-blue-800 uppercase">Calon Ketua</span>
                        <span id="detail-modal-no" class="text-sm font-extrabold text-slate-600">No. 01</span>
                    </div>
                    <h3 id="detail-modal-nama" class="text-xl sm:text-2xl font-black text-slate-900 mt-1">Nama Kandidat</h3>
                    <span id="detail-modal-tally" class="text-sm font-bold text-blue-700 block mt-0.5">0 Suara (0%)</span>
                </div>
            </div>

            <!-- Text-to-Speech (TTS) Voice Button -->
            <button id="btn-modal-tts" onclick="toggleModalTTS()" type="button" class="inline-flex items-center justify-center space-x-2 px-4 py-2.5 rounded-2xl bg-blue-50 hover:bg-blue-100 text-blue-800 font-extrabold text-xs sm:text-sm border-2 border-blue-200 shadow-xs transition cursor-pointer self-start sm:self-auto">
                <svg id="tts-icon-speaker" class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" />
                </svg>
                <span id="tts-btn-label">Dengarkan Suara (Audio)</span>
            </button>
        </div>

        <div class="space-y-5">
            <!-- Profil Singkat / Track Record -->
            <div>
                <h4 class="text-xs sm:text-sm uppercase font-black tracking-wider text-slate-600 mb-2">Latar Belakang / Profil Singkat:</h4>
                <p id="detail-modal-deskripsi" class="p-4 sm:p-5 rounded-2xl bg-slate-50 border-2 border-slate-200 text-slate-800 text-base sm:text-lg leading-relaxed font-semibold"></p>
            </div>

            <!-- Visi -->
            <div>
                <h4 class="text-xs sm:text-sm uppercase font-black tracking-wider text-slate-600 mb-2">{{ __('Vision') }}:</h4>
                <div id="detail-modal-visi" class="p-4 sm:p-5 rounded-2xl bg-slate-50 border-2 border-slate-200 text-slate-800 text-base sm:text-lg leading-relaxed whitespace-pre-line font-semibold"></div>
            </div>

            <!-- Misi -->
            <div>
                <h4 class="text-xs sm:text-sm uppercase font-black tracking-wider text-slate-600 mb-2">{{ __('Mission') }}:</h4>
                <div id="detail-modal-misi" class="p-4 sm:p-5 rounded-2xl bg-slate-50 border-2 border-slate-200 text-slate-800 text-base sm:text-lg leading-relaxed whitespace-pre-line font-semibold"></div>
            </div>
        </div>

        <div class="mt-8 pt-4 border-t-2 border-slate-100 flex justify-end">
            <button onclick="closeCandidateModal()" class="px-6 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-900 font-extrabold text-sm sm:text-base transition cursor-pointer">
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

    // Preload candidate avatars for instant drawing
    const preloadedCandidateImages = {};
    function getCandidateAvatarImg(url) {
        if (!preloadedCandidateImages[url]) {
            const img = new Image();
            img.crossOrigin = 'anonymous';
            img.src = url;
            img.onload = () => {
                if (comparisonChart) comparisonChart.draw();
            };
            preloadedCandidateImages[url] = img;
        }
        return preloadedCandidateImages[url];
    }

    document.addEventListener('DOMContentLoaded', function() {
        initComparisonChart();
        initLiveSSE();
    });

    // Custom Chart.js Plugin: Candidate Avatar Portraits on X-Axis
    const candidateAvatarPlugin = {
        id: 'candidateAvatars',
        afterDraw(chart) {
            const { ctx, scales: { x, y } } = chart;
            let candidates = [];
            if (currentChartMode === 'ketua') {
                candidates = rawKetuaData.map(k => ({ ...k, type: 'ketua' }));
            } else if (currentChartMode === 'pengawas') {
                candidates = rawPengawasData.map(p => ({ ...p, type: 'pengawas' }));
            } else {
                candidates = [
                    ...rawKetuaData.map(k => ({ ...k, type: 'ketua' })),
                    ...rawPengawasData.map(p => ({ ...p, type: 'pengawas' }))
                ];
            }

            candidates.forEach((cand, idx) => {
                const xPos = x.getPixelForTick(idx);
                if (isNaN(xPos)) return;
                const yPos = y.bottom + 10;
                const size = 32;

                const defaultAvatar = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(cand.nama) + '&background=' + (cand.type === 'ketua' ? 'dc2626' : '059669') + '&color=ffffff&size=100';
                const avatarSrc = cand.foto || defaultAvatar;
                const img = getCandidateAvatarImg(avatarSrc);

                // Circular photo
                ctx.save();
                ctx.beginPath();
                ctx.arc(xPos, yPos + size / 2, size / 2, 0, Math.PI * 2);
                ctx.closePath();
                ctx.clip();
                if (img.complete && img.naturalWidth !== 0) {
                    ctx.drawImage(img, xPos - size / 2, yPos, size, size);
                } else {
                    ctx.fillStyle = cand.type === 'ketua' ? '#dc2626' : '#059669';
                    ctx.fill();
                }
                ctx.restore();

                // Ring border around photo
                ctx.save();
                ctx.beginPath();
                ctx.arc(xPos, yPos + size / 2, size / 2, 0, Math.PI * 2);
                ctx.lineWidth = 2.5;
                ctx.strokeStyle = cand.type === 'ketua' ? '#dc2626' : '#059669';
                ctx.stroke();
                ctx.restore();

                // Mini number badge pill
                ctx.save();
                ctx.beginPath();
                ctx.arc(xPos + size / 2 - 2, yPos + 4, 7, 0, Math.PI * 2);
                ctx.fillStyle = '#ffffff';
                ctx.fill();
                ctx.lineWidth = 1.5;
                ctx.strokeStyle = cand.type === 'ketua' ? '#dc2626' : '#059669';
                ctx.stroke();
                ctx.font = 'bold 9px Plus Jakarta Sans, sans-serif';
                ctx.fillStyle = '#0f172a';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(cand.nomor_urut, xPos + size / 2 - 2, yPos + 4);
                ctx.restore();

                // Candidate full name (horizontal, non-tilted, clean multi-line wrapping if needed)
                ctx.save();
                ctx.font = 'bold 12px "Plus Jakarta Sans", sans-serif';
                ctx.fillStyle = '#0f172a';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'top';

                const fullName = cand.nama || '';
                const words = fullName.split(' ');
                if (words.length > 2) {
                    const mid = Math.ceil(words.length / 2);
                    const line1 = words.slice(0, mid).join(' ');
                    const line2 = words.slice(mid).join(' ');
                    ctx.fillText(line1, xPos, yPos + size + 7);
                    ctx.fillText(line2, xPos, yPos + size + 21);
                } else if (fullName.length > 14 && words.length === 2) {
                    ctx.fillText(words[0], xPos, yPos + size + 7);
                    ctx.fillText(words[1], xPos, yPos + size + 21);
                } else {
                    ctx.fillText(fullName, xPos, yPos + size + 7);
                }
                ctx.restore();
            });
        }
    };

    // 1. Inisialisasi Advanced Comparative Bar Chart (Chart.js) dengan Avatar Foto
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
            plugins: [candidateAvatarPlugin],
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        top: 36,
                        bottom: 84, // Extra breathing space for full candidate names & avatars
                        left: 14,
                        right: 14
                    }
                },
                animation: {
                    duration: 800,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'center',
                        labels: {
                            padding: 24,
                            font: { family: 'Plus Jakarta Sans', weight: 'bold', size: 14 },
                            usePointStyle: true,
                            pointStyle: 'rect',
                            boxWidth: 16,
                            boxHeight: 16
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { family: 'Plus Jakarta Sans', weight: 'bold', size: 13 },
                        bodyFont: { family: 'Plus Jakarta Sans', weight: '600', size: 12 },
                        padding: 14,
                        cornerRadius: 12,
                        boxPadding: 6,
                        callbacks: {
                            label: function(context) {
                                const val = context.parsed.y;
                                if (val === null || val === undefined) return '';
                                const dataIndex = context.dataIndex;
                                let candidateName = '';
                                let pct = '0.0';
                                
                                if (currentChartMode === 'ketua' && rawKetuaData[dataIndex]) {
                                    candidateName = rawKetuaData[dataIndex].nama;
                                    pct = rawKetuaData[dataIndex].persen;
                                } else if (currentChartMode === 'pengawas' && rawPengawasData[dataIndex]) {
                                    candidateName = rawPengawasData[dataIndex].nama;
                                    pct = rawPengawasData[dataIndex].persen;
                                } else if (currentChartMode === 'all') {
                                    if (dataIndex < rawKetuaData.length && rawKetuaData[dataIndex]) {
                                        candidateName = rawKetuaData[dataIndex].nama;
                                        pct = rawKetuaData[dataIndex].persen;
                                    } else {
                                        const pIndex = dataIndex - rawKetuaData.length;
                                        if (rawPengawasData[pIndex]) {
                                            candidateName = rawPengawasData[pIndex].nama;
                                            pct = rawPengawasData[pIndex].persen;
                                        }
                                    }
                                }
                                return ` ${candidateName}: ${val} Suara (${pct}%)`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grace: '25%',
                        ticks: {
                            stepSize: 1,
                            font: { family: 'Plus Jakarta Sans', weight: 'bold', size: 11 },
                            color: '#64748b'
                        },
                        grid: {
                            color: '#f1f5f9'
                        }
                    },
                    x: {
                        barPercentage: 0.65,
                        categoryPercentage: 0.65,
                        ticks: {
                            display: false // Hide text ticks, rendered as circular photo avatars by candidateAvatarPlugin
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
                labels: rawKetuaData.map(k => k.nama),
                datasets: [{
                    label: '{{ __('Category 1: Cooperative Chairman') }}',
                    data: rawKetuaData.map(k => k.suara),
                    backgroundColor: 'rgba(239, 68, 68, 0.85)',
                    hoverBackgroundColor: 'rgba(220, 38, 38, 0.95)',
                    borderWidth: 0,
                    borderRadius: 0,
                    barThickness: 32,
                    maxBarThickness: 44,
                }]
            };
        } else if (mode === 'pengawas') {
            return {
                labels: rawPengawasData.map(p => p.nama),
                datasets: [{
                    label: '{{ __('Category 2: Supervisory Board') }}',
                    data: rawPengawasData.map(p => p.suara),
                    backgroundColor: 'rgba(16, 185, 129, 0.85)',
                    hoverBackgroundColor: 'rgba(5, 150, 105, 0.95)',
                    borderWidth: 0,
                    borderRadius: 0,
                    barThickness: 32,
                    maxBarThickness: 44,
                }]
            };
        } else {
            const labels = [];
            rawKetuaData.forEach(k => labels.push(k.nama));
            rawPengawasData.forEach(p => labels.push(p.nama));

            const ketuaVotes = rawKetuaData.map(k => k.suara).concat(rawPengawasData.map(() => null));
            const pengawasVotes = rawKetuaData.map(() => null).concat(rawPengawasData.map(p => p.suara));

            return {
                labels: labels,
                datasets: [
                    {
                        label: '{{ __('Category 1: Cooperative Chairman') }}',
                        data: ketuaVotes,
                        backgroundColor: 'rgba(239, 68, 68, 0.85)',
                        hoverBackgroundColor: 'rgba(220, 38, 38, 0.95)',
                        borderWidth: 0,
                        borderRadius: 0,
                        barThickness: 32,
                        maxBarThickness: 44,
                        grouped: false,
                    },
                    {
                        label: '{{ __('Category 2: Supervisory Board') }}',
                        data: pengawasVotes,
                        backgroundColor: 'rgba(16, 185, 129, 0.85)',
                        hoverBackgroundColor: 'rgba(5, 150, 105, 0.95)',
                        borderWidth: 0,
                        borderRadius: 0,
                        barThickness: 32,
                        maxBarThickness: 44,
                        grouped: false,
                    }
                ]
            };
        }
    }

    function switchChartMode(mode) {
        if (window.SoundEffects) window.SoundEffects.click();
        currentChartMode = mode;

        const activeClass = 'px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl bg-white text-slate-900 shadow-sm transition cursor-pointer whitespace-nowrap font-black';
        const inactiveClass = 'px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl text-slate-600 hover:text-slate-900 transition cursor-pointer whitespace-nowrap font-extrabold';

        document.getElementById('btn-chart-all').className = mode === 'all' ? activeClass : inactiveClass;
        document.getElementById('btn-chart-ketua').className = mode === 'ketua' ? activeClass : inactiveClass;
        document.getElementById('btn-chart-pengawas').className = mode === 'pengawas' ? activeClass : inactiveClass;

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
                if (badge) badge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300';
                if (text) text.innerText = 'Live SSE';
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
                if (badge) badge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300';
                if (text) text.innerText = 'Polling';
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

        // Update Voting Deadline Countdown
        if (data.metrics && data.metrics.voting_deadline) {
            currentDeadlineTimestamp = data.metrics.deadline_timestamp;
            const container = document.getElementById('deadline-container');
            if (container) container.classList.remove('hidden');
            const fmt = document.getElementById('deadline-formatted-text');
            if (fmt && data.metrics.deadline_formatted) fmt.innerText = data.metrics.deadline_formatted;
            tickCountdown();
        } else if (data.metrics && !data.metrics.voting_deadline) {
            const container = document.getElementById('deadline-container');
            if (container) container.classList.add('hidden');
        }

        // Update Chart
        if (comparisonChart) {
            const { labels, datasets } = generateChartData(currentChartMode);
            comparisonChart.data.labels = labels;
            comparisonChart.data.datasets = datasets;
            comparisonChart.update('none');
        }
    }

    // Voting Deadline Realtime Countdown Timer
    let currentDeadlineTimestamp = {{ $metrics['deadline_timestamp'] ?? 'null' }};
    function tickCountdown() {
        if (!currentDeadlineTimestamp) return;
        const now = Math.floor(Date.now() / 1000);
        const diff = currentDeadlineTimestamp - now;

        const daysEl = document.getElementById('deadline-days');
        const hoursEl = document.getElementById('deadline-hours');
        const minsEl = document.getElementById('deadline-mins');
        const secsEl = document.getElementById('deadline-secs');
        const pillEl = document.getElementById('deadline-status-pill');

        if (diff <= 0) {
            if (daysEl) daysEl.innerText = '00d';
            if (hoursEl) hoursEl.innerText = '00h';
            if (minsEl) minsEl.innerText = '00m';
            if (secsEl) secsEl.innerText = '00s';
            if (pillEl) {
                pillEl.className = 'px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-slate-200 text-slate-800';
                pillEl.innerText = 'Telah Berakhir';
            }
            return;
        }

        const days = Math.floor(diff / 86400);
        const hours = Math.floor((diff % 86400) / 3600);
        const mins = Math.floor((diff % 3600) / 60);
        const secs = diff % 60;

        if (daysEl) daysEl.innerText = String(days).padStart(2, '0') + 'd';
        if (hoursEl) hoursEl.innerText = String(hours).padStart(2, '0') + 'h';
        if (minsEl) minsEl.innerText = String(mins).padStart(2, '0') + 'm';
        if (secsEl) secsEl.innerText = String(secs).padStart(2, '0') + 's';
    }

    setInterval(tickCountdown, 1000);
    document.addEventListener('DOMContentLoaded', tickCountdown);

    // 3. Modal Detail Calon & Text-to-Speech (TTS) Engine
    let activeModalCandidate = null;
    let isSpeaking = false;

    function openCandidateModal(kategori, nama, nomor, visi, misi, deskripsi, foto, suara, persen) {
        if (window.SoundEffects) window.SoundEffects.modal();

        activeModalCandidate = { kategori, nama, nomor, visi, misi, deskripsi };
        stopTTS();

        document.getElementById('detail-modal-foto').src = foto;
        document.getElementById('detail-modal-nama').innerText = nama;
        document.getElementById('detail-modal-no').innerText = 'No. ' + nomor;
        document.getElementById('detail-modal-badge').innerText = kategori;
        document.getElementById('detail-modal-badge').className = kategori.includes('Ketua') ? 'px-2.5 py-0.5 rounded-md text-xs font-black bg-red-100 text-red-800 uppercase' : 'px-2.5 py-0.5 rounded-md text-xs font-black bg-emerald-100 text-emerald-800 uppercase';
        document.getElementById('detail-modal-tally').innerText = `${suara} Suara (${persen})`;

        document.getElementById('detail-modal-deskripsi').innerText = deskripsi || 'Calon terdaftar resmi.';
        document.getElementById('detail-modal-visi').innerText = visi || '-';
        document.getElementById('detail-modal-misi').innerText = misi || '-';

        document.getElementById('candidate-detail-modal').classList.remove('hidden');
    }

    function closeCandidateModal() {
        if (window.SoundEffects) window.SoundEffects.click();
        stopTTS();
        document.getElementById('candidate-detail-modal').classList.add('hidden');
    }

    function toggleModalTTS() {
        if (!('speechSynthesis' in window)) {
            alert('Perangkat/browser Anda tidak mendukung Text-to-Speech.');
            return;
        }

        if (isSpeaking || window.speechSynthesis.speaking) {
            stopTTS();
            return;
        }

        if (!activeModalCandidate) return;

        const textToRead = `${activeModalCandidate.kategori}. Nomor urut ${activeModalCandidate.nomor}. Nama: ${activeModalCandidate.nama}. Latar belakang: ${activeModalCandidate.deskripsi || ''}. Visi: ${activeModalCandidate.visi || ''}. Misi: ${activeModalCandidate.misi || ''}.`;

        window.speechSynthesis.cancel();
        const utterance = new SpeechSynthesisUtterance(textToRead);
        utterance.lang = 'id-ID';
        utterance.rate = 0.95; // Kecepatan ramah dan jelas untuk boomer
        utterance.pitch = 1.0;

        const voices = window.speechSynthesis.getVoices();
        const idVoice = voices.find(v => v.lang.startsWith('id') || v.lang.includes('ID'));
        if (idVoice) {
            utterance.voice = idVoice;
        }

        utterance.onstart = function() {
            isSpeaking = true;
            updateTTSButtonUI(true);
        };

        utterance.onend = function() {
            isSpeaking = false;
            updateTTSButtonUI(false);
        };

        utterance.onerror = function() {
            isSpeaking = false;
            updateTTSButtonUI(false);
        };

        window.speechSynthesis.speak(utterance);
    }

    function stopTTS() {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
        }
        isSpeaking = false;
        updateTTSButtonUI(false);
    }

    function updateTTSButtonUI(speaking) {
        const btn = document.getElementById('btn-modal-tts');
        const label = document.getElementById('tts-btn-label');
        if (!btn || !label) return;

        if (speaking) {
            btn.className = 'inline-flex items-center justify-center space-x-2 px-4 py-2.5 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-extrabold text-xs sm:text-sm border-2 border-amber-600 shadow-md transition cursor-pointer animate-pulse self-start sm:self-auto';
            label.innerText = 'Hentikan Suara ⏹';
        } else {
            btn.className = 'inline-flex items-center justify-center space-x-2 px-4 py-2.5 rounded-2xl bg-blue-50 hover:bg-blue-100 text-blue-800 font-extrabold text-xs sm:text-sm border-2 border-blue-200 shadow-xs transition cursor-pointer self-start sm:self-auto';
            label.innerText = 'Dengarkan Suara (Audio)';
        }
    }
</script>
@endpush
@endsection
