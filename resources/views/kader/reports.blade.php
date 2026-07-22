@extends('layouts.app')

@section('title', 'Laporan Posyandu Digital')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Laporan Bulanan & Rekap Medis</h2>
        <p class="text-xs text-slate-400">Filter, cetak, atau unduh seluruh riwayat pemeriksaan warga</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

    <!-- Filter Card -->
    <div class="lg:col-span-1">
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs sticky top-22">
            <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filter Laporan
            </h3>

            <form action="{{ route('kader.reports') }}" method="GET" class="space-y-4">
                <div>
                    <label for="category" class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Kategori Kesehatan</label>
                    <select id="category" name="category" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs cursor-pointer">
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
                    <label for="start_date" class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Mulai Tanggal</label>
                    <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>

                <div>
                    <label for="end_date" class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Sampai Tanggal</label>
                    <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>

                <div class="pt-2 space-y-2">
                    <button type="submit" class="w-full py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold shadow-xs transition cursor-pointer">
                        Terapkan Filter
                    </button>
                    
                    <a href="{{ route('kader.reports') }}" class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold text-center block transition">
                        Reset Filter
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Log List Panel (3/4 width) -->
    <div class="lg:col-span-3 space-y-4">
        
        <!-- Action Row -->
        <div class="flex items-center justify-between bg-white border border-slate-100 p-4 rounded-2xl shadow-xs">
            <span class="text-xs text-slate-500 font-semibold">
                Menampilkan {{ $histories->total() }} hasil rekap medis
            </span>
            <div class="flex gap-2">
                <!-- CSV Export -->
                <a href="{{ route('kader.reports.export', request()->query()) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 hover:text-emerald-700 bg-emerald-50 border border-emerald-100 px-3.5 py-2 rounded-xl transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Ekspor CSV
                </a>

                <!-- Print Table -->
                <button onclick="window.print()" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 border border-slate-200 px-3.5 py-2 rounded-xl transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-4H7v4a2 2 0 002 2z"></path></svg>
                    Cetak Halaman
                </button>
            </div>
        </div>

        <!-- History Records Table -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs print:shadow-none print:border-0 print:p-0">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 font-semibold">
                            <th class="pb-3">Tanggal</th>
                            <th class="pb-3">KK / Kepala</th>
                            <th class="pb-3">Nama Anggota</th>
                            <th class="pb-3">Kategori</th>
                            <th class="pb-3">Ringkasan Medis</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($histories as $h)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 font-bold text-slate-500 whitespace-nowrap">{{ $h->tanggal_periksa->format('d/m/Y') }}</td>
                                <td class="py-3.5">
                                    <span class="font-bold text-slate-700 block">{{ $h->anggotaKeluarga->pengguna->username }}</span>
                                    <span class="text-[10px] text-slate-400 block mt-0.5">Bpk. {{ $h->anggotaKeluarga->pengguna->kepala_keluarga }}</span>
                                </td>
                                <td class="py-3.5 font-semibold text-slate-800">
                                    <a href="{{ route('kader.families.show', $h->anggotaKeluarga->pengguna_id) }}" class="hover:underline hover:text-emerald-600">
                                        {{ $h->anggotaKeluarga->nama }}
                                    </a>
                                    <span class="text-[10px] text-slate-400 block mt-0.5">NIK: {{ $h->anggotaKeluarga->nik }}</span>
                                </td>
                                <td class="py-3.5">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-100 whitespace-nowrap">
                                        {{ $h->anggotaKeluarga->kategori_formatted }}
                                    </span>
                                </td>
                                <td class="py-3.5 text-slate-600 leading-relaxed max-w-[280px]">
                                    <span class="font-bold text-slate-700">BB {{ $h->berat_badan }} kg</span>
                                    @if($h->tinggi_badan), TB {{ $h->tinggi_badan }} cm @endif
                                    @if($h->tekanan_darah), TD {{ $h->tekanan_darah }} @endif
                                    @if($h->gula_darah), GDS {{ $h->gula_darah }} @endif
                                    @if($h->usia_kehamilan), UK {{ $h->usia_kehamilan }} Mgg @endif
                                    @if($h->imunisasi), Imunisasi {{ $h->imunisasi }} @endif
                                    @if($h->vitamin), Vitamin {{ $h->vitamin }} @endif
                                    @if($h->keluhan). Keluhan: <span class="italic text-slate-500">{{ $h->keluhan }}</span>@endif
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
        header, sidebar, nav, footer, button, a, form, .print\:hidden {
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
            border-collapse: collapse !important;
        }
        th, td {
            border-bottom: 1px solid #ddd !important;
            padding: 8px !important;
        }
    }
</style>
@endsection
