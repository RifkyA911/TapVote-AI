@extends('layouts.app')

@section('title', 'Bilik Suara - Pemilihan Ketua & Pengawas Koperasi')

@section('content')
<div class="flex-1 flex flex-col p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full pb-36">

    <!-- Top Identity Header -->
    <header class="p-4 sm:p-5 rounded-2xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-blue-600/20 border border-blue-500/30 text-blue-400 flex items-center justify-center font-bold text-lg">
                {{ strtoupper(substr($pemilih->nama, 0, 2)) }}
            </div>
            <div>
                <div class="flex items-center space-x-2">
                    <h2 class="text-lg font-bold text-white">{{ $pemilih->nama }}</h2>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-300">Hak Suara Terverifikasi</span>
                </div>
                <p class="text-xs text-slate-400">NIK: <span class="font-mono text-slate-300">{{ $pemilih->nik }}</span> • Dept: <span class="text-slate-300">{{ $pemilih->dept }}</span></p>
            </div>
        </div>

        <form action="{{ route('voter.logout') }}" method="POST">
            @csrf
            <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-slate-800 hover:bg-rose-900/40 text-slate-400 hover:text-rose-300 border border-slate-700 hover:border-rose-500/40 text-xs font-semibold transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span>Batal & Keluar</span>
            </button>
        </form>
    </header>

    <form id="voting-form" action="{{ route('voter.vote.store') }}" method="POST">
        @csrf

        <!-- ============================================== -->
        <!-- KATEGORI 1: PEMILIHAN KETUA KOPERASI           -->
        <!-- ============================================== -->
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-800">
                <div class="flex items-center space-x-3">
                    <span class="w-8 h-8 rounded-lg bg-blue-600 text-white font-extrabold flex items-center justify-center text-sm shadow-md shadow-blue-500/30">1</span>
                    <div>
                        <h3 class="text-xl font-extrabold text-white">Pilih 1 (Satu) Kandidat Ketua Koperasi</h3>
                        <p class="text-xs text-slate-400">Klik salah satu kartu kandidat untuk menentukan pilihan Anda.</p>
                    </div>
                </div>
                <span id="ketua-status-badge" class="px-3 py-1 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/30">
                    Belum Dipilih
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($kandidatKetua as $ketua)
                    <div 
                        id="card-ketua-{{ $ketua->nik }}"
                        onclick="selectKetua('{{ $ketua->nik }}', '{{ addslashes($ketua->nama) }}', '{{ $ketua->nomor_urut }}')"
                        class="candidate-card-ketua fluid-card relative rounded-3xl bg-slate-900/70 border-2 border-slate-800 hover:border-blue-500/60 p-6 flex flex-col justify-between cursor-pointer transition-all duration-300"
                    >
                        <!-- Radio Hidden Input -->
                        <input type="radio" name="ketua_nik" value="{{ $ketua->nik }}" id="radio-ketua-{{ $ketua->nik }}" class="hidden">

                        <div>
                            <!-- Badge Nomor Urut -->
                            <div class="flex items-center justify-between mb-4">
                                <span class="w-10 h-10 rounded-2xl bg-blue-600/20 text-blue-400 border border-blue-500/40 flex items-center justify-center text-lg font-black font-mono shadow-sm">
                                    {{ str_pad($ketua->nomor_urut, 2, '0', STR_PAD_LEFT) }}
                                </span>
                                <div id="check-icon-ketua-{{ $ketua->nik }}" class="w-7 h-7 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-transparent transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            </div>

                            <!-- Foto Kandidat -->
                            <div class="w-full h-52 rounded-2xl overflow-hidden bg-slate-950 mb-4 border border-slate-800 relative group">
                                <img 
                                    src="{{ $ketua->foto ?: 'https://ui-avatars.com/api/?name='.urlencode($ketua->nama).'&background=1e293b&color=3b82f6&size=400' }}" 
                                    alt="{{ $ketua->nama }}" 
                                    class="w-full h-full object-cover object-top group-hover:scale-105 transition duration-500"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                            </div>

                            <h4 class="text-lg font-bold text-white mb-1 leading-snug">{{ $ketua->nama }}</h4>
                            <p class="text-xs text-slate-400 line-clamp-2 mb-4">{{ $ketua->deskripsi ?: 'Kandidat resmi Pemilihan Ketua Koperasi.' }}</p>
                        </div>

                        <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between">
                            <button 
                                type="button" 
                                onclick="event.stopPropagation(); showDetailModal('Ketua Koperasi', '{{ addslashes($ketua->nama) }}', '{{ $ketua->nomor_urut }}', `{{ addslashes($ketua->visi) }}`, `{{ addslashes($ketua->misi) }}`, '{{ $ketua->foto }}')"
                                class="text-xs font-semibold text-blue-400 hover:text-blue-300 underline underline-offset-4"
                            >
                                Baca Visi & Misi →
                            </button>
                            <span class="text-[11px] font-bold text-slate-400 select-badge">PILIH</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>


        <!-- ============================================== -->
        <!-- KATEGORI 2: PEMILIHAN PENGAWAS KOPERASI        -->
        <!-- ============================================== -->
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-800">
                <div class="flex items-center space-x-3">
                    <span class="w-8 h-8 rounded-lg bg-emerald-600 text-white font-extrabold flex items-center justify-center text-sm shadow-md shadow-emerald-500/30">2</span>
                    <div>
                        <h3 class="text-xl font-extrabold text-white">Pilih 1 (Satu) Kandidat Pengawas Koperasi</h3>
                        <p class="text-xs text-slate-400">Klik salah satu kartu kandidat untuk menentukan pilihan Anda.</p>
                    </div>
                </div>
                <span id="pengawas-status-badge" class="px-3 py-1 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/30">
                    Belum Dipilih
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($kandidatPengawas as $pengawas)
                    <div 
                        id="card-pengawas-{{ $pengawas->nik }}"
                        onclick="selectPengawas('{{ $pengawas->nik }}', '{{ addslashes($pengawas->nama) }}', '{{ $pengawas->nomor_urut }}')"
                        class="candidate-card-pengawas fluid-card relative rounded-3xl bg-slate-900/70 border-2 border-slate-800 hover:border-emerald-500/60 p-6 flex flex-col justify-between cursor-pointer transition-all duration-300"
                    >
                        <!-- Radio Hidden Input -->
                        <input type="radio" name="pengawas_nik" value="{{ $pengawas->nik }}" id="radio-pengawas-{{ $pengawas->nik }}" class="hidden">

                        <div>
                            <!-- Badge Nomor Urut -->
                            <div class="flex items-center justify-between mb-4">
                                <span class="w-10 h-10 rounded-2xl bg-emerald-600/20 text-emerald-400 border border-emerald-500/40 flex items-center justify-center text-lg font-black font-mono shadow-sm">
                                    {{ str_pad($pengawas->nomor_urut, 2, '0', STR_PAD_LEFT) }}
                                </span>
                                <div id="check-icon-pengawas-{{ $pengawas->nik }}" class="w-7 h-7 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-transparent transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            </div>

                            <!-- Foto Kandidat -->
                            <div class="w-full h-52 rounded-2xl overflow-hidden bg-slate-950 mb-4 border border-slate-800 relative group">
                                <img 
                                    src="{{ $pengawas->foto ?: 'https://ui-avatars.com/api/?name='.urlencode($pengawas->nama).'&background=1e293b&color=10b981&size=400' }}" 
                                    alt="{{ $pengawas->nama }}" 
                                    class="w-full h-full object-cover object-top group-hover:scale-105 transition duration-500"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                            </div>

                            <h4 class="text-lg font-bold text-white mb-1 leading-snug">{{ $pengawas->nama }}</h4>
                            <p class="text-xs text-slate-400 line-clamp-2 mb-4">{{ $pengawas->deskripsi ?: 'Kandidat resmi Pemilihan Pengawas Koperasi.' }}</p>
                        </div>

                        <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between">
                            <button 
                                type="button" 
                                onclick="event.stopPropagation(); showDetailModal('Pengawas Koperasi', '{{ addslashes($pengawas->nama) }}', '{{ $pengawas->nomor_urut }}', `{{ addslashes($pengawas->visi) }}`, `{{ addslashes($pengawas->misi) }}`, '{{ $pengawas->foto }}')"
                                class="text-xs font-semibold text-emerald-400 hover:text-emerald-300 underline underline-offset-4"
                            >
                                Baca Visi & Misi →
                            </button>
                            <span class="text-[11px] font-bold text-slate-400 select-badge">PILIH</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </form>
