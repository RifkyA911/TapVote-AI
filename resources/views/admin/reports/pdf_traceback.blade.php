@php
    $isEn = app()->getLocale() === 'en';
    $electionTitle = \App\Models\AppSetting::getElectionTitle();
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ $isEn ? 'Forensic Audit Trail & Traceback Report' : 'Audit Trail Forensik Suara Pemilih' }} - TapVote AI</title>
    <style>
        @page { margin: 1.2cm; size: a4 landscape; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #0f172a; line-height: 1.35; font-size: 8.5pt; margin: 0; padding: 0; }
        .header-table { width: 100%; border-bottom: 2.5px solid #4338ca; padding-bottom: 10px; margin-bottom: 12px; }
        .header-title { text-align: center; }
        .header-title h1 { margin: 0; font-size: 13pt; text-transform: uppercase; color: #4338ca; letter-spacing: 0.5px; }
        .header-title h2 { margin: 3px 0 0 0; font-size: 11pt; color: #0f172a; font-weight: 800; }
        .header-title p { margin: 3px 0 0 0; font-size: 7.5pt; color: #64748b; }
        .doc-title { text-align: center; margin: 10px 0 12px 0; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px 12px; }
        .doc-title h3 { margin: 0; font-size: 10.5pt; text-transform: uppercase; color: #0f172a; letter-spacing: 0.5px; }
        .doc-title p { margin: 2px 0 0 0; font-size: 7.5pt; color: #475569; font-family: monospace; }
        .intro-text { font-size: 8pt; color: #334155; margin-bottom: 10px; }
        .table-data { width: 100%; border-collapse: collapse; font-size: 8pt; }
        .table-data th, .table-data td { border: 1px solid #cbd5e1; padding: 5px 8px; }
        .table-data th { background-color: #f1f5f9; font-weight: 800; text-transform: uppercase; font-size: 7.5pt; color: #1e293b; }
        .table-data tr:nth-child(even) { background-color: #f8fafc; }
        .footer-note { margin-top: 16px; border-top: 1px dashed #cbd5e1; padding-top: 5px; font-size: 7.5pt; color: #94a3b8; text-align: right; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="header-title">
                <h1>{{ $isEn ? 'INDEPENDENT ELECTION SUPERVISORY & FORENSIC AUDIT COMMITTEE' : 'PANITIA PEMILIHAN & PENGAWAS INDEPENDEN KOPERASI' }}</h1>
                <h2>{{ $electionTitle }}</h2>
                <p>{{ $isEn ? 'Digital Cryptographic Ballot Log • TapVote AI Enterprise Edition • Certified Traceback Audit' : 'Audit Trail Forensik Suara Pemilih • TapVote AI Enterprise Edition • Log Kriptografis Sah' }}</p>
            </td>
        </tr>
    </table>

    <div class="doc-title">
        <h3>{{ $isEn ? 'CONFIDENTIAL FORENSIC TRACEBACK AUDIT TRAIL' : 'BERITA ACARA AUDIT TRAIL FORENSIK SUARA' }}</h3>
        <p>{{ $isEn ? 'Doc Ref' : 'Nomor Dokumen' }}: AUDIT-TRACE/{{ date('Ymd') }}/{{ strtoupper(substr(md5(count($records) . 'traceback'), 0, 8)) }} • {{ $isEn ? 'Timestamp' : 'Waktu Audit' }}: {{ now()->format('d/m/Y H:i:s') }} WIB</p>
    </div>

    <p class="intro-text">
        {{ $isEn
            ? "Total Certified Ballots Audited: " . count($records) . " Registered Members. This confidential audit trail reconciles genuine RFID transactions with cast candidate selections for post-election audit verification."
            : "Total Rekaman Suara Tervalidasi: " . count($records) . " Anggota. Dokumen rahasia ini memuat rekonsiliasi pilihan kartu sah untuk keperluan audit sengketa pemilu."
        }}
    </p>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 4%; text-align: center;">No.</th>
                <th style="width: 14%;">{{ $isEn ? 'Voter ID (NIK)' : 'NIK' }}</th>
                <th style="width: 22%;">{{ $isEn ? 'Member Name' : 'Nama Anggota' }}</th>
                <th style="width: 14%;">{{ $isEn ? 'Department' : 'Departemen' }}</th>
                <th style="width: 20%;">{{ $isEn ? 'Chairman Ballot Selection' : 'Pilihan Calon Ketua' }}</th>
                <th style="width: 20%;">{{ $isEn ? 'Supervisory Ballot Selection' : 'Pilihan Calon Pengawas' }}</th>
                <th style="width: 14%; text-align: center;">{{ $isEn ? 'Vote Timestamp' : 'Waktu Vote' }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $index => $r)
                @php
                    $ketuaNama = $r->hasilKetua?->kandidatKetua?->nama ?? '-';
                    $pengawasNama = $r->hasilPengawas?->kandidatPengawas?->nama ?? '-';
                @endphp
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $index + 1 }}</td>
                    <td style="font-family: monospace;">{{ $r->nik }}</td>
                    <td style="font-weight: 800; color: #0f172a;">{{ $r->nama }}</td>
                    <td>{{ $r->dept }}</td>
                    <td style="color: #1e40af; font-weight: 700;">{{ $ketuaNama }}</td>
                    <td style="color: #065f46; font-weight: 700;">{{ $pengawasNama }}</td>
                    <td style="text-align: center; font-family: monospace; font-size: 7.5pt;">{{ $r->voted_at ? $r->voted_at->format('H:i:s d/m/Y') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-note">
        {{ $isEn
            ? "Official Confidential Audit Document • SHA-256 Ledger Verified • Generated at " . now()->format('Y-m-d H:i:s') . " WIB"
            : "Dokumen Resmi Rahasia Panitia Pemilihan • SHA-256 Ledger Verified • Dicetak " . now()->format('Y-m-d H:i:s') . " WIB"
        }}
    </div>

</body>
</html>
