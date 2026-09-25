@extends('layouts.admin')

@section('title', 'Audit Trail Logs')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-200 text-slate-800">Security Audit</span>
                <h2 class="text-2xl font-extrabold text-slate-900">Audit Trail & Activity Logs</h2>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Rekam jejak setiap aksi sistem: login, tap RFID, voting transaksi, dan aktivitas admin.</p>
        </div>

        <div class="flex items-center gap-2.5 self-start sm:self-auto">
            <button onclick="window.print()" type="button" class="px-4 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold border border-slate-300 shadow-2xs transition flex items-center space-x-1.5 cursor-pointer">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>{{ __('Export PDF / Print') }}</span>
            </button>
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

    <!-- Logs Table -->
    <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-2xs">
        <div class="overflow-x-auto">
            <table class="datatable w-full text-left text-xs sm:text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                        <th class="py-3 px-3">Timestamp</th>
                        <th class="py-3 px-3">Tipe Modul</th>
                        <th class="py-3 px-3">Aksi</th>
                        <th class="py-3 px-3">Keterangan Aktivitas</th>
                        <th class="py-3 px-3 text-right">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-3 font-mono text-slate-500 text-xs">{{ $log->created_at->format('H:i:s d/m/Y') }}</td>
                            <td class="py-3 px-3 font-bold text-slate-700">{{ $log->module }}</td>
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ str_contains($log->action, 'FAIL') || str_contains($log->action, 'REJECT') ? 'bg-rose-100 text-rose-800 border border-rose-200' : (str_contains($log->action, 'SUCCESS') ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200') }}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-slate-800 font-medium">{{ $log->description }}</td>
                            <td class="py-3 px-3 text-right font-mono text-slate-500 text-xs">{{ $log->ip_address }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">Belum ada rekam log aktivitas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script>
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
