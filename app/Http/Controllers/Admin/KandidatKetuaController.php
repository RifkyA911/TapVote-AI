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
            'foto' => 'nullable|image|max:2048',
            'foto_url' => 'nullable|url',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('candidates/ketua', 'public');
            $fotoPath = '/storage/' . $fotoPath;
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
            'foto' => 'nullable|image|max:2048',
            'foto_url' => 'nullable|url',
        ]);

        $fotoPath = $kandidat->foto;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('candidates/ketua', 'public');
            $fotoPath = '/storage/' . $path;
        } elseif (!empty($request->foto_url)) {
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

        $nama = $kandidat->nama;
        $kandidat->delete();

        ActivityLog::log('DELETE_CANDIDATE_KETUA', 'KANDIDAT_KETUA', "Menghapus calon ketua: {$nama} (NIK: {$nik})");

        return redirect()->route('admin.ketua.index')->with('success', "Kandidat {$nama} berhasil dihapus.");
    }
}
