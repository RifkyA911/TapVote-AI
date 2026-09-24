@extends('layouts.admin')

@section('title', 'Laporan Pemenang Ketua Koperasi')

@section('content')
<div class="space-y-8">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">Laporan Resmi 01</span>
                <h2 class="text-2xl font-extrabold text-slate-900">Hasil & Pemenang Ketua Koperasi</h2>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Perolehan suara resmi, penetapan calon ketua terpilih, dan rincian suara.</p>
        </div>

        <div class="flex items-center gap-2.5 self-start sm:self-auto">
            <a href="{{ route('admin.reports.ketua.export') }}" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition flex items-center space-x-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>{{ __('Export Excel') }}</span>
            </a>

            <button onclick="window.print()" type="button" class="px-4 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold border border-slate-300 shadow-2xs transition flex items-center space-x-1.5 cursor-pointer">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>{{ __('Export PDF / Print') }}</span>
            </button>
        </div>
    </div>

    <!-- Winner Showcase Banner -->
    @if($pemenang && $pemenang->perolehan_suara_count > 0)
        @php
            $persenPemenang = $totalSuara > 0 ? round(($pemenang->perolehan_suara_count / $totalSuara) * 100, 2) : 0;
        @endphp
        <div class="p-6 sm:p-8 rounded-3xl bg-blue-50/80 border-2 border-blue-200 shadow-sm relative overflow-hidden">
            <div class="flex flex-col sm:flex-row items-center gap-6 relative z-10">
                <div class="relative">
                    <div class="w-32 h-32 rounded-3xl overflow-hidden bg-slate-100 border-2 border-blue-500 shadow-md">
                        <img 
                            src="{{ $pemenang->foto ?: 'https://ui-avatars.com/api/?name='.urlencode($pemenang->nama).'&background=2563eb&color=ffffff&size=400' }}" 
                            alt="{{ $pemenang->nama }}" 
                            class="w-full h-full object-cover object-top"
                        >
                    </div>
                    <span class="absolute -bottom-2 -right-2 px-3 py-1 rounded-full bg-blue-600 text-white font-mono font-bold text-xs shadow-md">
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
                            <span class="text-[11px] uppercase tracking-wider text-slate-500 block font-semibold">Total Suara</span>
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

    <!-- Breakdown Table -->
    <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-2xs">
        <h3 class="text-base font-bold text-slate-900 mb-4">Tabel Rekapitulasi Suara Ketua</h3>

        <div class="overflow-x-auto">
            <table class="datatable w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                        <th class="py-3 px-3">No. Urut</th>
                        <th class="py-3 px-3">Nama Kandidat</th>
                        <th class="py-3 px-3">NIK</th>
                        <th class="py-3 px-3 text-right">Perolehan Suara</th>
                        <th class="py-3 px-3 text-right">Persentase</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($kandidatKetua as $k)
                        @php
                            $persen = $totalSuara > 0 ? round(($k->perolehan_suara_count / $totalSuara) * 100, 2) : 0;
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-3 font-bold text-slate-900">{{ $k->nomor_urut }}</td>
                            <td class="py-3 px-3 font-bold text-slate-900">{{ $k->nama }}</td>
                            <td class="py-3 px-3 text-slate-500 font-mono">{{ $k->nik }}</td>
                            <td class="py-3 px-3 text-right font-bold text-slate-900">{{ $k->perolehan_suara_count }} Suara</td>
                            <td class="py-3 px-3 text-right font-extrabold text-blue-600">{{ $persen }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
