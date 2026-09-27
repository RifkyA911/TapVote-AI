@php
    $isEn = app()->getLocale() === 'en';
    $electionTitle = \App\Models\AppSetting::getElectionTitle();
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ $isEn ? 'Vote Flow & Department Distribution Report' : 'Laporan Aliran Suara & Distribusi Departemen' }} - TapVote AI</title>
    <style>
        @page { margin: 1.2cm; size: a4 landscape; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 8.5pt; color: #0f172a; line-height: 1.35; margin: 0; padding: 0; }
        .header { text-align: center; border-bottom: 2.5px solid #0284c7; padding-bottom: 10px; margin-bottom: 12px; }
        .title { font-size: 13pt; font-weight: 800; color: #0284c7; text-transform: uppercase; margin: 0; letter-spacing: 0.5px; }
        .subtitle { font-size: 10pt; color: #0f172a; font-weight: 800; margin-top: 2px; }
        .meta-p { font-size: 7.5pt; color: #64748b; margin-top: 2px; }
        .badge { display: inline-block; padding: 2px 7px; border-radius: 9999px; font-weight: 800; font-size: 7pt; text-transform: uppercase; }
        .badge-blue { background: #e0f2fe; color: #0369a1; }
        .badge-green { background: #dcfce7; color: #15803d; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 8pt; }
        th, td { border: 1px solid #cbd5e1; padding: 5px 8px; text-align: left; }
        th { background: #f8fafc; font-weight: 800; text-transform: uppercase; font-size: 7.5pt; color: #334155; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: monospace; }
        .summary-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 7px 12px; margin-bottom: 10px; font-size: 8pt; }
        .section-header { font-size: 9.5pt; font-weight: 800; color: #0f172a; margin-top: 10px; margin-bottom: 4px; text-transform: uppercase; padding-left: 6px; border-left: 3px solid #0284c7; }
        .footer { margin-top: 16px; font-size: 7.5pt; color: #94a3b8; text-align: right; border-top: 1px dashed #cbd5e1; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">{{ $isEn ? 'VOTE FLOW & DEPARTMENT DISTRIBUTION REPORT' : 'LAPORAN ALIRAN SUARA & DISTRIBUSI DEPARTEMEN' }}</h1>
        <div class="subtitle">{{ $electionTitle }} • {{ $isEn ? 'Target Category:' : 'Target Pemilihan:' }} {{ strtoupper($target) }}</div>
        <p class="meta-p">{{ $isEn ? 'Cross-Departmental Electoral Flow Matrix • TapVote AI Intelligence Core' : 'Analisis Matriks Aliran Elektoral Lintas Departemen • TapVote AI Intelligence Core' }}</p>
    </div>

    <div class="summary-box">
        <strong>{{ $isEn ? 'Executive Summary:' : 'Statistik Ringkas:' }}</strong> 
        {{ $isEn ? 'Total Analyzed Ballots:' : 'Total Suara Dianalisis:' }} <strong>{{ $flowData['totalVotes'] }} {{ $isEn ? 'Votes' : 'Suara' }}</strong> • 
        {{ $isEn ? 'Participating Departments:' : 'Total Departemen:' }} <strong>{{ count($flowData['departments']) }} {{ $isEn ? 'Divisions' : 'Departemen' }}</strong> • 
        {{ $isEn ? 'Timestamp:' : 'Waktu Unduh:' }} <strong>{{ date('d F Y, H:i:s') }} WIB</strong>
    </div>

    <div class="section-header">1. {{ $isEn ? 'Department Distribution Ranking (Vote Volume & Division Solidity)' : 'Tabel Distribusi Departemen (Peringkat Lumbung Suara per Departemen)' }}</div>
    <table>
        <thead>
            <tr>
                <th style="width: 25px;" class="text-center">No</th>
                <th>{{ $isEn ? 'Department' : 'Departemen' }}</th>
                <th class="text-right">{{ $isEn ? 'Vote Volume' : 'Total Suara' }}</th>
                <th class="text-right">{{ $isEn ? 'Share (%)' : 'Porsi (%)' }}</th>
                <th>{{ $isEn ? 'Leading Candidate in Division' : 'Kandidat Dominan yang Didukung' }}</th>
                <th class="text-right">{{ $isEn ? 'Candidate Votes' : 'Suara Kandidat' }}</th>
                <th class="text-right">{{ $isEn ? 'Solidity Rate (%)' : 'Tingkat Soliditas (%)' }}</th>
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

    <div class="section-header">2. {{ $isEn ? 'Detailed Cross-Tabulation Matrix (Department-to-Candidate Grid)' : 'Matriks Aliran Detail (Department-to-Candidate Vote Matrix)' }}</div>
    <table>
        <thead>
            <tr>
                <th>{{ $isEn ? 'Department' : 'Departemen' }}</th>
                @foreach($flowData['candidates'] as $c)
                    <th class="text-center font-mono">No. {{ $c->nomor_urut }}<br>{{ $c->nama }}</th>
                @endforeach
                <th class="text-right">{{ $isEn ? 'Total Division' : 'Total Dept' }}</th>
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
                            {{ $v }} <span style="color: #64748b; font-size: 7.5pt;">({{ $pct }}%)</span>
                        </td>
                    @endforeach
                    <td class="text-right font-mono" style="background: #f8fafc;">
                        <strong>{{ $flowData['deptTotals'][$d] ?? 0 }}</strong>
                    </td>
                </tr>
            @endforeach
            <tr style="background: #f1f5f9; font-weight: bold;">
                <td>{{ $isEn ? 'TOTAL CANDIDATE BALLOTS' : 'TOTAL SUARA KANDIDAT' }}</td>
                @foreach($flowData['candidates'] as $c)
                    <td class="text-center font-mono">{{ $flowData['candidateTotals'][$c->nik] ?? 0 }}</td>
                @endforeach
                <td class="text-right font-mono">{{ $flowData['totalVotes'] }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        {{ $isEn
            ? "Automatically certified by TapVote AI Sovereign Telemetry • Official Election Audit Document"
            : "Dicetak secara otomatis oleh Sistem TapVote AI Sovereign Telemetry • Dokumen Resmi Audit Pemilihan"
        }}
    </div>
</body>
</html>
