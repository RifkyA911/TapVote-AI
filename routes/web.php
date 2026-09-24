<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KandidatKetuaController;
use App\Http\Controllers\Admin\KandidatPengawasController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\VoterController as AdminVoterController;
use App\Http\Controllers\VoterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - TapVote AI E-Voting System
|--------------------------------------------------------------------------
*/

// Redirect root to /voter or overview
Route::get('/', function () {
    return view('welcome');
})->name('home');

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


// ==========================================
// ADMIN CONTROL PANEL (role.admin middleware)
// ==========================================
Route::prefix('admin')->middleware(['auth', 'role.admin'])->group(function () {
    // Dashboard & Live Realtime SSE
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/stream/results', [DashboardController::class, 'sseStream'])->name('admin.stream.results');
    Route::get('/api/live-results', [DashboardController::class, 'liveResults'])->name('admin.api.live-results');

    // CRUD Kandidat Ketua
    Route::resource('ketua', KandidatKetuaController::class, [
        'as' => 'admin',
    ])->parameters(['ketua' => 'nik']);

    // CRUD Kandidat Pengawas
    Route::resource('pengawas', KandidatPengawasController::class, [
        'as' => 'admin',
    ])->parameters(['pengawas' => 'nik']);

    // CRUD Voters & Import Excel/CSV
    Route::get('/voters', [AdminVoterController::class, 'index'])->name('admin.voters.index');
    Route::post('/voters', [AdminVoterController::class, 'store'])->name('admin.voters.store');
    Route::post('/voters/import', [AdminVoterController::class, 'import'])->name('admin.voters.import');
    Route::get('/voters/template', [AdminVoterController::class, 'downloadTemplate'])->name('admin.voters.template');
    Route::post('/voters/reset-votes', [AdminVoterController::class, 'resetVotes'])->name('admin.voters.reset');
    Route::delete('/voters/{nik}', [AdminVoterController::class, 'destroy'])->name('admin.voters.destroy');

    // Laporan-Laporan Resmi
    Route::prefix('reports')->group(function () {
        Route::get('/ketua', [ReportController::class, 'pemenangKetua'])->name('admin.reports.ketua');
        Route::get('/pengawas', [ReportController::class, 'pemenangPengawas'])->name('admin.reports.pengawas');
        Route::get('/traceback', [ReportController::class, 'traceback'])->name('admin.reports.traceback');
        Route::get('/traceback/export', [ReportController::class, 'exportTraceback'])->name('admin.reports.traceback.export');
        Route::get('/doorprize', [ReportController::class, 'doorprize'])->name('admin.reports.doorprize');
    });

    // Audit Trail Logs Viewer
    Route::get('/logs', [LogController::class, 'index'])->name('admin.logs.index');
});
