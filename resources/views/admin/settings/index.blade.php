@extends('layouts.admin')

@section('title', 'System Settings - TapVote AI')

@section('content')
<div class="space-y-6 sm:space-y-8 max-w-5xl mx-auto">

    <!-- Top Header Banner -->
    <div class="p-6 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-3.5">
            <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">System Settings & Configuration</h2>
                <p class="text-xs sm:text-sm text-slate-500 font-medium">Pengaturan parameter pemilihan, integrasi kecerdasan buatan Gemini AI, audio & hardware terminal.</p>
            </div>
        </div>

        <div class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-mono font-bold">
            <span>TapVote v2.5 Stable</span>
        </div>
    </div>

    <!-- Form Configuration -->
    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- 1. IDENTITAS & KUORUM PEMILU -->
        <div class="p-6 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-5">
            <div class="flex items-center space-x-2 pb-3 border-b border-slate-100">
                <span class="text-lg">🏛️</span>
                <h3 class="text-base font-extrabold text-slate-900">1. Parameter Utama Pemilihan & Kuorum</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Judul Resmi Pemilihan</label>
                    <input 
                        type="text" 
                        name="election_title" 
                        required 
                        value="{{ old('election_title', $settings['election_title']) }}" 
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 font-medium focus:bg-white focus:border-blue-500 outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Institusi / Badan Koperasi</label>
                    <input 
                        type="text" 
                        name="institution_name" 
                        required 
                        value="{{ old('institution_name', $settings['institution_name']) }}" 
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 font-medium focus:bg-white focus:border-blue-500 outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Batas Minimum Kuorum Suara (%)</label>
                    <div class="relative">
                        <input 
                            type="number" 
                            step="0.1" 
                            min="1" 
                            max="100" 
                            name="quorum_percentage" 
                            required 
                            value="{{ old('quorum_percentage', $settings['quorum_percentage']) }}" 
                            class="w-full px-4 py-2.5 pr-10 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 font-mono font-bold focus:bg-white focus:border-blue-500 outline-none"
                        >
                        <span class="absolute right-3.5 top-2.5 text-xs font-bold text-slate-400">%</span>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1">Standar aturan koperasi umumnya mensyaratkan minimal 50% partisipasi anggota.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Operasional Sistem Voting</label>
                    <select name="voting_status" class="w-full h-10 px-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm font-bold text-slate-800 focus:bg-white focus:border-blue-500 outline-none cursor-pointer">
                        <option value="STARTED" {{ $settings['voting_status'] === 'STARTED' ? 'selected' : '' }}>LIVE (Bilik Terbuka & Aktif)</option>
                        <option value="PAUSED" {{ $settings['voting_status'] === 'PAUSED' ? 'selected' : '' }}>PAUSED (Bilik Di-Jeda Sementara)</option>
                        <option value="STOPPED" {{ $settings['voting_status'] === 'STOPPED' ? 'selected' : '' }}>FINISHED (Pemungutan Suara Resmi Ditutup)</option>
                    </select>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between sm:col-span-2">
                    <div>
                        <strong class="text-xs sm:text-sm text-slate-900 block font-extrabold">Public Realtime SSE Stream</strong>
                        <span class="text-[11px] text-slate-500">Izinkan publik mengakses siaran langsung Server-Sent Events di beranda utama tanpa login admin.</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer ml-3">
                        <input type="checkbox" name="public_sse_enabled" value="1" {{ ($settings['public_sse_enabled'] ?? '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
            </div>
        </div>

        <!-- 2. AI INTELLIGENCE & GOOGLE GEMINI -->
        <div class="p-6 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-5">
            <div class="flex items-center space-x-2 pb-3 border-b border-slate-100">
                <span class="text-lg">🤖</span>
                <h3 class="text-base font-extrabold text-slate-900">2. Mesin Analisis AI (Google Gemini)</h3>
            </div>

            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-slate-700">Google AI Studio Gemini API Key</label>
                        <a href="https://aistudio.google.com/app/apikey" target="_blank" class="text-xs text-blue-600 hover:underline font-bold">
                            Dapatkan API Key Gratis di Google AI Studio ↗
                        </a>
                    </div>
                    <input 
                        type="password" 
                        name="gemini_api_key" 
                        placeholder="AIzaSy..." 
                        value="{{ old('gemini_api_key', $settings['gemini_api_key']) }}" 
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 font-mono focus:bg-white focus:border-blue-500 outline-none"
                    >
                    <p class="text-[11px] text-slate-500 mt-1">API Key disimpan secara aman dan digunakan untuk analisis tren pemilu real-time.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Model Gemini yang Digunakan</label>
                    <select name="gemini_model" class="w-full h-10 px-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm font-bold text-slate-800 focus:bg-white focus:border-blue-500 outline-none cursor-pointer">
                        <option value="gemini-2.0-flash" {{ $settings['gemini_model'] === 'gemini-2.0-flash' ? 'selected' : '' }}>Google Gemini 2.0 Flash (Direkomendasikan • Cepat & Presisi)</option>
                        <option value="gemini-1.5-flash" {{ $settings['gemini_model'] === 'gemini-1.5-flash' ? 'selected' : '' }}>Google Gemini 1.5 Flash (Stabil)</option>
                        <option value="gemini-1.5-pro" {{ $settings['gemini_model'] === 'gemini-1.5-pro' ? 'selected' : '' }}>Google Gemini 1.5 Pro (Deep Reasoning)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 3. AUDIO, VOICE GREETING & HARDWARE TIMEOUT -->
        <div class="p-6 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-5">
            <div class="flex items-center space-x-2 pb-3 border-b border-slate-100">
                <span class="text-lg">🔊</span>
                <h3 class="text-base font-extrabold text-slate-900">3. Pengalaman Audio & Waktu Sesi Terminal</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                    <div>
                        <strong class="text-xs sm:text-sm text-slate-900 block font-extrabold">Sambutan Suara Otomatis (Voice Greeting)</strong>
                        <span class="text-[11px] text-slate-500">Auto-play suara sambutan "Selamat Datang Mas Admin" & panduan bilik suara.</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer ml-3">
                        <input type="checkbox" name="enable_voice_greeting" value="1" {{ $settings['enable_voice_greeting'] == '1' ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                    <div>
                        <strong class="text-xs sm:text-sm text-slate-900 block font-extrabold">Efek Suara Audio (Sound FX)</strong>
                        <span class="text-[11px] text-slate-500">Suara klik tombol, tap RFID, submit bilik, dan gong undian.</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer ml-3">
                        <input type="checkbox" name="enable_sound_fx" value="1" {{ $settings['enable_sound_fx'] == '1' ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Batas Waktu Auto-Logout Bilik Suara (Detik)</label>
                    <input 
                        type="number" 
                        name="kiosk_session_timeout" 
                        min="10" 
                        max="600" 
                        required 
                        value="{{ old('kiosk_session_timeout', $settings['kiosk_session_timeout']) }}" 
                        class="w-full sm:w-64 px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 font-mono font-bold focus:bg-white focus:border-blue-500 outline-none"
                    >
                    <p class="text-[11px] text-slate-500 mt-1">Sesi akan otomatis ditutup jika pemilih tidak melakukan aktivitas dalam bilik.</p>
                </div>
            </div>
        </div>

        <!-- 4. PWA & OFFLINE DIAGNOSTICS -->
        <div class="p-6 sm:p-7 rounded-3xl bg-slate-900 text-white border border-slate-800 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <div class="flex items-center space-x-2">
                    <span class="text-lg">📲</span>
                    <h3 class="text-base font-extrabold text-white">4. Status Progressive Web App (PWA)</h3>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                    Install Ready
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Web Manifest</span>
                    <strong class="text-white font-mono">/manifest.json (200 OK)</strong>
                </div>
                <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Service Worker</span>
                    <strong class="text-emerald-400 font-mono">/sw.js (Cache-First + Offline)</strong>
                </div>
                <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">PWA Icon Assets</span>
                    <strong class="text-blue-400 font-mono">192px, 512px, Maskable OK</strong>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end space-x-3 pt-2">
            <button 
                type="submit" 
                class="px-8 py-3.5 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-black text-sm shadow-lg hover:shadow-xl transition cursor-pointer flex items-center space-x-2"
            >
                <span>💾 Simpan Konfigurasi Sistem</span>
            </button>
        </div>

    </form>
</div>
@endsection
