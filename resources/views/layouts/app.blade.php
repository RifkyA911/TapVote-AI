<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TapVote AI') - {{ __('Live Count Cooperative Election') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-full bg-slate-50 text-slate-800 font-sans antialiased selection:bg-blue-600 selection:text-white flex flex-col relative overflow-x-hidden">

    @hasSection('custom_backdrop')
        @yield('custom_backdrop')
    @endif

    <!-- Alert Notifications with Audio Triggers -->
    <div class="relative z-50">
        @if(session('success'))
            <div id="toast-success" class="fixed top-5 right-5 max-w-lg bg-emerald-50 border-2 border-emerald-500 text-emerald-950 px-5 py-3.5 rounded-2xl shadow-xl flex items-center space-x-3 transition-all duration-300">
                <svg class="w-6 h-6 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="text-sm font-bold">{{ session('success') }}</div>
                <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 text-lg font-bold ml-auto px-2">✕</button>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    setTimeout(() => window.SoundEffects && window.SoundEffects.success(), 100);
                });
            </script>
        @endif

        @if(session('error') || $errors->any())
            <div id="toast-error" class="fixed top-5 right-5 max-w-lg bg-rose-50 border-2 border-rose-500 text-rose-950 px-5 py-3.5 rounded-2xl shadow-xl flex items-center space-x-3 transition-all duration-300">
                <svg class="w-6 h-6 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <div class="text-sm font-bold">
                    {{ session('error') ?? $errors->first() }}
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-700 hover:text-rose-950 text-lg font-bold ml-auto px-2">✕</button>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    setTimeout(() => window.SoundEffects && window.SoundEffects.error(), 100);
                });
            </script>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="relative z-10 flex-1 flex flex-col">
        @yield('content')
    </main>

    @stack('scripts')
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(() => {});
            });
        }
    </script>
</body>
</html>
