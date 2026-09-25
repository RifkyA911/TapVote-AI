@extends('layouts.app')

@section('title', __('Kios Pemilih - Tempelkan Kartu'))

@section('content')
<div class="flex-1 flex flex-col justify-between p-4 sm:p-6 lg:p-8 max-w-4xl mx-auto w-full min-h-screen">

    <!-- Header Instansi & Navigasi dengan Ambient Dot Colors Backdrop -->
    <header class="relative flex flex-col sm:flex-row items-center sm:justify-between gap-3 py-3 px-4 sm:px-6 rounded-3xl border border-slate-200/80 bg-white/70 backdrop-blur-md shadow-xs overflow-hidden">
        <!-- Ambient Dot Glows Behind Navbar -->
        <div class="absolute -top-10 -left-10 w-44 h-44 bg-blue-400/20 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute top-1/2 left-1/3 -translate-y-1/2 w-48 h-24 bg-indigo-300/20 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-10 -right-10 w-44 h-44 bg-emerald-400/20 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex items-center space-x-3 w-full sm:w-auto justify-between sm:justify-start">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-sm shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">TapVote AI</h1>
                    <p class="text-xs text-slate-500 font-medium">{{ __('Kios Pemungutan Suara') }}</p>
                </div>
            </div>

            <!-- Language Switcher Pill for Mobile View -->
            <div class="inline-flex sm:hidden rounded-xl border border-slate-200 bg-white p-1 text-xs font-bold shadow-2xs">
                <a href="{{ route('lang.switch', 'en') }}" class="px-2.5 py-1 rounded-lg transition {{ app()->getLocale() === 'en' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-blue-600' }}">EN</a>
                <a href="{{ route('lang.switch', 'id') }}" class="px-2.5 py-1 rounded-lg transition {{ app()->getLocale() === 'id' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-blue-600' }}">ID</a>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-2 sm:space-x-3.5 w-full sm:w-auto">
            <!-- Language Switcher Pill (Desktop) -->
            <div class="hidden sm:inline-flex rounded-xl border border-slate-200 bg-white p-1 text-xs sm:text-sm font-bold shadow-2xs">
                <a href="{{ route('lang.switch', 'en') }}" class="px-3 py-1.5 rounded-lg transition {{ app()->getLocale() === 'en' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-blue-600' }}">EN</a>
                <a href="{{ route('lang.switch', 'id') }}" class="px-3 py-1.5 rounded-lg transition {{ app()->getLocale() === 'id' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-blue-600' }}">ID</a>
            </div>

            <!-- Tombol Petunjuk Cara Memilih -->
            <button 
                type="button" 
                onclick="openGuideModal()"
                class="flex-1 sm:flex-none text-xs sm:text-base font-extrabold text-amber-900 hover:text-amber-950 py-2.5 px-3.5 sm:py-3 sm:px-5 rounded-2xl border-2 border-amber-300 hover:border-amber-400 bg-amber-50 hover:bg-amber-100 shadow-xs transition flex items-center justify-center space-x-2 cursor-pointer"
            >
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                </svg>
                <span>{{ __('Petunjuk Memilih') }}</span>
            </button>

            <!-- Tombol Live Count -->
            <a href="{{ route('home') }}" class="flex-1 sm:flex-none text-xs sm:text-base font-extrabold text-slate-800 hover:text-blue-700 py-2.5 px-3.5 sm:py-3 sm:px-5 rounded-2xl border-2 border-slate-200 hover:border-blue-400 bg-white hover:bg-slate-50 shadow-xs transition flex items-center justify-center space-x-2 cursor-pointer">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M3 20.25h18M3.75 20.25V3.75" /></svg>
                <span>{{ __('Live Count') }}</span>
            </a>
        </div>
    </header>

    <!-- Modal Petunjuk Cara Memilih (Font Lebih Besar & Sesuai Fitur Terbaru) -->
    <div id="guide-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white border border-slate-200 rounded-3xl max-w-2xl w-full p-6 sm:p-9 shadow-2xl relative max-h-[85vh] overflow-y-auto">
            <button onclick="closeGuideModal()" class="absolute top-5 right-5 text-slate-500 hover:text-slate-800 p-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-lg font-bold transition cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>

            <div class="flex items-center space-x-3.5 mb-6">
                <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center shadow-xs shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ __('Petunjuk Cara Memilih') }}</h3>
                    <p class="text-sm sm:text-base text-slate-500 font-medium">{{ __('Ikuti langkah-langkah mudah di bawah ini') }}</p>
                </div>
            </div>

            <div id="guide-steps" class="space-y-4 mb-7">
                <!-- Langkah 1 -->
                <div class="flex items-start space-x-4 p-4 sm:p-5 rounded-2xl bg-blue-50 border border-blue-200">
                    <span class="w-11 h-11 rounded-2xl bg-blue-600 text-white font-black text-lg flex items-center justify-center shrink-0 shadow-xs">1</span>
                    <div>
                        <h4 class="font-black text-slate-900 text-base sm:text-lg">{{ __('Tempelkan Keplek / ID Card ke Sensor Scanner') }}</h4>
                        <p class="text-xs sm:text-sm text-slate-600 mt-1 leading-relaxed">{{ __('Dekatkan kartu identitas / Keplek Anda ke sensor reader scanner di depan layar. Lampu indikator akan menyala hijau ketika kartu terbaca sah.') }}</p>
                    </div>
                </div>

                <!-- Langkah 2 -->
                <div class="flex items-start space-x-4 p-4 sm:p-5 rounded-2xl bg-emerald-50 border border-emerald-200">
                    <span class="w-11 h-11 rounded-2xl bg-emerald-600 text-white font-black text-lg flex items-center justify-center shrink-0 shadow-xs">2</span>
                    <div>
                        <h4 class="font-black text-slate-900 text-base sm:text-lg">{{ __('Bilik Suara Digital (Wizard 2 Tahap)') }}</h4>
                        <p class="text-xs sm:text-sm text-slate-600 mt-1 leading-relaxed">{{ __('Pilih 1 Calon Ketua Koperasi, lalu pilih 1 Calon Pengawas. Anda dapat menyentuh tombol "Baca Visi & Misi" untuk melihat foto besar dan rincian program kerja calon sebelum menentukan pilihan.') }}</p>
                    </div>
                </div>

                <!-- Langkah 3 -->
                <div class="flex items-start space-x-4 p-4 sm:p-5 rounded-2xl bg-amber-50 border border-amber-200">
                    <span class="w-11 h-11 rounded-2xl bg-amber-600 text-white font-black text-lg flex items-center justify-center shrink-0 shadow-xs">3</span>
                    <div>
                        <h4 class="font-black text-slate-900 text-base sm:text-lg">{{ __('Tinjau & Konfirmasi Suara (Vote Sekarang)') }}</h4>
                        <p class="text-xs sm:text-sm text-slate-600 mt-1 leading-relaxed">{{ __('Pada tahap akhir, periksa kembali kandidat pilihan Anda. Jika sudah yakin, sentuh tombol "Vote Sekarang".') }}</p>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button 
                    type="button" 
                    onclick="toggleGuideVoice()"
                    id="guide-voice-btn"
                    class="w-1/2 py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm sm:text-base transition cursor-pointer flex items-center justify-center space-x-2"
                >
                    <svg id="guide-voice-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" />
                    </svg>
                    <span id="guide-voice-text">{{ __('Dengarkan Petunjuk') }}</span>
                </button>
                <button 
                    onclick="closeGuideModal()" 
                    class="w-1/2 py-3.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-sm sm:text-base transition cursor-pointer"
                >
                    {{ __('Tutup') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Voting Status Alert Banner (PAUSED / STOPPED) -->
    @if(($votingStatus ?? 'STARTED') === 'PAUSED')
        <div class="mt-4 p-4 rounded-2xl bg-amber-500 text-white font-extrabold text-sm sm:text-base flex items-center justify-center space-x-3 shadow-md animate-pulse">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ __('PERHATIAN: Sistem Pemungutan Suara Sedang Di-Jeda Sementara oleh Panitia Pemilihan.') }}</span>
        </div>
    @elseif(($votingStatus ?? 'STARTED') === 'STOPPED')
        <div class="mt-4 p-4 rounded-2xl bg-rose-600 text-white font-extrabold text-sm sm:text-base flex items-center justify-center space-x-3 shadow-md">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
            <span>{{ __('PEMUNGUTAN SUARA TELAH RESMI DITUTUP. Terima kasih atas partisipasi seluruh anggota Koperasi.') }}</span>
        </div>
    @endif

    <!-- Main Tap Area (Animasi Bouncing Smooth & Ripple Efek Bersih Bebas Stroke Biru) -->
    <div class="my-auto py-4 sm:py-6 flex flex-col items-center justify-center text-center">
        
        <div id="tap-kiosk-box" class="w-full max-w-xl bg-transparent p-3 sm:p-8 flex flex-col items-center relative transition-all duration-500 {{ session('error') ? 'animate-distracted-shake' : '' }}">
            
            <!-- Sensor Target Box (Bebas stroke biru & background putih) -->
            <div id="sensor-container" class="relative w-40 h-40 sm:w-48 sm:h-48 flex items-center justify-center mb-4 sm:mb-6 bg-transparent">
                
                <!-- Normal Radar Rings (Ripple Lembut Tanpa Stroke Biru) -->
                <div id="normal-rings" class="absolute inset-0 flex items-center justify-center {{ session('error') || session('already_voted') || ($votingStatus ?? 'STARTED') !== 'STARTED' ? 'hidden' : '' }}">
                    <div class="radar-ring w-36 h-36 sm:w-40 sm:h-40"></div>
                    <div class="radar-ring w-36 h-36 sm:w-40 sm:h-40"></div>
                    <div class="radar-ring w-36 h-36 sm:w-40 sm:h-40"></div>
                </div>

                <!-- Warning Amber/Yellow Rings (Ketika Pemilih Sudah Pernah Memilih) -->
                <div id="warning-rings" class="absolute inset-0 flex items-center justify-center {{ session('already_voted') ? '' : 'hidden' }}">
                    <div class="warning-ring w-38 h-38 sm:w-44 sm:h-44"></div>
                    <div class="warning-ring w-38 h-38 sm:w-44 sm:h-44" style="animation-delay: 0.5s;"></div>
                    <div class="warning-ring w-38 h-38 sm:w-44 sm:h-44" style="animation-delay: 1s;"></div>
                </div>

                <!-- Danger Red Waving Chaos Rings (Ketika Keplek Belum Terdaftar) -->
                <div id="danger-rings" class="absolute inset-0 flex items-center justify-center {{ (session('error') && !session('already_voted')) ? '' : 'hidden' }}">
                    <div class="danger-ring w-36 h-36 sm:w-44 sm:h-44"></div>
                    <div class="danger-ring w-36 h-36 sm:w-44 sm:h-44" style="animation-delay: 0.35s;"></div>
                    <div class="danger-ring w-36 h-36 sm:w-44 sm:h-44" style="animation-delay: 0.7s;"></div>
                    <div class="danger-ring w-36 h-36 sm:w-44 sm:h-44" style="animation-delay: 1.05s;"></div>
                    <div class="danger-ring w-36 h-36 sm:w-44 sm:h-44" style="animation-delay: 1.4s;"></div>
                </div>

                <!-- Green Nova Success Rings (Muncul saat Berhasil Tap) -->
                <div id="nova-rings" class="absolute inset-0 flex items-center justify-center hidden">
                    <div class="nova-success-ring w-40 h-40 sm:w-48 sm:h-48"></div>
                    <div class="nova-success-ring w-40 h-40 sm:w-48 sm:h-48" style="animation-delay: 0.4s;"></div>
                    <div class="nova-success-ring w-40 h-40 sm:w-48 sm:h-48" style="animation-delay: 0.8s;"></div>
                </div>

                <!-- Kartu Keplek dengan Animasi Bouncing Smooth (Transparan Murni, Tanpa Frame Biru/Putih) -->
                <div id="card-graphic" class="relative z-10 w-40 h-54 sm:w-48 sm:h-64 flex flex-col items-center justify-center animate-smooth-bounce transition-all duration-500 bg-transparent border-0 ring-0 shadow-none">
                    <img id="card-badge-img" src="/images/blank_id_card.png" alt="Keplek ID Card" class="w-full h-full object-contain drop-shadow-xl transition-all duration-500">
                </div>
            </div>

            <!-- Petunjuk Jelas: Ditambahi kata "ke Scanner" -->
            <h2 id="tap-title" class="text-2xl sm:text-4xl lg:text-5xl font-black text-slate-900 mb-4 sm:mb-5 tracking-tight transition-all">
                @if(session('already_voted'))
                    {{ __('Hak Suara Telah Digunakan') }}
                @elseif(($votingStatus ?? 'STARTED') === 'PAUSED')
                    {{ __('Pemilihan Sedang Di-Jeda') }}
                @elseif(($votingStatus ?? 'STARTED') === 'STOPPED')
                    {{ __('Pemilihan Telah Ditutup') }}
                @else
                    {{ __('Tempelkan Keplek Anda ke Scanner') }}
                @endif
            </h2>

            <!-- Status Indicator (Diperbesar & Kontekstual) -->
            <div id="tap-status-pill" class="inline-flex items-center space-x-2.5 sm:space-x-3 px-4 py-2.5 sm:px-6 sm:py-3.5 rounded-full {{ session('already_voted') ? 'bg-amber-100 border-amber-400 text-amber-950' : (session('error') ? 'bg-rose-50 border-rose-300 text-rose-800' : (($votingStatus ?? 'STARTED') === 'PAUSED' ? 'bg-amber-50 border-amber-300 text-amber-900' : (($votingStatus ?? 'STARTED') === 'STOPPED' ? 'bg-rose-50 border-rose-300 text-rose-900' : 'bg-emerald-50 border-emerald-300 text-emerald-800'))) }} border-2 text-xs sm:text-base font-black shadow-md transition-all duration-500">
                <span id="tap-status-dot" class="relative flex h-3 sm:h-3.5 w-3 sm:w-3.5 shrink-0">
                    @if(session('already_voted') || ($votingStatus ?? 'STARTED') === 'PAUSED')
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 sm:h-3.5 w-3 sm:w-3.5 bg-amber-600"></span>
                    @elseif(($votingStatus ?? 'STARTED') === 'STOPPED')
                        <span class="relative inline-flex rounded-full h-3 sm:h-3.5 w-3 sm:w-3.5 bg-rose-600"></span>
                    @else
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ session('error') ? 'bg-rose-400' : 'bg-emerald-400' }} opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 sm:h-3.5 w-3 sm:w-3.5 {{ session('error') ? 'bg-rose-600' : 'bg-emerald-600' }}"></span>
                    @endif
                </span>
                <span id="tap-status-text">
                    @if(session('already_voted'))
                        {{ __('Hak Suara Telah Digunakan • Tidak Dapat Memilih Lagi') }}
                    @elseif(($votingStatus ?? 'STARTED') === 'PAUSED')
                        {{ __('Sistem Di-Jeda Sementara • Mohon Menunggu') }}
                    @elseif(($votingStatus ?? 'STARTED') === 'STOPPED')
                        {{ __('Pemungutan Suara Telah Ditutup • Terima Kasih') }}
                    @elseif(session('error'))
                        {{ __('Kartu Ditolak • Silakan Tempel Ulang') }}
                    @else
                        {{ __('Scanner Siap • Menunggu Keplek') }}
                    @endif
                </span>
            </div>

            <p class="text-xs sm:text-base text-slate-500 font-semibold mt-3 sm:mt-4">
                @if(session('already_voted'))
                    <span class="text-amber-700 font-bold">{{ session('error') }}</span>
                @elseif(($votingStatus ?? 'STARTED') === 'PAUSED' || ($votingStatus ?? 'STARTED') === 'STOPPED')
                    {{ __('Bilik suara sedang dikunci oleh panitia.') }}
                @else
                    {{ __('Input otomatis terdeteksi tanpa perlu menekan layar.') }}
                @endif
            </p>
        </div>

        <!-- Hidden Form & Input (Hanya menerima input dari Keplek Hardware Reader) -->
        <form action="{{ route('voter.tap.process') }}" method="POST" id="tap-form" class="opacity-0 pointer-events-none absolute -top-96 -left-96" aria-hidden="true" tabindex="-1">
            @csrf
            <input 
                type="text" 
                name="rfid" 
                id="rfid_input" 
                {{ ($votingStatus ?? 'STARTED') !== 'STARTED' ? 'disabled' : 'autofocus' }}
                autocomplete="off"
                tabindex="-1"
            >
        </form>

    </div>

    <!-- Section Simulasi Demo dengan Tombol Hide/Show Toggle -->
    <div class="mt-4 p-4 rounded-2xl bg-white border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-600 uppercase">Demo</span>
                <h3 class="text-xs sm:text-sm font-bold text-slate-800">{{ __('Simulasi Pengujian (Khusus Uji Coba Tanpa Scanner Fisik)') }}</h3>
            </div>

            <button 
                type="button" 
                onclick="toggleDemoSection()" 
                id="toggle-demo-btn"
                class="inline-flex items-center space-x-2 px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-extrabold transition cursor-pointer"
            >
                <span id="demo-btn-icon">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                </span>
                <span id="demo-btn-text">{{ __('Buka Akun Demo') }}</span>
            </button>
        </div>

        <div id="demo-section-content" class="hidden mt-4 pt-3 border-t border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 p-3 rounded-xl bg-slate-50 border border-slate-200">
                <div>
                    <span class="text-xs font-bold text-slate-800">{{ __('Simulasi Pengujian Scanner & Deteksi Kartu') }}</span>
                    <p class="text-[11px] text-slate-500">{{ __('Uji berbagai skenario: kartu sah baru, kartu yang sudah memilih, atau kartu asing yang tidak terdaftar.') }}</p>
                </div>
                <!-- Kartu Uji Coba Tidak Dikenali (Red Error Simulation) -->
                <button 
                    type="button"
                    onclick="triggerUnknownCardTest('99A88F11')"
                    class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 border border-rose-300 text-rose-800 text-xs font-black shadow-2xs transition cursor-pointer self-start sm:self-auto shrink-0"
                    title="Simulasikan kartu RFID asing yang belum terdaftar di DPT"
                >
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span>{{ __('Uji Kartu Tidak Dikenali (Asing)') }}</span>
                </button>
            </div>

            <!-- Grid Akun Demo: Tampilkan Semua Tanpa Scroll Sesuai Request -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                @foreach($demoVoters as $demo)
                    <div class="p-3 rounded-2xl border {{ $demo->sudahMemilih() ? 'bg-slate-50 border-slate-200 text-slate-400' : 'bg-white border-slate-200 hover:border-blue-400 shadow-2xs' }} flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="font-mono text-[11px] font-extrabold text-blue-600">{{ $demo->nik }}</span>
                                @if($demo->sudahMemilih())
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-200">{{ __('Sudah Memilih') }}</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">{{ __('Tersedia') }}</span>
                                @endif
                            </div>
                            <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate" title="{{ $demo->nama }}">{{ $demo->nama }}</h4>
                            <p class="text-[11px] text-slate-500 truncate">{{ $demo->dept }}</p>
                        </div>

                        <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[10px] font-mono text-slate-400">UID: {{ $demo->rfid }}</span>
                            @if(!$demo->sudahMemilih())
                                <button 
                                    type="button"
                                    onclick="triggerCardTap('{{ $demo->rfid }}')"
                                    class="px-2.5 py-1 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-2xs transition cursor-pointer"
                                >
                                    {{ __('Tap') }}
                                </button>
                            @else
                                <button 
                                    type="button"
                                    onclick="showAlreadyVotedWarning('{{ addslashes($demo->nama) }}', '{{ $demo->voted_at ? $demo->voted_at->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB' : '' }}')"
                                    class="px-2.5 py-1 rounded-lg bg-amber-100 hover:bg-amber-200 text-amber-900 text-[11px] font-bold transition cursor-pointer border border-amber-300"
                                    title="Coba tap pemilih yang sudah memilih"
                                >
                                    {{ __('Uji Dicegah') }}
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    const rfidInput = document.getElementById('rfid_input');
    const tapForm = document.getElementById('tap-form');
    const normalRings = document.getElementById('normal-rings');
    const warningRings = document.getElementById('warning-rings');
    const dangerRings = document.getElementById('danger-rings');
    const novaRings = document.getElementById('nova-rings');
    const tapTitle = document.getElementById('tap-title');
    const tapStatusPill = document.getElementById('tap-status-pill');
    const tapStatusText = document.getElementById('tap-status-text');

    // 1. Toggle Akun Demo
    function toggleDemoSection() {
        if (window.SoundEffects) window.SoundEffects.click();
        const content = document.getElementById('demo-section-content');
        const btnText = document.getElementById('demo-btn-text');
        const btnIcon = document.getElementById('demo-btn-icon');
        
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            btnText.innerText = '{{ __("Sembunyikan Akun Demo") }}';
            btnIcon.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>';
        } else {
            content.classList.add('hidden');
            btnText.innerText = '{{ __("Buka Akun Demo") }}';
            btnIcon.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>';
        }
    }

    // Map status pemilih demo untuk deteksi instan client-side
    const demoVoterMap = @json($demoVoterMap ?? []);

    // 2. Transisi Ripple Nova Green Saat Sukses Membaca Kartu
    function triggerCardTap(rfid) {
        if (!rfid) return;

        const cleanKey = rfid.trim().toLowerCase();
        // Cek jika pemilih demo sudah pernah memilih
        if (demoVoterMap[cleanKey] && demoVoterMap[cleanKey].pilih === 'T') {
            showAlreadyVotedWarning(demoVoterMap[cleanKey].nama, demoVoterMap[cleanKey].voted_at);
            return;
        }

        // Mainkan melodi welcome sound effect 3 detik saat tap login berhasil
        if (window.SoundEffects) {
            if (typeof window.SoundEffects.welcome === 'function') {
                window.SoundEffects.welcome();
            } else {
                window.SoundEffects.tap();
            }
        }

        // Aktifkan Nova Green Rings di latar belakang
        normalRings.classList.add('hidden');
        dangerRings.classList.add('hidden');
        warningRings.classList.add('hidden');
        novaRings.classList.remove('hidden');

        // Transformasi Card Graphic menjadi Hijau Sukses
        const badgeImg = document.getElementById('card-badge-img');
        if (badgeImg) {
            badgeImg.classList.add('scale-105', 'drop-shadow-2xl');
        }

        tapTitle.innerText = "{{ __('Kartu Terbaca Sah!') }}";
        tapStatusPill.className = "inline-flex items-center space-x-3 px-6 py-3.5 rounded-full bg-emerald-100 border-2 border-emerald-400 text-emerald-950 text-sm sm:text-base font-black shadow-md";
        tapStatusText.innerText = "{{ __('Akses Diterima • Masuk ke Bilik Suara...') }}";

        // Transisi halus antar halaman: Biarkan audio selesai berbunyi, lalu fadeout smooth
        setTimeout(() => {
            document.body.classList.add('transition-opacity', 'duration-500', 'opacity-0');
        }, 1800);

        setTimeout(() => {
            rfidInput.value = rfid;
            tapForm.submit();
        }, 2300);
    }

    // 3. Pencegahan & Animasi Ripple Kuning/Amber Jika Sudah Pernah Memilih
    function showAlreadyVotedWarning(nama, waktu) {
        if (window.SoundEffects) window.SoundEffects.error();

        // Aktifkan Ripple Kuning/Amber
        normalRings.classList.add('hidden');
        dangerRings.classList.add('hidden');
        novaRings.classList.add('hidden');
        warningRings.classList.remove('hidden');

        const box = document.getElementById('tap-kiosk-box');
        box.classList.remove('animate-distracted-shake');
        void box.offsetWidth;
        box.classList.add('animate-distracted-shake');

        tapTitle.innerText = "{{ __('Hak Suara Telah Digunakan') }}";
        tapStatusPill.className = "inline-flex items-center space-x-3 px-6 py-3.5 rounded-full bg-amber-100 border-2 border-amber-400 text-amber-950 text-sm sm:text-base font-black shadow-md";
        tapStatusText.innerText = "Peringatan: Hak suara " + nama + " telah digunakan pada " + (waktu || 'sesi sebelumnya') + "!";

        setTimeout(() => {
            warningRings.classList.add('hidden');
            normalRings.classList.remove('hidden');
            tapTitle.innerText = "{{ __('Tempelkan Keplek Anda ke Scanner') }}";
            tapStatusPill.className = "inline-flex items-center space-x-3 px-6 py-3.5 rounded-full bg-emerald-50 border-2 border-emerald-300 text-emerald-800 text-sm sm:text-base font-black shadow-md";
            tapStatusText.innerText = "{{ __('Scanner Siap • Menunggu Keplek') }}";
        }, 4500);
    }

    // 3b. Pengujian Kartu Tidak Dikenali / Asing (Ripple Merah Danger Waving Chaos)
    function triggerUnknownCardTest(mockUid = '99A88F11') {
        if (window.SoundEffects) window.SoundEffects.error();

        // Aktifkan Ripple Merah Bahaya Waving Chaos
        normalRings.classList.add('hidden');
        warningRings.classList.add('hidden');
        novaRings.classList.add('hidden');
        dangerRings.classList.remove('hidden');

        const box = document.getElementById('tap-kiosk-box');
        box.classList.remove('animate-distracted-shake');
        void box.offsetWidth;
        box.classList.add('animate-distracted-shake');

        const badgeImg = document.getElementById('card-badge-img');
        if (badgeImg) {
            badgeImg.classList.add('grayscale', 'opacity-70');
        }

        tapTitle.innerText = "{{ __('Belum Bisa Mengikuti Voting') }}";
        tapStatusPill.className = "inline-flex items-center space-x-3 px-6 py-3.5 rounded-full bg-rose-100 border-2 border-rose-400 text-rose-950 text-sm sm:text-base font-black shadow-md";
        tapStatusText.innerText = "{{ __('Keplek belum bisa mengikuti voting.') }}";

        setTimeout(() => {
            dangerRings.classList.add('hidden');
            normalRings.classList.remove('hidden');
            if (badgeImg) {
                badgeImg.classList.remove('grayscale', 'opacity-70');
            }
            tapTitle.innerText = "{{ __('Tempelkan Keplek Anda ke Scanner') }}";
            tapStatusPill.className = "inline-flex items-center space-x-3 px-6 py-3.5 rounded-full bg-emerald-50 border-2 border-emerald-300 text-emerald-800 text-sm sm:text-base font-black shadow-md";
            tapStatusText.innerText = "{{ __('Scanner Siap • Menunggu Keplek') }}";
        }, 4500);
    }

    // 4. Efek Distracted Red Danger Saat Terjadi Error DPT Lainnya
    @if(session('already_voted'))
        document.addEventListener('DOMContentLoaded', () => {
            showAlreadyVotedWarning("{{ addslashes(session('voter_name', 'Anggota')) }}", "{{ session('voted_time', '') }}");
        });
    @elseif(session('error'))
        document.addEventListener('DOMContentLoaded', () => {
            if (window.SoundEffects) window.SoundEffects.error();

            dangerRings.classList.remove('hidden');
            normalRings.classList.add('hidden');
            warningRings.classList.add('hidden');

            const box = document.getElementById('tap-kiosk-box');
            if (box) {
                box.classList.remove('animate-distracted-shake');
                void box.offsetWidth;
                box.classList.add('animate-distracted-shake');
            }

            const badgeImg = document.getElementById('card-badge-img');
            if (badgeImg) {
                badgeImg.classList.add('grayscale', 'opacity-70');
            }

            tapTitle.innerText = "{{ __('Belum Bisa Mengikuti Voting') }}";
            tapStatusPill.className = "inline-flex items-center space-x-3 px-6 py-3.5 rounded-full bg-rose-100 border-2 border-rose-400 text-rose-950 text-sm sm:text-base font-black shadow-md";
            tapStatusText.innerText = "{{ session('error') }}";

            // Reset otomatis ke mode normal setelah 4 detik
            setTimeout(() => {
                dangerRings.classList.add('hidden');
                normalRings.classList.remove('hidden');
                if (badgeImg) {
                    badgeImg.classList.remove('grayscale', 'opacity-70');
                }
                tapTitle.innerText = "{{ __('Tempelkan Keplek Anda ke Scanner') }}";
                tapStatusPill.className = "inline-flex items-center space-x-3 px-6 py-3.5 rounded-full bg-emerald-50 border-2 border-emerald-300 text-emerald-800 text-sm sm:text-base font-black shadow-md";
                tapStatusText.innerText = "{{ __('Scanner Siap • Menunggu Keplek') }}";
            }, 4000);
        });
    @endif

    // 5. Pastikan input selalu fokus untuk mendengarkan Keplek USB Hardware Reader
    function keepFocus() {
        if (rfidInput && document.activeElement !== rfidInput) {
            rfidInput.focus();
        }
    }

    keepFocus();
    setInterval(keepFocus, 1000);

    // Kembalikan fokus jika layar disentuh
    document.addEventListener('click', function(e) {
        if (!e.target.closest('button') && !e.target.closest('a')) {
            keepFocus();
        }
    });

    // 6. Buffer Global USB Keplek Reader
    let scanBuffer = '';
    let lastKeyTime = Date.now();

    window.addEventListener('keydown', function(e) {
        const currentTime = Date.now();
        lastKeyTime = currentTime;

        if (e.key === 'Enter') {
            e.preventDefault();
            const scannedValue = rfidInput.value.trim() || scanBuffer.trim();
            if (scannedValue.length > 0) {
                triggerCardTap(scannedValue);
            }
            scanBuffer = '';
            return;
        }

        if (e.key.length === 1) {
            scanBuffer += e.key;
        }
    });

    // 7. Guide Modal with Text-to-Speech (Boomer Friendly & Terbaru)
    let guideSpeaking = false;
    let guideUtterance = null;

    function openGuideModal() {
        if (window.SoundEffects) window.SoundEffects.modal();
        document.getElementById('guide-modal').classList.remove('hidden');
        setTimeout(() => startGuideVoice(), 500);
    }

    function closeGuideModal() {
        if (window.SoundEffects) window.SoundEffects.click();
        stopGuideVoice();
        document.getElementById('guide-modal').classList.add('hidden');
    }

    function startGuideVoice() {
        if (!('speechSynthesis' in window)) return;
        stopGuideVoice();

        const guideText = `Selamat datang di Aplikasi Pemilihan Suara TapVote. Berikut petunjuk cara memilih. Langkah pertama, tempelkan Keplek atau kartu ID Anda ke sensor reader scanner yang ada di depan layar hingga berbunyi beep dan indikator berwarna hijau. Langkah kedua, di bilik suara digital, Anda akan memilih Calon Ketua dan Calon Pengawas Koperasi. Anda dapat membaca Visi dan Misi setiap calon sebelum memilih. Langkah ketiga, periksa kembali kandidat pilihan Anda pada halaman tinjau, lalu tekan tombol Vote Sekarang. Terima kasih telah berpartisipasi menyukseskan pemilihan Koperasi.`;

        guideUtterance = new SpeechSynthesisUtterance(guideText);
        guideUtterance.lang = 'id-ID';
        guideUtterance.rate = 0.85;
        guideUtterance.pitch = 1.0;
        guideUtterance.volume = 1.0;

        guideUtterance.onend = () => {
            guideSpeaking = false;
            updateGuideVoiceBtn();
        };

        speechSynthesis.speak(guideUtterance);
        guideSpeaking = true;
        updateGuideVoiceBtn();
    }

    function stopGuideVoice() {
        if ('speechSynthesis' in window) {
            speechSynthesis.cancel();
        }
        guideSpeaking = false;
        updateGuideVoiceBtn();
    }

    function toggleGuideVoice() {
        if (guideSpeaking) {
            stopGuideVoice();
        } else {
            startGuideVoice();
        }
    }

    function updateGuideVoiceBtn() {
        const btn = document.getElementById('guide-voice-btn');
        const text = document.getElementById('guide-voice-text');
        const icon = document.getElementById('guide-voice-icon');
        if (guideSpeaking) {
            btn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            btn.classList.add('bg-rose-600', 'hover:bg-rose-700');
            text.innerText = '{{ __("Hentikan Suara") }}';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M17.25 9.75 19.5 12m0 0 2.25 2.25M19.5 12l2.25-2.25M19.5 12l-2.25 2.25m-10.5-6 4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" />';
        } else {
            btn.classList.remove('bg-rose-600', 'hover:bg-rose-700');
            btn.classList.add('bg-blue-600', 'hover:bg-blue-700');
            text.innerText = '{{ __("Dengarkan Petunjuk") }}';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" />';
        }
    }

    // Close guide modal on outside click
    window.addEventListener('click', function(e) {
        const guideModal = document.getElementById('guide-modal');
        if (e.target === guideModal) closeGuideModal();
    });
</script>
@endpush
@endsection
