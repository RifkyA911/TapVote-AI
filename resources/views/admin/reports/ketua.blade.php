@extends('layouts.admin')

@section('title', 'Chairman Election Recap & Results')

@section('content')
<div class="space-y-6">

    <!-- Top Header with Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-blue-100 text-blue-800 border border-blue-200">
                    Chairman Recap
                </span>
                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-mono font-bold bg-slate-100 text-slate-700 border border-slate-200">
                    Official Report 01
                </span>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900">Hasil & Rekapitulasi Ketua Koperasi</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Perolehan suara resmi, penetapan calon ketua terpilih, dan rincian suara per kandidat.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.reports.ketua.export') }}" class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition flex items-center space-x-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Export Excel</span>
            </a>

            <a href="{{ route('admin.reports.ketua.export.pdf') }}" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold border border-slate-300 shadow-2xs transition flex items-center space-x-1.5 cursor-pointer" title="Download Official Vector PDF">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Export PDF</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-xs text-slate-500 font-semibold">Total Suara Sah Masuk</span>
                <strong class="block text-2xl font-black text-slate-900 font-mono">{{ $totalSuara }}</strong>
            </div>
            <span class="p-2.5 rounded-xl bg-blue-50 text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </span>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-xs text-emerald-700 font-semibold">Perolehan Suara Tertinggi</span>
                <strong class="block text-2xl font-black text-emerald-600 font-mono">{{ $maxVotes }} Suara</strong>
            </div>
            <span class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </span>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-xs {{ $isSeri ? 'text-amber-700' : 'text-indigo-700' }} font-semibold">Status Hasil Pemilihan</span>
                <strong class="block text-base font-black {{ $isSeri ? 'text-amber-600' : 'text-indigo-600' }} truncate mt-1">
                    {{ $isSeri ? '⚖️ HASIL SERI (DRAW)' : ($pemenang ? '🏆 ADA PEMENANG' : 'BELUM ADA SUARA') }}
                </strong>
            </div>
            <span class="p-2.5 rounded-xl {{ $isSeri ? 'bg-amber-50 text-amber-600' : 'bg-indigo-50 text-indigo-600' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
            </span>
        </div>
    </div>

    <!-- TIE / SERI ALERT BANNER -->
    @if($isSeri)
        <div class="p-6 sm:p-7 rounded-3xl bg-amber-50 border-2 border-amber-300 shadow-2xs relative overflow-hidden">
            <div class="flex flex-col md:flex-row items-start gap-5">
                <div class="w-12 h-12 rounded-2xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="flex-1">
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-amber-200 text-amber-900 border border-amber-300 mb-2">
                        ⚖️ HASIL SERI / DRAW (Suara Terbanyak Seimbang)
                    </div>
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900">Belum Ada Pemenang Tunggal Ketua Koperasi</h3>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1 leading-relaxed">
                        Terdapat <strong>{{ $topCandidates->count() }} kandidat</strong> yang memperoleh perolehan suara tertinggi sama persis, yaitu <strong>{{ $maxVotes }} suara</strong>. Diperlukan musyawarah mufakat atau pemungutan suara putaran kedua (run-off).
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                        @foreach($topCandidates as $c)
                            <div class="p-3.5 rounded-2xl bg-white border border-amber-200 shadow-2xs flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-100 border border-amber-300 shrink-0">
                                    <img src="{{ $c->foto ?: 'https://ui-avatars.com/api/?name='.urlencode($c->nama).'&background=d97706&color=ffffff&size=200' }}" alt="{{ $c->nama }}" class="w-full h-full object-cover object-top">
                                </div>
                                <div class="min-w-0">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-amber-100 text-amber-800">No. {{ $c->nomor_urut }}</span>
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate mt-0.5">{{ $c->nama }}</h4>
                                    <p class="text-xs font-mono font-bold text-amber-700">{{ $c->perolehan_suara_count }} Suara</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @elseif($pemenang && $pemenang->perolehan_suara_count > 0)
        @php
            $persenPemenang = $totalSuara > 0 ? round(($pemenang->perolehan_suara_count / $totalSuara) * 100, 2) : 0;
        @endphp
        <div class="p-6 sm:p-7 rounded-3xl bg-blue-50/80 border-2 border-blue-200 shadow-2xs relative overflow-hidden">
            <div class="flex flex-col sm:flex-row items-center gap-6 relative z-10">
                <div class="relative">
                    <div class="w-28 h-28 rounded-2xl overflow-hidden bg-white border-2 border-blue-500 shadow-md">
                        <img 
                            src="{{ $pemenang->foto ?: 'https://ui-avatars.com/api/?name='.urlencode($pemenang->nama).'&background=2563eb&color=ffffff&size=400' }}" 
                            alt="{{ $pemenang->nama }}" 
                            class="w-full h-full object-cover object-top"
                        >
                    </div>
                    <span class="absolute -bottom-2 -right-2 px-2.5 py-0.5 rounded-full bg-blue-600 text-white font-mono font-bold text-xs shadow-md">
                        No. {{ $pemenang->nomor_urut }}
                    </span>
                </div>

                <div class="text-center sm:text-left flex-1">
                    <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-900 border border-amber-300 text-xs font-extrabold uppercase tracking-wider mb-2">
                        <span>🏆</span>
                        <span>Kandidat Terpilih (Suara Terbanyak)</span>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900">{{ $pemenang->nama }}</h3>
                    <p class="text-xs text-blue-700 font-mono mt-0.5">NIK: {{ $pemenang->nik }}</p>

                    <div class="flex flex-wrap items-center gap-6 mt-4 pt-4 border-t border-blue-200">
                        <div>
                            <span class="text-[11px] uppercase tracking-wider text-slate-500 block font-semibold">Total Perolehan</span>
                            <strong class="text-2xl font-black text-slate-900 font-mono">{{ $pemenang->perolehan_suara_count }} Suara</strong>
                        </div>
                        <div class="h-8 w-px bg-blue-200"></div>
                        <div>
                            <span class="text-[11px] uppercase tracking-wider text-slate-500 block font-semibold">Persentase</span>
                            <strong class="text-2xl font-black text-blue-600 font-mono">{{ $persenPemenang }}%</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Foldable Interactive Query Toolbar -->
    <div class="p-4 sm:p-5 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-3.5">
        <!-- Foldable Header with Icon -->
        <div class="flex items-center justify-between cursor-pointer select-none pb-2 border-b border-slate-100" onclick="toggleFilterFold()">
            <div class="flex items-center space-x-2.5">
                <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                </div>
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-slate-800">Filter & Dynamic Search</h4>
                    <p class="text-[11px] text-slate-400">Cari kandidat berdasarkan nomor urut, nama lengkap, atau NIK</p>
                </div>
            </div>
            <button type="button" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-500 transition">
                <span id="filter-fold-icon" class="text-xs font-mono font-bold block transform transition-transform duration-200">▲</span>
            </button>
        </div>

        <div id="filter-body-container" class="space-y-3.5 transition-all duration-300">
            <!-- Row 1: Full Width Search Input with Shortcut Hints -->
            <div class="flex items-center gap-3">
                <div class="relative flex-1">
                    <input 
                        type="text" 
                        id="candidate-search-input" 
                        placeholder="Cari kandidat berdasarkan Nomor Urut, Nama, atau NIK (Tekan '/' untuk fokus)..."
                        oninput="filterCandidateTable()"
                        class="w-full pl-10 pr-10 py-3 rounded-2xl bg-slate-50/80 border border-slate-200 text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition font-medium"
                    >
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <button type="button" onclick="clearCandidateSearch()" class="absolute right-3.5 top-3 text-slate-400 hover:text-slate-600 text-sm font-bold cursor-pointer" title="Hapus Pencarian">✕</button>
                </div>
                <div class="hidden md:flex items-center shrink-0">
                    <kbd class="px-2.5 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-500 text-[11px] font-bold font-mono">
                        /
                    </kbd>
                </div>
            </div>

            <!-- Row 2: Status & Action Filters -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                <div class="col-span-1">
                    <select id="candidate-status-filter" onchange="filterCandidateTable()" class="w-full h-11 px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 font-bold focus:bg-white focus:border-indigo-500 outline-none cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="TERPILIH">Terpilih</option>
                        <option value="SERI">Seri</option>
                        <option value="BELUM">Kandidat Lainnya</option>
                    </select>
                </div>

                <div class="col-span-1">
                    <select id="candidate-sort-select" onchange="sortCandidateTable(this.value)" class="w-full h-11 px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 font-bold focus:bg-white focus:border-indigo-500 outline-none cursor-pointer">
                        <option value="votes_desc">Suara Terbanyak (Default)</option>
                        <option value="votes_asc">Suara Tersedikit</option>
                        <option value="nomor_asc">Nomor Urut (1, 2, 3...)</option>
                        <option value="nama_asc">Nama (A-Z)</option>
                    </select>
                </div>

                <div class="col-span-1">
                    <button 
                        type="button" 
                        onclick="filterCandidateTable()" 
                        class="w-full h-11 px-4 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white text-xs font-black shadow-xs transition flex items-center justify-center space-x-1.5 cursor-pointer"
                        title="Terapkan Filter"
                    >
                        <span>⚡</span>
                        <span>Apply</span>
                    </button>
                </div>

                <div class="col-span-1">
                    <button 
                        type="button" 
                        onclick="resetCandidateFilters()" 
                        class="w-full h-11 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center justify-center space-x-1 cursor-pointer"
                    >
                        <span>↺</span>
                        <span>Reset</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Candidate Table Container -->
    <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-2xs overflow-hidden relative">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm" id="candidates-datatable">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-extrabold uppercase text-[11px] tracking-wider select-none bg-slate-50/80">
                        <th class="py-3.5 px-4">No. Urut</th>
                        <th class="py-3.5 px-4">Kandidat Ketua</th>
                        <th class="py-3.5 px-4">NIK</th>
                        <th class="py-3.5 px-4 text-right">Perolehan Suara</th>
                        <th class="py-3.5 px-4 text-right">Persentase</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="candidate-table-body">
                    @foreach($kandidatKetua as $k)
                        @php
                            $persen = $totalSuara > 0 ? round(($k->perolehan_suara_count / $totalSuara) * 100, 2) : 0;
                            $isTop = $maxVotes > 0 && $k->perolehan_suara_count === $maxVotes;
                            $statusKey = ($isSeri && $isTop) ? 'SERI' : ((!$isSeri && $isTop) ? 'TERPILIH' : 'BELUM');
                        @endphp
                        <tr class="hover:bg-slate-50/75 transition border-b border-slate-100 candidate-row" 
                            data-nomor="{{ $k->nomor_urut }}"
                            data-nama="{{ strtolower($k->nama) }}"
                            data-nik="{{ $k->nik }}"
                            data-votes="{{ $k->perolehan_suara_count }}"
                            data-status="{{ $statusKey }}">
                            <td class="py-3 px-4 font-mono font-bold text-slate-900">
                                <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-800 flex items-center justify-center font-bold text-xs">
                                    {{ $k->nomor_urut }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                        <img src="{{ $k->foto ?: 'https://ui-avatars.com/api/?name='.urlencode($k->nama).'&background=2563eb&color=ffffff&size=100' }}" alt="{{ $k->nama }}" class="w-full h-full object-cover object-top">
                                    </div>
                                    <div>
                                        <strong class="font-extrabold text-slate-900 block">{{ $k->nama }}</strong>
                                        <span class="text-xs text-slate-400">Calon Ketua Koperasi</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-slate-500 font-mono">{{ $k->nik }}</td>
                            <td class="py-3 px-4 text-right">
                                <strong class="font-black text-slate-900 font-mono text-sm">{{ $k->perolehan_suara_count }}</strong>
                                <span class="text-xs text-slate-400 ml-0.5">Suara</span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <span class="font-mono font-black text-blue-600 text-sm">{{ $persen }}%</span>
                                <div class="w-20 bg-slate-100 rounded-full h-1.5 ml-auto mt-1 overflow-hidden">
                                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $persen }}%"></div>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($isSeri && $isTop)
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-black bg-amber-100 text-amber-800 border border-amber-300">
                                        ⚖️ SERI
                                    </span>
                                @elseif(!$isSeri && $isTop)
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        🏆 TERPILIH
                                    </span>
                                @else
                                    <span class="text-slate-400 text-xs font-medium">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
