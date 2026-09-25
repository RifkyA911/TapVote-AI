@extends('layouts.admin')

@section('title', 'Audit Trail & Security Logs')

@section('content')
<div class="space-y-6">

    <!-- Top Header with Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-slate-200 text-slate-800 border border-slate-300">
                    Security Audit
                </span>
                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-mono font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    Activity Ledger
                </span>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900">Audit Trail & Security Logs</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Rekam jejak setiap aksi sistem: otentikasi login, tap RFID, transaksi bilik suara, dan aktivitas administratif.</p>
        </div>

        <div class="flex items-center gap-2.5 self-start sm:self-auto">
            <button onclick="window.print()" type="button" class="px-4 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold border border-slate-300 shadow-2xs transition flex items-center space-x-1.5 cursor-pointer">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Export PDF / Print</span>
            </button>
        </div>
    </div>

    <!-- Quick Stats Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-xs text-slate-500 font-semibold">Total Rekam Jejak</span>
                <strong class="block text-2xl font-black text-slate-900 font-mono">{{ $totalLogs ?? count($logs) }}</strong>
            </div>
            <span class="p-2.5 rounded-xl bg-blue-50 text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </span>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-xs text-rose-700 font-semibold">Security Alerts / Gagal</span>
                <strong class="block text-2xl font-black text-rose-600 font-mono">{{ $securityEvents ?? 0 }}</strong>
            </div>
            <span class="p-2.5 rounded-xl bg-rose-50 text-rose-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </span>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-xs text-emerald-700 font-semibold">Transaksi Sukses</span>
                <strong class="block text-2xl font-black text-emerald-600 font-mono">{{ $successEvents ?? 0 }}</strong>
            </div>
            <span class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </span>
        </div>
    </div>

    <!-- AI FORENSIC SECURITY & THREAT INTELLIGENCE REASONER -->
    <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 text-white border-2 border-indigo-900/60 shadow-2xl relative overflow-hidden">
        <div class="absolute -right-24 -top-24 w-80 h-80 rounded-full bg-rose-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-24 -bottom-24 w-80 h-80 rounded-full bg-indigo-500/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-5 border-b border-indigo-800/40 gap-4">
                <div class="flex items-center space-x-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-rose-500 to-indigo-600 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="text-lg sm:text-xl font-black text-white tracking-tight">AI Cybersecurity & Forensic Threat Reasoner</h3>
                            <span id="logs-ai-engine-badge" class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-400/30">
                                Google Gemini 2.0 Flash / Heuristic
                            </span>
                        </div>
                        <p class="text-xs text-indigo-200/70 mt-0.5">Penalaran forensik mendalam untuk deteksi anomali tap ganda, brute-force RFID, dan audit integritas data</p>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    <button 
                        id="btn-run-logs-ai" 
                        type="button" 
                        onclick="runLogsAiReasoning()" 
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-indigo-600 hover:from-rose-500 hover:to-indigo-500 text-white text-xs sm:text-sm font-extrabold shadow-lg shadow-rose-900/30 transition cursor-pointer flex items-center space-x-2"
                    >
                        <span id="logs-ai-btn-icon">🛡️</span>
                        <span id="logs-ai-btn-text">Audit Forensik dengan AI</span>
                    </button>
                </div>
            </div>

            <!-- Dynamic AI Output Container -->
            <div id="logs-ai-container" class="space-y-4">
                <div class="p-5 rounded-2xl bg-indigo-950/60 border border-indigo-800/40 text-center py-8">
                    <span class="text-3xl block mb-2">🔍</span>
                    <h4 class="text-sm font-black text-white">AI Forensic Threat Intelligence Siap Dijalankan</h4>
                    <p class="text-xs text-indigo-200/70 max-w-md mx-auto mt-1">
                        Klik tombol di atas untuk menjalankan penalaran forensik terhadap log audit trail, verifikasi tamper-proof sha256, dan pola percobaan bypass keamanan.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Foldable Interactive Query Toolbar -->
    <div class="p-4 sm:p-5 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-3.5">
        <!-- Foldable Header with Icon -->
        <div class="flex items-center justify-between cursor-pointer select-none pb-2 border-b border-slate-100" onclick="toggleFilterFold()">
            <div class="flex items-center space-x-2.5">
                <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                </div>
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-slate-800">Filter & Dynamic Search</h4>
                    <p class="text-[11px] text-slate-400">Cari log berdasarkan kata kunci deskripsi, modul, aksi, atau IP Address</p>
                </div>
            </div>
            <button type="button" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-500 transition">
                <span id="filter-fold-icon" class="text-xs font-mono font-bold block transform transition-transform duration-200">▲</span>
            </button>
        </div>

        <div id="filter-body-container" class="space-y-3.5 transition-all duration-300">
            <!-- Row 1: Full Width Search Input with Shortcut Hints -->
            <div class="flex items-center gap-3">
                <div class="relative flex-1">
                    <input 
                        type="text" 
                        id="logs-search-input" 
                        placeholder="Cari deskripsi, user identifier, atau IP address (Tekan '/' untuk fokus)..."
                        oninput="filterLogsTable()"
                        class="w-full pl-10 pr-10 py-3 rounded-2xl bg-slate-50/80 border border-slate-200 text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition font-medium"
                    >
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <button type="button" onclick="clearLogsSearch()" class="absolute right-3.5 top-3 text-slate-400 hover:text-slate-600 text-sm font-bold cursor-pointer" title="Hapus Pencarian">✕</button>
                </div>
                <div class="hidden md:flex items-center shrink-0">
                    <kbd class="px-2.5 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-500 text-[11px] font-bold font-mono">
                        /
                    </kbd>
                </div>
            </div>

            <!-- Row 2: Module, Action Filters, and Buttons -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                <div class="col-span-1">
                    <select id="logs-module-filter" onchange="filterLogsTable()" class="w-full h-11 px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 font-bold focus:bg-white focus:border-indigo-500 outline-none cursor-pointer">
                        <option value="">Semua Modul</option>
                        @foreach($modules as $m)
                            <option value="{{ $m }}">{{ $m }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-1">
                    <select id="logs-action-filter" onchange="filterLogsTable()" class="w-full h-11 px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 font-bold focus:bg-white focus:border-indigo-500 outline-none cursor-pointer">
                        <option value="">Semua Aksi</option>
                        @foreach($actions as $a)
                            <option value="{{ $a }}">{{ $a }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-1">
                    <button 
                        type="button" 
                        onclick="filterLogsTable()" 
                        class="w-full h-11 px-4 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white text-xs font-black shadow-xs transition flex items-center justify-center space-x-1.5 cursor-pointer"
                        title="Terapkan Filter"
                    >
                        <span>⚡</span>
                        <span>Apply</span>
                    </button>
                </div>

                <div class="col-span-1">
                    <button 
                        type="button" 
                        onclick="resetLogsFilters()" 
                        class="w-full h-11 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center justify-center space-x-1 cursor-pointer"
                    >
                        <span>↺</span>
                        <span>Reset</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Logs Table Container -->
    <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-2xs overflow-hidden relative">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm" id="logs-datatable">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-extrabold uppercase text-[11px] tracking-wider select-none bg-slate-50/80">
                        <th class="py-3.5 px-4">Timestamp</th>
                        <th class="py-3.5 px-4">Tipe Modul</th>
                        <th class="py-3.5 px-4">Aksi</th>
                        <th class="py-3.5 px-4">Keterangan Aktivitas</th>
                        <th class="py-3.5 px-4 text-right">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="logs-table-body">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/75 transition border-b border-slate-100 logs-row"
                            data-module="{{ strtolower($log->module) }}"
                            data-action="{{ strtolower($log->action) }}"
                            data-desc="{{ strtolower($log->description) }}"
                            data-ip="{{ $log->ip_address }}">
                            <td class="py-3 px-4 font-mono text-slate-500 text-xs">{{ $log->created_at->format('H:i:s d/m/Y') }}</td>
                            <td class="py-3 px-4 font-bold text-slate-700">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-slate-100 text-slate-700">
                                    {{ $log->module }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ str_contains($log->action, 'FAIL') || str_contains($log->action, 'REJECT') ? 'bg-rose-100 text-rose-800 border border-rose-200' : (str_contains($log->action, 'SUCCESS') ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200') }}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-800 font-medium">{{ $log->description }}</td>
                            <td class="py-3 px-4 text-right font-mono text-slate-500 text-xs">{{ $log->ip_address }}</td>
                        </tr>
                    @empty
                        <tr id="empty-logs-row">
                            <td colspan="5" class="py-12 text-center text-slate-400 font-medium">Belum ada rekam log aktivitas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script>
    function toggleFilterFold() {
        const container = document.getElementById('filter-body-container');
        const icon = document.getElementById('filter-fold-icon');
        if (container.classList.contains('hidden')) {
            container.classList.remove('hidden');
            icon.style.transform = 'rotate(0deg)';
        } else {
            container.classList.add('hidden');
            icon.style.transform = 'rotate(180deg)';
        }
    }

    function clearLogsSearch() {
        const input = document.getElementById('logs-search-input');
        input.value = '';
        filterLogsTable();
        input.focus();
    }

    function resetLogsFilters() {
        document.getElementById('logs-search-input').value = '';
        document.getElementById('logs-module-filter').value = '';
        document.getElementById('logs-action-filter').value = '';
        filterLogsTable();
    }

    function filterLogsTable() {
        const search = document.getElementById('logs-search-input').value.toLowerCase().trim();
        const module = document.getElementById('logs-module-filter').value.toLowerCase().trim();
        const action = document.getElementById('logs-action-filter').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.logs-row');

        rows.forEach(row => {
            const rowModule = row.getAttribute('data-module');
            const rowAction = row.getAttribute('data-action');
            const rowDesc = row.getAttribute('data-desc');
            const rowIp = row.getAttribute('data-ip');

            const matchesSearch = !search || rowDesc.includes(search) || rowIp.includes(search) || rowAction.includes(search) || rowModule.includes(search);
            const matchesModule = !module || rowModule === module;
            const matchesAction = !action || rowAction === action;

            if (matchesSearch && matchesModule && matchesAction) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === '/' && document.activeElement.tagName !== 'INPUT') {
            e.preventDefault();
            const input = document.getElementById('logs-search-input');
            if (input) input.focus();
        }
    });

    async function runLogsAiReasoning() {
        const btn = document.getElementById('btn-run-logs-ai');
        const icon = document.getElementById('logs-ai-btn-icon');
        const text = document.getElementById('logs-ai-btn-text');
        const container = document.getElementById('logs-ai-container');
        const engineBadge = document.getElementById('logs-ai-engine-badge');

        if (!btn || !container) return;

        btn.disabled = true;
        btn.classList.add('opacity-70', 'cursor-not-allowed');
        icon.innerHTML = '<span class="inline-block animate-spin">⚙️</span>';
        text.innerText = 'Menjalankan Audit Forensik...';

        container.innerHTML = `
            <div class="p-6 rounded-2xl bg-indigo-950/60 border border-indigo-800/40 text-center py-10 space-y-3">
                <div class="inline-block animate-spin text-3xl">🛡️</div>
                <h4 class="text-sm font-bold text-indigo-200">AI sedang menganalisis pola transaksi & log integritas kriptografis...</h4>
                <p class="text-xs text-indigo-300/60">Memeriksa upaya tap kartu asing, frekuensi brute-force, dan validitas audit trail</p>
            </div>
        `;

        try {
            const res = await fetch("{{ route('admin.logs.ai') }}", {
                headers: { 'Accept': 'application/json' }
            });
            const payload = await res.json();

            if (res.ok && payload.success && payload.data) {
                const d = payload.data;
                if (engineBadge && d.engine) {
                    engineBadge.innerText = `${d.engine} • ${d.timestamp || ''}`;
                }

                let threatBadgeColor = 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40';
                if (d.threat_level === 'ELEVATED') threatBadgeColor = 'bg-amber-500/20 text-amber-300 border-amber-500/40';
                else if (d.threat_level === 'HIGH' || d.threat_level === 'CRITICAL') threatBadgeColor = 'bg-rose-500/20 text-rose-300 border-rose-500/40';
                else if (d.threat_level === 'GUARDED') threatBadgeColor = 'bg-blue-500/20 text-blue-300 border-blue-500/40';

                let anomaliesHtml = '';
                if (Array.isArray(d.detected_anomalies)) {
                    anomaliesHtml = d.detected_anomalies.map(a => `
                        <div class="p-3 rounded-xl bg-white/5 border border-white/10 flex items-start space-x-2.5 text-xs text-slate-200">
                            <span class="text-amber-400 font-bold shrink-0">⚠️</span>
                            <span>${a}</span>
                        </div>
                    `).join('');
                }

                let recsHtml = '';
                if (Array.isArray(d.security_recommendations)) {
                    recsHtml = d.security_recommendations.map(r => `
                        <li class="flex items-start space-x-2 text-xs text-slate-200">
                            <span class="text-emerald-400 font-bold shrink-0">✓</span>
                            <span>${r}</span>
                        </li>
                    `).join('');
                }

                container.innerHTML = `
                    <div class="space-y-4 animate-fadeIn">
                        <!-- Top Forensic Summary & Threat Level -->
                        <div class="p-5 rounded-2xl bg-indigo-900/40 border border-indigo-700/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <span class="text-[10px] font-black uppercase tracking-widest text-indigo-300">Forensic Investigation Summary</span>
                                <p class="text-xs sm:text-sm text-slate-100 font-medium leading-relaxed">${d.forensic_summary || '-'}</p>
                            </div>
                            <div class="flex items-center space-x-3 shrink-0">
                                <div class="p-3 rounded-xl bg-white/5 border border-white/10 text-center">
                                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Integrity Verdict</span>
                                    <span class="text-xs font-black text-emerald-400 font-mono">${d.integrity_verdict || 'Tamper-Proof'}</span>
                                </div>
                                <div class="px-3.5 py-2.5 rounded-xl border text-xs font-black uppercase ${threatBadgeColor}">
                                    Threat: ${d.threat_level || 'SECURE'}
                                </div>
                            </div>
                        </div>

                        <!-- Attack Pattern Evaluation & Detected Anomalies -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-white/5 border border-white/10 space-y-2">
                                <h5 class="text-xs font-extrabold text-blue-300 flex items-center space-x-1.5">
                                    <span>🔎</span>
                                    <span>Evaluasi Pola Serangan & Replay Check</span>
                                </h5>
                                <p class="text-xs text-slate-300 leading-relaxed">${d.attack_pattern_evaluation || '-'}</p>
                            </div>

                            <div class="space-y-2">
                                <h5 class="text-xs font-extrabold text-amber-300 flex items-center space-x-1.5">
                                    <span>⚠️</span>
                                    <span>Hasil Analisis Kejadian Anomali</span>
                                </h5>
                                <div class="space-y-1.5">${anomaliesHtml}</div>
                            </div>
                        </div>

                        <!-- Security Recommendations -->
                        <div class="p-4 rounded-2xl bg-slate-900/60 border border-indigo-900/60 space-y-2">
                            <h5 class="text-xs font-black uppercase tracking-wider text-indigo-300">Rekomendasi Keamanan Siber & Panitia:</h5>
                            <ul class="space-y-1.5">${recsHtml}</ul>
                        </div>
                    </div>
                `;
            } else {
                container.innerHTML = `
                    <div class="p-4 rounded-2xl bg-rose-950/60 border border-rose-800 text-xs text-rose-200">
                        Gagal memuat analisis forensik AI: ${payload.message || 'Terjadi kesalahan sistem.'}
                    </div>
                `;
            }
        } catch (err) {
            console.error(err);
            container.innerHTML = `
                <div class="p-4 rounded-2xl bg-rose-950/60 border border-rose-800 text-xs text-rose-200">
                    Koneksi ke endpoint AI Forensic terputus. Silakan coba lagi.
                </div>
            `;
        } finally {
            btn.disabled = false;
            btn.classList.remove('opacity-70', 'cursor-not-allowed');
            icon.innerText = '🛡️';
            text.innerText = 'Audit Ulang dengan AI';
        }
    }
</script>
@endpush
@endsection
