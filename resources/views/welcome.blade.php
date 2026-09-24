@extends('layouts.app')

@section('title', 'TapVote AI - Sistem E-Voting Pemilihan Ketua & Pengawas Koperasi')

@section('content')
<div class="flex-1 flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8 py-12">
    <!-- Hero Header -->
    <div class="text-center max-w-3xl mx-auto space-y-6">
        <div class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-400 text-xs font-semibold uppercase tracking-wider backdrop-blur-md">
            <span class="w-2 h-2 rounded-full bg-blue-400 animate-ping"></span>
            <span>Enterprise SaaS E-Voting • Realtime SSE</span>
        </div>

        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight">
            <span class="bg-gradient-to-r from-white via-slate-100 to-slate-400 bg-clip-text text-transparent">Pemilihan Umum Koperasi</span>
            <br>
            <span class="bg-gradient-to-r from-blue-400 via-indigo-400 to-emerald-400 bg-clip-text text-transparent">Cepat, Transparan & Nirsentuh</span>
        </h1>

        <p class="text-slate-400 text-base sm:text-lg max-w-2xl mx-auto leading-relaxed">
            Sistem pemungutan suara digital terintegrasi <strong>RFID Mifare ISO 14443A</strong>. Memilih Ketua dan Pengawas Koperasi secara serentak, aman, dan terpantau live real-time.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
            <a href="{{ route('voter.tap') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-base shadow-xl shadow-blue-600/30 hover:shadow-blue-600/50 transform hover:-translate-y-1 transition duration-200 flex items-center justify-center space-x-3 group">
                <svg class="w-6 h-6 text-blue-200 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004 11m0 0a8 8 0 00.99 7.132"></path></svg>
                <span>Masuk Kios Pemilih (Tap Card)</span>
            </a>

            <a href="{{ route('admin.login') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-slate-900/90 hover:bg-slate-800 border border-slate-700/80 text-slate-200 font-semibold text-base backdrop-blur-xl transition flex items-center justify-center space-x-3">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                <span>Panel Panitia / Admin</span>
            </a>
        </div>
    </div>

    <!-- Feature Highlights Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto mt-16 w-full">
        <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800/80 backdrop-blur-xl shadow-lg relative overflow-hidden group hover:border-blue-500/40 transition">
            <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center mb-4 group-hover:scale-110 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">RFID Mifare ISO 14443A</h3>
            <p class="text-sm text-slate-400 leading-relaxed">
                Autentikasi pemilih instan melalui tap ID card dengan proteksi *single-vote lock*. Dilengkapi demo card simulator.
            </p>
        </div>

        <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800/80 backdrop-blur-xl shadow-lg relative overflow-hidden group hover:border-emerald-500/40 transition">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center mb-4 group-hover:scale-110 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Dual Ballot In One Flow</h3>
            <p class="text-sm text-slate-400 leading-relaxed">
                Pemilihan serentak 1 Kandidat Ketua dan 1 Pengawas Koperasi secara atomik dengan database transaction & row locking.
            </p>
        </div>

        <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800/80 backdrop-blur-xl shadow-lg relative overflow-hidden group hover:border-indigo-500/40 transition">
            <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mb-4 group-hover:scale-110 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Realtime Live SSE Stream</h3>
            <p class="text-sm text-slate-400 leading-relaxed">
                Grafik perolehan suara live tanpa reload menggunakan Server-Sent Events, lengkap dengan trace back dan undian doorprize.
            </p>
        </div>
    </div>
</div>
@endsection
