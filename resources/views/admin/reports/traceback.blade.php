@extends('layouts.admin')

@section('title', 'Trace Back Pilihan Suara')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">Audit Forensik</span>
                <h2 class="text-2xl font-extrabold text-slate-900">Trace Back Rekapitulasi Suara</h2>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Audit log pemilih dan pasangan pilihan calon (Ketua & Pengawas) per transaksi.</p>
        </div>

        <div class="flex items-center gap-2.5 self-start sm:self-auto">
            <a href="{{ route('admin.reports.traceback.export') }}" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition flex items-center space-x-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>{{ __('Export Excel') }}</span>
            </a>

            <button onclick="window.print()" type="button" class="px-4 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold border border-slate-300 shadow-2xs transition flex items-center space-x-1.5 cursor-pointer">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>{{ __('Export PDF / Print') }}</span>
            </button>
        </div>
    </div>

    <!-- Trace Back Table -->
    <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-2xs">
        <div class="overflow-x-auto">
            <table class="datatable w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                        <th class="py-3 px-3">Waktu Vote</th>
                        <th class="py-3 px-3">NIK Pemilih</th>
                        <th class="py-3 px-3">Nama Pemilih</th>
                        <th class="py-3 px-3">Departemen</th>
                        <th class="py-3 px-3">Pilihan Calon Ketua</th>
                        <th class="py-3 px-3">Pilihan Calon Pengawas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($voters as $v)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-3 font-mono text-slate-500 text-xs">{{ $v->voted_at ? $v->voted_at->format('H:i:s d/m/Y') : '-' }}</td>
                            <td class="py-3 px-3 font-mono font-bold text-slate-700">{{ $v->nik }}</td>
                            <td class="py-3 px-3 font-bold text-slate-900">{{ $v->nama }}</td>
                            <td class="py-3 px-3 text-slate-600">{{ $v->dept }}</td>
                            <td class="py-3 px-3">
                                @if($v->hasilKetua && $v->hasilKetua->kandidatKetua)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-50 text-blue-800 text-xs font-bold border border-blue-200">
                                        No. {{ $v->hasilKetua->kandidatKetua->nomor_urut }} - {{ $v->hasilKetua->kandidatKetua->nama }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic">Belum memilih</span>
                                @endif
                            </td>
                            <td class="py-3 px-3">
                                @if($v->hasilPengawas && $v->hasilPengawas->kandidatPengawas)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 text-xs font-bold border border-emerald-200">
                                        No. {{ $v->hasilPengawas->kandidatPengawas->nomor_urut }} - {{ $v->hasilPengawas->kandidatPengawas->nama }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic">Belum memilih</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Belum ada pemilih yang memberikan suara.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
