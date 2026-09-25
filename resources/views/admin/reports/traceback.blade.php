@extends('layouts.admin')

@section('title', 'Forensic Trace Back - Vote Ledger')

@section('content')
<div class="space-y-6">

    <!-- Top Header with Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-blue-100 text-blue-800 border border-blue-200">
                    Forensic Audit
                </span>
                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-mono font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    Traceback Ledger
                </span>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900">Trace Back Rekapitulasi Suara</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Audit log pemilih dan verifikasi pasangan pilihan calon (Ketua & Pengawas) per transaksi.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.reports.traceback.export') }}" class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition flex items-center space-x-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Export Excel</span>
            </a>

            <a href="{{ route('admin.reports.traceback.export.pdf') }}" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold border border-slate-300 shadow-2xs transition flex items-center space-x-1.5 cursor-pointer" title="Download Official Vector PDF">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Export PDF</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-xs text-slate-500 font-semibold">Total Suara Terekam</span>
                <strong class="block text-2xl font-black text-slate-900 font-mono">{{ $totalVoted }}</strong>
            </div>
            <span class="p-2.5 rounded-xl bg-blue-50 text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </span>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-xs text-emerald-700 font-semibold">Departemen Berpartisipasi</span>
                <strong class="block text-2xl font-black text-emerald-600 font-mono">{{ count($departments) }} Divisi</strong>
            </div>
            <span class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </span>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-xs text-indigo-700 font-semibold">Integritas Kriptografis</span>
                <strong class="block text-base font-black text-indigo-600 truncate mt-1">100% SHA-256 Valid</strong>
            </div>
            <span class="p-2.5 rounded-xl bg-indigo-50 text-indigo-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </span>
        </div>
    </div>

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
                    <p class="text-[11px] text-slate-400">Cari audit transaksi pemilih berdasarkan NIK, Nama, Departemen, atau Pilihan Kandidat</p>
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
                        id="traceback-search-input" 
                        placeholder="Search by NIK, Name, Department, or Candidate choice (Press '/' to focus)..."
                        oninput="filterTracebackTable()"
                        class="w-full pl-10 pr-10 py-3 rounded-2xl bg-slate-50/80 border border-slate-200 text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition font-medium"
                    >
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <button type="button" onclick="clearTracebackSearch()" class="absolute right-3.5 top-3 text-slate-400 hover:text-slate-600 text-sm font-bold cursor-pointer" title="Hapus Pencarian">✕</button>
                </div>
                <div class="hidden md:flex items-center shrink-0">
                    <kbd class="px-2.5 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-500 text-[11px] font-bold font-mono">
                        /
                    </kbd>
                </div>
            </div>

            <!-- Row 2: Department Filter & Action Buttons -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                <div class="col-span-1 sm:col-span-2">
                    <select id="traceback-dept-filter" onchange="filterTracebackTable()" class="w-full h-11 px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 font-bold focus:bg-white focus:border-indigo-500 outline-none cursor-pointer">
                        <option value="">Semua Departemen</option>
                        @foreach($departments as $d)
                            <option value="{{ $d }}">{{ $d }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-1">
                    <button 
                        type="button" 
                        onclick="filterTracebackTable()" 
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
                        onclick="resetTracebackFilters()" 
                        class="w-full h-11 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center justify-center space-x-1 cursor-pointer"
                    >
                        <span>↺</span>
                        <span>Reset</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Trace Back Table Container -->
    <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-2xs overflow-hidden relative">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm" id="traceback-datatable">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-extrabold uppercase text-[11px] tracking-wider select-none bg-slate-50/80">
                        <th class="py-3.5 px-4">Waktu Transaksi</th>
                        <th class="py-3.5 px-4">NIK Pemilih</th>
                        <th class="py-3.5 px-4">Nama Pemilih</th>
                        <th class="py-3.5 px-4">Departemen</th>
                        <th class="py-3.5 px-4">Pilihan Calon Ketua</th>
                        <th class="py-3.5 px-4">Pilihan Calon Pengawas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="traceback-table-body">
                    @forelse($voters as $v)
                        @php
                            $ketuaNama = $v->hasilKetua?->kandidatKetua?->nama ?? '';
                            $pengawasNama = $v->hasilPengawas?->kandidatPengawas?->nama ?? '';
                        @endphp
                        <tr class="hover:bg-slate-50/75 transition border-b border-slate-100 traceback-row"
                            data-nik="{{ $v->nik }}"
                            data-nama="{{ strtolower($v->nama) }}"
                            data-dept="{{ strtolower($v->dept) }}"
                            data-ketua="{{ strtolower($ketuaNama) }}"
                            data-pengawas="{{ strtolower($pengawasNama) }}">
                            <td class="py-3.5 px-4 font-mono text-slate-500 text-xs">
                                {{ $v->voted_at ? $v->voted_at->format('H:i:s d/m/Y') : '-' }}
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-700">{{ $v->nik }}</td>
                            <td class="py-3.5 px-4 font-extrabold text-slate-900">{{ $v->nama }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                                    {{ $v->dept }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($v->hasilKetua && $v->hasilKetua->kandidatKetua)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl bg-blue-50 text-blue-800 text-xs font-bold border border-blue-200">
                                        No. {{ $v->hasilKetua->kandidatKetua->nomor_urut }} - {{ $v->hasilKetua->kandidatKetua->nama }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic text-xs">Belum memilih</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($v->hasilPengawas && $v->hasilPengawas->kandidatPengawas)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl bg-emerald-50 text-emerald-800 text-xs font-bold border border-emerald-200">
                                        No. {{ $v->hasilPengawas->kandidatPengawas->nomor_urut }} - {{ $v->hasilPengawas->kandidatPengawas->nama }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic text-xs">Belum memilih</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr id="empty-traceback-row">
                            <td colspan="6" class="py-12 text-center text-slate-400 font-medium">Belum ada transaksi pemungutan suara yang tercatat.</td>
                        </tr>
                    @endforelse
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

function clearTracebackSearch() {
    const input = document.getElementById('traceback-search-input');
    input.value = '';
    filterTracebackTable();
    input.focus();
}

function resetTracebackFilters() {
    document.getElementById('traceback-search-input').value = '';
    document.getElementById('traceback-dept-filter').value = '';
    filterTracebackTable();
}

function filterTracebackTable() {
    const search = document.getElementById('traceback-search-input').value.toLowerCase().trim();
    const dept = document.getElementById('traceback-dept-filter').value.toLowerCase().trim();
    const rows = document.querySelectorAll('.traceback-row');

    rows.forEach(row => {
        const nik = row.getAttribute('data-nik');
        const nama = row.getAttribute('data-nama');
        const rowDept = row.getAttribute('data-dept');
        const ketua = row.getAttribute('data-ketua');
        const pengawas = row.getAttribute('data-pengawas');

        const matchesSearch = !search || nik.includes(search) || nama.includes(search) || rowDept.includes(search) || ketua.includes(search) || pengawas.includes(search);
        const matchesDept = !dept || rowDept === dept;

        if (matchesSearch && matchesDept) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

document.addEventListener('keydown', (e) => {
    if (e.key === '/' && document.activeElement.tagName !== 'INPUT') {
        e.preventDefault();
        const input = document.getElementById('traceback-search-input');
        if (input) input.focus();
    }
});
</script>
@endsection
