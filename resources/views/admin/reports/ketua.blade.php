@extends('layouts.admin')

@section('title', 'Laporan Pemenang Ketua Koperasi')

@section('content')
<div class="space-y-8">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-500/20 text-blue-400">Laporan Resmi 01</span>
                <h2 class="text-2xl font-black text-white">Hasil & Pemenang Ketua Koperasi</h2>
            </div>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">Perolehan suara resmi, penetapan calon ketua terpilih, dan rincian suara.</p>
        </div>

        <button onclick="window.print()" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold border border-slate-700 transition flex items-center space-x-2 self-start sm:self-auto">
            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            <span>Cetak Dokumen Laporan</span>
        </button>
    </div>

    <!-- Winner Showcase Banner -->
    @if($pemenang && $pemenang->perolehan_suara_count > 0)
        @php
            $persenPemenang = $totalSuara > 0 ? round(($pemenang->perolehan_suara_count / $totalSuara) * 100, 2) : 0;
        @endphp
        <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-blue-950/80 via-indigo-950/60 to-slate-900 border-2 border-blue-500/40 backdrop-blur-xl shadow-2xl relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl"></div>

            <div class="flex flex-col sm:flex-row items-center gap-6 relative z-10">
                <div class="relative">
                    <div class="w-32 h-32 rounded-3xl overflow-hidden bg-slate-950 border-2 border-blue-400 shadow-xl">
                        <img 
                            src="{{ $pemenang->foto ?: 'https://ui-avatars.com/api/?name='.urlencode($pemenang->nama).'&background=1e293b&color=3b82f6&size=400' }}" 
                            alt="{{ $pemenang->nama }}" 
                            class="w-full h-full object-cover object-top"
                        >
                    </div>
                    <span class="absolute -bottom-2 -right-2 px-3 py-1 rounded-full bg-blue-600 text-white font-mono font-bold text-xs shadow-md">
                        No. {{ $pemenang->nomor_urut }}
                    </span>
                </div>

                <div class="text-center sm:text-left flex-1">
                    <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-extrabold uppercase tracking-wider mb-2">
                        <span>🏆</span>
                        <span>Kandidat Terpilih (Suara Terbanyak)</span>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-black text-white">{{ $pemenang->nama }}</h3>
                    <p class="text-xs text-blue-300 font-mono mt-0.5">NIK: {{ $pemenang->nik }}</p>

                    <div class="flex flex-wrap items-center gap-6 mt-4 pt-4 border-t border-blue-900/60">
                        <div>
                            <span class="text-[11px] uppercase tracking-wider text-slate-400 block font-semibold">Total Suara</span>
                            <strong class="text-2xl font-black text-white font-mono">{{ $pemenang->perolehan_suara_count }} Suara</strong>
                        </div>
                        <div class="h-8 w-px bg-slate-800"></div>
                        <div>
                            <span class="text-[11px] uppercase tracking-wider text-slate-400 block font-semibold">Persentase</span>
                            <strong class="text-2xl font-black text-blue-400 font-mono">{{ $persenPemenang }}%</strong>
                        </div>
                        <div class="h-8 w-px bg-slate-800"></div>
                        <div>
                            <span class="text-[11px] uppercase tracking-wider text-slate-400 block font-semibold">Total Partisipasi</span>
                            <strong class="text-2xl font-black text-slate-300 font-mono">{{ $totalSuara }} Suara Sah</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="p-8 rounded-3xl bg-slate-900/60 border border-slate-800 text-center text-slate-400">
            Belum ada suara pemilihan ketua yang masuk ke sistem.
        </div>
    @endif

    <!-- Breakdown Table: Tiap Calon Ketua Dapat Berapa Suara -->
    <div class="rounded-3xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl shadow-xl overflow-hidden">
        <div class="p-5 border-b border-slate-800">
            <h3 class="text-base font-bold text-white">Rincian Perolehan Suara Seluruh Calon Ketua</h3>
            <p class="text-xs text-slate-400">Tabel komparasi suara dan persentase dari total {{ $totalSuara }} suara sah.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-950/50 text-slate-400 uppercase tracking-wider font-semibold">
                        <th class="py-3.5 px-5">Peringkat</th>
                        <th class="py-3.5 px-5">No. Urut</th>
                        <th class="py-3.5 px-5">Nama Calon Ketua</th>
                        <th class="py-3.5 px-5">NIK</th>
                        <th class="py-3.5 px-5">Perolehan Suara</th>
                        <th class="py-3.5 px-5">Persentase</th>
                        <th class="py-3.5 px-5">Visual Bar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @foreach($kandidatList as $idx => $k)
                        @php
                            $pct = $totalSuara > 0 ? round(($k->perolehan_suara_count / $totalSuara) * 100, 2) : 0;
                        @endphp
                        <tr class="{{ $idx === 0 && $k->perolehan_suara_count > 0 ? 'bg-blue-950/20' : '' }} hover:bg-slate-800/30 transition">
                            <td class="py-4 px-5">
                                @if($idx === 0 && $k->perolehan_suara_count > 0)
                                    <span class="w-6 h-6 rounded-full bg-amber-500/20 text-amber-300 font-bold text-xs inline-flex items-center justify-center">1</span>
                                @else
                                    <span class="w-6 h-6 rounded-full bg-slate-800 text-slate-400 font-bold text-xs inline-flex items-center justify-center">{{ $idx + 1 }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-5 font-mono font-bold text-blue-400">No. {{ $k->nomor_urut }}</td>
                            <td class="py-4 px-5 font-bold text-white text-sm">
                                {{ $k->nama }}
                                @if($idx === 0 && $k->perolehan_suara_count > 0)
                                    <span class="ml-2 text-[10px] px-2 py-0.5 rounded-full bg-blue-500/20 text-blue-300 font-normal">Pemenang</span>
                                @endif
                            </td>
                            <td class="py-4 px-5 font-mono text-slate-400">{{ $k->nik }}</td>
                            <td class="py-4 px-5 font-mono font-black text-white text-sm">{{ $k->perolehan_suara_count }} Suara</td>
                            <td class="py-4 px-5 font-mono font-bold text-blue-400">{{ $pct }}%</td>
                            <td class="py-4 px-5 w-48">
                                <div class="w-full bg-slate-800 h-2.5 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-500 rounded-full" style="width: {{ $pct }}%;"></div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Rincian Suara per Departemen -->
    <div class="rounded-3xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl shadow-xl p-6">
        <h3 class="text-base font-bold text-white mb-1">Rincian Sebaran Suara Berdasarkan Departemen</h3>
        <p class="text-xs text-slate-400 mb-6">Melihat distribusi preferensi suara masing-masing divisi/departemen.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($deptBreakdown->groupBy('dept') as $deptName => $votes)
                <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800">
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-3 pb-2 border-b border-slate-800 flex items-center justify-between">
                        <span>{{ $deptName }}</span>
                        <span class="text-slate-400 font-mono">{{ $votes->sum('total') }} Suara</span>
                    </h4>
                    <div class="space-y-2">
                        @foreach($votes as $v)
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-300 truncate max-w-[180px]">No. {{ $v->nomor_urut }} {{ $v->kandidat_nama }}</span>
                                <strong class="text-blue-400 font-mono">{{ $v->total }}</strong>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-6 text-slate-500 text-xs">Belum ada data distribusi suara per departemen.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
