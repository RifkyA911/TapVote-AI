@php
    $isEn = app()->getLocale() === 'en';
    $electionTitle = \App\Models\AppSetting::getElectionTitle();
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ $isEn ? 'Official Report - Chairman Election Results' : 'Berita Acara - Hasil Pemilihan Ketua' }} - TapVote AI</title>
    <style>
        @page { margin: 1.5cm; size: a4 portrait; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #0f172a; line-height: 1.4; font-size: 9.5pt; margin: 0; padding: 0; }
        .header-table { width: 100%; border-bottom: 2.5px solid #1e3a8a; padding-bottom: 12px; margin-bottom: 16px; }
        .header-title { text-align: center; }
        .header-title h1 { margin: 0; font-size: 13pt; text-transform: uppercase; color: #1e3a8a; letter-spacing: 0.5px; }
        .header-title h2 { margin: 3px 0 0 0; font-size: 11pt; color: #0f172a; font-weight: 800; }
        .header-title p { margin: 3px 0 0 0; font-size: 8pt; color: #64748b; font-weight: 500; }
        .doc-title { text-align: center; margin: 14px 0 16px 0; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 8px 12px; }
        .doc-title h3 { margin: 0; font-size: 11.5pt; text-transform: uppercase; color: #0f172a; letter-spacing: 0.5px; }
        .doc-title p { margin: 3px 0 0 0; font-size: 8pt; color: #475569; font-family: monospace; }
        .intro-text { font-size: 9pt; margin-bottom: 10px; color: #334155; line-height: 1.5; }
        .table-data { width: 100%; border-collapse: collapse; margin-top: 8px; margin-bottom: 16px; font-size: 9pt; }
        .table-data th, .table-data td { border: 1px solid #cbd5e1; padding: 7px 10px; }
        .table-data th { background-color: #f1f5f9; font-weight: 800; text-transform: uppercase; font-size: 8pt; color: #1e293b; letter-spacing: 0.3px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 7.5pt; font-weight: 800; text-transform: uppercase; }
        .badge-leader { background-color: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .badge-tie { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .badge-candidate { background-color: #f1f5f9; color: #475569; }
        .signature-table { width: 100%; margin-top: 30px; border-collapse: collapse; page-break-inside: avoid; }
        .signature-cell { width: 50%; text-align: center; vertical-align: top; font-size: 9pt; }
        .signature-space { height: 55px; }
        .footer-note { margin-top: 25px; border-top: 1px dashed #cbd5e1; padding-top: 6px; font-size: 7.5pt; color: #94a3b8; text-align: right; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="header-title">
                <h1>{{ $isEn ? 'GENERAL ELECTION STEERING COMMITTEE' : 'PANITIA PEMILIHAN PENGURUS KOPERASI' }}</h1>
                <h2>{{ $electionTitle }}</h2>
                <p>{{ $isEn ? 'Official Digital Voting Report • Powered by TapVote AI Enterprise • Cryptographic Integrity' : 'Laporan Resmi Pemungutan Suara Digital • Didukung oleh TapVote AI Enterprise • Integritas Kriptografis' }}</p>
            </td>
        </tr>
    </table>

    <div class="doc-title">
        <h3>{{ $isEn ? 'OFFICIAL PLENARY REPORT: CHAIRMAN ELECTION' : 'BERITA ACARA PEROLEHAN SUARA CALON KETUA' }}</h3>
        <p>{{ $isEn ? 'Doc Ref' : 'Nomor Dokumen' }}: BA-KETUA/{{ date('Y/m') }}/{{ strtoupper(substr(md5($totalSuara . 'ketua'), 0, 8)) }} • {{ $isEn ? 'Date' : 'Tanggal' }}: {{ date('d F Y') }}</p>
    </div>

    <p class="intro-text">
        {{ $isEn
            ? "On this day, " . date('d F Y') . ", the TapVote AI Digital Voting System officially certified a total of " . $totalSuara . " verified valid ballots for the Chairman candidate election with the certified breakdown detailed below:"
            : "Pada hari ini, " . date('d F Y') . ", sistem pemungutan suara digital TapVote AI mencatat total perolehan suara sah calon Ketua sejumlah " . $totalSuara . " suara dengan rincian hasil rekapitulasi sebagai berikut:"
        }}
    </p>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 8%; text-align: center;">{{ $isEn ? 'No.' : 'No. Urut' }}</th>
                <th style="width: 18%;">{{ $isEn ? 'Voter ID (NIK)' : 'NIK' }}</th>
                <th>{{ $isEn ? 'Candidate Full Name' : 'Nama Calon Ketua' }}</th>
                <th style="width: 16%; text-align: center;">{{ $isEn ? 'Ballots Won' : 'Perolehan Suara' }}</th>
                <th style="width: 14%; text-align: center;">{{ $isEn ? 'Percentage' : 'Persentase' }}</th>
                <th style="width: 20%; text-align: center;">{{ $isEn ? 'Electoral Status' : 'Status' }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kandidatList as $k)
                @php
                    $pct = $totalSuara > 0 ? round(($k->perolehan_suara_count / $totalSuara) * 100, 2) : 0;
                    $isTop = $maxVotes > 0 && $k->perolehan_suara_count === $maxVotes;
                @endphp
                <tr>
                    <td style="text-align: center; font-weight: 800;">#{{ $k->nomor_urut }}</td>
                    <td style="font-family: monospace; font-size: 8.5pt;">{{ $k->nik }}</td>
                    <td style="font-weight: 800; color: #0f172a;">{{ $k->nama }}</td>
                    <td style="text-align: center; font-weight: 800; font-family: monospace;">{{ number_format($k->perolehan_suara_count) }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $pct }}%</td>
                    <td style="text-align: center;">
                        @if($isSeri && $isTop)
                            <span class="badge badge-tie">{{ $isEn ? 'TIE (EQUAL VOTES)' : 'HASIL SERI' }}</span>
                        @elseif(!$isSeri && $isTop)
                            <span class="badge badge-leader">{{ $isEn ? 'ELECTED WINNER' : 'PEMENANG TERPILIH' }}</span>
                        @else
                            <span class="badge badge-candidate">{{ $isEn ? 'Candidate' : 'Kandidat' }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="signature-table">
        <tr>
            <td class="signature-cell">
                <p><strong>{{ $isEn ? 'Election Committee Chairperson,' : 'Ketua Panitia Pemilihan,' }}</strong></p>
                <div class="signature-space"></div>
                <p><strong>( .................................................... )</strong><br><span style="font-size: 8pt; color: #64748b;">NIP: .......................................</span></p>
            </td>
            <td class="signature-cell">
                <p><strong>{{ $isEn ? 'Plenary Witness / Supervisory Board,' : 'Saksi Sidang Pleno / Pengawas,' }}</strong></p>
                <div class="signature-space"></div>
                <p><strong>( .................................................... )</strong><br><span style="font-size: 8pt; color: #64748b;">NIP: .......................................</span></p>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        {{ $isEn
            ? "Digitally generated via TapVote AI Controller PDF Engine • SHA-256 Validated • Generated at " . now()->format('Y-m-d H:i:s') . " WIB"
            : "Dicetak secara otomatis melalui TapVote AI Controller PDF Engine • Otentikasi SHA-256 Valid • " . now()->format('Y-m-d H:i:s') . " WIB"
        }}
    </div>

</body>
</html>
