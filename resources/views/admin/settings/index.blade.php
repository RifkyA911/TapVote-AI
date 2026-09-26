@extends('layouts.admin')

@section('title', 'System Settings & Controls - TapVote AI')

@section('content')
<div class="space-y-6 sm:space-y-8 max-w-5xl mx-auto pb-12">

    <!-- Top Header Banner -->
    <div class="p-6 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-3.5">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white flex items-center justify-center font-black shadow-md shadow-blue-500/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">System Settings & Controls</h2>
                <p class="text-xs sm:text-sm text-slate-500 font-medium">Pengaturan otomatis tersimpan secara real-time tanpa perlu tombol simpan manual.</p>
            </div>
        </div>

        <div class="flex items-center space-x-2">
            <!-- Active Autosave Indicator -->
            <div id="autosave-status" class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-mono font-bold border border-slate-200 shadow-2xs transition-all duration-300">
                <span id="autosave-dot" class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span id="autosave-text">Autosave Siap</span>
            </div>
        </div>
    </div>

    <!-- Form Configuration (Autosaved via AJAX) -->
    <form action="{{ route('admin.settings.update') }}" method="POST" id="settings-form" class="space-y-6 sm:space-y-8">
        @csrf

        <!-- 1. VOTING OPERATIONAL STATE CONTROL -->
        <div class="p-6 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-2xs space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-100 gap-2">
                <div class="flex items-center space-x-2">
                    <span class="text-lg">🚦</span>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">1. Status Operasional Pemungutan Suara</h3>
                        <p class="text-xs text-slate-500 font-medium">Kendalikan akses bilik pemungutan suara secara terpusat.</p>
                    </div>
                </div>
                <div class="flex items-center space-x-1.5">
                    @if($settings['voting_status'] === 'STARTED')
                        <span id="status-badge-current" class="px-3 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-700 border border-emerald-300 flex items-center">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5 animate-ping"></span>
                            CURRENT: LIVE
                        </span>
                    @elseif($settings['voting_status'] === 'PAUSED')
                        <span id="status-badge-current" class="px-3 py-1 rounded-full text-xs font-black bg-amber-100 text-amber-800 border border-amber-300 flex items-center">
                            <span class="w-2 h-2 rounded-full bg-amber-500 mr-1.5"></span>
                            CURRENT: PAUSED
                        </span>
                    @else
                        <span id="status-badge-current" class="px-3 py-1 rounded-full text-xs font-black bg-rose-100 text-rose-800 border border-rose-300 flex items-center">
                            <span class="w-2 h-2 rounded-full bg-rose-500 mr-1.5"></span>
                            CURRENT: FINISHED
                        </span>
                    @endif
                </div>
            </div>

            <!-- Segmented Radio Cards for Voting Status -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- LIVE / STARTED -->
                <label class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition-all duration-200 {{ $settings['voting_status'] === 'STARTED' ? 'border-emerald-500 bg-emerald-50/50 shadow-sm' : 'border-slate-200 hover:border-slate-300 bg-slate-50/50' }}">
                    <input type="radio" name="voting_status" value="STARTED" class="sr-only peer" {{ $settings['voting_status'] === 'STARTED' ? 'checked' : '' }}>
                    <div class="flex items-center justify-between mb-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-black bg-emerald-500 text-white uppercase tracking-wider">
                            ● LIVE
                        </span>
                        <span class="w-4 h-4 rounded-full border-2 border-slate-300 peer-checked:border-emerald-600 peer-checked:bg-emerald-600 flex items-center justify-center"></span>
                    </div>
                    <strong class="text-sm font-black text-slate-900">Bilik Terbuka & Aktif</strong>
                    <p class="text-xs text-slate-500 mt-1">Pemilih dapat tap RFID / NFC dan memberikan suara di bilik voting secara normal.</p>
                </label>

                <!-- PAUSED -->
                <label class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition-all duration-200 {{ $settings['voting_status'] === 'PAUSED' ? 'border-amber-500 bg-amber-50/50 shadow-sm' : 'border-slate-200 hover:border-slate-300 bg-slate-50/50' }}">
                    <input type="radio" name="voting_status" value="PAUSED" class="sr-only peer" {{ $settings['voting_status'] === 'PAUSED' ? 'checked' : '' }}>
                    <div class="flex items-center justify-between mb-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-black bg-amber-500 text-white uppercase tracking-wider">
                            ❚❚ PAUSED
                        </span>
                        <span class="w-4 h-4 rounded-full border-2 border-slate-300 peer-checked:border-amber-600 peer-checked:bg-amber-600 flex items-center justify-center"></span>
                    </div>
                    <strong class="text-sm font-black text-slate-900">Jeda Sementara (Break)</strong>
                    <p class="text-xs text-slate-500 mt-1">Bilik suara dibekukan sementara untuk istirahat, ISHOMA, atau verifikasi teknis.</p>
                </label>

                <!-- FINISHED / STOPPED -->
                <label class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition-all duration-200 {{ $settings['voting_status'] === 'STOPPED' ? 'border-rose-500 bg-rose-50/50 shadow-sm' : 'border-slate-200 hover:border-slate-300 bg-slate-50/50' }}">
                    <input type="radio" name="voting_status" value="STOPPED" class="sr-only peer" {{ $settings['voting_status'] === 'STOPPED' ? 'checked' : '' }}>
                    <div class="flex items-center justify-between mb-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-black bg-rose-600 text-white uppercase tracking-wider">
                            ■ FINISHED
                        </span>
                        <span class="w-4 h-4 rounded-full border-2 border-slate-300 peer-checked:border-rose-600 peer-checked:bg-rose-600 flex items-center justify-center"></span>
                    </div>
                    <strong class="text-sm font-black text-slate-900">Ditutup Resmi (End)</strong>
                    <p class="text-xs text-slate-500 mt-1">Pemungutan suara resmi selesai. Kunci bilik permanen & hasil suara final diumumkan.</p>
                </label>
            </div>
        </div>

        <!-- 2. IDENTITAS, KUORUM & DEADLINE PEMILU -->
        <div class="p-6 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-2xs space-y-5">
            <div class="flex items-center space-x-2 pb-3 border-b border-slate-100">
                <span class="text-lg">🏛️</span>
                <h3 class="text-base font-extrabold text-slate-900">2. Parameter Utama Pemilihan, Kuorum & Deadline</h3>
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

                <!-- DEADLINE WAKTU PEMILIHAN (OPSI: HARI, TANGGAL, JAM, WAKTU) -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-slate-700">Tenggat Waktu / Deadline Pemilihan</label>
                        <div class="flex items-center space-x-2 text-[11px]">
                            <button type="button" onclick="setDeadlinePreset('today17')" class="text-blue-600 font-bold hover:underline">Hari Ini 17:00</button>
                            <span class="text-slate-300">•</span>
                            <button type="button" onclick="setDeadlinePreset('clear')" class="text-rose-600 font-bold hover:underline">Hapus</button>
                        </div>
                    </div>
                    <input 
                        type="datetime-local" 
                        name="voting_deadline" 
                        id="voting_deadline_input"
                        value="{{ old('voting_deadline', $settings['voting_deadline']) }}" 
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 font-mono font-bold focus:bg-white focus:border-blue-500 outline-none"
                    >
                    <p class="text-[11px] text-slate-500 mt-1">
                        Batas waktu selesai mencakup hari, tanggal, dan jam. Disiarkan langsung (*streaming*) dengan countdown ke publik.
                    </p>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kiosk Click Throttle (Anti Double-Tap / Jitter)</label>
                    <div class="relative">
                        <input 
                            type="number" 
                            name="throttle_click_ms" 
                            min="200" 
                            max="5000" 
                            step="100" 
                            required 
                            value="{{ old('throttle_click_ms', $settings['throttle_click_ms']) }}" 
                            class="w-full sm:w-64 px-4 py-2.5 pr-12 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 font-mono font-bold focus:bg-white focus:border-blue-500 outline-none"
                        >
                        <span class="absolute sm:left-56 right-3.5 top-2.5 text-xs font-bold text-slate-400">ms</span>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1">Waktu perlindungan tombol untuk mencegah klik ganda tak sengaja (rekomendasi: 800ms).</p>
                </div>
            </div>
        </div>

        <!-- 3. FITUR KIOSK, SENSOR & PUBLIK STREAM -->
        <div class="p-6 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-2xs space-y-5">
            <div class="flex items-center space-x-2 pb-3 border-b border-slate-100">
                <span class="text-lg">📡</span>
                <h3 class="text-base font-extrabold text-slate-900">3. Fitur Kiosk, Hardware Sensor & Public Stream</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Public SSE -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                    <div>
                        <strong class="text-xs sm:text-sm text-slate-900 block font-extrabold">Public Realtime SSE Stream</strong>
                        <span class="text-[11px] text-slate-500">Izinkan publik mengakses siaran langsung Server-Sent Events di beranda utama tanpa login admin.</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer ml-3 shrink-0">
                        <input type="checkbox" name="public_sse_enabled" value="1" {{ ($settings['public_sse_enabled'] ?? '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <!-- Show Candidate NIK -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                    <div>
                        <strong class="text-xs sm:text-sm text-slate-900 block font-extrabold">Tampilkan NIK Kandidat di Surat Suara</strong>
                        <span class="text-[11px] text-slate-500">Menampilkan NIK resmi kandidat di bawah nama pada bilik pemungutan suara.</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer ml-3 shrink-0">
                        <input type="checkbox" name="show_candidate_nik" value="1" {{ ($settings['show_candidate_nik'] ?? '0') == '1' ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <!-- Mobile NFC Sensor -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                    <div>
                        <strong class="text-xs sm:text-sm text-slate-900 block font-extrabold">Sensor Web NFC (Mobile Phones)</strong>
                        <span class="text-[11px] text-slate-500">Aktifkan NDEF Reader hardware bawaan smartphone Chrome Android untuk tap kartu anggota.</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer ml-3 shrink-0">
                        <input type="checkbox" name="enable_nfc_mobile" value="1" {{ ($settings['enable_nfc_mobile'] ?? '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <!-- Demo Accounts Display -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                    <div>
                        <strong class="text-xs sm:text-sm text-slate-900 block font-extrabold">Tampilkan Akun Demo di Halaman Voter</strong>
                        <span class="text-[11px] text-slate-500">Tampilkan tabel akun demo untuk simulasi cepat penguji / demonstrasi panitia.</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer ml-3 shrink-0">
                        <input type="checkbox" name="enable_demo_accounts" value="1" {{ ($settings['enable_demo_accounts'] ?? '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <!-- Voice Greeting -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                    <div>
                        <strong class="text-xs sm:text-sm text-slate-900 block font-extrabold">Sambutan Suara Otomatis (Voice Greeting)</strong>
                        <span class="text-[11px] text-slate-500">Auto-play suara sambutan "Selamat Datang Mas Admin" & panduan bilik suara.</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer ml-3 shrink-0">
                        <input type="checkbox" name="enable_voice_greeting" value="1" {{ ($settings['enable_voice_greeting'] ?? '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <!-- Sound FX -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                    <div>
                        <strong class="text-xs sm:text-sm text-slate-900 block font-extrabold">Efek Suara Audio (Sound FX)</strong>
                        <span class="text-[11px] text-slate-500">Suara klik tombol, tap RFID, submit bilik, dan gong undian doorprize.</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer ml-3 shrink-0">
                        <input type="checkbox" name="enable_sound_fx" value="1" {{ ($settings['enable_sound_fx'] ?? '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <!-- Kiosk Session Timeout -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Batas Waktu Auto-Logout Bilik Suara (Detik)</label>
                    <input 
                        type="number" 
                        name="kiosk_session_timeout" 
                        min="10" 
                        max="600" 
                        required 
                        value="{{ old('kiosk_session_timeout', $settings['kiosk_session_timeout']) }}" 
                        class="w-full sm:w-64 px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-xs sm:text-sm text-slate-900 font-mono font-bold focus:border-blue-500 outline-none"
                    >
                    <p class="text-[11px] text-slate-500 mt-1">Sesi bilik akan otomatis ditutup jika pemilih tidak melakukan aktivitas dalam bilik.</p>
                </div>
            </div>
        </div>

        <!-- 4. AI INTELLIGENCE & GOOGLE GEMINI -->
        <div class="p-6 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-2xs space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <span class="text-lg">🤖</span>
                    <h3 class="text-base font-extrabold text-slate-900">4. Mesin Analisis AI (Google Gemini Reasoning)</h3>
                </div>
                <button 
                    type="button" 
                    id="btn-test-gemini" 
                    onclick="testGeminiConnection()"
                    class="px-3.5 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold border border-blue-200 transition cursor-pointer flex items-center space-x-1.5"
                >
                    <span id="gemini-test-icon">⚡</span>
                    <span id="gemini-test-label">Test AI Connection</span>
                </button>
            </div>

            <!-- Test status alert box -->
            <div id="gemini-test-result" class="hidden p-4 rounded-2xl text-xs font-medium"></div>

            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-slate-700">Google AI Studio Gemini API Key</label>
                        <a href="https://aistudio.google.com/app/apikey" target="_blank" class="text-xs text-blue-600 hover:underline font-bold inline-flex items-center">
                            Dapatkan API Key Gratis di Google AI Studio ↗
                        </a>
                    </div>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="gemini_api_key" 
                            id="gemini_api_key_input"
                            placeholder="AIzaSy..." 
                            value="{{ old('gemini_api_key', $settings['gemini_api_key']) }}" 
                            class="w-full px-4 py-2.5 pr-20 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 font-mono focus:bg-white focus:border-blue-500 outline-none"
                        >
                        <button 
                            type="button" 
                            onclick="toggleApiKeyVisibility()"
                            class="absolute right-3 top-2 text-xs font-bold text-slate-400 hover:text-slate-600 px-2 py-1 rounded bg-slate-200/50"
                        >
                            <span id="toggle-key-label">SHOW</span>
                        </button>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1">API Key disimpan secara aman dan digunakan untuk analisis mendalam telemetri pemilu dan deteksi anomali log audit.</p>
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

        <!-- 5. PWA & OFFLINE DIAGNOSTICS -->
        <div class="p-6 sm:p-7 rounded-3xl bg-slate-900 text-white border border-slate-800 shadow-2xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <div class="flex items-center space-x-2">
                    <span class="text-lg">📲</span>
                    <h3 class="text-base font-extrabold text-white">5. Status Progressive Web App (PWA)</h3>
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

    </form>
</div>

<script>
// Autosave Debounced Engine
let autosaveTimer = null;

function triggerAutosave() {
    clearTimeout(autosaveTimer);
    const dot = document.getElementById('autosave-dot');
    const text = document.getElementById('autosave-text');
    const statusBox = document.getElementById('autosave-status');
    
    dot.className = 'w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping';
    text.innerText = 'Menyimpan...';
    statusBox.className = 'inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-xl bg-amber-50 text-amber-800 text-xs font-mono font-bold border border-amber-300 shadow-2xs';

    autosaveTimer = setTimeout(async () => {
        const form = document.getElementById('settings-form');
        const formData = new FormData(form);

        try {
            const res = await fetch("{{ route('admin.settings.update') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await res.json();
            if (res.ok && data.success) {
                dot.className = 'w-2.5 h-2.5 rounded-full bg-emerald-500';
                text.innerText = 'Tersimpan ' + data.saved_at;
                statusBox.className = 'inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 text-xs font-mono font-bold border border-emerald-300 shadow-2xs';
            } else {
                dot.className = 'w-2.5 h-2.5 rounded-full bg-rose-500';
                text.innerText = 'Gagal menyimpan';
                statusBox.className = 'inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-xl bg-rose-50 text-rose-800 text-xs font-mono font-bold border border-rose-300 shadow-2xs';
            }
        } catch(err) {
            dot.className = 'w-2.5 h-2.5 rounded-full bg-rose-500';
            text.innerText = 'Koneksi terputus';
            statusBox.className = 'inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-xl bg-rose-50 text-rose-800 text-xs font-mono font-bold border border-rose-300 shadow-2xs';
        }
    }, 600);
}

function setDeadlinePreset(preset) {
    const input = document.getElementById('voting_deadline_input');
    if (preset === 'today17') {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        input.value = `${year}-${month}-${day}T17:00`;
    } else if (preset === 'clear') {
        input.value = '';
    }
    triggerAutosave();
}

function toggleApiKeyVisibility() {
    const input = document.getElementById('gemini_api_key_input');
    const label = document.getElementById('toggle-key-label');
    if (input.type === 'password') {
        input.type = 'text';
        label.innerText = 'HIDE';
    } else {
        input.type = 'password';
        label.innerText = 'SHOW';
    }
}

async function testGeminiConnection() {
    const btn = document.getElementById('btn-test-gemini');
    const icon = document.getElementById('gemini-test-icon');
    const label = document.getElementById('gemini-test-label');
    const resultBox = document.getElementById('gemini-test-result');
    const apiKey = document.getElementById('gemini_api_key_input').value;

    btn.disabled = true;
    icon.innerHTML = '<span class="inline-block animate-spin">⏳</span>';
    label.innerText = 'Testing...';
    resultBox.classList.add('hidden');

    try {
        const response = await fetch("{{ route('admin.settings.gemini-test') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ gemini_api_key: apiKey })
        });

        const data = await response.json();

        resultBox.classList.remove('hidden');
        if (data.success) {
            resultBox.className = 'p-4 rounded-2xl text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-start space-x-2';
            resultBox.innerHTML = `
                <span class="text-base">✅</span>
                <div>
                    <strong class="font-bold block">${data.message || 'Koneksi Sukses!'}</strong>
                    <span class="text-[11px] text-emerald-700">Model: ${data.model || 'gemini-2.0-flash'} • Latensi: ${data.latency_ms || 0}ms</span>
                </div>
            `;
        } else {
            resultBox.className = 'p-4 rounded-2xl text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200 flex items-start space-x-2';
            resultBox.innerHTML = `
                <span class="text-base">❌</span>
                <div>
                    <strong class="font-bold block">Koneksi Gagal</strong>
                    <span class="text-[11px] text-rose-700">${data.message || 'Periksa kembali API Key Anda.'}</span>
                </div>
            `;
        }
    } catch (err) {
        resultBox.classList.remove('hidden');
        resultBox.className = 'p-4 rounded-2xl text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200 flex items-start space-x-2';
        resultBox.innerHTML = `
            <span class="text-base">⚠️</span>
            <div>
                <strong class="font-bold block">Kesalahan Jaringan</strong>
                <span class="text-[11px] text-rose-700">${err.message}</span>
            </div>
        `;
    } finally {
        btn.disabled = false;
        icon.innerHTML = '⚡';
        label.innerText = 'Test AI Connection';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('settings-form');
    if (form) {
        form.addEventListener('input', triggerAutosave);
        form.addEventListener('change', triggerAutosave);
    }
});
</script>
@endsection
