<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Aliran Suara & Broker Summary (Vote Flow)</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #1e293b; margin: 20px; }
        .header { text-align: center; border-bottom: 2px solid #0284c7; padding-bottom: 12px; margin-bottom: 16px; }
        .title { font-size: 18px; font-weight: bold; color: #0f172a; text-transform: uppercase; margin: 0; }
        .subtitle { font-size: 11px; color: #64748b; margin-top: 4px; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 6px; font-weight: bold; font-size: 9px; text-transform: uppercase; }
        .badge-blue { background: #e0f2fe; color: #0369a1; }
        .badge-green { background: #dcfce7; color: #15803d; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; font-size: 10px; }
        th, td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: left; }
        th { background: #f8fafc; font-weight: bold; text-transform: uppercase; font-size: 9px; color: #475569; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: monospace; }
        .summary-box { background: #f1f5f9; border-radius: 8px; padding: 10px; margin-bottom: 14px; font-size: 10px; }
        .footer { margin-top: 24px; font-size: 9px; color: #94a3b8; text-align: right; border-top: 1px solid #e2e8f0; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">Laporan Aliran Suara & Broker Summary Pemilihan</h1>
        <p class="subtitle">Analisis Matriks Aliran Elektoral Lintas Departemen • Target: {{ strtoupper($target) }}</p>
        <span class="badge badge-blue">TapVote AI Intelligence Core</span>
        <span class="badge badge-green">Sah & Terverifikasi</span>
    </div>

    <div class="summary-box">
        <strong>Statistik Ringkas:</strong> Total Suara Dianalisis: <strong>{{ $flowData['totalVotes'] }} Suara</strong> • Total Departemen Berpartisipasi: <strong>{{ count($flowData['departments']) }} Departemen</strong> • Waktu Unduh: <strong>{{ date('d F Y, H:i:s') }} WIB</strong>
    </div>

    <h3 style="font-size: 12px; margin-bottom: 4px; color: #0f172a;">1. Tabel Broker Summary (Peringkat Lumbung Suara per Departemen)</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">No</th>
                <th>Departemen (Broker)</th>
                <th class="text-right">Total Suara</th>
                <th class="text-right">Porsi (%)</th>
                <th>Kandidat Dominan yang Didukung</th>
                <th class="text-right">Suara Kandidat</th>
                <th class="text-right">Tingkat Soliditas (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($flowData['brokerSummary'] as $idx => $b)
                <tr>
                    <td class="text-center font-mono">{{ $idx + 1 }}</td>
                    <td><strong>{{ $b['dept'] }}</strong></td>
                    <td class="text-right font-mono">{{ $b['total_votes'] }}</td>
                    <td class="text-right font-mono">{{ $b['dept_share_pct'] }}%</td>
                    <td>{{ $b['top_candidate'] }}</td>
                    <td class="text-right font-mono">{{ $b['top_candidate_votes'] }}</td>
                    <td class="text-right font-mono"><strong>{{ $b['loyalty_rate'] }}%</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3 style="font-size: 12px; margin-top: 18px; margin-bottom: 4px; color: #0f172a;">2. Matriks Aliran Detail (Department-to-Candidate Vote Matrix)</h3>
    <table>
        <thead>
            <tr>
                <th>Departemen</th>
                @foreach($flowData['candidates'] as $c)
                    <th class="text-center">No. {{ $c->nomor_urut }}<br>{{ $c->nama }}</th>
                @endforeach
                <th class="text-right">Total Dept</th>
            </tr>
        </thead>
        <tbody>
            @foreach($flowData['departments'] as $d)
                <tr>
                    <td><strong>{{ $d }}</strong></td>
                    @foreach($flowData['candidates'] as $c)
                        @php
                            $v = $flowData['matrix'][$d][$c->nik] ?? 0;
                            $dTot = $flowData['deptTotals'][$d] ?? 0;
                            $pct = $dTot > 0 ? round(($v / $dTot) * 100) : 0;
                        @endphp
                        <td class="text-center font-mono">
                            {{ $v }} <span style="color: #64748b; font-size: 8px;">({{ $pct }}%)</span>
                        </td>
                    @endforeach
                    <td class="text-right font-mono" style="background: #f8fafc;">
                        <strong>{{ $flowData['deptTotals'][$d] ?? 0 }}</strong>
                    </td>
                </tr>
            @endforeach
            <tr style="background: #f1f5f9; font-weight: bold;">
                <td>TOTAL SUARA KANDIDAT</td>
                @foreach($flowData['candidates'] as $c)
                    <td class="text-center font-mono">{{ $flowData['candidateTotals'][$c->nik] ?? 0 }}</td>
                @endforeach
                <td class="text-right font-mono">{{ $flowData['totalVotes'] }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Dicetak secara otomatis oleh Sistem TapVote AI Sovereign Telemetry • Dokumen Resmi Audit Pemilihan
    </div>
</body>
</html>
