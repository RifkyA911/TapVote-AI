<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara & Rekapitulasi Hasil Pemilihan - TapVote AI</title>
    <style>
        @page {
            margin: 1.5cm 1.5cm 1.5cm 1.5cm;
            size: a4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            line-height: 1.4;
            font-size: 11pt;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-bottom: 3px double #0f172a;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }
        .header-title {
            text-align: center;
        }
        .header-title h1 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0f172a;
        }
        .header-title h2 {
            margin: 4px 0 0 0;
            font-size: 13pt;
            font-weight: 600;
            color: #334155;
        }
        .header-title p {
            margin: 4px 0 0 0;
            font-size: 9pt;
            color: #64748b;
        }
        .doc-title {
            text-align: center;
            margin: 16px 0 20px 0;
        }
        .doc-title h3 {
            margin: 0;
            font-size: 14pt;
            text-decoration: underline;
            text-transform: uppercase;
            color: #0f172a;
        }
        .doc-title p {
            margin: 3px 0 0 0;
            font-size: 9pt;
            color: #475569;
        }
        .metrics-grid {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .metrics-grid td {
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            font-size: 10pt;
        }
        .metrics-grid .label {
            background-color: #f8fafc;
            font-weight: bold;
            width: 32%;
            color: #334155;
        }
        .metrics-grid .value {
            font-weight: bold;
            color: #0f172a;
        }
        .section-title {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 18px 0 8px 0;
            padding-left: 6px;
            border-left: 4px solid #2563eb;
            color: #0f172a;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-size: 9.5pt;
        }
        .table-data th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: bold;
            text-align: left;
            padding: 7px 10px;
            border: 1px solid #cbd5e1;
            text-transform: uppercase;
            font-size: 8.5pt;
        }
        .table-data td {
            padding: 7px 10px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .table-data tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: bold;
        }
        .badge-leader {
            background-color: #dcfce7;
            color: #166534;
        }
        .badge-candidate {
            background-color: #f1f5f9;
            color: #475569;
        }
        .signatures-table {
            width: 100%;
            margin-top: 36px;
            page-break-inside: avoid;
        }
        .signatures-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 10px 20px;
            font-size: 9.5pt;
        }
        .sig-space {
            height: 65px;
        }
        .footer-note {
            margin-top: 30px;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
            font-size: 8pt;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Header Dokumen -->
    <table class="header-table">
        <tr>
            <td class="header-title">
                <h1>PANITIA PEMILIHAN ELEKTRONIK KOPERASI</h1>
                <h2>SISTEM E-VOTING TAPVOTE-AI BERBASIS RFID</h2>
                <p>Alamat: Sekretariat Panitia Pemilihan Koperasi • Tahun Sidang 2026/2027</p>
            </td>
        </tr>
    </table>

    <!-- Judul Berita Acara -->
    <div class="doc-title">
        <h3>BERITA ACARA REKAPITULASI HASIL PEMUNGUTAN SUARA</h3>
        <p>Nomor Dokumen: BA-EVOTE/{{ date('Ymd') }}/{{ strtoupper(substr(md5($metrics['timestamp']), 0, 6)) }}</p>
    </div>

    <p style="font-size: 10pt; line-height: 1.5; margin-bottom: 14px;">
        Pada hari ini, <strong>{{ now()->isoFormat('dddd, D MMMM Y') }}</strong>, telah dilaksanakan pemungutan dan penghitungan suara secara elektronik (E-Voting) dengan otentikasi kartu pintar RFID Mifare ISO 14443A. Rekapitulasi perolehan suara resmi dilaporkan sebagai berikut:
    </p>

    <!-- Ringkasan Metrik Pemilihan -->
    <table class="metrics-grid">
        <tr>
            <td class="label">Total Daftar Pemilih Tetap (DPT)</td>
            <td class="value">{{ number_format($metrics['metrics']['total_voters']) }} Orang</td>
            <td class="label">Tingkat Partisipasi (Turnout)</td>
            <td class="value">{{ $metrics['metrics']['turnout_percentage'] }}%</td>
        </tr>
        <tr>
            <td class="label">Total Suara Sah Masuk</td>
            <td class="value">{{ number_format($metrics['metrics']['total_voted']) }} Suara</td>
            <td class="label">Status Kuorum Pemilihan</td>
            <td class="value">{{ $metrics['metrics']['turnout_percentage'] >= 50.0 ? 'KUORUM TERPENUHI (SAH)' : 'BELUM KUORUM' }}</td>
        </tr>
        <tr>
            <td class="label">Sisa Belum Memberikan Suara</td>
            <td class="value">{{ number_format($metrics['metrics']['remaining_voters']) }} Orang</td>
            <td class="label">Status Operasional Sistem</td>
            <td class="value">{{ $metrics['voting_status'] }}</td>
        </tr>
    </table>

    <!-- Tabel Hasil Calon Ketua Koperasi -->
    <div class="section-title">I. Perolehan Suara Calon Ketua Koperasi</div>
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 12%;">No. Urut</th>
                <th>Nama Kandidat Calon Ketua</th>
                <th style="width: 18%; text-align: right;">Perolehan Suara</th>
                <th style="width: 16%; text-align: right;">Persentase</th>
                <th style="width: 22%; text-align: center;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($metrics['ketua_results'] as $k)
                <tr>
                    <td style="font-weight: bold; text-align: center;">No. {{ $k['nomor_urut'] }}</td>
                    <td style="font-weight: bold; color: #0f172a;">{{ $k['nama'] }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($k['suara']) }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ $k['persen'] }}%</td>
                    <td style="text-align: center;">
                        @if($k['is_leader'])
                            <span class="badge badge-leader">Unggul Terpilih</span>
                        @elseif($k['is_tie'])
                            <span class="badge" style="background-color: #fef3c7; color: #92400e;">Hasil Seri (Draw)</span>
                        @else
                            <span class="badge badge-candidate">Kandidat</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tabel Hasil Calon Pengawas Koperasi -->
    <div class="section-title">II. Perolehan Suara Calon Pengawas Koperasi</div>
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 12%;">No. Urut</th>
                <th>Nama Kandidat Calon Pengawas</th>
                <th style="width: 18%; text-align: right;">Perolehan Suara</th>
                <th style="width: 16%; text-align: right;">Persentase</th>
                <th style="width: 22%; text-align: center;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($metrics['pengawas_results'] as $p)
                <tr>
                    <td style="font-weight: bold; text-align: center;">No. {{ $p['nomor_urut'] }}</td>
                    <td style="font-weight: bold; color: #0f172a;">{{ $p['nama'] }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($p['suara']) }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ $p['persen'] }}%</td>
                    <td style="text-align: center;">
                        @if($p['is_leader'])
                            <span class="badge badge-leader">Unggul Terpilih</span>
                        @elseif($p['is_tie'])
                            <span class="badge" style="background-color: #fef3c7; color: #92400e;">Hasil Seri (Draw)</span>
                        @else
                            <span class="badge badge-candidate">Kandidat</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="font-size: 9.5pt; color: #475569; margin-top: 12px;">
        Demikian Berita Acara Rekapitulasi Pemungutan Suara ini dibuat dengan sebenarnya dan disahkan secara digital tanpa adanya rekayasa atau manipulasi suara.
    </p>

    <!-- Tanda Tangan Pengesahan -->
    <table class="signatures-table">
        <tr>
            <td>
                <span>Mengetahui,</span><br>
                <strong>Ketua Panitia Pemilihan</strong>
                <div class="sig-space"></div>
                <strong>( ...................................................... )</strong><br>
                <span style="font-size: 8.5pt; color: #64748b;">NIP / NIK Panitia</span>
            </td>
            <td>
                <span>Ditetapkan di Tempat,</span><br>
                <strong>Sekretaris Panitia Pemilihan</strong>
                <div class="sig-space"></div>
                <strong>( ...................................................... )</strong><br>
                <span style="font-size: 8.5pt; color: #64748b;">NIP / NIK Panitia</span>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dokumen ini di-generate secara otomatis oleh Sistem TapVote-AI • Waktu Cetak: {{ now()->format('d/m/Y H:i:s') }} WIB • SHA-256 Validated
    </div>

</body>
</html>
