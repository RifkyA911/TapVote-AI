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
     * Server-Sent Events (SSE) Stream untuk Pembaruan Real-Time Tanpa Delay
     */
    public function stream(): StreamedResponse
    {
        return response()->stream(function () {
            // Memberikan instruksi reconnect instan 1000ms ke browser
            echo "retry: 1000\n\n";

            $start = time();
            // Streaming loop aktif selama 25 detik per koneksi dengan interval 1 detik
            while (time() - $start < 25) {
                if (connection_aborted()) {
                    break;
                }

                $payload = $this->getMetricsData();
                echo "data: " . json_encode($payload) . "\n\n";

                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();

                sleep(1);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache, no-transform',
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
     * Kalkulasi Perolehan Suara & Persentase dengan Proteksi Hasil Seri (Tie-Break)
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
        $maxSuaraKetua = $ketuaList->max('perolehan_suara_count') ?? 0;
        $topKetuaCount = ($totalSuaraKetua > 0 && $maxSuaraKetua > 0) ? $ketuaList->where('perolehan_suara_count', $maxSuaraKetua)->count() : 0;
        $isKetuaSeri = $topKetuaCount > 1;

        $ketuaResults = $ketuaList->map(function ($k) use ($totalSuaraKetua, $maxSuaraKetua, $topKetuaCount) {
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
                'is_leader' => ($totalSuaraKetua > 0 && $topKetuaCount === 1 && $suara === $maxSuaraKetua),
                'is_tie' => ($totalSuaraKetua > 0 && $topKetuaCount > 1 && $suara === $maxSuaraKetua),
            ];
        });

        // Data Kandidat Pengawas
        $pengawasList = KandidatPengawas::withCount('perolehanSuara')
            ->orderBy('nomor_urut', 'asc')
            ->get();

        $totalSuaraPengawas = $pengawasList->sum('perolehan_suara_count');
        $maxSuaraPengawas = $pengawasList->max('perolehan_suara_count') ?? 0;
        $topPengawasCount = ($totalSuaraPengawas > 0 && $maxSuaraPengawas > 0) ? $pengawasList->where('perolehan_suara_count', $maxSuaraPengawas)->count() : 0;
        $isPengawasSeri = $topPengawasCount > 1;

        $pengawasResults = $pengawasList->map(function ($p) use ($totalSuaraPengawas, $maxSuaraPengawas, $topPengawasCount) {
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
                'is_leader' => ($totalSuaraPengawas > 0 && $topPengawasCount === 1 && $suara === $maxSuaraPengawas),
                'is_tie' => ($totalSuaraPengawas > 0 && $topPengawasCount > 1 && $suara === $maxSuaraPengawas),
            ];
        });

        $leaderKetuaName = '';
        if ($isKetuaSeri) {
            $leaderKetuaName = 'HASIL SERI (' . $maxSuaraKetua . ' Suara)';
        } elseif ($totalSuaraKetua > 0 && $topKetuaCount === 1) {
            $leaderKetua = $ketuaList->firstWhere('perolehan_suara_count', $maxSuaraKetua);
            $leaderKetuaName = $leaderKetua ? $leaderKetua->nama : '';
        }

        $leaderPengawasName = '';
        if ($isPengawasSeri) {
            $leaderPengawasName = 'HASIL SERI (' . $maxSuaraPengawas . ' Suara)';
        } elseif ($totalSuaraPengawas > 0 && $topPengawasCount === 1) {
            $leaderPengawas = $pengawasList->firstWhere('perolehan_suara_count', $maxSuaraPengawas);
            $leaderPengawasName = $leaderPengawas ? $leaderPengawas->nama : '';
        }

        return [
            'metrics' => [
                'total_voters' => $totalVoters,
                'total_voted' => $totalVoted,
                'total_pemilih' => $totalVoters,
                'total_suara_masuk' => $totalVoted,
                'total_belum_memilih' => max(0, $totalVoters - $totalVoted),
                'partisipasi_persen' => $turnoutPct,
                'turnout_pct' => $turnoutPct,
                'total_suara_ketua' => $totalSuaraKetua,
                'total_suara_pengawas' => $totalSuaraPengawas,
                'leader_ketua' => $leaderKetuaName,
                'leader_pengawas' => $leaderPengawasName,
                'is_ketua_seri' => $isKetuaSeri,
                'is_pengawas_seri' => $isPengawasSeri,
                'last_updated' => now()->timezone('Asia/Jakarta')->format('H:i:s') . ' WIB',
                'last_updated_full' => now()->timezone('Asia/Jakarta')->format('d M Y, H:i:s') . ' WIB',
            ],
            'ketuaResults' => $ketuaResults,
            'pengawasResults' => $pengawasResults,
        ];
    }
}
