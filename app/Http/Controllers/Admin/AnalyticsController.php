<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\AppSetting;
use App\Models\HasilKetua;
use App\Models\HasilPengawas;
use App\Models\KandidatKetua;
use App\Models\KandidatPengawas;
use App\Models\Pemilih;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * "Mata Langit" (Sky Eye Telemetry & Deep Analytics)
     */
    public function index(Request $request)
    {
        $data = $this->gatherAnalyticsData($request);
        return view('admin.analytics.index', $data);
    }

    /**
     * Export Analytics Telemetry Report to PDF
     */
    public function exportPdf(Request $request)
    {
        $data = $this->gatherAnalyticsData($request);
        $pdf = Pdf::loadView('admin.analytics.pdf', $data);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('laporan_telemetri_analytics_' . date('Ymd_His') . '.pdf');
    }

    /**
     * Centralized Data Aggregator for Analytics Telemetry & Filters
     */
    private function gatherAnalyticsData(Request $request)
    {
        $selectedDept = $request->query('dept', '');
        $selectedDate = $request->query('date', '');
        $selectedShift = $request->query('shift', '');

        // Base voters query with filters
        $voterQuery = Pemilih::query();
        if ($selectedDept) {
            $voterQuery->where('dept', $selectedDept);
        }

        $totalVoters = (clone $voterQuery)->count();

        // Query voted members with date/shift filters
        $votedQuery = (clone $voterQuery)->where('pilih', 'T');

        if ($selectedDate === 'today') {
            $votedQuery->whereDate('voted_at', now()->toDateString());
        } elseif (!empty($selectedDate) && strtotime($selectedDate)) {
            $votedQuery->whereDate('voted_at', $selectedDate);
        }

        if ($selectedShift === 'morning') {
            $votedQuery->whereTime('voted_at', '>=', '06:00:00')->whereTime('voted_at', '<', '12:00:00');
        } elseif ($selectedShift === 'afternoon') {
            $votedQuery->whereTime('voted_at', '>=', '12:00:00')->whereTime('voted_at', '<', '18:00:00');
        } elseif ($selectedShift === 'night') {
            $votedQuery->whereTime('voted_at', '>=', '18:00:00')->whereTime('voted_at', '<=', '23:59:59');
        }

        $totalVoted = $votedQuery->count();
        $remaining = max(0, $totalVoters - $totalVoted);
        $turnoutPct = $totalVoters > 0 ? round(($totalVoted / $totalVoters) * 100, 1) : 0;
        $quorumThreshold = (float) AppSetting::get('quorum_percentage', 50.0);
        $quorumMet = $turnoutPct >= $quorumThreshold;

        // Distinct departments list for filter dropdown
        $allDepartments = Pemilih::distinct()->orderBy('dept')->pluck('dept');

        // 1. Department Breakdown (Real Database Telemetry)
        $deptBaseQuery = Pemilih::query();
        if ($selectedDept) {
            $deptBaseQuery->where('dept', $selectedDept);
        }

        $deptStats = $deptBaseQuery->select('dept', 
                DB::raw('COUNT(*) as total_members'),
                DB::raw("SUM(CASE WHEN pilih = 'T' THEN 1 ELSE 0 END) as voted_members"),
                DB::raw("SUM(CASE WHEN pilih = 'F' THEN 1 ELSE 0 END) as pending_members")
            )
            ->groupBy('dept')
            ->orderBy('total_members', 'desc')
            ->get()
            ->map(function ($d) {
                $pct = $d->total_members > 0 ? round(($d->voted_members / $d->total_members) * 100, 1) : 0;
                return [
                    'dept' => $d->dept,
                    'total' => $d->total_members,
                    'voted' => $d->voted_members,
                    'pending' => $d->pending_members,
                    'pct' => $pct,
                ];
            });

        // 2. Hourly Voting Velocity (Histogram) - Database driver agnostic
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $hourExpr = $isSqlite ? "strftime('%H', voted_at)" : "DATE_FORMAT(voted_at, '%H')";

        $hourlyQuery = Pemilih::where('pilih', 'T')->whereNotNull('voted_at');
        if ($selectedDept) {
            $hourlyQuery->where('dept', $selectedDept);
        }
        if ($selectedDate === 'today') {
            $hourlyQuery->whereDate('voted_at', now()->toDateString());
        } elseif (!empty($selectedDate) && strtotime($selectedDate)) {
            $hourlyQuery->whereDate('voted_at', $selectedDate);
        }

        $hourlyDistribution = $hourlyQuery
            ->select(DB::raw("{$hourExpr} as hour_slot"), DB::raw('COUNT(*) as vote_count'))
            ->groupBy('hour_slot')
            ->orderBy('hour_slot', 'asc')
            ->pluck('vote_count', 'hour_slot')
            ->toArray();

        // Standard 24-hour timeline slots (00 to 23)
        $hoursLabels = [];
        $hoursValues = [];
        for ($i = 0; $i < 24; $i++) {
            $slot = sprintf('%02d', $i);
            $hoursLabels[] = $slot . ':00';
            $hoursValues[] = $hourlyDistribution[$slot] ?? 0;
        }

        $peakHourIndex = array_search(max($hoursValues ?: [0]), $hoursValues);
        $peakHourLabel = $hoursLabels[$peakHourIndex] ?? '-';
        $peakHourVotes = max($hoursValues ?: [0]);

        // 3. Hardware RFID Authenticity & Anomaly Audit
        $unknownCardAttempts = ActivityLog::where('action', 'like', '%UNKNOWN%')
            ->orWhere('description', 'like', '%tidak dikenali%')
            ->orWhere('description', 'like', '%asing%')
            ->count();

        $alreadyVotedAttempts = ActivityLog::where('action', 'like', '%ALREADY%')
            ->orWhere('description', 'like', '%sudah memilih%')
            ->count();

        $totalValidScans = $totalVoted;
        $totalScanAttempts = $totalValidScans + $unknownCardAttempts + $alreadyVotedAttempts;
        $rfidAuthenticityRate = $totalScanAttempts > 0 ? round(($totalValidScans / $totalScanAttempts) * 100, 1) : 100.0;

        // 4. Device & Browser User-Agent Diagnostics from Activity Logs
        $logsWithAgents = ActivityLog::whereNotNull('user_agent')
            ->select('user_agent', DB::raw('count(*) as count'))
            ->groupBy('user_agent')
            ->orderBy('count', 'desc')
            ->take(8)
            ->get();

        // 5. Recent Forensic Anomaly Timeline
        $recentSecurityEvents = ActivityLog::where(function ($q) {
                $q->where('action', 'like', '%UNKNOWN%')
                  ->orWhere('action', 'like', '%REJECT%')
                  ->orWhere('action', 'like', '%FAIL%')
                  ->orWhere('action', 'like', '%ALREADY%');
            })
            ->latest()
            ->take(10)
            ->get();

        // 6. Candidate Live Rankings (Ketua & Pengawas)
        $rankingKetua = KandidatKetua::withCount('perolehanSuara')
            ->orderBy('perolehan_suara_count', 'desc')
            ->orderBy('nomor_urut', 'asc')
            ->get();

        $rankingPengawas = KandidatPengawas::withCount('perolehanSuara')
            ->orderBy('perolehan_suara_count', 'desc')
            ->orderBy('nomor_urut', 'asc')
            ->get();

        return compact(
            'totalVoters',
            'totalVoted',
            'remaining',
            'turnoutPct',
            'quorumThreshold',
            'quorumMet',
            'allDepartments',
            'selectedDept',
            'selectedDate',
            'selectedShift',
            'deptStats',
            'hoursLabels',
            'hoursValues',
            'peakHourLabel',
            'peakHourVotes',
            'unknownCardAttempts',
            'alreadyVotedAttempts',
            'rfidAuthenticityRate',
            'totalScanAttempts',
            'logsWithAgents',
            'recentSecurityEvents',
            'rankingKetua',
            'rankingPengawas'
        );
    }

    /**
     * AI Deep Reasoning & Analytical Telemetry Endpoint
     */
    public function getAiAnalysis(\App\Services\AiReasoningService $aiService)
    {
        $totalVoters = Pemilih::count();
        $totalVoted = Pemilih::where('pilih', 'T')->count();
        $turnoutPct = $totalVoters > 0 ? round(($totalVoted / $totalVoters) * 100, 1) : 0;

        $deptStats = Pemilih::select('dept', 
                DB::raw('COUNT(*) as total_members'),
                DB::raw("SUM(CASE WHEN pilih = 'T' THEN 1 ELSE 0 END) as voted_members")
            )
            ->groupBy('dept')
            ->get()
            ->map(function ($d) {
                return [
                    'dept' => $d->dept,
                    'total' => $d->total_members,
                    'voted' => $d->voted_members,
                    'pct' => $d->total_members > 0 ? round(($d->voted_members / $d->total_members) * 100, 1) : 0,
                ];
            })->toArray();

        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $hourExpr = $isSqlite ? "strftime('%H', voted_at)" : "DATE_FORMAT(voted_at, '%H')";

        $hourlyDistribution = Pemilih::where('pilih', 'T')
            ->whereNotNull('voted_at')
            ->select(DB::raw("{$hourExpr} as hour_slot"), DB::raw('COUNT(*) as vote_count'))
            ->groupBy('hour_slot')
            ->orderBy('hour_slot', 'asc')
            ->pluck('vote_count', 'hour_slot')
            ->toArray();

        $peakHour = '-';
        $peakVotes = 0;
        if (!empty($hourlyDistribution)) {
            $peakSlot = array_search(max($hourlyDistribution), $hourlyDistribution);
            $peakHour = $peakSlot . ':00';
            $peakVotes = max($hourlyDistribution);
        }

        $analysis = $aiService->analyzeTelemetry([
            'total_voters' => $totalVoters,
            'total_voted' => $totalVoted,
            'turnout_pct' => $turnoutPct,
            'departments' => $deptStats,
            'peak_hour' => $peakHour,
            'peak_votes' => $peakVotes,
        ]);

        return response()->json([
            'success' => true,
            'data' => $analysis,
        ]);
    }
}
