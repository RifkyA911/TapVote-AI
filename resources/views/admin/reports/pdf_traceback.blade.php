<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forensic Traceback Report - TapVote AI</title>
    <style>
        @page { margin: 1.2cm; size: a4 portrait; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1e293b; line-height: 1.4; font-size: 9pt; margin: 0; padding: 0; }
        .header-table { width: 100%; border-bottom: 2px solid #0f172a; padding-bottom: 8px; margin-bottom: 14px; }
        .header-title { text-align: center; }
        .header-title h1 { margin: 0; font-size: 13pt; text-transform: uppercase; color: #0f172a; }
        .header-title h2 { margin: 2px 0 0 0; font-size: 10.5pt; color: #334155; }
        .header-title p { margin: 2px 0 0 0; font-size: 8pt; color: #64748b; }
        .table-data { width: 100%; border-collapse: collapse; font-size: 8.5pt; }
        .table-data th, .table-data td { border: 1px solid #cbd5e1; padding: 5px 8px; }
        .table-data th { background-color: #f8fafc; font-weight: bold; text-transform: uppercase; font-size: 7.5pt; color: #334155; }
        .footer-note { margin-top: 15px; border-top: 1px dashed #cbd5e1; padding-top: 5px; font-size: 7.5pt; color: #94a3b8; text-align: right; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="header-title">
                <h1>PANITIA PEMILIHAN & PENGAWAS INDEPENDEN KOPERASI</h1>
                <h2>AUDIT TRAIL FORENSIK SUARA PEMILIH (TRACEBACK AUDIT)</h2>
                <p>Digital Cryptographic Log • TapVote AI Enterprise Edition • {{ now()->format('d M Y H:i:s') }} WIB</p>
            </td>
        </tr>
    </table>

    <p style="font-size: 8.5pt; margin-bottom: 10px;">
        Total Rekaman Suara Tervalidasi: <strong>{{ count($records) }} Anggota</strong>. Dokumen rahasia ini memuat rekonsiliasi pilihan kartu sah untuk keperluan audit sengketa pemilu.
    </p>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No.</th>
                <th style="width: 15%;">NIK</th>
                <th style="width: 23%;">Nama Anggota</th>
                <th style="width: 14%;">Departemen</th>
                <th style="width: 20%;">Pilihan Calon Ketua</th>
                <th style="width: 20%;">Pilihan Calon Pengawas</th>
                <th style="width: 15%; text-align: center;">Waktu Vote</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $index => $r)
                @php
                    $ketuaNama = $r->hasilKetua?->kandidatKetua?->nama ?? '-';
                    $pengawasNama = $r->hasilPengawas?->kandidatPengawas?->nama ?? '-';
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="font-family: monospace;">{{ $r->nik }}</td>
                    <td style="font-weight: bold;">{{ $r->nama }}</td>
                    <td>{{ $r->dept }}</td>
                    <td style="color: #991b1b; font-weight: 600;">{{ $ketuaNama }}</td>
                    <td style="color: #065f46; font-weight: 600;">{{ $pengawasNama }}</td>
                    <td style="text-align: center; font-family: monospace; font-size: 8pt;">{{ $r->voted_at ? $r->voted_at->format('H:i:s d/m/Y') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-note">
        Dokumen Resmi Rahasia Panitia Pemilihan • SHA-256 Ledger Verified • Dicetak {{ now()->format('Y-m-d H:i:s') }} WIB
    </div>

</body>
</html>
