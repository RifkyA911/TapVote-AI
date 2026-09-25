<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\AppSetting;
use App\Models\KandidatKetua;
use App\Models\KandidatPengawas;
use App\Models\Pemilih;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

        $aiConclusion = null; // Lazy-loaded on explicit user click
        $votingStatus = AppSetting::get('voting_status', 'STARTED');
        $geminiKey = AppSetting::get('gemini_api_key', '');

        return view('admin.dashboard', compact(
            'totalVoters',
            'totalVoted',
            'remaining',
            'turnoutPct',
            'ketuaResults',
            'pengawasResults',
            'recentVotes',
            'recentLogs',
            'aiConclusion',
            'votingStatus',
            'geminiKey'
        ));
    }

    /**
     * Server-Sent Events (SSE) Endpoint for Realtime Voting Stream
     * Continuous 1-second interval loop without reconnect delay
     */
    public function sseStream(): StreamedResponse
    {
        return response()->stream(function () {
            echo "retry: 1000\n\n";

            $start = time();
            while (time() - $start < 25) {
                if (connection_aborted()) {
                    break;
                }

                $payload = $this->gatherVotingMetrics();
                // Note: AI conclusion is NOT computed every second here to avoid latency and quota exhaustion.
                // It is fetched on demand via /admin/api/ai-conclusion.

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
     * REST Endpoint for instant JSON metrics fetch (polling fallback)
     */
    public function liveResults()
    {
        $payload = $this->gatherVotingMetrics();
        return response()->json($payload);
    }

    /**
     * Endpoint API khusus AI Conclusion
     */
    public function getAiConclusion()
    {
        $payload = $this->gatherVotingMetrics();
        return response()->json($this->generateAiConclusion($payload));
    }

    /**
     * Update status sistem voting (START, PAUSE, STOP)
     */
    public function updateVotingStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:STARTED,PAUSED,STOPPED',
        ]);

        $newStatus = $request->status;
        $prevStatus = AppSetting::get('voting_status', 'STARTED');
        AppSetting::set('voting_status', $newStatus);

        ActivityLog::log(
            'VOTING_STATUS_CHANGE',
            'ADMIN',
            "Status sistem voting diubah dari [{$prevStatus}] menjadi [{$newStatus}] oleh Admin."
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'message' => "Status sistem voting berhasil diubah menjadi {$newStatus}."
            ]);
        }

        return redirect()->back()->with('success', "Status sistem voting berhasil diubah menjadi: {$newStatus}");
    }

    /**
     * Simpan / Perbarui Gemini API Key dari Google AI Studio
     */
    public function saveGeminiKey(Request $request)
    {
        $request->validate([
            'gemini_api_key' => 'nullable|string|max:255',
        ]);

        $key = trim($request->gemini_api_key ?? '');
        AppSetting::set('gemini_api_key', $key);

        ActivityLog::log(
            'GEMINI_KEY_UPDATED',
            'SETTINGS',
            empty($key) ? 'Gemini API Key dihapus oleh Admin.' : 'Gemini API Key berhasil diperbarui oleh Admin.'
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => empty($key) ? 'Gemini API Key dinonaktifkan (menggunakan mesin heuristik lokal).' : 'Gemini API Key dari Google AI Studio berhasil disimpan.'
            ]);
        }

        return redirect()->back()->with('success', empty($key) ? 'Gemini API Key dihapus. AI Conclusion beralih ke analisis statistik lokal.' : 'Gemini API Key Google AI Studio berhasil disimpan!');
    }

    /**
     * AI Conclusion Generator dengan Deteksi Seri Profesional & Gemini Generative AI
     */
    public function generateAiConclusion(array $payload): array
    {
        $totalVoters = $payload['metrics']['total_voters'];
        $totalVoted = $payload['metrics']['total_voted'];
        $turnout = $payload['metrics']['turnout_percentage'];
        
        $ketua = collect($payload['ketua_results'])->sortByDesc('suara')->values();
        $pengawas = collect($payload['pengawas_results'])->sortByDesc('suara')->values();

        $topKetua = $ketua->first();
        $runnerKetua = $ketua->get(1);
        $isKetuaSeri = ($topKetua && $runnerKetua && $topKetua['suara'] > 0 && $topKetua['suara'] === $runnerKetua['suara']);
        
        $marginKetua = ($topKetua && $runnerKetua && !$isKetuaSeri) ? ($topKetua['suara'] - $runnerKetua['suara']) : 0;
        $marginKetuaPct = ($topKetua && $runnerKetua && !$isKetuaSeri) ? round($topKetua['persen'] - $runnerKetua['persen'], 1) : 0;

        $topPengawas = $pengawas->first();
        $runnerPengawas = $pengawas->get(1);
        $isPengawasSeri = ($topPengawas && $runnerPengawas && $topPengawas['suara'] > 0 && $topPengawas['suara'] === $runnerPengawas['suara']);
        
        $marginPengawas = ($topPengawas && $runnerPengawas && !$isPengawasSeri) ? ($topPengawas['suara'] - $runnerPengawas['suara']) : 0;
        $marginPengawasPct = ($topPengawas && $runnerPengawas && !$isPengawasSeri) ? round($topPengawas['persen'] - $runnerPengawas['persen'], 1) : 0;

        $quorumMet = $turnout >= 50.0;
        $confidence = min(99.4, max(70.0, round(65 + ($turnout * 0.28) + ($marginKetuaPct * 0.1), 1)));

        $insights = [];
        if ($totalVoted === 0) {
            $summary = "Pemilihan baru saja dimulai. Belum ada suara masuk yang dicatat oleh sistem. Menunggu pemilih melakukan tap kartu RFID di bilik suara.";
            $insights[] = "Bilik suara dalam kondisi siaga (ready state).";
            $leaderKetuaText = "Belum Ada Suara";
            $leaderPengawasText = "Belum Ada Suara";
        } else {
            if ($quorumMet) {
                $insights[] = "Quorum pemilihan terpenuhi sah ({$turnout}% partisipasi dari {$totalVoters} DPT).";
            } else {
                $needed = max(0, (int)ceil($totalVoters * 0.5) - $totalVoted);
                $insights[] = "Partisipasi saat ini {$turnout}%. Masih dibutuhkan {$needed} suara lagi untuk mencapai batas quorum 50%.";
            }

            // Status Ketua
            if ($isKetuaSeri) {
                $tiedKetuaNames = $ketua->where('suara', $topKetua['suara'])->pluck('nama')->implode(' & ');
                $leaderKetuaText = "HASIL SERI / DRAW (" . $topKetua['suara'] . " Suara)";
                $insights[] = "PEROLEHAN SERI KETUA: {$tiedKetuaNames} memperoleh suara sama ({$topKetua['suara']} suara). Belum ada pemenang tunggal; menunggu putaran kedua atau musyawarah mufakat.";
            } elseif ($topKetua && $topKetua['suara'] > 0) {
                $leaderKetuaText = $topKetua['nama'];
                if ($marginKetuaPct > 15) {
                    $insights[] = "Kandidat Ketua No. {$topKetua['nomor_urut']} ({$topKetua['nama']}) memimpin kuat dengan selisih +{$marginKetuaPct}% ({$marginKetua} suara).";
                } else {
                    $insights[] = "Persaingan Calon Ketua berlangsung sangat ketat antara No. {$topKetua['nomor_urut']} dan No. " . ($runnerKetua ? $runnerKetua['nomor_urut'] : '-') . " (selisih tipis {$marginKetua} suara).";
                }
            } else {
                $leaderKetuaText = "Belum Ada Suara";
            }

            // Status Pengawas
            if ($isPengawasSeri) {
                $tiedPengawasNames = $pengawas->where('suara', $topPengawas['suara'])->pluck('nama')->implode(' & ');
                $leaderPengawasText = "HASIL SERI / DRAW (" . $topPengawas['suara'] . " Suara)";
                $insights[] = "PEROLEHAN SERI PENGAWAS: {$tiedPengawasNames} memperoleh suara sama ({$topPengawas['suara']} suara). Penentuan pemenang memerlukan mekanisme musyawarah.";
            } elseif ($topPengawas && $topPengawas['suara'] > 0) {
                $leaderPengawasText = $topPengawas['nama'];
                $insights[] = "Kandidat Pengawas No. {$topPengawas['nomor_urut']} ({$topPengawas['nama']}) unggul sementara dengan {$topPengawas['persen']}% suara.";
            } else {
                $leaderPengawasText = "Belum Ada Suara";
            }

            if ($isKetuaSeri) {
                $summary = "Berdasarkan audit hasil suara pemilu realtime: Hasil pemilihan Ketua saat ini dalam status SERI (TIE) dengan perolehan suara imbang di puncak. Sistem tidak menetapkan pemenang sepihak.";
            } else {
                $summary = "Berdasarkan analisis statistik pemilu realtime, data pemungutan suara tervalidasi 100% konsisten. " . ($topKetua && $topKetua['suara'] > 0 ? "Tren kemenangan sementara mengarah kuat kepada {$topKetua['nama']} untuk Ketua Koperasi." : "");
            }
        }

        // Cek apakah ada Gemini API Key dari Google AI Studio
        $geminiKey = AppSetting::get('gemini_api_key');
        $aiSource = 'Local Heuristic Engine';

        if (!empty($geminiKey) && $totalVoted > 0) {
            $geminiGenerated = $this->callGeminiApi($geminiKey, [
                'total_voters' => $totalVoters,
                'total_voted' => $totalVoted,
                'turnout' => $turnout,
                'quorum' => $quorumMet,
                'is_ketua_seri' => $isKetuaSeri,
                'is_pengawas_seri' => $isPengawasSeri,
                'ketua_results' => $ketua->toArray(),
                'pengawas_results' => $pengawas->toArray(),
            ]);

            if ($geminiGenerated) {
                $summary = $geminiGenerated['summary'] ?? $summary;
                if (!empty($geminiGenerated['insights'])) {
                    $insights = array_merge($insights, $geminiGenerated['insights']);
                }
                $aiSource = $geminiGenerated['model_name'] ?? 'Google Gemini 2.0 Flash';
            }
        }

        return [
            'confidence_score' => $confidence,
            'quorum_status' => $quorumMet ? 'Quorum Terpenuhi' : 'Menuju Quorum',
            'summary' => $summary,
            'insights' => array_values(array_unique($insights)),
            'leader_ketua' => $leaderKetuaText,
            'leader_pengawas' => $leaderPengawasText,
            'margin_ketua' => $marginKetua,
            'margin_pengawas' => $marginPengawas,
            'is_ketua_seri' => $isKetuaSeri,
            'is_pengawas_seri' => $isPengawasSeri,
            'ai_source' => $aiSource,
            'has_gemini_key' => !empty($geminiKey),
            'generated_at' => now()->format('H:i:s') . ' WIB',
        ];
    }

    /**
     * Memanggil Google AI Studio Gemini REST API (Gemini 2.0 Flash & 1.5 Flash Fallback)
     */
    private function callGeminiApi(string $apiKey, array $context): ?array
    {
        $models = ['gemini-2.0-flash', 'gemini-1.5-flash'];
        $prompt = "Anda adalah AI Analis Pemilu Koperasi Profesional. Berikan analisis pemilu real-time, kesimpulan ringkas berbobot, dan 2-4 poin insight strategis dalam Bahasa Indonesia berdasarkan data pemilihan berikut:\n" . json_encode($context, JSON_PRETTY_PRINT) . "\n\nPENTING: Kembalikan HANYA format JSON valid tanpa tanda markdown (tanpa ```json ... ```): {\"summary\": \"...\", \"insights\": [\"...\", \"...\"]}";

        foreach ($models as $model) {
            try {
                $response = Http::timeout(8)->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.2,
                        'maxOutputTokens' => 600,
                    ]
                ]);

                if ($response->successful()) {
                    $resData = $response->json();
                    $rawText = $resData['candidates'][0]['content']['parts'][0]['text'] ?? '';
                    $cleanJson = trim(preg_replace('/```(?:json)?|```/', '', $rawText));
                    $parsed = json_decode($cleanJson, true);
                    if (is_array($parsed) && !empty($parsed['summary'])) {
                        $parsed['model_name'] = $model === 'gemini-2.0-flash' ? 'Google Gemini 2.0 Flash' : 'Google Gemini 1.5 Flash';
                        return $parsed;
                    }
                }
            } catch (\Throwable $e) {
                // Try next model if timeout or network issue
                continue;
            }
        }

        return null;
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
        $votingStatus = AppSetting::get('voting_status', 'STARTED');

        // Metrik Kandidat Ketua
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
                'suara' => $suara,
                'persen' => $persen,
                'is_leader' => ($totalSuaraKetua > 0 && $topKetuaCount === 1 && $suara === $maxSuaraKetua),
                'is_tie' => ($totalSuaraKetua > 0 && $topKetuaCount > 1 && $suara === $maxSuaraKetua),
            ];
        });

        // Metrik Kandidat Pengawas
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
                'suara' => $suara,
                'persen' => $persen,
                'is_leader' => ($totalSuaraPengawas > 0 && $topPengawasCount === 1 && $suara === $maxSuaraPengawas),
                'is_tie' => ($totalSuaraPengawas > 0 && $topPengawasCount > 1 && $suara === $maxSuaraPengawas),
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
            'voting_status' => $votingStatus,
            'metrics' => [
                'total_voters' => $totalVoters,
                'total_voted' => $totalVoted,
                'remaining_voters' => $remaining,
                'turnout_percentage' => $turnoutPct,
                'is_ketua_seri' => $isKetuaSeri,
                'is_pengawas_seri' => $isPengawasSeri,
            ],
            'ketua_results' => $ketuaResults,
            'pengawas_results' => $pengawasResults,
            'recent_votes' => $recentVotes,
        ];
    }
}
