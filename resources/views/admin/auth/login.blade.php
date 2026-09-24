@extends('layouts.app')

@section('title', 'Login Panitia / Administrator - TapVote AI')

@section('content')
<div class="flex-1 flex flex-col justify-center items-center p-4 sm:p-6 lg:p-8">
    <div class="max-w-md w-full">
        <!-- Logo & Branding -->
        <div class="text-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center shadow-xl shadow-blue-500/25 mx-auto mb-4">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h2 class="text-2xl font-black text-white">Panel Administrator</h2>
            <p class="text-xs text-slate-400 mt-1">TapVote AI • E-Voting Koperasi Control Center</p>
        </div>

        <!-- Login Card -->
        <div class="p-8 rounded-3xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl shadow-2xl">
            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Email Administrator</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="login-email"
                        value="{{ old('email', 'admin@tapvote.ai') }}" 
                        required 
                        class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 text-sm text-white placeholder-slate-500 outline-none transition"
                        placeholder="admin@tapvote.ai"
                    >
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="login-password"
                        required 
                        value="admin123"
                        class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 text-sm text-white placeholder-slate-500 outline-none transition"
                        placeholder="••••••••"
                    >
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center text-slate-400 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded bg-slate-800 border-slate-700 text-blue-600 mr-2">
                        <span>Ingat Sesi Saya</span>
                    </label>
                    <span class="text-slate-500 font-mono">Role: Super Admin</span>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-sm shadow-xl shadow-blue-600/30 hover:shadow-blue-600/50 transition">
                    Masuk ke Control Panel
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-800 text-center">
                <button 
                    type="button" 
                    onclick="document.getElementById('login-email').value='admin@tapvote.ai'; document.getElementById('login-password').value='admin123';"
                    class="text-xs text-blue-400 hover:text-blue-300 font-medium"
                >
                    Klik di sini untuk Auto-Fill Akun Demo Admin
                </button>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('voter.tap') }}" class="text-xs text-slate-500 hover:text-slate-300 transition">
                ← Kembali ke Kios Pemilih (Tap Card)
            </a>
        </div>
    </div>
</div>
@endsection
