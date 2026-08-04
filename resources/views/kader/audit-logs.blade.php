@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem')

@section('content')
<!-- Hero Header Card (Ultra-Clean Responsive SaaS Banner) -->
<div class="bg-white border border-slate-100 rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-xs mb-5 sm:mb-6 relative overflow-hidden">
    <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-50 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="flex items-center justify-between gap-3 relative z-10">
        <!-- Icon & Titles -->
        <div class="flex items-center gap-3 min-w-0">
            <div class="p-2.5 sm:p-3 bg-emerald-500 text-white rounded-xl sm:rounded-2xl shadow-xs shrink-0">
                <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2">
                    <h2 class="text-base sm:text-xl font-extrabold text-slate-800 tracking-tight truncate">Log Audit Keamanan & Aktivitas</h2>
                    <span class="hidden sm:inline-block text-[10px] font-extrabold text-emerald-700 bg-emerald-50 border border-emerald-100 px-2.5 py-0.5 rounded-full uppercase">Audit System</span>
                </div>
                <p class="text-[11px] sm:text-xs text-slate-500 font-medium mt-0.5 truncate sm:whitespace-normal">Riwayat jejak audit seluruh transaksi & aktivitas yang dilakukan di platform DesaSehat</p>
            </div>
        </div>

        <!-- Quick Right Stat Badge -->
        <div class="shrink-0">
            <div class="bg-slate-50 border border-slate-150 px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl sm:rounded-2xl text-right">
                <span class="hidden sm:block text-[10px] text-slate-400 font-bold uppercase">Total Aktivitas</span>
                <span class="text-xs sm:text-sm font-extrabold text-emerald-600 leading-none whitespace-nowrap">{{ $logs->total() }} <span class="hidden sm:inline">Transaksi</span><span class="sm:hidden">Log</span></span>
            </div>
        </div>
    </div>
</div>

<!-- Logs Table Card -->
<div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="border-b border-slate-100 text-slate-400 font-semibold">
                    <th class="pb-3">Waktu Kejadian</th>
                    <th class="pb-3">Nama Pengguna</th>
                    <th class="pb-3">Aksi</th>
                    <th class="pb-3">Deskripsi Transaksi</th>
                    <th class="pb-3">Alamat IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-3.5 text-slate-500 font-medium whitespace-nowrap">
                            {{ $log->created_at->format('d/m/Y H:i') }} WIB
                        </td>
                        <td class="py-3.5 font-bold text-slate-700 whitespace-nowrap">
                            {{ $log->pengguna ? $log->pengguna->name : 'Sistem / Tamu' }}
                        </td>
                        <td class="py-3.5 whitespace-nowrap">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                @if(in_array($log->aksi, ['login', 'registrasi'])) bg-emerald-50 text-emerald-700 border border-emerald-100
                                @elseif($log->aksi === 'logout') bg-slate-100 text-slate-700 border border-slate-200
                                @elseif(str_contains($log->aksi, 'hapus')) bg-rose-50 text-rose-700 border border-rose-100
                                @else bg-amber-50 text-amber-700 border border-amber-100 @endif">
                                {{ $log->aksi }}
                            </span>
                        </td>
                        <td class="py-3.5 text-slate-650 font-medium leading-relaxed">{{ $log->deskripsi }}</td>
                        <td class="py-3.5 text-slate-400 font-mono whitespace-nowrap">{{ $log->ip_address }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400 text-xs">Belum ada catatan log aktivitas terekam.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 pt-4 border-t border-slate-100">
        {{ $logs->links() }}
    </div>
</div>
@endsection
