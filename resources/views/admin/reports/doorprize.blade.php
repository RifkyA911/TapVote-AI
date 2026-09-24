@extends('layouts.admin')

@section('title', 'Undian Doorprize Pemilih')

@section('content')
<div class="space-y-8">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">Modul Doorprize</span>
                <h2 class="text-2xl font-extrabold text-slate-900">Undian Doorprize Anggota</h2>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Undian acak khusus anggota yang telah menunaikan hak suara (Pilih = T).</p>
        </div>

        <button onclick="window.location.reload()" class="px-4 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold border border-slate-300 shadow-2xs transition flex items-center space-x-2 self-start sm:self-auto">
            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            <span>Kocok Ulang / Refresh</span>
        </button>
    </div>

    <!-- Random Draw Slot Showcase -->
    @if($pemenang)
        <div class="p-8 sm:p-12 rounded-3xl bg-amber-50/80 border-2 border-amber-300 shadow-sm text-center relative overflow-hidden">
            <div class="max-w-md mx-auto relative z-10">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wider bg-amber-200 text-amber-900 mb-4">
                    🎉 Pemenang Doorprize Terpilih!
                </span>

                <h3 class="text-3xl sm:text-4xl font-black text-slate-900 mb-2">{{ $pemenang->nama }}</h3>
                <p class="text-base text-amber-800 font-bold mb-4 font-mono">NIK: {{ $pemenang->nik }} • Bagian: {{ $pemenang->dept }}</p>

                <div class="inline-flex items-center px-4 py-2 rounded-xl bg-white border border-amber-200 text-xs text-slate-600 font-medium">
                    Waktu Memilih: {{ $pemenang->voted_at ? $pemenang->voted_at->format('H:i:s d/m/Y') : '-' }}
                </div>
            </div>
        </div>
    @else
        <div class="p-12 text-center rounded-3xl bg-white border border-slate-200 text-slate-400">
            Belum ada pemilih yang memberikan suara untuk diundi.
        </div>
    @endif

    <!-- Eligible Pool Table -->
    <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-2xs">
        <h3 class="text-base font-bold text-slate-900 mb-4">Daftar Anggota Berhak Undian (Pool: {{ $eligibleVoters->total() }} Pemilih)</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                        <th class="py-3 px-3">NIK</th>
                        <th class="py-3 px-3">Nama Anggota</th>
                        <th class="py-3 px-3">Departemen</th>
                        <th class="py-3 px-3 text-right">Waktu Memilih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($eligibleVoters as $v)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-3 font-mono text-slate-700 font-bold">{{ $v->nik }}</td>
                            <td class="py-3 px-3 font-bold text-slate-900">{{ $v->nama }}</td>
                            <td class="py-3 px-3 text-slate-600">{{ $v->dept }}</td>
                            <td class="py-3 px-3 text-right font-mono text-slate-500">{{ $v->voted_at ? $v->voted_at->format('H:i:s d/m/Y') : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-200">
            {{ $eligibleVoters->links() }}
        </div>
    </div>

</div>
@endsection
