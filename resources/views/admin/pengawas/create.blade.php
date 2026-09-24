@extends('layouts.admin')

@section('title', 'Tambah Kandidat Pengawas')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900">Tambah Calon Pengawas Koperasi</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Lengkapi informasi biodata, visi, dan misi kandidat.</p>
        </div>
        <a href="{{ route('admin.pengawas.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">
            ← Kembali
        </a>
    </div>

    <div class="p-8 rounded-3xl bg-white border border-slate-200 shadow-2xs">
        <form action="{{ route('admin.pengawas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Urut</label>
                    <input type="number" name="nomor_urut" value="{{ old('nomor_urut', $nextNomor) }}" required min="1" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-emerald-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">NIK (Nomor Induk Kandidat)</label>
                    <input type="text" name="nik" value="{{ old('nik') }}" required placeholder="Contoh: PW04 atau 102499" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-emerald-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap & Gelar</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Dra. Hj. Maryani, Ak., C.A." class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-emerald-500 outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Upload File Foto</label>
                    <input type="file" name="foto_file" accept="image/*" class="w-full px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-700 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-800">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Atau Gunakan URL Gambar</label>
                    <input type="url" name="foto_url" value="{{ old('foto_url') }}" placeholder="https://..." class="w-full px-4 py-2 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 focus:border-emerald-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Visi (Tipe TEXT)</label>
                <textarea name="visi" rows="3" required placeholder="Tuliskan visi calon pengawas koperasi..." class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-emerald-500 outline-none">{{ old('visi') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Misi (Tipe TEXT)</label>
                <textarea name="misi" rows="4" required placeholder="Tuliskan poin-poin misi calon pengawas koperasi..." class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-emerald-500 outline-none">{{ old('misi') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Singkat / Rekam Jejak</label>
                <textarea name="deskripsi" rows="2" placeholder="Pengalaman audit, kepatuhan, atau pengawasan..." class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs sm:text-sm text-slate-900 focus:border-emerald-500 outline-none">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-200 flex justify-end space-x-3">
                <a href="{{ route('admin.pengawas.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition">Simpan Calon Pengawas</button>
            </div>
        </form>
    </div>
</div>
@endsection
