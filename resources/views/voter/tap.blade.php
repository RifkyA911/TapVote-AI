@extends('layouts.app')

@section('title', __('Kios Pemilih - Tempelkan Kartu'))

@section('content')
<div class="flex-1 flex flex-col justify-between p-4 sm:p-6 lg:p-8 max-w-4xl mx-auto w-full min-h-screen">

    <!-- Header Instansi & Language Switcher -->
    <header class="flex items-center justify-between py-3 border-b border-slate-200">
        <div class="flex items-center space-x-3">
            <div class="w-11 h-11 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-sm shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">TapVote AI</h1>
                <p class="text-xs text-slate-500 font-medium">{{ __('Kios Pemungutan Suara') }}</p>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <!-- Language Switcher Pill -->
            <div class="inline-flex rounded-lg border border-slate-200 bg-white p-0.5 text-xs font-bold shadow-2xs">
                <a href="{{ route('lang.switch', 'en') }}" class="px-2 py-1 rounded-md transition {{ app()->getLocale() === 'en' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-blue-600' }}">EN</a>
                <a href="{{ route('lang.switch', 'id') }}" class="px-2 py-1 rounded-md transition {{ app()->getLocale() === 'id' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-blue-600' }}">ID</a>
            </div>

            <a href="{{ route('home') }}" class="text-xs font-semibold text-slate-600 hover:text-blue-700 py-1.5 px-3 rounded-lg border border-slate-200 hover:bg-slate-100 transition">
                📊 {{ __('Live Count') }}
            </a>
            <a href="{{ route('admin.login') }}" class="text-xs font-semibold text-slate-500 hover:text-blue-700 py-1.5 px-2.5 rounded-lg hover:bg-slate-100 transition">
                {{ __('Admin Panel') }} →
            </a>
        </div>
    </header>

    <!-- Main Tap Area (Animasi Bouncing Smooth & Nova Green / Danger Red Ripple) -->
    <div class="my-auto py-6 flex flex-col items-center justify-center text-center">
        
        <div id="tap-kiosk-box" class="w-full max-w-xl bg-white border border-slate-200 rounded-3xl p-6 sm:p-10 shadow-sm flex flex-col items-center relative overflow-hidden transition-all duration-500 {{ session('error') ? 'animate-distracted-shake border-rose-300' : '' }}">
            
            <!-- Sensor Target Box -->
            <div id="sensor-container" class="relative w-48 h-48 flex items-center justify-center mb-6">
                
                <!-- Normal Radar Rings -->
                <div id="normal-rings" class="absolute inset-0 flex items-center justify-center {{ session('error') ? 'hidden' : '' }}">
                    <div class="radar-ring w-40 h-40"></div>
                    <div class="radar-ring w-40 h-40"></div>
                    <div class="radar-ring w-40 h-40"></div>
                </div>

                <!-- Danger Red Distracted Rings (Ketika Error/Gagal) -->
                <div id="danger-rings" class="absolute inset-0 flex items-center justify-center {{ session('error') ? '' : 'hidden' }}">
                    <div class="danger-ring w-44 h-44"></div>
                    <div class="danger-ring w-44 h-44" style="animation-delay: 0.5s;"></div>
                    <div class="danger-ring w-44 h-44" style="animation-delay: 1s;"></div>
                </div>

                <!-- Green Nova Success Rings (Muncul saat Berhasil Tap) -->
                <div id="nova-rings" class="absolute inset-0 flex items-center justify-center hidden">
                    <div class="nova-success-ring w-48 h-48"></div>
                    <div class="nova-success-ring w-48 h-48" style="animation-delay: 0.4s;"></div>
                    <div class="nova-success-ring w-48 h-48" style="animation-delay: 0.8s;"></div>
                </div>

                <!-- Kartu RFID dengan Animasi Bouncing Smooth -->
                <div id="card-graphic" class="relative z-10 w-36 h-36 rounded-2xl bg-blue-50 border-2 border-blue-400 flex flex-col items-center justify-center p-4 shadow-sm animate-smooth-bounce transition-all duration-500">
                    <div id="card-icon" class="w-14 h-14 bg-blue-600 text-white rounded-xl flex items-center justify-center shadow-sm mb-2 transition-all duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <span id="card-label" class="text-[11px] font-extrabold text-blue-700 tracking-wider uppercase">RFID TAP</span>
                </div>
            </div>

            <!-- Petunjuk Jelas & Proporsional -->
            <h2 id="tap-title" class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-2 tracking-tight transition-all">
                {{ __('Tap Your Member ID Card') }}
            </h2>
            
            <p id="tap-desc" class="text-sm sm:text-base text-slate-600 font-medium max-w-md mb-6 leading-relaxed transition-all">
                {{ __('Place your RFID card near the scanner to open the digital ballot') }}
            </p>

            <!-- Status Indicator -->
            <div id="tap-status-pill" class="inline-flex items-center space-x-2.5 px-4 py-2 rounded-full {{ session('error') ? 'bg-rose-50 border-rose-300 text-rose-800' : 'bg-emerald-50 border-emerald-300 text-emerald-800' }} border text-xs sm:text-sm font-bold shadow-sm transition-all duration-500">
                <span id="tap-status-dot" class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ session('error') ? 'bg-rose-400' : 'bg-emerald-400' }} opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 {{ session('error') ? 'bg-rose-600' : 'bg-emerald-600' }}"></span>
                </span>
                <span id="tap-status-text">
                    {{ session('error') ? __('Card Rejected • Please Tap Again') : __('Scanner Ready • Waiting for Card') }}
                </span>
            </div>

            <p class="text-xs text-slate-400 mt-4">
                {{ __('Input otomatis terdeteksi tanpa perlu menekan layar.') }}
            </p>
        </div>

        <!-- Hidden Form & Input (Hanya menerima input dari RFID Hardware Reader) -->
        <form action="{{ route('voter.tap.process') }}" method="POST" id="tap-form" class="opacity-0 pointer-events-none absolute -top-96 -left-96" aria-hidden="true" tabindex="-1">
            @csrf
            <input 
                type="text" 
                name="rfid" 
                id="rfid_input" 
                autofocus
                autocomplete="off"
                tabindex="-1"
            >
        </form>

    </div>

    <!-- Section Simulasi Demo dengan Tombol Hide/Show Toggle -->
    <div class="mt-4 p-4 rounded-2xl bg-white border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-600 uppercase">Demo</span>
                <h3 class="text-xs sm:text-sm font-bold text-slate-800">{{ __('Simulasi Pengujian (Khusus Uji Coba Tanpa Scanner Fisik)') }}</h3>
            </div>
            
            <button 
                type="button" 
                id="toggle-demo-btn"
                onclick="toggleDemoSection()"
                class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition"
            >
                <span id="demo-btn-icon">👁️</span>
                <span id="demo-btn-text">{{ __('Buka Akun Demo') }}</span>
            </button>
        </div>

        <div id="demo-section-content" class="hidden mt-4 pt-3 border-t border-slate-100">
            <p class="text-xs text-slate-500 mb-3">{{ __('Klik tombol "Tap Kartu Ini" pada salah satu pemilih di bawah untuk mencoba:') }}</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2.5">
                @foreach($demoVoters as $demo)
                    <div class="p-2.5 rounded-xl border {{ $demo->sudahMemilih() ? 'bg-slate-50 border-slate-200 text-slate-400' : 'bg-white border-slate-200 hover:border-blue-400 shadow-2xs' }} flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] font-bold text-slate-700">{{ $demo->nik }}</span>
                                @if($demo->sudahMemilih())
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-500">{{ __('Sudah Memilih') }}</span>
                                @else
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">{{ __('Tersedia') }}</span>
                                @endif
                            </div>
                            <h4 class="text-xs font-bold text-slate-800 truncate" title="{{ $demo->nama }}">{{ $demo->nama }}</h4>
                            <p class="text-[11px] text-slate-500 truncate">{{ $demo->dept }}</p>
                        </div>

                        <div class="mt-2 pt-1.5 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[10px] font-mono text-slate-400">UID: {{ $demo->rfid }}</span>
                            @if(!$demo->sudahMemilih())
                                <button 
                                    type="button"
                                    onclick="triggerCardTap('{{ $demo->rfid }}')"
                                    class="px-2 py-1 rounded bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-bold shadow-2xs transition"
                                >
                                    {{ __('Tap Kartu Ini') }}
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    const rfidInput = document.getElementById('rfid_input');
    const tapForm = document.getElementById('tap-form');
    const normalRings = document.getElementById('normal-rings');
    const dangerRings = document.getElementById('danger-rings');
    const novaRings = document.getElementById('nova-rings');
    const cardGraphic = document.getElementById('card-graphic');
    const cardIcon = document.getElementById('card-icon');
    const cardLabel = document.getElementById('card-label');
    const tapTitle = document.getElementById('tap-title');
    const tapDesc = document.getElementById('tap-desc');
    const tapStatusPill = document.getElementById('tap-status-pill');
    const tapStatusText = document.getElementById('tap-status-text');

    // 1. Toggle Akun Demo
    function toggleDemoSection() {
        if (window.SoundEffects) window.SoundEffects.click();
        const content = document.getElementById('demo-section-content');
        const btnText = document.getElementById('demo-btn-text');
        const btnIcon = document.getElementById('demo-btn-icon');
        
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            btnText.innerText = '{{ __("Sembunyikan Akun Demo") }}';
            btnIcon.innerText = '🙈';
        } else {
            content.classList.add('hidden');
            btnText.innerText = '{{ __("Buka Akun Demo") }}';
            btnIcon.innerText = '👁️';
        }
    }

    // 2. Transisi Fluid Nova Green Saat Sukses Membaca Kartu
    function triggerCardTap(rfid) {
        if (!rfid) return;

        // Mainkan suara RFID chirp
        if (window.SoundEffects) window.SoundEffects.tap();

        // Aktifkan Efek Fluid Nova Green Smooth Switching
        normalRings.classList.add('hidden');
        dangerRings.classList.add('hidden');
        novaRings.classList.remove('hidden');

        // Transformasi Card Graphic menjadi Hijau Sukses
        cardGraphic.classList.remove('bg-blue-50', 'border-blue-400');
        cardGraphic.classList.add('bg-emerald-50', 'border-emerald-500', 'ring-8', 'ring-emerald-100');
        cardIcon.classList.remove('bg-blue-600');
        cardIcon.classList.add('bg-emerald-600', 'scale-110');
        cardIcon.innerHTML = `
            <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        `;
        cardLabel.innerText = "VERIFIED ✓";
        cardLabel.className = "text-[11px] font-black text-emerald-700 tracking-wider uppercase";

        tapTitle.innerText = "Kartu Terbaca Sah!";
        tapTitle.className = "text-2xl sm:text-3xl font-extrabold text-emerald-800 mb-2 tracking-tight";
        tapDesc.innerText = "Membuka bilik suara digital Anda sekarang...";
        
        tapStatusPill.className = "inline-flex items-center space-x-2.5 px-5 py-2.5 rounded-full bg-emerald-100 border border-emerald-400 text-emerald-900 text-sm font-extrabold shadow";
        tapStatusText.innerText = "✓ Akses Diterima • Mengarahkan...";

        // Mainkan melodi sukses
        setTimeout(() => {
            if (window.SoundEffects) window.SoundEffects.success();
        }, 120);

        // Submit form setelah animasi smooth dinikmati pengguna
        setTimeout(() => {
            rfidInput.value = rfid;
            tapForm.submit();
        }, 550);
    }

    // 3. Efek Distracted Red Danger Saat Terjadi Error
    @if(session('error'))
        document.addEventListener('DOMContentLoaded', () => {
            // Mainkan suara error
            if (window.SoundEffects) window.SoundEffects.error();

            // Set icon kartu menjadi peringatan merah
            cardGraphic.classList.remove('bg-blue-50', 'border-blue-400');
            cardGraphic.classList.add('bg-rose-50', 'border-rose-500', 'ring-4', 'ring-rose-200');
            cardIcon.classList.remove('bg-blue-600');
            cardIcon.classList.add('bg-rose-600');
            cardIcon.innerHTML = `
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            `;
            cardLabel.innerText = "REJECTED ✕";
            cardLabel.className = "text-[11px] font-black text-rose-700 tracking-wider uppercase";

            // Reset otomatis ke mode normal setelah 4 detik
            setTimeout(() => {
                dangerRings.classList.add('hidden');
                normalRings.classList.remove('hidden');
                cardGraphic.classList.remove('bg-rose-50', 'border-rose-500', 'ring-4', 'ring-rose-200');
                cardGraphic.classList.add('bg-blue-50', 'border-blue-400');
                cardIcon.classList.remove('bg-rose-600');
                cardIcon.classList.add('bg-blue-600');
                cardIcon.innerHTML = `
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                `;
                cardLabel.innerText = "RFID TAP";
                cardLabel.className = "text-[11px] font-extrabold text-blue-700 tracking-wider uppercase";
                tapStatusPill.className = "inline-flex items-center space-x-2.5 px-4 py-2 rounded-full bg-emerald-50 border border-emerald-300 text-emerald-800 text-xs sm:text-sm font-bold shadow-sm";
                tapStatusText.innerText = "{{ __('Scanner Ready • Waiting for Card') }}";
            }, 4000);
        });
    @endif

    // 4. Pastikan input selalu fokus untuk mendengarkan RFID USB Hardware Reader
    function keepFocus() {
        if (rfidInput && document.activeElement !== rfidInput) {
            rfidInput.focus();
        }
    }

    keepFocus();
    setInterval(keepFocus, 1000);

    // Kembalikan fokus jika layar disentuh
    document.addEventListener('click', function(e) {
        if (!e.target.closest('button') && !e.target.closest('a')) {
            keepFocus();
        }
    });

    // 5. Buffer Global USB RFID Reader
    let scanBuffer = '';
    let lastKeyTime = Date.now();

    window.addEventListener('keydown', function(e) {
        const currentTime = Date.now();
        lastKeyTime = currentTime;

        if (e.key === 'Enter') {
            e.preventDefault();
            const scannedValue = rfidInput.value.trim() || scanBuffer.trim();
            if (scannedValue.length > 0) {
                triggerCardTap(scannedValue);
            }
            scanBuffer = '';
            return;
        }

        if (e.key.length === 1) {
            scanBuffer += e.key;
        }
    });
</script>
@endpush
@endsection
