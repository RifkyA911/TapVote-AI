<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Official Report - Doorprize Raffle Winners - TapVote AI</title>
    <style>
        @page { margin: 1.5cm; size: a4 portrait; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1e293b; line-height: 1.4; font-size: 9.5pt; margin: 0; padding: 0; }
        .header-table { width: 100%; border-bottom: 3px double #0f172a; padding-bottom: 10px; margin-bottom: 16px; }
        .header-title { text-align: center; }
        .header-title h1 { margin: 0; font-size: 14pt; text-transform: uppercase; color: #0f172a; }
        .header-title h2 { margin: 2px 0 0 0; font-size: 11pt; color: #334155; }
        .header-title p { margin: 2px 0 0 0; font-size: 8pt; color: #64748b; }
        .table-data { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 8.5pt; }
        .table-data th, .table-data td { border: 1px solid #cbd5e1; padding: 6px 8px; }
        .table-data th { background-color: #f8fafc; font-weight: bold; text-transform: uppercase; font-size: 8pt; color: #334155; }
        .footer-note { margin-top: 20px; border-top: 1px dashed #cbd5e1; padding-top: 5px; font-size: 7.5pt; color: #94a3b8; text-align: right; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="header-title">
                <h1>PANITIA UNDIAN DOORPRIZE KOPERASI</h1>
                <h2>BERITA ACARA RESMI PENARIKAN & SERAH TERIMA DOORPRIZE</h2>
                <p>TapVote AI Lottery Module • Pool Undian Sah: {{ $totalEligible }} Anggota • {{ date('d F Y') }}</p>
            </td>
        </tr>
    </table>

    <h3 style="font-size: 11pt; text-transform: uppercase; margin-bottom: 6px; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px;">
        1. Rekap Pemenang Undian Doorprize
    </h3>

    <table class="table-data" style="margin-bottom: 20px;">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No.</th>
                <th style="width: 25%;">Hadiah Doorprize</th>
                <th style="width: 15%;">NIK</th>
                <th style="width: 25%;">Nama Pemenang</th>
                <th style="width: 15%;">Departemen</th>
                <th style="width: 15%; text-align: center;">Status Klaim</th>
            </tr>
        </thead>
        <tbody>
            @forelse($winners as $index => $w)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="font-weight: bold;">{{ $w->doorprize?->title ?? '-' }}</td>
                    <td style="font-family: monospace;">{{ $w->pemilih_nik }}</td>
                    <td style="font-weight: bold;">{{ $w->pemilih?->nama ?? '-' }}</td>
                    <td>{{ $w->pemilih?->dept ?? '-' }}</td>
                    <td style="text-align: center; font-weight: bold;">
                        {{ $w->claim_status === 'ACCEPTED' ? 'DITERIMA' : ($w->claim_status === 'REJECTED' ? 'DITOLAK' : 'PENDING') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #94a3b8; padding: 12px;">Belum ada data pemenang doorprize yang tercatat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3 style="font-size: 11pt; text-transform: uppercase; margin-bottom: 6px; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px;">
        2. Master Inventaris Hadiah Doorprize
    </h3>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No.</th>
                <th>Nama Hadiah</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 12%; text-align: center;">Total Qty</th>
                <th style="width: 12%; text-align: center;">Sisa Kuota</th>
                <th style="width: 20%;">Sponsor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($doorprizes as $idx => $d)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td style="font-weight: bold;">{{ $d->title }}</td>
                    <td>{{ $d->category }}</td>
                    <td style="text-align: center;">{{ $d->quantity }} Unit</td>
                    <td style="text-align: center; font-weight: bold;">{{ $d->remaining_slots }} Unit</td>
                    <td>{{ $d->sponsor ?: '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-note">
        Dokumen Resmi Penyerahan Hadiah Doorprize • TapVote AI • Dicetak {{ now()->format('Y-m-d H:i:s') }} WIB
    </div>

</body>
</html>
