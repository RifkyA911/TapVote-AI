@extends('layouts.admin')

@section('title', 'Data Pemilih & Import Excel')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-white">Daftar Pemilih Tetap (DPT)</h2>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">Kelola data anggota, hak suara (Pilih T/F), UID RFID Mifare, dan import bulk.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button onclick="openImportModal()" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold border border-slate-700 transition flex items-center space-x-2">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                <span>Import Excel / CSV</span>
            </button>

            <button onclick="openAddModal()" class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold shadow-lg shadow-blue-600/30 transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Tambah Pemilih</span>
            </button>

            <form action="{{ route('admin.voters.reset') }}" method="POST" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin ME-RESET SELURUH SUARA pemilihan? Semua data suara ketua & pengawas akan dikosongkan dan status pemilih dikembalikan ke Belum Memilih (F).')">
                @csrf
                <button type="submit" class="px-3.5 py-2.5 rounded-xl bg-rose-500/10 hover:bg-rose-500 text-rose-400 hover:text-white border border-rose-500/30 text-xs font-semibold transition" title="Reset Suara untuk Demo/Gladi">
                    Reset Suara
                </button>
            </form>
        </div>
    </div>

    <!-- Quick Stats Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800 flex items-center justify-between">
            <div>
                <span class="text-xs text-slate-400">Total Terdaftar</span>
                <strong class="block text-2xl font-black text-white font-mono">{{ $stats['total'] }}</strong>
            </div>
            <span class="p-2.5 rounded-xl bg-blue-500/10 text-blue-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </span>
        </div>

        <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800 flex items-center justify-between">
            <div>
                <span class="text-xs text-emerald-400">Sudah Memilih (Pilih = T)</span>
                <strong class="block text-2xl font-black text-emerald-400 font-mono">{{ $stats['voted'] }}</strong>
            </div>
            <span class="p-2.5 rounded-xl bg-emerald-500/10 text-emerald-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </span>
        </div>

        <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800 flex items-center justify-between">
            <div>
                <span class="text-xs text-amber-400">Belum Memilih (Pilih = F)</span>
                <strong class="block text-2xl font-black text-amber-400 font-mono">{{ $stats['not_voted'] }}</strong>
            </div>
            <span class="p-2.5 rounded-xl bg-amber-500/10 text-amber-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </span>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl flex flex-col sm:flex-row items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.voters.index') }}" class="flex flex-wrap items-center gap-3 w-full">
            <div class="relative flex-1 min-w-[200px]">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search }}"
                    placeholder="Cari NIK, Nama, Dept, atau RFID..." 
                    class="w-full pl-10 pr-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white placeholder-slate-500 outline-none focus:border-blue-500"
                >
                <svg class="w-4 h-4 text-slate-500 absolute left-3.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            <select name="dept" class="px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-xs text-slate-300 outline-none focus:border-blue-500">
                <option value="">Semua Departemen</option>
                @foreach($departments as $d)
                    <option value="{{ $d }}" {{ $dept == $d ? 'selected' : '' }}>{{ $d }}</option>
                @endforeach
            </select>

            <select name="status" class="px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-xs text-slate-300 outline-none focus:border-blue-500">
                <option value="">Status Memilih: Semua</option>
                <option value="T" {{ $status == 'T' ? 'selected' : '' }}>Sudah Memilih (T)</option>
                <option value="F" {{ $status == 'F' ? 'selected' : '' }}>Belum Memilih (F)</option>
            </select>

            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold transition">
                Filter Data
            </button>
            @if($search || $dept || $status)
                <a href="{{ route('admin.voters.index') }}" class="text-xs text-slate-500 hover:text-white transition">Reset Filter</a>
            @endif
        </form>
    </div>

    <!-- Voters Table -->
    <div class="rounded-3xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-950/50 text-slate-400 uppercase tracking-wider font-semibold">
                        <th class="py-3.5 px-4">NIK</th>
                        <th class="py-3.5 px-4">Nama Lengkap</th>
                        <th class="py-3.5 px-4">Departemen</th>
                        <th class="py-3.5 px-4">RFID UID</th>
                        <th class="py-3.5 px-4">Status Pilih</th>
                        <th class="py-3.5 px-4">Waktu Vote</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @forelse($voters as $voter)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="py-3 px-4 font-mono font-bold text-blue-400">{{ $voter->nik }}</td>
                            <td class="py-3 px-4 font-semibold text-white">{{ $voter->nama }}</td>
                            <td class="py-3 px-4">{{ $voter->dept }}</td>
                            <td class="py-3 px-4 font-mono text-slate-400">{{ $voter->rfid }}</td>
                            <td class="py-3 px-4">
                                @if($voter->pilih === 'T')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        Sudah (T)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-800 text-slate-400 border border-slate-700">
                                        Belum (F)
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-400">
                                {{ $voter->voted_at ? $voter->voted_at->format('d/m H:i') : '-' }}
                            </td>
                            <td class="py-3 px-4 text-right">
                                <form action="{{ route('admin.voters.destroy', $voter->nik) }}" method="POST" onsubmit="return confirm('Hapus pemilih {{ $voter->nama }}?')" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition" title="Hapus Pemilih">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500">Tidak ada data pemilih yang sesuai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            {{ $voters->links() }}
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL TAMBAH PEMILIH MANUAL                    -->
<!-- ============================================== -->
<div id="add-modal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl">
        <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-800">
            <h3 class="text-lg font-bold text-white">Tambah Pemilih Manual</h3>
            <button onclick="closeAddModal()" class="text-slate-400 hover:text-white">✕</button>
        </div>

        <form action="{{ route('admin.voters.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">NIK Pemilih</label>
                <input type="text" name="nik" required placeholder="Contoh: 102420" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-xs text-white focus:border-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Nama Anggota</label>
                <input type="text" name="nama" required placeholder="Contoh: Hendra Kusuma" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-xs text-white focus:border-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Departemen / Divisi</label>
                <input type="text" name="dept" required placeholder="Contoh: Operasional / Keuangan" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-xs text-white focus:border-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">RFID UID (Mifare ISO 14443A)</label>
                <input type="text" name="rfid" required placeholder="Contoh: E280681A atau 04A1B2C3" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-xs font-mono text-white focus:border-blue-500 outline-none uppercase">
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-800">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 rounded-xl bg-slate-800 text-xs text-slate-300">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-md">Simpan Pemilih</button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL IMPORT EXCEL / CSV                       -->
<!-- ============================================== -->
<div id="import-modal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-800">
            <h3 class="text-lg font-bold text-white">Import Data Pemilih (Excel / CSV)</h3>
            <button onclick="closeImportModal()" class="text-slate-400 hover:text-white">✕</button>
        </div>

        <p class="text-xs text-slate-400 mb-4 leading-relaxed">
            Format file harus menyertakan 4 kolom utama: <br>
            <strong class="text-slate-200 font-mono">nik, nama, dept, rfid</strong>
        </p>

        <div class="mb-5 p-3 rounded-xl bg-slate-950/60 border border-slate-800 flex items-center justify-between">
            <span class="text-xs text-slate-400">Unduh contoh format CSV siap pakai:</span>
            <a href="{{ route('admin.voters.template') }}" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-blue-400 text-xs font-semibold transition">
                Download Template CSV ↓
            </a>
        </div>

        <form action="{{ route('admin.voters.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="p-6 border-2 border-dashed border-slate-700 hover:border-blue-500 rounded-2xl text-center cursor-pointer bg-slate-950/40">
                <input type="file" name="file" accept=".csv, .txt, .xlsx, .xls" required class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white">
                <p class="text-[11px] text-slate-500 mt-2">Dukungan file: .csv, .txt (separator koma atau titik-koma)</p>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-800">
                <button type="button" onclick="closeImportModal()" class="px-4 py-2 rounded-xl bg-slate-800 text-xs text-slate-300">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md">Upload & Import</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openAddModal() {
        document.getElementById('add-modal').classList.remove('hidden');
    }
    function closeAddModal() {
        document.getElementById('add-modal').classList.add('hidden');
    }
    function openImportModal() {
        document.getElementById('import-modal').classList.remove('hidden');
    }
    function closeImportModal() {
        document.getElementById('import-modal').classList.add('hidden');
    }
</script>
@endpush
@endsection
