@extends('layouts.admin')

@section('title', 'Mata Langit (Sky Eye Analytics) - TapVote AI')

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- Top Sky Eye Header -->
    <div class="p-6 sm:p-7 rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white border border-indigo-900/60 shadow-xl relative overflow-hidden">
        <div class="absolute -right-24 -top-24 w-80 h-80 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-24 -bottom-24 w-80 h-80 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center space-x-2 mb-2">
                    <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-indigo-500/30 text-indigo-300 border border-indigo-400/30">
                        🛰️ Telemetri Lintas Sektor
                    </span>
                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-mono font-bold bg-white/10 text-emerald-400 border border-white/15">
                        Live Database Stream
                    </span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Mata Langit (Sky Eye Analytics)</h2>
                <p class="text-xs sm:text-sm text-indigo-200/80 mt-1 max-w-2xl font-medium">
                    Pusat observasi komprehensif metrik pemilu: lonjakan partisipasi per departemen, histori kecepatan voting, audit keaslian kartu RFID, dan deteksi anomali.
                </p>
            </div>

            <div class="flex items-center gap-2.5 self-start md:self-auto">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/15 transition flex items-center space-x-1.5 cursor-pointer">
                    <span>← Kembali ke Dashboard</span>
                </a>
                <button type="button" onclick="window.location.reload()" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs shadow-md transition flex items-center space-x-1.5 cursor-pointer">
                    <span>↻ Segarkan Data</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 4 High-Altitude KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <!-- Metric 1: Quorum & Turnout -->
        <div class="p-5 rounded-3xl bg-white border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-extrabold tracking-wider text-slate-500">Tingkat Partisipasi</span>
                <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase {{ $quorumMet ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                    {{ $quorumMet ? 'Quorum Sah' : 'Menuju Quorum' }}
                </span>
            </div>
            <div class="my-3 flex items-baseline space-x-2">
                <span class="text-3xl sm:text-4xl font-black text-blue-600 font-mono">{{ $turnoutPct }}%</span>
                <span class="text-xs text-slate-500 font-semibold">Target: {{ $quorumThreshold }}%</span>
            </div>
            <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                <div class="h-full bg-blue-600 rounded-full transition-all duration-700" style="width: {{ min(100, $turnoutPct) }}%;"></div>
            </div>
        </div>

        <!-- Metric 2: Total Suara Sah vs Pending -->
        <div class="p-5 rounded-3xl bg-white border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-extrabold tracking-wider text-emerald-700">Total Suara Masuk</span>
                <span class="w-7 h-7 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">✓</span>
            </div>
            <div class="my-3 flex items-baseline space-x-2">
                <span class="text-3xl sm:text-4xl font-black text-emerald-600 font-mono">{{ $totalVoted }}</span>
                <span class="text-xs text-slate-500 font-semibold">/ {{ $totalVoters }} Anggota</span>
            </div>
            <span class="text-xs text-slate-500 font-medium">Sisa belum memilih: <strong class="text-amber-600 font-mono">{{ $remaining }}</strong></span>
        </div>

        <!-- Metric 3: Peak Voting Hour -->
        <div class="p-5 rounded-3xl bg-white border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-extrabold tracking-wider text-indigo-700">Jam Lonjakan Puncak</span>
                <span class="text-lg">⚡</span>
            </div>
            <div class="my-3 flex items-baseline space-x-2">
                <span class="text-3xl sm:text-4xl font-black text-indigo-600 font-mono">{{ $peakHourLabel }}</span>
                <span class="text-xs text-slate-500 font-semibold">WIB</span>
            </div>
            <span class="text-xs text-slate-500 font-medium">Volume tertinggi: <strong class="text-indigo-700 font-mono">{{ $peakHourVotes }} suara/jam</strong></span>
        </div>

        <!-- Metric 4: RFID Pass Rate -->
        <div class="p-5 rounded-3xl bg-white border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase font-extrabold tracking-wider text-slate-500">Integritas Kartu RFID</span>
                <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase bg-blue-100 text-blue-800">Mifare 13.56M</span>
            </div>
            <div class="my-3 flex items-baseline space-x-2">
                <span class="text-3xl sm:text-4xl font-black text-slate-900 font-mono">{{ $rfidAuthenticityRate }}%</span>
                <span class="text-xs text-slate-500 font-semibold">Pass Rate</span>
            </div>
            <span class="text-xs text-slate-500 font-medium">Kartu asing dicegah: <strong class="text-rose-600 font-mono">{{ $unknownCardAttempts }} kali</strong></span>
        </div>
    </div>

    <!-- Charts Row: 24-Hour Velocity & Department Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8">
        <!-- Left: Hourly Velocity Histogram -->
        <div class="lg:col-span-7 p-5 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base sm:text-lg font-black text-slate-900">Distribusi Kecepatan Suara per Jam</h3>
                    <p class="text-xs text-slate-500">Histori ritme kedatangan anggota di bilik suara dari waktu ke waktu</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-mono font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    24-Hour Spectrum
                </span>
            </div>

            <div id="chartHourlyVelocity" class="w-full min-h-[300px]"></div>
        </div>

        <!-- Right: Department Turnout Ranking -->
        <div class="lg:col-span-5 p-5 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base sm:text-lg font-black text-slate-900">Peringkat Partisipasi Departemen</h3>
                    <p class="text-xs text-slate-500">Persentase suara masuk di tiap unit kerja koperasi</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-mono font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                    {{ count($deptStats) }} Dept
                </span>
            </div>

            <div id="chartDeptRanking" class="w-full min-h-[300px]"></div>
        </div>
    </div>

    <!-- Department Detail Breakdown Table -->
    <div class="p-5 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base sm:text-lg font-black text-slate-900">Matriks Partisipasi Seluruh Departemen</h3>
                <p class="text-xs text-slate-500">Data kuota absensi, suara sah tercatat, dan sisa belum memilih per departemen</p>
            </div>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-200">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-extrabold uppercase text-[10px] tracking-wider">
                        <th class="py-3 px-4">Nama Departemen</th>
                        <th class="py-3 px-4 text-center">Total Anggota</th>
                        <th class="py-3 px-4 text-center">Suara Masuk</th>
                        <th class="py-3 px-4 text-center">Belum Memilih</th>
                        <th class="py-3 px-4 text-center">Partisipasi</th>
                        <th class="py-3 px-4 w-44">Progress Bar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($deptStats as $dept)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-bold text-slate-900">{{ $dept['dept'] }}</td>
                            <td class="py-3 px-4 text-center font-mono font-bold text-slate-700">{{ $dept['total'] }}</td>
                            <td class="py-3 px-4 text-center font-mono font-bold text-emerald-700">{{ $dept['voted'] }}</td>
                            <td class="py-3 px-4 text-center font-mono font-bold text-amber-700">{{ $dept['pending'] }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-mono font-black {{ $dept['pct'] >= 50 ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $dept['pct'] }}%
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="w-full bg-slate-200 h-2.5 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full" style="width: {{ $dept['pct'] }}%;"></div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Security & Hardware Anomaly Audit Log -->
    <div class="p-5 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base sm:text-lg font-black text-slate-900">Audit Forensik Keamanan & Hardware Scanner</h3>
                <p class="text-xs text-slate-500">Log insiden kartu tidak dikenal, pencegahan double voting, dan histori anomali sistem</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-mono font-bold bg-rose-50 text-rose-800 border border-rose-200">
                {{ count($recentSecurityEvents) }} Event Terdeteksi
            </span>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-200">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-extrabold uppercase text-[10px] tracking-wider">
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4">Aksi / Event</th>
                        <th class="py-3 px-4">Aktor</th>
                        <th class="py-3 px-4">Rincian Deskripsi</th>
                        <th class="py-3 px-4 font-mono">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($recentSecurityEvents as $ev)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-mono text-xs text-slate-500">{{ $ev->created_at->format('d/m H:i:s') }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-100 text-rose-800 border border-rose-300">
                                    {{ $ev->action }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-bold text-slate-800">{{ $ev->actor }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $ev->description }}</td>
                            <td class="py-3 px-4 font-mono text-xs text-slate-500">{{ $ev->ip_address ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-400">Tidak ada anomali atau ancaman keamanan yang terdeteksi. Sistem berjalan 100% normal.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Hourly Velocity Spline Area Chart
        const hourlyOptions = {
            series: [{
                name: 'Volume Suara Masuk',
                data: @json($hoursValues)
            }],
            chart: {
                type: 'area',
                height: 300,
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                toolbar: { show: false },
                animations: { enabled: true, easing: 'easeinout', speed: 800 }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2.5, colors: ['#4f46e5'] },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.5,
                    opacityTo: 0.05,
                    stops: [0, 95, 100],
                    colorStops: [
                        { offset: 0, color: '#4f46e5', opacity: 0.5 },
                        { offset: 100, color: '#4f46e5', opacity: 0.0 }
                    ]
                }
            },
            colors: ['#4f46e5'],
            xaxis: {
                categories: @json($hoursLabels),
                labels: { style: { colors: '#64748b', fontSize: '11px', fontWeight: 600 } },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: { style: { colors: '#64748b', fontSize: '11px', fontWeight: 600 } },
                min: 0,
                forceNiceScale: true
            },
            grid: { borderColor: '#f1f5f9' },
            tooltip: {
                theme: 'dark',
                y: { formatter: val => val + ' Suara' }
            }
        };
        new ApexCharts(document.querySelector("#chartHourlyVelocity"), hourlyOptions).render();

        // 2. Department Turnout Bar Chart
        const deptNames = @json($deptStats->pluck('dept'));
        const deptPcts = @json($deptStats->pluck('pct'));
        const deptOptions = {
            series: [{
                name: 'Partisipasi (%)',
                data: deptPcts
            }],
            chart: {
                type: 'bar',
                height: 300,
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    horizontal: true,
                    barHeight: '55%',
                    distributed: false
                }
            },
            colors: ['#059669'],
            dataLabels: {
                enabled: true,
                formatter: val => val + '%',
                style: { fontSize: '11px', fontWeight: 'bold', colors: ['#ffffff'] }
            },
            xaxis: {
                categories: deptNames,
                max: 100,
                labels: { style: { colors: '#64748b', fontSize: '11px', fontWeight: 600 } }
            },
            yaxis: {
                labels: { style: { colors: '#1e293b', fontSize: '11px', fontWeight: 700 } }
            },
            grid: { borderColor: '#f1f5f9' },
            tooltip: {
                theme: 'dark',
                y: { formatter: val => val + '%' }
            }
        };
        new ApexCharts(document.querySelector("#chartDeptRanking"), deptOptions).render();
    });
</script>
@endpush
@endsection
