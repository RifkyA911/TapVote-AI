@extends('layouts.admin')

@section('title', 'Audit Trail Activity Logs')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-800 text-slate-300">Audit Trail System</span>
                <h2 class="text-2xl font-black text-white">Log Aktivitas & Transaksi Database</h2>
            </div>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">Audit forensik atas semua transaksi CRUD, pemungutan suara, dan operasi client/DBMS.</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl">
        <form method="GET" action="{{ route('admin.logs.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[200px]">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search }}"
                    placeholder="Cari deskripsi, user, atau IP address..." 
                    class="w-full pl-10 pr-4 py-2 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white placeholder-slate-500 outline-none focus:border-blue-500"
                >
                <svg class="w-4 h-4 text-slate-500 absolute left-3.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            <select name="module" class="px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-xs text-slate-300 outline-none focus:border-blue-500">
                <option value="">Semua Modul</option>
                @foreach($modules as $m)
                    <option value="{{ $m }}" {{ $module == $m ? 'selected' : '' }}>{{ $m }}</option>
                @endforeach
            </select>

            <select name="action" class="px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-xs text-slate-300 outline-none focus:border-blue-500">
                <option value="">Semua Aksi</option>
                @foreach($actions as $a)
                    <option value="{{ $a }}" {{ $action == $a ? 'selected' : '' }}>{{ $a }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold transition">
                Filter Log
            </button>

            @if($search || $module || $action)
                <a href="{{ route('admin.logs.index') }}" class="text-xs text-slate-500 hover:text-white transition">Reset</a>
            @endif
        </form>
    </div>

    <!-- Logs Table -->
    <div class="rounded-3xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-950/50 text-slate-400 uppercase tracking-wider font-semibold">
                        <th class="py-3.5 px-4">Waktu</th>
                        <th class="py-3.5 px-4">Tipe User</th>
                        <th class="py-3.5 px-4">User Identifier</th>
                        <th class="py-3.5 px-4">Modul</th>
                        <th class="py-3.5 px-4">Aksi</th>
                        <th class="py-3.5 px-4">Deskripsi Aktivitas</th>
                        <th class="py-3.5 px-4">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="py-3 px-4 font-mono text-[11px] text-slate-400">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded font-mono text-[10px] font-bold {{ $log->user_type === 'admin' ? 'bg-blue-500/10 text-blue-400' : ($log->user_type === 'voter' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-slate-800 text-slate-400') }}">
                                    {{ strtoupper($log->user_type) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-300">{{ $log->user_identifier }}</td>
                            <td class="py-3 px-4 font-semibold text-slate-300">{{ $log->module }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded font-mono text-[10px] font-bold bg-slate-800 text-slate-300">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-300 max-w-sm">{{ $log->description }}</td>
                            <td class="py-3 px-4 font-mono text-[11px] text-slate-500">{{ $log->ip_address }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500">Tidak ada log aktivitas yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
