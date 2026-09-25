@extends('layouts.app')

@section('title', __('Ballot Booth - Cooperative Election'))

@section('content')
<!-- Friendly Warm Light Welcoming Overlay (Sopan, Teduh & Nyaman untuk Mata 40+ Tahun) -->
<div id="vote-welcome-overlay" class="fixed inset-0 z-[100] bg-slate-900/60 backdrop-blur-sm flex flex-col items-center justify-center p-4 sm:p-6 transition-all duration-700 ease-out">
    <div id="vote-welcome-card" class="relative z-10 flex flex-col items-center text-center max-w-lg w-full bg-white border-2 border-slate-200/90 rounded-3xl p-6 sm:p-9 shadow-2xl transform transition-all duration-700 scale-100">
        
        <!-- Icon Koperasi Sejuk & Ramah -->
        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-blue-50 border-2 border-blue-200 text-blue-700 flex items-center justify-center shadow-sm mb-5">
            <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
            </svg>
        </div>

        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-black tracking-wide uppercase bg-emerald-100 text-emerald-800 border border-emerald-300 mb-3">
            {{ __('Bilik Suara Terverifikasi') }}
        </span>

        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mb-2">
            {{ __('Selamat Datang!') }}
        </h2>

        <p class="text-slate-800 text-lg sm:text-xl font-black mb-1">
            {{ $pemilih->nama }}
        </p>
        <p class="text-slate-500 text-xs sm:text-sm font-semibold mb-6">
            NIK: <span class="font-mono text-slate-700">{{ $pemilih->nik }}</span> • {{ $pemilih->dept }}
        </p>

        <!-- 3-Second Welcoming Progress Indicator -->
        <div class="w-full max-w-xs bg-slate-100 rounded-full h-3 overflow-hidden border border-slate-200 mb-2.5">
            <div id="welcome-progress-fill" class="h-full bg-blue-600 rounded-full transition-all duration-[2600ms] ease-linear" style="width: 0%;"></div>
        </div>
        <span class="text-xs font-bold text-slate-500 tracking-normal">{{ __('Menyiapkan surat suara pemilihan...') }}</span>
    </div>
</div>

