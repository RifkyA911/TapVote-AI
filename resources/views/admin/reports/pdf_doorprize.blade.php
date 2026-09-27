@php
    $isEn = app()->getLocale() === 'en';
    $electionTitle = \App\Models\AppSetting::getElectionTitle();
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ $isEn ? 'Official Report - Doorprize Raffle Winners' : 'Berita Acara - Pemenang Undian Doorprize' }} - TapVote AI</title>
    <style>
        @page { margin: 1.4cm; size: a4 portrait; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #0f172a; line-height: 1.35; font-size: 8.5pt; margin: 0; padding: 0; }
        .header-table { width: 100%; border-bottom: 2.5px solid #d97706; padding-bottom: 10px; margin-bottom: 14px; }
        .header-title { text-align: center; }
        .header-title h1 { margin: 0; font-size: 13pt; text-transform: uppercase; color: #d97706; letter-spacing: 0.5px; }
        .header-title h2 { margin: 3px 0 0 0; font-size: 11pt; color: #0f172a; font-weight: 800; }
        .header-title p { margin: 3px 0 0 0; font-size: 7.5pt; color: #64748b; }
        .section-header { font-size: 9.5pt; font-weight: 800; text-transform: uppercase; margin: 12px 0 6px 0; padding-left: 6px; border-left: 3.5px solid #d97706; color: #0f172a; }
        .table-data { width: 100%; border-collapse: collapse; margin-top: 6px; margin-bottom: 14px; font-size: 8.5pt; }
        .table-data th, .table-data td { border: 1px solid #cbd5e1; padding: 6px 8px; }
        .table-data th { background-color: #f8fafc; font-weight: 800; text-transform: uppercase; font-size: 7.5pt; color: #1e293b; }
        .badge { display: inline-block; padding: 2px 7px; border-radius: 9999px; font-size: 7pt; font-weight: 800; text-transform: uppercase; }
        .badge-accepted { background-color: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .badge-rejected { background-color: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .badge-pending { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .footer-note { margin-top: 20px; border-top: 1px dashed #cbd5e1; padding-top: 5px; font-size: 7.5pt; color: #94a3b8; text-align: right; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="header-title">
                <h1>{{ $isEn ? 'DOORPRIZE & GRAND LOTTERY COMMITTEE' : 'PANITIA UNDIAN DOORPRIZE KOPERASI' }}</h1>
                <h2>{{ $electionTitle }}</h2>
                <p>{{ $isEn ? 'Official Raffle Verification Report • Eligible Pool: ' . $totalEligible . ' Members • Date: ' . date('d F Y') : 'Berita Acara Resmi Penarikan & Serah Terima Doorprize • Pool Undian Sah: ' . $totalEligible . ' Anggota • Tanggal: ' . date('d F Y') }}</p>
            </td>
        </tr>
    </table>

    <div class="section-header">
        1. {{ $isEn ? 'Recapitulation of Certified Raffle Winners' : 'Rekapitulasi Pemenang Undian Doorprize' }}
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No.</th>
                <th style="width: 26%;">{{ $isEn ? 'Reward Title' : 'Hadiah Doorprize' }}</th>
                <th style="width: 14%;">{{ $isEn ? 'Voter ID' : 'NIK' }}</th>
                <th style="width: 24%;">{{ $isEn ? 'Winner Name' : 'Nama Pemenang' }}</th>
                <th style="width: 16%;">{{ $isEn ? 'Department' : 'Departemen' }}</th>
                <th style="width: 15%; text-align: center;">{{ $isEn ? 'Claim Status' : 'Status Klaim' }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($winners as $index => $w)
                @php
                    $cStatus = strtoupper($w->claim_status ?? 'PENDING');
                @endphp
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $index + 1 }}</td>
                    <td style="font-weight: 800; color: #0f172a;">{{ $w->doorprize?->title ?? '-' }}</td>
                    <td style="font-family: monospace;">{{ $w->pemilih_nik }}</td>
                    <td style="font-weight: 800;">{{ $w->pemilih?->nama ?? '-' }}</td>
                    <td>{{ $w->pemilih?->dept ?? '-' }}</td>
                    <td style="text-align: center;">
                        @if($cStatus === 'ACCEPTED' || $cStatus === 'DITERIMA')
                            <span class="badge badge-accepted">{{ $isEn ? 'CLAIMED' : 'DITERIMA' }}</span>
                        @elseif($cStatus === 'REJECTED' || $cStatus === 'DITOLAK')
                            <span class="badge badge-rejected">{{ $isEn ? 'FORFEITED' : 'DITOLAK' }}</span>
                        @else
                            <span class="badge badge-pending">{{ $isEn ? 'PENDING' : 'BELUM DIAMBIL' }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #94a3b8; padding: 12px;">{{ $isEn ? 'No raffle winners have been drawn yet.' : 'Belum ada data pemenang doorprize yang tercatat.' }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-header">
        2. {{ $isEn ? 'Master Rewards Inventory & Quota Tracking' : 'Master Inventaris Hadiah Doorprize' }}
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No.</th>
                <th>{{ $isEn ? 'Prize Item' : 'Nama Hadiah' }}</th>
                <th style="width: 18%;">{{ $isEn ? 'Category' : 'Kategori' }}</th>
                <th style="width: 14%; text-align: center;">{{ $isEn ? 'Total Qty' : 'Total Kuota' }}</th>
                <th style="width: 14%; text-align: center;">{{ $isEn ? 'Remaining' : 'Sisa Kuota' }}</th>
                <th style="width: 22%;">{{ $isEn ? 'Sponsor / Donor' : 'Sponsor' }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($doorprizes as $idx => $d)
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $idx + 1 }}</td>
                    <td style="font-weight: 800; color: #0f172a;">{{ $d->title }}</td>
                    <td>{{ $d->category }}</td>
                    <td style="text-align: center; font-family: monospace;">{{ $d->quantity }} {{ $isEn ? 'Unit' : 'Unit' }}</td>
                    <td style="text-align: center; font-weight: 800; font-family: monospace; color: {{ $d->remaining_slots <= 0 ? '#991b1b' : '#0f172a' }};">{{ $d->remaining_slots }} {{ $isEn ? 'Unit' : 'Unit' }}</td>
                    <td>{{ $d->sponsor ?: '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-note">
        {{ $isEn
            ? "Official Doorprize Award Certification • TapVote AI • Timestamp: " . now()->format('Y-m-d H:i:s') . " WIB"
            : "Dokumen Resmi Penyerahan Hadiah Doorprize • TapVote AI • Dicetak " . now()->format('Y-m-d H:i:s') . " WIB"
        }}
    </div>

</body>
</html>