</div>

<!-- ============================================== -->
<!-- FLOATING SUBMISSION & SUMMARY ACTION BAR       -->
<!-- ============================================== -->
<div class="fixed bottom-0 inset-x-0 bg-slate-900/95 border-t border-slate-800 backdrop-blur-2xl p-4 z-40 shadow-2xl">
    <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center space-x-6 text-xs w-full sm:w-auto justify-between sm:justify-start">
            <div>
                <span class="text-slate-400 block text-[11px]">Pilihan Ketua:</span>
                <strong id="summary-ketua" class="text-rose-400 font-bold text-sm">(Belum dipilih)</strong>
            </div>
            <div class="h-8 w-px bg-slate-800"></div>
            <div>
                <span class="text-slate-400 block text-[11px]">Pilihan Pengawas:</span>
                <strong id="summary-pengawas" class="text-rose-400 font-bold text-sm">(Belum dipilih)</strong>
            </div>
        </div>

        <button 
            type="button" 
            id="btn-confirm-trigger"
            disabled
            onclick="openConfirmModal()"
            class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-emerald-600 text-white font-extrabold text-sm shadow-xl disabled:opacity-40 disabled:cursor-not-allowed transition transform hover:-translate-y-0.5"
        >
            Kirim Suara Pemilihan →
        </button>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL DETAIL VISI & MISI                       -->
