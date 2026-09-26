@extends('layouts.app')

@section('title', __('Live Count Cooperative Election'))

@section('content')
<div class="flex-1 flex flex-col p-3.5 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full min-w-0 box-border overflow-x-hidden">

    <!-- Top Navigation & Live Header (Row 1 Header Text at Top, Row 2 Components) -->
    <header class="pb-6 mb-6 border-b border-slate-200 space-y-4">
        <!-- Row 1: Header Text (Brand Logo + Title + Subtitle + Live SSE Badge at Top Full Width) -->
        <div class="flex items-center space-x-3.5 min-w-0">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-xs shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center space-x-2.5 flex-wrap gap-y-1">
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-black text-slate-900 tracking-tight">{{ __('Live Count Cooperative Election') }}</h1>
                    <span id="sse-status-badge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 shrink-0">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse mr-1.5"></span>
                        <span id="sse-status-text">Live SSE</span>
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">
                    {{ __('Last Updated') }} • <span id="last-updated-text" class="font-bold text-slate-800">{{ $metrics['last_updated'] ?? (now()->timezone('Asia/Jakarta')->format('H:i:s') . ' WIB') }}</span>
                </p>
            </div>
        </div>

        <!-- Row 2: Action Components Row (Controls on Left, Language Switcher & Tap RFID Button on Right) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
            <!-- Left: Section Expand/Collapse Controls -->
            <div class="flex items-center space-x-2">
                <button type="button" onclick="expandAllSections()" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 hover:text-slate-900 border border-slate-200 text-xs font-black shadow-2xs transition flex items-center space-x-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    <span>{{ __('Expand All') }}</span>
                </button>
                <button type="button" onclick="collapseAllSections()" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 hover:text-slate-900 border border-slate-200 text-xs font-black shadow-2xs transition flex items-center space-x-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                    <span>{{ __('Collapse All') }}</span>
                </button>
            </div>

            <!-- Right: Language Switcher + Tap RFID Card to Vote Button -->
            <div class="flex items-center gap-2.5 sm:gap-3 w-full sm:w-auto">
                <div class="inline-flex rounded-xl border border-slate-200 bg-white p-1 text-xs font-bold shadow-2xs shrink-0">
                    <a href="{{ route('lang.switch', 'en') }}" class="px-2.5 py-1 rounded-md transition {{ app()->getLocale() === 'en' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-blue-600' }}">EN</a>
                    <a href="{{ route('lang.switch', 'id') }}" class="px-2.5 py-1 rounded-md transition {{ app()->getLocale() === 'id' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-blue-600' }}">ID</a>
                </div>

                <a href="{{ route('voter.tap') }}" class="flex-1 sm:flex-none justify-center px-4 py-2.5 sm:px-6 sm:py-3 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 hover:from-blue-700 hover:to-indigo-800 text-white font-black text-xs sm:text-sm md:text-base shadow-md shadow-blue-600/30 hover:shadow-lg transition-all flex items-center space-x-2.5 cursor-pointer min-w-0">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                        <rect x="3" y="5" width="18" height="14" rx="3" stroke-width="2.2" />
                        <path stroke-linecap="round" d="M7 15h3M7 11h2" stroke-width="2" />
                        <path stroke-linecap="round" d="M16 9a3 3 0 0 1 0 6m2.5-8a6 6 0 0 1 0 10" stroke-width="2.2" />
                    </svg>
                    <span class="tracking-wide truncate">{{ __('Tap RFID Card to Vote') }}</span>
                </a>
            </div>
        </div>
    </header>

    <!-- ======================================================== -->
    <!-- SECTION 1: EXECUTIVE OVERVIEW & KEY INDICATORS (FOLDABLE)-->
    <!-- ======================================================== -->
    <div id="sec-overview" class="p-5 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-xs mb-8 transition-all">
        <!-- Section Foldable Header (Minimalist Dot Color) -->
        <div class="flex items-center justify-between gap-3 pb-4 border-b border-slate-100 cursor-pointer select-none group" onclick="toggleSection('sec-overview')">
            <div class="flex items-center space-x-3 min-w-0">
                <span class="w-3.5 h-3.5 rounded-full bg-blue-600 ring-4 ring-blue-100 shrink-0"></span>
                <div class="min-w-0">
                    <h2 class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight group-hover:text-blue-600 transition-colors">
                        {{ __('Executive Overview & Metrics') }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-0.5">
                        {{ __('Key election metrics and live voting progress indicators') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-2 shrink-0">
                <span id="sec-overview-state" class="text-xs sm:text-sm font-bold text-slate-500 group-hover:text-slate-800 transition hidden sm:inline-block">
                    {{ __('Fold Section') }}
                </span>
                <div class="w-8 h-8 rounded-lg bg-slate-100 group-hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">
                    <svg id="sec-overview-chevron" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Section Content (Huge, High-Contrast, Boomer-Friendly Executive Cards) -->
        <div id="sec-overview-content" class="pt-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
                <!-- Card 1: Voter Participation Rate -->
                <div class="p-6 sm:p-7 rounded-3xl bg-blue-50/70 border-2 border-blue-200/90 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-sm sm:text-base font-black uppercase tracking-wider text-blue-900 block mb-2">{{ __('Voter Participation Rate') }}</span>
                        <div class="flex items-baseline space-x-2 my-2">
                            <span id="stat-turnout-pct" class="text-5xl sm:text-6xl font-black text-blue-700 font-mono tracking-tight">{{ $metrics['turnout_pct'] }}%</span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="w-full bg-blue-200/80 rounded-full h-4 overflow-hidden mb-2.5">
                            <div id="stat-turnout-bar" class="bg-blue-600 h-4 rounded-full transition-all duration-700 ease-out" style="width: {{ $metrics['turnout_pct'] }}%;"></div>
                        </div>
                        <span class="text-base sm:text-lg font-black text-slate-800 block">
                            <span id="stat-total-voted" class="text-blue-700 text-xl sm:text-2xl font-black">{{ $metrics['total_voted'] }}</span> / <span id="stat-total-voters" class="text-slate-900 text-xl sm:text-2xl font-black">{{ $metrics['total_voters'] }}</span> {{ __('Votes') }}
                        </span>
                    </div>
                </div>

                <!-- Card 2: Election Status & Security -->
                <div class="p-6 sm:p-7 rounded-3xl bg-emerald-50/70 border-2 border-emerald-200/90 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-sm sm:text-base font-black uppercase tracking-wider text-emerald-950 block mb-2">{{ __('Election Status') }}</span>
                        <div class="flex items-center space-x-3.5 my-3">
                            <span class="w-5 h-5 rounded-full bg-emerald-500 ring-8 ring-emerald-200 animate-pulse shrink-0"></span>
                            <span class="text-2xl sm:text-3xl font-black text-emerald-950 tracking-tight leading-tight">{{ __('Active & Verified') }}</span>
                        </div>
                    </div>
                    <p class="text-base sm:text-lg font-black text-emerald-800 mt-4 leading-snug">
                        ✓ {{ __('Voting Booth Ready • Secure & Verified') }}
                    </p>
                </div>

                <!-- Card 3: Leading Frontrunners (Huge, Boomer-Readable Text) -->
                <div class="p-6 sm:p-7 rounded-3xl bg-amber-50/70 border-2 border-amber-200/90 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm sm:text-base font-black uppercase tracking-wider text-amber-950 block">{{ __('Leading Frontrunners') }}</span>
                            <span class="w-3 h-3 rounded-full bg-amber-500 ring-4 ring-amber-200 animate-pulse"></span>
                        </div>
                        <div class="space-y-3 my-2">
                            <!-- Chairman Leader -->
                            <div class="pb-2 border-b border-amber-200/70">
                                <span class="text-xs font-black uppercase tracking-wider text-red-700 block mb-0.5">{{ __('Chairman') }}</span>
                                <strong id="leader-ketua-text" class="text-lg sm:text-xl font-black text-slate-950 block truncate">{{ $metrics['leader_ketua'] ?: '(' . __('No Votes Yet') . ')' }}</strong>
                            </div>
                            <!-- Supervisor Leader -->
                            <div>
                                <span class="text-xs font-black uppercase tracking-wider text-emerald-800 block mb-0.5">{{ __('Supervisor') }}</span>
                                <strong id="leader-pengawas-text" class="text-lg sm:text-xl font-black text-slate-950 block truncate">{{ $metrics['leader_pengawas'] ?: '(' . __('No Votes Yet') . ')' }}</strong>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm sm:text-base font-bold text-amber-900 mt-4 leading-snug">
                        ✓ {{ __('Highest live verified tally') }}
                    </p>
                </div>

                <!-- Card 4: Voting Deadline Countdown -->
                <div id="deadline-container" class="p-6 sm:p-7 rounded-3xl bg-rose-50/70 border-2 border-rose-200/90 shadow-sm flex flex-col justify-between {{ empty($metrics['voting_deadline']) ? 'hidden' : '' }}">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm sm:text-base font-black uppercase tracking-wider text-rose-950">{{ __('Voting Deadline') }}</span>
                            <span id="deadline-status-pill" class="px-3 py-1 rounded-full text-xs font-black uppercase bg-rose-200 text-rose-900 border border-rose-300">{{ __('Countdown') }}</span>
                        </div>
                        <div class="flex flex-wrap sm:flex-nowrap items-center space-x-1.5 font-mono text-2xl sm:text-3xl font-black text-slate-950 my-3" id="deadline-countdown-timer">
                            <span id="deadline-days" class="bg-white border-2 border-rose-200 px-2.5 py-1.5 rounded-2xl shadow-xs">00d</span>
                            <span>:</span>
                            <span id="deadline-hours" class="bg-white border-2 border-rose-200 px-2.5 py-1.5 rounded-2xl shadow-xs">00h</span>
                            <span>:</span>
                            <span id="deadline-mins" class="bg-white border-2 border-rose-200 px-2.5 py-1.5 rounded-2xl shadow-xs">00m</span>
                            <span>:</span>
                            <span id="deadline-secs" class="bg-white border-2 border-rose-200 px-2.5 py-1.5 rounded-2xl text-rose-600 shadow-xs">00s</span>
                        </div>
                    </div>
                    <p id="deadline-info-text" class="text-xs sm:text-sm text-slate-700 mt-4 font-bold truncate">
                        {{ __('Deadline:') }} <strong id="deadline-formatted-text" class="text-slate-950 font-black">{{ $metrics['deadline_formatted'] ?? '-' }}</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- SECTION 2: ADVANCED COMPARATIVE BAR CHART (FOLDABLE)     -->
    <!-- ======================================================== -->
    <div id="sec-chart" class="p-5 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-xs mb-8 transition-all">
        <!-- Section Foldable Header (Row 1 Full Width Title, Row 2 Filter Tabs) -->
        <div class="pb-5 border-b border-slate-100 space-y-4">
            <!-- Row 1: Full-Width Title & Fold Trigger (No Truncation) -->
            <div class="flex items-center justify-between gap-4 cursor-pointer select-none group" onclick="toggleSection('sec-chart')">
                <div class="flex items-center space-x-3 min-w-0">
                    <span class="w-3.5 h-3.5 rounded-full bg-indigo-600 ring-4 ring-indigo-100 shrink-0"></span>
                    <div>
                        <h2 class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight group-hover:text-indigo-600 transition-colors">
                            {{ __('Comparative Bar Chart of All Candidates') }}
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-0.5">
                            {{ __('Real-time vote count distribution and percentage for Chairman and Supervisory Board') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 shrink-0">
                    <span id="sec-chart-state" class="text-xs sm:text-sm font-bold text-slate-500 group-hover:text-slate-800 transition hidden sm:inline-block">
                        {{ __('Fold Section') }}
                    </span>
                    <div class="w-8 h-8 rounded-lg bg-slate-100 group-hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">
                        <svg id="sec-chart-chevron" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Row 2: Filter Tabs on Dedicated Row (Large, Touch-Friendly Buttons) -->
            <div class="flex items-center overflow-x-auto pt-1">
                <div class="inline-flex rounded-2xl bg-slate-100 p-1.5 text-xs sm:text-sm md:text-base font-extrabold shadow-inner gap-1">
                    <button type="button" onclick="switchChartMode('all')" id="btn-chart-all" class="px-4 sm:px-6 py-2.5 rounded-xl bg-white text-slate-900 shadow-sm transition cursor-pointer whitespace-nowrap font-black">{{ __('All Candidates') }}</button>
                    <button type="button" onclick="switchChartMode('ketua')" id="btn-chart-ketua" class="px-4 sm:px-6 py-2.5 rounded-xl text-slate-600 hover:text-slate-900 transition cursor-pointer whitespace-nowrap font-extrabold">{{ __('Chairman Candidates') }}</button>
                    <button type="button" onclick="switchChartMode('pengawas')" id="btn-chart-pengawas" class="px-4 sm:px-6 py-2.5 rounded-xl text-slate-600 hover:text-slate-900 transition cursor-pointer whitespace-nowrap font-extrabold">{{ __('Supervisor Candidates') }}</button>
                </div>
            </div>
        </div>

        <!-- Section Content (Enlarged Height For Boomer Readability) -->
        <div id="sec-chart-content" class="pt-6">
            <div class="relative w-full max-w-full mx-auto h-[460px] sm:h-[540px] md:h-[620px] overflow-hidden">
                <canvas id="comparisonChart"></canvas>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- SECTION 3: PROCEDURAL VOTING GUIDE (3 QUICK FULL-WIDTH ROWS) -->
    <!-- ======================================================== -->
    <div id="sec-guide" class="p-5 sm:p-7 rounded-3xl bg-slate-50 border border-slate-200 mb-8 transition-all">
        <!-- Section Foldable Header (Minimalist Dot Color) -->
        <div class="flex items-center justify-between gap-3 pb-4 border-b border-slate-200 cursor-pointer select-none group" onclick="toggleSection('sec-guide')">
            <div class="flex items-center space-x-3 min-w-0">
                <span class="w-3.5 h-3.5 rounded-full bg-amber-500 ring-4 ring-amber-100 shrink-0"></span>
                <div class="min-w-0">
                    <h2 class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight group-hover:text-amber-600 transition-colors">
                        {{ __('Voting Guide (3 Quick Steps)') }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-0.5">
                        {{ __('Simple 3-step procedural guide to cast your vote at the voting booth') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-2 shrink-0">
                <span id="sec-guide-state" class="text-xs sm:text-sm font-bold text-slate-500 group-hover:text-slate-800 transition hidden sm:inline-block">
                    {{ __('Fold Section') }}
                </span>
                <div class="w-8 h-8 rounded-lg bg-white group-hover:bg-slate-200 text-slate-600 border border-slate-200 flex items-center justify-center transition">
                    <svg id="sec-guide-chevron" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Section Content (3 Full-Width Row Cards, Enormous Step Badges & Description Text) -->
        <div id="sec-guide-content" class="pt-6">
            <div class="grid grid-cols-1 gap-5">
                <!-- Card 1: Step 01 -->
                <div class="p-6 sm:p-7 rounded-3xl bg-white border-2 border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-start sm:items-center space-x-5 min-w-0">
                        <span class="px-5 py-2.5 sm:px-6 sm:py-3 rounded-2xl text-base sm:text-lg font-black uppercase tracking-wider bg-blue-100 text-blue-900 border-2 border-blue-300 shrink-0 shadow-xs">
                            {{ __('Step 01') }}
                        </span>
                        <div class="min-w-0">
                            <h4 class="text-xl sm:text-2xl font-black text-slate-950">{{ __('Tap RFID Member Card') }}</h4>
                            <p class="text-base sm:text-xl text-slate-700 font-bold mt-1.5 leading-relaxed">
                                {{ __('Scan your registered cooperative RFID card on the voting booth reader to authenticate your identity and open the digital ballot.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Step 02 -->
                <div class="p-6 sm:p-7 rounded-3xl bg-white border-2 border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-start sm:items-center space-x-5 min-w-0">
                        <span class="px-5 py-2.5 sm:px-6 sm:py-3 rounded-2xl text-base sm:text-lg font-black uppercase tracking-wider bg-indigo-100 text-indigo-900 border-2 border-indigo-300 shrink-0 shadow-xs">
                            {{ __('Step 02') }}
                        </span>
                        <div class="min-w-0">
                            <h4 class="text-xl sm:text-2xl font-black text-slate-950">{{ __('Select Your Candidates') }}</h4>
                            <p class="text-base sm:text-xl text-slate-700 font-bold mt-1.5 leading-relaxed">
                                {{ __('Touch candidate photo on the screen to choose 1 Chairman and 1 Supervisory Board candidate of your choice.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Step 03 -->
                <div class="p-6 sm:p-7 rounded-3xl bg-white border-2 border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-start sm:items-center space-x-5 min-w-0">
                        <span class="px-5 py-2.5 sm:px-6 sm:py-3 rounded-2xl text-base sm:text-lg font-black uppercase tracking-wider bg-emerald-100 text-emerald-900 border-2 border-emerald-300 shrink-0 shadow-xs">
                            {{ __('Step 03') }}
                        </span>
                        <div class="min-w-0">
                            <h4 class="text-xl sm:text-2xl font-black text-slate-950">{{ __('Submit & Complete Vote') }}</h4>
                            <p class="text-base sm:text-xl text-slate-700 font-bold mt-1.5 leading-relaxed">
                                {{ __('Review your selected candidates on the confirmation screen and tap Submit Vote to securely record your ballot in the system.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- SECTION 4: KANDIDAT KETUA KOPERASI SHOWCASE (FOLDABLE)   -->
    <!-- ======================================================== -->
    <section id="sec-ketua" class="p-5 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-xs mb-8 transition-all">
        <!-- Section Foldable Header (Minimalist Dot Color) -->
        <div class="flex items-center justify-between gap-3 pb-4 border-b border-slate-200 cursor-pointer select-none group" onclick="toggleSection('sec-ketua')">
            <div class="flex items-center space-x-3 min-w-0">
                <span class="w-3.5 h-3.5 rounded-full bg-red-600 ring-4 ring-red-100 shrink-0"></span>
                <div class="min-w-0">
                    <h3 class="text-base sm:text-xl font-black text-slate-900 tracking-tight group-hover:text-red-600 transition-colors truncate">
                        {{ __('Chairman Election Comparison') }}
                    </h3>
                    <p class="text-xs text-red-700 font-bold truncate">
                        {{ __('Total:') }} <strong id="total-ketua-votes">{{ $metrics['total_suara_ketua'] }}</strong> {{ __('Votes') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-2 shrink-0">
                <span id="sec-ketua-state" class="text-xs font-bold text-slate-500 group-hover:text-slate-800 transition hidden sm:inline-block">
                    {{ __('Fold Section') }}
                </span>
                <div class="w-8 h-8 rounded-lg bg-slate-100 group-hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">
                    <svg id="sec-ketua-chevron" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Section Content -->
        <div id="sec-ketua-content" class="pt-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-7" id="ketua-cards-grid">
                @foreach($ketuaResults as $ketua)
                    <div class="rounded-3xl bg-white border-2 border-slate-200 hover:border-red-400 overflow-hidden shadow-2xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                        <div>
                            <!-- Foto Card: Responsive & Sharp -->
                            <div class="relative w-full h-[260px] sm:h-[300px] max-h-[300px] bg-slate-900 overflow-hidden">
                                <img 
                                    src="{{ $ketua['foto'] }}" 
                                    alt="{{ $ketua['nama'] }}" 
                                    class="w-full h-full object-cover object-top hover:scale-105 transition-transform duration-500 ease-out"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-black/20 pointer-events-none"></div>

                                <!-- Badge Nomor Urut Absolute di Kiri Atas -->
                                <div class="absolute top-3.5 left-3.5 z-10">
                                    <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl bg-white text-slate-950 border-2 border-slate-300 shadow-xl flex items-center justify-center text-lg sm:text-2xl font-black ring-4 ring-black/15">
                                        {{ $ketua['nomor_urut'] }}
                                    </div>
                                </div>

                                <!-- Leading Star Badge di Kanan Atas -->
                                <div class="absolute top-3.5 right-3.5 z-10">
                                    <span id="ketua-leader-badge-{{ $ketua['nik'] }}" class="{{ $ketua['is_leader'] && $ketua['suara'] > 0 ? '' : 'hidden' }} px-3 py-1 rounded-full text-xs font-black bg-amber-400 text-amber-950 border border-amber-300 shadow-md">
                                        ⭐ {{ __('Leading') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Identitas Calon -->
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
                                    <span id="ketua-vote-count-{{ $ketua['nik'] }}" class="text-slate-800 font-extrabold text-sm">{{ $ketua['suara'] }} {{ __('Votes') }}</span>
                                    <span id="ketua-pct-val-{{ $ketua['nik'] }}" class="text-red-700 font-black text-base">{{ $ketua['persen'] }}%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                                    <div id="ketua-bar-fill-{{ $ketua['nik'] }}" class="bg-red-600 h-2.5 rounded-full transition-all duration-700 ease-out" style="width: {{ $ketua['persen'] }}%;"></div>
                                </div>
                            </div>

                            <!-- Button View Details Modal -->
                            <button 
                                type="button" 
                                onclick="openCandidateModal('ketua', '{{ addslashes($ketua['nama']) }}', '{{ $ketua['nomor_urut'] }}', `{{ addslashes($ketua['visi']) }}`, `{{ addslashes($ketua['misi']) }}`, `{{ addslashes($ketua['deskripsi']) }}`, '{{ $ketua['foto'] }}', '{{ $ketua['suara'] }}', '{{ $ketua['persen'] }}%')"
                                class="w-full py-3 px-4 rounded-2xl bg-gradient-to-r from-red-600 via-rose-600 to-red-700 hover:from-red-700 hover:to-rose-800 text-white font-extrabold text-xs sm:text-sm shadow-md hover:shadow-lg transition-all flex items-center justify-center space-x-2 cursor-pointer"
                            >
                                <span>{{ __('View Profile & Vision') }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ======================================================== -->
    <!-- SECTION 5: KANDIDAT PENGAWAS KOPERASI SHOWCASE (FOLDABLE) -->
    <!-- ======================================================== -->
    <section id="sec-pengawas" class="p-5 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-xs mb-8 transition-all">
        <!-- Section Foldable Header (Minimalist Dot Color) -->
        <div class="flex items-center justify-between gap-3 pb-4 border-b border-slate-200 cursor-pointer select-none group" onclick="toggleSection('sec-pengawas')">
            <div class="flex items-center space-x-3 min-w-0">
                <span class="w-3.5 h-3.5 rounded-full bg-emerald-600 ring-4 ring-emerald-100 shrink-0"></span>
                <div class="min-w-0">
                    <h3 class="text-base sm:text-xl font-black text-slate-900 tracking-tight group-hover:text-emerald-600 transition-colors truncate">
                        {{ __('Supervisory Board Comparison') }}
                    </h3>
                    <p class="text-xs text-emerald-800 font-bold truncate">
                        {{ __('Total:') }} <strong id="total-pengawas-votes">{{ $metrics['total_suara_pengawas'] }}</strong> {{ __('Votes') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-2 shrink-0">
                <span id="sec-pengawas-state" class="text-xs font-bold text-slate-500 group-hover:text-slate-800 transition hidden sm:inline-block">
                    {{ __('Fold Section') }}
                </span>
                <div class="w-8 h-8 rounded-lg bg-slate-100 group-hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">
                    <svg id="sec-pengawas-chevron" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Section Content -->
        <div id="sec-pengawas-content" class="pt-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-7" id="pengawas-cards-grid">
                @foreach($pengawasResults as $pengawas)
                    <div class="rounded-3xl bg-white border-2 border-slate-200 hover:border-emerald-400 overflow-hidden shadow-2xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                        <div>
                            <!-- Foto Card: Responsive & Sharp -->
                            <div class="relative w-full h-[260px] sm:h-[300px] max-h-[300px] bg-slate-900 overflow-hidden">
                                <img 
                                    src="{{ $pengawas['foto'] }}" 
                                    alt="{{ $pengawas['nama'] }}" 
                                    class="w-full h-full object-cover object-top hover:scale-105 transition-transform duration-500 ease-out"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-black/20 pointer-events-none"></div>

                                <!-- Badge Nomor Urut Absolute di Kiri Atas -->
                                <div class="absolute top-3.5 left-3.5 z-10">
                                    <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl bg-white text-slate-950 border-2 border-slate-300 shadow-xl flex items-center justify-center text-lg sm:text-2xl font-black ring-4 ring-black/15">
                                        {{ $pengawas['nomor_urut'] }}
                                    </div>
                                </div>

                                <!-- Leading Star Badge di Kanan Atas -->
                                <div class="absolute top-3.5 right-3.5 z-10">
                                    <span id="pengawas-leader-badge-{{ $pengawas['nik'] }}" class="{{ $pengawas['is_leader'] && $pengawas['suara'] > 0 ? '' : 'hidden' }} px-3 py-1 rounded-full text-xs font-black bg-amber-400 text-amber-950 border border-amber-300 shadow-md">
                                        ⭐ {{ __('Leading') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Identitas Calon -->
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
                                    <span id="pengawas-vote-count-{{ $pengawas['nik'] }}" class="text-slate-800 font-extrabold text-sm">{{ $pengawas['suara'] }} {{ __('Votes') }}</span>
                                    <span id="pengawas-pct-val-{{ $pengawas['nik'] }}" class="text-emerald-700 font-black text-base">{{ $pengawas['persen'] }}%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                                    <div id="pengawas-bar-fill-{{ $pengawas['nik'] }}" class="bg-emerald-600 h-2.5 rounded-full transition-all duration-700 ease-out" style="width: {{ $pengawas['persen'] }}%;"></div>
                                </div>
                            </div>

                            <!-- Button View Details Modal -->
                            <button 
                                type="button" 
                                onclick="openCandidateModal('pengawas', '{{ addslashes($pengawas['nama']) }}', '{{ $pengawas['nomor_urut'] }}', `{{ addslashes($pengawas['visi']) }}`, `{{ addslashes($pengawas['misi']) }}`, `{{ addslashes($pengawas['deskripsi']) }}`, '{{ $pengawas['foto'] }}', '{{ $pengawas['suara'] }}', '{{ $pengawas['persen'] }}%')"
                                class="w-full py-3 px-4 rounded-2xl bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 hover:from-emerald-700 hover:to-teal-800 text-white font-extrabold text-xs sm:text-sm shadow-md hover:shadow-lg transition-all flex items-center justify-center space-x-2 cursor-pointer"
                            >
                                <span>{{ __('View Profile & Vision') }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
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
                        <span id="detail-modal-badge" class="px-2.5 py-0.5 rounded-md text-xs font-black bg-blue-100 text-blue-800 uppercase">{{ __('Candidate') }}</span>
                        <span id="detail-modal-no" class="text-sm font-extrabold text-slate-600">No. 01</span>
                    </div>
                    <h3 id="detail-modal-nama" class="text-xl sm:text-2xl font-black text-slate-900 mt-1">-</h3>
                    <span id="detail-modal-tally" class="text-sm font-bold text-blue-700 block mt-0.5">0 {{ __('Votes') }} (0%)</span>
                </div>
            </div>

            <!-- Text-to-Speech (TTS) Voice Button -->
            <button id="btn-modal-tts" onclick="toggleModalTTS()" type="button" class="inline-flex items-center justify-center space-x-2 px-4 py-2.5 rounded-2xl bg-blue-50 hover:bg-blue-100 text-blue-800 font-extrabold text-xs sm:text-sm border-2 border-blue-200 shadow-xs transition cursor-pointer self-start sm:self-auto">
                <svg id="tts-icon-speaker" class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" />
                </svg>
                <span id="tts-btn-label">{{ __('Listen to Audio (TTS)') }}</span>
            </button>
        </div>

        <div class="space-y-5">
            <!-- Profil Singkat / Track Record -->
            <div>
                <h4 class="text-xs sm:text-sm uppercase font-black tracking-wider text-slate-600 mb-2">{{ __('Background / Brief Profile:') }}</h4>
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
                const yPos = y.bottom + 12;
                const size = 46;

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
                ctx.lineWidth = 3;
                ctx.strokeStyle = cand.type === 'ketua' ? '#dc2626' : '#059669';
                ctx.stroke();
                ctx.restore();

                // Mini number badge pill (Enlarged for boomer readability)
                ctx.save();
                ctx.beginPath();
                ctx.arc(xPos + size / 2 - 3, yPos + 5, 9, 0, Math.PI * 2);
                ctx.fillStyle = '#ffffff';
                ctx.fill();
                ctx.lineWidth = 2;
                ctx.strokeStyle = cand.type === 'ketua' ? '#dc2626' : '#059669';
                ctx.stroke();
                ctx.font = '900 12px "Plus Jakarta Sans", sans-serif';
                ctx.fillStyle = '#0f172a';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(cand.nomor_urut, xPos + size / 2 - 3, yPos + 5);
                ctx.restore();

                // Candidate full name (Large font, horizontal, non-tilted, clean multi-line wrapping)
                ctx.save();
                ctx.font = '900 14px "Plus Jakarta Sans", sans-serif';
                ctx.fillStyle = '#0f172a';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'top';

                const fullName = cand.nama || '';
                const words = fullName.split(' ');
                if (words.length > 2) {
                    const mid = Math.ceil(words.length / 2);
                    const line1 = words.slice(0, mid).join(' ');
                    const line2 = words.slice(mid).join(' ');
                    ctx.fillText(line1, xPos, yPos + size + 8);
                    ctx.fillText(line2, xPos, yPos + size + 26);
                } else if (fullName.length > 14 && words.length === 2) {
                    ctx.fillText(words[0], xPos, yPos + size + 8);
                    ctx.fillText(words[1], xPos, yPos + size + 26);
                } else {
                    ctx.fillText(fullName, xPos, yPos + size + 8);
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
                        bottom: 110, // Generous breathing space for enlarged photo avatars & full candidate names
                        left: 16,
                        right: 16
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
                            padding: 26,
                            font: { family: 'Plus Jakarta Sans', weight: '900', size: 16 },
                            usePointStyle: true,
                            pointStyle: 'rect',
                            boxWidth: 20,
                            boxHeight: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { family: 'Plus Jakarta Sans', weight: '900', size: 15 },
                        bodyFont: { family: 'Plus Jakarta Sans', weight: 'bold', size: 14 },
                        padding: 16,
                        cornerRadius: 14,
                        boxPadding: 8,
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
                            font: { family: 'Plus Jakarta Sans', weight: '900', size: 14 },
                            color: '#334155'
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
                pillEl.innerText = '{{ __('Ended') }}';
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

    // 3. Section Folding Logic (All Sections Foldable)
    const FOLDABLE_SECTIONS = ['sec-overview', 'sec-chart', 'sec-guide', 'sec-ketua', 'sec-pengawas'];

    function toggleSection(secId) {
        if (window.SoundEffects) window.SoundEffects.click();
        const content = document.getElementById(secId + '-content');
        const chevron = document.getElementById(secId + '-chevron');
        const stateBadge = document.getElementById(secId + '-state');
        if (!content) return;

        const isHidden = content.classList.contains('hidden');
        if (isHidden) {
            content.classList.remove('hidden');
            if (chevron) chevron.classList.remove('rotate-180');
            if (stateBadge) stateBadge.innerText = '{{ __('Fold Section') }}';
            if (secId === 'sec-chart' && comparisonChart) {
                setTimeout(() => comparisonChart.resize(), 50);
            }
        } else {
            content.classList.add('hidden');
            if (chevron) chevron.classList.add('rotate-180');
            if (stateBadge) stateBadge.innerText = '{{ __('Expand Section') }}';
        }
    }

    function expandAllSections() {
        if (window.SoundEffects) window.SoundEffects.click();
        FOLDABLE_SECTIONS.forEach(id => {
            const content = document.getElementById(id + '-content');
            const chevron = document.getElementById(id + '-chevron');
            const stateBadge = document.getElementById(id + '-state');
            if (content) content.classList.remove('hidden');
            if (chevron) chevron.classList.remove('rotate-180');
            if (stateBadge) stateBadge.innerText = '{{ __('Fold Section') }}';
        });
        if (comparisonChart) setTimeout(() => comparisonChart.resize(), 50);
    }

    function collapseAllSections() {
        if (window.SoundEffects) window.SoundEffects.click();
        FOLDABLE_SECTIONS.forEach(id => {
            const content = document.getElementById(id + '-content');
            const chevron = document.getElementById(id + '-chevron');
            const stateBadge = document.getElementById(id + '-state');
            if (content) content.classList.add('hidden');
            if (chevron) chevron.classList.add('rotate-180');
            if (stateBadge) stateBadge.innerText = '{{ __('Expand Section') }}';
        });
    }

    // 4. Modal Detail Calon & Text-to-Speech (TTS) Engine
    let activeModalCandidate = null;
    let isSpeaking = false;

    function openCandidateModal(kategoriType, nama, nomor, visi, misi, deskripsi, foto, suara, persen) {
        if (window.SoundEffects) window.SoundEffects.modal();

        const localizedKategori = (kategoriType === 'ketua' || kategoriType.includes('Ketua') || kategoriType.includes('Chairman')) 
            ? '{{ __('Category 1: Cooperative Chairman') }}' 
            : '{{ __('Category 2: Supervisory Board') }}';

        activeModalCandidate = { kategori: localizedKategori, kategoriType, nama, nomor, visi, misi, deskripsi };
        stopTTS();

        document.getElementById('detail-modal-foto').src = foto;
        document.getElementById('detail-modal-nama').innerText = nama;
        document.getElementById('detail-modal-no').innerText = 'No. ' + nomor;
        document.getElementById('detail-modal-badge').innerText = localizedKategori;
        document.getElementById('detail-modal-badge').className = (kategoriType === 'ketua' || kategoriType.includes('Ketua') || kategoriType.includes('Chairman')) 
            ? 'px-2.5 py-0.5 rounded-md text-xs font-black bg-red-100 text-red-800 uppercase' 
            : 'px-2.5 py-0.5 rounded-md text-xs font-black bg-emerald-100 text-emerald-800 uppercase';
        document.getElementById('detail-modal-tally').innerText = `${suara} {{ __('Votes') }} (${persen})`;

        document.getElementById('detail-modal-deskripsi').innerText = deskripsi || '{{ __('Official registered candidate.') }}';
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
            alert('{{ __('Your browser does not support Text-to-Speech.') }}');
            return;
        }

        if (isSpeaking || window.speechSynthesis.speaking) {
            stopTTS();
            return;
        }

        if (!activeModalCandidate) return;

        const lang = '{{ app()->getLocale() }}';
        const labelNo = lang === 'id' ? 'Nomor urut' : 'Candidate number';
        const labelName = lang === 'id' ? 'Nama' : 'Name';
        const labelBg = lang === 'id' ? 'Latar belakang' : 'Background';
        const labelVis = lang === 'id' ? 'Visi' : 'Vision';
        const labelMis = lang === 'id' ? 'Misi' : 'Mission';

        const textToRead = `${activeModalCandidate.kategori}. ${labelNo} ${activeModalCandidate.nomor}. ${labelName}: ${activeModalCandidate.nama}. ${labelBg}: ${activeModalCandidate.deskripsi || ''}. ${labelVis}: ${activeModalCandidate.visi || ''}. ${labelMis}: ${activeModalCandidate.misi || ''}.`;

        window.speechSynthesis.cancel();
        const utterance = new SpeechSynthesisUtterance(textToRead);
        utterance.lang = lang === 'id' ? 'id-ID' : 'en-US';
        utterance.rate = 0.95;
        utterance.pitch = 1.0;

        const voices = window.speechSynthesis.getVoices();
        const matchedVoice = voices.find(v => lang === 'id' ? (v.lang.startsWith('id') || v.lang.includes('ID')) : (v.lang.startsWith('en') || v.lang.includes('US')));
        if (matchedVoice) {
            utterance.voice = matchedVoice;
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
            label.innerText = '{{ __('Stop Audio') }} ⏹';
        } else {
            btn.className = 'inline-flex items-center justify-center space-x-2 px-4 py-2.5 rounded-2xl bg-blue-50 hover:bg-blue-100 text-blue-800 font-extrabold text-xs sm:text-sm border-2 border-blue-200 shadow-xs transition cursor-pointer self-start sm:self-auto';
            label.innerText = '{{ __('Listen to Audio (TTS)') }}';
        }
    }
</script>
@endpush
@endsection
