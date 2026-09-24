@extends('layouts.admin')

@section('title', 'Edit Kandidat Ketua')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900">Edit Calon Ketua: {{ $kandidat->nama }}</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Perbarui nomor urut, visi, misi, atau foto calon.</p>
        </div>
        <a href="{{ route('admin.ketua.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">
            ← Kembali
        </a>
    </div>

    <div class="p-8 rounded-3xl bg-white border border-slate-200 shadow-2xs">
        <form action="{{ route('admin.ketua.update', $kandidat->nik) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Urut</label>
                    <input type="number" name="nomor_urut" value="{{ old('nomor_urut', $kandidat->nomor_urut) }}" required min="1" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">NIK (Kunci Utama / Read-only)</label>
                    <input type="text" value="{{ $kandidat->nik }}" disabled class="w-full px-4 py-2.5 rounded-xl bg-slate-100 border border-slate-200 text-xs sm:text-sm text-slate-500 font-mono outline-none cursor-not-allowed">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap & Gelar</label>
                <input type="text" name="nama" value="{{ old('nama', $kandidat->nama) }}" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-blue-500 outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Upload Foto Baru (Opsional)</label>
                    <input type="file" name="foto_file" accept="image/*" class="w-full px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-700 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Atau Ubah URL Gambar</label>
                    <input type="url" name="foto_url" value="{{ old('foto_url', str_starts_with($kandidat->foto ?? '', 'http') ? $kandidat->foto : '') }}" placeholder="https://..." class="w-full px-4 py-2 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 focus:border-blue-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Visi (Tipe TEXT)</label>
                <textarea name="visi" rows="3" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-blue-500 outline-none">{{ old('visi', $kandidat->visi) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Misi (Tipe TEXT)</label>
                <textarea name="misi" rows="4" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-blue-500 outline-none">{{ old('misi', $kandidat->misi) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Singkat / Rekam Jejak</label>
                <textarea name="deskripsi" rows="2" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-blue-500 outline-none">{{ old('deskripsi', $kandidat->deskripsi) }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-200 flex justify-end space-x-3">
                <a href="{{ route('admin.ketua.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition">Perbarui Calon Ketua</button>
            </div>
        </form>
    </div>
</div>
@endsection