<!-- ============================================== -->
<div id="detail-modal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button onclick="closeDetailModal()" class="absolute top-5 right-5 text-slate-400 hover:text-white p-2 rounded-xl bg-slate-800">✕</button>

        <div class="flex items-center space-x-4 mb-6 pb-4 border-b border-slate-800">
            <span id="modal-nomor" class="w-12 h-12 rounded-2xl bg-blue-600/20 text-blue-400 border border-blue-500/40 flex items-center justify-center text-xl font-mono font-bold">01</span>
            <div>
                <span id="modal-kategori" class="text-xs uppercase font-extrabold tracking-wider text-blue-400">Calon Ketua</span>
                <h3 id="modal-nama" class="text-xl font-bold text-white">Nama Calon</h3>
            </div>
        </div>

        <div class="space-y-6">
            <div>
                <h4 class="text-xs uppercase font-extrabold tracking-wider text-slate-400 mb-2">Visi Kandidat:</h4>
                <div id="modal-visi" class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800 text-slate-200 text-sm leading-relaxed whitespace-pre-line font-normal"></div>
            </div>

            <div>
                <h4 class="text-xs uppercase font-extrabold tracking-wider text-slate-400 mb-2">Misi Kandidat:</h4>
                <div id="modal-misi" class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800 text-slate-200 text-sm leading-relaxed whitespace-pre-line font-normal"></div>
            </div>
        </div>

        <div class="mt-8 pt-4 border-t border-slate-800 flex justify-end">
            <button onclick="closeDetailModal()" class="px-6 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs transition">Tutup Dialog</button>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL FINAL CONFIRMATION                       -->
