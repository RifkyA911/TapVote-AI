@extends('layouts.admin')

@section('title', 'Undian Doorprize Pemilih')

@section('content')
<div class="space-y-8">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/20 text-amber-400">Doorprize Engine 04</span>
                <h2 class="text-2xl font-black text-white">Peserta Berhak Mengikuti Undian</h2>
            </div>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">Daftar anggota yang telah menggunakan hak suaranya (Pilih = T) dan mesin undian digital.</p>
        </div>

        <button onclick="window.print()" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold border border-slate-700 transition flex items-center space-x-2 self-start sm:self-auto">
            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            <span>Cetak Daftar Undian</span>
        </button>
    </div>

    <!-- Interactive Digital Lucky Draw / Spin Slot Machine -->
    <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-amber-950/70 via-slate-900 to-slate-900 border-2 border-amber-500/40 backdrop-blur-xl shadow-2xl relative overflow-hidden text-center">
        <div class="absolute -left-10 -top-10 w-60 h-60 bg-amber-500/10 rounded-full blur-3xl"></div>

        <div class="max-w-xl mx-auto relative z-10">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-extrabold uppercase tracking-wider mb-4">
                <span>🎁</span>
                <span>Mesin Pengundian Doorprize Acak</span>
            </div>

            <!-- Rolling Slot Display -->
            <div class="p-6 sm:p-8 rounded-3xl bg-slate-950/90 border border-amber-500/30 shadow-inner mb-6">
                <span class="text-xs uppercase font-extrabold tracking-widest text-slate-500 block mb-2">Pemenang Undian:</span>
                <div id="slot-name" class="text-2xl sm:text-4xl font-black text-amber-400 font-sans tracking-wide min-h-[48px] flex items-center justify-center">
                    Klik "Spin Undian" Untuk Mengundi
                </div>
                <div id="slot-info" class="text-xs text-slate-400 font-mono mt-2 min-h-[20px]">
                    Pool: {{ $totalEligible }} Anggota Terverifikasi
                </div>
            </div>

            @if($totalEligible > 0)
                <button 
                    type="button" 
                    id="btn-spin"
                    onclick="spinLottery()"
                    class="px-8 py-4 rounded-2xl bg-gradient-to-r from-amber-500 via-amber-600 to-yellow-500 hover:from-amber-400 hover:to-yellow-400 text-slate-950 font-black text-base shadow-2xl shadow-amber-500/40 transform hover:-translate-y-1 transition duration-200"
                >
                    🎲 Spin & Putar Undian Sekarang!
                </button>
            @else
                <p class="text-xs text-amber-300/80">Belum ada anggota yang berstatus 'Sudah Memilih' untuk diundi.</p>
            @endif

            <!-- Winner Log Drawer -->
            <div id="winner-history-container" class="mt-8 pt-6 border-t border-slate-800 text-left hidden">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Daftar Pemenang Terpilih Hari Ini:</h4>
                <div id="winner-history-list" class="space-y-2"></div>
            </div>
        </div>
    </div>

    <!-- Eligible Voters Table -->
    <div class="rounded-3xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl shadow-xl overflow-hidden">
        <div class="p-5 border-b border-slate-800 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-white">Daftar Anggota Berhak Undian (Pilih = 'T')</h3>
                <p class="text-xs text-slate-400">Total {{ $totalEligible }} anggota berhak mendapatkan kupon undian doorprize.</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-mono font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                {{ $totalEligible }} Kupon Valid
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-950/50 text-slate-400 uppercase tracking-wider font-semibold">
                        <th class="py-3.5 px-5">No. Kupon</th>
                        <th class="py-3.5 px-5">NIK</th>
                        <th class="py-3.5 px-5">Nama Anggota</th>
                        <th class="py-3.5 px-5">Departemen</th>
                        <th class="py-3.5 px-5">Waktu Memberikan Suara</th>
                        <th class="py-3.5 px-5 text-right">Status Undian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @forelse($eligibleVoters as $idx => $voter)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="py-3.5 px-5 font-mono font-bold text-amber-400">#{{ str_pad($idx + 1, 3, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-3.5 px-5 font-mono text-slate-300">{{ $voter->nik }}</td>
                            <td class="py-3.5 px-5 font-bold text-white text-sm">{{ $voter->nama }}</td>
                            <td class="py-3.5 px-5 text-slate-400">{{ $voter->dept }}</td>
                            <td class="py-3.5 px-5 font-mono text-slate-400">
                                {{ $voter->voted_at ? $voter->voted_at->format('d/m/Y H:i:s') : '-' }}
                            </td>
                            <td class="py-3.5 px-5 text-right">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    Eligible ✓
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">Belum ada pemilih yang menyelesaikan voting.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const eligiblePool = @json($eligibleVoters);
    let isSpinning = false;
    let winnersList = [];

    function spinLottery() {
        if (isSpinning || eligiblePool.length === 0) return;

        isSpinning = true;
        const btn = document.getElementById('btn-spin');
        const nameEl = document.getElementById('slot-name');
        const infoEl = document.getElementById('slot-info');

        btn.setAttribute('disabled', 'disabled');
        btn.innerText = 'Sedang Mengundi...';

        let counter = 0;
        let speed = 40;
        const maxIterations = 50;

        function cycle() {
            counter++;
            const randomPick = eligiblePool[Math.floor(Math.random() * eligiblePool.length)];
            nameEl.innerText = randomPick.nama;
            infoEl.innerText = 'NIK: ' + randomPick.nik + ' • ' + randomPick.dept;

            if (counter < maxIterations) {
                setTimeout(cycle, speed);
                if (counter > 35) speed += 18; // deceleration effect
            } else {
                // Final Winner Selected
                isSpinning = false;
                btn.removeAttribute('disabled');
                btn.innerText = '🎲 Spin Undian Lagi';

                // Confetti blast
                if (typeof window.confetti === 'function') {
                    window.confetti({
                        particleCount: 150,
                        spread: 80,
                        origin: { y: 0.6 }
                    });
                }

                // Append to history
                winnersList.push(randomPick);
                renderWinnerHistory();
            }
        }

        cycle();
    }

    function renderWinnerHistory() {
        const container = document.getElementById('winner-history-container');
        const list = document.getElementById('winner-history-list');
        container.classList.remove('hidden');

        list.innerHTML = winnersList.map((w, i) => `
            <div class="p-3 rounded-xl bg-slate-950 border border-amber-500/30 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="w-6 h-6 rounded-full bg-amber-500/20 text-amber-300 font-bold text-xs flex items-center justify-center">#${i + 1}</span>
                    <div>
                        <strong class="text-white text-xs block">${w.nama}</strong>
                        <span class="text-[11px] text-slate-400 font-mono">NIK: ${w.nik} • ${w.dept}</span>
                    </div>
                </div>
                <span class="text-xs font-extrabold text-amber-400">PEMENANG</span>
            </div>
        `).join('');
    }
</script>
@endpush
@endsection
