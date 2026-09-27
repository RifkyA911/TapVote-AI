@extends('layouts.admin')

@section('title', 'System Documentation & Architectural Specification')

@section('content')
<div class="space-y-8 pb-16">

    <!-- Hero Header Banner -->
    <div class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xs transition-colors">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-blue-100 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                        System Documentation
                    </span>
                    <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-mono font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                        v2.4 Enterprise Edition
                    </span>
                    <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-mono font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                        English Specification
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">TapVote AI Architectural & Technical Specification</h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium max-w-3xl leading-relaxed">
                    Official technical manual, system architecture, database schema (DDL & DML), interactive business workflows, RFC safe query protocol, Web NFC/RFID interface standards, and Google Gemini AI telemetry intelligence.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <button onclick="window.print()" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold border border-slate-200 dark:border-slate-700 transition flex items-center space-x-1.5 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>Print Specification</span>
                </button>
                <a href="#interactive-flows" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-xs transition flex items-center space-x-1.5">
                    <span>Interactive Flowcharts</span>
                    <span class="text-xs">↓</span>
                </a>
            </div>
        </div>

        <!-- Sticky Quick Navigation Jump Links -->
        <div class="flex items-center gap-2 overflow-x-auto pt-6 mt-6 border-t border-slate-100 dark:border-slate-800 text-xs font-bold no-scrollbar">
            <a href="#tech-stack" class="px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-blue-50 hover:text-blue-700 dark:hover:bg-blue-950/60 dark:hover:text-blue-300 transition whitespace-nowrap">1. Tech Stack</a>
            <a href="#interactive-flows" class="px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-blue-50 hover:text-blue-700 dark:hover:bg-blue-950/60 dark:hover:text-blue-300 transition whitespace-nowrap">2. Interactive Flowcharts</a>
            <a href="#api-spec" class="px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-blue-50 hover:text-blue-700 dark:hover:bg-blue-950/60 dark:hover:text-blue-300 transition whitespace-nowrap">3. API & Protocols</a>
            <a href="#database-schema" class="px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-blue-50 hover:text-blue-700 dark:hover:bg-blue-950/60 dark:hover:text-blue-300 transition whitespace-nowrap">4. Database (DDL / DML)</a>
            <a href="#features-matrix" class="px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-blue-50 hover:text-blue-700 dark:hover:bg-blue-950/60 dark:hover:text-blue-300 transition whitespace-nowrap">5. Features & Security</a>
            <a href="#hardware-requirements" class="px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-blue-50 hover:text-blue-700 dark:hover:bg-blue-950/60 dark:hover:text-blue-300 transition whitespace-nowrap">6. Hardware & Requirements</a>
        </div>
    </div>

    <!-- SECTION 1: TECHNOLOGY STACK BREAKDOWN -->
    <section id="tech-stack" class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xs space-y-6 transition-colors">
        <div class="flex items-center space-x-3 pb-4 border-b border-slate-100 dark:border-slate-800">
            <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">1</div>
            <div>
                <h2 class="text-xl font-black text-slate-900 dark:text-white">Core Technology Stack</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Enterprise architectural layers powering high-throughput e-voting with sub-second latency.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Backend Framework -->
            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-mono font-bold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/60 px-2 py-0.5 rounded-lg border border-red-200 dark:border-red-900">Backend Core</span>
                    <span class="text-xs font-bold text-slate-400">PHP 8.2+</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Laravel 11 LTS</h3>
                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                    Atomic database transactions, Eloquent ORM with strict foreign integrity, session encryption, rate-limiting, and middleware pipeline for kiosk security.
                </p>
                <div class="flex flex-wrap gap-1.5 pt-1">
                    <span class="px-2 py-0.5 rounded bg-white dark:bg-slate-900 text-[10px] font-mono font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">Transactions</span>
                    <span class="px-2 py-0.5 rounded bg-white dark:bg-slate-900 text-[10px] font-mono font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">CSRF Guard</span>
                    <span class="px-2 py-0.5 rounded bg-white dark:bg-slate-900 text-[10px] font-mono font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">Artisan CLI</span>
                </div>
            </div>

            <!-- Frontend Engine -->
            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-mono font-bold text-cyan-600 dark:text-cyan-400 bg-cyan-50 dark:bg-cyan-950/60 px-2 py-0.5 rounded-lg border border-cyan-200 dark:border-cyan-900">Styling & UI</span>
                    <span class="text-xs font-bold text-slate-400">Tailwind v4</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Tailwind CSS & Blade</h3>
                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                    Lightning-fast native CSS rendering, pure modern flexbox/grid layout, full dark/light theme switching, and fluid responsive design across mobile, tablet, and big-screen kiosks.
                </p>
                <div class="flex flex-wrap gap-1.5 pt-1">
                    <span class="px-2 py-0.5 rounded bg-white dark:bg-slate-900 text-[10px] font-mono font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">Fluid Breakpoints</span>
                    <span class="px-2 py-0.5 rounded bg-white dark:bg-slate-900 text-[10px] font-mono font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">Dark/Light Class</span>
                </div>
            </div>

            <!-- 3D Graphics Engine -->
            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/60 px-2 py-0.5 rounded-lg border border-indigo-200 dark:border-indigo-900">3D Graphics</span>
                    <span class="text-xs font-bold text-slate-400">WebGL</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Three.js r128</h3>
                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                    GPU-accelerated interactive 3D ID Cards on `/` and `/dashboard`, plus dynamic 3D golden lottery reels with particle explosions on `/admin/reports/doorprize` and `/doorprize`.
                </p>
                <div class="flex flex-wrap gap-1.5 pt-1">
                    <span class="px-2 py-0.5 rounded bg-white dark:bg-slate-900 text-[10px] font-mono font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">PerspectiveCamera</span>
                    <span class="px-2 py-0.5 rounded bg-white dark:bg-slate-900 text-[10px] font-mono font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">Canvas WebGL</span>
                </div>
            </div>

            <!-- Realtime Telemetry -->
            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-mono font-bold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/60 px-2 py-0.5 rounded-lg border border-amber-200 dark:border-amber-900">Realtime Event</span>
                    <span class="text-xs font-bold text-slate-400">HTTP SSE</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Server-Sent Events (SSE)</h3>
                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                    Persistent unidirectional event stream (`/admin/stream/results`) pushing live vote counts, turnout percentages, and telemetry updates to client dashboards with zero polling overhead.
                </p>
                <div class="flex flex-wrap gap-1.5 pt-1">
                    <span class="px-2 py-0.5 rounded bg-white dark:bg-slate-900 text-[10px] font-mono font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">text/event-stream</span>
                    <span class="px-2 py-0.5 rounded bg-white dark:bg-slate-900 text-[10px] font-mono font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">EventSource</span>
                </div>
            </div>

            <!-- Artificial Intelligence -->
            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-mono font-bold text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-950/60 px-2 py-0.5 rounded-lg border border-violet-200 dark:border-violet-900">AI Reasoning</span>
                    <span class="text-xs font-bold text-slate-400">Gemini 2.0</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Google Gemini Reasoning</h3>
                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                    Integrated LLM reasoning engine evaluating quorum attainment, department turnout skewness, audit trail anomalies, and generating formal electoral plenary conclusions.
                </p>
                <div class="flex flex-wrap gap-1.5 pt-1">
                    <span class="px-2 py-0.5 rounded bg-white dark:bg-slate-900 text-[10px] font-mono font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">Gemini Flash/Pro</span>
                    <span class="px-2 py-0.5 rounded bg-white dark:bg-slate-900 text-[10px] font-mono font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">Plenary Telemetry</span>
                </div>
            </div>

            <!-- Hardware & NFC -->
            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-mono font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded-lg border border-emerald-200 dark:border-emerald-900">Hardware I/O</span>
                    <span class="text-xs font-bold text-slate-400">ISO 14443A</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Web NFC & Keywedge</h3>
                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                    W3C Web NFC API for native Android Chrome card tapping, dual-mode USB HID Keywedge keyboard emulation, and 4-byte / 7-byte Mifare UID endian reversal algorithms.
                </p>
                <div class="flex flex-wrap gap-1.5 pt-1">
                    <span class="px-2 py-0.5 rounded bg-white dark:bg-slate-900 text-[10px] font-mono font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">NDEFReader</span>
                    <span class="px-2 py-0.5 rounded bg-white dark:bg-slate-900 text-[10px] font-mono font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">Hex/Decimal Reversal</span>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: INTERACTIVE BUSINESS WORKFLOWS & FLOWCHARTS -->
    <section id="interactive-flows" class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xs space-y-6 transition-colors">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">2</div>
                <div>
                    <h2 class="text-xl font-black text-slate-900 dark:text-white">Interactive Operational Flowcharts</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Click on any flowchart step node below to inspect its technical details, database payload, and security checkpoints.</p>
                </div>
            </div>

            <!-- Tab Switcher for Flowcharts -->
            <div class="flex items-center space-x-1.5 p-1 rounded-2xl bg-slate-100 dark:bg-slate-800 text-xs font-bold self-start sm:self-auto overflow-x-auto no-scrollbar">
                <button type="button" onclick="switchFlowTab('voter-auth')" id="flow-tab-voter-auth" class="px-3 py-1.5 rounded-xl bg-blue-600 text-white shadow-xs transition">Voter Tap & Gate</button>
                <button type="button" onclick="switchFlowTab('vote-submit')" id="flow-tab-vote-submit" class="px-3 py-1.5 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition">Ballot Casting</button>
                <button type="button" onclick="switchFlowTab('doorprize-flow')" id="flow-tab-doorprize-flow" class="px-3 py-1.5 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition">Doorprize Raffle</button>
                <button type="button" onclick="switchFlowTab('system-lifecycle')" id="flow-tab-system-lifecycle" class="px-3 py-1.5 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition">Election Lifecycle</button>
            </div>
        </div>

        <!-- FLOW 1: VOTER TAP & ELIGIBILITY GATE (Interactive Diagram) -->
        <div id="flow-container-voter-auth" class="space-y-6">
            <div class="p-4 rounded-2xl bg-blue-50/60 dark:bg-blue-950/30 border border-blue-200/80 dark:border-blue-900 text-xs text-blue-950 dark:text-blue-300 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="text-base">ℹ️</span>
                    <span><strong>Tap Flow Highlight:</strong> Includes individual raffle & voting eligibility validation (<code class="bg-blue-100 dark:bg-blue-900/60 px-1 py-0.5 rounded text-[11px] font-mono">can_raffle == true</code>). Click a step to inspect.</span>
                </div>
                <span class="text-[11px] font-mono font-bold text-blue-600 dark:text-blue-400">Step 1 of 5</span>
            </div>

            <!-- Interactive Flow Nodes Grid -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <!-- Node 1 -->
                <div onclick="inspectFlowNode('voter-auth', 1)" id="flow-node-voter-auth-1" class="cursor-pointer p-4 rounded-2xl bg-blue-600 text-white shadow-md transition-all duration-200 border-2 border-transparent">
                    <span class="text-[10px] font-mono font-bold uppercase tracking-wider block opacity-80">Phase 01</span>
                    <strong class="text-sm block mt-1">NFC / RFID Tap</strong>
                    <p class="text-[11px] opacity-90 mt-1 line-clamp-2">Member touches ID card on reader or scans QR/enters NIK.</p>
                </div>

                <!-- Node 2 -->
                <div onclick="inspectFlowNode('voter-auth', 2)" id="flow-node-voter-auth-2" class="cursor-pointer p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-900 dark:text-white border-2 border-slate-200 dark:border-slate-700 transition-all duration-200">
                    <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 block">Phase 02</span>
                    <strong class="text-sm block mt-1">UID Reversal</strong>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">Endian byte swap, 10-digit decimal padding & candidate lookup.</p>
                </div>

                <!-- Node 3 -->
                <div onclick="inspectFlowNode('voter-auth', 3)" id="flow-node-voter-auth-3" class="cursor-pointer p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-900 dark:text-white border-2 border-slate-200 dark:border-slate-700 transition-all duration-200">
                    <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 block">Phase 03</span>
                    <strong class="text-sm block mt-1">Eligibility Check</strong>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">Verify <code class="font-mono text-emerald-600">can_raffle == true</code>. If false, trigger 403 error.</p>
                </div>

                <!-- Node 4 -->
                <div onclick="inspectFlowNode('voter-auth', 4)" id="flow-node-voter-auth-4" class="cursor-pointer p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-900 dark:text-white border-2 border-slate-200 dark:border-slate-700 transition-all duration-200">
                    <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 block">Phase 04</span>
                    <strong class="text-sm block mt-1">Vote State Check</strong>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">Verify <code class="font-mono text-amber-600">pilih == 'F'</code>. If already voted, trigger anti-double vote.</p>
                </div>

                <!-- Node 5 -->
                <div onclick="inspectFlowNode('voter-auth', 5)" id="flow-node-voter-auth-5" class="cursor-pointer p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-900 dark:text-white border-2 border-slate-200 dark:border-slate-700 transition-all duration-200">
                    <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 block">Phase 05</span>
                    <strong class="text-sm block mt-1">Session & Booth</strong>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">Set encrypted voter session and transition to `/vote` booth.</p>
                </div>
            </div>

            <!-- Dynamic Node Detail Inspector Panel -->
            <div id="flow-detail-voter-auth" class="p-5 rounded-2xl bg-slate-900 text-slate-100 font-mono text-xs space-y-3 border border-slate-800">
                <div class="flex items-center justify-between text-slate-400 border-b border-slate-800 pb-2">
                    <span id="flow-detail-title" class="font-bold text-white uppercase tracking-wider">Phase 01: NFC / RFID Sensor Contact</span>
                    <span id="flow-detail-endpoint" class="text-emerald-400 font-bold">CLIENT SENSOR I/O</span>
                </div>
                <div id="flow-detail-desc" class="text-slate-300 leading-relaxed font-sans text-xs">
                    The member presents an authorized Mifare 1K/4K ISO 14443A card to the NFC reader terminal. The sensor captures the raw 4-byte or 7-byte Unique Identifier (UID). If using a mobile phone, the Web NFC W3C API handles the reading event seamlessly.
                </div>
                <div class="pt-2 text-[11px] text-slate-400 border-t border-slate-800 flex items-center justify-between">
                    <span>Validation Guard: <strong class="text-indigo-400">Sensor Physical Proximity</strong></span>
                    <span>Failover: <strong class="text-amber-400">USB Keywedge / Manual NIK Search</strong></span>
                </div>
            </div>
        </div>

        <!-- FLOW 2: BALLOT CASTING (Hidden by default, switched via tab) -->
        <div id="flow-container-vote-submit" class="hidden space-y-6">
            <div class="p-4 rounded-2xl bg-emerald-50/60 dark:bg-emerald-950/30 border border-emerald-200/80 dark:border-emerald-900 text-xs text-emerald-950 dark:text-emerald-300">
                <strong>Atomic Ballot Flow:</strong> Votes for Chairman and Supervisor are submitted simultaneously in a single isolated database transaction, ensuring perfect ballot secrecy and mathematical parity.
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                    <span class="text-[10px] font-mono font-bold text-slate-400 uppercase">Step 01</span>
                    <strong class="text-sm block mt-1 text-slate-900 dark:text-white">Dual Selection</strong>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Voter selects 1 Chairman candidate and 1 Supervisor candidate on a tactile, accessible touchscreen.</p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                    <span class="text-[10px] font-mono font-bold text-slate-400 uppercase">Step 02</span>
                    <strong class="text-sm block mt-1 text-slate-900 dark:text-white">DB Transaction</strong>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1"><code class="font-mono text-blue-600">DB::transaction</code> commits `hasil_ketua` and `hasil_pengawas` and updates `pemilih.pilih = 'T'` atomically.</p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                    <span class="text-[10px] font-mono font-bold text-slate-400 uppercase">Step 03</span>
                    <strong class="text-sm block mt-1 text-slate-900 dark:text-white">Audit Trail Seal</strong>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1"><code class="font-mono text-emerald-600">ActivityLog::log</code> timestamps the ballot cast event without storing vote choice associations.</p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                    <span class="text-[10px] font-mono font-bold text-slate-400 uppercase">Step 04</span>
                    <strong class="text-sm block mt-1 text-slate-900 dark:text-white">Session Purge</strong>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Session tokens are scrubbed immediately, preventing browser back-button replay or impersonation.</p>
                </div>
            </div>
        </div>

        <!-- FLOW 3: DOORPRIZE RAFFLE (Hidden by default) -->
        <div id="flow-container-doorprize-flow" class="hidden space-y-6">
            <div class="p-4 rounded-2xl bg-amber-50/60 dark:bg-amber-950/30 border border-amber-200/80 dark:border-amber-900 text-xs text-amber-950 dark:text-amber-300">
                <strong>Fair-Entropy Doorprize Engine:</strong> Only voters who completed voting (`pilih == 'T'`) AND have active raffle eligibility (`can_raffle == true`) are included in the random lottery pool.
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                    <span class="text-[10px] font-mono font-bold text-slate-400 uppercase">Step 01</span>
                    <strong class="text-sm block mt-1 text-slate-900 dark:text-white">Eligibility Filter</strong>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Queries <code class="font-mono text-emerald-600">WHERE pilih = 'T' AND can_raffle = 1</code> excluding past winners of the same prize.</p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                    <span class="text-[10px] font-mono font-bold text-slate-400 uppercase">Step 02</span>
                    <strong class="text-sm block mt-1 text-slate-900 dark:text-white">Three.js Reel Spin</strong>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Interactive 3D golden cylinder reel accelerates to 2400 RPM, executing fluid cubic easing deceleration.</p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                    <span class="text-[10px] font-mono font-bold text-slate-400 uppercase">Step 03</span>
                    <strong class="text-sm block mt-1 text-slate-900 dark:text-white">Winner Inscription</strong>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Official winner is stored in `doorprize_winners` with timestamp, decrementing remaining stock slots.</p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                    <span class="text-[10px] font-mono font-bold text-slate-400 uppercase">Step 04</span>
                    <strong class="text-sm block mt-1 text-slate-900 dark:text-white">Stage Broadcast</strong>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Audience stage `/doorprize` receives real-time winner broadcast with celebratory particle fireworks.</p>
                </div>
            </div>
        </div>

        <!-- FLOW 4: ELECTION LIFECYCLE (Hidden by default) -->
        <div id="flow-container-system-lifecycle" class="hidden space-y-6">
            <div class="p-4 rounded-2xl bg-purple-50/60 dark:bg-purple-950/30 border border-purple-200/80 dark:border-purple-900 text-xs text-purple-950 dark:text-purple-300">
                <strong>System Lifecycle & States:</strong> The centralized operational state (`STARTED` → `PAUSED` → `STOPPED`) governs kiosk accessibility across all nodes simultaneously.
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="p-4 rounded-2xl bg-emerald-50/60 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800">
                    <strong class="text-emerald-700 dark:text-emerald-300 text-sm block">● LIVE (STARTED)</strong>
                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-1">All voting kiosks are fully operational. Members can tap cards, enter booths, and cast ballots.</p>
                </div>
                <div class="p-4 rounded-2xl bg-amber-50/60 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800">
                    <strong class="text-amber-700 dark:text-amber-300 text-sm block">❚❚ PAUSED</strong>
                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-1">Kiosks freeze with a friendly warning modal. Ideal for prayer breaks, lunch intervals, or technical checks.</p>
                </div>
                <div class="p-4 rounded-2xl bg-rose-50/60 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800">
                    <strong class="text-rose-700 dark:text-rose-300 text-sm block">■ FINISHED (STOPPED)</strong>
                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-1">Election officially concluded. Kiosks locked permanently. Final plenary certificates & exports generated.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3: REST API & RFC QUERY PROTOCOL SPECIFICATION -->
    <section id="api-spec" class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xs space-y-6 transition-colors">
        <div class="flex items-center space-x-3 pb-4 border-b border-slate-100 dark:border-slate-800">
            <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">3</div>
            <div>
                <h2 class="text-xl font-black text-slate-900 dark:text-white">API & Communication Protocols</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Standardized endpoints, authentication requirements, payload schemas, and RFC draft HTTP QUERY support.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-xs text-left">
                <thead class="border-b border-slate-200 dark:border-slate-800 font-bold text-slate-400 uppercase text-[10px]">
                    <tr>
                        <th class="py-3 px-3">Method</th>
                        <th class="py-3 px-3">Endpoint</th>
                        <th class="py-3 px-3">Auth / Guard</th>
                        <th class="py-3 px-3">Description</th>
                        <th class="py-3 px-3">Status Codes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-mono">
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 font-bold">POST</span></td>
                        <td class="py-3 px-3 font-bold text-slate-900 dark:text-white">/voter/tap</td>
                        <td class="py-3 px-3 text-slate-500">Public Kiosk (CSRF)</td>
                        <td class="py-3 px-3 font-sans text-slate-600 dark:text-slate-300">Process RFID / NFC tap verification and issue voting session.</td>
                        <td class="py-3 px-3 text-slate-700 dark:text-slate-300">200 OK, 400 Warning, 403 Forbidden, 404 Not Found</td>
                    </tr>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 font-bold">POST</span></td>
                        <td class="py-3 px-3 font-bold text-slate-900 dark:text-white">/voter/vote</td>
                        <td class="py-3 px-3 text-slate-500">Session Guard (`voter_nik`)</td>
                        <td class="py-3 px-3 font-sans text-slate-600 dark:text-slate-300">Submit atomic ballot choice for Chairman and Supervisor.</td>
                        <td class="py-3 px-3 text-slate-700 dark:text-slate-300">302 Found, 403 Forbidden, 422 Unprocessable</td>
                    </tr>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-indigo-100 dark:bg-indigo-950/60 text-indigo-800 dark:text-indigo-300 font-bold">QUERY / POST</span></td>
                        <td class="py-3 px-3 font-bold text-slate-900 dark:text-white">/admin/voters/query</td>
                        <td class="py-3 px-3 text-slate-500">Admin Auth + IP Guard</td>
                        <td class="py-3 px-3 font-sans text-slate-600 dark:text-slate-300">RFC safe query method with request body: search, dept, status, sort, dir, per_page.</td>
                        <td class="py-3 px-3 text-slate-700 dark:text-slate-300">200 OK</td>
                    </tr>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 font-bold">PATCH</span></td>
                        <td class="py-3 px-3 font-bold text-slate-900 dark:text-white">/admin/voters/{nik}/toggle-raffle</td>
                        <td class="py-3 px-3 text-slate-500">Admin Auth + IP Guard</td>
                        <td class="py-3 px-3 font-sans text-slate-600 dark:text-slate-300">Toggle individual member eligibility for doorprize raffle and voting participation.</td>
                        <td class="py-3 px-3 text-slate-700 dark:text-slate-300">200 OK, 404 Not Found</td>
                    </tr>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-blue-100 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 font-bold">GET</span></td>
                        <td class="py-3 px-3 font-bold text-slate-900 dark:text-white">/admin/stream/results</td>
                        <td class="py-3 px-3 text-slate-500">Admin Auth + IP Guard</td>
                        <td class="py-3 px-3 font-sans text-slate-600 dark:text-slate-300">Server-Sent Events (SSE) streaming live turnout counts, candidate tallies, and timestamps.</td>
                        <td class="py-3 px-3 text-slate-700 dark:text-slate-300">200 (Stream)</td>
                    </tr>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 font-bold">POST</span></td>
                        <td class="py-3 px-3 font-bold text-slate-900 dark:text-white">/admin/reports/doorprize/draw</td>
                        <td class="py-3 px-3 text-slate-500">Admin Auth + IP Guard</td>
                        <td class="py-3 px-3 font-sans text-slate-600 dark:text-slate-300">Execute fair-entropy prize drawing for specified doorprize package.</td>
                        <td class="py-3 px-3 text-slate-700 dark:text-slate-300">200 OK, 422 Depleted</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- SECTION 4: DATABASE ARCHITECTURE (DDL & DML SCHEMAS) -->
    <section id="database-schema" class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xs space-y-6 transition-colors">
        <div class="flex items-center space-x-3 pb-4 border-b border-slate-100 dark:border-slate-800">
            <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">4</div>
            <div>
                <h2 class="text-xl font-black text-slate-900 dark:text-white">Database Architecture (DDL / DML)</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Relational schema design, indexes, foreign constraints, and audit trail tables.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- DDL Code Snippet 1 -->
            <div class="space-y-2">
                <div class="flex items-center justify-between text-xs font-bold text-slate-700 dark:text-slate-300">
                    <span>Voter Roster & Ballot DDL</span>
                    <span class="text-slate-400 font-mono">pemilih, hasil_ketua, hasil_pengawas</span>
                </div>
                <div class="p-4 rounded-2xl bg-slate-950 text-slate-200 font-mono text-[11px] overflow-x-auto leading-relaxed border border-slate-800">
