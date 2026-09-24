<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\KandidatKetua;
use App\Models\KandidatPengawas;
use App\Models\Pemilih;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function index()
    {
        $payload = $this->gatherVotingMetrics();

        $totalVoters = $payload['metrics']['total_voters'];
        $totalVoted = $payload['metrics']['total_voted'];
        $remaining = $payload['metrics']['remaining_voters'];
        $turnoutPct = $payload['metrics']['turnout_percentage'];

        $ketuaResults = $payload['ketua_results'];
        $pengawasResults = $payload['pengawas_results'];
        $recentVotes = $payload['recent_votes'];
        $recentLogs = ActivityLog::latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'totalVoters',
            'totalVoted',
            'remaining',
            'turnoutPct',
            'ketuaResults',
            'pengawasResults',
            'recentVotes',
            'recentLogs'
        ));
    }

    /**
     * Server-Sent Events (SSE) Endpoint for Realtime Voting Stream
     */
    public function sseStream(): StreamedResponse
    {
        return response()->stream(function () {
            // Non-blocking single event or short loop stream
            $payload = $this->gatherVotingMetrics();

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
     * REST Endpoint for instant JSON metrics fetch (polling fallback)
     */
    public function liveResults()
    {
        return response()->json($this->gatherVotingMetrics());
    }

    /**
     * Helper untuk menghitung metrik suara dan persentase
     */
    private function gatherVotingMetrics(): array
    {
        $totalVoters = Pemilih::count();
        $totalVoted = Pemilih::where('pilih', 'T')->count();
        $remaining = max(0, $totalVoters - $totalVoted);
        $turnoutPct = $totalVoters > 0 ? round(($totalVoted / $totalVoters) * 100, 1) : 0;

        // Metrik Kandidat Ketua
        $ketuaList = KandidatKetua::withCount('perolehanSuara')
            ->orderBy('nomor_urut', 'asc')
            ->get();

        $totalSuaraKetua = $ketuaList->sum('perolehan_suara_count');
        $ketuaResults = $ketuaList->map(function ($k) use ($totalSuaraKetua) {
            $suara = $k->perolehan_suara_count;
            $persen = $totalSuaraKetua > 0 ? round(($suara / $totalSuaraKetua) * 100, 1) : 0;
            return [
                'nik' => $k->nik,
                'nama' => $k->nama,
                'nomor_urut' => $k->nomor_urut,
                'foto' => $k->foto,
                'suara' => $suara,
                'persen' => $persen,
            ];
        });

        // Metrik Kandidat Pengawas
        $pengawasList = KandidatPengawas::withCount('perolehanSuara')
            ->orderBy('nomor_urut', 'asc')
            ->get();

        $totalSuaraPengawas = $pengawasList->sum('perolehan_suara_count');
        $pengawasResults = $pengawasList->map(function ($p) use ($totalSuaraPengawas) {
            $suara = $p->perolehan_suara_count;
            $persen = $totalSuaraPengawas > 0 ? round(($suara / $totalSuaraPengawas) * 100, 1) : 0;
            return [
                'nik' => $p->nik,
                'nama' => $p->nama,
                'nomor_urut' => $p->nomor_urut,
                'foto' => $p->foto,
                'suara' => $suara,
                'persen' => $persen,
            ];
        });

        // Suara terbaru
        $recentVotes = Pemilih::where('pilih', 'T')
            ->orderBy('voted_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($v) {
                return [
                    'nik' => $v->nik,
                    'nama' => $v->nama,
                    'dept' => $v->dept,
                    'waktu' => $v->voted_at ? $v->voted_at->format('H:i:s') : '-',
                ];
            });

        return [
            'timestamp' => now()->timestamp,
            'time_formatted' => now()->format('d M Y H:i:s WIB'),
            'metrics' => [
                'total_voters' => $totalVoters,
                'total_voted' => $totalVoted,
                'remaining_voters' => $remaining,
                'turnout_percentage' => $turnoutPct,
            ],
            'ketua_results' => $ketuaResults,
            'pengawas_results' => $pengawasResults,
            'recent_votes' => $recentVotes,
        ];
    }
}
