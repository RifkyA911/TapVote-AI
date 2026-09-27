@extends('layouts.app')

@section('title', \App\Models\AppSetting::getElectionTitle())

@push('styles')
    <style>
        @keyframes fluidGradientSlow {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .voting-hero-card {
            background: linear-gradient(130deg, 
                #581c87 0%, 
                #7c3aed 18%, 
                #4338ca 36%, 
                #1d4ed8 54%, 
                #0284c7 72%, 
                #06b6d4 88%, 
                #6b21a8 100%
            );
            background-size: 320% 320%;
            animation: fluidGradientSlow 20s ease-in-out infinite;
            box-shadow: 0 16px 36px -6px rgba(67, 56, 202, 0.4), 0 0 24px 0 rgba(6, 182, 212, 0.2);
        }

        .voting-hero-card:hover {
            box-shadow: 0 22px 48px -6px rgba(124, 58, 237, 0.5), 0 0 36px 4px rgba(6, 182, 212, 0.35);
        }

        .scroll-sundul-init {
            opacity: 0;
            transform: translateY(60px) scale(0.96);
            will-change: transform, opacity;
        }
    </style>
@endpush

@section('content')
    <!-- ======================================================== -->
    <!-- 1. FULLSCREEN INITIAL FLUID WELCOMING LOADING OVERLAY (2s) -->
    <!-- ======================================================== -->
    <div id="welcome-loading-overlay"
        class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-white text-slate-900 overflow-hidden transition-all duration-700">
        <!-- Subtle Ambient Light Glows -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-purple-100/50 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-cyan-100/50 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-blue-50/60 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Central Loading Container -->
        <div class="relative z-10 flex flex-col items-center text-center px-6 max-w-md">
            <!-- 3D Card Loading Container (Exact Three.js Card replica with smooth rotation) -->
            <div class="w-36 h-[200px] sm:w-44 sm:h-[240px] rounded-3xl bg-slate-50/90 border-2 border-slate-200/90 shadow-2xl flex items-center justify-center relative mb-5 overflow-hidden">
                <div id="overlay-card-3d-canvas" class="w-full h-full flex items-center justify-center pointer-events-none"></div>
            </div>

            <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900 mb-1 drop-shadow-xs">
                TapVote <span class="bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent">AI</span>
            </h2>
            <p class="text-xs sm:text-sm font-bold text-slate-500 mb-5 uppercase tracking-wider">
                {{ __('Authenticating Electoral Live Stream...') }}
            </p>

            <!-- Fluid 2-Second Progress Bar -->
            <div class="w-64 sm:w-80 bg-slate-100 rounded-full h-2.5 overflow-hidden border border-slate-200 p-0.5 shadow-inner">
                <div id="welcome-loading-bar" class="h-full bg-gradient-to-r from-purple-500 via-blue-500 to-cyan-400 rounded-full w-0 transition-all duration-[2000ms] ease-out"></div>
            </div>
            <span id="welcome-loading-status" class="text-[11px] font-mono text-slate-500 mt-2.5 font-bold tracking-widest uppercase">System Initialization • 100%</span>
        </div>
    </div>

    <div class="flex-1 flex flex-col p-3.5 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full min-w-0 box-border overflow-x-hidden">

        <!-- Top Navigation & Live Header (Row 1 Header Text at Top, Row 2 Controls & Large Language Switcher) -->
        <header id="main-header" class="pb-6 mb-6 border-b border-slate-200 space-y-4">
            <!-- Row 1: Header Text (Brand Logo + Title + Subtitle + Live SSE Badge + Voting Deadline Badge on Right) -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 min-w-0">
                <div class="flex items-center space-x-3.5 min-w-0">
                    <div
                        class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-xs shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center space-x-2.5 flex-wrap gap-y-1">
                            <h1 class="text-xl sm:text-2xl md:text-3xl font-black text-slate-900 tracking-tight">
                                {{ \App\Models\AppSetting::getElectionTitle() }}</h1>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 shrink-0">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse mr-1.5"></span>
                                <span>Live SSE</span>
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">
                            {{ __('Last Updated') }} • <span id="last-updated-text"
                                class="font-bold text-slate-800">{{ $metrics['last_updated'] ?? (now()->timezone('Asia/Jakarta')->format('H:i:s') . ' WIB') }}</span>
                        </p>
                    </div>
                </div>

                <!-- Right: Deadline Waktu Badge dengan aksen merah (Satu row dengan judul, di atas EN/ID) -->
                @if(!empty($metrics['voting_deadline']))
                <div id="header-deadline-badge" class="inline-flex items-center gap-2 sm:gap-2.5 px-3.5 sm:px-4 py-2 rounded-2xl bg-rose-50 border-2 border-rose-300 text-rose-900 shadow-2xs self-start md:self-auto shrink-0">
                    <span class="relative flex h-2.5 w-2.5 shrink-0">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-600"></span>
                    </span>
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-[11px] sm:text-xs font-black uppercase tracking-wider text-rose-800">{{ __('Batas Waktu') }}:</span>
                        <span id="header-deadline-timer" class="font-mono font-black text-rose-700 text-xs sm:text-sm tracking-wide">--:--:--</span>
                        @if(!empty($metrics['deadline_formatted']))
                            <span class="text-[11px] text-rose-600/90 font-bold hidden xl:inline">({{ $metrics['deadline_formatted'] }})</span>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Row 2: Action Controls Row (Expand/Collapse on Left, Large EN/ID Language Switcher on Right) -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
                <!-- Left: Section Expand/Collapse Controls & Guide Button & Admin Button if IP is 192.168.1.5 -->
                <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-2 sm:gap-2.5 w-full sm:w-auto">
                    <button type="button" onclick="expandAllSections()"
                        class="w-full sm:w-auto justify-center px-3 sm:px-5 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl bg-white hover:bg-slate-100 text-slate-800 border-2 border-slate-200 text-xs sm:text-sm font-black shadow-2xs transition flex items-center space-x-1.5 sm:space-x-2 cursor-pointer">
                        <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                        <span class="truncate">{{ __('Expand All') }}</span>
                    </button>
                    <button type="button" onclick="collapseAllSections()"
                        class="w-full sm:w-auto justify-center px-3 sm:px-5 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl bg-white hover:bg-slate-100 text-slate-800 border-2 border-slate-200 text-xs sm:text-sm font-black shadow-2xs transition flex items-center space-x-1.5 sm:space-x-2 cursor-pointer">
                        <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" />
                        </svg>
                        <span class="truncate">{{ __('Collapse All') }}</span>
                    </button>
                    <button type="button" onclick="scrollToGuideSection(event)"
                        class="col-span-2 sm:col-span-1 w-full sm:w-auto justify-center px-3.5 sm:px-5 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl bg-amber-50 hover:bg-amber-100 text-amber-900 border-2 border-amber-300 text-xs sm:text-sm font-black shadow-2xs transition flex items-center space-x-1.5 sm:space-x-2 cursor-pointer">
                        <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <span>{{ __('Voting Guide') }}</span>
                    </button>

                    @if(in_array(request()->ip(), ['192.168.1.5', '127.0.0.1', '::1']) || request()->server('REMOTE_ADDR') === '192.168.1.5')
                        <a href="{{ route('admin.login') }}"
                            class="col-span-2 sm:col-span-1 w-full sm:w-auto justify-center px-3.5 sm:px-5 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl bg-slate-900 hover:bg-slate-800 text-white border-2 border-slate-700 text-xs sm:text-sm font-black shadow-2xs transition flex items-center space-x-1.5 sm:space-x-2 cursor-pointer">
                            <svg class="w-4 h-4 text-cyan-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                            <span>{{ __('Admin Panel') }}</span>
                        </a>
                    @endif
                </div>

                <!-- Right: Large EN / ID Language Switcher Buttons (Boomer-Readable) -->
                <div class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto justify-center sm:justify-end mt-1 sm:mt-0">
                    <div class="inline-flex rounded-xl sm:rounded-2xl border-2 border-slate-300 bg-white p-1 shadow-xs shrink-0 w-full sm:w-auto justify-center">
                        <a href="{{ route('lang.switch', 'en') }}"
                            class="flex-1 sm:flex-none text-center px-5 sm:px-6 py-2 sm:py-2.5 rounded-lg sm:rounded-xl transition text-xs sm:text-base font-black {{ app()->getLocale() === 'en' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-700 hover:text-blue-600 hover:bg-slate-100' }}">
                            EN
                        </a>
                        <a href="{{ route('lang.switch', 'id') }}"
                            class="flex-1 sm:flex-none text-center px-5 sm:px-6 py-2 sm:py-2.5 rounded-lg sm:rounded-xl transition text-xs sm:text-base font-black {{ app()->getLocale() === 'id' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-700 hover:text-blue-600 hover:bg-slate-100' }}">
                            ID
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- ======================================================== -->
        <!-- ACTION CARD: ENTER VOTING BOOTH (1 FULL CLICKABLE ROW)   -->
        <!-- Fluid 3D Three.js Asset + Purple/Blue/Cyan Blend Card    -->
        <!-- ======================================================== -->
        <a href="{{ route('voter.tap') }}" id="voting-hero-card" onclick="handleVotingHeroClick(event)"
            class="voting-hero-card group w-full p-4 sm:py-5 sm:px-7 rounded-2xl sm:rounded-3xl text-white shadow-xl hover:shadow-2xl transition-all duration-300 flex items-center justify-between gap-3 sm:gap-6 cursor-pointer mb-6 sm:mb-8 border-2 border-white/25 hover:border-white/40 hover:scale-[1.008] active:scale-[0.995] relative overflow-hidden">
            
            <!-- Soft Ambient Glow Orbs -->
            <div class="absolute -right-20 -top-20 w-72 h-72 bg-cyan-400/25 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-72 h-72 bg-purple-600/30 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Left: Icon & English/Bilingual Title -->
            <div class="flex items-center space-x-3.5 sm:space-x-5 min-w-0 relative z-10 flex-1">
                <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl sm:rounded-3xl bg-white/15 backdrop-blur-md border border-white/30 flex items-center justify-center shrink-0 shadow-inner group-hover:scale-105 group-hover:bg-white/20 transition-all duration-300">
                    <svg class="w-6 h-6 sm:w-9 sm:h-9 text-white drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                        <rect x="3" y="5" width="18" height="14" rx="3" stroke-width="2.2" />
                        <path stroke-linecap="round" d="M7 15h3M7 11h2" stroke-width="2" />
                        <path stroke-linecap="round" d="M16 9a3 3 0 0 1 0 6m2.5-8a6 6 0 0 1 0 10" stroke-width="2.2" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-1.5 sm:gap-2 mb-0.5">
                        <span class="text-[11px] sm:text-sm font-black uppercase tracking-wider text-cyan-200 block drop-shadow-xs">{{ __('Digital Voting Booth') }}</span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] sm:text-[10px] font-black bg-cyan-400/25 border border-cyan-300/40 text-cyan-100 uppercase tracking-widest">
                            {{ __('Ready') }}
                        </span>
                    </div>
                    <h2 class="text-lg sm:text-2xl md:text-3xl lg:text-4xl font-black text-white tracking-tight leading-snug sm:leading-tight drop-shadow-sm">
                        {{ __('Start Voting (Tap Card / Touch Here)') }}
                    </h2>
                </div>
            </div>

            <!-- Mobile Only: Clean Arrow Action (Hidden on desktop) -->
            <div class="flex sm:hidden shrink-0 items-center justify-center w-11 h-11 rounded-2xl bg-white/15 border border-white/30 text-white relative z-10 group-hover:translate-x-1 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            </div>

            <!-- Desktop Only: Interactive 3D Rectangular ID Card Pass (Hidden on mobile) -->
            <div class="hidden sm:flex shrink-0 items-center relative z-10 pl-2">
                <div id="hero-card-3d-wrapper"
                    class="w-36 h-[210px] md:w-44 md:h-[250px] rounded-3xl bg-white/10 backdrop-blur-md border border-white/25 shadow-inner flex items-center justify-center relative group-hover:border-cyan-300/60 group-hover:scale-105 group-hover:shadow-cyan-500/30 group-hover:shadow-lg transition-all duration-300">
                    <!-- Three.js Canvas mounts here -->
                    <div id="hero-card-3d-canvas" class="w-full h-full flex items-center justify-center pointer-events-none"></div>
                </div>
            </div>
        </a>

        <!-- ======================================================== -->
        <!-- SECTION 1: EXECUTIVE OVERVIEW & KEY INDICATORS (FOLDABLE)-->
        <!-- ======================================================== -->
        <div id="sec-overview"
            class="p-4 sm:p-7 rounded-2xl sm:rounded-3xl bg-white border border-slate-200 shadow-xs mb-6 sm:mb-8 transition-all">
            <!-- Section Foldable Header (Minimalist Dot Color) -->
            <div class="flex items-center justify-between gap-3 pb-4 border-b border-slate-100 cursor-pointer select-none group"
                onclick="toggleSection('sec-overview')">
                <div class="flex items-center space-x-3 min-w-0">
                    <span class="w-3.5 h-3.5 rounded-full bg-blue-600 ring-4 ring-blue-100 shrink-0"></span>
                    <div class="min-w-0">
                        <h2
                            class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight group-hover:text-blue-600 transition-colors">
                            {{ __('Executive Overview & Metrics') }}
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-0.5">
                            {{ __('Key election metrics and live voting progress indicators') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 shrink-0">
                    <span id="sec-overview-state"
                        class="text-xs sm:text-sm font-bold text-slate-500 group-hover:text-slate-800 transition hidden sm:inline-block">
                        {{ __('Fold Section') }}
                    </span>
                    <div
                        class="w-8 h-8 rounded-lg bg-slate-100 group-hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">
                        <svg id="sec-overview-chevron" class="w-4 h-4 transition-transform duration-300" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Section Content (Dedicated Full-Width Row Cards, Boomer-Readable, No Empty Grid Gaps) -->
            <div id="sec-overview-content" class="pt-4 sm:pt-6">
                <div class="flex flex-col space-y-3 sm:space-y-4">
                    <!-- Row 1: Voter Participation Rate & Turnout Progress -->
                    <div
                        class="p-4 sm:p-7 rounded-2xl sm:rounded-3xl bg-blue-50/80 border-2 border-blue-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 sm:gap-6">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-7 min-w-0">
                            <span id="stat-turnout-pct"
                                class="text-4xl sm:text-6xl lg:text-7xl font-black text-blue-700 font-mono tracking-tight shrink-0">{{ $metrics['turnout_pct'] }}%</span>
                            <div class="min-w-0">
                                <span
                                    class="text-[11px] sm:text-sm font-black uppercase tracking-wider text-blue-900 block">{{ __('Voter Participation Rate') }}</span>
                                <h3 class="text-lg sm:text-2xl font-black text-slate-900 mt-0.5 sm:mt-1">
                                    {{ __('Total Verified Turnout') }}</h3>
                                <p id="stat-total-votes-container" class="hidden" style="display: none !important;">
                                    <span id="stat-total-voted"
                                        class="text-blue-700 font-black text-base sm:text-xl">{{ $metrics['total_voted'] }}</span>
                                    {{ __('of') }} <span id="stat-total-voters"
                                        class="text-slate-950 font-black text-base sm:text-xl">{{ $metrics['total_voters'] }}</span>
                                    {{ __('Votes Cast') }}
                                </p>
                            </div>
                        </div>
                        <div class="w-full md:w-80 lg:w-96 shrink-0">
                            <div class="flex justify-between text-xs sm:text-sm font-black text-blue-950 mb-1.5 sm:mb-2">
                                <span>{{ __('Live Progress') }}</span>
                                <span class="text-blue-700 font-bold">✓ {{ __('Realtime Audited') }}</span>
                            </div>
                            <div
                                class="w-full bg-blue-200/90 rounded-full h-4 sm:h-5 overflow-hidden p-0.5 border border-blue-300">
                                <div id="stat-turnout-bar"
                                    class="bg-blue-600 h-3 sm:h-4 rounded-full transition-all duration-700 ease-out shadow-xs"
                                    style="width: {{ $metrics['turnout_pct'] }}%;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Election Status & Live Verification -->
                    <div
                        class="p-4 sm:p-7 rounded-2xl sm:rounded-3xl bg-emerald-50/80 border-2 border-emerald-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 sm:gap-5">
                        <div class="flex items-center space-x-3.5 sm:space-x-5 min-w-0">
                            <div
                                class="w-11 h-11 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl bg-emerald-100 border-2 border-emerald-300 flex items-center justify-center shrink-0">
                                <span
                                    class="w-4 h-4 sm:w-6 sm:h-6 rounded-full bg-emerald-500 ring-4 sm:ring-8 ring-emerald-200 animate-pulse"></span>
                            </div>
                            <div class="min-w-0">
                                <span
                                    class="text-[11px] sm:text-sm font-black uppercase tracking-wider text-emerald-900 block">{{ __('Election Status') }}</span>
                                <h3 class="text-lg sm:text-3xl font-black text-emerald-950 tracking-tight mt-0.5 sm:mt-1">
                                    {{ __('Active & Verified') }}</h3>
                                <p class="text-xs sm:text-base font-bold text-emerald-800 mt-0.5 sm:mt-1">✓
                                    {{ __('Voting Booth Ready • Secure Digital Ballot Transmission Active') }}</p>
                            </div>
                        </div>
                        <div class="shrink-0 flex items-center w-full sm:w-auto">
                            <span
                                class="w-full sm:w-auto justify-center px-4 py-2 sm:px-5 sm:py-2.5 rounded-xl sm:rounded-2xl text-xs sm:text-sm font-black bg-emerald-600 text-white shadow-xs tracking-wider uppercase flex items-center space-x-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-white animate-ping"></span>
                                <span>{{ __('LIVE VOTING OPEN') }}</span>
                            </span>
                        </div>
                    </div>

                    <!-- Row 3: Leading Frontrunners Split into 2 Dedicated Distinct Cards (Chairman & Supervisor Separated) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 sm:gap-5">
                        <!-- Card 1: Chairman Leader (Ketua) -->
                        <div onclick="previewLeader('ketua')"
                            class="p-4 sm:p-6 rounded-2xl sm:rounded-3xl bg-rose-50/80 border-2 border-rose-200 hover:border-red-400 shadow-sm hover:shadow-md transition-all cursor-pointer group/leader"
                            title="{{ __('Click to preview leader candidate') }}">
                            <!-- Top Card Header -->
                            <div class="flex items-center justify-between pb-3 border-b border-rose-200/80 gap-2 mb-3 sm:mb-4">
                                <div class="flex items-center space-x-2.5 min-w-0">
                                    <span class="w-3 h-3 sm:w-3.5 sm:h-3.5 rounded-full bg-red-600 ring-4 ring-red-100 animate-pulse shrink-0"></span>
                                    <div class="min-w-0">
                                        <span class="text-[10px] sm:text-xs font-black uppercase tracking-wider text-red-950 block">{{ __('Category 1: Cooperative Chairman') }}</span>
                                        <h4 class="text-xs sm:text-sm font-black text-red-700 tracking-tight">{{ __('Leading Frontrunner') }}</h4>
                                    </div>
                                </div>
                                <span class="text-[10px] sm:text-xs font-black text-red-800 bg-red-100/90 border border-red-300 px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-lg shrink-0">
                                    ✓ {{ __('Live Verified') }}
                                </span>
                            </div>
                            <!-- Candidate Details -->
                            <div class="flex items-center justify-between gap-3 sm:gap-4">
                                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0 flex-1">
                                    <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl overflow-hidden border-2 border-rose-300 group-hover/leader:border-red-500 group-hover/leader:scale-105 shadow-xs transition-all bg-white flex items-center justify-center shrink-0">
                                        <img id="leader-ketua-img" 
                                            src="{{ $metrics['leader_ketua_foto'] ?? 'https://ui-avatars.com/api/?name=Chairman&background=ef4444&color=ffffff&size=200' }}" 
                                            alt="Leader Chairman" 
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <span class="text-[10px] sm:text-xs font-bold text-rose-800/80 block uppercase tracking-wider">{{ __('Vote Leader') }}</span>
                                        <strong id="leader-ketua-text"
                                            class="text-base sm:text-xl font-black text-slate-950 block truncate group-hover/leader:text-red-700 transition-colors">{{ $metrics['leader_ketua'] ?: '(' . __('No Votes Yet') . ')' }}</strong>
                                    </div>
                                </div>
                                <div class="shrink-0 flex items-center text-xs font-bold text-red-600 bg-white/80 p-2 sm:px-3 sm:py-2 rounded-xl border border-rose-200 group-hover/leader:bg-red-600 group-hover/leader:text-white transition-colors">
                                    <span class="hidden sm:inline mr-1">{{ __('Visi & Misi') }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Supervisor Leader (Pengawas) -->
                        <div onclick="previewLeader('pengawas')"
                            class="p-4 sm:p-6 rounded-2xl sm:rounded-3xl bg-emerald-50/80 border-2 border-emerald-200 hover:border-emerald-400 shadow-sm hover:shadow-md transition-all cursor-pointer group/leader"
                            title="{{ __('Click to preview leader candidate') }}">
                            <!-- Top Card Header -->
                            <div class="flex items-center justify-between pb-3 border-b border-emerald-200/80 gap-2 mb-3 sm:mb-4">
                                <div class="flex items-center space-x-2.5 min-w-0">
                                    <span class="w-3 h-3 sm:w-3.5 sm:h-3.5 rounded-full bg-emerald-600 ring-4 ring-emerald-100 animate-pulse shrink-0"></span>
                                    <div class="min-w-0">
                                        <span class="text-[10px] sm:text-xs font-black uppercase tracking-wider text-emerald-950 block">{{ __('Category 2: Supervisory Board') }}</span>
                                        <h4 class="text-xs sm:text-sm font-black text-emerald-700 tracking-tight">{{ __('Leading Frontrunner') }}</h4>
                                    </div>
                                </div>
                                <span class="text-[10px] sm:text-xs font-black text-emerald-800 bg-emerald-100/90 border border-emerald-300 px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-lg shrink-0">
                                    ✓ {{ __('Live Verified') }}
                                </span>
                            </div>
                            <!-- Candidate Details -->
                            <div class="flex items-center justify-between gap-3 sm:gap-4">
                                <div class="flex items-center space-x-3 sm:space-x-4 min-w-0 flex-1">
                                    <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl overflow-hidden border-2 border-emerald-300 group-hover/leader:border-emerald-500 group-hover/leader:scale-105 shadow-xs transition-all bg-white flex items-center justify-center shrink-0">
                                        <img id="leader-pengawas-img" 
                                            src="{{ $metrics['leader_pengawas_foto'] ?? 'https://ui-avatars.com/api/?name=Supervisor&background=10b981&color=ffffff&size=200' }}" 
                                            alt="Leader Supervisor" 
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <span class="text-[10px] sm:text-xs font-bold text-emerald-800/80 block uppercase tracking-wider">{{ __('Vote Leader') }}</span>
                                        <strong id="leader-pengawas-text"
                                            class="text-base sm:text-xl font-black text-slate-950 block truncate group-hover/leader:text-emerald-700 transition-colors">{{ $metrics['leader_pengawas'] ?: '(' . __('No Votes Yet') . ')' }}</strong>
                                    </div>
                                </div>
                                <div class="shrink-0 flex items-center text-xs font-bold text-emerald-600 bg-white/80 p-2 sm:px-3 sm:py-2 rounded-xl border border-emerald-200 group-hover/leader:bg-emerald-600 group-hover/leader:text-white transition-colors">
                                    <span class="hidden sm:inline mr-1">{{ __('Visi & Misi') }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 4: Voting Deadline Countdown (Hidden if not configured, leaves zero gaps!) -->
                    <div id="deadline-container"
                        class="p-4 sm:p-7 rounded-2xl sm:rounded-3xl bg-rose-50/80 border-2 border-rose-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 sm:gap-6 {{ empty($metrics['voting_deadline']) ? 'hidden' : '' }}">
                        <div class="flex items-start sm:items-center space-x-3 sm:space-x-4 min-w-0">
                            <span
                                class="w-3.5 h-3.5 sm:w-4 sm:h-4 rounded-full bg-rose-500 ring-4 sm:ring-6 ring-rose-200 animate-pulse shrink-0 mt-1 sm:mt-0"></span>
                            <div class="min-w-0">
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <span
                                        class="text-[10px] sm:text-sm font-black uppercase tracking-wider text-rose-950 block">{{ __('Voting Deadline') }}</span>
                                    <span id="deadline-status-pill"
                                        class="px-2.5 sm:px-3 py-0.5 rounded-full text-[10px] sm:text-xs font-black uppercase bg-rose-200 text-rose-900 border border-rose-300">{{ __('Countdown Active') }}</span>
                                </div>
                                <h3 class="text-base sm:text-2xl font-black text-slate-950 tracking-tight mt-0.5 sm:mt-1">
                                    {{ __('Election Time Remaining') }}</h3>
                                <p id="deadline-info-text" class="text-xs sm:text-base text-slate-700 mt-0.5 sm:mt-1 font-bold">
                                    {{ __('Official Deadline:') }} <strong id="deadline-formatted-text"
                                        class="text-slate-950 font-black">{{ $metrics['deadline_formatted'] ?? '-' }}</strong>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center justify-center space-x-1 sm:space-x-1.5 font-mono text-base sm:text-2xl md:text-3xl font-black text-slate-950 shrink-0"
                            id="deadline-countdown-timer">
                            <span id="deadline-days"
                                class="bg-white border-2 border-rose-300 px-2 sm:px-3 py-1 sm:py-2 rounded-xl sm:rounded-2xl shadow-xs">00d</span>
                            <span class="text-rose-400 font-bold">:</span>
                            <span id="deadline-hours"
                                class="bg-white border-2 border-rose-300 px-2 sm:px-3 py-1 sm:py-2 rounded-xl sm:rounded-2xl shadow-xs">00h</span>
                            <span class="text-rose-400 font-bold">:</span>
                            <span id="deadline-mins"
                                class="bg-white border-2 border-rose-300 px-2 sm:px-3 py-1 sm:py-2 rounded-xl sm:rounded-2xl shadow-xs">00m</span>
                            <span class="text-rose-400 font-bold">:</span>
                            <span id="deadline-secs"
                                class="bg-white border-2 border-rose-300 px-2 sm:px-3 py-1 sm:py-2 rounded-xl sm:rounded-2xl text-rose-600 shadow-xs">00s</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- SECTION 2: ADVANCED COMPARATIVE BAR CHART (FOLDABLE)     -->
        <!-- ======================================================== -->
        <div id="sec-chart" class="scroll-sundul-init p-4 sm:p-7 rounded-2xl sm:rounded-3xl bg-white border border-slate-200 shadow-xs mb-6 sm:mb-8 transition-all">
            <!-- Section Foldable Header (Row 1 Full Width Title, Row 2 Filter Tabs) -->
            <div class="pb-4 sm:pb-5 border-b border-slate-100 space-y-3 sm:space-y-4">
                <!-- Row 1: Full-Width Title & Fold Trigger (No Truncation) -->
                <div class="flex items-center justify-between gap-3 sm:gap-4 cursor-pointer select-none group"
                    onclick="toggleSection('sec-chart')">
                    <div class="flex items-center space-x-2.5 sm:space-x-3 min-w-0">
                        <span class="w-3 sm:w-3.5 h-3 sm:h-3.5 rounded-full bg-indigo-600 ring-3 sm:ring-4 ring-indigo-100 shrink-0"></span>
                        <div>
                            <h2
                                class="text-base sm:text-2xl font-black text-slate-900 tracking-tight group-hover:text-indigo-600 transition-colors">
                                {{ __('Comparative Bar Chart of All Candidates') }}
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-0.5">
                                {{ __('Real-time vote count distribution and percentage for Chairman and Supervisory Board') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 shrink-0">
                        <span id="sec-chart-state"
                            class="text-xs sm:text-sm font-bold text-slate-500 group-hover:text-slate-800 transition hidden sm:inline-block">
                            {{ __('Fold Section') }}
                        </span>
                        <div
                            class="w-8 h-8 rounded-lg bg-slate-100 group-hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">
                            <svg id="sec-chart-chevron" class="w-4 h-4 transition-transform duration-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Filter Tabs on Dedicated Row (Large, Touch-Friendly Buttons) -->
                <div class="flex items-center overflow-x-auto pt-1 pb-1 scrollbar-none">
                    <div
                        class="inline-flex rounded-xl sm:rounded-2xl bg-slate-100 p-1 sm:p-1.5 text-xs sm:text-sm md:text-base font-extrabold shadow-inner gap-1 w-full sm:w-auto">
                        <button type="button" onclick="switchChartMode('all')" id="btn-chart-all"
                            class="flex-1 sm:flex-none text-center px-3 sm:px-6 py-2 sm:py-2.5 rounded-lg sm:rounded-xl bg-white text-slate-900 shadow-sm transition cursor-pointer whitespace-nowrap font-black">{{ __('All Candidates') }}</button>
                        <button type="button" onclick="switchChartMode('ketua')" id="btn-chart-ketua"
                            class="flex-1 sm:flex-none text-center px-3 sm:px-6 py-2 sm:py-2.5 rounded-lg sm:rounded-xl text-slate-600 hover:text-slate-900 transition cursor-pointer whitespace-nowrap font-extrabold">{{ __('Chairman Candidates') }}</button>
                        <button type="button" onclick="switchChartMode('pengawas')" id="btn-chart-pengawas"
                            class="flex-1 sm:flex-none text-center px-3 sm:px-6 py-2 sm:py-2.5 rounded-lg sm:rounded-xl text-slate-600 hover:text-slate-900 transition cursor-pointer whitespace-nowrap font-extrabold">{{ __('Supervisor Candidates') }}</button>
                    </div>
                </div>
            </div>

            <!-- Section Content (Enlarged Height For Boomer Readability) -->
            <div id="sec-chart-content" class="pt-4 sm:pt-6">
                <div class="relative w-full max-w-full mx-auto h-[380px] sm:h-[500px] md:h-[600px] overflow-hidden">
                    <canvas id="comparisonChart"></canvas>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- SECTION 3: PROCEDURAL VOTING GUIDE (3 QUICK FULL-WIDTH ROWS) -->
        <!-- ======================================================== -->
        <div id="sec-guide" class="scroll-sundul-init p-4 sm:p-7 rounded-2xl sm:rounded-3xl bg-slate-50 border border-slate-200 mb-6 sm:mb-8 transition-all">
            <!-- Section Foldable Header (Minimalist Dot Color + Text to Voice TTS) -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 sm:pb-4 border-b border-slate-200">
                <div class="flex items-center space-x-2.5 sm:space-x-3 min-w-0 cursor-pointer select-none group"
                    onclick="toggleSection('sec-guide')">
                    <span class="w-3 sm:w-3.5 h-3 sm:h-3.5 rounded-full bg-amber-500 ring-3 sm:ring-4 ring-amber-100 shrink-0"></span>
                    <div class="min-w-0">
                        <h2
                            class="text-base sm:text-2xl font-black text-slate-900 tracking-tight group-hover:text-amber-600 transition-colors">
                            {{ __('Voting Guide (3 Quick Steps)') }}
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-0.5">
                            {{ __('Simple 3-step procedural guide to cast your vote at the voting booth') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 shrink-0 justify-between sm:justify-end">
                    <!-- Text to Voice Button for Voting Guide -->
                    <button type="button" id="btn-guide-tts" onclick="toggleVotingGuideTTS(event)"
                        class="inline-flex items-center justify-center space-x-1.5 sm:space-x-2 px-3 py-1.5 sm:px-4 sm:py-2.5 rounded-xl sm:rounded-2xl bg-amber-100 hover:bg-amber-200 text-amber-950 font-black text-xs sm:text-sm border-2 border-amber-300 shadow-xs transition cursor-pointer">
                        <svg id="guide-tts-icon" class="w-4 h-4 sm:w-5 sm:h-5 text-amber-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" />
                        </svg>
                        <span id="guide-tts-label">{{ __('Listen to Guide (TTS)') }}</span>
                    </button>

                    <!-- Fold Trigger -->
                    <div class="flex items-center space-x-2 cursor-pointer select-none" onclick="toggleSection('sec-guide')">
                        <span id="sec-guide-state"
                            class="text-xs sm:text-sm font-bold text-slate-500 hover:text-slate-800 transition hidden sm:inline-block">
                            {{ __('Fold Section') }}
                        </span>
                        <div
                            class="w-8 h-8 rounded-lg bg-white hover:bg-slate-200 text-slate-600 border border-slate-200 flex items-center justify-center transition">
                            <svg id="sec-guide-chevron" class="w-4 h-4 transition-transform duration-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Content (3 Full-Width Row Cards, Enormous Step Badges & Description Text) -->
            <div id="sec-guide-content" class="pt-4 sm:pt-6">
                <div class="grid grid-cols-1 gap-3.5 sm:gap-5">
                    <!-- Card 1: Step 01 -->
                    <div
                        class="p-4 sm:p-7 rounded-2xl sm:rounded-3xl bg-white border-2 border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-5 min-w-0 flex-1">
                            <span
                                class="self-start sm:self-auto px-3.5 py-1.5 sm:px-6 sm:py-3 rounded-xl sm:rounded-2xl text-xs sm:text-lg font-black uppercase tracking-wider bg-blue-100 text-blue-900 border-2 border-blue-300 shrink-0 shadow-xs">
                                {{ __('Step 01') }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <h4 class="text-base sm:text-2xl font-black text-slate-950">{{ __('Tap RFID Member Card') }}
                                </h4>
                                <p class="text-xs sm:text-base md:text-xl text-slate-700 font-medium sm:font-bold mt-1 sm:mt-1.5 leading-relaxed">
                                    {{ __('Scan your registered cooperative RFID card on the voting booth reader to authenticate your identity and open the digital ballot.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Step 02 -->
                    <div
                        class="p-4 sm:p-7 rounded-2xl sm:rounded-3xl bg-white border-2 border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-5 min-w-0 flex-1">
                            <span
                                class="self-start sm:self-auto px-3.5 py-1.5 sm:px-6 sm:py-3 rounded-xl sm:rounded-2xl text-xs sm:text-lg font-black uppercase tracking-wider bg-indigo-100 text-indigo-900 border-2 border-indigo-300 shrink-0 shadow-xs">
                                {{ __('Step 02') }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <h4 class="text-base sm:text-2xl font-black text-slate-950">{{ __('Select Your Candidates') }}
                                </h4>
                                <p class="text-xs sm:text-base md:text-xl text-slate-700 font-medium sm:font-bold mt-1 sm:mt-1.5 leading-relaxed">
                                    {{ __('Touch candidate photo on the screen to choose 1 Chairman and 1 Supervisory Board candidate of your choice.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Step 03 -->
                    <div
                        class="p-4 sm:p-7 rounded-2xl sm:rounded-3xl bg-white border-2 border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-5 min-w-0 flex-1">
                            <span
                                class="self-start sm:self-auto px-3.5 py-1.5 sm:px-6 sm:py-3 rounded-xl sm:rounded-2xl text-xs sm:text-lg font-black uppercase tracking-wider bg-emerald-100 text-emerald-900 border-2 border-emerald-300 shrink-0 shadow-xs">
                                {{ __('Step 03') }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <h4 class="text-base sm:text-2xl font-black text-slate-950">{{ __('Submit & Complete Vote') }}
                                </h4>
                                <p class="text-xs sm:text-base md:text-xl text-slate-700 font-medium sm:font-bold mt-1 sm:mt-1.5 leading-relaxed">
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
        <section id="sec-ketua"
            class="scroll-sundul-init p-5 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-xs mb-8 transition-all">
            <!-- Section Foldable Header (Minimalist Dot Color) -->
            <div class="flex items-center justify-between gap-3 pb-4 border-b border-slate-200 cursor-pointer select-none group"
                onclick="toggleSection('sec-ketua')">
                <div class="flex items-center space-x-3 min-w-0">
                    <span class="w-3.5 h-3.5 rounded-full bg-red-600 ring-4 ring-red-100 shrink-0"></span>
                    <div class="min-w-0">
                        <h3
                            class="text-base sm:text-xl font-black text-slate-900 tracking-tight group-hover:text-red-600 transition-colors truncate">
                            {{ __('Chairman Election Comparison') }}
                        </h3>
                        <p class="text-xs text-red-700 font-bold truncate">
                            {{ __('Total:') }} <strong id="total-ketua-votes">{{ $metrics['total_suara_ketua'] }}</strong>
                            {{ __('Votes') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 shrink-0">
                    <span id="sec-ketua-state"
                        class="text-xs font-bold text-slate-500 group-hover:text-slate-800 transition hidden sm:inline-block">
                        {{ __('Fold Section') }}
                    </span>
                    <div
                        class="w-8 h-8 rounded-lg bg-slate-100 group-hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">
                        <svg id="sec-ketua-chevron" class="w-4 h-4 transition-transform duration-300" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Section Content -->
            <div id="sec-ketua-content" class="pt-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-7" id="ketua-cards-grid">
                    @foreach($ketuaResults as $ketua)
                        <div
                            class="rounded-3xl bg-white border-2 border-slate-200 hover:border-red-400 overflow-hidden shadow-2xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                            <div>
                                <!-- Foto Card: Responsive & Sharp (Clickable Headless Card Preview) -->
                                <div class="relative w-full h-[260px] sm:h-[300px] max-h-[300px] bg-slate-900 overflow-hidden cursor-pointer group/photo"
                                    onclick="openCandidateModal('ketua', '{{ addslashes($ketua['nama']) }}', '{{ $ketua['nomor_urut'] }}', `{{ addslashes($ketua['visi']) }}`, `{{ addslashes($ketua['misi']) }}`, `{{ addslashes($ketua['deskripsi']) }}`, '{{ $ketua['foto'] }}', '{{ $ketua['suara'] }}', '{{ $ketua['persen'] }}%')"
                                    title="{{ __('Click to preview candidate profile') }}">
                                    <img src="{{ $ketua['foto'] }}" alt="{{ $ketua['nama'] }}"
                                        class="w-full h-full object-cover object-top group-hover/photo:scale-105 transition-transform duration-500 ease-out">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-black/20 pointer-events-none">
                                    </div>

                                    <!-- Hover Preview Overlay Badge -->
                                    <div class="absolute inset-0 bg-slate-950/30 opacity-0 group-hover/photo:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                        <span class="px-4 py-2 rounded-2xl bg-white/95 text-slate-950 text-xs sm:text-sm font-black shadow-xl backdrop-blur-xs flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>{{ __('Preview Profile') }}</span>
                                        </span>
                                    </div>

                                    <!-- Badge Nomor Urut Absolute di Kiri Atas -->
                                    <div class="absolute top-3.5 left-3.5 z-10">
                                        <div
                                            class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl bg-white text-slate-950 border-2 border-slate-300 shadow-xl flex items-center justify-center text-lg sm:text-2xl font-black ring-4 ring-black/15">
                                            {{ $ketua['nomor_urut'] }}
                                        </div>
                                    </div>

                                    <!-- Leading Star Badge di Kanan Atas -->
                                    <div class="absolute top-3.5 right-3.5 z-10">
                                        <span id="ketua-leader-badge-{{ $ketua['nik'] }}"
                                            class="{{ $ketua['is_leader'] && $ketua['suara'] > 0 ? '' : 'hidden' }} px-3 py-1 rounded-full text-xs font-black bg-amber-400 text-amber-950 border border-amber-300 shadow-md">
                                            ⭐ {{ __('Leading') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Identitas Calon -->
                                <div class="p-5 pb-3">
                                    <h3 onclick="openCandidateModal('ketua', '{{ addslashes($ketua['nama']) }}', '{{ $ketua['nomor_urut'] }}', `{{ addslashes($ketua['visi']) }}`, `{{ addslashes($ketua['misi']) }}`, `{{ addslashes($ketua['deskripsi']) }}`, '{{ $ketua['foto'] }}', '{{ $ketua['suara'] }}', '{{ $ketua['persen'] }}%')"
                                        class="text-xl sm:text-2xl font-black text-slate-950 tracking-tight leading-snug cursor-pointer hover:text-red-600 transition-colors">
                                        {{ $ketua['nama'] }}</h3>
                                    <div
                                        class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-lg bg-red-50 border border-red-200 text-xs sm:text-sm font-mono font-bold text-red-800 mt-1.5">
                                        <span>NIK:</span>
                                        <span>{{ $ketua['nik'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-5 pt-0">
                                <!-- Votes & Percentage Bar -->
                                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 mb-3.5">
                                    <div class="flex items-baseline justify-between text-xs font-bold mb-1.5">
                                        <span id="ketua-vote-count-{{ $ketua['nik'] }}"
                                            class="text-slate-800 font-extrabold text-sm">{{ $ketua['suara'] }}
                                            {{ __('Votes') }}</span>
                                        <span id="ketua-pct-val-{{ $ketua['nik'] }}"
                                            class="text-red-700 font-black text-base">{{ $ketua['persen'] }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                                        <div id="ketua-bar-fill-{{ $ketua['nik'] }}"
                                            class="bg-red-600 h-2.5 rounded-full transition-all duration-700 ease-out"
                                            style="width: {{ $ketua['persen'] }}%;"></div>
                                    </div>
                                </div>

                                <!-- Button View Details Modal -->
                                <button type="button"
                                    onclick="openCandidateModal('ketua', '{{ addslashes($ketua['nama']) }}', '{{ $ketua['nomor_urut'] }}', `{{ addslashes($ketua['visi']) }}`, `{{ addslashes($ketua['misi']) }}`, `{{ addslashes($ketua['deskripsi']) }}`, '{{ $ketua['foto'] }}', '{{ $ketua['suara'] }}', '{{ $ketua['persen'] }}%')"
                                    class="w-full py-3 px-4 rounded-2xl bg-gradient-to-r from-red-600 via-rose-600 to-red-700 hover:from-red-700 hover:to-rose-800 text-white font-extrabold text-xs sm:text-sm shadow-md hover:shadow-lg transition-all flex items-center justify-center space-x-2 cursor-pointer">
                                    <span>{{ __('View Profile & Vision') }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                    </svg>
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
        <section id="sec-pengawas"
            class="scroll-sundul-init p-5 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-xs mb-8 transition-all">
            <!-- Section Foldable Header (Minimalist Dot Color) -->
            <div class="flex items-center justify-between gap-3 pb-4 border-b border-slate-200 cursor-pointer select-none group"
                onclick="toggleSection('sec-pengawas')">
                <div class="flex items-center space-x-3 min-w-0">
                    <span class="w-3.5 h-3.5 rounded-full bg-emerald-600 ring-4 ring-emerald-100 shrink-0"></span>
                    <div class="min-w-0">
                        <h3
                            class="text-base sm:text-xl font-black text-slate-900 tracking-tight group-hover:text-emerald-600 transition-colors truncate">
                            {{ __('Supervisory Board Comparison') }}
                        </h3>
                        <p class="text-xs text-emerald-800 font-bold truncate">
                            {{ __('Total:') }} <strong
                                id="total-pengawas-votes">{{ $metrics['total_suara_pengawas'] }}</strong> {{ __('Votes') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 shrink-0">
                    <span id="sec-pengawas-state"
                        class="text-xs font-bold text-slate-500 group-hover:text-slate-800 transition hidden sm:inline-block">
                        {{ __('Fold Section') }}
                    </span>
                    <div
                        class="w-8 h-8 rounded-lg bg-slate-100 group-hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">
                        <svg id="sec-pengawas-chevron" class="w-4 h-4 transition-transform duration-300" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Section Content -->
            <div id="sec-pengawas-content" class="pt-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-7" id="pengawas-cards-grid">
                    @foreach($pengawasResults as $pengawas)
                        <div
                            class="rounded-3xl bg-white border-2 border-slate-200 hover:border-emerald-400 overflow-hidden shadow-2xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                            <div>
                                <!-- Foto Card: Responsive & Sharp (Clickable Headless Card Preview) -->
                                <div class="relative w-full h-[260px] sm:h-[300px] max-h-[300px] bg-slate-900 overflow-hidden cursor-pointer group/photo"
                                    onclick="openCandidateModal('pengawas', '{{ addslashes($pengawas['nama']) }}', '{{ $pengawas['nomor_urut'] }}', `{{ addslashes($pengawas['visi']) }}`, `{{ addslashes($pengawas['misi']) }}`, `{{ addslashes($pengawas['deskripsi']) }}`, '{{ $pengawas['foto'] }}', '{{ $pengawas['suara'] }}', '{{ $pengawas['persen'] }}%')"
                                    title="{{ __('Click to preview candidate profile') }}">
                                    <img src="{{ $pengawas['foto'] }}" alt="{{ $pengawas['nama'] }}"
                                        class="w-full h-full object-cover object-top group-hover/photo:scale-105 transition-transform duration-500 ease-out">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-black/20 pointer-events-none">
                                    </div>

                                    <!-- Hover Preview Overlay Badge -->
                                    <div class="absolute inset-0 bg-slate-950/30 opacity-0 group-hover/photo:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                        <span class="px-4 py-2 rounded-2xl bg-white/95 text-slate-950 text-xs sm:text-sm font-black shadow-xl backdrop-blur-xs flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>{{ __('Preview Profile') }}</span>
                                        </span>
                                    </div>

                                    <!-- Badge Nomor Urut Absolute di Kiri Atas -->
                                    <div class="absolute top-3.5 left-3.5 z-10">
                                        <div
                                            class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl bg-white text-slate-950 border-2 border-slate-300 shadow-xl flex items-center justify-center text-lg sm:text-2xl font-black ring-4 ring-black/15">
                                            {{ $pengawas['nomor_urut'] }}
                                        </div>
                                    </div>

                                    <!-- Leading Star Badge di Kanan Atas -->
                                    <div class="absolute top-3.5 right-3.5 z-10">
                                        <span id="pengawas-leader-badge-{{ $pengawas['nik'] }}"
                                            class="{{ $pengawas['is_leader'] && $pengawas['suara'] > 0 ? '' : 'hidden' }} px-3 py-1 rounded-full text-xs font-black bg-amber-400 text-amber-950 border border-amber-300 shadow-md">
                                            ⭐ {{ __('Leading') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Identitas Calon -->
                                <div class="p-5 pb-3">
                                    <h3 onclick="openCandidateModal('pengawas', '{{ addslashes($pengawas['nama']) }}', '{{ $pengawas['nomor_urut'] }}', `{{ addslashes($pengawas['visi']) }}`, `{{ addslashes($pengawas['misi']) }}`, `{{ addslashes($pengawas['deskripsi']) }}`, '{{ $pengawas['foto'] }}', '{{ $pengawas['suara'] }}', '{{ $pengawas['persen'] }}%')"
                                        class="text-xl sm:text-2xl font-black text-slate-950 tracking-tight leading-snug cursor-pointer hover:text-emerald-600 transition-colors">
                                        {{ $pengawas['nama'] }}</h3>
                                    <div
                                        class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 border border-emerald-200 text-xs sm:text-sm font-mono font-bold text-emerald-800 mt-1.5">
                                        <span>NIK:</span>
                                        <span>{{ $pengawas['nik'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-5 pt-0">
                                <!-- Votes & Percentage Bar -->
                                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 mb-3.5">
                                    <div class="flex items-baseline justify-between text-xs font-bold mb-1.5">
                                        <span id="pengawas-vote-count-{{ $pengawas['nik'] }}"
                                            class="text-slate-800 font-extrabold text-sm">{{ $pengawas['suara'] }}
                                            {{ __('Votes') }}</span>
                                        <span id="pengawas-pct-val-{{ $pengawas['nik'] }}"
                                            class="text-emerald-700 font-black text-base">{{ $pengawas['persen'] }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                                        <div id="pengawas-bar-fill-{{ $pengawas['nik'] }}"
                                            class="bg-emerald-600 h-2.5 rounded-full transition-all duration-700 ease-out"
                                            style="width: {{ $pengawas['persen'] }}%;"></div>
                                    </div>
                                </div>

                                <!-- Button View Details Modal -->
                                <button type="button"
                                    onclick="openCandidateModal('pengawas', '{{ addslashes($pengawas['nama']) }}', '{{ $pengawas['nomor_urut'] }}', `{{ addslashes($pengawas['visi']) }}`, `{{ addslashes($pengawas['misi']) }}`, `{{ addslashes($pengawas['deskripsi']) }}`, '{{ $pengawas['foto'] }}', '{{ $pengawas['suara'] }}', '{{ $pengawas['persen'] }}%')"
                                    class="w-full py-3 px-4 rounded-2xl bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 hover:from-emerald-700 hover:to-teal-800 text-white font-extrabold text-xs sm:text-sm shadow-md hover:shadow-lg transition-all flex items-center justify-center space-x-2 cursor-pointer">
                                    <span>{{ __('View Profile & Vision') }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

    </div>

    <!-- ======================================================== -->
    <!-- HEADLESS CARD PREVIEW MODAL (CANDIDATE VISUAL & DETAILS) -->
    <!-- ======================================================== -->
    <div id="candidate-detail-modal"
        class="fixed inset-0 z-50 hidden bg-slate-950/75 backdrop-blur-sm flex items-center justify-center p-3 sm:p-5"
        onclick="if (event.target === this) closeCandidateModal()">
        <div
            class="bg-white rounded-3xl max-w-2xl w-full shadow-2xl overflow-hidden relative max-h-[92vh] flex flex-col border border-slate-200">
            <!-- Floating Close Button on Top Right -->
            <button onclick="closeCandidateModal()"
                class="absolute top-4 right-4 z-30 w-11 h-11 rounded-full bg-slate-900/60 hover:bg-slate-900 text-white backdrop-blur-md flex items-center justify-center text-lg font-black transition cursor-pointer shadow-lg hover:scale-105 active:scale-95"
                title="{{ __('Close') }}">✕</button>

            <!-- Headless Hero Header (Full-Bleed Visual Showcase) -->
            <div class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950 p-6 sm:p-8 text-white overflow-hidden shrink-0">
                <!-- Ambient Glow Elements -->
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-12 -left-12 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col sm:flex-row items-center sm:items-start gap-5 sm:gap-6 text-center sm:text-left">
                    <!-- Large Photo with Number Badge -->
                    <div class="relative shrink-0">
                        <img id="detail-modal-foto" src="" alt="Foto Kandidat"
                            class="w-28 h-28 sm:w-36 sm:h-36 rounded-3xl object-cover object-top border-4 border-white/90 shadow-2xl ring-4 ring-white/10">
                        <span id="detail-modal-no"
                            class="absolute -bottom-2.5 -right-2.5 px-3 py-1 rounded-2xl bg-white text-slate-950 text-xs sm:text-sm font-black shadow-lg border-2 border-slate-200">
                            No. 01
                        </span>
                    </div>

                    <!-- Candidate Identification -->
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-center sm:justify-start space-x-2 mb-2">
                            <span id="detail-modal-badge"
                                class="px-3 py-1 rounded-xl text-xs font-black uppercase tracking-wider bg-red-600 text-white shadow-sm">
                                {{ __('Candidate') }}
                            </span>
                            <span class="text-xs font-bold text-slate-300">
                                • {{ __('Verified Candidate') }}
                            </span>
                        </div>

                        <h3 id="detail-modal-nama"
                            class="text-2xl sm:text-3xl font-black text-white tracking-tight leading-tight">
                            -
                        </h3>

                        <!-- Tally & Audio TTS Action Row -->
                        <div class="flex items-center justify-center sm:justify-start space-x-2.5 mt-3.5 flex-wrap gap-y-2">
                            <span id="detail-modal-tally"
                                class="px-3.5 py-1.5 rounded-xl text-xs sm:text-sm font-black bg-white/15 text-white border border-white/20 backdrop-blur-xs shadow-xs">
                                0 {{ __('Votes') }} (0%)
                            </span>

                            <!-- Text-to-Speech (TTS) Voice Button -->
                            <button id="btn-modal-tts" onclick="toggleModalTTS()" type="button"
                                class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-500 active:bg-blue-700 text-white font-extrabold text-xs transition cursor-pointer shadow-md">
                                <svg id="tts-icon-speaker" class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" />
                                </svg>
                                <span id="tts-btn-label">{{ __('Listen to Audio (TTS)') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Headless Card Body (Scrollable Details) -->
            <div class="p-6 sm:p-8 space-y-5 overflow-y-auto max-h-[52vh]">
                <!-- Profil Singkat / Track Record -->
                <div class="p-5 rounded-2xl bg-slate-50 border-2 border-slate-200">
                    <h4 class="text-xs uppercase font-black tracking-wider text-slate-500 mb-2">
                        {{ __('Background / Brief Profile:') }}
                    </h4>
                    <p id="detail-modal-deskripsi"
                        class="text-slate-800 text-base sm:text-lg leading-relaxed font-semibold">
                    </p>
                </div>

                <!-- Visi -->
                <div class="p-5 rounded-2xl bg-blue-50/70 border-2 border-blue-200/80">
                    <h4 class="text-xs uppercase font-black tracking-wider text-blue-900 mb-2">
                        {{ __('Vision') }}:
                    </h4>
                    <div id="detail-modal-visi"
                        class="text-slate-800 text-base sm:text-lg leading-relaxed whitespace-pre-line font-semibold">
                    </div>
                </div>

                <!-- Misi -->
                <div class="p-5 rounded-2xl bg-emerald-50/70 border-2 border-emerald-200/80">
                    <h4 class="text-xs uppercase font-black tracking-wider text-emerald-950 mb-2">
                        {{ __('Mission') }}:
                    </h4>
                    <div id="detail-modal-misi"
                        class="text-slate-800 text-base sm:text-lg leading-relaxed whitespace-pre-line font-semibold">
                    </div>
                </div>
            </div>

            <!-- Headless Card Footer -->
            <div class="p-4 px-6 sm:px-8 bg-slate-50 border-t border-slate-200 flex items-center justify-between shrink-0">
                <span class="text-xs font-bold text-slate-500 hidden sm:inline-block">
                    {{ __('Tap anywhere outside or press ESC to close') }}
                </span>
                <button onclick="closeCandidateModal()"
                    class="w-full sm:w-auto px-7 py-2.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-sm transition cursor-pointer">
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

            document.addEventListener('DOMContentLoaded', function () {
                initComparisonChart();
                initLiveSSE();

                let resizeTimer;
                window.addEventListener('resize', function () {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(() => {
                        if (comparisonChart) {
                            const isMobile = window.innerWidth < 640;
                            comparisonChart.options.layout.padding.top = isMobile ? 24 : 36;
                            comparisonChart.options.layout.padding.bottom = isMobile ? 48 : 110;
                            comparisonChart.options.layout.padding.left = isMobile ? 8 : 16;
                            comparisonChart.options.layout.padding.right = isMobile ? 8 : 16;
                            const { datasets } = generateChartData(currentChartMode);
                            comparisonChart.data.datasets = datasets;
                            comparisonChart.resize();
                            comparisonChart.update();
                        }
                    }, 100);
                });
            });

            // Custom Chart.js Plugin: Candidate Avatar Portraits on X-Axis
            // Custom Chart.js Plugin: Candidate Avatar Portraits on X-Axis with Anti-Collision Logic
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

                    if (!candidates.length) return;

                    const chartW = chart.width || (chart.chartArea ? chart.chartArea.right - chart.chartArea.left : 400);
                    // Determine horizontal space available per candidate
                    let colWidth = 100;
                    if (candidates.length > 1 && !isNaN(x.getPixelForTick(1)) && !isNaN(x.getPixelForTick(0))) {
                        colWidth = Math.abs(x.getPixelForTick(1) - x.getPixelForTick(0));
                    } else if (candidates.length > 0) {
                        colWidth = chartW / candidates.length;
                    }

                    const isUltraCompact = colWidth < 55 || chartW < 380;
                    const isCompact = colWidth < 78 || chartW < 640;

                    const size = isUltraCompact ? 28 : (isCompact ? 34 : 44);
                    const badgeRadius = isUltraCompact ? 5.5 : (isCompact ? 7 : 8.5);
                    const badgeFontSize = isUltraCompact ? '9px' : (isCompact ? '10px' : '12px');
                    const nameFontSize = isUltraCompact ? 9 : (isCompact ? 10.5 : 13);
                    const lineHeight = nameFontSize + 3.5;
                    const maxTextWidth = Math.max(colWidth - 4, 30);

                    candidates.forEach((cand, idx) => {
                        const xPos = x.getPixelForTick(idx);
                        if (isNaN(xPos)) return;
                        const yPos = y.bottom + 8;

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
                        ctx.lineWidth = isUltraCompact ? 2 : 2.5;
                        ctx.strokeStyle = cand.type === 'ketua' ? '#dc2626' : '#059669';
                        ctx.stroke();
                        ctx.restore();

                        // Mini number badge pill
                        ctx.save();
                        ctx.beginPath();
                        ctx.arc(xPos + size / 2 - 2, yPos + badgeRadius, badgeRadius, 0, Math.PI * 2);
                        ctx.fillStyle = '#ffffff';
                        ctx.fill();
                        ctx.lineWidth = 1.8;
                        ctx.strokeStyle = cand.type === 'ketua' ? '#dc2626' : '#059669';
                        ctx.stroke();
                        ctx.font = `900 ${badgeFontSize} "Plus Jakarta Sans", sans-serif`;
                        ctx.fillStyle = '#0f172a';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.fillText(cand.nomor_urut, xPos + size / 2 - 2, yPos + badgeRadius);
                        ctx.restore();

                        // Candidate name: Completely hidden on mobile as requested to prevent collisions!
                        const isMobile = window.innerWidth < 640 || chartW < 540;
                        if (!isMobile) {
                            ctx.save();
                            ctx.font = `900 ${nameFontSize}px "Plus Jakarta Sans", sans-serif`;
                            ctx.fillStyle = '#0f172a';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'top';

                            const fullName = (cand.nama || '').trim();
                            const words = fullName.split(/\s+/);
                            let line1 = '';
                            let line2 = '';

                            if (words.length === 1) {
                                line1 = words[0];
                            } else if (words.length === 2) {
                                line1 = words[0];
                                line2 = words[1];
                            } else {
                                const mid = Math.ceil(words.length / 2);
                                line1 = words.slice(0, mid).join(' ');
                                line2 = words.slice(mid).join(' ');
                            }

                            const textStartY = yPos + size + 6;
                            ctx.fillText(line1, xPos, textStartY, maxTextWidth);
                            if (line2) {
                                ctx.fillText(line2, xPos, textStartY + lineHeight, maxTextWidth);
                            }
                            ctx.restore();
                        }
                    });
                }
            };

            // 1. Inisialisasi Advanced Comparative Bar Chart (Chart.js) dengan Avatar Foto
            function initComparisonChart() {
                if (typeof Chart === 'undefined') return;
                const ctx = document.getElementById('comparisonChart').getContext('2d');

                const { labels, datasets } = generateChartData(currentChartMode);
                const isMobile = window.innerWidth < 640;

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
                                top: isMobile ? 24 : 36,
                                bottom: isMobile ? 48 : 110, // 48px on mobile (avatars only), 110px on desktop (avatars + names)
                                left: isMobile ? 8 : 16,
                                right: isMobile ? 8 : 16
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
                                    padding: isMobile ? 12 : 26,
                                    font: { family: 'Plus Jakarta Sans', weight: '900', size: isMobile ? 12 : 16 },
                                    usePointStyle: true,
                                    pointStyle: 'rect',
                                    boxWidth: isMobile ? 12 : 20,
                                    boxHeight: isMobile ? 12 : 20
                                }
                            },
                            tooltip: {
                                backgroundColor: '#0f172a',
                                titleFont: { family: 'Plus Jakarta Sans', weight: '900', size: 14 },
                                bodyFont: { family: 'Plus Jakarta Sans', weight: 'bold', size: 13 },
                                padding: 14,
                                cornerRadius: 14,
                                boxPadding: 8,
                                callbacks: {
                                    label: function (context) {
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
                                    font: { family: 'Plus Jakarta Sans', weight: '900', size: isMobile ? 11 : 14 },
                                    color: '#334155'
                                },
                                grid: {
                                    color: '#f1f5f9'
                                }
                            },
                            x: {
                                barPercentage: isMobile ? 0.75 : 0.65,
                                categoryPercentage: isMobile ? 0.75 : 0.65,
                                ticks: {
                                    display: false // Rendered as circular photo avatars by candidateAvatarPlugin
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
                const isMobile = window.innerWidth < 640;
                const barW = isMobile ? (mode === 'all' ? 16 : 24) : 32;
                const maxBarW = isMobile ? (mode === 'all' ? 22 : 36) : 44;

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
                            barThickness: barW,
                            maxBarThickness: maxBarW,
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
                            barThickness: barW,
                            maxBarThickness: maxBarW,
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
                                barThickness: barW,
                                maxBarThickness: maxBarW,
                                grouped: false,
                            },
                            {
                                label: '{{ __('Category 2: Supervisory Board') }}',
                                data: pengawasVotes,
                                backgroundColor: 'rgba(16, 185, 129, 0.85)',
                                hoverBackgroundColor: 'rgba(5, 150, 105, 0.95)',
                                borderWidth: 0,
                                borderRadius: 0,
                                barThickness: barW,
                                maxBarThickness: maxBarW,
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

                    source.onopen = function () {
                        if (badge) badge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300';
                        if (text) text.innerText = 'Live SSE';
                    };

                    source.onmessage = function (event) {
                        try {
                            const data = JSON.parse(event.data);
                            updateLiveDisplay(data);
                        } catch (e) {
                            console.error('SSE Error:', e);
                        }
                    };

                    source.onerror = function () {
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
                if (data.metrics.leader_ketua_foto) {
                    const elKetuaImg = document.getElementById('leader-ketua-img');
                    if (elKetuaImg) elKetuaImg.src = data.metrics.leader_ketua_foto;
                }
                if (data.metrics.leader_pengawas) {
                    document.getElementById('leader-pengawas-text').innerText = data.metrics.leader_pengawas;
                }
                if (data.metrics.leader_pengawas_foto) {
                    const elPengawasImg = document.getElementById('leader-pengawas-img');
                    if (elPengawasImg) elPengawasImg.src = data.metrics.leader_pengawas_foto;
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

                const headerTimerEl = document.getElementById('header-deadline-timer');
                if (diff <= 0) {
                    if (headerTimerEl) headerTimerEl.innerText = '{{ __('Berakhir') }}';
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

                if (headerTimerEl) {
                    headerTimerEl.innerText = (days > 0 ? days + 'd ' : '') + String(hours).padStart(2, '0') + ':' + String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
                }
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
                    if (window.gsap) {
                        window.gsap.fromTo(content, { opacity: 0, y: -14 }, { opacity: 1, y: 0, duration: 0.4, ease: 'back.out(1.5)' });
                    }
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

            function previewLeader(type) {
                if (type === 'ketua') {
                    const leader = rawKetuaData.find(k => k.is_leader) || rawKetuaData[0];
                    if (leader) {
                        openCandidateModal('ketua', leader.nama, leader.nomor_urut, leader.visi, leader.misi, leader.deskripsi, leader.foto, leader.suara, leader.persen + '%');
                    }
                } else if (type === 'pengawas') {
                    const leader = rawPengawasData.find(p => p.is_leader) || rawPengawasData[0];
                    if (leader) {
                        openCandidateModal('pengawas', leader.nama, leader.nomor_urut, leader.visi, leader.misi, leader.deskripsi, leader.foto, leader.suara, leader.persen + '%');
                    }
                }
            }

            function openCandidateModal(kategoriType, nama, nomor, visi, misi, deskripsi, foto, suara, persen) {
                if (window.SoundEffects) window.SoundEffects.modal();

                const isKetua = (kategoriType === 'ketua' || String(kategoriType).toLowerCase().includes('ketua') || String(kategoriType).toLowerCase().includes('chairman'));
                const localizedKategori = isKetua
                    ? '{{ __('Category 1: Cooperative Chairman') }}'
                    : '{{ __('Category 2: Supervisory Board') }}';

                activeModalCandidate = { kategori: localizedKategori, kategoriType, nama, nomor, visi, misi, deskripsi };
                stopTTS();

                document.getElementById('detail-modal-foto').src = foto;
                document.getElementById('detail-modal-nama').innerText = nama;
                document.getElementById('detail-modal-no').innerText = 'No. ' + nomor;
                document.getElementById('detail-modal-badge').innerText = localizedKategori;
                document.getElementById('detail-modal-badge').className = isKetua
                    ? 'px-3 py-1 rounded-xl text-xs font-black uppercase tracking-wider bg-red-600 text-white shadow-sm'
                    : 'px-3 py-1 rounded-xl text-xs font-black uppercase tracking-wider bg-emerald-600 text-white shadow-sm';
                document.getElementById('detail-modal-tally').innerText = `${suara} {{ __('Votes') }} (${persen})`;

                document.getElementById('detail-modal-deskripsi').innerText = deskripsi || '{{ __('Official registered candidate.') }}';
                document.getElementById('detail-modal-visi').innerText = visi || '-';
                document.getElementById('detail-modal-misi').innerText = misi || '-';

                document.getElementById('candidate-detail-modal').classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeCandidateModal() {
                if (window.SoundEffects) window.SoundEffects.click();
                stopTTS();
                document.getElementById('candidate-detail-modal').classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            // Keyboard shortcut: Escape to close modal
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const modal = document.getElementById('candidate-detail-modal');
                    if (modal && !modal.classList.contains('hidden')) {
                        closeCandidateModal();
                    }
                }
            });

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

                utterance.onstart = function () {
                    isSpeaking = true;
                    updateTTSButtonUI(true);
                };

                utterance.onend = function () {
                    isSpeaking = false;
                    updateTTSButtonUI(false);
                };

                utterance.onerror = function () {
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

            // Overlay Loading 3D Rotating ID Card (Three.js WebGL)
            let overlayCard3DInstance = null;
            function startOverlay3DCard() {
                if (typeof window.initHero3DCard === 'function') {
                    overlayCard3DInstance = window.initHero3DCard('overlay-card-3d-canvas', { autoRotate: true, rotationSpeed: 1.8 });
                } else {
                    setTimeout(startOverlay3DCard, 50);
                }
            }

            // Hero Interactive 3D Rectangular ID Card Pass (Three.js WebGL)
            let heroCard3DInstance = null;
            function startHero3DCard() {
                if (typeof window.initHero3DCard === 'function') {
                    heroCard3DInstance = window.initHero3DCard('hero-card-3d-canvas');
                } else {
                    setTimeout(startHero3DCard, 60);
                }
            }

            // 1. Initial Render Fluid Welcoming Loading Overlay (2 Seconds)
            function runInitialLoadingOverlay() {
                const overlay = document.getElementById('welcome-loading-overlay');
                const bar = document.getElementById('welcome-loading-bar');
                if (!overlay || !bar) {
                    startHero3DCard();
                    triggerEntranceSundul();
                    return;
                }

                // Mount 3D spinning ID card in the white loading overlay
                startOverlay3DCard();

                // Trigger smooth 2-second progress bar fill
                requestAnimationFrame(() => {
                    bar.style.width = '100%';
                });

                // Exactly 2.0 seconds later, play welcoming audio & fluidly reveal page
                setTimeout(() => {
                    if (window.SoundEffects && typeof window.SoundEffects.welcome === 'function') {
                        window.SoundEffects.welcome();
                    }

                    if (window.gsap) {
                        window.gsap.to(overlay, {
                            yPercent: -100,
                            duration: 0.85,
                            ease: 'power4.inOut',
                            onComplete: () => {
                                overlay.style.display = 'none';
                                if (overlayCard3DInstance && typeof overlayCard3DInstance.destroy === 'function') {
                                    overlayCard3DInstance.destroy();
                                    overlayCard3DInstance = null;
                                }
                                startHero3DCard();
                                triggerEntranceSundul();
                            }
                        });
                    } else {
                        overlay.style.opacity = '0';
                        overlay.style.transform = 'translateY(-100%)';
                        setTimeout(() => {
                            overlay.style.display = 'none';
                            if (overlayCard3DInstance && typeof overlayCard3DInstance.destroy === 'function') {
                                overlayCard3DInstance.destroy();
                                overlayCard3DInstance = null;
                            }
                            startHero3DCard();
                            triggerEntranceSundul();
                        }, 800);
                    }
                }, 2000);
            }

            // 2. GSAP Entrance "Sundul" (Spring Bumps) & Staggered Section Reveals
            function triggerEntranceSundul() {
                if (!window.gsap) {
                    setupScrollSundul();
                    return;
                }

                // Header spring entrance
                window.gsap.fromTo('#main-header',
                    { y: -35, opacity: 0 },
                    { y: 0, opacity: 1, duration: 0.75, ease: 'back.out(1.6)', clearProps: 'transform,opacity' }
                );

                // Voting Hero Card "sundul" (elastic spring up)
                window.gsap.fromTo('#voting-hero-card',
                    { y: 55, scale: 0.94, opacity: 0 },
                    { y: 0, scale: 1.0, opacity: 1, duration: 0.9, delay: 0.12, ease: 'back.out(1.7)', clearProps: 'transform,opacity' }
                );

                // Overview section spring
                window.gsap.fromTo('#sec-overview',
                    { y: 60, scale: 0.98, opacity: 0 },
                    { y: 0, scale: 1.0, opacity: 1, duration: 0.85, delay: 0.24, ease: 'back.out(1.5)', clearProps: 'transform,opacity' }
                );

                setupScrollSundul();
            }

            // 3. Anchor Section Hit & Scroll "Sundul" Animations (Initially hidden, springs up on scroll)
            function setupScrollSundul() {
                const sections = document.querySelectorAll('#sec-chart, #sec-guide, #sec-ketua, #sec-pengawas');
                if (!('IntersectionObserver' in window)) {
                    sections.forEach(sec => sec.classList.remove('scroll-sundul-init'));
                    return;
                }

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting && !entry.target.dataset.sundulAnimated) {
                            entry.target.dataset.sundulAnimated = 'true';
                            entry.target.classList.remove('scroll-sundul-init');
                            if (window.gsap) {
                                window.gsap.fromTo(entry.target, 
                                    { y: 65, scale: 0.95, opacity: 0 }, 
                                    { y: 0, scale: 1.0, opacity: 1, duration: 0.75, ease: 'back.out(1.6)', clearProps: 'transform,opacity' }
                                );
                            }
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.05, rootMargin: '0px 0px -40px 0px' });

                sections.forEach(sec => observer.observe(sec));

                // Safe fallback after 4s to ensure accessibility
                setTimeout(() => {
                    sections.forEach(sec => sec.classList.remove('scroll-sundul-init'));
                }, 4000);
            }

            window.scrollToGuideSection = function(e) {
                if (e) e.preventDefault();
                const guideSec = document.getElementById('sec-guide');
                if (!guideSec) return;

                // Expand guide section if it was folded
                const content = document.getElementById('sec-guide-content');
                if (content && content.classList.contains('hidden')) {
                    toggleSection('sec-guide');
                }

                // Immediately clear init hidden state
                guideSec.classList.remove('scroll-sundul-init');
                guideSec.dataset.sundulAnimated = 'true';

                // Smooth scroll to guide
                guideSec.scrollIntoView({ behavior: 'smooth', block: 'start' });

                // Highlight sundul bounce effect
                if (window.gsap) {
                    window.gsap.fromTo(guideSec,
                        { scale: 0.96 },
                        { scale: 1.0, duration: 0.6, ease: 'back.out(2)', clearProps: 'transform' }
                    );
                }
            };

            // 4. Voting Guide Text-to-Speech (TTS) Engine
            let isGuideSpeaking = false;
            window.toggleVotingGuideTTS = function(e) {
                if (e) {
                    e.stopPropagation();
                    e.preventDefault();
                }
                if (window.SoundEffects) window.SoundEffects.click();

                if (!('speechSynthesis' in window)) {
                    alert('Text-to-Speech tidak didukung pada browser ini.');
                    return;
                }

                if (isGuideSpeaking) {
                    window.speechSynthesis.cancel();
                    isGuideSpeaking = false;
                    updateGuideTTSUI(false);
                    return;
                }

                const lang = '{{ app()->getLocale() }}';
                let guideText = '';
                if (lang === 'id') {
                    guideText = "Petunjuk Cara Memilih di Bilik Suara. Langkah 1: Tempelkan kartu identitas anggota Koperasi ke alat pembaca scanner untuk membuka surat suara digital. Langkah 2: Sentuh foto kandidat pada layar untuk memilih 1 Calon Ketua dan 1 Calon Pengawas pilihan Anda. Anda dapat menyentuh tombol lihat profil untuk membaca visi misi. Langkah 3: Tinjau kembali pilihan Anda pada layar konfirmasi, lalu sentuh Kirim Suara untuk menyimpan suara secara aman. Terima kasih.";
                } else {
                    guideText = "Voting Guide. Step 1: Tap your registered cooperative RFID card on the reader to authenticate your identity. Step 2: Touch candidate photos to choose 1 Chairman and 1 Supervisory Board candidate. Step 3: Review your selections and tap Submit Vote to securely record your ballot. Thank you.";
                }

                window.speechSynthesis.cancel();
                const utterance = new SpeechSynthesisUtterance(guideText);
                utterance.lang = lang === 'id' ? 'id-ID' : 'en-US';
                utterance.rate = 0.92;
                utterance.pitch = 1.0;

                const voices = window.speechSynthesis.getVoices();
                const matchedVoice = voices.find(v => lang === 'id' ? (v.lang.startsWith('id') || v.lang.includes('ID')) : (v.lang.startsWith('en') || v.lang.includes('US')));
                if (matchedVoice) utterance.voice = matchedVoice;

                utterance.onstart = function() {
                    isGuideSpeaking = true;
                    updateGuideTTSUI(true);
                };

                utterance.onend = function() {
                    isGuideSpeaking = false;
                    updateGuideTTSUI(false);
                };

                utterance.onerror = function() {
                    isGuideSpeaking = false;
                    updateGuideTTSUI(false);
                };

                window.speechSynthesis.speak(utterance);
            };

            function updateGuideTTSUI(speaking) {
                const btn = document.getElementById('btn-guide-tts');
                const label = document.getElementById('guide-tts-label');
                if (!btn || !label) return;

                if (speaking) {
                    btn.className = 'inline-flex items-center justify-center space-x-2 px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-black text-xs sm:text-sm border-2 border-amber-600 shadow-md transition cursor-pointer animate-pulse';
                    label.innerText = '{{ __("Stop Voice") }} ⏹';
                } else {
                    btn.className = 'inline-flex items-center justify-center space-x-2 px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-2xl bg-amber-100 hover:bg-amber-200 text-amber-950 font-black text-xs sm:text-sm border-2 border-amber-300 shadow-xs transition cursor-pointer';
                    label.innerText = '{{ __("Listen to Guide (TTS)") }}';
                }
            }

            // 5. Card Swipe-Out on Click with 2-Second Delay & Synced Audio Transition (Never Clipped)
            let isNavigatingToBooth = false;
            window.handleVotingHeroClick = function(e) {
                e.preventDefault();
                if (isNavigatingToBooth) return;
                isNavigatingToBooth = true;

                const targetUrl = document.getElementById('voting-hero-card').getAttribute('href');

                // A. Play dedicated 2.0-second rising harmonic transition sound
                if (window.SoundEffects && typeof window.SoundEffects.startVotingTransition === 'function') {
                    window.SoundEffects.startVotingTransition(2.0);
                } else if (window.SoundEffects && typeof window.SoundEffects.welcome === 'function') {
                    window.SoundEffects.welcome();
                }

                // B. Confetti particles burst
                if (typeof window.confetti === 'function') {
                    window.confetti({
                        particleCount: 65,
                        spread: 80,
                        origin: { y: 0.6 }
                    });
                }

                // C. Trigger 3D card physical glide acceleration (1850ms duration)
                if (heroCard3DInstance && typeof heroCard3DInstance.triggerSwipeOut === 'function') {
                    heroCard3DInstance.triggerSwipeOut(1850);
                }

                // D. Smooth dissolve & gentle drift (Safe within layout bounds, no knife cuts, no clipping)
                if (window.gsap) {
                    window.gsap.to('#voting-hero-card', {
                        x: 40,
                        y: -6,
                        opacity: 0,
                        scale: 0.96,
                        filter: 'blur(8px)',
                        duration: 1.85,
                        ease: 'power2.inOut',
                        onComplete: () => {
                            window.location.href = targetUrl;
                        }
                    });
                } else {
                    const card = document.getElementById('voting-hero-card');
                    if (card) {
                        card.style.transition = 'transform 1.85s cubic-bezier(0.4, 0, 0.2, 1), opacity 1.85s ease, filter 1.85s ease';
                        card.style.transform = 'translateX(40px) translateY(-6px) scale(0.96)';
                        card.style.opacity = '0';
                        card.style.filter = 'blur(8px)';
                    }
                    setTimeout(() => {
                        window.location.href = targetUrl;
                    }, 2000);
                }
            };

            // Initialize 3D Card & Loading Overlay on Load
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    runInitialLoadingOverlay();
                });
            } else {
                runInitialLoadingOverlay();
            }
        </script>
    @endpush
@endsection