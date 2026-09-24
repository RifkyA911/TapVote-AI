@extends('layouts.admin')

@section('title', 'Edit Kandidat Ketua')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-white">Edit Calon Ketua: {{ $kandidat->nama }}</h2>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">Perbarui nomor urut, visi, misi, dan profil.</p>
        </div>
        <a href="{{ route('admin.ketua.index') }}" class="text-xs text-slate-400 hover:text-white transition">← Kembali</a>
    </div>

    <div class="p-8 rounded-3xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl shadow-xl">
        <form action="{{ route('admin.ketua.update', $kandidat->nik) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Nomor Urut</label>
                    <input type="number" name="nomor_urut" value="{{ old('nomor_urut', $kandidat->nomor_urut) }}" required min="1" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white focus:border-blue-500 outline-none">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">NIK (Kunci Utama)</label>
                    <input type="text" value="{{ $kandidat->nik }}" disabled class="w-full px-4 py-3 rounded-xl bg-slate-950/50 border border-slate-800 text-sm text-slate-500 font-mono outline-none cursor-not-allowed">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Nama Lengkap & Gelar</label>
                <input type="text" name="nama" value="{{ old('nama', $kandidat->nama) }}" required class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white focus:border-blue-500 outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 items-center">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Ganti Foto (File)</label>
                    <input type="file" name="foto" accept="image/*" class="w-full text-xs text-slate-400 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Atau URL Foto</label>
                    <input type="url" name="foto_url" value="{{ old('foto_url', str_starts_with($kandidat->foto ?? '', 'http') ? $kandidat->foto : '') }}" placeholder="https://..." class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-xs text-white focus:border-blue-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">
                    Visi Kandidat <span class="text-blue-400 font-mono text-[10px]">(Tipe: TEXT)</span>
                </label>
                <textarea name="visi" rows="3" required class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white focus:border-blue-500 outline-none">{{ old('visi', $kandidat->visi) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">
                    Misi Kandidat <span class="text-blue-400 font-mono text-[10px]">(Tipe: TEXT)</span>
                </label>
                <textarea name="misi" rows="4" required class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white focus:border-blue-500 outline-none">{{ old('misi', $kandidat->misi) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Deskripsi Ringkas / Profil Tambahan</label>
                <textarea name="deskripsi" rows="2" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white focus:border-blue-500 outline-none">{{ old('deskripsi', $kandidat->deskripsi) }}</textarea>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-800">
                <a href="{{ route('admin.ketua.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-lg shadow-blue-600/30 transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
