<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\AppSetting;
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
        $votingStatus = AppSetting::get('voting_status', 'STARTED');
        $votingDeadline = AppSetting::get('voting_deadline', '');
        $deadlineFormatted = '';
        $deadlineTimestamp = null;
        if (!empty($votingDeadline) && strtotime($votingDeadline)) {
            $deadlineCarbon = \Carbon\Carbon::parse($votingDeadline);
            $deadlineFormatted = $deadlineCarbon->locale('id')->isoFormat('dddd, D MMMM Y - HH:mm') . ' WIB';
            $deadlineTimestamp = $deadlineCarbon->timestamp;
        }

        // Sample data pemilih untuk section Demo Simulation
        $demoVoters = Pemilih::orderBy('nik', 'asc')->get();

        $demoVoterMap = [];
        foreach ($demoVoters as $d) {
            $waktu = $d->voted_at ? $d->voted_at->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB' : '';
            $demoVoterMap[strtolower($d->rfid)] = [
                'pilih' => $d->pilih,
                'nama' => $d->nama,
                'voted_at' => $waktu,
            ];
        }

        return view('voter.tap', compact('demoVoters', 'votingStatus', 'demoVoterMap', 'votingDeadline', 'deadlineFormatted', 'deadlineTimestamp'));
    }

    /**
     * Memproses Tap Card RFID
     */
    public function processTap(Request $request)
    {
        $status = AppSetting::get('voting_status', 'STARTED');
        if ($status === 'PAUSED') {
            return redirect()->route('voter.tap')
                ->with('error', 'Sistem pemungutan suara sedang DI-JEDA (PAUSED) oleh Panitia Pemilihan. Silakan menunggu beberapa saat.');
        }
        if ($status === 'STOPPED') {
            return redirect()->route('voter.tap')
                ->with('error', 'Sistem pemungutan suara telah RESMI DITUTUP (STOPPED) oleh Panitia Pemilihan.');
        }

        $votingDeadline = AppSetting::get('voting_deadline', '');
        if (!empty($votingDeadline) && strtotime($votingDeadline)) {
            if (now()->greaterThan(\Carbon\Carbon::parse($votingDeadline))) {
                return redirect()->route('voter.tap')
                    ->with('error', 'Batas waktu pemungutan suara (deadline) telah berakhir pada ' . \Carbon\Carbon::parse($votingDeadline)->isoFormat('D MMMM Y, HH:mm') . ' WIB.');
            }
        }

        $request->validate([
            'rfid' => 'required|string',
        ]);

        $search = trim($request->rfid);

        // Reference absensi_gebyar Flutter logic:
        // Readers can output hex UID, reversed byte hex, or decimal string padded to 10 digits
        $candidates = [$search, strtoupper($search), strtolower($search)];

        // Clean any non-hex/alphanumeric characters
        $cleanHex = preg_replace('/[^0-9A-Fa-f]/', '', $search);

        if (strlen($cleanHex) >= 8) {
            // Take 8 hex characters (Mifare 4-byte UID standard)
            $hex8 = substr($cleanHex, 0, 8);
            $candidates[] = strtoupper($hex8);

            // Byte reversal: bytes [3][2][1][0] instead of [0][1][2][3] (Flutter hextodes logic)
            // e.g. "E280681A" -> reversed: "1A6880E2"
            $b0 = substr($hex8, 0, 2);
            $b1 = substr($hex8, 2, 2);
            $b2 = substr($hex8, 4, 2);
            $b3 = substr($hex8, 6, 2);
            $reversedHex = $b3 . $b2 . $b1 . $b0;
            $candidates[] = strtoupper($reversedHex);

            // Convert reversed hex to decimal and pad to 10 digits (absensi_gebyar hextodes format)
            $decVal = hexdec($reversedHex);
            $candidates[] = str_pad((string)$decVal, 10, '0', STR_PAD_LEFT);
            $candidates[] = (string)$decVal;

            // Direct forward hex to decimal
            $directDec = hexdec($hex8);
            $candidates[] = str_pad((string)$directDec, 10, '0', STR_PAD_LEFT);
            $candidates[] = (string)$directDec;
        }

        // If numeric input from barcode/keywedge reader (e.g. 10 digit decimal)
        if (ctype_digit($search)) {
            $candidates[] = str_pad($search, 10, '0', STR_PAD_LEFT);
            $candidates[] = ltrim($search, '0');
            // Try decimal to hex reversal
            $intVal = (int)$search;
            $hexFromDec = sprintf('%08X', $intVal);
            $candidates[] = $hexFromDec;
            $b0 = substr($hexFromDec, 0, 2);
            $b1 = substr($hexFromDec, 2, 2);
            $b2 = substr($hexFromDec, 4, 2);
            $b3 = substr($hexFromDec, 6, 2);
            $candidates[] = strtoupper($b3 . $b2 . $b1 . $b0);
        }

        $candidates = array_unique(array_filter($candidates));

        // Cari pemilih berdasarkan RFID UID, NIK, atau representasi konversi
        $pemilih = Pemilih::where(function ($query) use ($candidates, $search) {
            $query->whereIn('rfid', $candidates)
                  ->orWhereIn('nik', $candidates)
                  ->orWhere('rfid', 'LIKE', '%' . $search . '%')
                  ->orWhere('nik', 'LIKE', '%' . $search . '%');
        })->first();

        if (!$pemilih) {
            ActivityLog::log('TAP_FAILED', 'VOTING', "Percobaan tap kartu tidak dikenal: [{$search}]");
            return redirect()->route('voter.tap')
                ->with('error', "Keplek belum bisa mengikuti voting.");
        }

        if ($pemilih->sudahMemilih()) {
            ActivityLog::log('TAP_REJECTED', 'VOTING', "Percobaan memilih ulang ditolak untuk NIK: {$pemilih->nik} ({$pemilih->nama})");
            $waktuFormatted = $pemilih->voted_at 
                ? $pemilih->voted_at->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB' 
                : 'sesi sebelumnya';

            return redirect()->route('voter.tap')
                ->with('error', "Hak suara untuk Anggota [{$pemilih->nik} - {$pemilih->nama}] telah digunakan pada {$waktuFormatted}.")
                ->with('already_voted', true)
                ->with('voter_name', $pemilih->nama)
                ->with('voted_time', $waktuFormatted);
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
        $status = AppSetting::get('voting_status', 'STARTED');
        if ($status !== 'STARTED') {
            return redirect()->route('voter.tap')
                ->with('error', 'Bilik suara tidak dapat diakses karena sistem pemungutan suara sedang ' . ($status === 'PAUSED' ? 'di-jeda sementara' : 'resmi ditutup') . '.');
        }

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
        $status = AppSetting::get('voting_status', 'STARTED');
        if ($status !== 'STARTED') {
            return redirect()->route('voter.tap')
                ->with('error', 'Suara Anda tidak dapat dikirim karena sistem pemungutan suara sedang ' . ($status === 'PAUSED' ? 'di-jeda sementara' : 'resmi ditutup') . '.');
        }

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

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Suara Anda berhasil dicatat secara sah!',
                    'redirect' => route('voter.tap'),
                ]);
            }

            return redirect()->route('voter.finalization');

        } catch (\Throwable $e) {
            ActivityLog::log('CAST_VOTE_ERROR', 'VOTING', "Gagal merekam suara untuk NIK {$voterNik}: " . $e->getMessage());

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mencatat suara: ' . $e->getMessage(),
                ], 422);
            }

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
