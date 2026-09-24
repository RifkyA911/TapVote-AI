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
    </div>

    <!-- Logs Table -->
    <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
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
                            <td class="py-3 px-3 font-bold text-slate-700">{{ $log->modul }}</td>
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ str_contains($log->aksi, 'FAIL') || str_contains($log->aksi, 'REJECT') ? 'bg-rose-100 text-rose-800 border border-rose-200' : (str_contains($log->aksi, 'SUCCESS') ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200') }}">
                                    {{ $log->aksi }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-slate-800 font-medium">{{ $log->keterangan }}</td>
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

        <div class="mt-4 pt-4 border-t border-slate-200">
            {{ $logs->links() }}
        </div>
    </div>

</div>
@endsection
