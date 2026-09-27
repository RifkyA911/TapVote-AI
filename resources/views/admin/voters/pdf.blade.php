@php
    $isEn = app()->getLocale() === 'en';
    $electionTitle = \App\Models\AppSetting::getElectionTitle();
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ $isEn ? 'Official Eligible Voter Roster (DPT)' : 'Daftar Pemilih Tetap (DPT) Resmi' }} - TapVote AI</title>
    <style>
        @page { margin: 1.2cm; size: a4 portrait; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #0f172a; line-height: 1.35; font-size: 8pt; margin: 0; padding: 0; }
        .header-table { width: 100%; border-bottom: 2.5px solid #1e3a8a; padding-bottom: 8px; margin-bottom: 12px; }
        .header-title { text-align: center; }
        .header-title h1 { margin: 0; font-size: 12.5pt; text-transform: uppercase; color: #1e3a8a; letter-spacing: 0.5px; }
        .header-title h2 { margin: 2px 0 0 0; font-size: 10.5pt; color: #0f172a; font-weight: 800; }
        .header-title p { margin: 2px 0 0 0; font-size: 7.5pt; color: #64748b; }
        .table-data { width: 100%; border-collapse: collapse; font-size: 7.5pt; }
        .table-data th, .table-data td { border: 1px solid #cbd5e1; padding: 4px 6px; }
        .table-data th { background-color: #f1f5f9; font-weight: 800; text-transform: uppercase; font-size: 7pt; color: #1e293b; }
        .table-data tr:nth-child(even) { background-color: #f8fafc; }
        .footer-note { margin-top: 14px; border-top: 1px dashed #cbd5e1; padding-top: 5px; font-size: 7pt; color: #94a3b8; text-align: right; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="header-title">
                <h1>{{ $isEn ? 'GENERAL ELECTION STEERING COMMITTEE' : 'PANITIA PEMILIHAN PENGURUS KOPERASI' }}</h1>
                <h2>{{ $electionTitle }}</h2>
                <p>{{ $isEn ? 'Official Verified Voter Roll (DPT) • Certified Ledger • Total Members: ' . count($voters) : 'Daftar Pemilih Tetap (DPT) Resmi • Buku Induk Pemilih Sah • Total Anggota: ' . count($voters) }}</p>
            </td>
        </tr>
    </table>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No.</th>
                <th style="width: 13%;">{{ $isEn ? 'Voter ID (NIK)' : 'NIK' }}</th>
                <th style="width: 25%;">{{ $isEn ? 'Member Full Name' : 'Nama Lengkap Anggota' }}</th>
                <th style="width: 15%;">{{ $isEn ? 'Department' : 'Departemen' }}</th>
                <th style="width: 14%;">{{ $isEn ? 'RFID UID Mifare' : 'RFID UID Mifare' }}</th>
                <th style="width: 13%; text-align: center;">{{ $isEn ? 'Voting Status' : 'Status Hak Suara' }}</th>
                <th style="width: 15%; text-align: center;">{{ $isEn ? 'Vote Timestamp' : 'Waktu Vote' }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($voters as $idx => $v)
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $idx + 1 }}</td>
                    <td style="font-family: monospace;">{{ $v->nik }}</td>
                    <td style="font-weight: 800; color: #0f172a;">{{ $v->nama }}</td>
                    <td>{{ $v->dept }}</td>
                    <td style="font-family: monospace; font-size: 7pt;">{{ $v->rfid ?: '-' }}</td>
                    <td style="text-align: center; font-weight: bold; color: {{ $v->pilih === 'T' ? '#15803d' : '#64748b' }};">
                        {{ $v->pilih === 'T' ? ($isEn ? 'VOTED (T)' : 'Sudah (T)') : ($isEn ? 'PENDING (F)' : 'Belum (F)') }}
                    </td>
                    <td style="text-align: center; font-size: 7pt; font-family: monospace;">
                        {{ $v->voted_at ? $v->voted_at->format('H:i:s d/m/Y') : '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-note">
        {{ $isEn
            ? "Official Verified Document • SHA-256 Validated • Generated at " . now()->format('Y-m-d H:i:s') . " WIB"
            : "Dokumen Resmi Terotentikasi SHA-256 • Dicetak " . now()->format('Y-m-d H:i:s') . " WIB"
        }}
    </div>

</body>
</html>
