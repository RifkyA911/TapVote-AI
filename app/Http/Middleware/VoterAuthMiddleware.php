<?php

namespace App\Http\Middleware;

use App\Models\Pemilih;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VoterAuthMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $voterNik = session('voter_nik');

        if (!$voterNik) {
            return redirect()->route('voter.tap')
                ->with('error', 'Sesi belum aktif. Silakan tap kartu ID RFID Anda terlebih dahulu.');
        }

        $pemilih = Pemilih::find($voterNik);

        if (!$pemilih) {
            session()->forget('voter_nik');
            return redirect()->route('voter.tap')
                ->with('error', 'Data pemilih tidak ditemukan dalam sistem.');
        }

        if ($pemilih->sudahMemilih()) {
            session()->forget('voter_nik');
            return redirect()->route('voter.tap')
                ->with('error', "Hak suara untuk NIK {$pemilih->nik} ({$pemilih->nama}) telah digunakan pada " . ($pemilih->voted_at ? $pemilih->voted_at->format('H:i:s') : 'sesi sebelumnya') . '.');
        }

        return $next($request);
    }
}
