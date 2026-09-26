<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <title>@yield('title', 'Admin Panel') - TapVote AI</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- PWA Installation Support (Android, iOS, Windows, Linux) -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2563eb">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="TapVote AI">
    <link rel="apple-touch-icon" href="/images/pwa/icon-192.png">
    <meta name="msapplication-TileColor" content="#2563eb">
    <meta name="msapplication-TileImage" content="/images/pwa/icon-192.png">

    <!-- Admin Panel Only Theme Initializer (Strictly Isolated to Admin) -->
    <script>
        (function() {
            try {
                const theme = localStorage.getItem('tapvote_admin_theme') || 'system';
                const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
                if (isDark) {
                    document.documentElement.classList.add('dark', 'admin-dark');
                } else {
                    document.documentElement.classList.remove('dark', 'admin-dark');
                }
            } catch(e) {}
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-screen overflow-hidden bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-slate-100 font-sans antialiased flex flex-col lg:flex-row relative">

    <!-- Mobile & iPad Mini Sidebar Backdrop Overlay -->
    <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-30 hidden lg:hidden transition-opacity duration-300"></div>

    <!-- Sidebar Navigation (Responsive: Off-canvas drawer on Mobile & iPad Mini, static on Desktop) -->
    <aside id="admin-sidebar" class="fixed lg:static inset-y-0 left-0 z-40 w-64 bg-white border-r border-slate-200 flex flex-col shrink-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow-xl lg:shadow-none h-full">
        <!-- Sidebar Brand Header (Height 64px / h-16 matched exactly with Top App Bar) -->
        <div class="h-16 px-5 flex items-center justify-between border-b border-slate-200 shrink-0">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div>
                    <h1 class="font-black text-lg leading-tight text-slate-900">TapVote AI</h1>
                    <p class="text-[11px] text-blue-700 font-extrabold tracking-wide uppercase">Admin Panel</p>
                </div>
            </a>

            <!-- Close Sidebar Button on Mobile/iPad Mini -->
            <button onclick="toggleSidebar()" type="button" class="lg:hidden p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">
            <div class="text-[11px] uppercase tracking-wider text-slate-400 font-bold px-3 py-1">Monitoring</div>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span>Live Dashboard</span>
            </a>
            <a href="{{ route('admin.analytics') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.analytics') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                <span>Analytics</span>
            </a>
            <a href="{{ route('admin.vote-flow') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.vote-flow*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                <span>Vote Flow</span>
            </a>

            <div class="text-[11px] uppercase tracking-wider text-slate-400 font-bold px-3 pt-4 pb-1">Master Data</div>
            <a href="{{ route('admin.ketua.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.ketua.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span>Chairman Candidates</span>
            </a>
            <a href="{{ route('admin.pengawas.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.pengawas.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                <span>Supervisor Candidates</span>
            </a>
            <a href="{{ route('admin.voters.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.voters.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span>Eligible Voters</span>
            </a>

            <div class="text-[11px] uppercase tracking-wider text-slate-400 font-bold px-3 pt-4 pb-1">Reports & Analytics</div>
            <a href="{{ route('admin.reports.ketua') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.reports.ketua') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <span>Chairman Recap</span>
            </a>
            <a href="{{ route('admin.reports.pengawas') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.reports.pengawas') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path></svg>
                <span>Supervisor Recap</span>
            </a>
            <a href="{{ route('admin.reports.traceback') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.reports.traceback*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <span>Forensic Traceback</span>
            </a>
            <a href="{{ route('admin.reports.doorprize') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.reports.doorprize') ? 'bg-amber-600 text-white shadow-sm' : 'text-amber-700 hover:bg-amber-50' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                <span>Doorprize Raffle</span>
            </a>
            <a href="{{ route('doorprize.public') }}" target="_blank" class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-bold text-amber-900 bg-amber-50/80 hover:bg-amber-100/90 border border-amber-200 transition">
                <span class="flex items-center space-x-2">
                    <span>📺</span>
                    <span>Audience Stage View</span>
                </span>
                <span class="text-[10px] text-amber-600">↗</span>
            </a>
            <a href="{{ route('admin.logs.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.logs.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Audit Trail Logs</span>
            </a>

            <div class="text-[11px] uppercase tracking-wider text-slate-400 font-bold px-3 pt-4 pb-1">System</div>
            <a href="{{ route('admin.settings') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.settings*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span>System Settings</span>
            </a>
        </nav>

        <!-- Quick Switch to Voting Terminal & Logout -->
        <div class="p-4 border-t border-slate-200 space-y-2 bg-slate-50 shrink-0">
            <a href="{{ route('voter.tap') }}" target="_blank" class="w-full flex items-center justify-center space-x-2 py-2.5 px-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-xs font-bold text-white shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                <span>Open Voting Terminal</span>
            </a>

            <div class="flex items-center justify-between pt-2">
                <div class="text-xs text-slate-600 truncate pr-2">
                    <span class="block text-slate-900 font-bold truncate">{{ auth()->user()->name }}</span>
                    <span class="text-[11px] text-slate-500">System Admin</span>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Logout" class="p-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-600 hover:text-white transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Viewport -->
    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-y-auto">
        <!-- Top App Bar (Matched Height: h-16) -->
        <header class="sticky top-0 z-20 h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 px-4 sm:px-6 flex items-center justify-between shadow-2xs shrink-0">
            <div class="flex items-center space-x-3">
                <!-- Toggle Sidebar Button (Visible on mobile/tablet and desktop) -->
                <button 
                    onclick="toggleSidebar()" 
                    type="button" 
                    id="sidebar-toggle-btn"
                    title="Toggle Sidebar"
                    class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 transition cursor-pointer flex items-center justify-center"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                @php
                    $adminVotingStatus = \App\Models\AppSetting::get('voting_status', 'STARTED');
                    $statusDisplayLabel = match($adminVotingStatus) {
                        'STARTED' => 'LIVE',
                        'PAUSED' => 'PAUSED',
                        'STOPPED' => 'FINISHED',
                        default => 'LIVE',
                    };
                @endphp

                <!-- Compact Voting System Status Dot & Label -->
                <div class="flex items-center space-x-2">
                    <div id="voting-status-badge" class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-xl text-xs font-black tracking-wide uppercase {{ $adminVotingStatus === 'STARTED' ? 'bg-emerald-50 text-emerald-800 border border-emerald-300 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800' : ($adminVotingStatus === 'PAUSED' ? 'bg-amber-50 text-amber-800 border border-amber-300 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800' : 'bg-rose-50 text-rose-800 border border-rose-300 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800') }}">
                        <span id="voting-status-dot" class="w-2 h-2 rounded-full {{ $adminVotingStatus === 'STARTED' ? 'bg-emerald-500 animate-pulse' : ($adminVotingStatus === 'PAUSED' ? 'bg-amber-500 animate-ping' : 'bg-rose-500') }}"></span>
                        <span id="voting-status-text">{{ $statusDisplayLabel }}</span>
                    </div>
                </div>

                <!-- Modern Throttled Segmented Controls: LIVE / PAUSE / END -->
                <div id="voting-controls-group" class="inline-flex items-center rounded-xl bg-slate-100 dark:bg-slate-800 p-0.5 border border-slate-200/90 dark:border-slate-700 shadow-2xs space-x-0.5">
                    <button 
                        type="button" 
                        id="btn-status-started"
                        onclick="handleVotingStatusAction('STARTED')"
                        title="Start / Resume Voting System (LIVE)"
                        class="px-2.5 py-1.5 rounded-lg text-xs font-extrabold transition-all cursor-pointer flex items-center space-x-1 {{ $adminVotingStatus === 'STARTED' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-emerald-700 hover:bg-white dark:hover:bg-slate-700' }}"
                    >
                        <span>●</span>
                        <span>LIVE</span>
                    </button>
                    <button 
                        type="button" 
                        id="btn-status-paused"
                        onclick="handleVotingStatusAction('PAUSED')"
                        title="Pause Voting System"
                        class="px-2.5 py-1.5 rounded-lg text-xs font-extrabold transition-all cursor-pointer flex items-center space-x-1 {{ $adminVotingStatus === 'PAUSED' ? 'bg-amber-500 text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-amber-700 hover:bg-white dark:hover:bg-slate-700' }}"
                    >
                        <span>⏸</span>
                        <span>PAUSE</span>
                    </button>
                    <button 
                        type="button" 
                        id="btn-status-stopped"
                        onclick="handleVotingStatusAction('STOPPED')"
                        title="Officially End Voting System (FINISHED)"
                        class="px-2.5 py-1.5 rounded-lg text-xs font-extrabold transition-all cursor-pointer flex items-center space-x-1 {{ $adminVotingStatus === 'STOPPED' ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-rose-700 hover:bg-white dark:hover:bg-slate-700' }}"
                    >
                        <span>⏹</span>
                        <span>END</span>
                    </button>
                </div>
            </div>

            <!-- Center Command Palette Search Bar -->
            <div class="flex-1 max-w-sm mx-4 hidden md:flex items-center justify-center">
                <button 
                    type="button" 
                    onclick="openAdminCommandPalette()" 
                    class="w-full flex items-center justify-between px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200/90 dark:bg-slate-800 dark:hover:bg-slate-700/80 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 text-xs font-medium transition cursor-pointer shadow-2xs"
                >
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>Cari menu atau fitur...</span>
                    </div>
                    <kbd class="px-1.5 py-0.5 rounded bg-white dark:bg-slate-900 text-[10px] font-mono text-slate-400 dark:text-slate-500 border border-slate-200 dark:border-slate-700 font-bold shadow-2xs">Ctrl K</kbd>
                </button>
            </div>

            <!-- Right Header Actions (Search Mobile, Theme Switcher, Language, Live SSE) -->
            <div class="flex items-center space-x-2.5 text-xs text-slate-600 dark:text-slate-300">
                <!-- Mobile Search Button -->
                <button 
                    type="button" 
                    onclick="openAdminCommandPalette()" 
                    class="md:hidden p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer"
                    title="Cari"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>

                <!-- Admin Theme Switcher (Light / Dark / System - Admin panel only) -->
                <div class="relative inline-block text-left" id="admin-theme-dropdown-wrap">
                    <button 
                        type="button" 
                        id="admin-theme-btn" 
                        onclick="toggleAdminThemeDropdown()" 
                        class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 transition cursor-pointer shadow-2xs flex items-center justify-center"
                        title="Tema Admin Panel"
                    >
                        <span id="admin-theme-icon" class="text-sm">🌓</span>
                    </button>
                    <div id="admin-theme-menu" class="hidden absolute right-0 mt-2 w-36 rounded-2xl bg-white dark:bg-slate-800 shadow-xl border border-slate-200 dark:border-slate-700 py-1.5 z-50 text-xs font-semibold">
                        <button type="button" onclick="setAdminTheme('light')" class="w-full flex items-center space-x-2 px-3 py-2 text-left hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 cursor-pointer">
                            <span>☀️</span><span>Terang</span>
                        </button>
                        <button type="button" onclick="setAdminTheme('dark')" class="w-full flex items-center space-x-2 px-3 py-2 text-left hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 cursor-pointer">
                            <span>🌙</span><span>Gelap</span>
                        </button>
                        <button type="button" onclick="setAdminTheme('system')" class="w-full flex items-center space-x-2 px-3 py-2 text-left hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 cursor-pointer">
                            <span>💻</span><span>Sistem</span>
                        </button>
                    </div>
                </div>

                <!-- Language Switcher EN / ID -->
                <div class="inline-flex items-center rounded-xl bg-slate-100 dark:bg-slate-800 p-0.5 border border-slate-200 dark:border-slate-700 text-xs font-extrabold">
                    <a href="{{ route('lang.switch', 'en') }}" class="px-2.5 py-1 rounded-lg transition {{ app()->getLocale() === 'en' ? 'bg-blue-600 text-white shadow-2xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">EN</a>
                    <a href="{{ route('lang.switch', 'id') }}" class="px-2.5 py-1 rounded-lg transition {{ app()->getLocale() === 'id' ? 'bg-blue-600 text-white shadow-2xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">ID</a>
                </div>

                <a href="{{ route('home') }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-blue-50 text-blue-700 font-bold border border-slate-200 transition flex items-center space-x-1.5 shadow-2xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    <span>Live SSE</span>
                </a>
            </div>
        </header>

        <!-- Flash Alert Messages -->
        @if(session('success'))
            <div class="m-4 sm:m-6 mb-0 p-4 rounded-2xl bg-emerald-50 border-2 border-emerald-400 text-emerald-900 text-sm font-bold flex items-center space-x-3 shadow-2xs">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div class="m-4 sm:m-6 mb-0 p-4 rounded-2xl bg-rose-50 border-2 border-rose-400 text-rose-900 text-sm font-bold flex items-center space-x-3 shadow-2xs">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                <span>{{ session('error') ?? $errors->first() }}</span>
            </div>
        @endif

        <main class="p-4 sm:p-6 lg:p-8 flex-1">
            @yield('content')
        </main>
    </div>

    <!-- HeadlessUI-style Confirmation Modal -->
    <div id="headless-confirm-modal" class="fixed inset-0 z-50 hidden transition-opacity duration-200" aria-modal="true" role="dialog">
        <!-- Backdrop -->
        <div id="headless-modal-backdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity opacity-0"></div>

        <!-- Dialog Container -->
        <div class="fixed inset-0 z-10 overflow-y-auto flex items-center justify-center p-4">
            <div id="headless-modal-panel" class="relative bg-white rounded-3xl p-6 sm:p-7 max-w-md w-full shadow-2xl border border-slate-200 transform scale-95 opacity-0 transition-all duration-200">
                <div class="flex items-start space-x-4">
                    <div id="headless-modal-icon-wrap" class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0">
                        <!-- Icon will be inserted here -->
                    </div>
                    <div class="flex-1">
                        <h3 id="headless-modal-title" class="text-base font-black text-slate-900">Konfirmasi Status Voting</h3>
                        <p id="headless-modal-desc" class="text-xs text-slate-500 mt-1 leading-relaxed">Deskripsi konfirmasi aksi.</p>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end space-x-2.5">
                    <button 
                        type="button" 
                        id="headless-modal-btn-cancel"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 transition cursor-pointer"
                    >
                        Batal
                    </button>
                    <button 
                        type="button" 
                        id="headless-modal-btn-confirm"
                        class="px-5 py-2.5 rounded-xl text-xs font-black text-white shadow-xs transition cursor-pointer"
                    >
                        Ya, Lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            if (window.innerWidth < 1024) {
                // Mobile & iPad Mini Drawer Toggle
                if (sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.remove('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                }
            } else {
                // Desktop toggle collapse
                sidebar.classList.toggle('lg:hidden');
            }
        }

        // HeadlessUI-style Promise-based modal confirmation
        function showHeadlessConfirm({ title, description, confirmText, type = 'warning' }) {
            return new Promise((resolve) => {
                const modal = document.getElementById('headless-confirm-modal');
                const backdrop = document.getElementById('headless-modal-backdrop');
                const panel = document.getElementById('headless-modal-panel');
                const titleEl = document.getElementById('headless-modal-title');
                const descEl = document.getElementById('headless-modal-desc');
                const iconWrap = document.getElementById('headless-modal-icon-wrap');
                const btnConfirm = document.getElementById('headless-modal-btn-confirm');
                const btnCancel = document.getElementById('headless-modal-btn-cancel');

                titleEl.textContent = title;
                descEl.textContent = description;
                btnConfirm.textContent = confirmText || 'Konfirmasi';

                if (type === 'danger') {
                    iconWrap.className = 'w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 bg-rose-100 text-rose-600';
                    iconWrap.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
                    btnConfirm.className = 'px-5 py-2.5 rounded-xl text-xs font-black text-white shadow-xs transition cursor-pointer bg-rose-600 hover:bg-rose-700';
                } else if (type === 'warning') {
                    iconWrap.className = 'w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 bg-amber-100 text-amber-600';
                    iconWrap.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                    btnConfirm.className = 'px-5 py-2.5 rounded-xl text-xs font-black text-white shadow-xs transition cursor-pointer bg-amber-500 hover:bg-amber-600';
                } else {
                    iconWrap.className = 'w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 bg-emerald-100 text-emerald-600';
                    iconWrap.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                    btnConfirm.className = 'px-5 py-2.5 rounded-xl text-xs font-black text-white shadow-xs transition cursor-pointer bg-emerald-600 hover:bg-emerald-700';
                }

                modal.classList.remove('hidden');
                requestAnimationFrame(() => {
                    backdrop.classList.remove('opacity-0');
                    backdrop.classList.add('opacity-100');
                    panel.classList.remove('scale-95', 'opacity-0');
                    panel.classList.add('scale-100', 'opacity-100');
                });

                function cleanup(confirmed) {
                    backdrop.classList.remove('opacity-100');
                    backdrop.classList.add('opacity-0');
                    panel.classList.remove('scale-100', 'opacity-100');
                    panel.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => {
                        modal.classList.add('hidden');
                        btnConfirm.onclick = null;
                        btnCancel.onclick = null;
                        resolve(confirmed);
                    }, 200);
                }

                btnConfirm.onclick = () => cleanup(true);
                btnCancel.onclick = () => cleanup(false);
            });
        }

        // Throttled Voting Status Action (Prevents double clicks and provides instant feedback)
        let isVotingStatusUpdating = false;

        async function handleVotingStatusAction(newStatus) {
            if (isVotingStatusUpdating) {
                console.warn('Voting status update throttled. Please wait...');
                return;
            }

            if (newStatus === 'PAUSED') {
                const confirmed = await showHeadlessConfirm({
                    title: 'Jeda Pemungutan Suara (PAUSE)?',
                    description: 'Sistem pemungutan suara akan dijeda sementara. Seluruh bilik suara dan terminal NFC tidak akan menerima tap kartu sampai diaktifkan kembali.',
                    confirmText: 'Ya, Jeda Sistem',
                    type: 'warning'
                });
                if (!confirmed) return;
            } else if (newStatus === 'STOPPED') {
                const confirmed = await showHeadlessConfirm({
                    title: 'Akhiri Pemungutan Suara (END)?',
                    description: 'PERINGATAN RESMI: Pemungutan suara akan diakhiri secara resmi (FINISHED) dan seluruh sesi bilik ditutup. Anda dapat mengunduh Rekapitulasi Berita Acara resmi setelah ini.',
                    confirmText: 'Ya, Akhiri Resmi (END)',
                    type: 'danger'
                });
                if (!confirmed) return;
            } else if (newStatus === 'STARTED') {
                const confirmed = await showHeadlessConfirm({
                    title: 'Aktifkan Sesi Pemungutan Suara (LIVE)?',
                    description: 'Terminal bilik suara akan dibuka dan siap menerima pemilih untuk melakukan tap kartu RFID Mifare.',
                    confirmText: 'Buka Sesi (LIVE)',
                    type: 'success'
                });
                if (!confirmed) return;
            }

            isVotingStatusUpdating = true;
            const group = document.getElementById('voting-controls-group');
            const btns = group ? group.querySelectorAll('button') : [];
            btns.forEach(b => {
                b.disabled = true;
                b.classList.add('opacity-50', 'cursor-not-allowed');
            });

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const response = await fetch("{{ route('admin.voting.status') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ status: newStatus })
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    updateVotingStatusBadgeUI(newStatus);
                    if (window.SoundEffects) window.SoundEffects.click();
                } else {
                    alert(data.message || 'Gagal mengubah status voting.');
                }
            } catch (err) {
                console.error('Error updating voting status:', err);
                alert('Terjadi kesalahan koneksi saat mengubah status sistem voting.');
            } finally {
                setTimeout(() => {
                    btns.forEach(b => {
                        b.disabled = false;
                        b.classList.remove('opacity-50', 'cursor-not-allowed');
                    });
                    isVotingStatusUpdating = false;
                }, 800); // 800ms cooldown throttle
            }
        }

        function updateVotingStatusBadgeUI(status) {
            const badge = document.getElementById('voting-status-badge');
            const dot = document.getElementById('voting-status-dot');
            const text = document.getElementById('voting-status-text');
            const btnStarted = document.getElementById('btn-status-started');
            const btnPaused = document.getElementById('btn-status-paused');
            const btnStopped = document.getElementById('btn-status-stopped');

            let displayLabel = 'LIVE';
            if (status === 'PAUSED') displayLabel = 'PAUSED';
            else if (status === 'STOPPED') displayLabel = 'FINISHED';

            if (text) text.innerText = displayLabel;

            // Reset active button classes
            const defaultBtnClass = 'px-2.5 py-1.5 rounded-lg text-xs font-extrabold transition-all cursor-pointer flex items-center space-x-1 text-slate-600 hover:bg-white';
            if (btnStarted) btnStarted.className = defaultBtnClass + ' hover:text-emerald-700';
            if (btnPaused) btnPaused.className = defaultBtnClass + ' hover:text-amber-700';
            if (btnStopped) btnStopped.className = defaultBtnClass + ' hover:text-rose-700';

            if (status === 'STARTED') {
                if (badge) badge.className = 'inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-xl text-xs font-black tracking-wide uppercase bg-emerald-50 text-emerald-800 border border-emerald-300';
                if (dot) dot.className = 'w-2 h-2 rounded-full bg-emerald-500 animate-pulse';
                if (btnStarted) btnStarted.className = 'px-2.5 py-1.5 rounded-lg text-xs font-extrabold transition-all cursor-pointer flex items-center space-x-1 bg-emerald-600 text-white shadow-xs';
            } else if (status === 'PAUSED') {
                if (badge) badge.className = 'inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-xl text-xs font-black tracking-wide uppercase bg-amber-50 text-amber-800 border border-amber-300';
                if (dot) dot.className = 'w-2 h-2 rounded-full bg-amber-500 animate-ping';
                if (btnPaused) btnPaused.className = 'px-2.5 py-1.5 rounded-lg text-xs font-extrabold transition-all cursor-pointer flex items-center space-x-1 bg-amber-500 text-white shadow-xs';
            } else {
                if (badge) badge.className = 'inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-xl text-xs font-black tracking-wide uppercase bg-rose-50 text-rose-800 border border-rose-300';
                if (dot) dot.className = 'w-2 h-2 rounded-full bg-rose-500';
                if (btnStopped) btnStopped.className = 'px-2.5 py-1.5 rounded-lg text-xs font-extrabold transition-all cursor-pointer flex items-center space-x-1 bg-rose-600 text-white shadow-xs';
            }
        }

        // ==========================================
        // ADMIN THEME SWITCHER (Isolated to Admin)
        // ==========================================
        function toggleAdminThemeDropdown() {
            const menu = document.getElementById('admin-theme-menu');
            if (menu) menu.classList.toggle('hidden');
        }

        function setAdminTheme(theme) {
            localStorage.setItem('tapvote_admin_theme', theme);
            const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            const icon = document.getElementById('admin-theme-icon');
            if (isDark) {
                document.documentElement.classList.add('dark', 'admin-dark');
                if (icon) icon.textContent = '🌙';
            } else {
                document.documentElement.classList.remove('dark', 'admin-dark');
                if (icon) icon.textContent = '☀️';
            }
            if (theme === 'system' && icon) icon.textContent = '🌓';
            const menu = document.getElementById('admin-theme-menu');
            if (menu) menu.classList.add('hidden');
        }

        // Close dropdown on outside click
        document.addEventListener('click', function(e) {
            const wrap = document.getElementById('admin-theme-dropdown-wrap');
            const menu = document.getElementById('admin-theme-menu');
            if (wrap && menu && !wrap.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });

        // Initialize Theme Icon
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('tapvote_admin_theme') || 'system';
            const icon = document.getElementById('admin-theme-icon');
            if (icon) {
                if (savedTheme === 'dark') icon.textContent = '🌙';
                else if (savedTheme === 'light') icon.textContent = '☀️';
                else icon.textContent = '🌓';
            }
            renderCommandResults(commandItems);
        });

        // ==========================================
        // COMMAND PALETTE SEARCH (Ctrl+K or /)
        // ==========================================
        const commandItems = [
            { title: 'Live Dashboard', group: 'Monitoring', icon: '📊', url: '{{ route("admin.dashboard") }}' },
            { title: 'Analytics (Mata Langit Telemetri)', group: 'Monitoring', icon: '🛰️', url: '{{ route("admin.analytics") }}' },
            { title: 'Vote Flow (Broker Summary)', group: 'Monitoring', icon: '🌊', url: '{{ route("admin.vote-flow") }}' },
            { title: 'Chairman Candidates (Kandidat Ketua)', group: 'Master Data', icon: '👤', url: '{{ route("admin.ketua.index") }}' },
            { title: 'Supervisor Candidates (Kandidat Pengawas)', group: 'Master Data', icon: '👥', url: '{{ route("admin.pengawas.index") }}' },
            { title: 'Eligible Voters (DPT Pemilih)', group: 'Master Data', icon: '📋', url: '{{ route("admin.voters.index") }}' },
            { title: 'Chairman Recap Report (Rekapitulasi Ketua)', group: 'Reports', icon: '📜', url: '{{ route("admin.reports.ketua") }}' },
            { title: 'Supervisor Recap Report (Rekapitulasi Pengawas)', group: 'Reports', icon: '📑', url: '{{ route("admin.reports.pengawas") }}' },
            { title: 'Forensic Traceback (Audit Suara)', group: 'Reports', icon: '🔍', url: '{{ route("admin.reports.traceback") }}' },
            { title: 'Doorprize Raffle & Undian', group: 'Reports', icon: '🎁', url: '{{ route("admin.reports.doorprize") }}' },
            { title: 'Audit Trail Logs', group: 'Reports', icon: '🛡️', url: '{{ route("admin.logs.index") }}' },
            { title: 'System Settings (Deadline & Konfigurasi)', group: 'System', icon: '⚙️', url: '{{ route("admin.settings") }}' },
            { title: 'Buka Voting Terminal (RFID Scan)', group: 'Terminal', icon: '💳', url: '{{ route("voter.tap") }}' },
            { title: 'Audience Stage View Doorprize', group: 'Terminal', icon: '📺', url: '{{ route("doorprize.public") }}' },
            { title: 'Public Live Stream Screen', group: 'Public', icon: '🌐', url: '{{ route("home") }}' }
        ];

        let selectedCommandIndex = 0;
        let currentFilteredCommands = [...commandItems];

        function openAdminCommandPalette() {
            const modal = document.getElementById('admin-command-palette-modal');
            const input = document.getElementById('admin-command-input');
            if (modal) {
                modal.classList.remove('hidden');
                selectedCommandIndex = 0;
                filterCommandPalette('');
                if (input) {
                    input.value = '';
                    setTimeout(() => input.focus(), 50);
                }
            }
        }

        function closeAdminCommandPalette() {
            const modal = document.getElementById('admin-command-palette-modal');
            if (modal) modal.classList.add('hidden');
        }

        function filterCommandPalette(query) {
            const q = query.toLowerCase().trim();
            if (!q) {
                currentFilteredCommands = [...commandItems];
            } else {
                currentFilteredCommands = commandItems.filter(item => 
                    item.title.toLowerCase().includes(q) || item.group.toLowerCase().includes(q)
                );
            }
            selectedCommandIndex = 0;
            renderCommandResults(currentFilteredCommands);
        }

        function renderCommandResults(items) {
            const container = document.getElementById('admin-command-results');
            if (!container) return;

            if (items.length === 0) {
                container.innerHTML = `
                    <div class="p-6 text-center text-xs text-slate-400">
                        Tidak ada fitur atau menu yang cocok dengan pencarian.
                    </div>
                `;
                return;
            }

            container.innerHTML = items.map((item, idx) => `
                <a 
                    href="${item.url}" 
                    id="cmd-item-${idx}"
                    class="flex items-center justify-between px-3 py-2.5 rounded-2xl transition cursor-pointer ${idx === selectedCommandIndex ? 'bg-blue-600 text-white' : 'hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200'}"
                    onmouseover="selectCommandIndex(${idx})"
                >
                    <div class="flex items-center space-x-3">
                        <span class="text-base">${item.icon}</span>
                        <div>
                            <span class="font-bold text-xs block leading-tight">${item.title}</span>
                            <span class="text-[10px] ${idx === selectedCommandIndex ? 'text-blue-100' : 'text-slate-400 dark:text-slate-500'} uppercase font-semibold">${item.group}</span>
                        </div>
                    </div>
                    <span class="text-xs ${idx === selectedCommandIndex ? 'text-white' : 'text-slate-400'}">→</span>
                </a>
            `).join('');
        }

        function selectCommandIndex(idx) {
            selectedCommandIndex = idx;
            const items = document.querySelectorAll('[id^="cmd-item-"]');
            items.forEach((el, i) => {
                if (i === idx) {
                    el.className = 'flex items-center justify-between px-3 py-2.5 rounded-2xl transition cursor-pointer bg-blue-600 text-white';
                } else {
                    el.className = 'flex items-center justify-between px-3 py-2.5 rounded-2xl transition cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200';
                }
            });
        }

        function handleCommandKeydown(e) {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (selectedCommandIndex < currentFilteredCommands.length - 1) {
                    selectCommandIndex(selectedCommandIndex + 1);
                    document.getElementById(`cmd-item-${selectedCommandIndex}`)?.scrollIntoView({ block: 'nearest' });
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (selectedCommandIndex > 0) {
                    selectCommandIndex(selectedCommandIndex - 1);
                    document.getElementById(`cmd-item-${selectedCommandIndex}`)?.scrollIntoView({ block: 'nearest' });
                }
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (currentFilteredCommands[selectedCommandIndex]) {
                    window.location.href = currentFilteredCommands[selectedCommandIndex].url;
                }
            } else if (e.key === 'Escape') {
                closeAdminCommandPalette();
            }
        }

        // Global shortcut Ctrl+K and /
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                openAdminCommandPalette();
            } else if (e.key === '/' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA' && document.activeElement.tagName !== 'SELECT') {
                e.preventDefault();
                openAdminCommandPalette();
            } else if (e.key === 'Escape') {
                closeAdminCommandPalette();
            }
        });
    </script>

    <!-- Admin Command Palette Modal (Ctrl+K or /) -->
    <div id="admin-command-palette-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
        <div onclick="closeAdminCommandPalette()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 md:p-20 flex justify-center items-start">
            <div class="w-full max-w-xl transform rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xl transition-all overflow-hidden">
                <div class="relative border-b border-slate-200 dark:border-slate-800">
                    <svg class="pointer-events-none absolute top-4 left-4 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input 
                        type="text" 
                        id="admin-command-input" 
                        placeholder="Cari semua fitur admin, laporan, pengaturan..." 
                        class="h-13 w-full pl-12 pr-12 bg-transparent text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none"
                        oninput="filterCommandPalette(this.value)"
                        onkeydown="handleCommandKeydown(event)"
                    >
                    <button onclick="closeAdminCommandPalette()" class="absolute top-3.5 right-4 p-1 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xs font-bold cursor-pointer">
                        ESC
                    </button>
                </div>

                <div id="admin-command-results" class="max-h-80 overflow-y-auto p-2 space-y-1">
                    <!-- Populated dynamically -->
                </div>

                <div class="px-4 py-2.5 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                    <div class="flex items-center space-x-3">
                        <span><kbd class="px-1 py-0.5 rounded bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 font-mono text-[10px]">↑</kbd> <kbd class="px-1 py-0.5 rounded bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 font-mono text-[10px]">↓</kbd> Navigasi</span>
                        <span><kbd class="px-1 py-0.5 rounded bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 font-mono text-[10px]">↵</kbd> Buka</span>
                    </div>
                    <span>TapVote AI Quick Navigator</span>
                </div>
            </div>
        </div>
    </div>
    @stack('scripts')
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(() => {});
            });
        }

        let pwaDeferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            pwaDeferredPrompt = e;
            const btn = document.getElementById('pwa-install-btn');
            if (btn) {
                btn.classList.remove('hidden');
                btn.classList.add('inline-flex');
                btn.onclick = async () => {
                    if (pwaDeferredPrompt) {
                        pwaDeferredPrompt.prompt();
                        const { outcome } = await pwaDeferredPrompt.userChoice;
                        if (outcome === 'accepted') {
                            btn.classList.add('hidden');
                        }
                        pwaDeferredPrompt = null;
                    }
                };
            }
        });
    </script>
</body>
</html>
