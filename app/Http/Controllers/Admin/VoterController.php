<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\HasilKetua;
use App\Models\HasilPengawas;
use App\Models\Pemilih;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoterController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $dept = $request->query('dept');
        $status = $request->query('status'); // all, T, F

        $query = Pemilih::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('rfid', 'like', "%{$search}%")
                  ->orWhere('dept', 'like', "%{$search}%");
            });
        }

        if ($dept) {
            $query->where('dept', $dept);
        }

        if ($status && in_array($status, ['T', 'F'])) {
            $query->where('pilih', $status);
        }

        $voters = $query->orderBy('nama', 'asc')->get();
        $departments = Pemilih::distinct()->pluck('dept')->sort();

        $stats = [
            'total' => Pemilih::count(),
            'voted' => Pemilih::where('pilih', 'T')->count(),
            'not_voted' => Pemilih::where('pilih', 'F')->count(),
        ];

        return view('admin.voters.index', compact('voters', 'departments', 'stats', 'search', 'dept', 'status'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|unique:pemilih,nik|max:50',
            'rfid' => 'required|string|unique:pemilih,rfid|max:100',
            'nama' => 'required|string|max:255',
            'dept' => 'required|string|max:100',
        ]);

        $voter = Pemilih::create([
            'nik' => trim($validated['nik']),
            'rfid' => strtoupper(trim($validated['rfid'])),
            'nama' => trim($validated['nama']),
            'dept' => trim($validated['dept']),
            'pilih' => 'F',
        ]);

        ActivityLog::log('CREATE_VOTER', 'PEMILIH', "Menambahkan pemilih manual: {$voter->nama} (NIK: {$voter->nik}, RFID: {$voter->rfid})");

        return redirect()->route('admin.voters.index')->with('success', "Pemilih {$voter->nama} berhasil didaftarkan!");
    }

    public function destroy($nik)
    {
        $voter = Pemilih::findOrFail($nik);
        $nama = $voter->nama;

        $voter->delete();

        ActivityLog::log('DELETE_VOTER', 'PEMILIH', "Menghapus pemilih: {$nama} (NIK: {$nik})");

        return redirect()->route('admin.voters.index')->with('success', "Data pemilih {$nama} berhasil dihapus.");
    }

    /**
     * Import Pemilih dari File Excel / CSV
     * Format Kolom: NIK, Nama, Dept, RFID
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:5120',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        $imported = 0;
        $updated = 0;
        $errors = [];

        if (in_array($extension, ['csv', 'txt'])) {
            $handle = fopen($file->getRealPath(), 'r');
            $header = null;
            $rowNum = 0;

            // Auto-detect delimiter (, or ;)
            $firstLine = fgets($handle);
            $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';
            rewind($handle);

            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                $rowNum++;
                if (empty(array_filter($row))) {
                    continue;
                }

                // Check header
                if (!$header) {
                    $cleanHeader = array_map(fn($col) => strtolower(trim($col)), $row);
                    if (in_array('nik', $cleanHeader) && in_array('nama', $cleanHeader)) {
                        $header = $cleanHeader;
                        continue;
                    }
                    $header = ['nik', 'nama', 'dept', 'rfid']; // default fallback
                }

                $nik = trim($row[0] ?? '');
                $nama = trim($row[1] ?? '');
                $dept = trim($row[2] ?? 'Umum');
                $rfid = trim($row[3] ?? '');

                if (empty($nik) || empty($nama)) {
                    continue;
                }

                if (empty($rfid)) {
                    // generate unique hex dummy if RFID is omitted
                    $rfid = strtoupper(substr(md5($nik), 0, 8));
                }

                try {
                    $pemilih = Pemilih::updateOrCreate(
                        ['nik' => $nik],
                        [
                            'nama' => $nama,
                            'dept' => $dept,
                            'rfid' => strtoupper($rfid),
                        ]
                    );

                    if ($pemilih->wasRecentlyCreated) {
                        $imported++;
                    } else {
                        $updated++;
                    }
                } catch (\Throwable $e) {
                    $errors[] = "Baris #{$rowNum} (NIK: {$nik}): " . $e->getMessage();
                }
            }
            fclose($handle);

            ActivityLog::log(
                'IMPORT_VOTERS',
                'PEMILIH',
                "Import data pemilih CSV: {$imported} baru ditambahkan, {$updated} diperbarui. " . count($errors) . " gagal."
            );

            return redirect()->route('admin.voters.index')
                ->with('success', "Import berhasil! {$imported} pemilih baru ditambahkan, {$updated} data diperbarui.")
                ->with('import_errors', $errors);
        }

        return redirect()->route('admin.voters.index')->with('error', 'Format file harus berupa CSV/TXT dengan kolom: nik, nama, dept, rfid.');
    }

    /**
     * Download Template CSV
     */
    public function downloadTemplate()
    {
        $csvContent = "nik,nama,dept,rfid\n";
        $csvContent .= "202401,Budi Raharjo,Information Technology,A1B2C3D4\n";
        $csvContent .= "202402,Dewi Sartika,Keuangan,E5F6A7B8\n";
        $csvContent .= "202403,Agus Salim,Operasional,C9D8E7F6\n";

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_voters_tapvote.csv"',
        ]);
    }

    /**
     * Export Seluruh DPT ke Excel / CSV
     */
    public function export()
    {
        $voters = Pemilih::orderBy('nama', 'asc')->get();

        $csv = "\xEF\xBB\xBF"; // UTF-8 BOM
        $csv .= "DAFTAR PEMILIH TETAP (DPT) - PEMILU KOPERASI\n";
        $csv .= "Tanggal Unduh," . date('Y-m-d H:i:s') . "\n";
        $csv .= "Total Anggota," . $voters->count() . "\n\n";
        $csv .= "NIK,Nama Anggota,Departemen,UID RFID Mifare,Status Hak Suara,Waktu Memilih\n";

        foreach ($voters as $v) {
            $status = $v->pilih === 'T' ? 'Sudah Memilih' : 'Belum Memilih';
            $waktu = $v->voted_at ? $v->voted_at->format('Y-m-d H:i:s') : '-';
            $csv .= "\"{$v->nik}\",\"{$v->nama}\",\"{$v->dept}\",\"{$v->rfid}\",\"{$status}\",\"{$waktu}\"\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="dpt_pemilih_' . date('Ymd_His') . '.csv"',
        ]);
    }

    /**
     * Reset Seluruh Suara Pemilihan (Gladi Resik / Demo Test)
     */
    public function resetVotes()
    {
        DB::transaction(function () {
            HasilKetua::truncate();
            HasilPengawas::truncate();
            Pemilih::query()->update([
                'pilih' => 'F',
                'voted_at' => null,
            ]);

            ActivityLog::log('RESET_ALL_VOTES', 'VOTING', 'Admin me-reset seluruh suara pemilihan dan mengembalikan status semua pemilih menjadi belum memilih (F).');
        });

        return redirect()->route('admin.voters.index')->with('success', 'Seluruh hasil perolehan suara berhasil di-reset. Status pemilih kembali siap memilih.');
    }
}
