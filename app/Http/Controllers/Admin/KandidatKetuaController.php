<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\KandidatKetua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KandidatKetuaController extends Controller
{
    public function index()
    {
        $kandidat = KandidatKetua::withCount('perolehanSuara')
            ->orderBy('nomor_urut', 'asc')
            ->get();

        return view('admin.ketua.index', compact('kandidat'));
    }

    public function create()
    {
        $nextNomor = KandidatKetua::max('nomor_urut') + 1;
        return view('admin.ketua.create', compact('nextNomor'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|unique:kandidat_ketua,nik|max:50',
            'nama' => 'required|string|max:255',
            'nomor_urut' => 'required|integer|min:1',
            'visi' => 'required|string',
            'misi' => 'required|string',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'foto_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'foto_url' => 'nullable|url',
        ]);

        $fotoFile = $request->file('foto') ?? $request->file('foto_file');
        $fotoPath = null;
        if ($fotoFile && $fotoFile->isValid()) {
            $path = $fotoFile->store('kandidat_ketua', 'public');
            $fotoPath = '/storage/' . $path;
        } elseif (!empty($request->foto_url)) {
            $fotoPath = $request->foto_url;
        }

        $kandidat = KandidatKetua::create([
            'nik' => $validated['nik'],
            'nama' => $validated['nama'],
            'nomor_urut' => $validated['nomor_urut'],
            'visi' => $validated['visi'],
            'misi' => $validated['misi'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'foto' => $fotoPath,
        ]);

        ActivityLog::log('CREATE_CANDIDATE_KETUA', 'KANDIDAT_KETUA', "Menambahkan calon ketua: No. {$kandidat->nomor_urut} - {$kandidat->nama} (NIK: {$kandidat->nik})");

        return redirect()->route('admin.ketua.index')->with('success', "Kandidat Ketua {$kandidat->nama} berhasil ditambahkan!");
    }

    public function edit($nik)
    {
        $kandidat = KandidatKetua::findOrFail($nik);
        return view('admin.ketua.edit', compact('kandidat'));
    }

    public function update(Request $request, $nik)
    {
        $kandidat = KandidatKetua::findOrFail($nik);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_urut' => 'required|integer|min:1',
            'visi' => 'required|string',
            'misi' => 'required|string',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'foto_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'foto_url' => 'nullable|url',
        ]);

        $fotoFile = $request->file('foto') ?? $request->file('foto_file');
        $fotoPath = $kandidat->foto;

        if ($fotoFile && $fotoFile->isValid()) {
            // Hapus file lama dari storage jika sebelumnya tersimpan lokal
            if ($kandidat->foto && str_starts_with($kandidat->foto, '/storage/')) {
                $oldStoragePath = str_replace('/storage/', '', $kandidat->foto);
                Storage::disk('public')->delete($oldStoragePath);
            }
            $path = $fotoFile->store('kandidat_ketua', 'public');
            $fotoPath = '/storage/' . $path;
        } elseif ($request->filled('foto_url')) {
            if ($kandidat->foto && str_starts_with($kandidat->foto, '/storage/')) {
                $oldStoragePath = str_replace('/storage/', '', $kandidat->foto);
                Storage::disk('public')->delete($oldStoragePath);
            }
            $fotoPath = $request->foto_url;
        }

        $kandidat->update([
            'nama' => $validated['nama'],
            'nomor_urut' => $validated['nomor_urut'],
            'visi' => $validated['visi'],
            'misi' => $validated['misi'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'foto' => $fotoPath,
        ]);

        ActivityLog::log('UPDATE_CANDIDATE_KETUA', 'KANDIDAT_KETUA', "Memperbarui data calon ketua NIK: {$kandidat->nik} ({$kandidat->nama})");

        return redirect()->route('admin.ketua.index')->with('success', "Kandidat Ketua {$kandidat->nama} berhasil diperbarui!");
    }

    public function destroy($nik)
    {
        $kandidat = KandidatKetua::findOrFail($nik);

        if ($kandidat->perolehanSuara()->exists()) {
            return back()->with('error', "Kandidat {$kandidat->nama} tidak dapat dihapus karena sudah memiliki perolehan suara. Harap reset suara terlebih dahulu jika ingin menghapus.");
        }

        if ($kandidat->foto && str_starts_with($kandidat->foto, '/storage/')) {
            $oldStoragePath = str_replace('/storage/', '', $kandidat->foto);
            Storage::disk('public')->delete($oldStoragePath);
        }

        $nama = $kandidat->nama;
        $kandidat->delete();

        ActivityLog::log('DELETE_CANDIDATE_KETUA', 'KANDIDAT_KETUA', "Menghapus calon ketua: {$nama} (NIK: {$nik})");

        return redirect()->route('admin.ketua.index')->with('success', "Kandidat {$nama} berhasil dihapus.");
    }

    /**
     * Drag & Drop Reorder Prioritas Nomor Urut
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|string',
        ]);

        foreach ($request->order as $index => $nik) {
            KandidatKetua::where('nik', $nik)->update(['nomor_urut' => $index + 1]);
        }

        ActivityLog::log('CANDIDATE_REORDER', 'ADMIN', 'Urutan prioritas nomor urut Calon Ketua berhasil diperbarui via drag-and-drop.');

        return response()->json([
            'success' => true,
            'message' => 'Prioritas nomor urut Calon Ketua berhasil diperbarui.',
        ]);
    }
}
