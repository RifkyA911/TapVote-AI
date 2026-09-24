<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TapVote AI') - E-Voting Koperasi Modern</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-full bg-slate-950 text-slate-100 font-sans antialiased selection:bg-blue-600 selection:text-white flex flex-col relative overflow-x-hidden">

    <!-- Fluid Ambient Background Glow -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute top-1/3 -right-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-40 left-1/3 w-96 h-96 bg-emerald-600/15 rounded-full blur-3xl animate-pulse" style="animation-delay: 4s;"></div>
    </div>

    <!-- Alert Notifications -->
    <div class="relative z-50">
        @if(session('success'))
            <div id="toast-success" class="fixed top-5 right-5 max-w-md bg-emerald-950/90 border border-emerald-500/50 text-emerald-200 px-5 py-4 rounded-2xl shadow-2xl backdrop-blur-xl flex items-center space-x-3 transition-all duration-300">
                <svg class="w-6 h-6 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="text-sm font-medium">{{ session('success') }}</div>
                <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white ml-auto">✕</button>
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div id="toast-error" class="fixed top-5 right-5 max-w-md bg-rose-950/90 border border-rose-500/50 text-rose-200 px-5 py-4 rounded-2xl shadow-2xl backdrop-blur-xl flex items-center space-x-3 transition-all duration-300">
                <svg class="w-6 h-6 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <div class="text-sm font-medium">
                    {{ session('error') ?? $errors->first() }}
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-white ml-auto">✕</button>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="relative z-10 flex-1 flex flex-col">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
