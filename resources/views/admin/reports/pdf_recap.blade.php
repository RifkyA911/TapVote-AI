@php
    $isEn = app()->getLocale() === 'en';
    $electionTitle = \App\Models\AppSetting::getElectionTitle();
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ $isEn ? 'Official Plenary Recap - Election Results' : 'Berita Acara & Rekapitulasi Hasil Pemilihan' }} - TapVote AI</title>
    <style>
        @page { margin: 1.4cm; size: a4 portrait; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #0f172a; line-height: 1.35; font-size: 9pt; margin: 0; padding: 0; }
        .header-table { width: 100%; border-bottom: 2.5px solid #1e3a8a; padding-bottom: 10px; margin-bottom: 14px; }
        .header-title { text-align: center; }
        .header-title h1 { margin: 0; font-size: 13pt; text-transform: uppercase; color: #1e3a8a; letter-spacing: 0.5px; }
        .header-title h2 { margin: 3px 0 0 0; font-size: 11pt; color: #0f172a; font-weight: 800; }
        .header-title p { margin: 3px 0 0 0; font-size: 7.5pt; color: #64748b; }
        .doc-title { text-align: center; margin: 12px 0 14px 0; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 7px 12px; }
        .doc-title h3 { margin: 0; font-size: 11pt; text-transform: uppercase; color: #0f172a; letter-spacing: 0.5px; }
        .doc-title p { margin: 3px 0 0 0; font-size: 7.5pt; color: #475569; font-family: monospace; }
        .intro-text { font-size: 8.5pt; color: #334155; margin-bottom: 10px; line-height: 1.4; }
        .metrics-grid { width: 100%; margin-bottom: 14px; border-collapse: collapse; font-size: 8.5pt; }
        .metrics-grid td { padding: 6px 10px; border: 1px solid #cbd5e1; }
        .metrics-grid .label { background-color: #f1f5f9; font-weight: 700; width: 32%; color: #334155; font-size: 8pt; text-transform: uppercase; }
        .metrics-grid .value { font-weight: 800; color: #0f172a; font-family: monospace; }
        .section-title { font-size: 9.5pt; font-weight: 800; text-transform: uppercase; margin: 14px 0 6px 0; padding-left: 6px; border-left: 3.5px solid #2563eb; color: #0f172a; }
        .table-data { width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 8.5pt; }
        .table-data th { background-color: #f1f5f9; color: #1e293b; font-weight: 800; text-align: left; padding: 6px 8px; border: 1px solid #cbd5e1; text-transform: uppercase; font-size: 7.5pt; }
        .table-data td { padding: 6px 8px; border: 1px solid #e2e8f0; vertical-align: middle; }
        .table-data tr:nth-child(even) { background-color: #f8fafc; }
        .badge { display: inline-block; padding: 2px 7px; border-radius: 9999px; font-size: 7pt; font-weight: 800; text-transform: uppercase; }
        .badge-leader { background-color: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .badge-tie { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .badge-candidate { background-color: #f1f5f9; color: #475569; }
        .signatures-table { width: 100%; margin-top: 24px; border-collapse: collapse; page-break-inside: avoid; }
        .signatures-table td { width: 50%; text-align: center; vertical-align: top; padding: 8px 16px; font-size: 8.5pt; }
        .sig-space { height: 48px; }
        .footer-note { margin-top: 20px; border-top: 1px dashed #cbd5e1; padding-top: 6px; font-size: 7.5pt; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="header-title">
                <h1>{{ $isEn ? 'GENERAL ELECTION STEERING COMMITTEE' : 'PANITIA PEMILIHAN ELEKTRONIK KOPERASI' }}</h1>
                <h2>{{ $electionTitle }}</h2>
                <p>{{ $isEn ? 'Official Digital Voting Certification • Powered by TapVote AI Enterprise • Cryptographic Integrity' : 'Sistem E-Voting Berbasis RFID • TapVote AI Enterprise Edition • Standar Audit ISO/IEC 27001' }}</p>
            </td>
        </tr>
    </table>

    <div class="doc-title">
        <h3>{{ $isEn ? 'OFFICIAL PLENARY ELECTION RECAPITULATION REPORT' : 'BERITA ACARA REKAPITULASI HASIL PEMUNGUTAN SUARA' }}</h3>
        <p>{{ $isEn ? 'Doc Ref' : 'Nomor Dokumen' }}: BA-EVOTE/{{ date('Ymd') }}/{{ strtoupper(substr(md5($metrics['timestamp']), 0, 6)) }} • {{ $isEn ? 'Date' : 'Tanggal' }}: {{ date('d F Y') }}</p>
    </div>

    <p class="intro-text">
        {{ $isEn
            ? "On this day, " . date('d F Y') . ", electronic voting and certified vote tabulation were conducted using contactless RFID Mifare smart card authentication. The certified election outcomes are documented as follows:"
            : "Pada hari ini, " . date('d F Y') . ", telah dilaksanakan pemungutan dan penghitungan suara secara elektronik (E-Voting) dengan otentikasi kartu pintar RFID Mifare ISO 14443A. Rekapitulasi perolehan suara resmi dilaporkan sebagai berikut:"
        }}
    </p>

    <table class="metrics-grid">
        <tr>
            <td class="label">{{ $isEn ? 'Total Eligible Voters (DPT)' : 'Total Daftar Pemilih Tetap (DPT)' }}</td>
            <td class="value">{{ number_format($metrics['metrics']['total_voters']) }} {{ $isEn ? 'Voters' : 'Orang' }}</td>
            <td class="label">{{ $isEn ? 'Voter Turnout Rate' : 'Tingkat Partisipasi (Turnout)' }}</td>
            <td class="value">{{ $metrics['metrics']['turnout_percentage'] }}%</td>
        </tr>
        <tr>
            <td class="label">{{ $isEn ? 'Total Valid Ballots Cast' : 'Total Suara Sah Masuk' }}</td>
            <td class="value">{{ number_format($metrics['metrics']['total_voted']) }} {{ $isEn ? 'Ballots' : 'Suara' }}</td>
            <td class="label">{{ $isEn ? 'Quorum Legal Status' : 'Status Kuorum Pemilihan' }}</td>
            <td class="value">
                {{ $metrics['metrics']['turnout_percentage'] >= 50.0 
                    ? ($isEn ? 'QUORUM ACHIEVED (VALID)' : 'KUORUM TERPENUHI (SAH)') 
                    : ($isEn ? 'BELOW QUORUM' : 'BELUM KUORUM') 
                }}
            </td>
        </tr>
        <tr>
            <td class="label">{{ $isEn ? 'Remaining Uncast Ballots' : 'Sisa Belum Memberikan Suara' }}</td>
            <td class="value">{{ number_format($metrics['metrics']['remaining_voters']) }} {{ $isEn ? 'Voters' : 'Orang' }}</td>
            <td class="label">{{ $isEn ? 'System Lifecycle Status' : 'Status Operasional Sistem' }}</td>
            <td class="value">{{ $metrics['voting_status'] }}</td>
        </tr>
    </table>

    <div class="section-title">I. {{ $isEn ? 'Chairman Candidate Election Results' : 'Perolehan Suara Calon Ketua Koperasi' }}</div>
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 10%; text-align: center;">{{ $isEn ? 'No.' : 'No. Urut' }}</th>
                <th>{{ $isEn ? 'Candidate Full Name' : 'Nama Kandidat Calon Ketua' }}</th>
                <th style="width: 18%; text-align: right;">{{ $isEn ? 'Ballots Won' : 'Perolehan Suara' }}</th>
                <th style="width: 14%; text-align: right;">{{ $isEn ? 'Percentage' : 'Persentase' }}</th>
                <th style="width: 22%; text-align: center;">{{ $isEn ? 'Status' : 'Keterangan' }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($metrics['ketua_results'] as $k)
                <tr>
                    <td style="font-weight: 800; text-align: center;">#{{ $k['nomor_urut'] }}</td>
                    <td style="font-weight: 800; color: #0f172a;">{{ $k['nama'] }}</td>
                    <td style="text-align: right; font-weight: 800; font-family: monospace;">{{ number_format($k['suara']) }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ $k['persen'] }}%</td>
                    <td style="text-align: center;">
                        @if($k['is_leader'])
                            <span class="badge badge-leader">{{ $isEn ? 'ELECTED WINNER' : 'Unggul Terpilih' }}</span>
                        @elseif($k['is_tie'])
                            <span class="badge badge-tie">{{ $isEn ? 'TIE (DRAW)' : 'Hasil Seri (Draw)' }}</span>
                        @else
                            <span class="badge badge-candidate">{{ $isEn ? 'Candidate' : 'Kandidat' }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">II. {{ $isEn ? 'Supervisory Board Candidate Election Results' : 'Perolehan Suara Calon Pengawas Koperasi' }}</div>
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 10%; text-align: center;">{{ $isEn ? 'No.' : 'No. Urut' }}</th>
                <th>{{ $isEn ? 'Candidate Full Name' : 'Nama Kandidat Calon Pengawas' }}</th>
                <th style="width: 18%; text-align: right;">{{ $isEn ? 'Ballots Won' : 'Perolehan Suara' }}</th>
                <th style="width: 14%; text-align: right;">{{ $isEn ? 'Percentage' : 'Persentase' }}</th>
                <th style="width: 22%; text-align: center;">{{ $isEn ? 'Status' : 'Keterangan' }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($metrics['pengawas_results'] as $p)
                <tr>
                    <td style="font-weight: 800; text-align: center;">#{{ $p['nomor_urut'] }}</td>
                    <td style="font-weight: 800; color: #0f172a;">{{ $p['nama'] }}</td>
                    <td style="text-align: right; font-weight: 800; font-family: monospace;">{{ number_format($p['suara']) }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ $p['persen'] }}%</td>
                    <td style="text-align: center;">
                        @if($p['is_leader'])
                            <span class="badge badge-leader">{{ $isEn ? 'ELECTED WINNER' : 'Unggul Terpilih' }}</span>
                        @elseif($p['is_tie'])
                            <span class="badge badge-tie">{{ $isEn ? 'TIE (DRAW)' : 'Hasil Seri (Draw)' }}</span>
                        @else
                            <span class="badge badge-candidate">{{ $isEn ? 'Candidate' : 'Kandidat' }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="font-size: 8pt; color: #475569; margin-top: 10px;">
        {{ $isEn
            ? "This Plenary Election Recapitulation Document has been generated with authentic cryptographic verification without manual alteration."
            : "Demikian Berita Acara Rekapitulasi Pemungutan Suara ini dibuat dengan sebenarnya dan disahkan secara digital tanpa adanya rekayasa atau manipulasi suara."
        }}
    </p>

    <table class="signatures-table">
        <tr>
            <td>
                <span>{{ $isEn ? 'Acknowledged by,' : 'Mengetahui,' }}</span><br>
                <strong>{{ $isEn ? 'Election Committee Chairperson' : 'Ketua Panitia Pemilihan' }}</strong>
                <div class="sig-space"></div>
                <strong>( ...................................................... )</strong><br>
                <span style="font-size: 7.5pt; color: #64748b;">NIP / NIK</span>
            </td>
            <td>
                <span>{{ $isEn ? 'Certified on site by,' : 'Ditetapkan di Tempat,' }}</span><br>
                <strong>{{ $isEn ? 'Election Committee Secretary' : 'Sekretaris Panitia Pemilihan' }}</strong>
                <div class="sig-space"></div>
                <strong>( ...................................................... )</strong><br>
                <span style="font-size: 7.5pt; color: #64748b;">NIP / NIK</span>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        {{ $isEn
            ? "Automatically generated via TapVote AI System • Timestamp: " . now()->format('d/m/Y H:i:s') . " WIB • SHA-256 Validated"
            : "Dokumen ini di-generate secara otomatis oleh Sistem TapVote-AI • Waktu Cetak: " . now()->format('d/m/Y H:i:s') . " WIB • SHA-256 Validated"
        }}
    </div>

</body>
</html>