<pre><span class="text-indigo-400">CREATE TABLE</span> <span class="text-amber-300">pemilih</span> (
    <span class="text-sky-300">nik</span> VARCHAR(50) PRIMARY KEY,
    <span class="text-sky-300">rfid</span> VARCHAR(100) UNIQUE NOT NULL,
    <span class="text-sky-300">nama</span> VARCHAR(255) NOT NULL,
    <span class="text-sky-300">dept</span> VARCHAR(100) NOT NULL,
    <span class="text-sky-300">pilih</span> ENUM('T', 'F') DEFAULT 'F',
    <span class="text-sky-300">can_raffle</span> BOOLEAN DEFAULT TRUE,
    <span class="text-sky-300">voted_at</span> TIMESTAMP NULL,
    <span class="text-sky-300">created_at</span> TIMESTAMP,
    <span class="text-sky-300">updated_at</span> TIMESTAMP,
    INDEX idx_pemilih_rfid (rfid),
    INDEX idx_pemilih_pilih (pilih),
    INDEX idx_pemilih_raffle (can_raffle)
);

<span class="text-indigo-400">CREATE TABLE</span> <span class="text-amber-300">hasil_ketua</span> (
    <span class="text-sky-300">id</span> BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    <span class="text-sky-300">pemilih_nik</span> VARCHAR(50) UNIQUE NOT NULL,
    <span class="text-sky-300">kandidat_nik</span> VARCHAR(50) NOT NULL,
    <span class="text-sky-300">created_at</span> TIMESTAMP,
    FOREIGN KEY (pemilih_nik) REFERENCES pemilih(nik) ON DELETE CASCADE,
    FOREIGN KEY (kandidat_nik) REFERENCES kandidat_ketua(nik) ON DELETE CASCADE
);</pre>
                </div>
            </div>

            <!-- DDL Code Snippet 2 -->
            <div class="space-y-2">
                <div class="flex items-center justify-between text-xs font-bold text-slate-700 dark:text-slate-300">
                    <span>Doorprize & Audit DDL</span>
                    <span class="text-slate-400 font-mono">doorprizes, winners, activity_logs</span>
                </div>
                <div class="p-4 rounded-2xl bg-slate-950 text-slate-200 font-mono text-[11px] overflow-x-auto leading-relaxed border border-slate-800">
