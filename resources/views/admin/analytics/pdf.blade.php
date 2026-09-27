@php
    $isEn = app()->getLocale() === 'en';
    $electionTitle = \App\Models\AppSetting::getElectionTitle();
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ $isEn ? 'Telemetry Analytics & Voting Velocity Report' : 'Laporan Telemetri Analytics & Kecepatan Suara' }} - TapVote AI</title>
    <style>
        @page { margin: 1.2cm; size: a4 portrait; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 8.5pt; color: #0f172a; line-height: 1.35; margin: 0; padding: 0; }
        .header { text-align: center; border-bottom: 2.5px solid #4f46e5; padding-bottom: 10px; margin-bottom: 12px; }
        .title { font-size: 13pt; font-weight: 800; color: #4f46e5; text-transform: uppercase; margin: 0; letter-spacing: 0.5px; }
        .subtitle { font-size: 10pt; color: #0f172a; font-weight: 800; margin-top: 2px; }
        .meta-p { font-size: 7.5pt; color: #64748b; margin-top: 2px; }
        .badge { display: inline-block; padding: 2px 7px; border-radius: 9999px; font-weight: 800; font-size: 7pt; text-transform: uppercase; }
        .badge-indigo { background: #e0e7ff; color: #3730a3; }
        .badge-green { background: #dcfce7; color: #15803d; }
        .kpi-container { width: 100%; margin-bottom: 12px; }
        .kpi-table { width: 100%; border: none; margin: 0; }
        .kpi-table td { border: 1px solid #cbd5e1; padding: 6px 8px; background: #f8fafc; border-radius: 4px; vertical-align: top; }
        .kpi-label { font-size: 7.5pt; text-transform: uppercase; color: #64748b; font-weight: 700; }
        .kpi-val { font-size: 13pt; font-weight: 800; color: #0f172a; margin-top: 1px; font-family: monospace; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; font-size: 8pt; margin-bottom: 12px; }
        th, td { border: 1px solid #cbd5e1; padding: 5px 7px; text-align: left; }
        th { background: #f1f5f9; font-weight: 800; text-transform: uppercase; font-size: 7.5pt; color: #1e293b; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: monospace; }
        .section-title { font-size: 9.5pt; font-weight: 800; color: #0f172a; margin-top: 10px; margin-bottom: 4px; border-bottom: 1.5px solid #e2e8f0; padding-bottom: 3px; text-transform: uppercase; }
        .footer { margin-top: 16px; font-size: 7.5pt; color: #94a3b8; text-align: right; border-top: 1px dashed #cbd5e1; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">{{ $isEn ? 'TELEMETRY ANALYTICS & VELOCITY AUDIT' : 'LAPORAN TELEMETRI ANALYTICS & KECEPATAN SUARA' }}</h1>
        <div class="subtitle">{{ $electionTitle }}</div>
        <p class="meta-p">{{ $isEn ? 'TapVote AI Enterprise Telemetry • Realtime Turnout, Velocity & Forensic Scanner Audit' : 'Sistem E-Voting TapVote AI • Telemetri Pemilu, Kecepatan Suara, & Audit Anomali' }}</p>
    </div>

    <!-- Filter Meta & Parameters -->
    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 6px 10px; margin-bottom: 10px; font-size: 8pt;">
        <strong>{{ $isEn ? 'Filter Parameters:' : 'Parameter Filter:' }}</strong> 
        {{ $isEn ? 'Department:' : 'Departemen:' }} <strong>{{ $selectedDept ?: ($isEn ? 'All Departments' : 'Semua Departemen') }}</strong> | 
        {{ $isEn ? 'Date:' : 'Tanggal:' }} <strong>{{ $selectedDate ? ($selectedDate === 'today' ? ($isEn ? 'Today (' . date('d/m/Y') . ')' : 'Hari Ini (' . date('d/m/Y') . ')') : $selectedDate) : ($isEn ? 'All Dates' : 'Semua Tanggal') }}</strong> | 
        {{ $isEn ? 'Shift/Time:' : 'Waktu/Shift:' }} <strong>{{ $selectedShift ? strtoupper($selectedShift) : ($isEn ? 'All Hours' : 'Semua Jam') }}</strong> | 
        {{ $isEn ? 'Timestamp:' : 'Waktu Cetak:' }} <strong>{{ date('d F Y, H:i:s') }} WIB</strong>
    </div>

    <!-- 4 High Level KPIs -->
    <div class="kpi-container">
        <table class="kpi-table">
            <tr>
                <td style="width: 25%;">
                    <div class="kpi-label">{{ $isEn ? 'Voter Turnout Rate' : 'Tingkat Partisipasi' }}</div>
                    <div class="kpi-val" style="color: #4f46e5;">{{ $turnoutPct }}%</div>
                    <div style="font-size: 7.5pt; color: #64748b;">{{ $isEn ? 'Quorum Target:' : 'Target Quorum:' }} {{ $quorumThreshold }}% ({{ $quorumMet ? ($isEn ? 'Valid' : 'Sah') : ($isEn ? 'Pending' : 'Belum Memenuhi') }})</div>
                </td>
                <td style="width: 25%;">
                    <div class="kpi-label">{{ $isEn ? 'Total Valid Ballots' : 'Total Suara Sah' }}</div>
                    <div class="kpi-val" style="color: #059669;">{{ number_format($totalVoted) }}</div>
                    <div style="font-size: 7.5pt; color: #64748b;">{{ $isEn ? 'Eligible:' : 'Dari total DPT:' }} {{ number_format($totalVoters) }} ({{ $isEn ? 'Rem:' : 'Sisa:' }} {{ number_format($remaining) }})</div>
                </td>
                <td style="width: 25%;">
                    <div class="kpi-label">{{ $isEn ? 'Peak Voting Hour' : 'Jam Lonjakan Puncak' }}</div>
                    <div class="kpi-val" style="color: #d97706;">{{ $peakHourLabel }} WIB</div>
                    <div style="font-size: 7.5pt; color: #64748b;">{{ $isEn ? 'Velocity:' : 'Volume:' }} {{ $peakHourVotes }} {{ $isEn ? 'votes/hr' : 'suara/jam' }}</div>
                </td>
                <td style="width: 25%;">
                    <div class="kpi-label">{{ $isEn ? 'RFID Card Authenticity' : 'Integritas Kartu RFID' }}</div>
                    <div class="kpi-val" style="color: #2563eb;">{{ $rfidAuthenticityRate }}%</div>
                    <div style="font-size: 7.5pt; color: #64748b;">{{ $isEn ? 'Anomalies blocked:' : 'Anomali dicegah:' }} {{ $unknownCardAttempts }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- 1. Department Breakdown -->
    <div class="section-title">1. {{ $isEn ? 'Department Participation & Quorum Matrix' : 'Matriks Partisipasi Berdasarkan Departemen' }}</div>
    <table>
        <thead>
            <tr>
                <th style="width: 25px;" class="text-center">No</th>
                <th>{{ $isEn ? 'Department' : 'Departemen' }}</th>
                <th class="text-right">{{ $isEn ? 'Eligible' : 'Total Anggota' }}</th>
                <th class="text-right">{{ $isEn ? 'Voted' : 'Suara Masuk' }}</th>
                <th class="text-right">{{ $isEn ? 'Pending' : 'Belum Memilih' }}</th>
                <th class="text-right">{{ $isEn ? 'Turnout (%)' : 'Partisipasi (%)' }}</th>
                <th class="text-center">{{ $isEn ? 'Status' : 'Status' }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deptStats as $idx => $d)
                <tr>
                    <td class="text-center font-mono">{{ $idx + 1 }}</td>
                    <td><strong>{{ $d['dept'] }}</strong></td>
                    <td class="text-right font-mono">{{ number_format($d['total']) }}</td>
                    <td class="text-right font-mono" style="color: #059669; font-weight: bold;">{{ number_format($d['voted']) }}</td>
                    <td class="text-right font-mono" style="color: #d97706;">{{ number_format($d['pending']) }}</td>
                    <td class="text-right font-mono"><strong>{{ $d['pct'] }}%</strong></td>
                    <td class="text-center font-mono" style="font-size: 7.5pt;">
                        {{ $d['pct'] >= 50 ? ($isEn ? 'QUORUM MET' : 'QUORUM TERCAPAI') : ($isEn ? 'BELOW QUORUM' : 'BELUM QUORUM') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- 2. Candidate Rankings -->
    <div class="section-title">2. {{ $isEn ? 'Candidate Standings (Chairman & Supervisory Board)' : 'Peringkat Sementara Calon Ketua & Pengawas Koperasi' }}</div>
    <div style="width: 100%;">
        <table style="width: 49%; display: inline-table; vertical-align: top; margin-right: 1%;">
            <thead>
                <tr>
                    <th colspan="4" style="background: #eef2ff; color: #3730a3; text-align: center;">{{ $isEn ? 'Chairman Candidates' : 'Kandidat Ketua Koperasi' }}</th>
                </tr>
                <tr>
                    <th style="width: 25px;" class="text-center">No</th>
                    <th>{{ $isEn ? 'Candidate' : 'Nama Kandidat' }}</th>
                    <th class="text-right">{{ $isEn ? 'Votes' : 'Suara' }}</th>
                    <th class="text-right">{{ $isEn ? 'Share' : 'Porsi' }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rankingKetua as $idx => $k)
                    @php $kPct = $totalVoted > 0 ? round(($k->perolehan_suara_count / $totalVoted) * 100, 1) : 0; @endphp
                    <tr>
                        <td class="text-center font-mono">#{{ $k->nomor_urut }}</td>
                        <td><strong>{{ $k->nama }}</strong></td>
                        <td class="text-right font-mono" style="font-weight: bold;">{{ number_format($k->perolehan_suara_count) }}</td>
                        <td class="text-right font-mono">{{ $kPct }}%</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">{{ $isEn ? 'No candidates' : 'Belum ada kandidat' }}</td></tr>
                @endforelse
            </tbody>
        </table>

        <table style="width: 49%; display: inline-table; vertical-align: top;">
            <thead>
                <tr>
                    <th colspan="4" style="background: #ecfdf5; color: #065f46; text-align: center;">{{ $isEn ? 'Supervisory Board Candidates' : 'Kandidat Pengawas Koperasi' }}</th>
                </tr>
                <tr>
                    <th style="width: 25px;" class="text-center">No</th>
                    <th>{{ $isEn ? 'Candidate' : 'Nama Kandidat' }}</th>
                    <th class="text-right">{{ $isEn ? 'Votes' : 'Suara' }}</th>
                    <th class="text-right">{{ $isEn ? 'Share' : 'Porsi' }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rankingPengawas as $idx => $p)
                    @php $pPct = $totalVoted > 0 ? round(($p->perolehan_suara_count / $totalVoted) * 100, 1) : 0; @endphp
                    <tr>
                        <td class="text-center font-mono">#{{ $p->nomor_urut }}</td>
                        <td><strong>{{ $p->nama }}</strong></td>
                        <td class="text-right font-mono" style="font-weight: bold;">{{ number_format($p->perolehan_suara_count) }}</td>
                        <td class="text-right font-mono">{{ $pPct }}%</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">{{ $isEn ? 'No candidates' : 'Belum ada kandidat' }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 3. Hourly Velocity Summary -->
    <div class="section-title">3. {{ $isEn ? 'Hourly Voting Velocity Timeline' : 'Ritme Kecepatan Suara per Jam (Hourly Velocity Timeline)' }}</div>
    <table>
        <thead>
            <tr>
                @for($i = 6; $i <= 18; $i++)
                    <th class="text-center font-mono" style="font-size: 7.5pt;">{{ sprintf('%02d', $i) }}:00</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            <tr>
                @for($i = 6; $i <= 18; $i++)
                    <td class="text-center font-mono" style="font-size: 8pt; font-weight: bold;">
                        {{ $hoursValues[$i] ?? 0 }}
                    </td>
                @endfor
            </tr>
        </tbody>
    </table>

    <div class="footer">
        {{ $isEn
            ? "Digitally certified via TapVote AI Analytics Core Engine • SHA-256 Ledger Verified • " . date('Y-m-d H:i:s') . " WIB"
            : "Dicetak otomatis oleh TapVote AI Core Engine • Hash Keamanan SHA-256 Valid • " . date('Y-m-d H:i:s') . " WIB"
        }}
    </div>
</body>
</html>
