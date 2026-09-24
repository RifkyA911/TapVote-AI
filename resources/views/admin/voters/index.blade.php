@extends('layouts.admin')

@section('title', 'Data Pemilih & Import Excel')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900">Daftar Pemilih Tetap (DPT)</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Kelola data anggota, hak suara (Pilih T/F), UID RFID Mifare, dan import bulk.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button onclick="openImportModal()" class="px-4 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold border border-slate-300 shadow-2xs transition flex items-center space-x-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                <span>Import Excel / CSV</span>
            </button>

            <button onclick="openAddModal()" class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Tambah Pemilih</span>
            </button>

            <form action="{{ route('admin.voters.reset') }}" method="POST" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin ME-RESET SELURUH SUARA pemilihan? Semua data suara ketua & pengawas akan dikosongkan dan status pemilih dikembalikan ke Belum Memilih (F).')">
                @csrf
                <button type="submit" class="px-3.5 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-semibold transition" title="Reset Suara untuk Demo/Gladi">
                    Reset Suara
                </button>
            </form>
        </div>
    </div>

    <!-- Quick Stats Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-xs text-slate-500 font-semibold">Total Terdaftar</span>
                <strong class="block text-2xl font-black text-slate-900 font-mono">{{ $stats['total'] }}</strong>
            </div>
            <span class="p-2.5 rounded-xl bg-blue-50 text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </span>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-xs text-emerald-700 font-semibold">Sudah Memilih (Pilih = T)</span>
                <strong class="block text-2xl font-black text-emerald-600 font-mono">{{ $stats['voted'] }}</strong>
            </div>
            <span class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </span>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-xs text-amber-700 font-semibold">Belum Memilih (Pilih = F)</span>
                <strong class="block text-2xl font-black text-amber-600 font-mono">{{ $stats['not_voted'] }}</strong>
            </div>
            <span class="p-2.5 rounded-xl bg-amber-50 text-amber-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </span>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex flex-col sm:flex-row items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.voters.index') }}" class="flex flex-wrap items-center gap-3 w-full">
            <div class="relative flex-1 min-w-[200px]">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari NIK, Nama, Dept, atau RFID..."
                    class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:bg-white outline-none transition"
                >
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            <select name="status" class="px-3 py-2 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-700 focus:border-blue-500 outline-none">
                <option value="">Semua Status Hak Suara</option>
                <option value="T" {{ request('status') === 'T' ? 'selected' : '' }}>Sudah Memilih (T)</option>
                <option value="F" {{ request('status') === 'F' ? 'selected' : '' }}>Belum Memilih (F)</option>
            </select>

            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold transition">
                Filter Data
            </button>
            
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.voters.index') }}" class="text-xs text-slate-500 hover:text-slate-800 font-medium">Reset</a>
            @endif
        </form>
    </div>

    <!-- Voters Table -->
    <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                        <th class="py-3 px-3">NIK</th>
                        <th class="py-3 px-3">Nama Pemilih</th>
                        <th class="py-3 px-3">Departemen</th>
                        <th class="py-3 px-3">RFID UID (Mifare)</th>
                        <th class="py-3 px-3 text-center">Status Suara</th>
                        <th class="py-3 px-3 text-center">Waktu Vote</th>
                        <th class="py-3 px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($voters as $v)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-3 font-mono font-bold text-blue-700">{{ $v->nik }}</td>
                            <td class="py-3 px-3 font-bold text-slate-900">{{ $v->nama }}</td>
                            <td class="py-3 px-3 text-slate-600">{{ $v->dept }}</td>
                            <td class="py-3 px-3 font-mono text-slate-700 bg-slate-50 rounded px-2 py-0.5 inline-block my-1 border border-slate-200">{{ $v->rfid }}</td>
                            <td class="py-3 px-3 text-center">
                                @if($v->pilih === 'T')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        Sudah Memilih (T)
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        Belum (F)
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-center font-mono text-slate-500 text-xs">
                                {{ $v->voted_at ? $v->voted_at->format('H:i:s d/m/Y') : '-' }}
                            </td>
                            <td class="py-3 px-3 text-right">
                                <form action="{{ route('admin.voters.destroy', $v->nik) }}" method="POST" onsubmit="return confirm('Hapus pemilih {{ $v->nama }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50 transition" title="Hapus Pemilih">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">Tidak ada data pemilih yang sesuai kriteria pencarian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-200">
            {{ $voters->links() }}
        </div>
    </div>

</div>

<!-- Modal Tambah Pemilih Manual -->
<div id="add-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl max-w-md w-full p-6 shadow-2xl">
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-200">
            <h3 class="text-lg font-bold text-slate-900">Tambah Pemilih Baru</h3>
            <button onclick="closeAddModal()" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>

        <form action="{{ route('admin.voters.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">NIK (Nomor Induk Anggota)</label>
                <input type="text" name="nik" required placeholder="Contoh: 2024099" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 focus:border-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" name="nama" required placeholder="Nama anggota koperasi..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 focus:border-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Departemen / Bagian</label>
                <input type="text" name="dept" required placeholder="Contoh: Logistik / Keuangan" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 focus:border-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">RFID UID Mifare</label>
                <input type="text" name="rfid" required placeholder="Tempelkan pada reader atau input hex..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 font-mono focus:border-blue-500 outline-none">
            </div>

            <div class="pt-3 border-t border-slate-200 flex justify-end space-x-2">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Import Excel / CSV -->
<div id="import-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl max-w-md w-full p-6 shadow-2xl">
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-200">
            <h3 class="text-lg font-bold text-slate-900">Import Data Pemilih (DPT)</h3>
            <button onclick="closeImportModal()" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>

        <form action="{{ route('admin.voters.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Pilih File CSV / Excel (.csv, .xlsx, .xls)</label>
                <input type="file" name="file" required accept=".csv,.xlsx,.xls" class="w-full px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <div class="p-3 rounded-xl bg-blue-50 border border-blue-200 text-xs text-blue-900">
                Format kolom wajib: <strong>nik, rfid, nama, dept</strong>.
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-slate-200">
                <a href="{{ route('admin.voters.template') }}" class="text-xs font-bold text-blue-700 hover:underline">
                    ↓ Unduh Template CSV
                </a>
                <div class="flex space-x-2">
                    <button type="button" onclick="closeImportModal()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow">Mulai Import</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
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
</script>
@endpush
@endsection
