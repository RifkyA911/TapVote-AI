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
     * Laporan 1: Siapa Pemenang Ketua Koperasi & Rincian Suara
     */
    public function pemenangKetua()
    {
        $kandidatList = KandidatKetua::withCount('perolehanSuara')
            ->orderBy('perolehan_suara_count', 'desc')
            ->orderBy('nomor_urut', 'asc')
            ->get();

        $totalSuara = HasilKetua::count();
        $pemenang = $kandidatList->first();

        // Rincian Suara per Departemen
        $deptBreakdown = DB::table('hasil_ketua')
            ->join('pemilih', 'hasil_ketua.pemilih_nik', '=', 'pemilih.nik')
            ->join('kandidat_ketua', 'hasil_ketua.ketua_nik', '=', 'kandidat_ketua.nik')
            ->select('pemilih.dept', 'kandidat_ketua.nama as kandidat_nama', 'kandidat_ketua.nomor_urut', DB::raw('count(*) as total'))
            ->groupBy('pemilih.dept', 'kandidat_ketua.nama', 'kandidat_ketua.nomor_urut')
            ->orderBy('pemilih.dept')
            ->orderBy('kandidat_ketua.nomor_urut')
            ->get();

        return view('admin.reports.ketua', compact('kandidatList', 'totalSuara', 'pemenang', 'deptBreakdown'));
    }

    /**
     * Laporan 2: Siapa Pemenang Pengawas Koperasi & Rincian Suara
     */
    public function pemenangPengawas()
    {
        $kandidatList = KandidatPengawas::withCount('perolehanSuara')
            ->orderBy('perolehan_suara_count', 'desc')
            ->orderBy('nomor_urut', 'asc')
            ->get();

        $totalSuara = HasilPengawas::count();
        $pemenang = $kandidatList->first();

        // Rincian Suara per Departemen
        $deptBreakdown = DB::table('hasil_pengawas')
            ->join('pemilih', 'hasil_pengawas.pemilih_nik', '=', 'pemilih.nik')
            ->join('kandidat_pengawas', 'hasil_pengawas.pengawas_nik', '=', 'kandidat_pengawas.nik')
            ->select('pemilih.dept', 'kandidat_pengawas.nama as kandidat_nama', 'kandidat_pengawas.nomor_urut', DB::raw('count(*) as total'))
            ->groupBy('pemilih.dept', 'kandidat_pengawas.nama', 'kandidat_pengawas.nomor_urut')
            ->orderBy('pemilih.dept')
            ->orderBy('kandidat_pengawas.nomor_urut')
            ->get();

        return view('admin.reports.pengawas', compact('kandidatList', 'totalSuara', 'pemenang', 'deptBreakdown'));
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

        $records = $query->orderBy('voted_at', 'desc')->paginate(20)->withQueryString();
        $totalVoted = Pemilih::where('pilih', 'T')->count();

        return view('admin.reports.traceback', compact('records', 'totalVoted', 'search'));
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

        $csv = "NIK,Nama Anggota,Departemen,Pilihan Ketua,Pilihan Pengawas,Waktu Memilih\n";

        foreach ($records as $r) {
            $ketua = $r->hasilKetua?->kandidatKetua?->nama ?? '-';
            $pengawas = $r->hasilPengawas?->kandidatPengawas?->nama ?? '-';
            $waktu = $r->voted_at ? $r->voted_at->format('Y-m-d H:i:s') : '-';

            $csv .= "\"{$r->nik}\",\"{$r->nama}\",\"{$r->dept}\",\"{$ketua}\",\"{$pengawas}\",\"{$waktu}\"\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="audit_traceback_suara_' . date('Ymd_His') . '.csv"',
        ]);
    }

    /**
     * Laporan 4: Siapa Saja yang Berhak Mengikuti Undian (Doorprize)
     * Ditambah interactive lucky draw spin engine!
     */
    public function doorprize()
    {
        $eligibleVoters = Pemilih::where('pilih', 'T')
            ->orderBy('nama', 'asc')
            ->get(['nik', 'nama', 'dept', 'voted_at']);

        $totalEligible = $eligibleVoters->count();

        return view('admin.reports.doorprize', compact('eligibleVoters', 'totalEligible'));
    }
}
