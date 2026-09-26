@extends('layouts.admin')

@section('title', 'Vote Flow & Broker Summary Intelligence - TapVote AI')

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- Top Header Banner & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-2xs">
        <div>
            <div class="flex items-center space-x-2 mb-1.5">
                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-indigo-100 text-indigo-800 border border-indigo-200">
                    Vote Flow Intelligence
                </span>
                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-mono font-bold bg-slate-100 text-slate-700 border border-slate-200">
                    Broker Summary Matrix
                </span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Aliran Suara & Broker Summary</h2>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5 max-w-2xl">
                Audit visualisasi aliran suara dari setiap departemen ke masing-masing kandidat, konsentrasi blok pemilih, serta rasio dominasi elektoral.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 self-start sm:self-auto">
            <!-- Target Toggle (Ketua / Pengawas) -->
            <div class="inline-flex p-1 rounded-2xl bg-slate-100 border border-slate-200 text-xs font-bold">
                <a href="{{ route('admin.vote-flow', ['target' => 'ketua']) }}" class="px-3.5 py-1.5 rounded-xl transition {{ $target === 'ketua' ? 'bg-white text-blue-700 shadow-2xs font-black' : 'text-slate-600 hover:text-slate-900' }}">
                    Ketua
                </a>
                <a href="{{ route('admin.vote-flow', ['target' => 'pengawas']) }}" class="px-3.5 py-1.5 rounded-xl transition {{ $target === 'pengawas' ? 'bg-white text-emerald-700 shadow-2xs font-black' : 'text-slate-600 hover:text-slate-900' }}">
                    Pengawas
                </a>
            </div>

            <!-- Export PDF Button -->
            <a href="{{ route('admin.vote-flow.export.pdf', ['target' => $target]) }}" class="px-4 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold border border-slate-300 shadow-2xs transition flex items-center space-x-1.5 cursor-pointer" title="Download Official Vector PDF Report">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Export PDF</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-xs text-slate-500 font-semibold">Total Suara Terpetakan</span>
                <strong class="block text-2xl font-black text-slate-900 font-mono">{{ $totalVotes }} Suara</strong>
            </div>
            <span class="p-2.5 rounded-xl bg-blue-50 text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </span>
        </div>

        @php
            $topBroker = $brokerSummary[0] ?? null;
        @endphp
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-xs text-emerald-700 font-semibold">Lumbung Suara Terbesar (Top Broker)</span>
                <strong class="block text-xl font-black text-emerald-600 truncate mt-0.5">
                    {{ $topBroker ? $topBroker['dept'] : '-' }}
                </strong>
                <span class="text-[11px] text-slate-500 font-mono">{{ $topBroker ? $topBroker['total_votes'] . ' Suara (' . $topBroker['dept_share_pct'] . '%)' : '-' }}</span>
            </div>
            <span class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </span>
        </div>

        @php
            $avgLoyalty = count($brokerSummary) > 0 ? round(collect($brokerSummary)->avg('loyalty_rate'), 1) : 0;
        @endphp
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-xs text-indigo-700 font-semibold">Rata-rata Soliditas Departemen</span>
                <strong class="block text-2xl font-black text-indigo-600 font-mono">{{ $avgLoyalty }}%</strong>
                <span class="text-[11px] text-slate-500 font-medium">Tingkat konsolidasi satu calon per divisi</span>
            </div>
            <span class="p-2.5 rounded-xl bg-indigo-50 text-indigo-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </span>
        </div>
    </div>

    <!-- AI VISION SUMMARY CARD -->
    <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 text-white border-2 border-indigo-900/60 shadow-2xl relative overflow-hidden">
        <div class="absolute -right-24 -top-24 w-80 h-80 rounded-full bg-blue-500/15 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-24 -bottom-24 w-80 h-80 rounded-full bg-indigo-500/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-5 border-b border-indigo-800/40 gap-4">
                <div class="flex items-center space-x-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-500 to-blue-600 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="text-lg sm:text-xl font-black text-white tracking-tight">AI Vision Flow Intelligence</h3>
                            <span id="flow-ai-engine-badge" class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-400/30">
                                Google Gemini 2.0 Flash / Heuristic
                            </span>
                        </div>
                        <p class="text-xs text-indigo-200/70 mt-0.5">Penalaran AI mengenai pola lumbung suara, blok pemilih solid, dan kingmaker elektoral</p>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    <button 
                        id="btn-run-flow-ai" 
                        type="button" 
                        onclick="runVoteFlowAi()" 
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-xs sm:text-sm font-extrabold shadow-lg shadow-indigo-900/30 transition cursor-pointer flex items-center space-x-2"
                    >
                        <span id="flow-ai-btn-icon">⚡</span>
                        <span id="flow-ai-btn-text">Jalankan Analisis AI Vision</span>
                    </button>
                </div>
            </div>

            <!-- Dynamic AI Output Container -->
            <div id="flow-ai-container" class="space-y-4">
                <div class="p-5 rounded-2xl bg-indigo-950/60 border border-indigo-800/40 text-center py-8">
                    <span class="text-3xl block mb-2">🌊</span>
                    <h4 class="text-sm font-black text-white">AI Vision Flow Intelligence Siap Digunakan</h4>
                    <p class="text-xs text-indigo-200/70 max-w-md mx-auto mt-1">
                        Klik tombol di atas untuk menganalisis pemetaan aliansi departemen, lumbung suara penentu, dan tingkat soliditas pemilih secara otomatis.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- INTERACTIVE SANKEY FLOW DIAGRAM -->
    <div class="p-6 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-2xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-100 gap-2">
            <div>
                <h3 class="text-base font-extrabold text-slate-900 flex items-center space-x-2">
                    <span>🔀</span>
                    <span>Diagram Aliran Interaktif (Sankey Flow Chart)</span>
                </h3>
                <p class="text-xs text-slate-500">Arahkan kursor (*hover*) pada garis untuk melihat jumlah suara dari departemen ke kandidat.</p>
            </div>
            <div class="inline-flex items-center space-x-2 text-xs font-mono font-bold text-slate-500">
                <span>Departemen ➔ Calon {{ ucfirst($target) }}</span>
            </div>
        </div>

        @if(count($flowLinks) > 0)
            <div id="sankey-chart-container" class="w-full h-[420px] rounded-2xl bg-slate-50/50 p-2"></div>
        @else
            <div class="py-16 text-center text-slate-400 font-medium">
                <span class="text-3xl block mb-2">📭</span>
                Belum ada transaksi pemungutan suara yang tercatat untuk dipetakan ke dalam diagram alir.
            </div>
        @endif
    </div>

    <!-- BROKER SUMMARY RANKING TABLE -->
    <div class="space-y-4">
        <!-- Foldable Interactive Query Toolbar -->
        <div class="p-4 sm:p-5 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-3.5">
            <div class="flex items-center justify-between cursor-pointer select-none pb-2 border-b border-slate-100" onclick="toggleBrokerFold()">
                <div class="flex items-center space-x-2.5">
                    <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider text-slate-800">Filter & Broker Summary Search</h4>
                        <p class="text-[11px] text-slate-400">Cari peringkat lumbung suara berdasarkan nama departemen</p>
                    </div>
                </div>
                <button type="button" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-500 transition">
                    <span id="broker-fold-icon" class="text-xs font-mono font-bold block transform transition-transform duration-200">▲</span>
                </button>
            </div>

            <div id="broker-filter-body" class="space-y-3.5 transition-all duration-300">
                <div class="flex items-center gap-3">
                    <div class="relative flex-1">
                        <input 
                            type="text" 
                            id="broker-search-input" 
                            placeholder="Cari nama departemen (Tekan '/' untuk fokus)..."
                            oninput="filterBrokerTable()"
                            class="w-full pl-10 pr-10 py-3 rounded-2xl bg-slate-50/80 border border-slate-200 text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition font-medium"
                        >
                        <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <button type="button" onclick="clearBrokerSearch()" class="absolute right-3.5 top-3 text-slate-400 hover:text-slate-600 text-sm font-bold cursor-pointer" title="Hapus Pencarian">✕</button>
                    </div>
                    <div class="hidden md:flex items-center shrink-0">
                        <kbd class="px-2.5 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-500 text-[11px] font-bold font-mono">
                            /
                        </kbd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-2xs overflow-hidden relative">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm" id="broker-datatable">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 font-extrabold uppercase text-[11px] tracking-wider select-none bg-slate-50/80">
                            <th class="py-3.5 px-4 text-center">Rank</th>
                            <th class="py-3.5 px-4">Departemen (Broker)</th>
                            <th class="py-3.5 px-4 text-right">Volume Suara</th>
                            <th class="py-3.5 px-4 text-right">Porsi (%)</th>
                            <th class="py-3.5 px-4">Kandidat Terunggul di Divisi</th>
                            <th class="py-3.5 px-4 text-right">Soliditas (%)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="broker-table-body">
                        @forelse($brokerSummary as $idx => $b)
                            <tr class="hover:bg-slate-50/75 transition border-b border-slate-100 broker-row"
                                data-dept="{{ strtolower($b['dept']) }}">
                                <td class="py-3.5 px-4 text-center font-mono font-bold text-slate-500">
                                    <span class="w-6 h-6 rounded-full inline-flex items-center justify-center text-xs font-bold {{ $idx < 3 ? 'bg-indigo-100 text-indigo-700 font-black' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $idx + 1 }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <strong class="text-slate-900 block font-black text-sm">{{ $b['dept'] }}</strong>
                                </td>
                                <td class="py-3.5 px-4 text-right font-mono font-black text-slate-900">
                                    {{ $b['total_votes'] }} Suara
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <span class="font-mono font-bold text-slate-700">{{ $b['dept_share_pct'] }}%</span>
                                    <div class="w-16 bg-slate-100 rounded-full h-1.5 ml-auto mt-1 overflow-hidden">
                                        <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ min(100, $b['dept_share_pct'] * 3) }}%"></div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl bg-indigo-50 text-indigo-800 text-xs font-bold border border-indigo-200">
                                        {{ $b['top_candidate'] }} ({{ $b['top_candidate_votes'] }} suara)
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <span class="font-mono font-black text-xs {{ $b['loyalty_rate'] >= 60 ? 'text-emerald-600' : 'text-amber-600' }}">
                                        {{ $b['loyalty_rate'] }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400 font-medium">Belum ada data aliran suara.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- DETAILED CROSS-TABULATION MATRIX TABLE -->
    <div class="p-6 sm:p-7 rounded-3xl bg-white border border-slate-200 shadow-2xs space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base font-extrabold text-slate-900 flex items-center space-x-2">
                    <span>📊</span>
                    <span>Matriks Detail Aliran Suara (Department-to-Candidate Grid)</span>
                </h3>
                <p class="text-xs text-slate-500">Tabel tabulasi silang memperlihatkan rincian perolehan suara setiap calon di masing-masing departemen.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-600 uppercase text-[11px] font-extrabold tracking-wider">
                        <th class="py-3 px-4">Departemen</th>
                        @foreach($candidates as $c)
                            <th class="py-3 px-4 text-center">
                                <span class="block">No. {{ $c->nomor_urut }}</span>
                                <span class="text-[10px] text-slate-400 font-normal lowercase truncate block max-w-[120px]">{{ $c->nama }}</span>
                            </th>
                        @endforeach
                        <th class="py-3 px-4 text-right">Total Dept</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($departments as $d)
                        <tr class="hover:bg-slate-50 transition border-b border-slate-100">
                            <td class="py-3 px-4 font-bold text-slate-900">{{ $d }}</td>
                            @foreach($candidates as $c)
                                @php
                                    $v = $matrix[$d][$c->nik] ?? 0;
                                    $dTot = $deptTotals[$d] ?? 0;
                                    $pct = $dTot > 0 ? round(($v / $dTot) * 100) : 0;
                                @endphp
                                <td class="py-3 px-4 text-center font-mono {{ $v > 0 ? 'font-black text-indigo-700 bg-indigo-50/30' : 'text-slate-400' }}">
                                    <span>{{ $v }}</span>
                                    @if($v > 0)
                                        <span class="text-[10px] text-slate-400 block font-normal">{{ $pct }}%</span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="py-3 px-4 text-right font-mono font-black text-slate-900 bg-slate-50/60">
                                {{ $deptTotals[$d] ?? 0 }}
                            </td>
                        </tr>
                    @endforeach
                    <tr class="bg-slate-100 font-black border-t-2 border-slate-300">
                        <td class="py-3.5 px-4 uppercase text-slate-800">Total Suara Calon</td>
                        @foreach($candidates as $c)
                            <td class="py-3.5 px-4 text-center font-mono text-blue-700">
                                {{ $candidateTotals[$c->nik] ?? 0 }}
                            </td>
                        @endforeach
                        <td class="py-3.5 px-4 text-right font-mono text-slate-900">
                            {{ $totalVotes }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Apache ECharts CDN for Sankey Diagram -->
<script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>

<script>
// Render Sankey Flow Chart
document.addEventListener('DOMContentLoaded', () => {
    const chartDom = document.getElementById('sankey-chart-container');
    if (!chartDom) return;

    const myChart = echarts.init(chartDom);
    const nodes = @json($sankeyNodes);
    const links = @json($flowLinks);

    const option = {
        tooltip: {
            trigger: 'item',
            triggerOn: 'mousemove',
            formatter: (params) => {
                if (params.dataType === 'edge') {
                    return `<strong>${params.data.source} ➔ ${params.data.target}</strong><br/>Aliran Suara: <strong>${params.data.value} Suara</strong>`;
                }
                return `<strong>${params.name}</strong>`;
            }
        },
        series: [
            {
                type: 'sankey',
                layout: 'none',
                emphasis: {
                    focus: 'adjacency'
                },
                nodeAlign: 'justify',
                orient: 'horizontal',
                data: nodes,
                links: links,
                lineStyle: {
                    color: 'source',
                    curveness: 0.5,
                    opacity: 0.45
                },
                label: {
                    color: '#1e293b',
                    fontSize: 11,
                    fontWeight: 'bold'
                }
            }
        ]
    };

    myChart.setOption(option);
    window.addEventListener('resize', () => myChart.resize());
});

function toggleBrokerFold() {
    const body = document.getElementById('broker-filter-body');
    const icon = document.getElementById('broker-fold-icon');
    if (body.classList.contains('hidden')) {
        body.classList.remove('hidden');
        icon.style.transform = 'rotate(0deg)';
    } else {
        body.classList.add('hidden');
        icon.style.transform = 'rotate(180deg)';
    }
}

function clearBrokerSearch() {
    const input = document.getElementById('broker-search-input');
    input.value = '';
    filterBrokerTable();
    input.focus();
}

function filterBrokerTable() {
    const search = (document.getElementById('broker-search-input')?.value || '').toLowerCase().trim();
    const rows = document.querySelectorAll('.broker-row');

    rows.forEach(r => {
        const dept = r.getAttribute('data-dept') || '';
        r.style.display = (!search || dept.includes(search)) ? '' : 'none';
    });
}

document.addEventListener('keydown', (e) => {
    if (e.key === '/' && document.activeElement.tagName !== 'INPUT') {
        e.preventDefault();
        const input = document.getElementById('broker-search-input');
        if (input) input.focus();
    }
});

async function runVoteFlowAi() {
    const btn = document.getElementById('btn-run-flow-ai');
    const icon = document.getElementById('flow-ai-btn-icon');
    const text = document.getElementById('flow-ai-btn-text');
    const container = document.getElementById('flow-ai-container');
    const engineBadge = document.getElementById('flow-ai-engine-badge');

    btn.disabled = true;
    btn.classList.add('opacity-70', 'cursor-not-allowed');
    icon.innerHTML = '<span class="inline-block animate-spin">⚙️</span>';
    text.innerText = 'Menganalisis Aliran...';

    container.innerHTML = `
        <div class="p-6 rounded-2xl bg-indigo-950/60 border border-indigo-800/40 text-center py-10 space-y-3">
            <div class="inline-block animate-spin text-3xl">🌊</div>
            <h4 class="text-sm font-bold text-indigo-200">AI Vision sedang memetakan matriks aliran suara antar-departemen...</h4>
            <p class="text-xs text-indigo-300/60">Mengidentifikasi blok pemilih loyal, departemen swing, dan kingmaker elektoral</p>
        </div>
    `;

    try {
        const res = await fetch("{{ route('admin.vote-flow.ai', ['target' => $target]) }}", {
            headers: { 'Accept': 'application/json' }
        });
        const payload = await res.json();

        if (res.ok && payload.success && payload.data) {
            const d = payload.data;
            if (engineBadge && d.engine) {
                engineBadge.innerText = `${d.engine} • ${d.timestamp || ''}`;
            }

            let coalitionsHtml = '';
            if (Array.isArray(d.dominant_coalitions)) {
                coalitionsHtml = d.dominant_coalitions.map(c => `
                    <div class="p-3 rounded-xl bg-white/5 border border-white/10 flex items-start space-x-2 text-xs text-slate-200">
                        <span class="text-blue-400 font-bold">🏛️</span>
                        <span>${c}</span>
                    </div>
                `).join('');
            }

            let takeawaysHtml = '';
            if (Array.isArray(d.key_takeaways)) {
                takeawaysHtml = d.key_takeaways.map(t => `
                    <li class="flex items-start space-x-2 text-xs text-slate-200">
                        <span class="text-emerald-400 font-bold shrink-0">✓</span>
                        <span>${t}</span>
                    </li>
                `).join('');
            }

            container.innerHTML = `
                <div class="space-y-4 animate-fadeIn">
                    <!-- Top Summary & Score -->
                    <div class="p-5 rounded-2xl bg-indigo-900/40 border border-indigo-700/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <span class="text-[10px] font-black uppercase tracking-widest text-indigo-300">AI Vision Executive Summary</span>
                            <p class="text-xs sm:text-sm text-slate-100 font-medium leading-relaxed">${d.executive_summary || '-'}</p>
                        </div>
                        <div class="flex items-center space-x-3 shrink-0">
                            <div class="p-3 rounded-xl bg-white/5 border border-white/10 text-center">
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Consolidation Score</span>
                                <span class="text-base font-black text-emerald-400 font-mono">${d.consolidation_score || 85}%</span>
                            </div>
                            <div class="px-3.5 py-2.5 rounded-xl border text-xs font-black uppercase bg-emerald-500/20 text-emerald-300 border-emerald-500/40">
                                Risk: ${d.risk_assessment || 'LOW'}
                            </div>
                        </div>
                    </div>

                    <!-- Kingmaker & Coalitions -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 rounded-2xl bg-white/5 border border-white/10 space-y-2">
                            <h5 class="text-xs font-extrabold text-amber-300 flex items-center space-x-1.5">
                                <span>👑</span>
                                <span>Kingmaker & Swing Departments</span>
                            </h5>
                            <p class="text-xs text-slate-300 leading-relaxed">${d.kingmaker_analysis || '-'}</p>
                        </div>

                        <div class="space-y-2">
                            <h5 class="text-xs font-extrabold text-blue-300 flex items-center space-x-1.5">
                                <span>🤝</span>
                                <span>Koalisi Departemen Terkonsolidasi</span>
                            </h5>
                            <div class="space-y-1.5">${coalitionsHtml}</div>
                        </div>
                    </div>

                    <!-- Key Strategic Takeaways -->
                    <div class="p-4 rounded-2xl bg-slate-900/60 border border-indigo-900/60 space-y-2">
                        <h5 class="text-xs font-black uppercase tracking-wider text-indigo-300">Poin Kunci & Analisis Strategis:</h5>
                        <ul class="space-y-1.5">${takeawaysHtml}</ul>
                    </div>
                </div>
            `;
        } else {
            container.innerHTML = `
                <div class="p-4 rounded-2xl bg-rose-950/60 border border-rose-800 text-xs text-rose-200">
                    Gagal memuat analisis AI: ${payload.message || 'Terjadi kesalahan sistem.'}
                </div>
            `;
        }
    } catch(err) {
        console.error(err);
        container.innerHTML = `
            <div class="p-4 rounded-2xl bg-rose-950/60 border border-rose-800 text-xs text-rose-200">
                Terjadi gangguan koneksi jaringan saat memanggil AI.
            </div>
        `;
    } finally {
        btn.disabled = false;
        btn.classList.remove('opacity-70', 'cursor-not-allowed');
        icon.innerText = '⚡';
        text.innerText = 'Jalankan Analisis AI Vision';
    }
}
</script>
@endsection