<!-- ============================================== -->
<div id="confirm-modal" class="fixed inset-0 z-50 hidden bg-slate-950/85 backdrop-blur-md flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl text-center">
        <div class="w-16 h-16 rounded-full bg-blue-600/20 text-blue-400 border border-blue-500/30 flex items-center justify-center mx-auto mb-4 animate-bounce">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>

        <h3 class="text-xl font-bold text-white mb-2">Konfirmasi Pilihan Suara</h3>
        <p class="text-xs text-slate-400 mb-6">Pastikan pilihan Anda telah sesuai. Hak suara Anda hanya dapat digunakan 1 kali.</p>

        <div class="space-y-3 mb-6 text-left">
            <div class="p-3.5 rounded-2xl bg-slate-950/60 border border-slate-800 flex items-center justify-between">
                <div>
                    <span class="text-[10px] text-slate-400 uppercase tracking-wider block font-bold">Kandidat Ketua:</span>
                    <span id="confirm-ketua-name" class="text-sm font-bold text-blue-400">Ir. Bambang Sutrisno</span>
                </div>
                <span id="confirm-ketua-no" class="font-mono font-bold text-slate-300 text-sm bg-slate-800 px-2.5 py-1 rounded-lg">No. 01</span>
            </div>

            <div class="p-3.5 rounded-2xl bg-slate-950/60 border border-slate-800 flex items-center justify-between">
                <div>
                    <span class="text-[10px] text-slate-400 uppercase tracking-wider block font-bold">Kandidat Pengawas:</span>
                    <span id="confirm-pengawas-name" class="text-sm font-bold text-emerald-400">Drs. Ahmad Fauzi</span>
                </div>
                <span id="confirm-pengawas-no" class="font-mono font-bold text-slate-300 text-sm bg-slate-800 px-2.5 py-1 rounded-lg">No. 01</span>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <button onclick="closeConfirmModal()" type="button" class="w-1/2 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs transition">
                Ubah Pilihan
            </button>
            <button onclick="executeVoteSubmission()" type="button" id="btn-final-submit" class="w-1/2 py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-lg shadow-blue-600/30 transition">
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
        selectedKetua = { nik, nama, nomor };
        document.getElementById('radio-ketua-' + nik).checked = true;

        // Reset semua style kartu ketua
        document.querySelectorAll('.candidate-card-ketua').forEach(card => {
            card.classList.remove('border-blue-500', 'bg-blue-950/30', 'ring-2', 'ring-blue-500/50');
            card.classList.add('border-slate-800', 'bg-slate-900/70');
            const check = card.querySelector('[id^="check-icon-ketua-"]');
            if (check) {
                check.classList.remove('bg-blue-600', 'text-white', 'border-blue-500');
                check.classList.add('bg-slate-800', 'text-transparent', 'border-slate-700');
            }
        });

        // Set active kartu terpilih
        const activeCard = document.getElementById('card-ketua-' + nik);
        activeCard.classList.remove('border-slate-800', 'bg-slate-900/70');
        activeCard.classList.add('border-blue-500', 'bg-blue-950/30', 'ring-2', 'ring-blue-500/50');
        const activeCheck = document.getElementById('check-icon-ketua-' + nik);
        activeCheck.classList.remove('bg-slate-800', 'text-transparent', 'border-slate-700');
        activeCheck.classList.add('bg-blue-600', 'text-white', 'border-blue-500');

        // Update badge & summary
        const badge = document.getElementById('ketua-status-badge');
        badge.className = 'px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-300 border border-blue-500/40';
        badge.innerText = 'Dipilih: No. ' + nomor;

        const summary = document.getElementById('summary-ketua');
        summary.className = 'text-blue-400 font-bold text-sm';
        summary.innerText = 'No. ' + nomor + ' - ' + nama;

        checkReadiness();
    }

    function selectPengawas(nik, nama, nomor) {
        selectedPengawas = { nik, nama, nomor };
        document.getElementById('radio-pengawas-' + nik).checked = true;

        // Reset semua style kartu pengawas
        document.querySelectorAll('.candidate-card-pengawas').forEach(card => {
            card.classList.remove('border-emerald-500', 'bg-emerald-950/30', 'ring-2', 'ring-emerald-500/50');
            card.classList.add('border-slate-800', 'bg-slate-900/70');
            const check = card.querySelector('[id^="check-icon-pengawas-"]');
            if (check) {
                check.classList.remove('bg-emerald-600', 'text-white', 'border-emerald-500');
                check.classList.add('bg-slate-800', 'text-transparent', 'border-slate-700');
            }
        });

        // Set active kartu terpilih
        const activeCard = document.getElementById('card-pengawas-' + nik);
        activeCard.classList.remove('border-slate-800', 'bg-slate-900/70');
        activeCard.classList.add('border-emerald-500', 'bg-emerald-950/30', 'ring-2', 'ring-emerald-500/50');
        const activeCheck = document.getElementById('check-icon-pengawas-' + nik);
        activeCheck.classList.remove('bg-slate-800', 'text-transparent', 'border-slate-700');
        activeCheck.classList.add('bg-emerald-600', 'text-white', 'border-emerald-500');

        // Update badge & summary
        const badge = document.getElementById('pengawas-status-badge');
        badge.className = 'px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40';
        badge.innerText = 'Dipilih: No. ' + nomor;

        const summary = document.getElementById('summary-pengawas');
        summary.className = 'text-emerald-400 font-bold text-sm';
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

        document.getElementById('confirm-ketua-name').innerText = selectedKetua.nama;
        document.getElementById('confirm-ketua-no').innerText = 'No. ' + selectedKetua.nomor;
        document.getElementById('confirm-pengawas-name').innerText = selectedPengawas.nama;
        document.getElementById('confirm-pengawas-no').innerText = 'No. ' + selectedPengawas.nomor;

        document.getElementById('confirm-modal').classList.remove('hidden');
    }

    function closeConfirmModal() {
        document.getElementById('confirm-modal').classList.add('hidden');
    }

    function executeVoteSubmission() {
        const btn = document.getElementById('btn-final-submit');
        btn.innerText = 'Menyimpan Suara...';
        btn.setAttribute('disabled', 'disabled');
        document.getElementById('voting-form').submit();
    }

    // Modal Visi & Misi
    function showDetailModal(kategori, nama, nomor, visi, misi, foto) {
        document.getElementById('modal-kategori').innerText = kategori;
        document.getElementById('modal-nama').innerText = nama;
        document.getElementById('modal-nomor').innerText = nomor.padStart(2, '0');
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
