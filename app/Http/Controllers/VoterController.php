<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\HasilKetua;
use App\Models\HasilPengawas;
use App\Models\KandidatKetua;
use App\Models\KandidatPengawas;
use App\Models\Pemilih;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoterController extends Controller
{
    /**
     * Tampilan Tap Card RFID Mifare ISO 14443A
     */
    public function showTapPage()
    {
        // Sample data pemilih untuk section Demo Simulation
        $demoVoters = Pemilih::orderBy('nik', 'asc')->get();

        return view('voter.tap', compact('demoVoters'));
    }

    /**
     * Memproses Tap Card RFID
     */
    public function processTap(Request $request)
    {
        $request->validate([
            'rfid' => 'required|string',
        ]);

        $search = trim($request->rfid);

        // Cari pemilih berdasarkan RFID UID atau NIK (dukungan scanner & manual)
        $pemilih = Pemilih::where('rfid', $search)
            ->orWhere('nik', $search)
            ->first();

        if (!$pemilih) {
            ActivityLog::log('TAP_FAILED', 'VOTING', "Percobaan tap kartu tidak dikenal: [{$search}]");
            return redirect()->route('voter.tap')
                ->with('error', "Kartu RFID UID [{$search}] tidak terdaftar dalam Daftar Pemilih Tetap (DPT).");
        }

        if ($pemilih->sudahMemilih()) {
            ActivityLog::log('TAP_REJECTED', 'VOTING', "Percobaan memilih ulang ditolak untuk NIK: {$pemilih->nik} ({$pemilih->nama})");
            return redirect()->route('voter.tap')
                ->with('error', "Hak suara untuk Anggota [{$pemilih->nik} - {$pemilih->nama}] telah digunakan pada " . ($pemilih->voted_at ? $pemilih->voted_at->format('H:i:s WIB') : 'sesi sebelumnya') . ".");
        }

        // Simpan sesi pemilih aktif
        session(['voter_nik' => $pemilih->nik]);

        ActivityLog::log('TAP_SUCCESS', 'VOTING', "Anggota {$pemilih->nama} (NIK: {$pemilih->nik}) berhasil tap ID Card dan masuk bilik suara.");

        return redirect()->route('voter.vote');
    }

    /**
     * Halaman Pemilihan Suara (Ketua & Pengawas Sekaligus)
     */
    public function showVotePage()
    {
        $voterNik = session('voter_nik');
        $pemilih = Pemilih::findOrFail($voterNik);

        $kandidatKetua = KandidatKetua::orderBy('nomor_urut', 'asc')->get();
        $kandidatPengawas = KandidatPengawas::orderBy('nomor_urut', 'asc')->get();

        return view('voter.vote', compact('pemilih', 'kandidatKetua', 'kandidatPengawas'));
    }

    /**
     * Submit Suara Atomik
     */
    public function submitVote(Request $request)
    {
        $voterNik = session('voter_nik');

        $request->validate([
            'ketua_nik' => 'required|exists:kandidat_ketua,nik',
            'pengawas_nik' => 'required|exists:kandidat_pengawas,nik',
        ], [
            'ketua_nik.required' => 'Wajib memilih 1 Kandidat Ketua Koperasi.',
            'pengawas_nik.required' => 'Wajib memilih 1 Kandidat Pengawas Koperasi.',
        ]);

        try {
            DB::transaction(function () use ($voterNik, $request) {
                // Lock row pemilih untuk proteksi concurrency/double-vote
                $pemilih = Pemilih::where('nik', $voterNik)->lockForUpdate()->first();

                if (!$pemilih || $pemilih->sudahMemilih()) {
                    throw new \Exception('Hak suara Anda telah digunakan sebelumnya.');
                }

                // Simpan Pilihan Ketua
                HasilKetua::create([
                    'pemilih_nik' => $pemilih->nik,
                    'ketua_nik' => $request->ketua_nik,
                ]);

                // Simpan Pilihan Pengawas
                HasilPengawas::create([
                    'pemilih_nik' => $pemilih->nik,
                    'pengawas_nik' => $request->pengawas_nik,
                ]);

                // Update Status Pemilih: pilih = 'T' & set timestamp voted_at
                $pemilih->update([
                    'pilih' => 'T',
                    'voted_at' => now(),
                ]);

                ActivityLog::log(
                    'CAST_VOTE_SUCCESS',
                    'VOTING',
                    "Suara tercatat sah untuk NIK {$pemilih->nik} ({$pemilih->nama}). Pilihan Ketua: {$request->ketua_nik}, Pilihan Pengawas: {$request->pengawas_nik}"
                );
            });

            // Set flag sukses di session sebelum menghapus sesi pemilih
            session()->flash('vote_success', true);
            session()->forget('voter_nik');

            return redirect()->route('voter.finalization');

        } catch (\Throwable $e) {
            ActivityLog::log('CAST_VOTE_ERROR', 'VOTING', "Gagal merekam suara untuk NIK {$voterNik}: " . $e->getMessage());

            return redirect()->route('voter.vote')
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Halaman Finalisasi (Animasi & Countdown 5 Detik)
     */
    public function showFinalization()
    {
        return view('voter.finalization');
    }

    /**
     * Batalkan Sesi Pemilih
     */
    public function logout()
    {
        $voterNik = session('voter_nik');
        if ($voterNik) {
            ActivityLog::log('VOTER_CANCEL', 'VOTING', "Pemilih NIK {$voterNik} membatalkan sesi sebelum memilih.");
            session()->forget('voter_nik');
        }

        return redirect()->route('voter.tap');
    }
}