function toggleFilterFold() {
    const container = document.getElementById('filter-body-container');
    const icon = document.getElementById('filter-fold-icon');
    if (container.classList.contains('hidden')) {
        container.classList.remove('hidden');
        icon.style.transform = 'rotate(0deg)';
    } else {
        container.classList.add('hidden');
        icon.style.transform = 'rotate(180deg)';
    }
}

function clearCandidateSearch() {
    const input = document.getElementById('candidate-search-input');
    input.value = '';
    filterCandidateTable();
    input.focus();
}

function resetCandidateFilters() {
    document.getElementById('candidate-search-input').value = '';
    document.getElementById('candidate-status-filter').value = '';
    document.getElementById('candidate-sort-select').value = 'votes_desc';
    filterCandidateTable();
    sortCandidateTable('votes_desc');
}

function filterCandidateTable() {
    const search = document.getElementById('candidate-search-input').value.toLowerCase().trim();
    const status = document.getElementById('candidate-status-filter').value;
    const rows = document.querySelectorAll('.candidate-row');

    rows.forEach(row => {
        const nomor = row.getAttribute('data-nomor');
        const nama = row.getAttribute('data-nama');
        const nik = row.getAttribute('data-nik');
        const rowStatus = row.getAttribute('data-status');

        const matchesSearch = !search || nomor.includes(search) || nama.includes(search) || nik.includes(search);
        const matchesStatus = !status || rowStatus === status;

        if (matchesSearch && matchesStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function sortCandidateTable(mode) {
    const tbody = document.getElementById('candidate-table-body');
    const rows = Array.from(tbody.querySelectorAll('.candidate-row'));

    rows.sort((a, b) => {
        if (mode === 'votes_desc') {
            return parseInt(b.getAttribute('data-votes')) - parseInt(a.getAttribute('data-votes'));
        } else if (mode === 'votes_asc') {
            return parseInt(a.getAttribute('data-votes')) - parseInt(b.getAttribute('data-votes'));
        } else if (mode === 'nomor_asc') {
            return parseInt(a.getAttribute('data-nomor')) - parseInt(b.getAttribute('data-nomor'));
        } else if (mode === 'nama_asc') {
            return a.getAttribute('data-nama').localeCompare(b.getAttribute('data-nama'));
        }
        return 0;
    });

    rows.forEach(r => tbody.appendChild(r));
}

document.addEventListener('keydown', (e) => {
    if (e.key === '/' && document.activeElement.tagName !== 'INPUT') {
        e.preventDefault();
        const input = document.getElementById('candidate-search-input');
        if (input) input.focus();
    }
});
</script>
@endsection
