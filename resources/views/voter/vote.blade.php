@extends('layouts.app')

@section('title', __('Ballot Booth - Cooperative Election'))

@section('content')
<div class="flex-1 flex flex-col p-4 sm:p-6 lg:p-8 max-w-6xl mx-auto w-full pb-44 sm:pb-48">

    <!-- Header Identitas Pemilih (Proporsional & Nyaman di Tablet) -->
    <header class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div class="flex items-center space-x-3.5">
            <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-lg shadow-sm shrink-0">
                {{ strtoupper(substr($pemilih->nama, 0, 2)) }}
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">{{ $pemilih->nama }}</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        {{ __('Active Voting Right') }}
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 font-medium">
                    {{ __('NIK:') }} <span class="font-bold text-slate-700 font-mono">{{ $pemilih->nik }}</span> • {{ __('Department:') }} <span class="font-bold text-slate-700">{{ $pemilih->dept }}</span>
                </p>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <!-- Language Switcher Pill -->
            <div class="inline-flex rounded-lg border border-slate-200 bg-white p-0.5 text-xs font-bold shadow-2xs">
                <a href="{{ route('lang.switch', 'en') }}" class="px-2.5 py-1 rounded-md transition {{ app()->getLocale() === 'en' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-blue-600' }}">EN</a>
                <a href="{{ route('lang.switch', 'id') }}" class="px-2.5 py-1 rounded-md transition {{ app()->getLocale() === 'id' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-blue-600' }}">ID</a>
            </div>

            <form action="{{ route('voter.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full sm:w-auto px-4 py-2 rounded-xl bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-rose-700 border border-slate-200 hover:border-rose-300 text-xs font-bold transition flex items-center justify-center space-x-1.5 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>{{ __('Cancel & Logout') }}</span>
                </button>
            </form>
        </div>
    </header>

    <form id="voting-form" action="{{ route('voter.vote.store') }}" method="POST">
        @csrf

        <!-- ============================================== -->
        <!-- KATEGORI 1: PEMILIHAN KETUA KOPERASI           -->
        <!-- ============================================== -->
        <section class="mb-10">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3.5 mb-5 border-b border-slate-200 gap-2">
                <div class="flex items-center space-x-3">
                    <span class="w-9 h-9 rounded-xl bg-blue-600 text-white font-extrabold flex items-center justify-center text-base shadow-sm shrink-0">1</span>
                    <div>
                        <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">{{ __('Select 1 (One) Chairman Candidate') }}</h3>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium">{{ __('Touch candidate box to make your choice') }} • {{ __('Click on candidate picture to zoom in preview') }}</p>
                    </div>
                </div>
                <div id="ketua-status-badge" class="inline-flex self-start sm:self-auto px-3.5 py-1.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                    {{ __('Chairman Not Selected Yet') }}
                </div>
            </div>

            <!-- Grid Responsif untuk Tablet: 2 Kolom di Tablet, 3 Kolom di Layar Lebar -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($kandidatKetua as $ketua)
                    @php
                        $fotoKetua = $ketua->foto ?: 'https://ui-avatars.com/api/?name='.urlencode($ketua->nama).'&background=2563eb&color=ffffff&size=400';
                    @endphp
                    <div 
                        id="card-ketua-{{ $ketua->nik }}"
                        onclick="selectKetua('{{ $ketua->nik }}', '{{ addslashes($ketua->nama) }}', '{{ $ketua->nomor_urut }}')"
                        class="candidate-card-ketua ballot-card-sundul relative rounded-3xl bg-white border-2 border-slate-200 hover:border-blue-400 p-5 flex flex-col justify-between cursor-pointer shadow-2xs transition-all duration-300"
                    >
                        <input type="radio" name="ketua_nik" value="{{ $ketua->nik }}" id="radio-ketua-{{ $ketua->nik }}" class="hidden">

                        <div>
                            <!-- Header Nomor Urut & Check Icon -->
                            <div class="flex items-center justify-between mb-3.5">
                                <div class="flex items-center space-x-2.5">
                                    <span class="w-10 h-10 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-lg font-black shadow-sm">
                                        {{ $ketua->nomor_urut }}
                                    </span>
                                    <div>
                                        <span class="text-xs font-extrabold text-blue-600 uppercase tracking-wider block">{{ __('Candidate No.') }} {{ $ketua->nomor_urut }}</span>
                                        <span class="text-[11px] text-slate-400 font-medium">{{ __('Chairman Candidate') }}</span>
                                    </div>
                                </div>
                                <div id="check-icon-ketua-{{ $ketua->nik }}" class="w-8 h-8 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-transparent transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            </div>

                            <!-- Foto Kandidat dengan Tombol Zoom Preview -->
                            <div 
                                onclick="event.stopPropagation(); previewCandidatePhoto('{{ $fotoKetua }}', '{{ addslashes($ketua->nama) }}', '{{ $ketua->nomor_urut }}', '{{ __('Chairman Candidate') }}')"
                                class="relative group w-full h-52 sm:h-56 rounded-2xl overflow-hidden bg-slate-100 mb-3.5 border border-slate-200 cursor-zoom-in"
                                title="{{ __('Click on candidate picture to zoom in preview') }}"
                            >
                                <img 
                                    src="{{ $fotoKetua }}" 
                                    alt="{{ $ketua->nama }}" 
                                    class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500 ease-out"
                                >
                                <div class="absolute inset-0 bg-slate-900/30 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                    <span class="px-3 py-1.5 rounded-xl bg-white/90 text-slate-900 font-bold text-xs shadow-md flex items-center space-x-1.5 backdrop-blur-xs">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                        <span>{{ __('Click to Zoom') }}</span>
                                    </span>
                                </div>
                            </div>

                            <h4 class="text-base sm:text-lg font-extrabold text-slate-900 mb-1 leading-snug">{{ $ketua->nama }}</h4>
                            <p class="text-xs text-slate-500 line-clamp-2 mb-3.5 font-medium">{{ $ketua->deskripsi ?: __('Official Chairman Candidate') }}</p>
                        </div>

                        <div>
                            <!-- Tombol Visi Misi -->
                            <div class="mb-3">
                                <button 
                                    type="button" 
                                    onclick="event.stopPropagation(); showDetailModal('{{ __('Chairman Candidate') }}', '{{ addslashes($ketua->nama) }}', '{{ $ketua->nomor_urut }}', `{{ addslashes($ketua->visi) }}`, `{{ addslashes($ketua->misi) }}`, '{{ $fotoKetua }}')"
                                    class="w-full py-2 px-3 rounded-xl bg-slate-100 hover:bg-blue-50 text-blue-700 font-bold text-xs border border-slate-200 transition text-center cursor-pointer flex items-center justify-center space-x-1.5"
                                >
                                    <span>📄 {{ __('Read Vision & Mission') }}</span>
                                </button>
                            </div>

                            <!-- Banner Status Pemilihan -->
                            <div id="btn-select-ketua-{{ $ketua->nik }}" class="w-full py-3 rounded-xl bg-slate-100 border border-slate-300 text-slate-700 font-extrabold text-xs sm:text-sm text-center transition-all duration-300">
                                {{ __('TOUCH TO SELECT') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- ============================================== -->
        <!-- KATEGORI 2: PEMILIHAN PENGAWAS KOPERASI        -->
        <!-- ============================================== -->
        <section class="mb-10">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3.5 mb-5 border-b border-slate-200 gap-2">
                <div class="flex items-center space-x-3">
                    <span class="w-9 h-9 rounded-xl bg-emerald-600 text-white font-extrabold flex items-center justify-center text-base shadow-sm shrink-0">2</span>
                    <div>
                        <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">{{ __('Select 1 (One) Supervisor Candidate') }}</h3>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium">{{ __('Touch candidate box to make your choice') }} • {{ __('Click on candidate picture to zoom in preview') }}</p>
                    </div>
                </div>
                <div id="pengawas-status-badge" class="inline-flex self-start sm:self-auto px-3.5 py-1.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                    {{ __('Supervisor Not Selected Yet') }}
                </div>
            </div>

            <!-- Grid Responsif untuk Tablet: 2 Kolom di Tablet, 3 Kolom di Layar Lebar -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($kandidatPengawas as $pengawas)
                    @php
                        $fotoPengawas = $pengawas->foto ?: 'https://ui-avatars.com/api/?name='.urlencode($pengawas->nama).'&background=059669&color=ffffff&size=400';
                    @endphp
                    <div 
                        id="card-pengawas-{{ $pengawas->nik }}"
                        onclick="selectPengawas('{{ $pengawas->nik }}', '{{ addslashes($pengawas->nama) }}', '{{ $pengawas->nomor_urut }}')"
                        class="candidate-card-pengawas ballot-card-sundul relative rounded-3xl bg-white border-2 border-slate-200 hover:border-emerald-400 p-5 flex flex-col justify-between cursor-pointer shadow-2xs transition-all duration-300"
                    >
                        <input type="radio" name="pengawas_nik" value="{{ $pengawas->nik }}" id="radio-pengawas-{{ $pengawas->nik }}" class="hidden">

                        <div>
                            <!-- Header Nomor Urut & Check Icon -->
                            <div class="flex items-center justify-between mb-3.5">
                                <div class="flex items-center space-x-2.5">
                                    <span class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-lg font-black shadow-sm">
                                        {{ $pengawas->nomor_urut }}
                                    </span>
                                    <div>
                                        <span class="text-xs font-extrabold text-emerald-700 uppercase tracking-wider block">{{ __('Candidate No.') }} {{ $pengawas->nomor_urut }}</span>
                                        <span class="text-[11px] text-slate-400 font-medium">{{ __('Supervisor Candidate') }}</span>
                                    </div>
                                </div>
                                <div id="check-icon-pengawas-{{ $pengawas->nik }}" class="w-8 h-8 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-transparent transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            </div>

                            <!-- Foto Kandidat dengan Tombol Zoom Preview -->
                            <div 
                                onclick="event.stopPropagation(); previewCandidatePhoto('{{ $fotoPengawas }}', '{{ addslashes($pengawas->nama) }}', '{{ $pengawas->nomor_urut }}', '{{ __('Supervisor Candidate') }}')"
                                class="relative group w-full h-52 sm:h-56 rounded-2xl overflow-hidden bg-slate-100 mb-3.5 border border-slate-200 cursor-zoom-in"
                                title="{{ __('Click on candidate picture to zoom in preview') }}"
                            >
                                <img 
                                    src="{{ $fotoPengawas }}" 
                                    alt="{{ $pengawas->nama }}" 
                                    class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500 ease-out"
                                >
                                <div class="absolute inset-0 bg-slate-900/30 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                    <span class="px-3 py-1.5 rounded-xl bg-white/90 text-slate-900 font-bold text-xs shadow-md flex items-center space-x-1.5 backdrop-blur-xs">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                        <span>{{ __('Click to Zoom') }}</span>
                                    </span>
                                </div>
                            </div>

                            <h4 class="text-base sm:text-lg font-extrabold text-slate-900 mb-1 leading-snug">{{ $pengawas->nama }}</h4>
                            <p class="text-xs text-slate-500 line-clamp-2 mb-3.5 font-medium">{{ $pengawas->deskripsi ?: __('Official Supervisor Candidate') }}</p>
                        </div>

                        <div>
                            <!-- Tombol Visi Misi -->
                            <div class="mb-3">
                                <button 
                                    type="button" 
                                    onclick="event.stopPropagation(); showDetailModal('{{ __('Supervisor Candidate') }}', '{{ addslashes($pengawas->nama) }}', '{{ $pengawas->nomor_urut }}', `{{ addslashes($pengawas->visi) }}`, `{{ addslashes($pengawas->misi) }}`, '{{ $fotoPengawas }}')"
                                    class="w-full py-2 px-3 rounded-xl bg-slate-100 hover:bg-emerald-50 text-emerald-800 font-bold text-xs border border-slate-200 transition text-center cursor-pointer flex items-center justify-center space-x-1.5"
                                >
                                    <span>📄 {{ __('Read Vision & Mission') }}</span>
                                </button>
                            </div>

                            <!-- Banner Status Pemilihan -->
                            <div id="btn-select-pengawas-{{ $pengawas->nik }}" class="w-full py-3 rounded-xl bg-slate-100 border border-slate-300 text-slate-700 font-extrabold text-xs sm:text-sm text-center transition-all duration-300">
                                {{ __('TOUCH TO SELECT') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </form>
</div>

<!-- ============================================== -->
<!-- FLOATING ACTION DOCK (Proporsional & Rapi)     -->
<!-- ============================================== -->
<div class="fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-md border-t border-slate-200 shadow-2xl py-3 px-4 sm:px-6 z-40">
    <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3">
        
        <!-- Status Pilihan Pemilih -->
        <div class="flex items-center gap-4 text-xs w-full sm:w-auto justify-between sm:justify-start">
            <div class="flex items-center space-x-2.5">
                <span class="w-7 h-7 rounded-lg bg-blue-600 text-white font-black text-xs flex items-center justify-center shadow-xs">1</span>
                <div>
                    <span class="text-[11px] text-slate-500 font-medium block">{{ __('Chairman Choice:') }}</span>
                    <strong id="summary-ketua" class="text-rose-600 font-bold text-xs sm:text-sm">{{ __('(Not selected yet)') }}</strong>
                </div>
            </div>

            <div class="h-8 w-px bg-slate-200"></div>

            <div class="flex items-center space-x-2.5">
                <span class="w-7 h-7 rounded-lg bg-emerald-600 text-white font-black text-xs flex items-center justify-center shadow-xs">2</span>
                <div>
                    <span class="text-[11px] text-slate-500 font-medium block">{{ __('Supervisor Choice:') }}</span>
                    <strong id="summary-pengawas" class="text-rose-600 font-bold text-xs sm:text-sm">{{ __('(Not selected yet)') }}</strong>
                </div>
            </div>
        </div>

        <!-- Tombol Lanjut / Kirim Suara -->
        <button 
            type="button" 
            id="btn-confirm-trigger"
            disabled
            onclick="openConfirmModal()"
            class="w-full sm:w-auto px-7 py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-black text-sm sm:text-base shadow-md disabled:opacity-40 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:text-slate-500 transition-all duration-300 cursor-pointer flex items-center justify-center space-x-2.5"
        >
            <span>{{ __('SUBMIT BALLOT') }}</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </button>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL DETAIL VISI & MISI (Boomer Friendly)     -->
<!-- ============================================== -->
<div id="detail-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="boomer-modal-dialog bg-white border border-slate-200 rounded-3xl max-w-xl w-full p-6 sm:p-7 shadow-2xl relative max-h-[85vh] overflow-y-auto">
        <button onclick="closeDetailModal()" class="absolute top-4 right-4 text-slate-500 hover:text-slate-800 p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-base font-bold transition cursor-pointer">✕</button>

        <div class="flex items-center space-x-3.5 mb-5 pb-3.5 border-b border-slate-200">
            <span id="modal-nomor" class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-xl font-black shadow-sm">01</span>
            <div>
                <span id="modal-kategori" class="text-xs uppercase font-extrabold tracking-wider text-blue-700 block">{{ __('Chairman Candidate') }}</span>
                <h3 id="modal-nama" class="text-lg sm:text-xl font-extrabold text-slate-900 leading-snug">Nama Calon</h3>
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <h4 class="text-xs uppercase font-bold tracking-wider text-slate-500 mb-2">{{ __('Vision') }}:</h4>
                <div id="modal-visi" class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 text-sm leading-relaxed whitespace-pre-line font-medium"></div>
            </div>

            <div>
                <h4 class="text-xs uppercase font-bold tracking-wider text-slate-500 mb-2">{{ __('Mission') }}:</h4>
                <div id="modal-misi" class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 text-sm leading-relaxed whitespace-pre-line font-medium"></div>
            </div>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-200 flex justify-end">
            <button onclick="closeDetailModal()" class="px-6 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs sm:text-sm transition cursor-pointer">
                {{ __('Close') }}
            </button>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL IMAGE LIGHTBOX PREVIEW (Zoom In)         -->
<!-- ============================================== -->
<div id="image-preview-modal" class="fixed inset-0 z-50 hidden bg-slate-900/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="boomer-modal-dialog bg-white border border-slate-200 rounded-3xl max-w-lg w-full p-5 sm:p-6 shadow-2xl relative text-center">
        <button onclick="closeImagePreview()" class="absolute top-4 right-4 text-slate-500 hover:text-slate-800 p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-base font-bold transition cursor-pointer">✕</button>

        <span id="preview-badge" class="inline-block px-3 py-1 rounded-full text-xs font-extrabold bg-blue-100 text-blue-800 border border-blue-200 mb-3">
            {{ __('Candidate Photo Preview') }}
        </span>
        <h3 id="preview-name" class="text-xl font-extrabold text-slate-900 mb-4">Nama Kandidat</h3>

        <div class="w-full max-h-[60vh] rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 mb-5 flex items-center justify-center">
            <img id="preview-img-tag" src="" alt="Candidate Preview" class="w-full h-auto max-h-[55vh] object-contain">
        </div>

        <button onclick="closeImagePreview()" class="w-full py-3 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-sm transition cursor-pointer">
            {{ __('Close') }}
        </button>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL FINAL CONFIRMATION (Boomer Friendly)     -->
<!-- ============================================== -->
<div id="confirm-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="boomer-modal-dialog bg-white border border-slate-200 rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl text-center">
        <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center mx-auto mb-4 shadow-xs">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>

        <h3 class="text-xl sm:text-2xl font-black text-slate-900 mb-1.5">{{ __('Confirm Your Vote Selection') }}</h3>
        <p class="text-xs sm:text-sm text-slate-500 mb-5 leading-relaxed font-medium">{{ __('Make sure your candidate choices are correct before submitting') }}</p>

        <div class="space-y-3 mb-6 text-left">
            <div class="p-3.5 rounded-2xl bg-blue-50 border border-blue-200 flex items-center justify-between">
                <div>
                    <span class="text-[11px] text-blue-700 uppercase tracking-wider block font-bold">{{ __('1. Chairman Choice:') }}</span>
                    <span id="confirm-ketua-name" class="text-sm font-black text-slate-900">Ir. Bambang Sutrisno</span>
                </div>
                <span id="confirm-ketua-no" class="font-black text-white text-xs bg-blue-600 px-3 py-1 rounded-xl shadow-xs">No. 1</span>
            </div>

            <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-between">
                <div>
                    <span class="text-[11px] text-emerald-800 uppercase tracking-wider block font-bold">{{ __('2. Supervisor Choice:') }}</span>
                    <span id="confirm-pengawas-name" class="text-sm font-black text-slate-900">Drs. Ahmad Fauzi</span>
                </div>
                <span id="confirm-pengawas-no" class="font-black text-white text-xs bg-emerald-600 px-3 py-1 rounded-xl shadow-xs">No. 1</span>
            </div>
        </div>

        <div class="flex gap-3">
            <button onclick="closeConfirmModal()" type="button" class="w-1/2 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm transition cursor-pointer">
                {{ __('Change Selection') }}
            </button>
            <button onclick="executeVoteSubmission()" type="button" id="btn-final-submit" class="w-1/2 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs sm:text-sm shadow-md transition cursor-pointer">
                {{ __('Yes, Submit My Vote!') }}
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let selectedKetua = null;
    let selectedPengawas = null;

    const i18n = {
        touchToSelect: "{{ __('TOUCH TO SELECT') }}",
        selectedFormat: "{{ __('SELECTED (NO. :no)') }}",
        chairmanSelectedPrefix: "{{ __('Selected: No.') }} ",
        supervisorSelectedPrefix: "{{ __('Selected: No.') }} ",
        submitting: "{{ __('Submitting...') }}"
    };

    function selectKetua(nik, nama, nomor) {
        if (window.SoundEffects) window.SoundEffects.click();
        selectedKetua = { nik, nama, nomor };
        document.getElementById('radio-ketua-' + nik).checked = true;

        // Reset semua kartu ketua
        document.querySelectorAll('.candidate-card-ketua').forEach(card => {
            card.classList.remove('border-blue-600', 'bg-blue-50/70', 'ring-4', 'ring-blue-200', 'card-selected-pop');
            card.classList.add('border-slate-200', 'bg-white');
            
            const check = card.querySelector('[id^="check-icon-ketua-"]');
            if (check) {
                check.classList.remove('bg-blue-600', 'text-white', 'border-blue-600', 'scale-110');
                check.classList.add('bg-slate-100', 'text-transparent', 'border-slate-200');
            }

            const btnSelect = card.querySelector('[id^="btn-select-ketua-"]');
            if (btnSelect) {
                btnSelect.className = 'w-full py-3 rounded-xl bg-slate-100 border border-slate-300 text-slate-700 font-extrabold text-xs sm:text-sm text-center transition-all duration-300';
                btnSelect.innerText = i18n.touchToSelect;
            }
        });

        // Set kartu yang dipilih dengan efek spring sundul pop
        const activeCard = document.getElementById('card-ketua-' + nik);
        activeCard.classList.remove('border-slate-200', 'bg-white');
        activeCard.classList.add('border-blue-600', 'bg-blue-50/70', 'ring-4', 'ring-blue-200', 'card-selected-pop');

        const activeCheck = document.getElementById('check-icon-ketua-' + nik);
        activeCheck.classList.remove('bg-slate-100', 'text-transparent', 'border-slate-200');
        activeCheck.classList.add('bg-blue-600', 'text-white', 'border-blue-600', 'scale-110');

        const activeBtn = document.getElementById('btn-select-ketua-' + nik);
        activeBtn.className = 'w-full py-3 rounded-xl bg-blue-600 border border-blue-600 text-white font-black text-xs sm:text-sm text-center shadow-sm transition-all duration-300';
        activeBtn.innerText = '✓ ' + i18n.selectedFormat.replace(':no', nomor);

        // Update badge kategori atas
        const badge = document.getElementById('ketua-status-badge');
        badge.className = 'inline-flex self-start sm:self-auto px-3.5 py-1.5 rounded-full text-xs font-bold bg-blue-100 text-blue-900 border border-blue-200';
        badge.innerText = '✓ ' + i18n.chairmanSelectedPrefix + nomor;

        // Update summary bawah
        const summary = document.getElementById('summary-ketua');
        summary.className = 'text-blue-700 font-extrabold text-xs sm:text-sm';
        summary.innerText = 'No. ' + nomor + ' - ' + nama;

        checkReadiness();
    }

    function selectPengawas(nik, nama, nomor) {
        if (window.SoundEffects) window.SoundEffects.click();
        selectedPengawas = { nik, nama, nomor };
        document.getElementById('radio-pengawas-' + nik).checked = true;

        // Reset semua kartu pengawas
        document.querySelectorAll('.candidate-card-pengawas').forEach(card => {
            card.classList.remove('border-emerald-600', 'bg-emerald-50/70', 'ring-4', 'ring-emerald-200', 'card-selected-pop');
            card.classList.add('border-slate-200', 'bg-white');
            
            const check = card.querySelector('[id^="check-icon-pengawas-"]');
            if (check) {
                check.classList.remove('bg-emerald-600', 'text-white', 'border-emerald-600', 'scale-110');
                check.classList.add('bg-slate-100', 'text-transparent', 'border-slate-200');
            }

            const btnSelect = card.querySelector('[id^="btn-select-pengawas-"]');
            if (btnSelect) {
                btnSelect.className = 'w-full py-3 rounded-xl bg-slate-100 border border-slate-300 text-slate-700 font-extrabold text-xs sm:text-sm text-center transition-all duration-300';
                btnSelect.innerText = i18n.touchToSelect;
            }
        });

        // Set kartu yang dipilih dengan efek spring sundul pop
        const activeCard = document.getElementById('card-pengawas-' + nik);
        activeCard.classList.remove('border-slate-200', 'bg-white');
        activeCard.classList.add('border-emerald-600', 'bg-emerald-50/70', 'ring-4', 'ring-emerald-200', 'card-selected-pop');

        const activeCheck = document.getElementById('check-icon-pengawas-' + nik);
        activeCheck.classList.remove('bg-slate-100', 'text-transparent', 'border-slate-200');
        activeCheck.classList.add('bg-emerald-600', 'text-white', 'border-emerald-600', 'scale-110');

        const activeBtn = document.getElementById('btn-select-pengawas-' + nik);
        activeBtn.className = 'w-full py-3 rounded-xl bg-emerald-600 border border-emerald-600 text-white font-black text-xs sm:text-sm text-center shadow-sm transition-all duration-300';
        activeBtn.innerText = '✓ ' + i18n.selectedFormat.replace(':no', nomor);

        // Update badge kategori atas
        const badge = document.getElementById('pengawas-status-badge');
        badge.className = 'inline-flex self-start sm:self-auto px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-900 border border-emerald-200';
        badge.innerText = '✓ ' + i18n.supervisorSelectedPrefix + nomor;

        // Update summary bawah
        const summary = document.getElementById('summary-pengawas');
        summary.className = 'text-emerald-700 font-extrabold text-xs sm:text-sm';
        summary.innerText = 'No. ' + nomor + ' - ' + nama;

        checkReadiness();
    }

    function checkReadiness() {
        const btn = document.getElementById('btn-confirm-trigger');
        if (selectedKetua && selectedPengawas) {
            btn.removeAttribute('disabled');
            btn.classList.add('animate-pulse');
        } else {
            btn.setAttribute('disabled', 'disabled');
            btn.classList.remove('animate-pulse');
        }
    }

    function openConfirmModal() {
        if (!selectedKetua || !selectedPengawas) return;
        if (window.SoundEffects) window.SoundEffects.modal();

        document.getElementById('confirm-ketua-name').innerText = selectedKetua.nama;
        document.getElementById('confirm-ketua-no').innerText = 'No. ' + selectedKetua.nomor;
        document.getElementById('confirm-pengawas-name').innerText = selectedPengawas.nama;
        document.getElementById('confirm-pengawas-no').innerText = 'No. ' + selectedPengawas.nomor;

        document.getElementById('confirm-modal').classList.remove('hidden');
    }

    function closeConfirmModal() {
        if (window.SoundEffects) window.SoundEffects.click();
        document.getElementById('confirm-modal').classList.add('hidden');
    }

    function executeVoteSubmission() {
        if (window.SoundEffects) window.SoundEffects.success();
        const btn = document.getElementById('btn-final-submit');
        btn.innerText = i18n.submitting;
        btn.setAttribute('disabled', 'disabled');
        document.getElementById('voting-form').submit();
    }

    // Modal Visi & Misi
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

    // Modal Image Lightbox Preview
    function previewCandidatePhoto(fotoUrl, nama, nomor, kategori) {
        if (window.SoundEffects) window.SoundEffects.modal();
        document.getElementById('preview-name').innerText = 'No. ' + nomor + ' - ' + nama;
        document.getElementById('preview-badge').innerText = kategori;
        document.getElementById('preview-img-tag').src = fotoUrl;

        document.getElementById('image-preview-modal').classList.remove('hidden');
    }

    function closeImagePreview() {
        if (window.SoundEffects) window.SoundEffects.click();
        document.getElementById('image-preview-modal').classList.add('hidden');
    }

    // Click outside to close modals
    window.addEventListener('click', function(e) {
        const detailModal = document.getElementById('detail-modal');
        const confirmModal = document.getElementById('confirm-modal');
        const previewModal = document.getElementById('image-preview-modal');

        if (e.target === detailModal) closeDetailModal();
        if (e.target === confirmModal) closeConfirmModal();
        if (e.target === previewModal) closeImagePreview();
    });
</script>
@endpush
@endsection
