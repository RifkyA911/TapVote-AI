<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Telemetri Analytics & Kecepatan Suara - TapVote AI</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #1e293b; margin: 20px; line-height: 1.4; }
        .header { text-align: center; border-bottom: 2px solid #4f46e5; padding-bottom: 12px; margin-bottom: 16px; }
        .title { font-size: 18px; font-weight: bold; color: #0f172a; text-transform: uppercase; margin: 0; }
        .subtitle { font-size: 11px; color: #64748b; margin-top: 4px; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 6px; font-weight: bold; font-size: 9px; text-transform: uppercase; }
        .badge-indigo { background: #e0e7ff; color: #3730a3; }
        .badge-green { background: #dcfce7; color: #15803d; }
        .kpi-container { width: 100%; margin-bottom: 15px; }
        .kpi-table { width: 100%; border: none; margin: 0; }
        .kpi-table td { border: 1px solid #e2e8f0; padding: 8px 10px; background: #f8fafc; border-radius: 6px; vertical-align: top; }
        .kpi-label { font-size: 9px; text-transform: uppercase; color: #64748b; font-weight: bold; }
        .kpi-val { font-size: 16px; font-weight: bold; color: #1e293b; margin-top: 2px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 10px; margin-bottom: 16px; }
        th, td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: left; }
        th { background: #f1f5f9; font-weight: bold; text-transform: uppercase; font-size: 9px; color: #334155; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: monospace; }
        .section-title { font-size: 12px; font-weight: bold; color: #0f172a; margin-top: 14px; margin-bottom: 4px; border-bottom: 1px solid #e2e8f0; padding-bottom: 3px; }
        .footer { margin-top: 24px; font-size: 9px; color: #94a3b8; text-align: right; border-top: 1px solid #e2e8f0; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">Laporan Telemetri Analytics & Kecepatan Suara</h1>
        <p class="subtitle">Sistem E-Voting TapVote AI • Telemetri Pemilu, Kecepatan Suara, & Audit Anomali</p>
        <span class="badge badge-indigo">🛰️ Mata Langit Telemetry</span>
        <span class="badge badge-green">Dokumen Resmi Terverifikasi</span>
    </div>

    <!-- Filter Meta & Parameters -->
    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 8px 12px; margin-bottom: 14px; font-size: 10px;">
        <strong>Parameter Filter:</strong> 
        Departemen: <strong>{{ $selectedDept ?: 'Semua Departemen' }}</strong> | 
        Tanggal: <strong>{{ $selectedDate ? ($selectedDate === 'today' ? 'Hari Ini (' . date('d/m/Y') . ')' : $selectedDate) : 'Semua Tanggal' }}</strong> | 
        Shift Jam: <strong>{{ $selectedShift ? strtoupper($selectedShift) : 'Semua Jam' }}</strong> | 
        Waktu Cetak: <strong>{{ date('d F Y, H:i:s') }} WIB</strong>
    </div>

    <!-- 4 High Level KPIs -->
    <div class="kpi-container">
        <table class="kpi-table">
            <tr>
                <td style="width: 25%;">
                    <div class="kpi-label">Tingkat Partisipasi (Turnout)</div>
                    <div class="kpi-val" style="color: #4f46e5;">{{ $turnoutPct }}%</div>
                    <div style="font-size: 9px; color: #64748b;">Target Quorum: {{ $quorumThreshold }}% ({{ $quorumMet ? 'Sah' : 'Belum Memenuhi' }})</div>
                </td>
                <td style="width: 25%;">
                    <div class="kpi-label">Total Suara Sah Masuk</div>
                    <div class="kpi-val" style="color: #059669;">{{ number_format($totalVoted) }}</div>
                    <div style="font-size: 9px; color: #64748b;">Dari total DPT {{ number_format($totalVoters) }} anggota (Sisa: {{ number_format($remaining) }})</div>
                </td>
                <td style="width: 25%;">
                    <div class="kpi-label">Jam Lonjakan Puncak</div>
                    <div class="kpi-val" style="color: #d97706;">{{ $peakHourLabel }} WIB</div>
                    <div style="font-size: 9px; color: #64748b;">Volume: {{ $peakHourVotes }} suara/jam</div>
                </td>
                <td style="width: 25%;">
                    <div class="kpi-label">Integritas Kartu RFID</div>
                    <div class="kpi-val" style="color: #2563eb;">{{ $rfidAuthenticityRate }}%</div>
                    <div style="font-size: 9px; color: #64748b;">Anomali asing dicegah: {{ $unknownCardAttempts }} kali</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- 1. Department Breakdown -->
    <div class="section-title">1. Matriks Partisipasi Berdasarkan Departemen</div>
    <table>
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">No</th>
                <th>Departemen</th>
                <th class="text-right">Total Anggota</th>
                <th class="text-right">Suara Masuk</th>
                <th class="text-right">Belum Memilih</th>
                <th class="text-right">Partisipasi (%)</th>
                <th class="text-center">Status</th>
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
                    <td class="text-center font-mono" style="font-size: 9px;">
                        {{ $d['pct'] >= 50 ? 'QUORUM TERCAPAI' : 'BELUM QUORUM' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- 2. Candidate Rankings -->
    <div class="section-title">2. Peringkat Sementara Calon Ketua & Pengawas Koperasi</div>
    <div style="width: 100%;">
        <table style="width: 49%; display: inline-table; vertical-align: top; margin-right: 1%;">
            <thead>
                <tr>
                    <th colspan="4" style="background: #eef2ff; color: #3730a3; text-align: center;">Kandidat Ketua Koperasi</th>
                </tr>
                <tr>
                    <th style="width: 30px;" class="text-center">No</th>
                    <th>Nama Kandidat</th>
                    <th class="text-right">Suara</th>
                    <th class="text-right">Porsi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rankingKetua as $idx => $k)
                    @php $kPct = $totalVoted > 0 ? round(($k->perolehan_suara_count / $totalVoted) * 100, 1) : 0; @endphp
                    <tr>
                        <td class="text-center font-mono">{{ $k->nomor_urut }}</td>
                        <td><strong>{{ $k->nama }}</strong></td>
                        <td class="text-right font-mono" style="font-weight: bold;">{{ number_format($k->perolehan_suara_count) }}</td>
                        <td class="text-right font-mono">{{ $kPct }}%</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">Belum ada kandidat</td></tr>
                @endforelse
            </tbody>
        </table>

        <table style="width: 49%; display: inline-table; vertical-align: top;">
            <thead>
                <tr>
                    <th colspan="4" style="background: #ecfdf5; color: #065f46; text-align: center;">Kandidat Pengawas Koperasi</th>
                </tr>
                <tr>
                    <th style="width: 30px;" class="text-center">No</th>
                    <th>Nama Kandidat</th>
                    <th class="text-right">Suara</th>
                    <th class="text-right">Porsi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rankingPengawas as $idx => $p)
                    @php $pPct = $totalVoted > 0 ? round(($p->perolehan_suara_count / $totalVoted) * 100, 1) : 0; @endphp
                    <tr>
                        <td class="text-center font-mono">{{ $p->nomor_urut }}</td>
                        <td><strong>{{ $p->nama }}</strong></td>
                        <td class="text-right font-mono" style="font-weight: bold;">{{ number_format($p->perolehan_suara_count) }}</td>
                        <td class="text-right font-mono">{{ $pPct }}%</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">Belum ada kandidat</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 3. Hourly Velocity Summary -->
    <div class="section-title">3. Ritme Kecepatan Suara per Jam (Hourly Velocity Timeline)</div>
    <table>
        <thead>
            <tr>
                @for($i = 6; $i <= 18; $i++)
                    <th class="text-center font-mono" style="font-size: 8px;">{{ sprintf('%02d', $i) }}:00</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            <tr>
                @for($i = 6; $i <= 18; $i++)
                    <td class="text-center font-mono" style="font-size: 9px; font-weight: bold;">
                        {{ $hoursValues[$i] ?? 0 }}
                    </td>
                @endfor
            </tr>
        </tbody>
    </table>

    <!-- 4. Security Audit -->
    <div class="section-title">4. Audit Forensik Keamanan & Hardware Scanner</div>
    <table>
        <thead>
            <tr>
                <th style="width: 100px;">Waktu</th>
                <th style="width: 110px;">Event</th>
                <th style="width: 80px;">Aktor</th>
                <th>Rincian Keterangan</th>
                <th style="width: 90px;" class="font-mono">IP Address</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentSecurityEvents->take(5) as $ev)
                <tr>
                    <td class="font-mono" style="font-size: 9px;">{{ $ev->created_at->format('d/m/Y H:i:s') }}</td>
                    <td><strong>{{ $ev->action }}</strong></td>
                    <td>{{ $ev->actor }}</td>
                    <td>{{ $ev->description }}</td>
                    <td class="font-mono" style="font-size: 9px;">{{ $ev->ip_address ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada insiden anomali keamanan yang tercatat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak otomatis oleh TapVote AI Core Engine • Hash Keamanan: {{ hash('sha256', $totalVoted . '|' . $totalVoters . '|' . date('YmdHis')) }}
    </div>
</body>
</html>
