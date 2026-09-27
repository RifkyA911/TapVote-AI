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
            $msg = 'Sesi belum aktif. Silakan tap kartu ID RFID Anda terlebih dahulu.';
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                    'redirect' => route('voter.tap'),
                ], 401);
            }
            return redirect()->route('voter.tap')
                ->with('error', $msg);
        }

        $pemilih = Pemilih::find($voterNik);

        if (!$pemilih) {
            session()->forget('voter_nik');
            $msg = 'Data pemilih tidak ditemukan dalam sistem.';
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                    'redirect' => route('voter.tap'),
                ], 404);
            }
            return redirect()->route('voter.tap')
                ->with('error', $msg);
        }

        if (!$pemilih->can_raffle) {
            session()->forget('voter_nik');
            $msg = "Akses Ditolak: Anggota [{$pemilih->nik} - {$pemilih->nama}] dinonaktifkan dari partisipasi undian & pemilihan.";
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                    'redirect' => route('voter.tap'),
                ], 403);
            }
            return redirect()->route('voter.tap')
                ->with('error', $msg);
        }

        if ($pemilih->sudahMemilih()) {
            session()->forget('voter_nik');
            $waktu = $pemilih->voted_at ? $pemilih->voted_at->timezone('Asia/Jakarta')->format('H:i') . ' WIB' : 'sesi sebelumnya';
            $msg = "Hak suara untuk NIK {$pemilih->nik} ({$pemilih->nama}) telah digunakan pada {$waktu}.";
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                    'redirect' => route('voter.tap'),
                ], 403);
            }
            return redirect()->route('voter.tap')
                ->with('error', $msg);
        }

        return $next($request);
    }
}
