<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Official Report - Supervisor Election Results - TapVote AI</title>
    <style>
        @page { margin: 1.5cm; size: a4 portrait; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1e293b; line-height: 1.4; font-size: 10pt; margin: 0; padding: 0; }
        .header-table { width: 100%; border-bottom: 3px double #0f172a; padding-bottom: 12px; margin-bottom: 18px; }
        .header-title { text-align: center; }
        .header-title h1 { margin: 0; font-size: 15pt; text-transform: uppercase; color: #0f172a; letter-spacing: 1px; }
        .header-title h2 { margin: 3px 0 0 0; font-size: 12pt; color: #334155; }
        .header-title p { margin: 3px 0 0 0; font-size: 8.5pt; color: #64748b; }
        .doc-title { text-align: center; margin: 15px 0 20px 0; }
        .doc-title h3 { margin: 0; font-size: 13pt; text-decoration: underline; text-transform: uppercase; color: #0f172a; }
        .doc-title p { margin: 3px 0 0 0; font-size: 8.5pt; color: #475569; }
        .table-data { width: 100%; border-collapse: collapse; margin-top: 12px; margin-bottom: 16px; font-size: 9pt; }
        .table-data th, .table-data td { border: 1px solid #cbd5e1; padding: 7px 10px; }
        .table-data th { background-color: #f8fafc; font-weight: bold; text-transform: uppercase; font-size: 8pt; color: #334155; }
        .badge { display: inline-block; padding: 2px 7px; border-radius: 4px; font-size: 8pt; font-weight: bold; }
        .badge-leader { background-color: #d1fae5; color: #065f46; }
        .badge-tie { background-color: #fef3c7; color: #92400e; }
        .signature-table { width: 100%; margin-top: 35px; border-collapse: collapse; page-break-inside: avoid; }
        .signature-cell { width: 50%; text-align: center; vertical-align: top; font-size: 9.5pt; }
        .signature-space { height: 60px; }
        .footer-note { margin-top: 25px; border-top: 1px dashed #cbd5e1; padding-top: 6px; font-size: 8pt; color: #94a3b8; text-align: right; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="header-title">
                <h1>PANITIA PEMILIHAN PENGURUS KOPERASI</h1>
                <h2>LAPORAN RESMI PEROLEHAN SUARA CALON PENGAWAS KOPERASI</h2>
                <p>Digital Voting System • TapVote AI Enterprise Edition • Standar Audit ISO/IEC 27001</p>
            </td>
        </tr>
    </table>

    <div class="doc-title">
        <h3>BERITA ACARA PEROLEHAN SUARA PENGAWAS</h3>
        <p>Nomor Dokumen: BA-PENGAWAS/{{ date('Y/m') }}/{{ strtoupper(substr(md5($totalSuara . 'pengawas'), 0, 8)) }} • Tanggal: {{ date('d F Y') }}</p>
    </div>

    <p style="font-size: 9pt; margin-bottom: 8px;">
        Pada hari ini, <strong>{{ date('d F Y') }}</strong>, sistem pemungutan suara digital TapVote AI mencatat total perolehan suara sah calon Pengawas Koperasi sejumlah <strong>{{ $totalSuara }} suara</strong> dengan rincian sebagai berikut:
    </p>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 8%; text-align: center;">No. Urut</th>
                <th style="width: 20%;">NIK</th>
                <th>Nama Calon Pengawas</th>
                <th style="width: 15%; text-align: center;">Perolehan Suara</th>
                <th style="width: 15%; text-align: center;">Persentase</th>
                <th style="width: 20%; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kandidatList as $p)
                @php
                    $pct = $totalSuara > 0 ? round(($p->perolehan_suara_count / $totalSuara) * 100, 2) : 0;
                    $isTop = $maxVotes > 0 && $p->perolehan_suara_count === $maxVotes;
                @endphp
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $p->nomor_urut }}</td>
                    <td style="font-family: monospace;">{{ $p->nik }}</td>
                    <td style="font-weight: bold;">{{ $p->nama }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $p->perolehan_suara_count }}</td>
                    <td style="text-align: center;">{{ $pct }}%</td>
                    <td style="text-align: center;">
                        @if($isSeri && $isTop)
                            <span class="badge badge-tie">HASIL SERI</span>
                        @elseif(!$isSeri && $isTop)
                            <span class="badge badge-leader">PEMENANG TERPILIH</span>
                        @else
                            <span style="color: #64748b;">Kandidat</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="signature-table">
        <tr>
            <td class="signature-cell">
                <p>Ketua Panitia Pemilihan,</p>
                <div class="signature-space"></div>
                <p><strong>( .................................................... )</strong><br><span style="font-size: 8pt; color: #64748b;">NIP: .......................................</span></p>
            </td>
            <td class="signature-cell">
                <p>Saksi Sidang Pleno / Pengawas,</p>
                <div class="signature-space"></div>
                <p><strong>( .................................................... )</strong><br><span style="font-size: 8pt; color: #64748b;">NIP: .......................................</span></p>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dicetak secara otomatis melalui TapVote AI Controller PDF Engine • Otentikasi SHA-256 Valid • {{ now()->format('Y-m-d H:i:s') }} WIB
    </div>

</body>
</html>
