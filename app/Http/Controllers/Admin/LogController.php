<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $module = $request->query('module');
        $action = $request->query('action');
        $search = $request->query('search');

        $query = ActivityLog::query();

        if ($module) {
            $query->where('module', $module);
        }

        if ($action) {
            $query->where('action', $action);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('user_identifier', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $query->orderBy('created_at', 'desc')->take(200)->get();
        $modules = ActivityLog::distinct()->pluck('module')->sort();
        $actions = ActivityLog::distinct()->pluck('action')->sort();

        return view('admin.logs.index', compact('logs', 'modules', 'actions', 'module', 'action', 'search'));
    }

    /**
     * AI Deep Forensic & Security Threat Intelligence Reasoning
     */
    public function getAiAnalysis(\App\Services\AiReasoningService $aiService)
    {
        $totalLogs = ActivityLog::count();
        $unknownCardAttempts = ActivityLog::where('action', 'like', '%UNKNOWN%')
            ->orWhere('description', 'like', '%tidak dikenali%')
            ->orWhere('description', 'like', '%asing%')
            ->count();

        $alreadyVotedAttempts = ActivityLog::where('action', 'like', '%ALREADY%')
            ->orWhere('description', 'like', '%sudah memilih%')
            ->count();

        $recentLogs = ActivityLog::latest()
            ->take(30)
            ->get(['action', 'module', 'description', 'ip_address', 'created_at'])
            ->toArray();

        $analysis = $aiService->analyzeAuditLogs($recentLogs, [
            'total_logs' => $totalLogs,
            'unknown_card_attempts' => $unknownCardAttempts,
            'already_voted_attempts' => $alreadyVotedAttempts,
        ]);

        return response()->json([
            'success' => true,
            'data' => $analysis,
        ]);
    }
}
