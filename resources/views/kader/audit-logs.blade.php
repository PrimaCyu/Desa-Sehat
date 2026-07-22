@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Log Audit Keamanan & Aktivitas</h2>
        <p class="text-xs text-slate-400">Riwayat jejak audit seluruh transaksi yang dilakukan di platform DesaSehat</p>
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
