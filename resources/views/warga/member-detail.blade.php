@extends('layouts.app')

@section('title', 'Detail Kesehatan ' . $member->nama)

@section('content')
<!-- Header Back Button -->
<div class="flex items-center justify-between mb-6">
    <a href="{{ route('warga.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 px-3.5 py-2 rounded-xl transition shadow-xs">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Dashboard
    </a>
    <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 border border-emerald-100 px-3 py-1 rounded-full">
        {{ $member->kategori_formatted }}
    </span>
</div>

<!-- Profile Info Card -->
<div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs relative overflow-hidden mb-6">
    <div class="absolute -top-12 -right-12 w-32 h-32 bg-emerald-50 rounded-full blur-2xl"></div>
    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="bg-emerald-50 text-emerald-600 h-14 w-14 rounded-2xl flex items-center justify-center font-bold text-lg border border-emerald-100 shrink-0">
                {{ substr($member->nama, 0, 2) }}
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $member->nama }}</h2>
                <p class="text-xs text-slate-400 mt-1">NIK: {{ $member->nik }} &bull; Lahir: {{ $member->tanggal_lahir->format('d F Y') }}</p>
                <div class="flex items-center gap-4 mt-2">
                    <span class="text-xs font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">
                        Umur: {{ $member->umur }}
                    </span>
                    <span class="text-xs font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">
                        Gender: {{ $member->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Growth Chart (Only for Bayi / Balita) -->
@if(in_array($member->kategori, ['bayi', 'balita']) && $chartData && count($chartData['labels']) > 0)
    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs mb-6">
        <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Grafik Pertumbuhan Anak (KMS Digital)
        </h3>
        <div class="h-80 w-full">
            <canvas id="growthChart"></canvas>
        </div>
    </div>
@endif

<!-- Health Examination History Log -->
<div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs">
    <h3 class="text-base font-bold text-slate-800 mb-6 flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        Riwayat Pemeriksaan Posyandu
    </h3>

    <div class="space-y-6">
        @forelse($histories as $history)
            <div class="relative pl-6 border-l-2 border-slate-200 pb-2 last:pb-0">
                <!-- Timeline Dot Indicator -->
                <div class="absolute -left-1.5 top-1.5 w-3 h-3 bg-emerald-500 rounded-full border border-white"></div>
                
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400">{{ $history->tanggal_periksa->format('d F Y') }}</span>
                        <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                            {{ $member->kategori_formatted }}
                        </span>
                    </div>

                    <!-- Medical Details Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-2 text-[11px]">
                        <div>
                            <span class="text-slate-400 font-semibold block uppercase">Berat Badan</span>
                            <span class="text-slate-700 font-semibold">{{ $history->berat_badan }} kg</span>
                        </div>

                        @if($history->tinggi_badan)
                            <div>
                                <span class="text-slate-400 font-semibold block uppercase">Tinggi Badan</span>
                                <span class="text-slate-700 font-semibold">{{ $history->tinggi_badan }} cm</span>
                            </div>
                        @endif

                        @if($history->tekanan_darah)
                            <div>
                                <span class="text-slate-400 font-semibold block uppercase">Tensi Darah</span>
                                <span class="text-slate-700 font-semibold">{{ $history->tekanan_darah }} mmHg</span>
                            </div>
                        @endif

                        @if($history->usia_kehamilan)
                            <div>
                                <span class="text-slate-400 font-semibold block uppercase">Usia Kehamilan</span>
                                <span class="text-slate-700 font-semibold">{{ $history->usia_kehamilan }} Minggu</span>
                            </div>
                        @endif

                        @if($history->tinggi_fundus)
                            <div>
                                <span class="text-slate-400 font-semibold block uppercase">Tinggi Fundus</span>
                                <span class="text-slate-700 font-semibold">{{ $history->tinggi_fundus }} cm</span>
                            </div>
                        @endif

                        @if($history->lingkar_kepala)
                            <div>
                                <span class="text-slate-400 font-semibold block uppercase">Lingkar Kepala</span>
                                <span class="text-slate-700 font-semibold">{{ $history->lingkar_kepala }} cm</span>
                            </div>
                        @endif

                        @if($history->gula_darah)
                            <div>
                                <span class="text-slate-400 font-semibold block uppercase">Gula Darah</span>
                                <span class="text-slate-700 font-semibold">{{ $history->gula_darah }} mg/dL</span>
                            </div>
                        @endif

                        @if($history->imunisasi)
                            <div>
                                <span class="text-slate-400 font-semibold block uppercase">Imunisasi</span>
                                <span class="text-slate-700 font-semibold bg-emerald-50 text-emerald-800 px-1.5 py-0.5 rounded-md border border-emerald-100">{{ $history->imunisasi }}</span>
                            </div>
                        @endif

                        @if($history->vitamin)
                            <div>
                                <span class="text-slate-400 font-semibold block uppercase">Vitamin</span>
                                <span class="text-slate-700 font-semibold bg-amber-50 text-amber-800 px-1.5 py-0.5 rounded-md border border-amber-100">{{ $history->vitamin }}</span>
                            </div>
                        @endif

                        @if($history->obat)
                            <div>
                                <span class="text-slate-400 font-semibold block uppercase">Obat Diberikan</span>
                                <span class="text-slate-700 font-semibold bg-indigo-50 text-indigo-800 px-1.5 py-0.5 rounded-md border border-indigo-100">{{ $history->obat }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Complaints & Notes -->
                    @if($history->keluhan || $history->catatan)
                        <div class="mt-3 bg-white border border-slate-100 p-3 rounded-xl text-[11px] text-slate-500 space-y-2">
                            @if($history->keluhan)
                                <div>
                                    <span class="font-bold text-slate-700 block mb-0.5">Keluhan:</span>
                                    <p class="text-slate-600 font-medium leading-relaxed">{{ $history->keluhan }}</p>
                                </div>
                            @endif
                            @if($history->catatan)
                                <div>
                                    <span class="font-bold text-slate-700 block mb-0.5">Catatan Petugas:</span>
                                    <p class="text-slate-600 font-medium leading-relaxed">{{ $history->catatan }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        @empty
            <div class="text-center py-8 text-slate-400 text-xs">
                Belum ada riwayat pemeriksaan Posyandu tercatat untuk anggota keluarga ini.
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
@if(in_array($member->kategori, ['bayi', 'balita']) && $chartData && count($chartData['labels']) > 0)
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('growthChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['labels']) !!},
                datasets: [
                    {
                        label: 'Berat Badan (kg)',
                        data: {!! json_encode($chartData['weights']) !!},
                        borderColor: '#10b981', // emerald-500
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#10b981',
                        pointHoverRadius: 6,
                        tension: 0.3,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Tinggi Badan (cm)',
                        data: {!! json_encode($chartData['heights']) !!},
                        borderColor: '#06b6d4', // cyan-500
                        backgroundColor: 'rgba(6, 182, 212, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#06b6d4',
                        pointHoverRadius: 6,
                        tension: 0.3,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                family: 'Plus Jakarta Sans',
                                weight: '600',
                                size: 11
                            }
                        }
                    },
                    tooltip: {
                        padding: 12,
                        cornerRadius: 12,
                        titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: 'bold' },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 12 }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 10 } }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Berat (kg)',
                            font: { family: 'Plus Jakarta Sans', weight: 'bold', size: 10 }
                        },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 10 } }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Tinggi (cm)',
                            font: { family: 'Plus Jakarta Sans', weight: 'bold', size: 10 }
                        },
                        grid: { drawOnChartArea: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 10 } }
                    }
                }
            }
        });
    });
</script>
@endif
@endsection
