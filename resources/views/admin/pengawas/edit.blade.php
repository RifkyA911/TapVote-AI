@extends('layouts.admin')

@section('title', __('Edit Kandidat Pengawas'))

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ __('Edit Calon Pengawas:') }} {{ $kandidat->nama }}</h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Perbarui nomor urut, visi, misi, atau foto calon.') }}</p>
        </div>
        <a href="{{ route('admin.pengawas.index') }}" class="text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">
            ← {{ __('Kembali') }}
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-300 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-xs sm:text-sm">
            <div class="font-bold flex items-center space-x-2 mb-1">
                <svg class="w-4 h-4 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>{{ __('Terdapat kesalahan pada input form:') }}</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xs">
        <form action="{{ route('admin.pengawas.update', $kandidat->nik) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">{{ __('Nomor Urut') }}</label>
                    <input type="number" name="nomor_urut" value="{{ old('nomor_urut', $kandidat->nomor_urut) }}" required min="1" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-xs sm:text-sm text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-900 focus:border-emerald-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">{{ __('NIK (Kunci Utama / Read-only)') }}</label>
                    <input type="text" value="{{ $kandidat->nik }}" disabled class="w-full px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-mono outline-none cursor-not-allowed">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">{{ __('Nama Lengkap & Gelar') }}</label>
                <input type="text" name="nama" value="{{ old('nama', $kandidat->nama) }}" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-xs sm:text-sm text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-900 focus:border-emerald-500 outline-none">
            </div>

            <div class="space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">{{ __('Upload Foto Baru (Disimpan ke Storage)') }}</label>
                        <input type="file" name="foto" id="foto-input" accept="image/*" onchange="handlePhotoChange(event)" class="w-full px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-xs text-slate-700 dark:text-slate-300 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 dark:file:bg-emerald-950/60 file:text-emerald-800 dark:file:text-emerald-300 cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">{{ __('Atau Ubah URL Gambar Eksternal') }}</label>
                        <input type="url" name="foto_url" value="{{ old('foto_url', str_starts_with($kandidat->foto ?? '', 'http') ? $kandidat->foto : '') }}" placeholder="https://..." class="w-full px-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-xs text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-900 focus:border-emerald-500 outline-none">
                    </div>
                </div>

                <!-- Live Photo Preview Box (Menampilkan Foto Saat Ini / Foto Baru) -->
                @php
                    $currentPhoto = $kandidat->foto ?: 'https://ui-avatars.com/api/?name='.urlencode($kandidat->nama).'&background=059669&color=ffffff&size=400';
                @endphp
                <div class="flex items-center gap-4 p-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-2xl">
                    <div class="w-16 h-20 bg-slate-200 dark:bg-slate-800 rounded-xl overflow-hidden border border-slate-300 dark:border-slate-700 flex items-center justify-center shrink-0 shadow-2xs">
                        <img id="image-preview" src="{{ $currentPhoto }}" alt="{{ __('Foto') }} {{ $kandidat->nama }}" class="w-full h-full object-cover object-top">
                    </div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 space-y-0.5">
                        <p class="font-bold text-slate-800 dark:text-slate-200" id="preview-filename">
                            {{ $kandidat->foto ? (str_starts_with($kandidat->foto, '/storage/') ? __('Foto tersimpan di storage lokal') : __('Foto URL eksternal')) : __('Menggunakan avatar default') }}
                        </p>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">{{ __('Biarkan kosong jika tidak ingin mengubah foto saat ini.') }}</p>
                        <p class="text-[11px] font-semibold text-emerald-700 dark:text-emerald-400">{{ __('Folder Storage:') }} <code class="font-mono bg-emerald-50 dark:bg-emerald-950/60 px-1 py-0.5 rounded text-emerald-800 dark:text-emerald-300">storage/app/public/kandidat_pengawas</code></p>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">{{ __('Visi (Tipe TEXT)') }}</label>
                <textarea name="visi" rows="3" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-xs sm:text-sm text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-900 focus:border-emerald-500 outline-none">{{ old('visi', $kandidat->visi) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">{{ __('Misi (Tipe TEXT)') }}</label>
                <textarea name="misi" rows="4" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-xs sm:text-sm text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-900 focus:border-emerald-500 outline-none">{{ old('misi', $kandidat->misi) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">{{ __('Deskripsi Singkat / Rekam Jejak') }}</label>
                <textarea name="deskripsi" rows="2" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-xs sm:text-sm text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-900 focus:border-emerald-500 outline-none">{{ old('deskripsi', $kandidat->deskripsi) }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex justify-end space-x-3">
                <a href="{{ route('admin.pengawas.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs transition border border-transparent dark:border-slate-700">{{ __('Batal') }}</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition">{{ __('Perbarui Calon Pengawas') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
function handlePhotoChange(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('image-preview');
    const filenameLabel = document.getElementById('preview-filename');

    if (file) {
        filenameLabel.textContent = '{{ __('File baru dipilih:') }} ' + file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endsection