<pre><span class="text-indigo-400">CREATE TABLE</span> <span class="text-amber-300">doorprizes</span> (
    <span class="text-sky-300">id</span> BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    <span class="text-sky-300">title</span> VARCHAR(255) NOT NULL,
    <span class="text-sky-300">category</span> VARCHAR(100) NOT NULL,
    <span class="text-sky-300">quantity</span> INT UNSIGNED DEFAULT 1,
    <span class="text-sky-300">sponsor</span> VARCHAR(255) NULL,
    <span class="text-sky-300">image</span> VARCHAR(255) NULL,
    <span class="text-sky-300">status</span> ENUM('active', 'archived') DEFAULT 'active'
);

<span class="text-indigo-400">CREATE TABLE</span> <span class="text-amber-300">doorprize_winners</span> (
    <span class="text-sky-300">id</span> BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    <span class="text-sky-300">doorprize_id</span> BIGINT UNSIGNED NOT NULL,
    <span class="text-sky-300">pemilih_nik</span> VARCHAR(50) NOT NULL,
    <span class="text-sky-300">claim_status</span> ENUM('unclaimed', 'claimed') DEFAULT 'unclaimed',
    <span class="text-sky-300">won_at</span> TIMESTAMP NOT NULL,
    FOREIGN KEY (doorprize_id) REFERENCES doorprizes(id) ON DELETE CASCADE,
    FOREIGN KEY (pemilih_nik) REFERENCES pemilih(nik) ON DELETE CASCADE
);</pre>
                </div>
            </div>
        </div>

        <!-- Sample Integrity Verification DML Queries -->
        <div class="space-y-2 pt-2">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Electoral Integrity Audit Queries (DML)</h4>
            <div class="p-4 rounded-2xl bg-slate-950 text-slate-200 font-mono text-[11px] overflow-x-auto leading-relaxed border border-slate-800">
