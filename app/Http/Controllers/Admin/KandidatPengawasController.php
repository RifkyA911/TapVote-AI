<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\KandidatPengawas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KandidatPengawasController extends Controller
{
    public function index()
    {
        $kandidat = KandidatPengawas::withCount('perolehanSuara')
            ->orderBy('nomor_urut', 'asc')
            ->get();

        return view('admin.pengawas.index', compact('kandidat'));
    }

    public function create()
    {
        $nextNomor = KandidatPengawas::max('nomor_urut') + 1;
        return view('admin.pengawas.create', compact('nextNomor'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|unique:kandidat_pengawas,nik|max:50',
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
            $path = $fotoFile->store('kandidat_pengawas', 'public');
            $fotoPath = '/storage/' . $path;
        } elseif (!empty($request->foto_url)) {
            $fotoPath = $request->foto_url;
        }

        $kandidat = KandidatPengawas::create([
            'nik' => $validated['nik'],
            'nama' => $validated['nama'],
            'nomor_urut' => $validated['nomor_urut'],
            'visi' => $validated['visi'],
            'misi' => $validated['misi'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'foto' => $fotoPath,
        ]);

        ActivityLog::log('CREATE_CANDIDATE_PENGAWAS', 'KANDIDAT_PENGAWAS', "Menambahkan calon pengawas: No. {$kandidat->nomor_urut} - {$kandidat->nama} (NIK: {$kandidat->nik})");

        return redirect()->route('admin.pengawas.index')->with('success', "Kandidat Pengawas {$kandidat->nama} berhasil ditambahkan!");
    }

    public function edit($nik)
    {
        $kandidat = KandidatPengawas::findOrFail($nik);
        return view('admin.pengawas.edit', compact('kandidat'));
    }

    public function update(Request $request, $nik)
    {
        $kandidat = KandidatPengawas::findOrFail($nik);

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
            $path = $fotoFile->store('kandidat_pengawas', 'public');
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

        ActivityLog::log('UPDATE_CANDIDATE_PENGAWAS', 'KANDIDAT_PENGAWAS', "Memperbarui data calon pengawas NIK: {$kandidat->nik} ({$kandidat->nama})");

        return redirect()->route('admin.pengawas.index')->with('success', "Kandidat Pengawas {$kandidat->nama} berhasil diperbarui!");
    }

    public function destroy($nik)
    {
        $kandidat = KandidatPengawas::findOrFail($nik);

        if ($kandidat->perolehanSuara()->exists()) {
            return back()->with('error', "Kandidat {$kandidat->nama} tidak dapat dihapus karena sudah memiliki perolehan suara. Harap reset suara terlebih dahulu jika ingin menghapus.");
        }

        if ($kandidat->foto && str_starts_with($kandidat->foto, '/storage/')) {
            $oldStoragePath = str_replace('/storage/', '', $kandidat->foto);
            Storage::disk('public')->delete($oldStoragePath);
        }

        $nama = $kandidat->nama;
        $kandidat->delete();

        ActivityLog::log('DELETE_CANDIDATE_PENGAWAS', 'KANDIDAT_PENGAWAS', "Menghapus calon pengawas: {$nama} (NIK: {$nik})");

        return redirect()->route('admin.pengawas.index')->with('success', "Kandidat {$nama} berhasil dihapus.");
    }
}
