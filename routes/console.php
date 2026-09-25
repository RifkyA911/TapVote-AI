<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('tapvote:health', function () {
    $this->info("=== TapVote-AI System Health Check ===");
    
    // 1. Database Check
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $this->line("  ✓ Database: Connected (" . config('database.default') . ")");
    } catch (\Throwable $e) {
        $this->error("  ✗ Database: Disconnected (" . $e->getMessage() . ")");
    }

    // 2. Storage Directory Junction Check
    $storageLinked = is_dir(public_path('storage'));
    if ($storageLinked) {
        $this->line("  ✓ Storage: Linked (public/storage exists)");
    } else {
        $this->warn("  ! Storage: Not linked. Run 'php artisan storage:link'");
    }

    // 3. Gemini API Key
    $geminiKey = \App\Models\AppSetting::get('gemini_api_key');
    if (!empty($geminiKey)) {
        $this->line("  ✓ Gemini AI: Configured (" . substr($geminiKey, 0, 8) . "...)");
    } else {
        $this->comment("  - Gemini AI: No key configured (Heuristics fallback active)");
    }

    // 4. Voting Status
    $status = \App\Models\AppSetting::get('voting_status', 'STARTED');
    $this->line("  ✓ Voting Status: " . $status);

    $this->info("System check completed.");
})->purpose('Inspect TapVote-AI election system health and connectivity');

Artisan::command('tapvote:recap', function () {
    $totalVoters = \App\Models\Pemilih::count();
    $voted = \App\Models\Pemilih::where('pilih', 'T')->count();
    $turnout = $totalVoters > 0 ? round(($voted / $totalVoters) * 100, 2) : 0;
    
    $this->info("=== REKAPITULASI HASIL PEMILIHAN TAPVOTE-AI ===");
    $this->line("Total DPT: {$totalVoters} Pemilih");
    $this->line("Suara Masuk: {$voted} ({$turnout}% Partisipasi)");
    $this->line("Kuorum: " . ($turnout >= 50.0 ? "TERPENUHI (SAH)" : "BELUM KUORUM"));
})->purpose('Display live election turnout and quorum status in terminal');
