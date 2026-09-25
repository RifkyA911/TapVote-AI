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

            <div class="space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Upload File Foto Profile (Disimpan ke Storage)</label>
                        <input type="file" name="foto" id="foto-input" accept="image/*" onchange="handlePhotoChange(event)" class="w-full px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-700 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-800 cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Atau Gunakan URL Gambar Eksternal</label>
                        <input type="url" name="foto_url" value="{{ old('foto_url') }}" placeholder="https://..." class="w-full px-4 py-2 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 focus:border-emerald-500 outline-none">
                    </div>
                </div>

                <!-- Live Photo Preview Box -->
                <div class="flex items-center gap-4 p-3 bg-slate-50 border border-slate-200 rounded-2xl">
                    <div class="w-16 h-20 bg-slate-200 rounded-xl overflow-hidden border border-slate-300 flex items-center justify-center shrink-0 shadow-2xs">
                        <img id="image-preview" src="" alt="Preview" class="w-full h-full object-cover object-top hidden">
                        <svg id="preview-placeholder-icon" class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="text-xs text-slate-500 space-y-0.5">
                        <p class="font-bold text-slate-800" id="preview-filename">Belum ada file dipilih</p>
                        <p class="text-[11px] text-slate-500">Format: JPG, JPEG, PNG, WEBP (Maksimal 10MB).</p>
                        <p class="text-[11px] font-semibold text-emerald-700">Folder Storage: <code class="font-mono bg-emerald-50 px-1 py-0.5 rounded text-emerald-800">storage/app/public/kandidat_pengawas</code></p>
                    </div>
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

<script>
function handlePhotoChange(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('image-preview');
    const placeholder = document.getElementById('preview-placeholder-icon');
    const filenameLabel = document.getElementById('preview-filename');

    if (file) {
        filenameLabel.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