<pre><span class="text-slate-400">-- 1. Check for vote count parity between Chairman, Supervisor, and marked voters</span>
<span class="text-indigo-400">SELECT</span> 
    (SELECT COUNT(*) FROM pemilih WHERE pilih = 'T') AS marked_voters,
    (SELECT COUNT(*) FROM hasil_ketua) AS chairman_ballots,
    (SELECT COUNT(*) FROM hasil_pengawas) AS supervisor_ballots,
    ((SELECT COUNT(*) FROM pemilih WHERE pilih = 'T') = (SELECT COUNT(*) FROM hasil_ketua) AND 
     (SELECT COUNT(*) FROM hasil_ketua) = (SELECT COUNT(*) FROM hasil_pengawas)) AS is_zero_discrepancy;

<span class="text-slate-400">-- 2. Audit doorprize raffle pool compliance</span>
<span class="text-indigo-400">SELECT</span> COUNT(*) AS total_eligible_raffle_pool 
<span class="text-indigo-400">FROM</span> pemilih 
<span class="text-indigo-400">WHERE</span> pilih = 'T' <span class="text-indigo-400">AND</span> can_raffle = TRUE;</pre>
            </div>
        </div>
    </section>

    <!-- SECTION 5: FEATURES & SECURITY HARDENING -->
    <section id="features-matrix" class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xs space-y-6 transition-colors">
        <div class="flex items-center space-x-3 pb-4 border-b border-slate-100 dark:border-slate-800">
            <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">5</div>
            <div>
                <h2 class="text-xl font-black text-slate-900 dark:text-white">Feature Matrix & Security Hardening</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Enterprise multi-layer protection guarantees secrecy, auditability, and tamper-resistance.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-xs">
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 space-y-1.5">
                <span class="text-emerald-600 font-bold text-base">🛡️ Kiosk Lockdown</span>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed">Prevents URL manipulation, right-click context menus, and navigation hotkeys on public voting terminals.</p>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 space-y-1.5">
                <span class="text-blue-600 font-bold text-base">🌐 Dual-Language (i18n)</span>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed">Full root-level localization between Indonesian (ID) and English (EN) across all admin panels, charts, and public kiosks.</p>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 space-y-1.5">
                <span class="text-indigo-600 font-bold text-base">🔒 Network Whitelist (IP)</span>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed">Strict IP filtering middleware (`admin.ip`) ensures admin panels are accessible only from authorized subnet addresses.</p>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 space-y-1.5">
                <span class="text-purple-600 font-bold text-base">📊 High-Definition PDF</span>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed">DomPDF-powered vector export of electoral certificates, candidate summaries, and forensic traceback sheets.</p>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 space-y-1.5">
                <span class="text-amber-600 font-bold text-base">⚡ Quorum & Deadline Alert</span>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed">Automatic threshold verification with dynamic visual countdown timer and automated kiosk closing at cutoff.</p>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 space-y-1.5">
                <span class="text-rose-600 font-bold text-base">🎁 Selective Raffle Toggle</span>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed">Per-voter toggle to allow or exclude individual members from doorprize drawings and ballot access with instant AJAX.</p>
            </div>
        </div>
    </section>

    <!-- SECTION 6: HARDWARE & SYSTEM REQUIREMENTS -->
    <section id="hardware-requirements" class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xs space-y-6 transition-colors">
        <div class="flex items-center space-x-3 pb-4 border-b border-slate-100 dark:border-slate-800">
            <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">6</div>
            <div>
                <h2 class="text-xl font-black text-slate-900 dark:text-white">Hardware & Deployment Specifications</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Recommended server configurations and client card reader specifications.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 space-y-3">
                <h4 class="font-bold text-slate-900 dark:text-white uppercase tracking-wider text-[11px]">Server / Kiosk Master Node</h4>
                <ul class="space-y-2 text-slate-600 dark:text-slate-300">
                    <li class="flex items-start space-x-2">
                        <span class="text-blue-600 font-bold">•</span>
                        <span><strong>Processor:</strong> Quad-Core 2.4 GHz (Intel Core i5 / AMD Ryzen 5 or higher)</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <span class="text-blue-600 font-bold">•</span>
                        <span><strong>Memory (RAM):</strong> 8 GB Minimum (16 GB Recommended for 1,000+ concurrent clients)</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <span class="text-blue-600 font-bold">•</span>
                        <span><strong>Storage:</strong> NVMe SSD with 20 GB free space for database & telemetry logs</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <span class="text-blue-600 font-bold">•</span>
                        <span><strong>Runtime:</strong> PHP 8.2 or 8.3 with extensions (bcmath, ctype, curl, mbstring, openssl, pdo, tokenizer, xml)</span>
                    </li>
                </ul>
            </div>

            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 space-y-3">
                <h4 class="font-bold text-slate-900 dark:text-white uppercase tracking-wider text-[11px]">RFID / NFC Card Reader Specification</h4>
                <ul class="space-y-2 text-slate-600 dark:text-slate-300">
                    <li class="flex items-start space-x-2">
                        <span class="text-emerald-600 font-bold">•</span>
                        <span><strong>Card Standard:</strong> Mifare Classic 1K / 4K / Ultralight (ISO/IEC 14443 Type A, 13.56 MHz)</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <span class="text-emerald-600 font-bold">•</span>
                        <span><strong>Reader Model:</strong> ACS ACR122U USB NFC Reader, or Plug-and-Play USB HID Keywedge Reader</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <span class="text-emerald-600 font-bold">•</span>
                        <span><strong>Mobile Support:</strong> Android Smartphone with NFC enabled running Google Chrome 89+ (Web NFC API)</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <span class="text-emerald-600 font-bold">•</span>
                        <span><strong>Read Range:</strong> Up to 50 mm (depending on transponder antenna geometry)</span>
                    </li>
                </ul>
            </div>
        </div>
    </section>

