@extends('layouts.admin')

@section('title', 'Data Pemilih & Dynamic Query')

@section('content')
<div class="space-y-6">

    <!-- Top Header Banner & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-2xs">
        <div>
            <div class="flex items-center space-x-2 mb-1.5">
                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-blue-100 text-blue-800 border border-blue-200">
                    {{ __('Eligible Voters') }}
                </span>
                <span id="http-method-badge" class="px-2.5 py-0.5 rounded-lg text-[10px] font-mono font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    {{ __('HTTP Method: QUERY') }}
                </span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ __('Eligible Voters & Dynamic Query') }}</h2>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5 max-w-2xl">{{ __('Manage voter roster, voting eligibility, RFID credentials, and CSV operations.') }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 self-start sm:self-auto">
            <a href="{{ route('admin.voters.export') }}" class="px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition flex items-center space-x-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>{{ __('Export CSV') }}</span>
            </a>

            <a href="{{ route('admin.voters.export.pdf') }}" class="px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold border border-slate-300 shadow-2xs transition flex items-center space-x-1.5 cursor-pointer" title="{{ __('Download Official Vector PDF') }}">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>{{ __('Export PDF') }}</span>
            </a>

            <button onclick="openImportModal()" class="px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold border border-slate-300 shadow-2xs transition flex items-center space-x-1.5 cursor-pointer">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                <span>{{ __('Import CSV') }}</span>
            </button>

            <button onclick="openAddModal()" class="px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-xs transition flex items-center space-x-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>{{ __('Add Voter') }}</span>
            </button>

            <form action="{{ route('admin.voters.reset') }}" method="POST" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin ME-RESET SELURUH SUARA pemilihan? Semua data suara ketua & pengawas akan dikosongkan dan status pemilih dikembalikan ke Belum Memilih (F).')">
                @csrf
                <button type="submit" class="px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold transition cursor-pointer" title="{{ __('Reset Suara untuk Demo/Gladi') }}">
                    {{ __('Reset Suara') }}
                </button>
            </form>
        </div>
    </div>

    <!-- Quick Stats Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-xs text-slate-500 font-semibold">{{ __('Total Registered') }}</span>
                <strong id="stat-total" class="block text-2xl font-black text-slate-900 font-mono">{{ $stats['total'] }}</strong>
            </div>
            <span class="p-2.5 rounded-xl bg-blue-50 text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </span>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-xs text-emerald-700 font-semibold">{{ __('Already Voted (T)') }}</span>
                <strong id="stat-voted" class="block text-2xl font-black text-emerald-600 font-mono">{{ $stats['voted'] }}</strong>
            </div>
            <span class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </span>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-xs text-amber-700 font-semibold">{{ __('Pending Vote (F)') }}</span>
                <strong id="stat-not-voted" class="block text-2xl font-black text-amber-600 font-mono">{{ $stats['not_voted'] }}</strong>
            </div>
            <span class="p-2.5 rounded-xl bg-amber-50 text-amber-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
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
                    <h4 class="text-xs font-black uppercase tracking-wider text-slate-800">{{ __('Filter & Dynamic Search') }}</h4>
                    <p class="text-[11px] text-slate-400">{{ __('Refine voter roster by keyword, status, and department') }}</p>
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
                        id="query-search-input" 
                        placeholder="{{ __('Search by NIK, Name, Department, or RFID UID (Press \'/\' to focus)...') }}"
                        oninput="debounceExecuteQuery()"
                        class="w-full pl-10 pr-10 py-3 rounded-2xl bg-slate-50/80 border border-slate-200 text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition font-medium"
                    >
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <button type="button" onclick="clearSearch()" class="absolute right-3.5 top-3 text-slate-400 hover:text-slate-600 text-sm font-bold cursor-pointer" title="{{ __('Hapus Pencarian') }}">✕</button>
                </div>
                <div class="hidden md:flex items-center shrink-0">
                    <kbd class="px-2.5 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-500 text-[11px] font-bold font-mono">
                        /
                    </kbd>
                </div>
            </div>

            <!-- Row 2: Filters Grid Optimized for iPad Mini (md: 768px), Mobile, and Desktop -->
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-5 gap-2.5">
                <!-- Status Filter -->
                <div class="col-span-1">
                    <select id="query-status-select" onchange="executeQuery(1)" class="w-full h-11 px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 font-bold focus:bg-white focus:border-indigo-500 outline-none cursor-pointer">
                        <option value="">{{ __('All Eligibility Status') }}</option>
                        <option value="T">{{ __('Already Voted (T)') }}</option>
                        <option value="F">{{ __('Pending Vote (F)') }}</option>
                    </select>
                </div>

                <!-- Department Filter -->
                <div class="col-span-1">
                    <select id="query-dept-select" onchange="executeQuery(1)" class="w-full h-11 px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 font-bold focus:bg-white focus:border-indigo-500 outline-none cursor-pointer">
                        <option value="">{{ __('All Departments') }}</option>
                        @foreach($departments as $d)
                            <option value="{{ $d }}">{{ $d }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Items Per Page -->
                <div class="col-span-1">
                    <select id="query-per-page-select" onchange="executeQuery(1)" class="w-full h-11 px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 font-bold focus:bg-white focus:border-indigo-500 outline-none cursor-pointer">
                        <option value="10">10 / {{ __('Page') }}</option>
                        <option value="25">25 / {{ __('Page') }}</option>
                        <option value="50">50 / {{ __('Page') }}</option>
                        <option value="100">100 / {{ __('Page') }}</option>
                    </select>
                </div>

                <!-- Apply Filters Button -->
                <div class="col-span-1">
                    <button 
                        type="button" 
                        onclick="executeQuery(1)" 
                        class="w-full h-11 px-4 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white text-xs font-black shadow-xs transition flex items-center justify-center space-x-1.5 cursor-pointer"
                        title="{{ __('Apply Current Filters') }}"
                    >
                        <span id="query-btn-icon">⚡</span>
                        <span>{{ __('Apply') }}</span>
                    </button>
                </div>

                <!-- Reset Filter Button -->
                <div class="col-span-2 sm:col-span-2 md:col-span-1">
                    <button 
                        type="button" 
                        onclick="resetAllFilters()" 
                        class="w-full h-11 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center justify-center space-x-1 cursor-pointer"
                    >
                        <span>↺</span>
                        <span>{{ __('Reset') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Voters Table Container -->
    <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-2xs overflow-hidden relative">
        <!-- Loading Overlay -->
        <div id="table-loading-overlay" class="hidden absolute inset-0 bg-white/70 backdrop-blur-2xs z-20 flex items-center justify-center">
            <div class="flex items-center space-x-2 px-4 py-2 rounded-2xl bg-slate-900 text-white text-xs font-bold shadow-lg">
                <span class="w-3 h-3 rounded-full bg-blue-400 animate-ping"></span>
                <span>Mengambil Data (HTTP QUERY)...</span>
            </div>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="min-w-[840px] w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-extrabold uppercase text-[11px] tracking-wider select-none">
                        <th class="py-3 px-3 cursor-pointer hover:text-slate-900 transition whitespace-nowrap" onclick="changeSort('nik')">
                            <span class="flex items-center space-x-1">
                                <span>NIK</span>
                                <span id="sort-icon-nik">↕</span>
                            </span>
                        </th>
                        <th class="py-3 px-3 cursor-pointer hover:text-slate-900 transition whitespace-nowrap" onclick="changeSort('nama')">
                            <span class="flex items-center space-x-1">
                                <span>{{ __('Nama Pemilih') }}</span>
                                <span id="sort-icon-nama">↕</span>
                            </span>
                        </th>
                        <th class="py-3 px-3 cursor-pointer hover:text-slate-900 transition whitespace-nowrap" onclick="changeSort('dept')">
                            <span class="flex items-center space-x-1">
                                <span>{{ __('Departemen') }}</span>
                                <span id="sort-icon-dept">↕</span>
                            </span>
                        </th>
                        <th class="py-3 px-3 whitespace-nowrap">RFID UID (Mifare)</th>
                        <th class="py-3 px-3 text-center cursor-pointer hover:text-slate-900 transition whitespace-nowrap" onclick="changeSort('pilih')">
                            <span class="inline-flex items-center space-x-1">
                                <span>{{ __('Status Suara') }}</span>
                                <span id="sort-icon-pilih">↕</span>
                            </span>
                        </th>
                        <th class="py-3 px-3 text-center whitespace-nowrap">
                            <span class="inline-flex items-center space-x-1">
                                <span>{{ __('Ikut Undian') }}</span>
                            </span>
                        </th>
                        <th class="py-3 px-3 text-center cursor-pointer hover:text-slate-900 transition whitespace-nowrap" onclick="changeSort('voted_at')">
                            <span class="inline-flex items-center space-x-1">
                                <span>{{ __('Waktu Vote') }}</span>
                                <span id="sort-icon-voted_at">↕</span>
                            </span>
                        </th>
                        <th class="py-3 px-3 text-right whitespace-nowrap">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody id="voters-table-body" class="divide-y divide-slate-100">
                    <!-- Populated by JavaScript executeQuery() -->
                    @forelse($voters->take(10) as $v)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-3 font-mono font-bold text-blue-700 whitespace-nowrap">{{ $v->nik }}</td>
                            <td class="py-3 px-3 font-bold text-slate-900">{{ $v->nama }}</td>
                            <td class="py-3 px-3 text-slate-600 whitespace-nowrap">{{ $v->dept }}</td>
                            <td class="py-3 px-3 font-mono text-slate-700 whitespace-nowrap">
                                <span class="bg-slate-50 rounded px-2 py-0.5 inline-block border border-slate-200">{{ $v->rfid }}</span>
                            </td>
                            <td class="py-3 px-3 text-center whitespace-nowrap">
                                @if($v->pilih === 'T')
                                    <span class="whitespace-nowrap px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        {{ __('Sudah Memilih (T)') }}
                                    </span>
                                @else
                                    <span class="whitespace-nowrap px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        {{ __('Belum (F)') }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-center whitespace-nowrap">
                                <button type="button" 
                                        onclick="toggleVoterRaffle('{{ $v->nik }}', this)" 
                                        data-nik="{{ $v->nik }}"
                                        data-active="{{ $v->can_raffle ? '1' : '0' }}"
                                        class="relative inline-flex items-center h-[22px] w-[40px] shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out focus:outline-none {{ $v->can_raffle ? 'bg-emerald-500' : 'bg-slate-300' }}"
                                        role="switch" 
                                        aria-checked="{{ $v->can_raffle ? 'true' : 'false' }}"
                                        title="{{ $v->can_raffle ? __('Boleh Ikut Undian & Voting (Klik untuk menonaktifkan)') : __('Tidak Boleh Ikut Undian & Voting (Klik untuk mengaktifkan)') }}">
                                    <span class="pointer-events-none inline-block h-[18px] w-[18px] rounded-full bg-white shadow-sm transition-transform duration-200 ease-in-out" style="transform: translateX({{ $v->can_raffle ? '20px' : '2px' }})"></span>
                                </button>
                            </td>
                            <td class="py-3 px-3 text-center font-mono text-slate-500 text-xs whitespace-nowrap">
                                {{ $v->voted_at ? $v->voted_at->format('H:i:s d/m/Y') : '-' }}
                            </td>
                            <td class="py-3 px-3 text-right whitespace-nowrap">
                                <form action="{{ route('admin.voters.destroy', $v->nik) }}" method="POST" onsubmit="return confirm('{{ __('Hapus pemilih') }} {{ $v->nama }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50 transition" title="{{ __('Hapus Pemilih') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400">{{ __('Tidak ada data pemilih.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination & Range Indicator -->
        <div class="mt-5 pt-4 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-600">
            <div>
                {{ __('Menampilkan') }} <strong id="pagination-from" class="text-slate-900">1</strong> {{ __('sampai') }} <strong id="pagination-to" class="text-slate-900">10</strong> {{ __('dari') }} <strong id="pagination-total" class="text-slate-900">{{ $stats['total'] }}</strong> {{ __('data anggota') }}
            </div>

            <div class="flex items-center space-x-1" id="pagination-buttons">
                <!-- Dynamic Page Buttons -->
            </div>
        </div>
    </div>

</div>

<!-- Modal Tambah Pemilih Manual -->
<div id="add-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl max-w-md w-full p-6 shadow-2xl">
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-200">
            <h3 class="text-lg font-bold text-slate-900">{{ __('Tambah Pemilih Baru') }}</h3>
            <button onclick="closeAddModal()" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>

        <form action="{{ route('admin.voters.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('NIK (Nomor Induk Anggota)') }}</label>
                <input type="text" name="nik" required placeholder="{{ __('Contoh: 2024099') }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 focus:border-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('Nama Lengkap') }}</label>
                <input type="text" name="nama" required placeholder="{{ __('Nama anggota koperasi...') }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 focus:border-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('Departemen / Bagian') }}</label>
                <select name="dept" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 focus:border-blue-500 outline-none cursor-pointer">
                    <option value="" disabled selected>{{ __('Pilih Departemen...') }}</option>
                    @foreach($departments as $d)
                        <option value="{{ $d }}">{{ $d }}</option>
                    @endforeach
                    <option value="ICT">ICT</option>
                    <option value="Keuangan">Keuangan</option>
                    <option value="Operasional">Operasional</option>
                    <option value="HRD">HRD</option>
                    <option value="Logistik">Logistik</option>
                    <option value="Produksi">Produksi</option>
                    <option value="Pemasaran">Pemasaran</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('RFID UID Mifare') }}</label>
                <input type="text" name="rfid" required placeholder="{{ __('Tempelkan pada reader atau input hex...') }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 font-mono focus:border-blue-500 outline-none">
            </div>

            <div class="pt-1">
                <label class="flex items-center space-x-2.5 cursor-pointer">
                    <input type="checkbox" name="can_raffle" value="1" checked class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300">
                    <span class="text-xs font-bold text-slate-700">{{ __('Izinkan Ikut Undian Doorprize & Voting') }} <span class="text-[11px] text-slate-400 font-normal">({{ __('Default: Aktif') }})</span></span>
                </label>
            </div>

            <div class="pt-3 border-t border-slate-200 flex justify-end space-x-2">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold">{{ __('Batal') }}</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow">{{ __('Simpan') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Import Excel / CSV -->
<div id="import-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl max-w-md w-full p-6 shadow-2xl">
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-200">
            <h3 class="text-lg font-bold text-slate-900">{{ __('Import Data Pemilih (DPT)') }}</h3>
            <button onclick="closeImportModal()" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>

        <form action="{{ route('admin.voters.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('Pilih File CSV / Excel (.csv, .xlsx, .xls)') }}</label>
                <input type="file" name="file" required accept=".csv,.xlsx,.xls" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <div class="p-3 rounded-xl bg-blue-50 border border-blue-200 text-xs text-blue-900">
                {{ __('Format kolom wajib:') }} <strong>nik, rfid, nama, dept</strong>.
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-slate-200">
                <a href="{{ route('admin.voters.template') }}" class="text-xs font-bold text-blue-700 hover:underline">
                    {{ __('↓ Unduh Template CSV') }}
                </a>
                <div class="flex space-x-2">
                    <button type="button" onclick="closeImportModal()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold">{{ __('Batal') }}</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow">{{ __('Mulai Import') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let currentQueryPage = 1;
    let currentSortField = 'nama';
    let currentSortDir = 'asc';
    let debounceTimer = null;

    document.addEventListener('keydown', (e) => {
        if (e.key === '/' && document.activeElement.tagName !== 'INPUT') {
            e.preventDefault();
            document.getElementById('query-search-input')?.focus();
        }
    });

    function toggleFilterFold() {
        const container = document.getElementById('filter-body-container');
        const icon = document.getElementById('filter-fold-icon');
        if (!container) return;

        if (container.classList.contains('hidden')) {
            container.classList.remove('hidden');
            if (icon) icon.textContent = '▲';
        } else {
            container.classList.add('hidden');
            if (icon) icon.textContent = '▼';
        }
    }

    function openAddModal() {
        if (window.SoundEffects) window.SoundEffects.modal();
        document.getElementById('add-modal').classList.remove('hidden');
    }
    function closeAddModal() {
        document.getElementById('add-modal').classList.add('hidden');
    }
    function openImportModal() {
        if (window.SoundEffects) window.SoundEffects.modal();
        document.getElementById('import-modal').classList.remove('hidden');
    }
    function closeImportModal() {
        document.getElementById('import-modal').classList.add('hidden');
    }

    function clearSearch() {
        document.getElementById('query-search-input').value = '';
        executeQuery(1);
    }

    function resetAllFilters() {
        document.getElementById('query-search-input').value = '';
        document.getElementById('query-status-select').value = '';
        document.getElementById('query-dept-select').value = '';
        document.getElementById('query-per-page-select').value = '10';
        currentSortField = 'nama';
        currentSortDir = 'asc';
        updateSortIcons();
        executeQuery(1);
    }

    function debounceExecuteQuery() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            executeQuery(1);
        }, 300);
    }

    function changeSort(field) {
        if (currentSortField === field) {
            currentSortDir = currentSortDir === 'asc' ? 'desc' : 'asc';
        } else {
            currentSortField = field;
            currentSortDir = 'asc';
        }
        updateSortIcons();
        executeQuery(currentQueryPage);
    }

    function updateSortIcons() {
        ['nik', 'nama', 'dept', 'pilih', 'voted_at'].forEach(f => {
            const el = document.getElementById('sort-icon-' + f);
            if (el) {
                if (currentSortField === f) {
                    el.innerText = currentSortDir === 'asc' ? '▲' : '▼';
                    el.className = 'text-blue-600 font-bold';
                } else {
                    el.innerText = '↕';
                    el.className = 'text-slate-300';
                }
            }
        });
    }

    // Execute HTTP QUERY Method request with JSON body (RFC Draft safe method)
    async function executeQuery(page = 1) {
        currentQueryPage = page;
        const search = document.getElementById('query-search-input').value;
        const status = document.getElementById('query-status-select').value;
        const dept = document.getElementById('query-dept-select').value;
        const perPage = document.getElementById('query-per-page-select').value;

        const overlay = document.getElementById('table-loading-overlay');
        overlay.classList.remove('hidden');

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const payload = {
            search: search,
            status: status,
            dept: dept,
            per_page: perPage,
            page: page,
            sort: currentSortField,
            dir: currentSortDir
        };

        const badge = document.getElementById('http-method-badge');

        try {
            // Attempt genuine 'QUERY' HTTP method
            let response;
            try {
                response = await fetch("{{ route('admin.voters.query') }}", {
                    method: 'QUERY',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                badge.innerText = 'HTTP Method: QUERY (RFC Safe)';
                badge.className = 'px-2.5 py-0.5 rounded-lg text-[10px] font-mono font-bold bg-indigo-50 text-indigo-700 border border-indigo-200';
            } catch (queryErr) {
                // Graceful fallback to POST with X-HTTP-Method-Override if browser blocks QUERY
                response = await fetch("{{ route('admin.voters.query') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-HTTP-Method-Override': 'QUERY',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                badge.innerText = 'HTTP Method: POST (Method Override: QUERY)';
                badge.className = 'px-2.5 py-0.5 rounded-lg text-[10px] font-mono font-bold bg-amber-50 text-amber-700 border border-amber-200';
            }

            const resData = await response.json();
            renderTableData(resData);
        } catch (err) {
            console.error('Query error:', err);
        } finally {
            overlay.classList.add('hidden');
        }
    }

    function renderTableData(res) {
        if (!res || !res.success) return;

        const tbody = document.getElementById('voters-table-body');
        const items = res.data;

        if (items.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="py-8 text-center text-slate-400">{{ __('Tidak ada data pemilih yang sesuai kriteria query.') }}</td>
                </tr>
            `;
        } else {
            let rowsHtml = '';
            items.forEach(v => {
                const statusBadge = v.pilih === 'T'
                    ? `<span class="whitespace-nowrap px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">{{ __('Sudah Memilih (T)') }}</span>`
                    : `<span class="whitespace-nowrap px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">{{ __('Belum (F)') }}</span>`;

                const isRaffle = v.can_raffle !== false;
                const raffleSwitch = `
                    <button type="button" 
                            onclick="toggleVoterRaffle('${v.nik}', this)" 
                            data-nik="${v.nik}"
                            data-active="${isRaffle ? '1' : '0'}"
                            class="relative inline-flex items-center h-[22px] w-[40px] shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out focus:outline-none ${isRaffle ? 'bg-emerald-500' : 'bg-slate-300'}"
                            role="switch" 
                            aria-checked="${isRaffle ? 'true' : 'false'}"
                            title="${isRaffle ? '{{ __('Boleh Ikut Undian & Voting (Klik untuk menonaktifkan)') }}' : '{{ __('Tidak Boleh Ikut Undian & Voting (Klik untuk mengaktifkan)') }}'}">
                        <span class="pointer-events-none inline-block h-[18px] w-[18px] rounded-full bg-white shadow-sm transition-transform duration-200 ease-in-out" style="transform: translateX(${isRaffle ? '20px' : '2px'})"></span>
                    </button>
                `;

                rowsHtml += `
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-3 font-mono font-bold text-blue-700 whitespace-nowrap">${v.nik}</td>
                        <td class="py-3 px-3 font-bold text-slate-900">${v.nama}</td>
                        <td class="py-3 px-3 text-slate-600 whitespace-nowrap">${v.dept}</td>
                        <td class="py-3 px-3 font-mono text-slate-700 whitespace-nowrap">
                            <span class="bg-slate-50 rounded px-2 py-0.5 inline-block border border-slate-200">${v.rfid}</span>
                        </td>
                        <td class="py-3 px-3 text-center whitespace-nowrap">${statusBadge}</td>
                        <td class="py-3 px-3 text-center whitespace-nowrap">${raffleSwitch}</td>
                        <td class="py-3 px-3 text-center font-mono text-slate-500 text-xs whitespace-nowrap">${v.voted_at}</td>
                        <td class="py-3 px-3 text-right whitespace-nowrap">
                            <form action="${v.delete_url}" method="POST" onsubmit="return confirm('{{ __('Hapus pemilih') }} ${v.nama}?')">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50 transition" title="{{ __('Hapus Pemilih') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = rowsHtml;
        }

        // Update pagination numbers
        document.getElementById('pagination-from').innerText = res.pagination.from;
        document.getElementById('pagination-to').innerText = res.pagination.to;
        document.getElementById('pagination-total').innerText = res.pagination.total;

        // Update stats
        if (res.stats) {
            document.getElementById('stat-total').innerText = res.stats.total;
            document.getElementById('stat-voted').innerText = res.stats.voted;
            document.getElementById('stat-not-voted').innerText = res.stats.not_voted;
        }

        renderPaginationButtons(res.pagination);
    }

    function renderPaginationButtons(p) {
        const container = document.getElementById('pagination-buttons');
        let html = '';

        if (p.current_page > 1) {
            html += `<button onclick="executeQuery(${p.current_page - 1})" class="px-2.5 py-1 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 font-bold">‹</button>`;
        }

        const startPage = Math.max(1, p.current_page - 2);
        const endPage = Math.min(p.last_page, p.current_page + 2);

        for (let i = startPage; i <= endPage; i++) {
            if (i === p.current_page) {
                html += `<button class="px-3 py-1 rounded-lg bg-blue-600 text-white font-bold">${i}</button>`;
            } else {
                html += `<button onclick="executeQuery(${i})" class="px-3 py-1 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-100 font-bold">${i}</button>`;
            }
        }

        if (p.current_page < p.last_page) {
            html += `<button onclick="executeQuery(${p.current_page + 1})" class="px-2.5 py-1 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 font-bold">›</button>`;
        }

        container.innerHTML = html;
    }

    async function toggleVoterRaffle(nik, btnEl) {
        if (!btnEl) return;
        const currentActive = btnEl.getAttribute('data-active') === '1';
        const nextActive = !currentActive;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        btnEl.disabled = true;
        btnEl.style.opacity = '0.6';

        try {
            const res = await fetch(`/admin/voters/${encodeURIComponent(nik)}/toggle-raffle`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ can_raffle: nextActive })
            });
            const data = await res.json();
            if (data.success) {
                const isActive = data.can_raffle;
                btnEl.setAttribute('data-active', isActive ? '1' : '0');
                btnEl.setAttribute('aria-checked', isActive ? 'true' : 'false');
                const dot = btnEl.querySelector('span');
                if (isActive) {
                    btnEl.classList.remove('bg-slate-300');
                    btnEl.classList.add('bg-emerald-500');
                    if (dot) dot.style.transform = 'translateX(20px)';
                    btnEl.title = "{{ __('Boleh Ikut Undian & Voting (Klik untuk menonaktifkan)') }}";
                } else {
                    btnEl.classList.remove('bg-emerald-500');
                    btnEl.classList.add('bg-slate-300');
                    if (dot) dot.style.transform = 'translateX(2px)';
                    btnEl.title = "{{ __('Tidak Boleh Ikut Undian & Voting (Klik untuk mengaktifkan)') }}";
                }
                if (window.SoundEffects && window.SoundEffects.click) {
                    window.SoundEffects.click();
                }
            } else {
                alert(data.message || 'Gagal mengubah status undian.');
            }
        } catch (err) {
            console.error('Toggle error:', err);
            alert('Terjadi kesalahan jaringan saat memperbarui status.');
        } finally {
            btnEl.disabled = false;
            btnEl.style.opacity = '1';
        }
    }

    // Initial load on page ready
    document.addEventListener('DOMContentLoaded', () => {
        executeQuery(1);
    });
</script>
@endpush
@endsection
