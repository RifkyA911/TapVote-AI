@extends('layouts.admin')

@section('title', 'Kandidat Pengawas Koperasi')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-white">Manajemen Calon Pengawas Koperasi</h2>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">Kelola data resmi, visi, misi, dan nomor urut calon Pengawas Koperasi.</p>
        </div>

        <a href="{{ route('admin.pengawas.create') }}" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg shadow-emerald-600/30 transition flex items-center space-x-2 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Calon Pengawas</span>
        </a>
    </div>

    <!-- Candidate Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($kandidat as $p)
            <div class="p-6 rounded-3xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl shadow-xl flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="w-10 h-10 rounded-2xl bg-emerald-600/20 text-emerald-400 border border-emerald-500/40 font-mono font-bold flex items-center justify-center text-lg">
                            {{ str_pad($p->nomor_urut, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <span class="px-2.5 py-1 rounded-full text-xs font-mono font-semibold bg-slate-800 text-slate-300">
                            {{ $p->perolehan_suara_count }} Suara
                        </span>
                    </div>

                    <div class="w-full h-48 rounded-2xl overflow-hidden bg-slate-950 mb-4 border border-slate-800">
                        <img 
                            src="{{ $p->foto ?: 'https://ui-avatars.com/api/?name='.urlencode($p->nama).'&background=1e293b&color=10b981&size=400' }}" 
                            alt="{{ $p->nama }}" 
                            class="w-full h-full object-cover object-top"
                        >
                    </div>

                    <h3 class="text-lg font-bold text-white mb-1">{{ $p->nama }}</h3>
                    <p class="text-xs font-mono text-emerald-400 mb-3">NIK: {{ $p->nik }}</p>

                    <div class="space-y-2 text-xs text-slate-400">
                        <div>
                            <strong class="text-slate-300 block">Visi:</strong>
                            <p class="line-clamp-2 text-[11px]">{{ $p->visi }}</p>
                        </div>
                        <div>
                            <strong class="text-slate-300 block">Misi:</strong>
                            <p class="line-clamp-2 text-[11px]">{{ $p->misi }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-800/80 flex items-center justify-between">
                    <a href="{{ route('admin.pengawas.edit', $p->nik) }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold transition">
                        Edit Data
                    </a>

                    <form action="{{ route('admin.pengawas.destroy', $p->nik) }}" method="POST" onsubmit="return confirm('Hapus kandidat {{ $p->nama }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 rounded-xl bg-rose-500/10 hover:bg-rose-500 text-rose-400 hover:text-white text-xs font-semibold transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-3 p-12 text-center rounded-3xl bg-slate-900/60 border border-slate-800 text-slate-400">
                Belum ada data calon Pengawas Koperasi.
            </div>
        @endforelse
    </div>
</div>
@endsection
