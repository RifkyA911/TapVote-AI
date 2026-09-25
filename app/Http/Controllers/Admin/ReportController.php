<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilKetua;
use App\Models\HasilPengawas;
use App\Models\KandidatKetua;
use App\Models\KandidatPengawas;
use App\Models\Pemilih;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Laporan 1: Siapa Pemenang Ketua Koperasi & Rincian Suara (Dengan Deteksi Hasil Seri)
     */
    public function pemenangKetua()
    {
        $kandidatList = KandidatKetua::withCount('perolehanSuara')
            ->orderBy('perolehan_suara_count', 'desc')
            ->orderBy('nomor_urut', 'asc')
            ->get();

        $totalSuara = HasilKetua::count();
        $maxVotes = $kandidatList->max('perolehan_suara_count') ?? 0;
        $topCandidates = $maxVotes > 0 ? $kandidatList->where('perolehan_suara_count', $maxVotes)->values() : collect();
        $isSeri = $topCandidates->count() > 1;

        // Pemenang tunggal hanya jika tidak seri dan memiliki suara > 0
        $pemenang = (!$isSeri && $maxVotes > 0) ? $topCandidates->first() : null;

        // Rincian Suara per Departemen
        $deptBreakdown = DB::table('hasil_ketua')
            ->join('pemilih', 'hasil_ketua.pemilih_nik', '=', 'pemilih.nik')
            ->join('kandidat_ketua', 'hasil_ketua.ketua_nik', '=', 'kandidat_ketua.nik')
            ->select('pemilih.dept', 'kandidat_ketua.nama as kandidat_nama', 'kandidat_ketua.nomor_urut', DB::raw('count(*) as total'))
            ->groupBy('pemilih.dept', 'kandidat_ketua.nama', 'kandidat_ketua.nomor_urut')
            ->orderBy('pemilih.dept')
            ->orderBy('kandidat_ketua.nomor_urut')
            ->get();

        $kandidatKetua = $kandidatList;
        return view('admin.reports.ketua', compact('kandidatList', 'kandidatKetua', 'totalSuara', 'pemenang', 'deptBreakdown', 'isSeri', 'topCandidates', 'maxVotes'));
    }

    /**
     * Laporan 2: Siapa Pemenang Pengawas Koperasi & Rincian Suara (Dengan Deteksi Hasil Seri)
     */
    public function pemenangPengawas()
    {
        $kandidatList = KandidatPengawas::withCount('perolehanSuara')
            ->orderBy('perolehan_suara_count', 'desc')
            ->orderBy('nomor_urut', 'asc')
            ->get();

        $totalSuara = HasilPengawas::count();
        $maxVotes = $kandidatList->max('perolehan_suara_count') ?? 0;
        $topCandidates = $maxVotes > 0 ? $kandidatList->where('perolehan_suara_count', $maxVotes)->values() : collect();
        $isSeri = $topCandidates->count() > 1;

        // Pemenang tunggal hanya jika tidak seri dan memiliki suara > 0
        $pemenang = (!$isSeri && $maxVotes > 0) ? $topCandidates->first() : null;

        // Rincian Suara per Departemen
        $deptBreakdown = DB::table('hasil_pengawas')
            ->join('pemilih', 'hasil_pengawas.pemilih_nik', '=', 'pemilih.nik')
            ->join('kandidat_pengawas', 'hasil_pengawas.pengawas_nik', '=', 'kandidat_pengawas.nik')
            ->select('pemilih.dept', 'kandidat_pengawas.nama as kandidat_nama', 'kandidat_pengawas.nomor_urut', DB::raw('count(*) as total'))
            ->groupBy('pemilih.dept', 'kandidat_pengawas.nama', 'kandidat_pengawas.nomor_urut')
            ->orderBy('pemilih.dept')
            ->orderBy('kandidat_pengawas.nomor_urut')
            ->get();

        $kandidatPengawas = $kandidatList;
        return view('admin.reports.pengawas', compact('kandidatList', 'kandidatPengawas', 'totalSuara', 'pemenang', 'deptBreakdown', 'isSeri', 'topCandidates', 'maxVotes'));
    }

    /**
     * Laporan 3: Trace Back Anggota Memilih Ketua Siapa & Pengawas Siapa
     */
    public function traceback(Request $request)
    {
        $search = $request->query('search');

        $query = Pemilih::where('pilih', 'T')
            ->with(['hasilKetua.kandidatKetua', 'hasilPengawas.kandidatPengawas']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('dept', 'like', "%{$search}%");
            });
        }

        $records = $query->orderBy('voted_at', 'desc')->get();
        $voters = $records;
        $totalVoted = Pemilih::where('pilih', 'T')->count();

        return view('admin.reports.traceback', compact('records', 'voters', 'totalVoted', 'search'));
    }

    /**
     * Export Rekapitulasi Ketua ke Excel / CSV
     */
    public function exportKetua()
    {
        $kandidatList = KandidatKetua::withCount('perolehanSuara')
            ->orderBy('perolehan_suara_count', 'desc')
            ->orderBy('nomor_urut', 'asc')
            ->get();

        $totalSuara = HasilKetua::count();
        $maxVotes = $kandidatList->max('perolehan_suara_count') ?? 0;
        $topCount = $maxVotes > 0 ? $kandidatList->where('perolehan_suara_count', $maxVotes)->count() : 0;
        $isSeri = $topCount > 1;

        $csv = "\xEF\xBB\xBF"; // UTF-8 BOM for Microsoft Excel
        $csv .= "REKAPITULASI HASIL PEMILIHAN KETUA KOPERASI\n";
        $csv .= "Waktu Unduh," . date('Y-m-d H:i:s') . "\n";
        $csv .= "Total Suara Masuk," . $totalSuara . "\n";
        $csv .= "Status Pemilihan," . ($isSeri ? "HASIL SERI / DRAW (Perlu Putaran Kedua / Musyawarah)" : ($totalSuara > 0 ? "Telah Ada Pemenang Terpilih" : "Belum Ada Suara")) . "\n\n";
        $csv .= "Nomor Urut,Nama Calon Ketua,Perolehan Suara,Persentase Suara,Status\n";

        foreach ($kandidatList as $k) {
            $persen = $totalSuara > 0 ? round(($k->perolehan_suara_count / $totalSuara) * 100, 2) : 0;
            if ($isSeri && $k->perolehan_suara_count === $maxVotes && $maxVotes > 0) {
                $status = 'HASIL SERI (Suara Terbanyak Seimbang)';
            } elseif (!$isSeri && $k->perolehan_suara_count === $maxVotes && $maxVotes > 0) {
                $status = 'Pemenang Unggul Terpilih';
            } else {
                $status = 'Kandidat';
            }
            $csv .= "\"{$k->nomor_urut}\",\"{$k->nama}\",\"{$k->perolehan_suara_count}\",\"{$persen}%\",\"{$status}\"\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="rekapitulasi_ketua_' . date('Ymd_His') . '.csv"',
        ]);
    }

    /**
     * Export Rekapitulasi Pengawas ke Excel / CSV
     */
    public function exportPengawas()
    {
        $kandidatList = KandidatPengawas::withCount('perolehanSuara')
            ->orderBy('perolehan_suara_count', 'desc')
            ->orderBy('nomor_urut', 'asc')
            ->get();

        $totalSuara = HasilPengawas::count();
        $maxVotes = $kandidatList->max('perolehan_suara_count') ?? 0;
        $topCount = $maxVotes > 0 ? $kandidatList->where('perolehan_suara_count', $maxVotes)->count() : 0;
        $isSeri = $topCount > 1;

        $csv = "\xEF\xBB\xBF"; // UTF-8 BOM for Microsoft Excel
        $csv .= "REKAPITULASI HASIL PEMILIHAN PENGAWAS KOPERASI\n";
        $csv .= "Waktu Unduh," . date('Y-m-d H:i:s') . "\n";
        $csv .= "Total Suara Masuk," . $totalSuara . "\n";
        $csv .= "Status Pemilihan," . ($isSeri ? "HASIL SERI / DRAW (Perlu Putaran Kedua / Musyawarah)" : ($totalSuara > 0 ? "Telah Ada Pemenang Terpilih" : "Belum Ada Suara")) . "\n\n";
        $csv .= "Nomor Urut,Nama Calon Pengawas,Perolehan Suara,Persentase Suara,Status\n";

        foreach ($kandidatList as $k) {
            $persen = $totalSuara > 0 ? round(($k->perolehan_suara_count / $totalSuara) * 100, 2) : 0;
            if ($isSeri && $k->perolehan_suara_count === $maxVotes && $maxVotes > 0) {
                $status = 'HASIL SERI (Suara Terbanyak Seimbang)';
            } elseif (!$isSeri && $k->perolehan_suara_count === $maxVotes && $maxVotes > 0) {
                $status = 'Pemenang Unggul Terpilih';
            } else {
                $status = 'Kandidat';
            }
            $csv .= "\"{$k->nomor_urut}\",\"{$k->nama}\",\"{$k->perolehan_suara_count}\",\"{$persen}%\",\"{$status}\"\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="rekapitulasi_pengawas_' . date('Ymd_His') . '.csv"',
        ]);
    }

    /**
     * Export Trace Back ke CSV
     */
    public function exportTraceback()
    {
        $records = Pemilih::where('pilih', 'T')
            ->with(['hasilKetua.kandidatKetua', 'hasilPengawas.kandidatPengawas'])
            ->orderBy('voted_at', 'desc')
            ->get();

        $csv = "\xEF\xBB\xBF"; // UTF-8 BOM for Microsoft Excel
        $csv .= "NIK,Nama Anggota,Departemen,Pilihan Ketua,Pilihan Pengawas,Waktu Memilih\n";

        foreach ($records as $r) {
            $ketua = $r->hasilKetua?->kandidatKetua?->nama ?? '-';
            $pengawas = $r->hasilPengawas?->kandidatPengawas?->nama ?? '-';
            $waktu = $r->voted_at ? $r->voted_at->format('Y-m-d H:i:s') : '-';

            $csv .= "\"{$r->nik}\",\"{$r->nama}\",\"{$r->dept}\",\"{$ketua}\",\"{$pengawas}\",\"{$waktu}\"\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="audit_traceback_suara_' . date('Ymd_His') . '.csv"',
        ]);
    }

    /**
     * Laporan 4: Siapa Saja yang Berhak Mengikuti Undian (Doorprize)
     * Awal render KOSONG (tanpa pemenang default)
     * Mengundi interaktif minimal 10 detik dengan drum roll & congrats audio
     */
    public function doorprize()
    {
        $eligibleQuery = Pemilih::where('pilih', 'T')
            ->orderBy('voted_at', 'desc');

        $totalEligible = $eligibleQuery->count();

        // Awal render KOSONG sesuai permintaan pengguna
        $pemenang = null;

        // Data semua pemilih yang sah untuk animasi roulette undian 10 detik di client
        $eligibleList = Pemilih::where('pilih', 'T')
            ->select('nik', 'nama', 'dept', 'voted_at')
            ->get()
            ->map(function ($p) {
                return [
                    'nik' => $p->nik,
                    'nama' => $p->nama,
                    'dept' => $p->dept,
                    'waktu' => $p->voted_at ? $p->voted_at->format('H:i:s d/m/Y') : '-',
                ];
            });

        $eligibleVoters = $eligibleQuery->paginate(20);

        return view('admin.reports.doorprize', compact('eligibleVoters', 'totalEligible', 'pemenang', 'eligibleList'));
    }
}
