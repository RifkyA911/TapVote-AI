<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KandidatKetuaController;
use App\Http\Controllers\Admin\KandidatPengawasController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\VoterController as AdminVoterController;
use App\Http\Controllers\LiveCountController;
use App\Http\Controllers\VoterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - TapVote AI E-Voting System
|--------------------------------------------------------------------------
*/

// Language Switcher
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
        session()->save();
        \Illuminate\Support\Facades\Cookie::queue(cookie()->forever('app_locale', $locale));
        \Illuminate\Support\Facades\App::setLocale($locale);
    }
    return redirect()->back();
})->name('lang.switch');

// Public Live Count SSE Dashboard
Route::get('/', [LiveCountController::class, 'index'])->name('home');
Route::get('/live-count/stream', [LiveCountController::class, 'stream'])->name('live.stream');
Route::get('/live-count/data', [LiveCountController::class, 'apiData'])->name('live.data');

// ==========================================
// VOTER FLOW (RFID Mifare ISO 14443A)
// ==========================================
Route::prefix('voter')->group(function () {
    Route::get('/', [VoterController::class, 'showTapPage'])->name('voter.tap');
    Route::post('/tap', [VoterController::class, 'processTap'])->name('voter.tap.process');
    Route::post('/logout', [VoterController::class, 'logout'])->name('voter.logout');
});

// Bilik Suara (Protected by voter.auth session)
Route::get('/vote', [VoterController::class, 'showVotePage'])
    ->middleware('voter.auth')
    ->name('voter.vote');

Route::post('/vote/store', [VoterController::class, 'submitVote'])
    ->middleware('voter.auth')
    ->name('voter.vote.store');

// Halaman Finalisasi & Auto-logout 5 detik
Route::get('/finalization', [VoterController::class, 'showFinalization'])->name('voter.finalization');


// ==========================================
// ADMIN AUTHENTICATION
// ==========================================
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});


// Public Audience Stage: Doorprize Big-Screen Presentation
Route::get('/doorprize', [ReportController::class, 'publicDoorprize'])->name('doorprize.public');
Route::get('/doorprize/data', [ReportController::class, 'publicDoorprizeData'])->name('doorprize.data');

// ==========================================
// ADMIN CONTROL PANEL (role.admin middleware)
// ==========================================
Route::prefix('admin')->middleware(['auth', 'role.admin'])->group(function () {
    // Dashboard & Live Realtime SSE
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/stream/results', [DashboardController::class, 'sseStream'])->name('admin.stream.results');
    Route::get('/api/live-results', [DashboardController::class, 'liveResults'])->name('admin.api.live-results');
    Route::get('/api/ai-conclusion', [DashboardController::class, 'getAiConclusion'])->name('admin.api.ai-conclusion');
    Route::post('/voting/status', [DashboardController::class, 'updateVotingStatus'])->name('admin.voting.status');
    Route::post('/settings/gemini-key', [DashboardController::class, 'saveGeminiKey'])->name('admin.settings.gemini-key');
    Route::post('/settings/gemini-test', [DashboardController::class, 'testGeminiKey'])->name('admin.settings.gemini-test');
    Route::get('/dashboard/export/excel', [DashboardController::class, 'exportRecapExcel'])->name('admin.dashboard.export.excel');
    Route::get('/dashboard/export/pdf', [DashboardController::class, 'exportRecapPdf'])->name('admin.dashboard.export.pdf');

    // CRUD Kandidat Ketua
    Route::resource('ketua', KandidatKetuaController::class, [
        'as' => 'admin',
    ])->parameters(['ketua' => 'nik']);

    // CRUD Kandidat Pengawas
    Route::resource('pengawas', KandidatPengawasController::class, [
        'as' => 'admin',
    ])->parameters(['pengawas' => 'nik']);

    // CRUD Voters & Query DPT
    Route::get('/voters', [AdminVoterController::class, 'index'])->name('admin.voters.index');
    Route::match(['GET', 'POST', 'QUERY'], '/voters/query', [AdminVoterController::class, 'queryVoters'])->name('admin.voters.query');
    Route::get('/voters/export', [AdminVoterController::class, 'export'])->name('admin.voters.export');
    Route::post('/voters', [AdminVoterController::class, 'store'])->name('admin.voters.store');
    Route::post('/voters/import', [AdminVoterController::class, 'import'])->name('admin.voters.import');
    Route::get('/voters/template', [AdminVoterController::class, 'downloadTemplate'])->name('admin.voters.template');
    Route::post('/voters/reset-votes', [AdminVoterController::class, 'resetVotes'])->name('admin.voters.reset');
    Route::delete('/voters/{nik}', [AdminVoterController::class, 'destroy'])->name('admin.voters.destroy');

    // Laporan-Laporan Resmi & Rekapitulasi
    Route::prefix('reports')->group(function () {
        Route::get('/ketua', [ReportController::class, 'pemenangKetua'])->name('admin.reports.ketua');
        Route::get('/ketua/export', [ReportController::class, 'exportKetua'])->name('admin.reports.ketua.export');
        Route::get('/pengawas', [ReportController::class, 'pemenangPengawas'])->name('admin.reports.pengawas');
        Route::get('/pengawas/export', [ReportController::class, 'exportPengawas'])->name('admin.reports.pengawas.export');
        Route::get('/traceback', [ReportController::class, 'traceback'])->name('admin.reports.traceback');
        Route::get('/traceback/export', [ReportController::class, 'exportTraceback'])->name('admin.reports.traceback.export');
        
        // Doorprize Modul & Master Rewards
        Route::get('/doorprize', [ReportController::class, 'doorprize'])->name('admin.reports.doorprize');
        Route::post('/doorprize/draw', [ReportController::class, 'drawWinner'])->name('admin.reports.doorprize.draw');
        Route::post('/doorprize/items', [ReportController::class, 'storeDoorprize'])->name('admin.reports.doorprize.store');
        Route::delete('/doorprize/items/{id}', [ReportController::class, 'destroyDoorprize'])->name('admin.reports.doorprize.destroy');
        Route::delete('/doorprize/winners/{id}', [ReportController::class, 'deleteWinner'])->name('admin.reports.doorprize.winner.destroy');
        Route::match(['POST', 'PATCH'], '/doorprize/winners/{id}/status', [ReportController::class, 'updateWinnerStatus'])->name('admin.reports.doorprize.winner.status');
    });

    // Audit Trail Logs Viewer
    Route::get('/logs', [LogController::class, 'index'])->name('admin.logs.index');
});
