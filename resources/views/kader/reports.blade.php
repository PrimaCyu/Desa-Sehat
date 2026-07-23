@extends('layouts.app')

@section('title', 'Laporan Posyandu Digital')

@section('content')
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-slate-800 tracking-tight">Laporan Bulanan & Rekap Medis</h2>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Filter, cetak, atau unduh seluruh riwayat pemeriksaan kesehatan warga</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

    <!-- Filter Card (Sisi Kiri) -->
    <div class="lg:col-span-1">
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs sticky top-20">
            <h3 class="text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filter Laporan
            </h3>

            <form action="{{ route('kader.reports') }}" method="GET" class="space-y-4">
                <div>
                    <label for="category" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Kategori Kesehatan</label>
                    <select id="category" name="category" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-hidden transition cursor-pointer">
                        <option value="">Semua Kategori</option>
                        <option value="bayi" {{ request('category') === 'bayi' ? 'selected' : '' }}>Bayi (&lt; 12 Bulan)</option>
                        <option value="balita" {{ request('category') === 'balita' ? 'selected' : '' }}>Balita (1 - 5 Tahun)</option>
                        <option value="anak" {{ request('category') === 'anak' ? 'selected' : '' }}>Anak-Anak</option>
                        <option value="remaja" {{ request('category') === 'remaja' ? 'selected' : '' }}>Remaja</option>
                        <option value="dewasa" {{ request('category') === 'dewasa' ? 'selected' : '' }}>Dewasa</option>
                        <option value="ibu_hamil" {{ request('category') === 'ibu_hamil' ? 'selected' : '' }}>Ibu Hamil</option>
                        <option value="lansia" {{ request('category') === 'lansia' ? 'selected' : '' }}>Lansia</option>
                    </select>
                </div>

                <div>
                    <label for="start_date" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Mulai Tanggal</label>
                    <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-hidden transition">
                </div>

                <div>
                    <label for="end_date" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                    <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-hidden transition">
                </div>

                <div class="pt-2 space-y-2">
                    <button type="submit" class="w-full py-2.5 px-4 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold shadow-xs transition cursor-pointer">
                        Terapkan Filter
                    </button>
                    
                    <a href="{{ route('kader.reports') }}" class="w-full py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold text-center block transition">
                        Reset Filter
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Log List Panel (3/4 width) -->
    <div class="lg:col-span-3 space-y-4">
        
        <!-- Action Header Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white border border-slate-100 p-4 rounded-2xl shadow-xs">
            <span class="text-xs text-slate-600 font-semibold">
                Menampilkan <strong class="text-slate-800">{{ $histories->total() }}</strong> hasil rekap medis
            </span>
            <div class="flex items-center gap-2">
                <!-- CSV Export Button -->
                <a href="{{ route('kader.reports.export', request()->query()) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 px-3.5 py-2 rounded-xl transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Ekspor CSV
                </a>

                <!-- Print Table Button -->
                <button onclick="window.print()" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 border border-slate-200 px-3.5 py-2 rounded-xl transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-4H7v4a2 2 0 002 2z"></path></svg>
                    Cetak Halaman
                </button>
            </div>
        </div>

        <!-- History Records Table Card -->
        <div class="bg-white border border-slate-100 rounded-3xl p-4 sm:p-6 shadow-xs overflow-hidden print:shadow-none print:border-0 print:p-0">
            <div class="overflow-x-auto overflow-y-hidden">
                <table class="w-full table-fixed min-w-[700px] md:min-w-0 text-left border-collapse align-middle text-xs">
                    <thead>
                        <tr class="border-b border-slate-200/80 text-slate-500 font-semibold text-[11px] uppercase tracking-wider">
                            <th class="py-3 px-3 w-[14%] whitespace-nowrap">Tanggal</th>
                            <th class="py-3 px-3 w-[20%]">KK / Kepala</th>
                            <th class="py-3 px-3 w-[24%]">Nama Anggota</th>
                            <th class="py-3 px-3 w-[15%] whitespace-nowrap">Kategori</th>
                            <th class="py-3 px-3 w-[27%]">Ringkasan Medis</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($histories as $h)
                            <tr class="hover:bg-slate-50/60 transition-colors align-middle">
                                <td class="py-3.5 px-3 font-bold text-slate-600 align-middle whitespace-nowrap">
                                    {{ $h->tanggal_periksa->format('d/m/Y') }}
                                </td>
                                <td class="py-3.5 px-3 align-middle">
                                    <span class="font-bold text-slate-800 block truncate" title="{{ $h->anggotaKeluarga->pengguna->username }}">{{ $h->anggotaKeluarga->pengguna->username }}</span>
                                    <span class="text-[10px] text-slate-400 block mt-0.5 truncate" title="Bpk. {{ $h->anggotaKeluarga->pengguna->kepala_keluarga }}">Bpk. {{ $h->anggotaKeluarga->pengguna->kepala_keluarga }}</span>
                                </td>
                                <td class="py-3.5 px-3 align-middle">
                                    <a href="{{ route('kader.families.show', $h->anggotaKeluarga->pengguna_id) }}" class="font-semibold text-slate-800 hover:text-emerald-600 hover:underline block truncate" title="{{ $h->anggotaKeluarga->nama }}">
                                        {{ $h->anggotaKeluarga->nama }}
                                    </a>
                                    <span class="text-[10px] text-slate-400 block mt-0.5 truncate">NIK: {{ $h->anggotaKeluarga->nik }}</span>
                                </td>
                                <td class="py-3.5 px-3 align-middle whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-100">
                                        {{ $h->anggotaKeluarga->kategori_formatted }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 text-slate-600 leading-relaxed align-middle">
                                    <div class="line-clamp-2 leading-relaxed" title="BB {{ $h->berat_badan }} kg{{ $h->tinggi_badan ? ', TB '.$h->tinggi_badan.' cm' : '' }}{{ $h->tekanan_darah ? ', TD '.$h->tekanan_darah : '' }}{{ $h->gula_darah ? ', GDS '.$h->gula_darah : '' }}{{ $h->keluhan ? '. Keluhan: '.$h->keluhan : '' }}">
                                        <span class="font-bold text-slate-800">BB {{ $h->berat_badan }} kg</span>
                                        @if($h->tinggi_badan), TB {{ $h->tinggi_badan }} cm @endif
                                        @if($h->tekanan_darah), TD {{ $h->tekanan_darah }} @endif
                                        @if($h->gula_darah), GDS {{ $h->gula_darah }} @endif
                                        @if($h->usia_kehamilan), UK {{ $h->usia_kehamilan }} Mgg @endif
                                        @if($h->imunisasi), Imunisasi {{ $h->imunisasi }} @endif
                                        @if($h->vitamin), Vitamin {{ $h->vitamin }} @endif
                                        @if($h->keluhan). Keluhan: <span class="italic text-slate-500">{{ $h->keluhan }}</span>@endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400 text-xs">Belum ada data rekam medis Posyandu yang tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 print:hidden">
                {{ $histories->links() }}
            </div>
        </div>

    </div>

</div>

<!-- Print-only CSS layout styling -->
<style>
    @media print {
        header, aside, nav, footer, button, a, form, .print\:hidden {
            display: none !important;
        }
        body, main, .print\:shadow-none {
            background: white !important;
            color: black !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        table {
            width: 100% !important;
            table-layout: auto !important;
            border-collapse: collapse !important;
        }
        th, td {
            border-bottom: 1px solid #ddd !important;
            padding: 8px !important;
        }
    }
</style>
@endsection
