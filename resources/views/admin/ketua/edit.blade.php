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

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-300 text-rose-800 text-xs sm:text-sm">
            <div class="font-bold flex items-center space-x-2 mb-1">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>Terdapat kesalahan pada input form:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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

            <div class="space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Upload Foto Baru (Disimpan ke Storage)</label>
                        <input type="file" name="foto" id="foto-input" accept="image/*" onchange="handlePhotoChange(event)" class="w-full px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-700 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Atau Ubah URL Gambar Eksternal</label>
                        <input type="url" name="foto_url" value="{{ old('foto_url', str_starts_with($kandidat->foto ?? '', 'http') ? $kandidat->foto : '') }}" placeholder="https://..." class="w-full px-4 py-2 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 focus:border-blue-500 outline-none">
                    </div>
                </div>

                <!-- Live Photo Preview Box (Menampilkan Foto Saat Ini / Foto Baru) -->
                @php
                    $currentPhoto = $kandidat->foto ?: 'https://ui-avatars.com/api/?name='.urlencode($kandidat->nama).'&background=2563eb&color=ffffff&size=400';
                @endphp
                <div class="flex items-center gap-4 p-3 bg-slate-50 border border-slate-200 rounded-2xl">
                    <div class="w-16 h-20 bg-slate-200 rounded-xl overflow-hidden border border-slate-300 flex items-center justify-center shrink-0 shadow-2xs">
                        <img id="image-preview" src="{{ $currentPhoto }}" alt="Foto {{ $kandidat->nama }}" class="w-full h-full object-cover object-top">
                    </div>
                    <div class="text-xs text-slate-500 space-y-0.5">
                        <p class="font-bold text-slate-800" id="preview-filename">
                            {{ $kandidat->foto ? (str_starts_with($kandidat->foto, '/storage/') ? 'Foto tersimpan di storage lokal' : 'Foto URL eksternal') : 'Menggunakan avatar default' }}
                        </p>
                        <p class="text-[11px] text-slate-500">Biarkan kosong jika tidak ingin mengubah foto saat ini.</p>
                        <p class="text-[11px] font-semibold text-blue-700">Folder Storage: <code class="font-mono bg-blue-50 px-1 py-0.5 rounded text-blue-800">storage/app/public/kandidat_ketua</code></p>
                    </div>
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

<script>
function handlePhotoChange(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('image-preview');
    const filenameLabel = document.getElementById('preview-filename');

    if (file) {
        filenameLabel.textContent = 'File baru dipilih: ' + file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
