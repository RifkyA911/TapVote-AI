<?php

namespace App\Http\Controllers;

use App\Models\KandidatKetua;
use App\Models\KandidatPengawas;
use App\Models\Pemilih;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LiveCountController extends Controller
{
    /**
     * Tampilan Halaman Utama / Live Count Realtime
     */
    public function index()
    {
        $data = $this->getMetricsData();
        return view('welcome', $data);
    }

    /**
     * Server-Sent Events (SSE) Stream untuk Pembaruan Real-Time Tanpa Reload
     */
    public function stream(): StreamedResponse
    {
        return response()->stream(function () {
            $payload = $this->getMetricsData();

            echo "data: " . json_encode($payload) . "\n\n";

            if (ob_get_level() > 0) {
                ob_flush();
            }
            flush();
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    /**
     * JSON API Endpoint Fallback
     */
    public function apiData()
    {
        return response()->json($this->getMetricsData());
    }

    /**
     * Kalkulasi Perolehan Suara & Persentase
     */
    public function getMetricsData(): array
    {
        $totalVoters = Pemilih::count();
        $totalVoted = Pemilih::where('pilih', 'T')->count();
        $turnoutPct = $totalVoters > 0 ? round(($totalVoted / $totalVoters) * 100, 1) : 0;

        // Data Kandidat Ketua
        $ketuaList = KandidatKetua::withCount('perolehanSuara')
            ->orderBy('nomor_urut', 'asc')
            ->get();

        $totalSuaraKetua = $ketuaList->sum('perolehan_suara_count');
        $maxSuaraKetua = $ketuaList->max('perolehan_suara_count');
        $leaderKetua = $totalSuaraKetua > 0 ? $ketuaList->firstWhere('perolehan_suara_count', $maxSuaraKetua) : null;

        $ketuaResults = $ketuaList->map(function ($k) use ($totalSuaraKetua, $maxSuaraKetua) {
            $suara = $k->perolehan_suara_count;
            $persen = $totalSuaraKetua > 0 ? round(($suara / $totalSuaraKetua) * 100, 1) : 0;
            return [
                'nik' => $k->nik,
                'nama' => $k->nama,
                'nomor_urut' => $k->nomor_urut,
                'foto' => $k->foto ?: 'https://ui-avatars.com/api/?name=' . urlencode($k->nama) . '&background=2563eb&color=ffffff&size=400',
                'visi' => $k->visi,
                'misi' => $k->misi,
                'deskripsi' => $k->deskripsi,
                'suara' => $suara,
                'persen' => $persen,
                'is_leader' => ($totalSuaraKetua > 0 && $suara === $maxSuaraKetua),
            ];
        });

        // Data Kandidat Pengawas
        $pengawasList = KandidatPengawas::withCount('perolehanSuara')
            ->orderBy('nomor_urut', 'asc')
            ->get();

        $totalSuaraPengawas = $pengawasList->sum('perolehan_suara_count');
        $maxSuaraPengawas = $pengawasList->max('perolehan_suara_count');
        $leaderPengawas = $totalSuaraPengawas > 0 ? $pengawasList->firstWhere('perolehan_suara_count', $maxSuaraPengawas) : null;

        $pengawasResults = $pengawasList->map(function ($p) use ($totalSuaraPengawas, $maxSuaraPengawas) {
            $suara = $p->perolehan_suara_count;
            $persen = $totalSuaraPengawas > 0 ? round(($suara / $totalSuaraPengawas) * 100, 1) : 0;
            return [
                'nik' => $p->nik,
                'nama' => $p->nama,
                'nomor_urut' => $p->nomor_urut,
                'foto' => $p->foto ?: 'https://ui-avatars.com/api/?name=' . urlencode($p->nama) . '&background=059669&color=ffffff&size=400',
                'visi' => $p->visi,
                'misi' => $p->misi,
                'deskripsi' => $p->deskripsi,
                'suara' => $suara,
                'persen' => $persen,
                'is_leader' => ($totalSuaraPengawas > 0 && $suara === $maxSuaraPengawas),
            ];
        });

        return [
            'metrics' => [
                'total_voters' => $totalVoters,
                'total_voted' => $totalVoted,
                'turnout_pct' => $turnoutPct,
                'total_suara_ketua' => $totalSuaraKetua,
                'total_suara_pengawas' => $totalSuaraPengawas,
                'leader_ketua' => $leaderKetua ? $leaderKetua->nama : null,
                'leader_pengawas' => $leaderPengawas ? $leaderPengawas->nama : null,
                'last_updated' => now()->format('d M Y H:i:s') . ' WIB',
            ],
            'ketuaResults' => $ketuaResults,
            'pengawasResults' => $pengawasResults,
        ];
    }
}