</div>

@push('scripts')
<script>
    const flowData = {
        'voter-auth': [
            {
                phase: 'Phase 01',
                title: 'Phase 01: NFC / RFID Sensor Contact',
                endpoint: 'CLIENT SENSOR I/O',
                desc: 'The member presents an authorized Mifare 1K/4K ISO 14443A card to the NFC reader terminal. The sensor captures the raw 4-byte or 7-byte Unique Identifier (UID). If using a mobile phone, the Web NFC W3C API handles the reading event seamlessly.',
                guard: 'Sensor Physical Proximity',
                failover: 'USB Keywedge / Manual NIK Search'
            },
            {
                phase: 'Phase 02',
                title: 'Phase 02: Endian Reversal & Decimal Transformation',
                endpoint: 'VoterController@processTap',
                desc: 'Hardware readers produce varied endianness representations (e.g. forward hex "E280681A", reversed hex "1A6880E2", or 10-digit padded decimal "0443056354"). The server generates all permutation candidates and queries the database for an exact voter match.',
                guard: 'Cryptographic Permutation Match',
                failover: 'Direct NIK lookup fallback'
            },
            {
                phase: 'Phase 03',
                title: 'Phase 03: Individual Raffle & Vote Eligibility Check',
                endpoint: 'Pemilih::can_raffle Guard',
                desc: 'The system inspects the `can_raffle` boolean flag on the matched voter record. If disabled by the committee administrator, access is rejected immediately with HTTP 403 Forbidden, preventing transition to the voting booth.',
                guard: 'Individual Committee Access Toggle (can_raffle == true)',
                failover: 'Rejection Modal & Audio Warning'
            },
            {
                phase: 'Phase 04',
                title: 'Phase 04: Duplicate Voting Prevention (Anti-Double Ballot)',
                endpoint: 'Pemilih::sudahMemilih Guard',
                desc: 'The system verifies the `pilih` column (`T` = Already Voted, `F` = Pending). If `pilih == "T"`, the request is rejected with HTTP 400 and an Amber ripple warning displays the exact timestamp of their previous vote.',
                guard: 'Atomic Double-Voting Interceptor',
                failover: 'Forensic Audit Log Entry (TAP_REJECTED)'
            },
            {
                phase: 'Phase 05',
                title: 'Phase 05: Session Creation & Booth Redirection',
                endpoint: 'session([\'voter_nik\' => $nik])',
                desc: 'Upon passing all verification checks, the server generates a cryptographically signed, encrypted session storing the member NIK. The kiosk triggers green Nova particle animation and smoothly transitions to `/vote`.',
                guard: 'Server-Side Session Token Seal',
                failover: 'Auto-Timeout Booth Inactivity Eviction'
            }
        ]
    };

    function switchFlowTab(tabId) {
        ['voter-auth', 'vote-submit', 'doorprize-flow', 'system-lifecycle'].forEach(id => {
            const container = document.getElementById('flow-container-' + id);
            const btn = document.getElementById('flow-tab-' + id);
            if (container) {
                if (id === tabId) {
                    container.classList.remove('hidden');
                } else {
                    container.classList.add('hidden');
                }
            }
            if (btn) {
                if (id === tabId) {
                    btn.className = 'px-3 py-1.5 rounded-xl bg-blue-600 text-white shadow-xs transition';
                } else {
                    btn.className = 'px-3 py-1.5 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition';
                }
            }
        });
    }

    function inspectFlowNode(flowKey, nodeIndex) {
        const list = flowData[flowKey];
        if (!list || !list[nodeIndex - 1]) return;
        const item = list[nodeIndex - 1];

        // Update active node styling
        for (let i = 1; i <= list.length; i++) {
            const el = document.getElementById(`flow-node-${flowKey}-${i}`);
            if (el) {
                if (i === nodeIndex) {
                    el.className = 'cursor-pointer p-4 rounded-2xl bg-blue-600 text-white shadow-md transition-all duration-200 border-2 border-transparent';
                    el.querySelector('span').className = 'text-[10px] font-mono font-bold uppercase tracking-wider block opacity-80';
                    el.querySelector('p').className = 'text-[11px] opacity-90 mt-1 line-clamp-2';
                } else {
                    el.className = 'cursor-pointer p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-900 dark:text-white border-2 border-slate-200 dark:border-slate-700 transition-all duration-200';
                    el.querySelector('span').className = 'text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 block';
                    el.querySelector('p').className = 'text-[11px] text-slate-500 dark:text-slate-400 mt-1 line-clamp-2';
                }
            }
        }

        // Update inspector card
        const titleEl = document.getElementById('flow-detail-title');
        const endpointEl = document.getElementById('flow-detail-endpoint');
        const descEl = document.getElementById('flow-detail-desc');

        if (titleEl) titleEl.innerText = item.title;
        if (endpointEl) endpointEl.innerText = item.endpoint;
        if (descEl) descEl.innerText = item.desc;
    }
</script>
@endpush
@endsection
