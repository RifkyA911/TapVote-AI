@extends('layouts.app')

@section('title', 'Login Panitia / Administrator - TapVote AI')

@section('content')
<div class="flex-1 flex flex-col justify-center items-center p-4 sm:p-6 lg:p-8 min-h-screen">
    <div class="max-w-md w-full">
        <!-- Logo & Branding -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-md mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Panel Administrator</h2>
            <p class="text-sm text-slate-600 mt-1 font-medium">TapVote AI • Sistem E-Voting Koperasi</p>
        </div>

        <!-- Login Card Bersih -->
        <div class="p-8 rounded-3xl bg-white border-2 border-slate-300 shadow-sm">
            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-slate-800 mb-2">Email Administrator</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="login-email"
                        value="{{ old('email', 'admin@tapvote.ai') }}" 
                        required 
                        class="w-full px-4 py-3.5 rounded-xl bg-white border-2 border-slate-300 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 text-base text-slate-900 placeholder-slate-400 outline-none transition"
                        placeholder="admin@tapvote.ai"
                    >
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-800 mb-2">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="login-password"
                        required 
                        value="admin123"
                        class="w-full px-4 py-3.5 rounded-xl bg-white border-2 border-slate-300 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 text-base text-slate-900 placeholder-slate-400 outline-none transition"
                        placeholder="••••••••"
                    >
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center text-slate-700 cursor-pointer font-medium">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-blue-600 mr-2">
                        <span>Ingat Sesi Saya</span>
                    </label>
                    <span class="text-slate-500 font-medium">Role: Admin</span>
                </div>

                <button type="submit" class="w-full py-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-base shadow transition">
                    Masuk ke Control Panel
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-200 text-center">
                <a href="{{ route('voter.tap') }}" class="text-sm font-bold text-blue-700 hover:underline">
                    ← Kembali ke Kios Pemilih (Tap Kartu)
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
