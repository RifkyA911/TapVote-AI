@extends('layouts.app')

@section('title', 'Admin Portal Login - TapVote AI')

@push('styles')
<style>
    /* Pure Neumorphism (Soft UI) Elevation System */
    .neumorph-bg {
        background-color: #e8ecf2;
    }
    .neumorph-card {
        background: #e8ecf2;
        border-radius: 2.25rem;
        box-shadow: 18px 18px 36px #c3c9d4, -18px -18px 36px #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.6);
    }
    .neumorph-circle {
        background: #e8ecf2;
        border-radius: 50%;
        box-shadow: 8px 8px 18px #c3c9d4, -8px -8px 18px #ffffff;
    }
    .neumorph-inset {
        background: #e8ecf2;
        box-shadow: inset 4px 4px 9px #c3c9d4, inset -4px -4px 9px #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.4);
        transition: all 0.25s ease-in-out;
    }
    .neumorph-inset:focus {
        box-shadow: inset 5px 5px 10px #b6beca, inset -5px -5px 10px #ffffff, 0 0 0 2px rgba(37, 99, 235, 0.3);
        outline: none;
    }
    .neumorph-btn-primary {
        background: linear-gradient(145deg, #2b70f0, #1d4ed8);
        border-radius: 1.25rem;
        box-shadow: 6px 6px 14px #b8c0cc, -6px -6px 14px #ffffff, 0 4px 12px rgba(37, 99, 235, 0.25);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .neumorph-btn-primary:hover {
        background: linear-gradient(145deg, #3b82f6, #1e40af);
        box-shadow: 8px 8px 18px #b4bcc8, -8px -8px 18px #ffffff;
    }
    .neumorph-btn-primary:active {
        transform: scale(0.985);
        box-shadow: inset 3px 3px 6px rgba(0, 0, 0, 0.35);
    }
    .neumorph-pill {
        background: #e8ecf2;
        box-shadow: 4px 4px 8px #c5cbd6, -4px -4px 8px #ffffff;
        transition: all 0.2s ease;
    }
    .neumorph-pill:hover {
        box-shadow: 6px 6px 12px #bcc3cf, -6px -6px 12px #ffffff;
    }
</style>
@endpush

@section('content')
<div class="flex-1 flex flex-col justify-center items-center p-4 sm:p-6 lg:p-8 min-h-screen neumorph-bg transition-colors duration-500">
    <div class="max-w-md w-full my-auto">

        <!-- Neumorphic Tactile Branding -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 neumorph-circle text-blue-600 flex items-center justify-center mx-auto mb-4 relative">
                <svg class="w-10 h-10 drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight">Admin Telemetry Portal</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 font-semibold">TapVote AI • Cooperative Election Command Center</p>
        </div>

        <!-- Raised Neumorphic Card -->
        <div class="p-7 sm:p-9 neumorph-card">
            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email Input -->
                <div class="space-y-2">
                    <label class="block text-xs uppercase font-extrabold tracking-wider text-slate-600">
                        Admin Email Address
                    </label>
                    <div class="relative">
                        <input 
                            type="email" 
                            name="email" 
                            id="login-email"
                            value="{{ old('email', 'admin@tapvote.ai') }}" 
                            required 
                            class="w-full px-4 py-3.5 rounded-2xl text-sm font-semibold text-slate-800 placeholder-slate-400 neumorph-inset"
                            placeholder="admin@tapvote.ai"
                        >
                    </div>
                </div>

                <!-- Password Input -->
                <div class="space-y-2">
                    <label class="block text-xs uppercase font-extrabold tracking-wider text-slate-600">
                        Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password" 
                            id="login-password"
                            required 
                            value="admin123"
                            class="w-full px-4 py-3.5 rounded-2xl text-sm font-semibold text-slate-800 placeholder-slate-400 neumorph-inset font-mono"
                            placeholder="••••••••"
                        >
                    </div>
                </div>

                <!-- Checkbox & Role Pill -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center text-slate-600 cursor-pointer font-bold select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-blue-600 mr-2 cursor-pointer">
                        <span>Keep me logged in</span>
                    </label>
                    <span class="px-2.5 py-1 rounded-xl neumorph-pill text-[11px] font-mono font-bold text-slate-600">
                        Role: Master Admin
                    </span>
                </div>

                <!-- Neumorphic Tactile Submit Button -->
                <div class="pt-2">
                    <button 
                        type="submit" 
                        class="w-full py-4 text-white font-black text-sm tracking-wide neumorph-btn-primary cursor-pointer flex items-center justify-center space-x-2"
                    >
                        <span>Access Control Panel</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>

            <!-- Bottom Back Link (Neumorphic Pill) -->
            <div class="mt-7 pt-5 border-t border-slate-300/40 text-center">
                <a href="{{ route('voter.tap') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl neumorph-pill text-xs font-bold text-slate-700 hover:text-blue-600 transition">
                    <span>← Return to Voter Kiosk (Tap Card)</span>
                </a>
            </div>
        </div>

        <!-- Soft Security Watermark -->
        <div class="mt-6 text-center text-[11px] font-semibold text-slate-400 tracking-wide">
            Neumorphic Soft UI • SHA-256 Hash Verification • TapVote AI 2.0
        </div>
    </div>
</div>
@endsection
