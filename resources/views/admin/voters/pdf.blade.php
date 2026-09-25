<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Official Eligible Voter Roster - TapVote AI</title>
    <style>
        @page { margin: 1.2cm; size: a4 portrait; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1e293b; line-height: 1.4; font-size: 8.5pt; margin: 0; padding: 0; }
        .header-table { width: 100%; border-bottom: 2px solid #0f172a; padding-bottom: 8px; margin-bottom: 12px; }
        .header-title { text-align: center; }
        .header-title h1 { margin: 0; font-size: 13pt; text-transform: uppercase; color: #0f172a; }
        .header-title h2 { margin: 2px 0 0 0; font-size: 10pt; color: #334155; }
        .header-title p { margin: 2px 0 0 0; font-size: 7.5pt; color: #64748b; }
        .table-data { width: 100%; border-collapse: collapse; font-size: 8pt; }
        .table-data th, .table-data td { border: 1px solid #cbd5e1; padding: 4px 6px; }
        .table-data th { background-color: #f8fafc; font-weight: bold; text-transform: uppercase; font-size: 7pt; color: #334155; }
        .footer-note { margin-top: 15px; border-top: 1px dashed #cbd5e1; padding-top: 5px; font-size: 7pt; color: #94a3b8; text-align: right; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="header-title">
                <h1>PANITIA PEMILIHAN PENGURUS KOPERASI</h1>
                <h2>DAFTAR PEMILIH TETAP (ELIGIBLE VOTERS) RESMI</h2>
                <p>Digital Roster Ledger • TapVote AI Enterprise Edition • Total Anggota: {{ count($voters) }}</p>
            </td>
        </tr>
    </table>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No.</th>
                <th style="width: 14%;">NIK</th>
                <th style="width: 28%;">Nama Lengkap Anggota</th>
                <th style="width: 18%;">Departemen</th>
                <th style="width: 15%;">RFID UID Mifare</th>
                <th style="width: 10%; text-align: center;">Hak Suara</th>
                <th style="width: 10%; text-align: center;">Waktu Vote</th>
            </tr>
        </thead>
        <tbody>
            @foreach($voters as $idx => $v)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td style="font-family: monospace;">{{ $v->nik }}</td>
                    <td style="font-weight: bold;">{{ $v->nama }}</td>
                    <td>{{ $v->dept }}</td>
                    <td style="font-family: monospace; font-size: 7.5pt;">{{ $v->rfid }}</td>
                    <td style="text-align: center; font-weight: bold; color: {{ $v->pilih === 'T' ? '#059669' : '#64748b' }};">
                        {{ $v->pilih === 'T' ? 'Sudah (T)' : 'Belum (F)' }}
                    </td>
                    <td style="text-align: center; font-size: 7pt; font-family: monospace;">
                        {{ $v->voted_at ? $v->voted_at->format('H:i:s d/m') : '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-note">
        Dokumen Resmi Terotentikasi SHA-256 • Dicetak {{ now()->format('Y-m-d H:i:s') }} WIB
    </div>

</body>
</html>
