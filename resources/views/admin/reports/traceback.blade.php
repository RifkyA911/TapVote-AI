@extends('layouts.admin')

@section('title', 'Laporan Trace Back Pilihan Anggota')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/20 text-indigo-400">Audit Trail 03</span>
                <h2 class="text-2xl font-black text-white">Trace Back Pilihan Anggota</h2>
            </div>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">Audit rekonsiliasi saksi: Melacak anggota memilih calon ketua dan pengawas siapa.</p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.reports.traceback.export') }}" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold border border-slate-700 transition flex items-center space-x-2">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Export Audit CSV</span>
            </a>
            <button onclick="window.print()" class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold shadow-lg shadow-blue-600/30 transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak Rekap</span>
            </button>
        </div>
    </div>

    <!-- Search Box -->
    <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl">
        <form method="GET" action="{{ route('admin.reports.traceback') }}" class="flex items-center gap-3">
            <div class="relative flex-1">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search }}"
                    placeholder="Cari berdasarkan NIK, Nama Anggota, atau Departemen..." 
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white placeholder-slate-500 outline-none focus:border-blue-500"
                >
                <svg class="w-4 h-4 text-slate-500 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold transition">
                Cari Data
            </button>
            @if($search)
                <a href="{{ route('admin.reports.traceback') }}" class="text-xs text-slate-500 hover:text-white transition">Reset</a>
            @endif
        </form>
    </div>

    <!-- Trace Back Table -->
    <div class="rounded-3xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl shadow-xl overflow-hidden">
        <div class="p-5 border-b border-slate-800 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-white">Log Pilihan Individu Anggota</h3>
                <p class="text-xs text-slate-400">Total {{ $totalVoted }} anggota yang telah memberikan hak suaranya.</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                Pilih (T) Terverifikasi
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-950/50 text-slate-400 uppercase tracking-wider font-semibold">
                        <th class="py-3.5 px-4">Waktu Vote</th>
                        <th class="py-3.5 px-4">NIK Anggota</th>
                        <th class="py-3.5 px-4">Nama Lengkap</th>
                        <th class="py-3.5 px-4">Departemen</th>
                        <th class="py-3.5 px-4">Pilihan Ketua Koperasi</th>
                        <th class="py-3.5 px-4">Pilihan Pengawas Koperasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @forelse($records as $rec)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="py-3 px-4 font-mono text-[11px] text-slate-400">
                                {{ $rec->voted_at ? $rec->voted_at->format('d/m/Y H:i:s') : '-' }}
                            </td>
                            <td class="py-3 px-4 font-mono font-bold text-blue-400">{{ $rec->nik }}</td>
                            <td class="py-3 px-4 font-semibold text-white">{{ $rec->nama }}</td>
                            <td class="py-3 px-4 text-slate-400">{{ $rec->dept }}</td>

                            <!-- Pilihan Ketua -->
                            <td class="py-3 px-4">
                                @if($rec->hasilKetua && $rec->hasilKetua->kandidatKetua)
                                    <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-lg bg-blue-500/10 text-blue-300 border border-blue-500/20 font-medium">
                                        <span class="font-mono font-bold">No. {{ $rec->hasilKetua->kandidatKetua->nomor_urut }}</span>
                                        <span>• {{ $rec->hasilKetua->kandidatKetua->nama }}</span>
                                    </span>
                                @else
                                    <span class="text-slate-600">-</span>
                                @endif
                            </td>

                            <!-- Pilihan Pengawas -->
                            <td class="py-3 px-4">
                                @if($rec->hasilPengawas && $rec->hasilPengawas->kandidatPengawas)
                                    <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-lg bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 font-medium">
                                        <span class="font-mono font-bold">No. {{ $rec->hasilPengawas->kandidatPengawas->nomor_urut }}</span>
                                        <span>• {{ $rec->hasilPengawas->kandidatPengawas->nama }}</span>
                                    </span>
                                @else
                                    <span class="text-slate-600">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">Belum ada suara yang masuk untuk dilacak.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            {{ $records->links() }}
        </div>
    </div>
</div>
@endsection
