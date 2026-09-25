@extends('layouts.admin')

@section('title', 'Kandidat Pengawas Koperasi')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900">Manajemen Calon Pengawas Koperasi</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Kelola data resmi, visi, misi, dan nomor urut calon Pengawas Koperasi.</p>
        </div>

        <a href="{{ route('admin.pengawas.create') }}" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition flex items-center space-x-2 self-start sm:self-auto cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Calon Pengawas</span>
        </a>
    </div>

    <!-- Candidate Cards Grid (Serasi dengan Format Rasio 3.5:5 & Badge Putih Kios Suara) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        @forelse($kandidat as $p)
            @php
                $fotoKandidat = $p->foto ?: 'https://ui-avatars.com/api/?name='.urlencode($p->nama).'&background=059669&color=ffffff&size=400';
            @endphp
            <div class="rounded-3xl bg-white border-2 border-slate-200 hover:border-emerald-400 overflow-hidden shadow-2xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                <div>
                    <!-- Foto Card: Rasio 3.5:5 Full-Bleed Sesuai Permintaan -->
                    <div class="relative w-full aspect-[3.5/5] max-h-[380px] bg-slate-900 overflow-hidden">
                        <img 
                            src="{{ $fotoKandidat }}" 
                            alt="{{ $p->nama }}" 
                            class="w-full h-full object-cover object-top hover:scale-105 transition-transform duration-500 ease-out"
                        >
                        <!-- Gradient Overlay Tipis -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-black/20 pointer-events-none"></div>

                        <!-- Badge Nomor Urut Absolute Kiri Atas: Background Putih, Font Hitam Kontras -->
                        <div class="absolute top-3.5 left-3.5 z-10">
                            <div class="w-13 h-13 sm:w-15 sm:h-15 rounded-2xl bg-white text-slate-950 border-2 border-slate-300 shadow-xl flex items-center justify-center text-2xl sm:text-3xl font-black ring-4 ring-black/15">
                                {{ $p->nomor_urut }}
                            </div>
                        </div>

                        <!-- Perolehan Suara Pill Kanan Atas -->
                        <div class="absolute top-3.5 right-3.5 z-10">
                            <span class="px-3.5 py-1.5 rounded-xl text-xs font-black bg-white/95 backdrop-blur-xs text-slate-900 shadow-md border border-slate-200">
                                {{ $p->perolehan_suara_count }} Suara
                            </span>
                        </div>
                    </div>

                    <!-- Identitas Calon: Font Elegan, Besar & Kontras -->
                    <div class="p-5 pb-3">
                        <h3 class="text-xl sm:text-2xl font-black text-slate-950 tracking-tight leading-snug">{{ $p->nama }}</h3>
                        <div class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 border border-emerald-200 text-xs sm:text-sm font-mono font-bold text-emerald-800 mt-1.5">
                            <span>NIK:</span>
                            <span>{{ $p->nik }}</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Action Buttons -->
                <div class="p-5 pt-0 flex items-center gap-3">
                    <a href="{{ route('admin.pengawas.edit', $p->nik) }}" class="flex-1 py-3 px-4 rounded-2xl bg-slate-100 hover:bg-emerald-50 hover:text-emerald-800 text-slate-800 text-xs sm:text-sm font-bold border border-slate-200 transition text-center flex items-center justify-center space-x-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <span>Edit Data Calon</span>
                    </a>

                    <form action="{{ route('admin.pengawas.destroy', $p->nik) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kandidat {{ $p->nama }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-3 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 transition cursor-pointer" title="Hapus Calon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full p-12 text-center rounded-3xl bg-white border border-slate-200 text-slate-400">
                Belum ada data kandidat Pengawas. Silakan klik tombol Tambah.
            </div>
        @endforelse
    </div>
</div>
@endsection