<div class="flex-1 flex flex-col p-4 sm:p-6 lg:p-8 max-w-6xl mx-auto w-full pb-44 sm:pb-52">

    <!-- Header Identitas Pemilih & Kontrol Aksi (Boomer Friendly Size) -->
    <header class="p-4 sm:p-5 rounded-3xl bg-white border-2 border-slate-200 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center space-x-3.5 sm:space-x-4">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-lg sm:text-xl shadow-sm shrink-0">
                {{ strtoupper(substr($pemilih->nama, 0, 2)) }}
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-base sm:text-xl font-black text-slate-900">{{ $pemilih->nama }}</h2>
                    <span class="px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">
                        {{ __('Hak Suara Aktif') }}
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-600 font-semibold mt-0.5">
                    {{ __('NIK:') }} <span class="font-bold text-slate-900 font-mono text-xs sm:text-sm">{{ $pemilih->nik }}</span> • {{ __('Departemen:') }} <span class="font-bold text-slate-900">{{ $pemilih->dept }}</span>
                </p>
            </div>
        </div>

        <div class="flex items-center justify-between sm:justify-end space-x-2.5 sm:space-x-3 w-full sm:w-auto">
            <!-- Language Switcher Pill -->
            <div class="inline-flex rounded-xl border border-slate-200 bg-white p-1 text-xs sm:text-sm font-bold shadow-2xs">
                <a href="{{ route('lang.switch', 'en') }}" class="px-2.5 sm:px-3 py-1.5 rounded-lg transition {{ app()->getLocale() === 'en' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-blue-600' }}">EN</a>
                <a href="{{ route('lang.switch', 'id') }}" class="px-2.5 sm:px-3 py-1.5 rounded-lg transition {{ app()->getLocale() === 'id' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-blue-600' }}">ID</a>
            </div>

            <!-- Tombol Batal & Keluar (Besar & Kontras untuk Boomer) -->
            <form action="{{ route('voter.logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2.5 sm:px-5 sm:py-3 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-700 border-2 border-rose-200 hover:border-rose-300 text-xs sm:text-base font-extrabold shadow-xs transition flex items-center justify-center space-x-2 cursor-pointer">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" /></svg>
                    <span>{{ __('Batal & Keluar') }}</span>
                </button>
            </form>
        </div>
    </header>

    <!-- Wizard Stepper Navigation dengan Arrow Penunjuk Arah (Jelas & Terpahami untuk Boomer) -->
    <div class="mb-6 sm:mb-8 p-2 sm:p-4 rounded-3xl bg-white border-2 border-slate-200 shadow-xs">
        <div class="flex items-center justify-between gap-1 sm:gap-3 text-center">
            
            <!-- Step Indicator 1: Calon Ketua -->
            <button 
                type="button" 
                id="stepper-tab-1"
                onclick="goToStep(1)"
                class="flex-1 flex flex-col sm:flex-row items-center justify-center gap-1.5 sm:gap-2.5 p-2 sm:py-3.5 sm:px-4 rounded-2xl bg-red-600 text-white font-black text-xs sm:text-base transition-all shadow-sm cursor-pointer"
            >
                <span id="step-badge-1" class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl bg-white text-red-700 flex items-center justify-center font-black text-xs sm:text-base shadow-xs shrink-0">1</span>
                <span class="truncate"><span class="sm:hidden">Ketua</span><span class="hidden sm:inline">{{ __('Pilih Ketua') }}</span></span>
            </button>

            <!-- Arrow 1 -> 2 -->
            <div class="text-slate-400 shrink-0 px-0.5 sm:px-1">
                <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
            </div>

            <!-- Step Indicator 2: Calon Pengawas -->
            <button 
                type="button" 
                id="stepper-tab-2"
                onclick="goToStep(2)"
                class="flex-1 flex flex-col sm:flex-row items-center justify-center gap-1.5 sm:gap-2.5 p-2 sm:py-3.5 sm:px-4 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold text-xs sm:text-base transition-all cursor-pointer"
            >
                <span id="step-badge-2" class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl bg-slate-300 text-slate-700 flex items-center justify-center font-black text-xs sm:text-base shrink-0">2</span>
                <span class="truncate"><span class="sm:hidden">Pengawas</span><span class="hidden sm:inline">{{ __('Pilih Pengawas') }}</span></span>
            </button>

            <!-- Arrow 2 -> 3 -->
            <div class="text-slate-400 shrink-0 px-0.5 sm:px-1">
                <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
            </div>

            <!-- Step Indicator 3: Konfirmasi Pilihan -->
            <button 
                type="button" 
                id="stepper-tab-3"
                onclick="goToStep(3)"
                class="flex-1 flex flex-col sm:flex-row items-center justify-center gap-1.5 sm:gap-2.5 p-2 sm:py-3.5 sm:px-4 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold text-xs sm:text-base transition-all cursor-pointer"
            >
                <span id="step-badge-3" class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl bg-slate-300 text-slate-700 flex items-center justify-center font-black text-xs sm:text-base shrink-0">3</span>
                <span class="truncate"><span class="sm:hidden">Konfirmasi</span><span class="hidden sm:inline">{{ __('Konfirmasi Suara') }}</span></span>
            </button>
        </div>
    </div>

    <!-- Form Voting -->
    <form id="voting-form" action="{{ route('voter.vote.store') }}" method="POST">
        @csrf

        <!-- ============================================== -->
        <!-- WIZARD STEP 1: PEMILIHAN KETUA KOPERASI        -->
        <!-- ============================================== -->
        <section id="step-section-1" class="wizard-step-container">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 mb-6 border-b-2 border-slate-200 gap-3">
                <div class="flex items-center space-x-3.5">
                    <span class="w-11 h-11 rounded-2xl bg-red-600 text-white font-black flex items-center justify-center text-lg shadow-sm shrink-0">1</span>
                    <div>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-950 tracking-tight">{{ __('Langkah 1: Pilih 1 (Satu) Calon Ketua Koperasi') }}</h3>
                        <p class="text-sm sm:text-base text-slate-600 font-medium">{{ __('Sentuh kotak calon yang Anda inginkan untuk memilih') }}</p>
                    </div>
                </div>
                <div id="ketua-status-badge" class="inline-flex self-start sm:self-auto px-4 py-2 rounded-2xl text-xs sm:text-sm font-extrabold bg-rose-50 text-rose-700 border-2 border-rose-200">
                    {{ __('Ketua Belum Dipilih') }}
                </div>
            </div>

            <!-- Grid Card Calon Ketua (Tinggi Foto 300px Sesuai Permintaan) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach($kandidatKetua as $ketua)
                    @php
                        $fotoKetua = $ketua->foto ?: 'https://ui-avatars.com/api/?name='.urlencode($ketua->nama).'&background=dc2626&color=ffffff&size=400';
                    @endphp
                    <div 
                        id="card-ketua-{{ $ketua->nik }}"
                        onclick="selectKetua('{{ $ketua->nik }}', '{{ addslashes($ketua->nama) }}', '{{ $ketua->nomor_urut }}', '{{ $fotoKetua }}')"
                        class="candidate-card-ketua ballot-card-sundul relative rounded-3xl bg-white border-3 border-slate-200 hover:border-red-400 overflow-hidden flex flex-col justify-between cursor-pointer shadow-sm hover:shadow-xl transition-all duration-300"
                    >
                        <input type="radio" name="ketua_nik" value="{{ $ketua->nik }}" id="radio-ketua-{{ $ketua->nik }}" class="hidden">

                        <div>
                            <!-- Foto Card: Tinggi Pas 300px Sesuai Permintaan -->
                            <div class="relative w-full h-[300px] max-h-[300px] bg-slate-900 overflow-hidden">
                                <img 
                                    src="{{ $fotoKetua }}" 
                                    alt="{{ $ketua->nama }}" 
                                    class="w-full h-full object-cover object-top hover:scale-105 transition-transform duration-500 ease-out"
                                >
                                <!-- Gradient Tipis untuk Kontras -->
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-black/20 pointer-events-none"></div>

                                <!-- Badge Nomor Urut Absolute di Kiri Atas: Nomor Saja, Background Putih, Teks Hitam Kontras -->
                                <div class="absolute top-3.5 left-3.5 z-10">
                                    <div class="w-13 h-13 sm:w-15 sm:h-15 rounded-2xl bg-white text-slate-950 border-2 border-slate-300 shadow-xl flex items-center justify-center text-2xl sm:text-3xl font-black ring-4 ring-black/15">
                                        {{ $ketua->nomor_urut }}
                                    </div>
                                </div>
                            </div>

                            <!-- Identitas Calon: Font Elegan, Besar & Kontras -->
                            <div class="p-5 pb-3">
                                <h3 class="text-xl sm:text-2xl font-black text-slate-950 tracking-tight leading-snug">{{ $ketua->nama }}</h3>
                            </div>
                        </div>

                        <!-- Actions: Tombol Visi Misi & Tombol Pilih -->
                        <div class="p-5 pt-0 flex flex-col gap-3.5">
                            <!-- Tombol Baca Visi & Misi (Font Gede, BG Gradient Red) -->
                            <button 
                                type="button" 
                                onclick="event.stopPropagation(); showDetailModal('{{ __('Calon Ketua Koperasi') }}', '{{ addslashes($ketua->nama) }}', '{{ $ketua->nomor_urut }}', `{{ addslashes($ketua->visi) }}`, `{{ addslashes($ketua->misi) }}`, '{{ $fotoKetua }}')"
                                class="w-full py-3 sm:py-3.5 px-4 rounded-2xl bg-gradient-to-r from-red-600 via-rose-600 to-red-700 hover:from-red-700 hover:to-rose-800 text-white font-extrabold text-sm sm:text-base shadow-md hover:shadow-lg transition-all flex items-center justify-center space-x-2.5 cursor-pointer"
                            >
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                <span>{{ __('Baca Visi & Misi') }}</span>
                            </button>

                            <!-- Tombol Pilih (BG Success Emerald, 1 Centang Bersih) -->
                            <div 
                                id="btn-select-ketua-{{ $ketua->nik }}" 
                                class="w-full py-3.5 sm:py-4 px-4 rounded-2xl bg-emerald-50 hover:bg-emerald-600 border-2 border-emerald-500 hover:border-emerald-600 text-emerald-800 hover:text-white font-black text-sm sm:text-base shadow-xs hover:shadow-md transition-all duration-300 flex items-center justify-center space-x-2.5 text-center"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                                </svg>
                                <span>{{ __('PILIH') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Card Pilihan Ketua Anda (Ditambah Margin Bottom Agar Tidak Tertutup Sticky Dock) -->
            <div class="mt-10 mb-28 sm:mb-32 p-5 sm:p-6 rounded-3xl bg-white border-2 border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <span class="text-xs sm:text-sm text-slate-500 font-bold uppercase tracking-wider block">{{ __('Pilihan Ketua Anda:') }}</span>
                    <strong id="step1-summary-text" class="text-base sm:text-lg font-black text-rose-600">{{ __('(Belum memilih calon ketua)') }}</strong>
                </div>
                <button 
                    type="button" 
                    id="btn-goto-step-2"
                    onclick="goToStep(2)"
                    disabled
                    class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-red-600 hover:bg-red-700 text-white font-black text-base sm:text-lg shadow-md disabled:opacity-40 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:text-slate-500 transition-all flex items-center justify-center space-x-3 cursor-pointer"
                >
                    <span>{{ __('Lanjut ke Pilih Pengawas') }}</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                </button>
            </div>
        </section>

        <!-- ============================================== -->
        <!-- WIZARD STEP 2: PEMILIHAN PENGAWAS KOPERASI     -->
        <!-- ============================================== -->
        <section id="step-section-2" class="wizard-step-container hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 mb-6 border-b-2 border-slate-200 gap-3">
                <div class="flex items-center space-x-3.5">
                    <span class="w-11 h-11 rounded-2xl bg-emerald-600 text-white font-black flex items-center justify-center text-lg shadow-sm shrink-0">2</span>
                    <div>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-950 tracking-tight">{{ __('Langkah 2: Pilih 1 (Satu) Calon Pengawas Koperasi') }}</h3>
                        <p class="text-sm sm:text-base text-slate-600 font-medium">{{ __('Sentuh kotak calon yang Anda inginkan untuk memilih') }}</p>
                    </div>
                </div>
                <div id="pengawas-status-badge" class="inline-flex self-start sm:self-auto px-4 py-2 rounded-2xl text-xs sm:text-sm font-extrabold bg-rose-50 text-rose-700 border-2 border-rose-200">
                    {{ __('Pengawas Belum Dipilih') }}
                </div>
            </div>

            <!-- Grid Card Calon Pengawas (Foto Dipotong Lebih Ringkas, Tanpa Ikon Checked) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach($kandidatPengawas as $pengawas)
                    @php
                        $fotoPengawas = $pengawas->foto ?: 'https://ui-avatars.com/api/?name='.urlencode($pengawas->nama).'&background=059669&color=ffffff&size=400';
                    @endphp
                    <div 
                        id="card-pengawas-{{ $pengawas->nik }}"
                        onclick="selectPengawas('{{ $pengawas->nik }}', '{{ addslashes($pengawas->nama) }}', '{{ $pengawas->nomor_urut }}', '{{ $fotoPengawas }}')"
                        class="candidate-card-pengawas ballot-card-sundul relative rounded-3xl bg-white border-3 border-slate-200 hover:border-emerald-400 overflow-hidden flex flex-col justify-between cursor-pointer shadow-sm hover:shadow-xl transition-all duration-300"
                    >
                        <input type="radio" name="pengawas_nik" value="{{ $pengawas->nik }}" id="radio-pengawas-{{ $pengawas->nik }}" class="hidden">

                        <div>
                            <!-- Foto Card: Tinggi Pas 300px Sesuai Permintaan -->
                            <div class="relative w-full h-[300px] max-h-[300px] bg-slate-900 overflow-hidden">
                                <img 
                                    src="{{ $fotoPengawas }}" 
                                    alt="{{ $pengawas->nama }}" 
                                    class="w-full h-full object-cover object-top hover:scale-105 transition-transform duration-500 ease-out"
                                >
                                <!-- Gradient Tipis untuk Kontras -->
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-black/20 pointer-events-none"></div>

                                <!-- Badge Nomor Urut Absolute di Kiri Atas: Nomor Saja, Background Putih, Teks Hitam Kontras -->
                                <div class="absolute top-3.5 left-3.5 z-10">
                                    <div class="w-13 h-13 sm:w-15 sm:h-15 rounded-2xl bg-white text-slate-950 border-2 border-slate-300 shadow-xl flex items-center justify-center text-2xl sm:text-3xl font-black ring-4 ring-black/15">
                                        {{ $pengawas->nomor_urut }}
                                    </div>
                                </div>
                            </div>

                            <!-- Identitas Calon: Font Elegan, Besar & Kontras -->
                            <div class="p-5 pb-3">
                                <h3 class="text-xl sm:text-2xl font-black text-slate-950 tracking-tight leading-snug">{{ $pengawas->nama }}</h3>
                            </div>
                        </div>

                        <!-- Actions: Tombol Visi Misi & Tombol Pilih -->
                        <div class="p-5 pt-0 flex flex-col gap-3.5">
                            <!-- Tombol Baca Visi & Misi (Font Gede, BG Gradient Blue) -->
                            <button 
                                type="button" 
                                onclick="event.stopPropagation(); showDetailModal('{{ __('Calon Pengawas Koperasi') }}', '{{ addslashes($pengawas->nama) }}', '{{ $pengawas->nomor_urut }}', `{{ addslashes($pengawas->visi) }}`, `{{ addslashes($pengawas->misi) }}`, '{{ $fotoPengawas }}')"
                                class="w-full py-3 sm:py-3.5 px-4 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 hover:from-blue-700 hover:to-indigo-800 text-white font-extrabold text-sm sm:text-base shadow-md hover:shadow-lg transition-all flex items-center justify-center space-x-2.5 cursor-pointer"
                            >
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                <span>{{ __('Baca Visi & Misi') }}</span>
                            </button>

                            <!-- Tombol Pilih (BG Success Emerald, 1 Centang Bersih) -->
                            <div 
                                id="btn-select-pengawas-{{ $pengawas->nik }}" 
                                class="w-full py-3.5 sm:py-4 px-4 rounded-2xl bg-emerald-50 hover:bg-emerald-600 border-2 border-emerald-500 hover:border-emerald-600 text-emerald-800 hover:text-white font-black text-sm sm:text-base shadow-xs hover:shadow-md transition-all duration-300 flex items-center justify-center space-x-2.5 text-center"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                                </svg>
                                <span>{{ __('PILIH') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Card Pilihan Pengawas Anda (Ditambah Margin Bottom Agar Tidak Tertutup Sticky Dock) -->
            <div class="mt-10 mb-28 sm:mb-32 p-5 sm:p-6 rounded-3xl bg-white border-2 border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                <button 
                    type="button" 
                    onclick="goToStep(1)"
                    class="w-full sm:w-auto px-6 py-4 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold text-sm sm:text-base transition flex items-center justify-center space-x-2.5 cursor-pointer"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                    <span>{{ __('Kembali ke Calon Ketua') }}</span>
                </button>

                <div class="text-center sm:text-left">
                    <span class="text-xs sm:text-sm text-slate-500 font-bold uppercase tracking-wider block">{{ __('Pilihan Pengawas Anda:') }}</span>
                    <strong id="step2-summary-text" class="text-base sm:text-lg font-black text-rose-600">{{ __('(Belum memilih calon pengawas)') }}</strong>
                </div>

                <button 
                    type="button" 
                    id="btn-goto-step-3"
                    onclick="goToStep(3)"
                    disabled
                    class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-base sm:text-lg shadow-md disabled:opacity-40 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:text-slate-500 transition-all flex items-center justify-center space-x-3 cursor-pointer"
                >
                    <span>{{ __('Lanjut ke Konfirmasi') }}</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                </button>
            </div>
        </section>

        <!-- ============================================== -->
        <!-- WIZARD STEP 3: PREVIEW & KONFIRMASI SUARA      -->
        <!-- ============================================== -->
        <section id="step-section-3" class="wizard-step-container hidden">
            <!-- Header Bersih Tanpa Ikon Atas Sesuai Request -->
            <div class="text-center max-w-2xl mx-auto mb-8 pt-2">
                <h3 class="text-2xl sm:text-3xl font-black text-slate-950 tracking-tight">{{ __('Tinjau & Konfirmasi Pilihan Anda') }}</h3>
                <p class="text-sm sm:text-base text-slate-600 font-medium mt-1.5">{{ __('Pastikan calon Ketua dan Pengawas yang Anda pilih sudah benar sebelum mengirim suara.') }}</p>
            </div>

            <!-- Preview Card Berdampingan (Ketua & Pengawas) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                
                <!-- Review Card Calon Ketua (Palet Merah) -->
                <div class="p-6 sm:p-7 rounded-3xl bg-white border-3 border-red-500 shadow-lg relative overflow-hidden flex flex-col justify-between">
                    <div class="absolute top-0 right-0 bg-red-600 text-white font-black text-xs uppercase px-4 py-1.5 rounded-bl-2xl shadow-sm tracking-wider">
                        {{ __('1. Calon Ketua Terpilih') }}
                    </div>

                    <div class="flex items-center space-x-5 mt-3 mb-6">
                        <div class="relative w-28 h-36 sm:w-32 sm:h-40 rounded-2xl overflow-hidden bg-slate-900 border-2 border-red-300 shadow-md shrink-0">
                            <img id="review-ketua-img" src="" alt="Ketua" class="w-full h-full object-cover object-top">
                            <div class="absolute top-2 left-2 w-9 h-9 rounded-xl bg-white text-slate-950 border border-slate-300 font-black text-lg flex items-center justify-center shadow-md">
                                <span id="review-ketua-nomor">1</span>
                            </div>
                        </div>
                        <div>
                            <span class="text-xs uppercase font-extrabold text-red-700 tracking-wider block mb-1">{{ __('Calon Ketua Koperasi') }}</span>
                            <h4 id="review-ketua-nama" class="text-xl sm:text-2xl font-black text-slate-950 leading-snug">Nama Calon</h4>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-slate-500 font-semibold">{{ __('Ingin mengubah pilihan?') }}</span>
                        <button 
                            type="button" 
                            onclick="goToStep(1)"
                            class="px-4 py-2 rounded-xl bg-red-50 hover:bg-red-100 text-red-700 font-bold text-xs sm:text-sm transition cursor-pointer"
                        >
                            {{ __('Ubah Pilihan Ketua') }}
                        </button>
                    </div>
                </div>

                <!-- Review Card Calon Pengawas -->
                <div class="p-6 sm:p-7 rounded-3xl bg-white border-3 border-emerald-500 shadow-lg relative overflow-hidden flex flex-col justify-between">
                    <div class="absolute top-0 right-0 bg-emerald-600 text-white font-black text-xs uppercase px-4 py-1.5 rounded-bl-2xl shadow-sm tracking-wider">
                        {{ __('2. Calon Pengawas Terpilih') }}
                    </div>

                    <div class="flex items-center space-x-5 mt-3 mb-6">
                        <div class="relative w-28 h-36 sm:w-32 sm:h-40 rounded-2xl overflow-hidden bg-slate-900 border-2 border-emerald-300 shadow-md shrink-0">
                            <img id="review-pengawas-img" src="" alt="Pengawas" class="w-full h-full object-cover object-top">
                            <div class="absolute top-2 left-2 w-9 h-9 rounded-xl bg-white text-slate-950 border border-slate-300 font-black text-lg flex items-center justify-center shadow-md">
                                <span id="review-pengawas-nomor">1</span>
                            </div>
                        </div>
                        <div>
                            <span class="text-xs uppercase font-extrabold text-emerald-800 tracking-wider block mb-1">{{ __('Calon Pengawas Koperasi') }}</span>
                            <h4 id="review-pengawas-nama" class="text-xl sm:text-2xl font-black text-slate-950 leading-snug">Nama Calon</h4>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-slate-500 font-semibold">{{ __('Ingin mengubah pilihan?') }}</span>
                        <button 
                            type="button" 
                            onclick="goToStep(2)"
                            class="px-4 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-xs sm:text-sm transition cursor-pointer"
                        >
                            {{ __('Ubah Pilihan Pengawas') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Box Jaminan & Tombol Aksi Ukuran Sempurna (Periksa Ulang & Vote Sekarang) - Versi Light Sesuai Permintaan -->
            <div class="p-6 sm:p-8 rounded-3xl bg-white border-2 border-slate-200 text-slate-900 shadow-xl text-center flex flex-col items-center mb-24 sm:mb-28">
                <div class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-300 text-xs sm:text-sm font-extrabold mb-4">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" /></svg>
                    <span>{{ __('Pilihan Anda Bersifat Rahasia, Langsung, Bebas, dan Sah') }}</span>
                </div>

                <h4 class="text-xl sm:text-2xl font-black text-slate-950 mb-2">{{ __('Apakah Anda Sudah Yakin dengan Pilihan Ini?') }}</h4>
                <p class="text-sm sm:text-base text-slate-600 max-w-xl mb-6 font-medium">
                    {{ __('Setelah tombol di bawah ditekan, suara Anda akan langsung dicatat oleh sistem secara permanen dan tidak dapat diubah kembali.') }}
                </p>

                <!-- Tombol Berdampingan Ukuran Sama Rata -->
                <div class="flex flex-col sm:flex-row gap-4 w-full max-w-xl justify-center">
                    <button 
                        type="button" 
                        onclick="goToStep(2)"
                        class="flex-1 py-4 sm:py-5 px-6 rounded-2xl bg-slate-100 hover:bg-slate-200 border-2 border-slate-300 text-slate-700 font-extrabold text-base sm:text-lg transition flex items-center justify-center space-x-2.5 cursor-pointer shadow-sm"
                    >
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                        <span>{{ __('Periksa Ulang') }}</span>
                    </button>

                    <button 
                        type="button" 
                        id="btn-final-submit"
                        onclick="executeVoteSubmission()"
                        class="flex-1 py-4 sm:py-5 px-6 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-base sm:text-lg shadow-xl hover:shadow-2xl transition-all duration-300 flex items-center justify-center space-x-2.5 cursor-pointer ring-4 ring-emerald-300 active:scale-98"
                    >
                        <svg id="btn-submit-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                        </svg>
                        <span id="btn-submit-label">{{ __('Vote Sekarang') }}</span>
                    </button>
                </div>
            </div>
        </section>

    </form>
</div>

<!-- ============================================== -->
<!-- FLOATING SUMMARY DOCK (Hanya Tampil di Step 1 & 2) -->
<!-- ============================================== -->
<div id="floating-ballot-dock" class="fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-md border-t-2 border-slate-200 shadow-2xl py-3.5 px-4 sm:px-6 z-40">
    <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3">
        
        <!-- Status Pilihan Pemilih -->
        <div class="flex items-center gap-4 text-xs sm:text-sm w-full sm:w-auto justify-between sm:justify-start">
            <div class="flex items-center space-x-2.5">
                <span class="w-8 h-8 rounded-xl bg-red-600 text-white font-black text-sm flex items-center justify-center shadow-xs">1</span>
                <div>
                    <span class="text-[11px] sm:text-xs text-slate-500 font-bold block">{{ __('Ketua Koperasi:') }}</span>
                    <strong id="dock-summary-ketua" class="text-rose-600 font-black text-xs sm:text-sm">{{ __('(Belum Dipilih)') }}</strong>
                </div>
            </div>

            <div class="h-9 w-px bg-slate-200"></div>

            <div class="flex items-center space-x-2.5">
                <span class="w-8 h-8 rounded-xl bg-emerald-600 text-white font-black text-sm flex items-center justify-center shadow-xs">2</span>
                <div>
                    <span class="text-[11px] sm:text-xs text-slate-500 font-bold block">{{ __('Pengawas Koperasi:') }}</span>
                    <strong id="dock-summary-pengawas" class="text-rose-600 font-black text-xs sm:text-sm">{{ __('(Belum Dipilih)') }}</strong>
                </div>
            </div>
        </div>

        <!-- Tombol Lanjut ke Review / Konfirmasi -->
        <button 
            type="button" 
            id="dock-btn-review"
            disabled
            onclick="goToStep(3)"
            class="w-full sm:w-auto px-7 py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-black text-sm sm:text-base shadow-md disabled:opacity-40 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:text-slate-500 transition-all duration-300 cursor-pointer flex items-center justify-center space-x-2.5"
        >
            <span>{{ __('TINJAU & KIRIM SUARA') }}</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </button>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL DETAIL VISI & MISI (Font Super Besar)   -->
<!-- ============================================== -->
<div id="detail-modal" class="fixed inset-0 z-50 hidden bg-slate-950/75 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="boomer-modal-dialog bg-white border-2 border-slate-200 rounded-3xl max-w-3xl sm:max-w-4xl w-full p-6 sm:p-9 shadow-2xl relative max-h-[88vh] overflow-y-auto">
        <button onclick="closeDetailModal()" class="absolute top-5 right-5 text-slate-500 hover:text-slate-800 p-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 transition cursor-pointer">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
        </button>

        <div class="flex items-center space-x-4 mb-6 pb-5 border-b-2 border-slate-200">
            <span id="modal-nomor" class="w-14 h-14 rounded-2xl bg-white text-slate-950 border-2 border-slate-300 flex items-center justify-center text-2xl sm:text-3xl font-black shadow-md">1</span>
            <div>
                <span id="modal-kategori" class="text-sm sm:text-base uppercase font-black tracking-wider text-blue-700 block mb-0.5">{{ __('Calon Ketua Koperasi') }}</span>
                <h3 id="modal-nama" class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-950 leading-snug">Nama Calon</h3>
            </div>
        </div>

        <div class="space-y-6">
            <div>
                <div class="flex items-center space-x-2.5 mb-2.5">
                    <span class="w-3.5 h-3.5 rounded-full bg-blue-600"></span>
                    <h4 class="text-sm sm:text-base uppercase font-black tracking-wider text-slate-700">{{ __('Visi Utama') }}:</h4>
                </div>
                <div id="modal-visi" class="p-6 sm:p-7 rounded-3xl bg-slate-50 border-2 border-slate-200 text-slate-900 text-lg sm:text-xl lg:text-2xl leading-relaxed whitespace-pre-line font-bold"></div>
            </div>

            <div>
                <div class="flex items-center space-x-2.5 mb-2.5">
                    <span class="w-3.5 h-3.5 rounded-full bg-emerald-600"></span>
                    <h4 class="text-sm sm:text-base uppercase font-black tracking-wider text-slate-700">{{ __('Misi Kerja') }}:</h4>
                </div>
                <div id="modal-misi" class="p-6 sm:p-7 rounded-3xl bg-slate-50 border-2 border-slate-200 text-slate-900 text-lg sm:text-xl lg:text-2xl leading-relaxed whitespace-pre-line font-bold"></div>
            </div>
        </div>

        <div class="mt-8 pt-5 border-t-2 border-slate-200 flex justify-end">
            <button onclick="closeDetailModal()" class="px-8 py-3.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-base sm:text-lg transition cursor-pointer shadow-md">
                {{ __('Tutup Jendela') }}
            </button>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- ANIMASI GEBYAR MERIAH CONGRATS (5 DETIK POPUP) -->
<!-- ============================================== -->
<div id="gebyar-modal" class="fixed inset-0 z-[100] hidden bg-slate-950/85 backdrop-blur-md flex items-center justify-center p-4">
    <div class="bg-white border-4 border-emerald-500 rounded-3xl max-w-lg w-full p-8 sm:p-10 shadow-2xl text-center relative overflow-hidden flex flex-col items-center">
        
        <!-- Background Radial Glow -->
        <div class="absolute -top-24 -left-24 w-64 h-64 bg-emerald-400/30 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-blue-400/30 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Trophy / Check Celebration Badge -->
        <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl bg-emerald-500 text-white flex items-center justify-center mb-5 shadow-xl ring-8 ring-emerald-100 animate-bounce">
            <svg class="w-14 h-14 sm:w-16 sm:h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
            </svg>
        </div>

        <span class="inline-flex items-center px-5 py-2 rounded-full text-sm font-black uppercase tracking-wider bg-emerald-100 text-emerald-900 border-2 border-emerald-400 mb-3">
            🎉 {{ __('SUARA SAH TERCATAT!') }} 🎉
        </span>

        <h3 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-950 mb-3 tracking-tight">
            {{ __('Selamat, :nama!', ['nama' => $pemilih->nama]) }}
        </h3>

        <p class="text-xl sm:text-2xl font-black text-emerald-800 mb-2">
            {{ __('Suara Anda Telah Sah & Berhasil Disimpan!') }}
        </p>

        <p class="text-base sm:text-lg text-slate-700 font-semibold mb-6 max-w-md">
            {{ __('Terima kasih atas partisipasi aktif Bapak/Ibu :nama dalam Pemilihan Koperasi ini.', ['nama' => $pemilih->nama]) }}
        </p>

        <!-- Countdown Bar 5 Detik -->
        <div class="w-full p-4 rounded-2xl bg-slate-50 border border-slate-200 mb-3">
            <div class="flex items-center justify-between text-xs sm:text-sm font-bold text-slate-700 mb-2">
                <span>{{ __('Kembali ke layar kios dalam:') }}</span>
                <span class="font-black text-emerald-700 text-base sm:text-lg"><span id="gebyar-countdown">5</span> Detik</span>
            </div>
            <div class="w-full h-3 rounded-full bg-slate-200 overflow-hidden">
                <div id="gebyar-progress-bar" class="h-full bg-emerald-600 transition-all duration-1000 ease-linear" style="width: 100%;"></div>
            </div>
        </div>

        <a href="{{ route('voter.tap') }}" class="text-xs text-slate-400 hover:text-slate-600 font-bold underline transition mt-2">
            {{ __('Klik di sini jika tidak beralih otomatis') }}
        </a>
    </div>
</div>

<!-- ============================================== -->
<!-- ANIMASI PEDAS ERROR MODAL                      -->
<!-- ============================================== -->
<div id="spicy-error-modal" class="fixed inset-0 z-[100] hidden bg-rose-950/80 backdrop-blur-md flex items-center justify-center p-4">
    <div class="bg-white border-4 border-rose-600 rounded-3xl max-w-md w-full p-7 sm:p-8 shadow-2xl text-center relative animate-distracted flex flex-col items-center">
        
        <!-- Icon Api / Danger Pedas -->
        <div class="w-20 h-20 rounded-3xl bg-rose-600 text-white flex items-center justify-center mb-4 shadow-xl ring-8 ring-rose-100">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>
        </div>

        <span class="inline-flex items-center px-4 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-rose-100 text-rose-900 border border-rose-300 mb-2">
            ⚠️ {{ __('Terjadi Kesalahan') }} ⚠️
        </span>

        <h3 class="text-xl sm:text-2xl font-black text-slate-950 mb-2 tracking-tight">
            {{ __('Gagal Mengirim Suara!') }}
        </h3>

        <p id="spicy-error-text" class="text-sm sm:text-base text-slate-700 font-bold mb-6 max-w-xs leading-relaxed">
            {{ __('Mohon periksa kembali pilihan Anda atau hubungi petugas TPS.') }}
        </p>

        <button 
            type="button" 
            onclick="closeSpicyError()"
            class="w-full py-4 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-black text-base shadow-lg transition cursor-pointer"
        >
            {{ __('Tutup & Coba Lagi') }}
        </button>
    </div>
</div>

@push('scripts')
<script>
    let currentStep = 1;
    let selectedKetua = null;
    let selectedPengawas = null;
    let isSubmitting = false;

    const i18n = {
        touchToSelect: "{{ __('PILIH') }}",
        selectedFormat: "{{ __('TERPILIH') }}",
        chairmanSelectedPrefix: "{{ __('Terpilih: No.') }} ",
        supervisorSelectedPrefix: "{{ __('Terpilih: No.') }} ",
        submitting: "{{ __('Memproses Suara...') }}",
        voteNow: "{{ __('Vote Sekarang') }}"
    };

    // 1. Full-Screen Cinematic 3-Second Welcoming Overlay
    document.addEventListener('DOMContentLoaded', () => {
        const overlay = document.getElementById('vote-welcome-overlay');
        const progressFill = document.getElementById('welcome-progress-fill');

        if (overlay) {
            setTimeout(() => {
                if (progressFill) progressFill.style.width = '100%';
            }, 60);

            // Mainkan sound effect welcome saat masuk bilik suara
            if (window.SoundEffects && typeof window.SoundEffects.welcome === 'function') {
                window.SoundEffects.welcome();
            }

            // Welcoming tepat 3.0 detik, lalu smooth transisi ke konten bilik suara
            setTimeout(() => {
                overlay.classList.add('opacity-0', 'scale-105', 'pointer-events-none');
                setTimeout(() => {
                    overlay.remove();
                }, 500);
            }, 2500); // 2.5s tampil + 0.5s fadeout = tepat 3.0 detik
        }

        @if(session('error'))
            showSpicyError("{{ session('error') }}");
        @endif
    });

    // 2. Wizard Navigation System
    function goToStep(stepNumber) {
        if (stepNumber === 2 && !selectedKetua) {
            alert("{{ __('Silakan pilih Calon Ketua terlebih dahulu sebelum melanjutkan.') }}");
            return;
        }
        if (stepNumber === 3 && (!selectedKetua || !selectedPengawas)) {
            alert("{{ __('Silakan lengkapi pilihan Calon Ketua dan Pengawas terlebih dahulu.') }}");
            return;
        }

        if (window.SoundEffects) window.SoundEffects.click();
        currentStep = stepNumber;

        // Hide all steps
        document.querySelectorAll('.wizard-step-container').forEach(el => el.classList.add('hidden'));

        // Show target step
        const targetSection = document.getElementById('step-section-' + stepNumber);
        if (targetSection) {
            targetSection.classList.remove('hidden');
        }

        // Update stepper tab indicators
        updateStepperIndicators();

        // Control floating dock visibility (hide on step 3)
        const dock = document.getElementById('floating-ballot-dock');
        if (dock) {
            if (stepNumber === 3) {
                dock.classList.add('hidden');
                populateReviewStep();
            } else {
                dock.classList.remove('hidden');
            }
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function updateStepperIndicators() {
        for (let i = 1; i <= 3; i++) {
            const tab = document.getElementById('stepper-tab-' + i);
            const badge = document.getElementById('step-badge-' + i);
            if (!tab || !badge) continue;

            if (i === currentStep) {
                if (i === 1) {
                    tab.className = "flex-1 flex flex-col sm:flex-row items-center justify-center gap-1.5 sm:gap-2.5 p-2 sm:py-3.5 sm:px-4 rounded-2xl bg-red-600 text-white font-black text-xs sm:text-base transition-all shadow-sm cursor-pointer";
                    badge.className = "w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-white text-red-700 flex items-center justify-center font-black text-sm sm:text-base shadow-xs shrink-0";
                } else if (i === 2) {
                    tab.className = "flex-1 flex flex-col sm:flex-row items-center justify-center gap-1.5 sm:gap-2.5 p-2 sm:py-3.5 sm:px-4 rounded-2xl bg-emerald-600 text-white font-black text-xs sm:text-base transition-all shadow-sm cursor-pointer";
                    badge.className = "w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-white text-emerald-700 flex items-center justify-center font-black text-sm sm:text-base shadow-xs shrink-0";
                } else {
                    tab.className = "flex-1 flex flex-col sm:flex-row items-center justify-center gap-1.5 sm:gap-2.5 p-2 sm:py-3.5 sm:px-4 rounded-2xl bg-blue-600 text-white font-black text-xs sm:text-base transition-all shadow-sm cursor-pointer";
                    badge.className = "w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-white text-blue-700 flex items-center justify-center font-black text-sm sm:text-base shadow-xs shrink-0";
                }
            } else if ((i === 1 && selectedKetua) || (i === 2 && selectedPengawas)) {
                tab.className = "flex-1 flex flex-col sm:flex-row items-center justify-center gap-1.5 sm:gap-2.5 p-2 sm:py-3.5 sm:px-4 rounded-2xl bg-emerald-50 text-emerald-800 border border-emerald-300 font-extrabold text-xs sm:text-base transition-all cursor-pointer";
                badge.className = "w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-black text-sm sm:text-base shrink-0";
                badge.innerHTML = "✓";
            } else {
                tab.className = "flex-1 flex flex-col sm:flex-row items-center justify-center gap-1.5 sm:gap-2.5 p-2 sm:py-3.5 sm:px-4 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold text-xs sm:text-base transition-all cursor-pointer";
                badge.className = "w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-slate-300 text-slate-700 flex items-center justify-center font-black text-sm sm:text-base shrink-0";
                badge.innerText = i;
            }
        }
    }

    // 3. Selection Calon Ketua (Palet Merah)
    function selectKetua(nik, nama, nomor, foto) {
        if (window.SoundEffects) window.SoundEffects.click();
        selectedKetua = { nik, nama, nomor, foto };
        document.getElementById('radio-ketua-' + nik).checked = true;

        // Reset semua kartu ketua
        document.querySelectorAll('.candidate-card-ketua').forEach(card => {
            card.classList.remove('border-4', 'border-red-600', 'bg-red-50/70', 'ring-8', 'ring-red-300', 'shadow-2xl', 'card-selected-pop');
            card.classList.add('border-3', 'border-slate-200', 'bg-white');

            const btnSelect = card.querySelector('[id^="btn-select-ketua-"]');
            if (btnSelect) {
                btnSelect.className = 'w-full py-3.5 sm:py-4 px-4 rounded-2xl bg-emerald-50 hover:bg-emerald-600 border-2 border-emerald-500 hover:border-emerald-600 text-emerald-800 hover:text-white font-black text-sm sm:text-base shadow-xs hover:shadow-md transition-all duration-300 flex items-center justify-center space-x-2.5 text-center';
                btnSelect.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" /></svg><span>${i18n.touchToSelect}</span>`;
            }
        });

        // Set kartu terpilih (Palet Merah Solid & Tegas)
        const activeCard = document.getElementById('card-ketua-' + nik);
        activeCard.classList.remove('border-3', 'border-slate-200', 'bg-white');
        activeCard.classList.add('border-4', 'border-red-600', 'bg-red-50/70', 'ring-8', 'ring-red-300', 'shadow-2xl', 'card-selected-pop');

        const activeBtn = document.getElementById('btn-select-ketua-' + nik);
        activeBtn.className = 'w-full py-3.5 sm:py-4 px-4 rounded-2xl bg-emerald-600 border-2 border-emerald-600 text-white font-black text-sm sm:text-base shadow-lg ring-4 ring-emerald-200 transition-all duration-300 flex items-center justify-center space-x-2.5 text-center';
        activeBtn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg><span>${i18n.selectedFormat}</span>`;

        // Update badge kategori atas
        const badge = document.getElementById('ketua-status-badge');
        badge.className = 'inline-flex self-start sm:self-auto px-4 py-2 rounded-2xl text-xs sm:text-sm font-extrabold bg-red-100 text-red-900 border-2 border-red-300';
        badge.innerText = '✓ ' + i18n.chairmanSelectedPrefix + nomor;

        // Update summary di Step 1
        const step1Summary = document.getElementById('step1-summary-text');
        step1Summary.className = 'text-base sm:text-lg font-black text-red-700';
        step1Summary.innerText = 'No. ' + nomor + ' - ' + nama;

        // Enable tombol Lanjut ke Step 2
        const btnNextStep2 = document.getElementById('btn-goto-step-2');
        btnNextStep2.removeAttribute('disabled');
        btnNextStep2.classList.add('animate-pulse');

        // Update dock summary
        const dockKetua = document.getElementById('dock-summary-ketua');
        dockKetua.className = 'text-red-700 font-black text-xs sm:text-sm';
        dockKetua.innerText = 'No. ' + nomor + ' - ' + nama;

        updateStepperIndicators();
        checkDockReadiness();
    }

    // 4. Selection Calon Pengawas
    function selectPengawas(nik, nama, nomor, foto) {
        if (window.SoundEffects) window.SoundEffects.click();
        selectedPengawas = { nik, nama, nomor, foto };
        document.getElementById('radio-pengawas-' + nik).checked = true;

        // Reset semua kartu pengawas
        document.querySelectorAll('.candidate-card-pengawas').forEach(card => {
            card.classList.remove('border-4', 'border-emerald-600', 'bg-emerald-50/70', 'ring-8', 'ring-emerald-300', 'shadow-2xl', 'card-selected-pop');
            card.classList.add('border-3', 'border-slate-200', 'bg-white');

            const btnSelect = card.querySelector('[id^="btn-select-pengawas-"]');
            if (btnSelect) {
                btnSelect.className = 'w-full py-3.5 sm:py-4 px-4 rounded-2xl bg-emerald-50 hover:bg-emerald-600 border-2 border-emerald-500 hover:border-emerald-600 text-emerald-800 hover:text-white font-black text-sm sm:text-base shadow-xs hover:shadow-md transition-all duration-300 flex items-center justify-center space-x-2.5 text-center';
                btnSelect.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" /></svg><span>${i18n.touchToSelect}</span>`;
            }
        });

        // Set kartu terpilih (Stroke Ekstra Tebal 4px + Ring 8px Terang Kontras)
        const activeCard = document.getElementById('card-pengawas-' + nik);
        activeCard.classList.remove('border-3', 'border-slate-200', 'bg-white');
        activeCard.classList.add('border-4', 'border-emerald-600', 'bg-emerald-50/70', 'ring-8', 'ring-emerald-300', 'shadow-2xl', 'card-selected-pop');

        const activeBtn = document.getElementById('btn-select-pengawas-' + nik);
        activeBtn.className = 'w-full py-3.5 sm:py-4 px-4 rounded-2xl bg-emerald-600 border-2 border-emerald-600 text-white font-black text-sm sm:text-base shadow-lg ring-4 ring-emerald-200 transition-all duration-300 flex items-center justify-center space-x-2.5 text-center';
        activeBtn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg><span>${i18n.selectedFormat}</span>`;

        // Update badge kategori atas
        const badge = document.getElementById('pengawas-status-badge');
        badge.className = 'inline-flex self-start sm:self-auto px-4 py-2 rounded-2xl text-xs sm:text-sm font-extrabold bg-emerald-100 text-emerald-900 border-2 border-emerald-300';
        badge.innerText = '✓ ' + i18n.supervisorSelectedPrefix + nomor;

        // Update summary di Step 2
        const step2Summary = document.getElementById('step2-summary-text');
        step2Summary.className = 'text-base sm:text-lg font-black text-emerald-700';
        step2Summary.innerText = 'No. ' + nomor + ' - ' + nama;

        // Enable tombol Lanjut ke Step 3
        const btnNextStep3 = document.getElementById('btn-goto-step-3');
        btnNextStep3.removeAttribute('disabled');
        btnNextStep3.classList.add('animate-pulse');

        // Update dock summary
        const dockPengawas = document.getElementById('dock-summary-pengawas');
        dockPengawas.className = 'text-emerald-700 font-black text-xs sm:text-sm';
        dockPengawas.innerText = 'No. ' + nomor + ' - ' + nama;

        updateStepperIndicators();
        checkDockReadiness();
    }

    function checkDockReadiness() {
        const btnDock = document.getElementById('dock-btn-review');
        if (selectedKetua && selectedPengawas) {
            btnDock.removeAttribute('disabled');
            btnDock.classList.add('animate-pulse');
        } else {
            btnDock.setAttribute('disabled', 'disabled');
            btnDock.classList.remove('animate-pulse');
        }
    }

    // 5. Populate Step 3 Review Screen
    function populateReviewStep() {
        if (!selectedKetua || !selectedPengawas) return;

        // Ketua
        document.getElementById('review-ketua-img').src = selectedKetua.foto;
        document.getElementById('review-ketua-nomor').innerText = selectedKetua.nomor;
        document.getElementById('review-ketua-nama').innerText = selectedKetua.nama;

        // Pengawas
        document.getElementById('review-pengawas-img').src = selectedPengawas.foto;
        document.getElementById('review-pengawas-nomor').innerText = selectedPengawas.nomor;
        document.getElementById('review-pengawas-nama').innerText = selectedPengawas.nama;
    }

    // 6. Submit Suara Final dengan Throttle, Spinner, Gebyar Congrats & Spicy Error
    async function executeVoteSubmission() {
        if (isSubmitting) return;

        if (!selectedKetua || !selectedPengawas) {
            showSpicyError("{{ __('Pilihan belum lengkap. Silakan pilih Calon Ketua dan Pengawas.') }}");
            return;
        }

        // Throttle Double Click & Tampilkan Spinner Loading
        isSubmitting = true;
        const btn = document.getElementById('btn-final-submit');
        const icon = document.getElementById('btn-submit-icon');
        const label = document.getElementById('btn-submit-label');

        btn.setAttribute('disabled', 'disabled');
        btn.classList.add('opacity-75', 'cursor-not-allowed');
        
        if (icon) {
            icon.outerHTML = `<svg id="btn-submit-icon" class="w-6 h-6 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>`;
        }
        if (label) {
            label.innerText = i18n.submitting;
        }

        if (window.SoundEffects) window.SoundEffects.click();

        try {
            const form = document.getElementById('voting-form');
            const formData = new FormData(form);

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Tampilkan Animasi Gebyar Meriah Congrats selama 5 detik
                showGebyarCongrats(data.redirect || "{{ route('voter.tap') }}");
            } else {
                throw new Error(data.message || "{{ __('Gagal mencatat suara. Silakan coba lagi.') }}");
            }

        } catch (err) {
            showSpicyError(err.message || "{{ __('Terjadi kesalahan jaringan atau sistem. Silakan coba lagi.') }}");
            resetSubmitButton();
        }
    }

    function resetSubmitButton() {
        isSubmitting = false;
        const btn = document.getElementById('btn-final-submit');
        btn.removeAttribute('disabled');
        btn.classList.remove('opacity-75', 'cursor-not-allowed');
        btn.innerHTML = `<svg id="btn-submit-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" /></svg><span id="btn-submit-label">${i18n.voteNow}</span>`;
    }

    // 7. Animasi Gebyar Meriah Congrats 5 Detik
    function showGebyarCongrats(redirectUrl) {
        if (window.SoundEffects) window.SoundEffects.success();

        const gebyarModal = document.getElementById('gebyar-modal');
        gebyarModal.classList.remove('hidden');

        // Subtle Confetti Cannons: Ringan di pinggir layar agar tidak menutupi kartu & teks
        if (typeof window.confetti === 'function') {
            const end = Date.now() + 3000;
            const colors = ['#2563eb', '#10b981', '#f59e0b', '#8b5cf6'];

            (function frame() {
                window.confetti({
                    particleCount: 2,
                    angle: 60,
                    spread: 45,
                    origin: { x: 0, y: 0.8 },
                    colors: colors,
                    zIndex: 90
                });
                window.confetti({
                    particleCount: 2,
                    angle: 120,
                    spread: 45,
                    origin: { x: 1, y: 0.8 },
                    colors: colors,
                    zIndex: 90
                });

                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                }
            }());
        }

        // Countdown 5 Detik & Auto Redirect
        let secondsLeft = 5;
        const countdownEl = document.getElementById('gebyar-countdown');
        const progressBar = document.getElementById('gebyar-progress-bar');

        const timer = setInterval(() => {
            secondsLeft--;
            if (countdownEl) countdownEl.innerText = secondsLeft;
            if (progressBar) progressBar.style.width = (secondsLeft / 5 * 100) + '%';

            if (secondsLeft <= 0) {
                clearInterval(timer);
                window.location.href = redirectUrl;
            }
        }, 1000);
    }

    // 8. Animasi Pedas Error Modal
    function showSpicyError(msg) {
        if (window.SoundEffects) window.SoundEffects.error();

        const modal = document.getElementById('spicy-error-modal');
        const text = document.getElementById('spicy-error-text');
        if (text) text.innerText = msg;
        if (modal) modal.classList.remove('hidden');
    }

    function closeSpicyError() {
        if (window.SoundEffects) window.SoundEffects.click();
        const modal = document.getElementById('spicy-error-modal');
        if (modal) modal.classList.add('hidden');
        resetSubmitButton();
    }

    // 9. Modal Visi & Misi
    function showDetailModal(kategori, nama, nomor, visi, misi, foto) {
        if (window.SoundEffects) window.SoundEffects.modal();
        document.getElementById('modal-kategori').innerText = kategori;
        document.getElementById('modal-nama').innerText = nama;
        document.getElementById('modal-nomor').innerText = nomor;
        document.getElementById('modal-visi').innerText = visi || '-';
        document.getElementById('modal-misi').innerText = misi || '-';

        document.getElementById('detail-modal').classList.remove('hidden');
    }

    function closeDetailModal() {
        if (window.SoundEffects) window.SoundEffects.click();
        document.getElementById('detail-modal').classList.add('hidden');
    }

    // Click outside modal to close
    window.addEventListener('click', function(e) {
        const detailModal = document.getElementById('detail-modal');
        if (e.target === detailModal) closeDetailModal();
    });
</script>
@endpush
@endsection
