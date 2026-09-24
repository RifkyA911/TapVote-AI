<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - TapVote AI</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-full bg-slate-100 text-slate-800 font-sans antialiased flex flex-col md:flex-row relative overflow-x-hidden">

    <!-- Mobile / Tablet Sidebar Backdrop Overlay -->
    <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-30 hidden md:hidden transition-opacity duration-300"></div>

    <!-- Sidebar Navigation -->
    <aside id="admin-sidebar" class="fixed md:static inset-y-0 left-0 z-40 w-64 bg-white border-r border-slate-200 flex flex-col shrink-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out shadow-lg md:shadow-none h-full">
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

            <!-- Close Sidebar Button on Mobile/Tablet -->
            <button onclick="toggleSidebar()" type="button" class="md:hidden p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 cursor-pointer">
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

            <div class="text-[11px] uppercase tracking-wider text-slate-400 font-bold px-3 pt-4 pb-1">Master Data</div>
            <a href="{{ route('admin.ketua.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.ketua.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span>Calon Ketua</span>
            </a>
            <a href="{{ route('admin.pengawas.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.pengawas.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                <span>Calon Pengawas</span>
            </a>
            <a href="{{ route('admin.voters.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.voters.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span>Data Pemilih (DPT)</span>
            </a>

            <div class="text-[11px] uppercase tracking-wider text-slate-400 font-bold px-3 pt-4 pb-1">Laporan & Rekapitulasi</div>
            <a href="{{ route('admin.reports.ketua') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.reports.ketua') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <span>Rekapitulasi Ketua</span>
            </a>
            <a href="{{ route('admin.reports.pengawas') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.reports.pengawas') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path></svg>
                <span>Rekapitulasi Pengawas</span>
            </a>
            <a href="{{ route('admin.reports.traceback') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.reports.traceback*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <span>Audit Pilihan Suara</span>
            </a>
            <a href="{{ route('admin.reports.doorprize') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.reports.doorprize') ? 'bg-amber-600 text-white shadow-sm' : 'text-amber-700 hover:bg-amber-50' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                <span>Undian Doorprize</span>
            </a>
            <a href="{{ route('admin.logs.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.logs.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Audit Logs Sistem</span>
            </a>
        </nav>

        <!-- Quick Switch to Voter Kiosk & Logout -->
        <div class="p-4 border-t border-slate-200 space-y-2 bg-slate-50 shrink-0">
            <a href="{{ route('voter.tap') }}" target="_blank" class="w-full flex items-center justify-center space-x-2 py-2.5 px-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-xs font-bold text-white shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                <span>Buka Kios Pemilih (Tap)</span>
            </a>

            <div class="flex items-center justify-between pt-2">
                <div class="text-xs text-slate-600 truncate pr-2">
                    <span class="block text-slate-900 font-bold truncate">{{ auth()->user()->name }}</span>
                    <span class="text-[11px] text-slate-500">Administrator</span>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Keluar / Logout" class="p-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-600 hover:text-white transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Viewport -->
    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-y-auto">
        <!-- Top App Bar (Matched Height: h-16) -->
        <header class="sticky top-0 z-20 h-16 bg-white border-b border-slate-200 px-4 sm:px-6 flex items-center justify-between shadow-2xs shrink-0">
            <div class="flex items-center space-x-3">
                <!-- Toggle Sidebar Button (Visible on mobile/tablet and desktop) -->
                <button 
                    onclick="toggleSidebar()" 
                    type="button" 
                    id="sidebar-toggle-btn"
                    title="Toggle Sidebar"
                    class="p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200 transition cursor-pointer flex items-center justify-center"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                    <span class="w-2 h-2 mr-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                    Sistem Aktif
                </span>
                <span class="text-xs text-slate-500 hidden sm:inline">DB: <strong class="text-slate-800 font-mono">tapvote_ai</strong></span>
            </div>

            <div class="flex items-center space-x-3 sm:space-x-4 text-xs text-slate-600">
                <!-- Language Switcher Pill -->
                <div class="inline-flex rounded-lg border border-slate-200 bg-white p-0.5 text-xs font-bold shadow-2xs">
                    <a href="{{ route('lang.switch', 'en') }}" class="px-2 py-1 rounded-md transition {{ app()->getLocale() === 'en' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-blue-600' }}">EN</a>
                    <a href="{{ route('lang.switch', 'id') }}" class="px-2 py-1 rounded-md transition {{ app()->getLocale() === 'id' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-blue-600' }}">ID</a>
                </div>

                <div class="hidden lg:block">
                    <span id="header-clock" class="font-bold text-slate-700"></span>
                </div>

                <a href="{{ route('home') }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-blue-50 text-blue-700 font-bold border border-slate-200 transition flex items-center space-x-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    <span class="hidden sm:inline">Live SSE</span>
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

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            if (window.innerWidth < 768) {
                // Mobile / Tablet Drawer Toggle
                if (sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.remove('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                }
            } else {
                // Desktop toggle collapse
                sidebar.classList.toggle('md:hidden');
            }
        }

        function updateClock() {
            const el = document.getElementById('header-clock');
            if (el) {
                const now = new Date();
                el.innerText = now.toLocaleDateString('id-ID', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' }) + ' ' + now.toLocaleTimeString('id-ID') + ' WIB';
            }
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
    @stack('scripts')
</body>
</html>
