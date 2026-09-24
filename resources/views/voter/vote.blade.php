@extends('layouts.app')

@section('title', 'Bilik Suara - Pemilihan Koperasi')

@section('content')
<div class="flex-1 flex flex-col p-4 sm:p-6 max-w-6xl mx-auto w-full pb-36">

    <!-- Header Identitas Pemilih (Proporsional & Nyaman di Tablet) -->
    <header class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div class="flex items-center space-x-3">
            <div class="w-11 h-11 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-lg shadow-2xs shrink-0">
                {{ strtoupper(substr($pemilih->nama, 0, 2)) }}
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">{{ $pemilih->nama }}</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                        Hak Suara Aktif
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 font-medium">
                    NIK: <span class="font-bold text-slate-700">{{ $pemilih->nik }}</span> • Bagian: <span class="font-bold text-slate-700">{{ $pemilih->dept }}</span>
                </p>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <div class="inline-flex rounded-lg border border-slate-200 bg-white p-0.5 text-xs font-bold shadow-2xs">
                <a href="{{ route('lang.switch', 'en') }}" class="px-2 py-1 rounded-md transition {{ app()->getLocale() === 'en' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-blue-600' }}">EN</a>
                <a href="{{ route('lang.switch', 'id') }}" class="px-2 py-1 rounded-md transition {{ app()->getLocale() === 'id' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-blue-600' }}">ID</a>
            </div>

            <form action="{{ route('voter.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full sm:w-auto px-4 py-2 rounded-xl bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-rose-700 border border-slate-200 hover:border-rose-300 text-xs font-bold transition flex items-center justify-center space-x-1.5">
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
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 mb-5 border-b border-slate-200 gap-2">
                <div class="flex items-center space-x-3">
                    <span class="w-8 h-8 rounded-lg bg-blue-600 text-white font-extrabold flex items-center justify-center text-sm shadow-2xs shrink-0">1</span>
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">Pilih 1 (Satu) Calon Ketua Koperasi</h3>
                        <p class="text-xs sm:text-sm text-slate-500">Sentuh salah satu kotak calon untuk menentukan pilihan.</p>
                    </div>
                </div>
                <div id="ketua-status-badge" class="inline-flex self-start sm:self-auto px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                    Belum Memilih Ketua
                </div>
            </div>

            <!-- Grid Responsif untuk Tablet: 2 Kolom di Tablet, 3 Kolom di Layar Lebar -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($kandidatKetua as $ketua)
                    <div 
                        id="card-ketua-{{ $ketua->nik }}"
                        onclick="selectKetua('{{ $ketua->nik }}', '{{ addslashes($ketua->nama) }}', '{{ $ketua->nomor_urut }}')"
                        class="candidate-card-ketua kiosk-card relative rounded-2xl bg-white border-2 border-slate-200 hover:border-blue-400 p-4 sm:p-5 flex flex-col justify-between cursor-pointer shadow-2xs transition"
                    >
                        <input type="radio" name="ketua_nik" value="{{ $ketua->nik }}" id="radio-ketua-{{ $ketua->nik }}" class="hidden">

                        <div>
                            <!-- Header Nomor Urut & Check Icon -->
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-2">
                                    <span class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center text-base font-black shadow-2xs">
                                        {{ $ketua->nomor_urut }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">No. Urut {{ $ketua->nomor_urut }}</span>
                                </div>
                                <div id="check-icon-ketua-{{ $ketua->nik }}" class="w-7 h-7 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-transparent transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            </div>

                            <!-- Foto Kandidat Terang -->
                            <div class="w-full h-48 sm:h-52 rounded-xl overflow-hidden bg-slate-100 mb-3 border border-slate-200">
                                <img 
                                    src="{{ $ketua->foto ?: 'https://ui-avatars.com/api/?name='.urlencode($ketua->nama).'&background=2563eb&color=ffffff&size=400' }}" 
                                    alt="{{ $ketua->nama }}" 
                                    class="w-full h-full object-cover object-top"
                                >
                            </div>

                            <h4 class="text-base sm:text-lg font-bold text-slate-900 mb-1 leading-snug">{{ $ketua->nama }}</h4>
                            <p class="text-xs text-slate-500 line-clamp-2 mb-3">{{ $ketua->deskripsi ?: 'Calon resmi Pemilihan Ketua Koperasi.' }}</p>
                        </div>

                        <div>
                            <!-- Tombol Visi Misi -->
                            <div class="mb-3">
                                <button 
                                    type="button" 
                                    onclick="event.stopPropagation(); showDetailModal('Calon Ketua Koperasi', '{{ addslashes($ketua->nama) }}', '{{ $ketua->nomor_urut }}', `{{ addslashes($ketua->visi) }}`, `{{ addslashes($ketua->misi) }}`, '{{ $ketua->foto }}')"
                                    class="w-full py-1.5 px-3 rounded-lg bg-slate-100 hover:bg-blue-50 text-blue-700 font-bold text-xs border border-slate-200 transition text-center"
                                >
                                    📄 Baca Visi & Misi
                                </button>
                            </div>

                            <!-- Banner Status Pemilihan -->
                            <div id="btn-select-ketua-{{ $ketua->nik }}" class="w-full py-2.5 rounded-xl bg-slate-100 border border-slate-300 text-slate-700 font-bold text-xs sm:text-sm text-center transition">
                                SENTUH UNTUK MEMILIH
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
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 mb-5 border-b border-slate-200 gap-2">
                <div class="flex items-center space-x-3">
                    <span class="w-8 h-8 rounded-lg bg-emerald-600 text-white font-extrabold flex items-center justify-center text-sm shadow-2xs shrink-0">2</span>
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">Pilih 1 (Satu) Calon Pengawas Koperasi</h3>
                        <p class="text-xs sm:text-sm text-slate-500">Sentuh salah satu kotak calon untuk menentukan pilihan.</p>
                    </div>
                </div>
                <div id="pengawas-status-badge" class="inline-flex self-start sm:self-auto px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                    Belum Memilih Pengawas
                </div>
            </div>

            <!-- Grid Responsif untuk Tablet: 2 Kolom di Tablet, 3 Kolom di Layar Lebar -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($kandidatPengawas as $pengawas)
                    <div 
                        id="card-pengawas-{{ $pengawas->nik }}"
                        onclick="selectPengawas('{{ $pengawas->nik }}', '{{ addslashes($pengawas->nama) }}', '{{ $pengawas->nomor_urut }}')"
                        class="candidate-card-pengawas kiosk-card relative rounded-2xl bg-white border-2 border-slate-200 hover:border-emerald-400 p-4 sm:p-5 flex flex-col justify-between cursor-pointer shadow-2xs transition"
                    >
                        <input type="radio" name="pengawas_nik" value="{{ $pengawas->nik }}" id="radio-pengawas-{{ $pengawas->nik }}" class="hidden">

                        <div>
                            <!-- Header Nomor Urut & Check Icon -->
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-2">
                                    <span class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-base font-black shadow-2xs">
                                        {{ $pengawas->nomor_urut }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">No. Urut {{ $pengawas->nomor_urut }}</span>
                                </div>
                                <div id="check-icon-pengawas-{{ $pengawas->nik }}" class="w-7 h-7 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-transparent transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            </div>

                            <!-- Foto Kandidat Terang -->
                            <div class="w-full h-48 sm:h-52 rounded-xl overflow-hidden bg-slate-100 mb-3 border border-slate-200">
                                <img 
                                    src="{{ $pengawas->foto ?: 'https://ui-avatars.com/api/?name='.urlencode($pengawas->nama).'&background=059669&color=ffffff&size=400' }}" 
                                    alt="{{ $pengawas->nama }}" 
                                    class="w-full h-full object-cover object-top"
                                >
                            </div>

                            <h4 class="text-base sm:text-lg font-bold text-slate-900 mb-1 leading-snug">{{ $pengawas->nama }}</h4>
                            <p class="text-xs text-slate-500 line-clamp-2 mb-3">{{ $pengawas->deskripsi ?: 'Calon resmi Pemilihan Pengawas Koperasi.' }}</p>
                        </div>

                        <div>
                            <!-- Tombol Visi Misi -->
                            <div class="mb-3">
                                <button 
                                    type="button" 
                                    onclick="event.stopPropagation(); showDetailModal('Calon Pengawas Koperasi', '{{ addslashes($pengawas->nama) }}', '{{ $pengawas->nomor_urut }}', `{{ addslashes($pengawas->visi) }}`, `{{ addslashes($pengawas->misi) }}`, '{{ $pengawas->foto }}')"
                                    class="w-full py-1.5 px-3 rounded-lg bg-slate-100 hover:bg-emerald-50 text-emerald-800 font-bold text-xs border border-slate-200 transition text-center"
                                >
                                    📄 Baca Visi & Misi
                                </button>
                            </div>

                            <!-- Banner Status Pemilihan -->
                            <div id="btn-select-pengawas-{{ $pengawas->nik }}" class="w-full py-2.5 rounded-xl bg-slate-100 border border-slate-300 text-slate-700 font-bold text-xs sm:text-sm text-center transition">
                                SENTUH UNTUK MEMILIH
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </form>
</div>

<!-- ============================================== -->
<!-- FLOATING ACTION BAR                            -->
<!-- ============================================== -->
<div class="fixed bottom-0 inset-x-0 bg-white/95 border-t border-slate-200 shadow-xl p-3 sm:p-4 z-40 backdrop-blur-xs">
    <div class="max-w-5xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3">
        
        <!-- Status Pilihan Pemilih -->
        <div class="flex items-center gap-4 text-xs w-full sm:w-auto justify-between sm:justify-start">
            <div class="flex items-center space-x-2">
                <span class="w-6 h-6 rounded-md bg-blue-600 text-white font-bold text-xs flex items-center justify-center">1</span>
                <div>
                    <span class="text-[11px] text-slate-500 block">Calon Ketua:</span>
                    <strong id="summary-ketua" class="text-rose-600 font-bold text-xs sm:text-sm">(Belum dipilih)</strong>
                </div>
            </div>

            <div class="h-8 w-px bg-slate-200"></div>

            <div class="flex items-center space-x-2">
                <span class="w-6 h-6 rounded-md bg-emerald-600 text-white font-bold text-xs flex items-center justify-center">2</span>
                <div>
                    <span class="text-[11px] text-slate-500 block">Calon Pengawas:</span>
                    <strong id="summary-pengawas" class="text-rose-600 font-bold text-xs sm:text-sm">(Belum dipilih)</strong>
                </div>
            </div>
        </div>

        <!-- Tombol Lanjut / Kirim Suara -->
        <button 
            type="button" 
            id="btn-confirm-trigger"
            disabled
            onclick="openConfirmModal()"
            class="w-full sm:w-auto px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm sm:text-base shadow-sm disabled:opacity-40 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:text-slate-500 transition cursor-pointer flex items-center justify-center space-x-2"
        >
            <span>KIRIM SUARA</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </button>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL DETAIL VISI & MISI                       -->
<!-- ============================================== -->
<div id="detail-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl max-w-xl w-full p-5 sm:p-6 shadow-2xl relative max-h-[85vh] overflow-y-auto">
        <button onclick="closeDetailModal()" class="absolute top-4 right-4 text-slate-500 hover:text-slate-800 p-2 rounded-lg bg-slate-100 text-lg font-bold">✕</button>

        <div class="flex items-center space-x-3 mb-5 pb-3 border-b border-slate-200">
            <span id="modal-nomor" class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center text-lg font-black">01</span>
            <div>
                <span id="modal-kategori" class="text-[11px] uppercase font-bold tracking-wider text-blue-700 block">Calon Ketua</span>
                <h3 id="modal-nama" class="text-lg font-bold text-slate-900">Nama Calon</h3>
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <h4 class="text-xs uppercase font-bold tracking-wider text-slate-500 mb-1.5">Visi:</h4>
                <div id="modal-visi" class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 text-xs sm:text-sm leading-relaxed whitespace-pre-line font-medium"></div>
            </div>

            <div>
                <h4 class="text-xs uppercase font-bold tracking-wider text-slate-500 mb-1.5">Misi:</h4>
                <div id="modal-misi" class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 text-xs sm:text-sm leading-relaxed whitespace-pre-line font-medium"></div>
            </div>
        </div>

        <div class="mt-6 pt-3 border-t border-slate-200 flex justify-end">
            <button onclick="closeDetailModal()" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL FINAL CONFIRMATION                       -->
<!-- ============================================== -->
<div id="confirm-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl max-w-md w-full p-6 shadow-2xl text-center">
        <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center mx-auto mb-3">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>

        <h3 class="text-xl font-bold text-slate-900 mb-1">Konfirmasi Pilihan Anda</h3>
        <p class="text-xs sm:text-sm text-slate-500 mb-5">Pastikan calon pilihan Anda sudah sesuai sebelum mengirim suara.</p>

        <div class="space-y-3 mb-6 text-left">
            <div class="p-3 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-between">
                <div>
                    <span class="text-[10px] text-blue-700 uppercase tracking-wider block font-bold">1. Pilihan Ketua:</span>
                    <span id="confirm-ketua-name" class="text-sm font-bold text-slate-900">Ir. Bambang Sutrisno</span>
                </div>
                <span id="confirm-ketua-no" class="font-bold text-white text-xs bg-blue-600 px-2.5 py-1 rounded-lg">No. 1</span>
            </div>

            <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-between">
                <div>
                    <span class="text-[10px] text-emerald-800 uppercase tracking-wider block font-bold">2. Pilihan Pengawas:</span>
                    <span id="confirm-pengawas-name" class="text-sm font-bold text-slate-900">Drs. Ahmad Fauzi</span>
                </div>
                <span id="confirm-pengawas-no" class="font-bold text-white text-xs bg-emerald-600 px-2.5 py-1 rounded-lg">No. 1</span>
            </div>
        </div>

        <div class="flex gap-2.5">
            <button onclick="closeConfirmModal()" type="button" class="w-1/2 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm transition">
                Ubah Pilihan
            </button>
            <button onclick="executeVoteSubmission()" type="button" id="btn-final-submit" class="w-1/2 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm shadow transition">
                Ya, Kirim Suara!
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let selectedKetua = null;
    let selectedPengawas = null;

    function selectKetua(nik, nama, nomor) {
        if (window.SoundEffects) window.SoundEffects.click();
        selectedKetua = { nik, nama, nomor };
        document.getElementById('radio-ketua-' + nik).checked = true;

        // Reset semua kartu ketua
        document.querySelectorAll('.candidate-card-ketua').forEach(card => {
            card.classList.remove('border-blue-600', 'bg-blue-50/70', 'ring-2', 'ring-blue-200');
            card.classList.add('border-slate-200', 'bg-white');
            
            const check = card.querySelector('[id^="check-icon-ketua-"]');
            if (check) {
                check.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                check.classList.add('bg-slate-100', 'text-transparent', 'border-slate-200');
            }

            const btnSelect = card.querySelector('[id^="btn-select-ketua-"]');
            if (btnSelect) {
                btnSelect.className = 'w-full py-2.5 rounded-xl bg-slate-100 border border-slate-300 text-slate-700 font-bold text-xs sm:text-sm text-center transition';
                btnSelect.innerText = 'SENTUH UNTUK MEMILIH';
            }
        });

        // Set kartu yang dipilih
        const activeCard = document.getElementById('card-ketua-' + nik);
        activeCard.classList.remove('border-slate-200', 'bg-white');
        activeCard.classList.add('border-blue-600', 'bg-blue-50/70', 'ring-2', 'ring-blue-200');

        const activeCheck = document.getElementById('check-icon-ketua-' + nik);
        activeCheck.classList.remove('bg-slate-100', 'text-transparent', 'border-slate-200');
        activeCheck.classList.add('bg-blue-600', 'text-white', 'border-blue-600');

        const activeBtn = document.getElementById('btn-select-ketua-' + nik);
        activeBtn.className = 'w-full py-2.5 rounded-xl bg-blue-600 border border-blue-600 text-white font-bold text-xs sm:text-sm text-center shadow-2xs transition';
        activeBtn.innerText = '✓ TERPILIH (NOMOR ' + nomor + ')';

        // Update badge kategori atas
        const badge = document.getElementById('ketua-status-badge');
        badge.className = 'inline-flex self-start sm:self-auto px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-900 border border-blue-200';
        badge.innerText = '✓ Dipilih: No. ' + nomor;

        // Update summary bawah
        const summary = document.getElementById('summary-ketua');
        summary.className = 'text-blue-700 font-bold text-xs sm:text-sm';
        summary.innerText = 'No. ' + nomor + ' - ' + nama;

        checkReadiness();
    }

    function selectPengawas(nik, nama, nomor) {
        if (window.SoundEffects) window.SoundEffects.click();
        selectedPengawas = { nik, nama, nomor };
        document.getElementById('radio-pengawas-' + nik).checked = true;

        // Reset semua kartu pengawas
        document.querySelectorAll('.candidate-card-pengawas').forEach(card => {
            card.classList.remove('border-emerald-600', 'bg-emerald-50/70', 'ring-2', 'ring-emerald-200');
            card.classList.add('border-slate-200', 'bg-white');
            
            const check = card.querySelector('[id^="check-icon-pengawas-"]');
            if (check) {
                check.classList.remove('bg-emerald-600', 'text-white', 'border-emerald-600');
                check.classList.add('bg-slate-100', 'text-transparent', 'border-slate-200');
            }

            const btnSelect = card.querySelector('[id^="btn-select-pengawas-"]');
            if (btnSelect) {
                btnSelect.className = 'w-full py-2.5 rounded-xl bg-slate-100 border border-slate-300 text-slate-700 font-bold text-xs sm:text-sm text-center transition';
                btnSelect.innerText = 'SENTUH UNTUK MEMILIH';
            }
        });

        // Set kartu yang dipilih
        const activeCard = document.getElementById('card-pengawas-' + nik);
        activeCard.classList.remove('border-slate-200', 'bg-white');
        activeCard.classList.add('border-emerald-600', 'bg-emerald-50/70', 'ring-2', 'ring-emerald-200');

        const activeCheck = document.getElementById('check-icon-pengawas-' + nik);
        activeCheck.classList.remove('bg-slate-100', 'text-transparent', 'border-slate-200');
        activeCheck.classList.add('bg-emerald-600', 'text-white', 'border-emerald-600');

        const activeBtn = document.getElementById('btn-select-pengawas-' + nik);
        activeBtn.className = 'w-full py-2.5 rounded-xl bg-emerald-600 border border-emerald-600 text-white font-bold text-xs sm:text-sm text-center shadow-2xs transition';
        activeBtn.innerText = '✓ TERPILIH (NOMOR ' + nomor + ')';

        // Update badge kategori atas
        const badge = document.getElementById('pengawas-status-badge');
        badge.className = 'inline-flex self-start sm:self-auto px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-900 border border-emerald-200';
        badge.innerText = '✓ Dipilih: No. ' + nomor;

        // Update summary bawah
        const summary = document.getElementById('summary-pengawas');
        summary.className = 'text-emerald-700 font-bold text-xs sm:text-sm';
        summary.innerText = 'No. ' + nomor + ' - ' + nama;

        checkReadiness();
    }

    function checkReadiness() {
        const btn = document.getElementById('btn-confirm-trigger');
        if (selectedKetua && selectedPengawas) {
            btn.removeAttribute('disabled');
        } else {
            btn.setAttribute('disabled', 'disabled');
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
        btn.innerText = 'Menyimpan...';
        btn.setAttribute('disabled', 'disabled');
        document.getElementById('voting-form').submit();
    }

    // Modal Visi & Misi
    function showDetailModal(kategori, nama, nomor, visi, misi, foto) {
        if (window.SoundEffects) window.SoundEffects.modal();
        document.getElementById('modal-kategori').innerText = kategori;
        document.getElementById('modal-nama').innerText = nama;
        document.getElementById('modal-nomor').innerText = nomor;
        document.getElementById('modal-visi').innerText = visi;
        document.getElementById('modal-misi').innerText = misi;

        document.getElementById('detail-modal').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('detail-modal').classList.add('hidden');
    }
</script>
@endpush
@endsection
