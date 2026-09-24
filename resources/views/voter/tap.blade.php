@extends('layouts.app')

@section('title', 'Kios Pemilih - Tap ID Card RFID Mifare')

@section('content')
<div class="flex-1 flex flex-col justify-between p-4 sm:p-6 lg:p-8 max-w-6xl mx-auto w-full">

    <!-- Top Header Bar -->
    <header class="flex items-center justify-between py-2 border-b border-slate-800/80">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <h1 class="text-base font-bold text-white tracking-wide">Kios Pemungutan Suara</h1>
                <p class="text-xs text-slate-400">Pemilihan Ketua & Pengawas Koperasi</p>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                <span class="w-2 h-2 mr-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Reader Ready: ISO 14443A
            </span>
            <a href="{{ route('admin.login') }}" class="text-xs text-slate-500 hover:text-slate-300 transition">Panel Panitia →</a>
        </div>
    </header>

    <!-- Center Tap Scanner Section -->
    <div class="my-auto py-8 flex flex-col items-center justify-center text-center">
        <!-- Interactive Tap Radar Target -->
        <div class="relative w-64 h-64 flex items-center justify-center mb-8">
            <div class="radar-ring w-48 h-48"></div>
            <div class="radar-ring w-48 h-48"></div>
            <div class="radar-ring w-48 h-48"></div>

            <div class="relative z-10 w-48 h-48 rounded-3xl bg-gradient-to-tr from-slate-900 to-slate-800 border-2 border-blue-500/50 shadow-2xl shadow-blue-600/30 flex flex-col items-center justify-center p-6 group cursor-pointer hover:border-blue-400 transition" onclick="document.getElementById('rfid_input').focus()">
                <div class="w-16 h-16 rounded-2xl bg-blue-600/20 text-blue-400 flex items-center justify-center mb-3 group-hover:scale-110 transition">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <span class="text-xs uppercase font-extrabold tracking-widest text-blue-400">TAP ID CARD</span>
                <span class="text-[11px] text-slate-400 mt-1">Mifare 13.56 MHz</span>
            </div>
        </div>

        <h2 class="text-2xl sm:text-3xl font-extrabold text-white mb-2">Tempelkan Kartu Anggota Anda</h2>
        <p class="text-slate-400 text-sm max-w-md mx-auto mb-6">
            Arahkan kartu RFID Anda ke sensor reader untuk membuka surat suara digital pemilihan Ketua & Pengawas Koperasi.
        </p>

        <!-- Tap Input Form (Auto-focusing) -->
        <form action="{{ route('voter.tap.process') }}" method="POST" id="tap-form" class="w-full max-w-sm">
            @csrf
            <div class="relative flex items-center">
                <input 
                    type="text" 
                    name="rfid" 
                    id="rfid_input" 
                    autofocus
                    autocomplete="off"
                    placeholder="Scan RFID UID atau NIK..." 
                    class="w-full px-5 py-3.5 rounded-2xl bg-slate-900/90 border border-slate-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 text-center text-base font-mono tracking-wider text-white placeholder-slate-500 shadow-inner outline-none transition"
                >
                <button type="submit" class="absolute right-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs transition">
                    Masuk
                </button>
            </div>
            <p class="text-[11px] text-slate-500 mt-2">Sensor otomatis membaca input USB HID Keyboard RFID Reader.</p>
        </form>
    </div>

    <!-- Interactive Section DEMO Simulation (Requirement Khusus: 'tambahkan section untuk demo') -->
    <div class="mt-4 p-5 rounded-2xl bg-slate-900/70 border border-slate-800 backdrop-blur-xl shadow-xl">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-800">
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded-md text-[11px] font-extrabold bg-blue-500/20 text-blue-400 uppercase tracking-wider">Demo Mode</span>
                <h3 class="text-sm font-bold text-white">Simulasi Tap Kartu RFID (Pilih Sample Pemilih)</h3>
            </div>
            <span class="text-xs text-slate-400">Klik salah satu kartu di bawah untuk mencoba tanpa reader fisik:</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3">
            @foreach($demoVoters as $demo)
                <div class="p-3.5 rounded-xl border {{ $demo->sudahMemilih() ? 'bg-slate-950/40 border-slate-800/40 opacity-60' : 'bg-slate-800/40 border-slate-700/60 hover:border-blue-500 hover:bg-slate-800 transition shadow-sm' }} flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="font-mono text-xs font-bold text-blue-400">{{ $demo->nik }}</span>
                            @if($demo->sudahMemilih())
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-800 text-slate-400">Sudah Vote</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300">Siap Tap</span>
                            @endif
                        </div>
                        <h4 class="text-xs font-semibold text-white truncate" title="{{ $demo->nama }}">{{ $demo->nama }}</h4>
                        <p class="text-[11px] text-slate-400 truncate">{{ $demo->dept }}</p>
                    </div>

                    <div class="mt-3 pt-2 border-t border-slate-700/50 flex items-center justify-between">
                        <span class="font-mono text-[10px] text-slate-400" title="UID RFID">{{ $demo->rfid }}</span>
                        @if(!$demo->sudahMemilih())
                            <button 
                                type="button"
                                onclick="simulateTap('{{ $demo->rfid }}')"
                                class="px-2.5 py-1 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-[11px] font-medium shadow-sm transition"
                            >
                                Tap Kartu
                            </button>
                        @else
                            <span class="text-[10px] text-slate-500 italic">Voted</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Pastikan input selalu fokus untuk mendengarkan RFID USB reader
    const rfidInput = document.getElementById('rfid_input');
    if (rfidInput) {
        rfidInput.focus();
        // Kembalikan fokus jika user tidak sengaja klik di luar
        document.addEventListener('click', function(e) {
            if (!e.target.closest('button') && !e.target.closest('a')) {
                rfidInput.focus();
            }
        });
    }

    // Fungsi simulasi demo tap
    function simulateTap(rfid) {
        if (rfidInput) {
            rfidInput.value = rfid;
            document.getElementById('tap-form').submit();
        }
    }
</script>
@endpush
@endsection
