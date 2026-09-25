<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Doorprize;
use App\Models\DoorprizeWinner;
use App\Models\HasilKetua;
use App\Models\HasilPengawas;
use App\Models\KandidatKetua;
use App\Models\KandidatPengawas;
use App\Models\Pemilih;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Laporan 1: Siapa Pemenang Ketua Koperasi & Rincian Suara (Dengan Deteksi Hasil Seri)
     */
    public function pemenangKetua()
    {
        $kandidatList = KandidatKetua::withCount('perolehanSuara')
            ->orderBy('perolehan_suara_count', 'desc')
            ->orderBy('nomor_urut', 'asc')
            ->get();

        $totalSuara = HasilKetua::count();
        $maxVotes = $kandidatList->max('perolehan_suara_count') ?? 0;
        $topCandidates = $maxVotes > 0 ? $kandidatList->where('perolehan_suara_count', $maxVotes)->values() : collect();
        $isSeri = $topCandidates->count() > 1;

        // Pemenang tunggal hanya jika tidak seri dan memiliki suara > 0
        $pemenang = (!$isSeri && $maxVotes > 0) ? $topCandidates->first() : null;

        // Rincian Suara per Departemen
        $deptBreakdown = DB::table('hasil_ketua')
            ->join('pemilih', 'hasil_ketua.pemilih_nik', '=', 'pemilih.nik')
            ->join('kandidat_ketua', 'hasil_ketua.ketua_nik', '=', 'kandidat_ketua.nik')
            ->select('pemilih.dept', 'kandidat_ketua.nama as kandidat_nama', 'kandidat_ketua.nomor_urut', DB::raw('count(*) as total'))
            ->groupBy('pemilih.dept', 'kandidat_ketua.nama', 'kandidat_ketua.nomor_urut')
            ->orderBy('pemilih.dept')
            ->orderBy('kandidat_ketua.nomor_urut')
            ->get();

        $kandidatKetua = $kandidatList;
        return view('admin.reports.ketua', compact('kandidatList', 'kandidatKetua', 'totalSuara', 'pemenang', 'deptBreakdown', 'isSeri', 'topCandidates', 'maxVotes'));
    }

    /**
     * Laporan 2: Siapa Pemenang Pengawas Koperasi & Rincian Suara (Dengan Deteksi Hasil Seri)
     */
    public function pemenangPengawas()
    {
        $kandidatList = KandidatPengawas::withCount('perolehanSuara')
            ->orderBy('perolehan_suara_count', 'desc')
            ->orderBy('nomor_urut', 'asc')
            ->get();

        $totalSuara = HasilPengawas::count();
        $maxVotes = $kandidatList->max('perolehan_suara_count') ?? 0;
        $topCandidates = $maxVotes > 0 ? $kandidatList->where('perolehan_suara_count', $maxVotes)->values() : collect();
        $isSeri = $topCandidates->count() > 1;

        // Pemenang tunggal hanya jika tidak seri dan memiliki suara > 0
        $pemenang = (!$isSeri && $maxVotes > 0) ? $topCandidates->first() : null;

        // Rincian Suara per Departemen
        $deptBreakdown = DB::table('hasil_pengawas')
            ->join('pemilih', 'hasil_pengawas.pemilih_nik', '=', 'pemilih.nik')
            ->join('kandidat_pengawas', 'hasil_pengawas.pengawas_nik', '=', 'kandidat_pengawas.nik')
            ->select('pemilih.dept', 'kandidat_pengawas.nama as kandidat_nama', 'kandidat_pengawas.nomor_urut', DB::raw('count(*) as total'))
            ->groupBy('pemilih.dept', 'kandidat_pengawas.nama', 'kandidat_pengawas.nomor_urut')
            ->orderBy('pemilih.dept')
            ->orderBy('kandidat_pengawas.nomor_urut')
            ->get();

        $kandidatPengawas = $kandidatList;
        return view('admin.reports.pengawas', compact('kandidatList', 'kandidatPengawas', 'totalSuara', 'pemenang', 'deptBreakdown', 'isSeri', 'topCandidates', 'maxVotes'));
    }

    /**
     * Laporan 3: Trace Back Anggota Memilih Ketua Siapa & Pengawas Siapa
     */
    public function traceback(Request $request)
    {
        $search = $request->query('search');
        $dept = $request->query('dept');

        $query = Pemilih::where('pilih', 'T')
            ->with(['hasilKetua.kandidatKetua', 'hasilPengawas.kandidatPengawas']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('dept', 'like', "%{$search}%");
            });
        }

        if ($dept) {
            $query->where('dept', $dept);
        }

        $records = $query->orderBy('voted_at', 'desc')->get();
        $voters = $records;
        $totalVoted = Pemilih::where('pilih', 'T')->count();
        $departments = Pemilih::distinct()->pluck('dept')->filter()->sort()->values();

        return view('admin.reports.traceback', compact('records', 'voters', 'totalVoted', 'search', 'dept', 'departments'));
    }

    /**
     * Export Rekapitulasi Ketua ke Excel / CSV
     */
    public function exportKetua()
    {
        $kandidatList = KandidatKetua::withCount('perolehanSuara')
            ->orderBy('perolehan_suara_count', 'desc')
            ->orderBy('nomor_urut', 'asc')
            ->get();

        $totalSuara = HasilKetua::count();
        $maxVotes = $kandidatList->max('perolehan_suara_count') ?? 0;
        $topCount = $maxVotes > 0 ? $kandidatList->where('perolehan_suara_count', $maxVotes)->count() : 0;
        $isSeri = $topCount > 1;

        $csv = "\xEF\xBB\xBF"; // UTF-8 BOM for Microsoft Excel
        $csv .= "REKAPITULASI HASIL PEMILIHAN KETUA KOPERASI\n";
        $csv .= "Waktu Unduh," . date('Y-m-d H:i:s') . "\n";
        $csv .= "Total Suara Masuk," . $totalSuara . "\n";
        $csv .= "Status Pemilihan," . ($isSeri ? "HASIL SERI / DRAW (Perlu Putaran Kedua / Musyawarah)" : ($totalSuara > 0 ? "Telah Ada Pemenang Terpilih" : "Belum Ada Suara")) . "\n\n";
        $csv .= "Nomor Urut,Nama Calon Ketua,Perolehan Suara,Persentase Suara,Status\n";

        foreach ($kandidatList as $k) {
            $persen = $totalSuara > 0 ? round(($k->perolehan_suara_count / $totalSuara) * 100, 2) : 0;
            if ($isSeri && $k->perolehan_suara_count === $maxVotes && $maxVotes > 0) {
                $status = 'HASIL SERI (Suara Terbanyak Seimbang)';
            } elseif (!$isSeri && $k->perolehan_suara_count === $maxVotes && $maxVotes > 0) {
                $status = 'Pemenang Unggul Terpilih';
            } else {
                $status = 'Kandidat';
            }
            $csv .= "\"{$k->nomor_urut}\",\"{$k->nama}\",\"{$k->perolehan_suara_count}\",\"{$persen}%\",\"{$status}\"\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="rekapitulasi_ketua_' . date('Ymd_His') . '.csv"',
        ]);
    }

    /**
     * Export Rekapitulasi Pengawas ke Excel / CSV
     */
    public function exportPengawas()
    {
        $kandidatList = KandidatPengawas::withCount('perolehanSuara')
            ->orderBy('perolehan_suara_count', 'desc')
            ->orderBy('nomor_urut', 'asc')
            ->get();

        $totalSuara = HasilPengawas::count();
        $maxVotes = $kandidatList->max('perolehan_suara_count') ?? 0;
        $topCount = $maxVotes > 0 ? $kandidatList->where('perolehan_suara_count', $maxVotes)->count() : 0;
        $isSeri = $topCount > 1;

        $csv = "\xEF\xBB\xBF"; // UTF-8 BOM for Microsoft Excel
        $csv .= "REKAPITULASI HASIL PEMILIHAN PENGAWAS KOPERASI\n";
        $csv .= "Waktu Unduh," . date('Y-m-d H:i:s') . "\n";
        $csv .= "Total Suara Masuk," . $totalSuara . "\n";
        $csv .= "Status Pemilihan," . ($isSeri ? "HASIL SERI / DRAW (Perlu Putaran Kedua / Musyawarah)" : ($totalSuara > 0 ? "Telah Ada Pemenang Terpilih" : "Belum Ada Suara")) . "\n\n";
        $csv .= "Nomor Urut,Nama Calon Pengawas,Perolehan Suara,Persentase Suara,Status\n";

        foreach ($kandidatList as $k) {
            $persen = $totalSuara > 0 ? round(($k->perolehan_suara_count / $totalSuara) * 100, 2) : 0;
            if ($isSeri && $k->perolehan_suara_count === $maxVotes && $maxVotes > 0) {
                $status = 'HASIL SERI (Suara Terbanyak Seimbang)';
            } elseif (!$isSeri && $k->perolehan_suara_count === $maxVotes && $maxVotes > 0) {
                $status = 'Pemenang Unggul Terpilih';
            } else {
                $status = 'Kandidat';
            }
            $csv .= "\"{$k->nomor_urut}\",\"{$k->nama}\",\"{$k->perolehan_suara_count}\",\"{$persen}%\",\"{$status}\"\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="rekapitulasi_pengawas_' . date('Ymd_His') . '.csv"',
        ]);
    }

    /**
     * Export Trace Back ke CSV
     */
    public function exportTraceback()
    {
        $records = Pemilih::where('pilih', 'T')
            ->with(['hasilKetua.kandidatKetua', 'hasilPengawas.kandidatPengawas'])
            ->orderBy('voted_at', 'desc')
            ->get();

        $csv = "\xEF\xBB\xBF"; // UTF-8 BOM for Microsoft Excel
        $csv .= "NIK,Nama Anggota,Departemen,Pilihan Ketua,Pilihan Pengawas,Waktu Memilih\n";

        foreach ($records as $r) {
            $ketua = $r->hasilKetua?->kandidatKetua?->nama ?? '-';
            $pengawas = $r->hasilPengawas?->kandidatPengawas?->nama ?? '-';
            $waktu = $r->voted_at ? $r->voted_at->format('Y-m-d H:i:s') : '-';

            $csv .= "\"{$r->nik}\",\"{$r->nama}\",\"{$r->dept}\",\"{$ketua}\",\"{$pengawas}\",\"{$waktu}\"\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="audit_traceback_suara_' . date('Ymd_His') . '.csv"',
        ]);
    }

    /**
     * Export Rekapitulasi Ketua Resmi ke PDF (DomPDF)
     */
    public function exportKetuaPdf()
    {
        $kandidatList = KandidatKetua::withCount('perolehanSuara')
            ->orderBy('perolehan_suara_count', 'desc')
            ->orderBy('nomor_urut', 'asc')
            ->get();

        $totalSuara = HasilKetua::count();
        $maxVotes = $kandidatList->max('perolehan_suara_count') ?? 0;
        $topCount = $maxVotes > 0 ? $kandidatList->where('perolehan_suara_count', $maxVotes)->count() : 0;
        $isSeri = $topCount > 1;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.pdf_ketua', compact('kandidatList', 'totalSuara', 'maxVotes', 'isSeri'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('berita_acara_ketua_' . date('Ymd_His') . '.pdf');
    }

    /**
     * Export Rekapitulasi Pengawas Resmi ke PDF (DomPDF)
     */
    public function exportPengawasPdf()
    {
        $kandidatList = KandidatPengawas::withCount('perolehanSuara')
            ->orderBy('perolehan_suara_count', 'desc')
            ->orderBy('nomor_urut', 'asc')
            ->get();

        $totalSuara = HasilPengawas::count();
        $maxVotes = $kandidatList->max('perolehan_suara_count') ?? 0;
        $topCount = $maxVotes > 0 ? $kandidatList->where('perolehan_suara_count', $maxVotes)->count() : 0;
        $isSeri = $topCount > 1;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.pdf_pengawas', compact('kandidatList', 'totalSuara', 'maxVotes', 'isSeri'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('berita_acara_pengawas_' . date('Ymd_His') . '.pdf');
    }

    /**
     * Export Forensic Traceback ke PDF (DomPDF)
     */
    public function exportTracebackPdf()
    {
        $records = Pemilih::where('pilih', 'T')
            ->with(['hasilKetua.kandidatKetua', 'hasilPengawas.kandidatPengawas'])
            ->orderBy('voted_at', 'desc')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.pdf_traceback', compact('records'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('audit_traceback_suara_' . date('Ymd_His') . '.pdf');
    }

    /**
     * Export Rekap Pemenang & Master Doorprize ke PDF (DomPDF)
     */
    public function exportDoorprizePdf()
    {
        $totalEligible = Pemilih::where('pilih', 'T')->count();
        $doorprizes = Doorprize::withCount('winners')->orderBy('id', 'asc')->get();
        $winners = DoorprizeWinner::with(['doorprize', 'pemilih'])
            ->orderBy('won_at', 'desc')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.pdf_doorprize', compact('totalEligible', 'doorprizes', 'winners'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('rekap_doorprize_' . date('Ymd_His') . '.pdf');
    }

    /**
     * Export Rekap Pemenang & Hadiah Doorprize ke Excel / CSV
     */
    public function exportDoorprizeExcel()
    {
        $winners = DoorprizeWinner::with(['doorprize', 'pemilih'])
            ->orderBy('won_at', 'desc')
            ->get();

        $csv = "\xEF\xBB\xBF";
        $csv .= "REKAPITULASI PEMENANG UNDIAN DOORPRIZE - TAPVOTE AI\n";
        $csv .= "Tanggal Unduh," . date('Y-m-d H:i:s') . "\n";
        $csv .= "Total Pemenang Tercatat," . $winners->count() . "\n\n";
        $csv .= "No,Nama Hadiah,NIK Pemenang,Nama Pemenang,Departemen,Status Klaim,Waktu Menang\n";

        foreach ($winners as $index => $w) {
            $hadiah = $w->doorprize?->title ?? '-';
            $nik = $w->pemilih_nik;
            $nama = $w->pemilih?->nama ?? '-';
            $dept = $w->pemilih?->dept ?? '-';
            $status = $w->claim_status;
            $waktu = $w->won_at ? $w->won_at->format('Y-m-d H:i:s') : '-';

            $csv .= ($index + 1) . ",\"{$hadiah}\",\"{$nik}\",\"{$nama}\",\"{$dept}\",\"{$status}\",\"{$waktu}\"\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="rekap_doorprize_' . date('Ymd_His') . '.csv"',
        ]);
    }
     public function doorprize()
     {
         $eligibleQuery = Pemilih::where('pilih', 'T')->orderBy('voted_at', 'desc');
         $eligibleVoters = (clone $eligibleQuery)->paginate(15);
         $totalEligible = Pemilih::where('pilih', 'T')->count();

         // Master Hadiah Doorprize
         $doorprizes = Doorprize::withCount('winners')->orderBy('id', 'asc')->get();

         // Log Pemenang yang sudah tercatat
         $winners = DoorprizeWinner::with(['doorprize', 'pemilih'])
             ->orderBy('won_at', 'desc')
             ->get();

         // Pool pemilih untuk client-side rapid name animation
         $eligibleList = Pemilih::where('pilih', 'T')
             ->select('nik', 'nama', 'dept')
             ->get()
             ->map(function ($p) {
                 return [
                     'nik' => $p->nik,
                     'nama' => $p->nama,
                     'dept' => $p->dept,
                 ];
             });

         $pemenang = null;
         return view('admin.reports.doorprize', compact('eligibleVoters', 'totalEligible', 'doorprizes', 'winners', 'eligibleList', 'pemenang'));
     }

     /**
      * Eksekusi Pengundian Hadiah (Simpan ke Log Doorprize Winners)
      */
     public function drawWinner(Request $request)
     {
         $request->validate([
             'doorprize_id' => 'required|exists:doorprizes,id',
         ]);

         $doorprize = Doorprize::withCount('winners')->findOrFail($request->doorprize_id);

         if ($doorprize->remaining_slots <= 0) {
             return response()->json([
                 'success' => false,
                 'message' => "Kuota hadiah [{$doorprize->title}] sudah habis!",
             ], 422);
         }

         // Exclude anggota yang sudah memenangkan hadiah ini
         $existingWinners = DoorprizeWinner::where('doorprize_id', $doorprize->id)->pluck('nik');

         $winnerVoter = Pemilih::where('pilih', 'T')
             ->whereNotIn('nik', $existingWinners)
             ->inRandomOrder()
             ->first();

         if (!$winnerVoter) {
             return response()->json([
                 'success' => false,
                 'message' => "Tidak ada anggota sah yang tersisa untuk memenangkan hadiah ini.",
             ], 422);
         }

         // Simpan log pemenang resmi
         $winner = DoorprizeWinner::create([
             'doorprize_id' => $doorprize->id,
             'nik' => $winnerVoter->nik,
             'won_at' => now(),
         ]);

         ActivityLog::log(
             'DOORPRIZE_WINNER',
             'DOORPRIZE',
             "Anggota {$winnerVoter->nama} (NIK: {$winnerVoter->nik}, Dept: {$winnerVoter->dept}) memenangkan Doorprize: {$doorprize->title}"
         );

         return response()->json([
             'success' => true,
             'winner' => [
                 'id' => $winner->id,
                 'nik' => $winnerVoter->nik,
                 'nama' => $winnerVoter->nama,
                 'dept' => $winnerVoter->dept,
                 'won_at' => $winner->won_at->format('H:i:s d/m/Y'),
             ],
             'doorprize' => [
                 'id' => $doorprize->id,
                 'title' => $doorprize->title,
                 'category' => $doorprize->category,
                 'remaining_slots' => $doorprize->remaining_slots - 1,
             ],
             'message' => "Selamat kepada {$winnerVoter->nama} telah memenangkan {$doorprize->title}!"
         ]);
     }

     /**
      * Simpan Master Hadiah Doorprize Baru (Mendukung Upload Gambar)
      */
     public function storeDoorprize(Request $request)
     {
         $validated = $request->validate([
             'title' => 'required|string|max:255',
             'description' => 'nullable|string|max:1000',
             'category' => 'required|string|max:100',
             'quantity' => 'required|integer|min:1|max:1000',
             'sponsor' => 'nullable|string|max:255',
             'icon' => 'nullable|string|max:50',
             'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
         ]);

         $imagePath = null;
         if ($request->hasFile('image')) {
             $imagePath = $request->file('image')->store('doorprizes', 'public');
         }

         $doorprize = Doorprize::create([
             'title' => trim($validated['title']),
             'description' => !empty($validated['description']) ? trim($validated['description']) : null,
             'category' => trim($validated['category']),
             'quantity' => (int)$validated['quantity'],
             'sponsor' => !empty($validated['sponsor']) ? trim($validated['sponsor']) : null,
             'icon' => $validated['icon'] ?? 'gift',
             'image' => $imagePath,
         ]);

         ActivityLog::log('CREATE_DOORPRIZE', 'DOORPRIZE', "Menambahkan reward doorprize baru: {$doorprize->title} ({$doorprize->quantity} unit)");

         if ($request->wantsJson()) {
             return response()->json(['success' => true, 'doorprize' => $doorprize]);
         }

         return redirect()->back()->with('success', "Hadiah Doorprize {$doorprize->title} berhasil ditambahkan!");
     }

     /**
      * Update / Edit Master Hadiah Doorprize beserta Foto
      */
     public function updateDoorprize(Request $request, $id)
     {
         $doorprize = Doorprize::findOrFail($id);

         $validated = $request->validate([
             'title' => 'required|string|max:255',
             'description' => 'nullable|string|max:1000',
             'category' => 'required|string|max:100',
             'quantity' => 'required|integer|min:1|max:1000',
             'sponsor' => 'nullable|string|max:255',
             'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
         ]);

         if ($request->hasFile('image')) {
             if ($doorprize->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($doorprize->image)) {
                 \Illuminate\Support\Facades\Storage::disk('public')->delete($doorprize->image);
             }
             $doorprize->image = $request->file('image')->store('doorprizes', 'public');
         }

         $doorprize->title = trim($validated['title']);
         $doorprize->description = !empty($validated['description']) ? trim($validated['description']) : null;
         $doorprize->category = trim($validated['category']);
         $doorprize->quantity = (int)$validated['quantity'];
         $doorprize->sponsor = !empty($validated['sponsor']) ? trim($validated['sponsor']) : null;
         $doorprize->save();

         ActivityLog::log('UPDATE_DOORPRIZE', 'DOORPRIZE', "Memperbarui data hadiah doorprize: {$doorprize->title}");

         if ($request->wantsJson()) {
             return response()->json([
                 'success' => true,
                 'doorprize' => $doorprize,
                 'image_url' => $doorprize->image_url,
                 'message' => "Hadiah Doorprize {$doorprize->title} berhasil diperbarui!"
             ]);
         }

         return redirect()->back()->with('success', "Hadiah Doorprize {$doorprize->title} berhasil diperbarui!");
     }

     /**
      * Hapus Master Hadiah Doorprize
      */
     public function destroyDoorprize($id)
     {
         $doorprize = Doorprize::findOrFail($id);
         $title = $doorprize->title;
         $doorprize->delete();

         ActivityLog::log('DELETE_DOORPRIZE', 'DOORPRIZE', "Menghapus master doorprize: {$title}");

         return redirect()->back()->with('success', "Master hadiah {$title} berhasil dihapus.");
     }

     /**
      * Hapus / Batalkan Log Pemenang Doorprize
      */
     public function deleteWinner($id)
     {
         $winner = DoorprizeWinner::with(['doorprize', 'pemilih'])->findOrFail($id);
         $nama = $winner->pemilih?->nama ?? $winner->nik;
         $hadiah = $winner->doorprize?->title ?? 'Hadiah';

         $winner->delete();

         ActivityLog::log('CANCEL_WINNER', 'DOORPRIZE', "Membatalkan pemenang: {$nama} untuk {$hadiah}");

         return redirect()->back()->with('success', "Pemenang {$nama} berhasil dibatalkan dari daftar.");
     }

     /**
      * Update Status Klaim Pemenang Doorprize (Sudah Diterima, Ditolak, Alasan Lain)
      */
     public function updateWinnerStatus(Request $request, $id)
     {
         $validated = $request->validate([
             'status' => 'required|in:pending,accepted,rejected,other',
             'status_note' => 'nullable|string|max:500',
         ]);

         $winner = DoorprizeWinner::with(['doorprize', 'pemilih'])->findOrFail($id);
         $prevStatus = $winner->status;
         $winner->status = $validated['status'];
         $winner->status_note = !empty($validated['status_note']) ? trim($validated['status_note']) : null;
         
         if ($validated['status'] === 'accepted') {
             $winner->received_at = now();
         } elseif ($validated['status'] !== 'accepted') {
             $winner->received_at = null;
         }
         $winner->save();

         $nama = $winner->pemilih?->nama ?? $winner->nik;
         $statusLabel = match($winner->status) {
             'accepted' => 'Sudah Diterima',
             'rejected' => 'Ditolak',
             'other' => 'Lainnya / Alasan Khusus',
             default => 'Belum Diambil (Pending)'
         };

         ActivityLog::log(
             'DOORPRIZE_CLAIM_STATUS',
             'DOORPRIZE',
             "Status hadiah untuk {$nama} diperbarui menjadi [{$statusLabel}]. Catatan: " . ($winner->status_note ?? '-')
         );

         if ($request->wantsJson()) {
             return response()->json([
                 'success' => true,
                 'message' => "Status klaim hadiah {$nama} berhasil diubah menjadi {$statusLabel}.",
                 'winner' => [
                     'id' => $winner->id,
                     'status' => $winner->status,
                     'status_note' => $winner->status_note,
                     'received_at' => $winner->received_at ? $winner->received_at->format('H:i:s d/m/Y') : null,
                 ]
             ]);
         }

         return redirect()->back()->with('success', "Status klaim hadiah {$nama} berhasil diubah menjadi {$statusLabel}.");
     }

     /**
      * Halaman Khusus Panggung Penonton (Stage View Fullscreen untuk Proyektor)
      */
     public function publicDoorprize()
     {
         $doorprizes = Doorprize::withCount('winners')->orderBy('id', 'asc')->get();
         $totalEligible = Pemilih::where('pilih', 'T')->count();
         $winners = DoorprizeWinner::with(['doorprize', 'pemilih'])->latest('won_at')->get();

         $eligibleList = Pemilih::where('pilih', 'T')
             ->select('nik', 'nama', 'dept')
             ->get()
             ->map(function ($p) {
                 return [
                     'nik' => $p->nik,
                     'nama' => $p->nama,
                     'dept' => $p->dept,
                 ];
             });

         return view('doorprize_public', compact('doorprizes', 'totalEligible', 'winners', 'eligibleList'));
     }

     /**
      * API Data Sinkronisasi Panggung Penonton
      */
     public function publicDoorprizeData()
     {
         $doorprizes = Doorprize::withCount('winners')->orderBy('id', 'asc')->get()->map(function ($d) {
             return [
                 'id' => $d->id,
                 'title' => $d->title,
                 'category' => $d->category,
                 'quantity' => $d->quantity,
                 'remaining_slots' => $d->remaining_slots,
                 'icon' => $d->icon,
                 'sponsor' => $d->sponsor,
             ];
         });

         $winners = DoorprizeWinner::with(['doorprize', 'pemilih'])->latest('won_at')->get()->map(function ($w) {
             return [
                 'id' => $w->id,
                 'hadiah' => $w->doorprize?->title ?? '-',
                 'nama' => $w->pemilih?->nama ?? '-',
                 'dept' => $w->pemilih?->dept ?? '-',
                 'nik' => $w->nik,
                 'won_at' => $w->won_at ? $w->won_at->format('H:i:s d/m/Y') : '-',
             ];
         });

         return response()->json([
             'total_eligible' => Pemilih::where('pilih', 'T')->count(),
             'doorprizes' => $doorprizes,
             'winners' => $winners,
         ]);
     }
}
