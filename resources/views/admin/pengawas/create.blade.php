@extends('layouts.admin')

@section('title', 'Tambah Kandidat Pengawas')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-white">Tambah Calon Pengawas Koperasi</h2>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">Daftarkan nomor urut, visi, misi, dan foto calon pengawas baru.</p>
        </div>
        <a href="{{ route('admin.pengawas.index') }}" class="text-xs text-slate-400 hover:text-white transition">← Kembali</a>
    </div>

    <div class="p-8 rounded-3xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl shadow-xl">
        <form action="{{ route('admin.pengawas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Nomor Urut</label>
                    <input type="number" name="nomor_urut" value="{{ old('nomor_urut', $nextNomor) }}" required min="1" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white focus:border-emerald-500 outline-none">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">NIK Kandidat</label>
                    <input type="text" name="nik" value="{{ old('nik') }}" required placeholder="Contoh: PW04 atau 102499" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white focus:border-emerald-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Nama Lengkap & Gelar</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Siti Fatimah, S.E., Ak." class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white focus:border-emerald-500 outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Upload Foto (File)</label>
                    <input type="file" name="foto" accept="image/*" class="w-full text-xs text-slate-400 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Atau URL Foto (Opsional)</label>
                    <input type="url" name="foto_url" value="{{ old('foto_url') }}" placeholder="https://..." class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-xs text-white focus:border-emerald-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">
                    Visi Kandidat <span class="text-emerald-400 font-mono text-[10px]">(Tipe: TEXT)</span>
                </label>
                <textarea name="visi" rows="3" required placeholder="Tuliskan visi calon pengawas koperasi..." class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white focus:border-emerald-500 outline-none">{{ old('visi') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">
                    Misi Kandidat <span class="text-emerald-400 font-mono text-[10px]">(Tipe: TEXT)</span>
                </label>
                <textarea name="misi" rows="4" required placeholder="Tuliskan poin-poin misi calon pengawas koperasi..." class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white focus:border-emerald-500 outline-none">{{ old('misi') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Deskripsi Ringkas / Profil Tambahan</label>
                <textarea name="deskripsi" rows="2" placeholder="Keahlian audit atau latar belakang hukum..." class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white focus:border-emerald-500 outline-none">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-800">
                <a href="{{ route('admin.pengawas.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg shadow-emerald-600/30 transition">Simpan Calon Pengawas</button>
            </div>
        </form>
    </div>
</div>
@endsection
