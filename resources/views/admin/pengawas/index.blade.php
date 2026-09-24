@extends('layouts.admin')

@section('title', 'Kandidat Pengawas Koperasi')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900">Manajemen Calon Pengawas Koperasi</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Kelola data resmi, visi, misi, dan nomor urut calon Pengawas Koperasi.</p>
        </div>

        <a href="{{ route('admin.pengawas.create') }}" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition flex items-center space-x-2 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Calon Pengawas</span>
        </a>
    </div>

    <!-- Candidate Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($kandidat as $p)
            <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-2xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center text-lg">
                            {{ str_pad($p->nomor_urut, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                            {{ $p->perolehan_suara_count }} Suara
                        </span>
                    </div>

                    <div class="w-full h-48 rounded-2xl overflow-hidden bg-slate-100 mb-4 border border-slate-200">
                        <img 
                            src="{{ $p->foto ?: 'https://ui-avatars.com/api/?name='.urlencode($p->nama).'&background=059669&color=ffffff&size=400' }}" 
                            alt="{{ $p->nama }}" 
                            class="w-full h-full object-cover object-top"
                        >
                    </div>

                    <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-1">{{ $p->nama }}</h3>
                    <p class="text-xs text-emerald-700 font-mono mb-3">NIK: {{ $p->nik }}</p>

                    <div class="space-y-2 text-xs text-slate-600">
                        <div>
                            <strong class="text-slate-800 block">Visi:</strong>
                            <p class="line-clamp-2 text-xs">{{ $p->visi }}</p>
                        </div>
                        <div>
                            <strong class="text-slate-800 block">Misi:</strong>
                            <p class="line-clamp-2 text-xs">{{ $p->misi }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('admin.pengawas.edit', $p->nik) }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">
                        Edit Data
                    </a>

                    <form action="{{ route('admin.pengawas.destroy', $p->nik) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kandidat {{ $p->nama }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 rounded-xl text-rose-600 hover:bg-rose-50 transition" title="Hapus Calon">
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
