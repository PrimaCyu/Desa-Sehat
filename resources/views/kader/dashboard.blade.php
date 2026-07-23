@extends('layouts.app')

@section('title', 'Posyandu Command Center')

@section('content')
<style>
    .mini-calendar-grid {
        display: grid !important;
        grid-template-columns: repeat(7, 1fr) !important;
        gap: 3px !important;
        justify-items: center !important;
        align-items: center !important;
        width: 100% !important;
    }
    .calendar-day-cell {
        width: 100% !important;
        max-width: 32px !important;
        aspect-ratio: 1 / 1 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 6px !important;
        font-size: 10px !important;
        font-weight: 700 !important;
        margin: 0 auto !important;
    }
</style>
<!-- Command Center Hero Section -->
<div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-700 text-white rounded-3xl p-6 md:p-8 shadow-xl relative overflow-hidden mb-6">
    <div class="absolute right-0 bottom-0 opacity-10 translate-x-12 translate-y-12">
        <svg class="w-72 h-72" fill="currentColor" viewBox="0 0 24 24">
            <path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2m-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"></path>
        </svg>
    </div>
    <div class="relative z-10 grid grid-cols-1 lg:grid-cols-3 gap-6 items-center">
        <!-- Greetings & Live clock -->
        <div class="lg:col-span-2 space-y-3">
            <span class="bg-white/20 text-white px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider backdrop-blur-xs">
                Pusat Kendali Pelayanan &bull; Posyandu Command Center
            </span>
            <h2 class="text-3xl font-black tracking-tight" id="hero-greetings">Selamat Pagi, Kader!</h2>
            <p class="text-sm text-emerald-100 max-w-xl">
                Pantau antrean Posyandu digital hari ini secara real-time, prioritaskan warga yang membutuhkan tindakan segera, dan catat hasil rekap medis dengan cepat.
            </p>
            <!-- Real-time clock widget -->
            <div class="flex items-center gap-3 text-xs text-emerald-50 bg-emerald-500/20 border border-emerald-400/20 px-3.5 py-2 rounded-xl w-fit">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-300"></span>
                </span>
                <span id="live-clock" class="font-semibold">Mengecek Waktu...</span>
            </div>
        </div>

        <!-- Next Schedule Quick Overview -->
        <div @if($schedules->first()) onclick="openKaderScheduleModal({{ $schedules->first()->id }})" @endif class="bg-white/10 border border-white/20 p-5 rounded-2xl backdrop-blur-xs space-y-2.5 hover:bg-white/15 transition cursor-pointer group">
            <div class="flex items-center justify-between">
                <span class="text-[10px] uppercase font-bold tracking-wider text-emerald-200">Agenda Posyandu Terdekat</span>
                @if($schedules->first())
                    <span class="text-[9px] font-bold bg-white/20 text-white px-2 py-0.5 rounded-md backdrop-blur-xs group-hover:bg-emerald-400 group-hover:text-emerald-950 transition">Detail</span>
                @endif
            </div>
            @if($schedules->first())
                <h4 class="font-extrabold text-sm leading-snug text-white group-hover:text-emerald-100 transition">{{ $schedules->first()->judul }}</h4>
                <div class="flex items-center gap-2 text-xs text-emerald-100">
                    <svg class="w-4 h-4 shrink-0 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>{{ $schedules->first()->tanggal_kegiatan->format('d M Y') }} &bull; {{ substr($schedules->first()->jam_mulai, 0, 5) }} WIB</span>
                </div>
                <div class="flex items-center gap-2 text-xs text-emerald-100">
                    <svg class="w-4 h-4 shrink-0 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="line-clamp-1">{{ $schedules->first()->tempat }}</span>
                </div>
            @else
                <p class="text-xs text-emerald-100">Belum ada agenda terdekat.</p>
            @endif
        </div>
    </div>
</div>

<!-- Instant Quick Action Menu -->
<div class="bg-white border border-slate-100 rounded-3xl p-5 shadow-xs mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:flex md:flex-wrap items-center gap-3">
        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider col-span-1 sm:col-span-2 md:col-span-1">Aksi Cepat:</span>
        <button onclick="focusSearch()" class="inline-flex items-center justify-center gap-1.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-semibold text-xs px-4 py-2.5 rounded-xl transition cursor-pointer w-full md:w-auto">
            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            Cari Warga
        </button>
        <a href="#queue-monitor-section" class="inline-flex items-center justify-center gap-1.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-semibold text-xs px-4 py-2.5 rounded-xl transition w-full md:w-auto">
            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M18.364 5.636a9 9 0 010 12.728M12 18a6 6 0 100-12 6 6 0 000 12z"></path></svg>
            Panggil Antrean
        </a>
        <a href="{{ route('kader.families.index') }}#add-family-form" class="inline-flex items-center justify-center gap-1.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-semibold text-xs px-4 py-2.5 rounded-xl transition w-full md:w-auto">
            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            Tambah Keluarga
        </a>
        <a href="{{ route('kader.schedules.index') }}" class="inline-flex items-center justify-center gap-1.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-semibold text-xs px-4 py-2.5 rounded-xl transition w-full md:w-auto">
            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Atur Jadwal
        </a>
        <a href="{{ route('kader.reports') }}" class="inline-flex items-center justify-center gap-1.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-semibold text-xs px-4 py-2.5 rounded-xl transition w-full md:w-auto">
            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Unduh Laporan
        </a>
    </div>
</div>

<!-- Real-time Global Search bar -->
<div class="relative mb-6 z-40">
    <div class="bg-white border border-slate-150 rounded-2xl p-4 shadow-sm">
        <label for="global-search-input" class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2">Pencarian Global Instan (Command Search)</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" id="global-search-input" 
                class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all" 
                placeholder="Ketik nama keluarga, nomor KK, nama anggota, NIK, atau nomor telepon...">
        </div>
    </div>
    
    <!-- Floating Search Results Dropdown -->
    <div id="search-results-box" class="absolute left-0 right-0 mt-2 bg-white rounded-2xl border border-slate-150 shadow-xl overflow-hidden hidden max-h-80 overflow-y-auto">
        <div class="p-3 bg-slate-50 border-b border-slate-150 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">
            Hasil Pencarian Posyandu Command Center
        </div>
        <div id="search-results-list" class="divide-y divide-slate-100">
            <!-- Results injected here -->
        </div>
    </div>
</div>

<!-- Ringkasan Hari Ini (Stats Grid) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-xs flex items-center gap-4 transition hover:shadow-md">
        <div class="p-3 bg-slate-50 text-slate-650 rounded-xl shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        </div>
        <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Keluarga</span>
            <span class="text-xl font-black text-slate-800">{{ $totalFamilies }}</span>
        </div>
    </div>
    <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-xs flex items-center gap-4 transition hover:shadow-md">
        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Warga Terdaftar</span>
            <span class="text-xl font-black text-slate-800">{{ $totalBumil + $totalBayi + $totalBalita + $totalLansia }}</span>
        </div>
    </div>
    <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-xs flex items-center gap-4 transition hover:shadow-md">
        <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Kehadiran Hari Ini</span>
            <span class="text-xl font-black text-slate-800" id="today-attendance-count">{{ $todayAttendance }} KK</span>
        </div>
    </div>
    <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-xs flex items-center gap-4 transition hover:shadow-md">
        <div class="p-3 bg-cyan-50 text-cyan-600 rounded-xl shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
        <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Pemeriksaan Hari Ini</span>
            <span class="text-xl font-black text-slate-800" id="today-checks-count">{{ $todayChecks }} Orang</span>
        </div>
    </div>
</div>

<!-- Command Center Grid System -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    <!-- Left: Smart Priority Panel (2/3 width) -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Smart Priority Panel -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-28 h-28 bg-rose-50 rounded-full blur-2xl"></div>
            <div class="flex items-center gap-2.5 mb-6 relative z-10">
                <div class="p-2 bg-rose-50 text-rose-500 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-800">Smart Priority Panel (Prioritas Penanganan)</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Sistem memindai otomatis warga yang terlambat jadwal kontrol</p>
                </div>
            </div>

            <!-- List Priorities -->
            <div class="space-y-4">
                <!-- Ibu Hamil Overdue -->
                @if($prioritasBumil->count() > 0)
                    <div class="bg-rose-50/40 border border-rose-100 rounded-2xl p-4 space-y-3">
                        <span class="text-[9px] font-extrabold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-100 uppercase tracking-wider">Ibu Hamil Terlambat Kontrol (&gt;30 Hari)</span>
                        <div class="divide-y divide-rose-100/50">
                            @foreach($prioritasBumil as $bumil)
                                <div class="py-2 flex items-center justify-between gap-4 first:pt-0 last:pb-0 text-xs">
                                    <div class="min-w-0">
                                        <h5 class="font-extrabold text-slate-800 truncate">{{ $bumil->nama }}</h5>
                                        <p class="text-[10px] text-slate-400 mt-0.5 truncate">KK Bpk: {{ $bumil->pengguna->kepala_keluarga ?? '-' }} &bull; Kontak: {{ $bumil->pengguna->nomor_telepon ?? '-' }}</p>
                                    </div>
                                    <a href="{{ route('kader.families.show', $bumil->pengguna_id) }}" class="bg-white hover:bg-rose-50 text-rose-600 hover:text-rose-700 border border-rose-200 px-3 py-1.5 rounded-xl font-bold transition shrink-0">Kelola</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Anak/Bayi Overdue -->
                @if($prioritasAnak->count() > 0)
                    <div class="bg-amber-50/40 border border-amber-100 rounded-2xl p-4 space-y-3">
                        <span class="text-[9px] font-extrabold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-100 uppercase tracking-wider">Bayi / Balita Lewat Jadwal Timbang (&gt;30 Hari)</span>
                        <div class="divide-y divide-amber-100/50">
                            @foreach($prioritasAnak as $anak)
                                <div class="py-2 flex items-center justify-between gap-4 first:pt-0 last:pb-0 text-xs">
                                    <div class="min-w-0">
                                        <h5 class="font-extrabold text-slate-800 truncate">{{ $anak->nama }} <span class="text-[9px] text-slate-400 bg-slate-200/50 px-2 py-0.2 rounded">({{ $anak->umur }})</span></h5>
                                        <p class="text-[10px] text-slate-400 mt-0.5 truncate">Orang Tua: {{ $anak->pengguna->kepala_keluarga ?? '-' }}</p>
                                    </div>
                                    <a href="{{ route('kader.families.show', $anak->pengguna_id) }}" class="bg-white hover:bg-amber-50 text-amber-600 hover:text-amber-700 border border-amber-200 px-3 py-1.5 rounded-xl font-bold transition shrink-0">Kelola</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Lansia Overdue -->
                @if($prioritasLansia->count() > 0)
                    <div class="bg-indigo-50/40 border border-indigo-100 rounded-2xl p-4 space-y-3">
                        <span class="text-[9px] font-extrabold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100 uppercase tracking-wider">Lansia Terlambat Pemeriksaan Rutin (&gt;60 Hari)</span>
                        <div class="divide-y divide-indigo-100/50">
                            @foreach($prioritasLansia as $lansia)
                                <div class="py-2 flex items-center justify-between gap-4 first:pt-0 last:pb-0 text-xs">
                                    <div class="min-w-0">
                                        <h5 class="font-extrabold text-slate-800 truncate">{{ $lansia->nama }} ({{ $lansia->umur }})</h5>
                                        <p class="text-[10px] text-slate-400 mt-0.5 truncate">Alamat: {{ $lansia->pengguna->alamat ?? '-' }}</p>
                                    </div>
                                    <a href="{{ route('kader.families.show', $lansia->pengguna_id) }}" class="bg-white hover:bg-indigo-50 text-indigo-600 hover:text-indigo-700 border border-indigo-200 px-3 py-1.5 rounded-xl font-bold transition shrink-0">Kelola</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Pasif Families -->
                @if($prioritasKeluargaPasif->count() > 0)
                    <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 space-y-3">
                        <span class="text-[9px] font-extrabold text-slate-500 bg-white px-2 py-0.5 rounded-full border border-slate-200 uppercase tracking-wider">Keluarga Pasif Kegiatan (&gt;90 Hari Belum Hadir)</span>
                        <div class="divide-y divide-slate-200/50">
                            @foreach($prioritasKeluargaPasif as $pasif)
                                <div class="py-2 flex items-center justify-between gap-4 first:pt-0 last:pb-0 text-xs">
                                    <div class="min-w-0">
                                        <h5 class="font-extrabold text-slate-800 truncate">Keluarga Bapak {{ $pasif->kepala_keluarga }}</h5>
                                        <p class="text-[10px] text-slate-400 mt-0.5 truncate">No KK: {{ $pasif->username }} &bull; Kontak: {{ $pasif->nomor_telepon }}</p>
                                    </div>
                                    <a href="{{ route('kader.families.show', $pasif->id) }}" class="bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 px-3 py-1.5 rounded-xl font-bold transition shrink-0">Hubungi</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($prioritasBumil->isEmpty() && $prioritasAnak->isEmpty() && $prioritasLansia->isEmpty() && $prioritasKeluargaPasif->isEmpty())
                    <div class="text-center py-6 text-slate-400 text-xs bg-slate-50 border border-slate-100 rounded-2xl">
                        Seluruh warga terpantau rutin melakukan kontrol kesehatan. Keadaan Posyandu Aman.
                    </div>
                @endif
            </div>
        </div>

        <!-- Live Queue Monitor (COMMAND AREA) -->
        <div id="queue-monitor-section" class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs relative">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2.5">
                    <div class="p-2 bg-amber-50 text-amber-500 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M18.364 5.636a9 9 0 010 12.728M12 18a6 6 0 100-12 6 6 0 000 12z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-800">Live Queue Monitor & Controller</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5">Hub antrean dan pengeras suara pelayanan hari ini</p>
                    </div>
                </div>
                <span class="text-[9px] font-bold text-amber-600 bg-amber-50 border border-amber-100 animate-ping h-2.5 w-2.5 rounded-full self-start"></span>
            </div>

            <!-- Active Called Card -->
            <div id="active-queue-card-wrapper" class="mb-6">
                @if($activeQueue)
                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-6 text-center space-y-4">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Sedang Dilayani</span>
                        <div class="text-6xl font-black text-emerald-600 tracking-tight" id="active-num">
                            {{ $activeQueue->kode_antrean }}
                        </div>
                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-700 text-sm">Keluarga: {{ $activeQueue->pengguna->kepala_keluarga ?? '-' }}</h4>
                            <p class="text-xs text-slate-400">No. KK: {{ $activeQueue->pengguna->username ?? '-' }}</p>
                        </div>
                        <div class="flex flex-col sm:flex-row justify-center gap-2.5 pt-2">
                            <button onclick="speakQueue('{{ $activeQueue->kode_antrean }}', '{{ addslashes($activeQueue->pengguna->kepala_keluarga ?? 'Umum') }}')" 
                                class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold text-xs px-4 py-2.5 rounded-xl transition duration-150 shadow-xs flex items-center justify-center gap-1.5 cursor-pointer w-full sm:w-auto">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M18.364 5.636a9 9 0 110 12.728M12 18a6 6 0 100-12 6 6 0 000 12z"></path></svg>
                                Panggil Suara
                            </button>
                            
                            <form action="{{ route('kader.queue.skip', $activeQueue->id) }}" method="POST" class="w-full sm:w-auto">
                                @csrf
                                <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 font-semibold text-xs px-4 py-2.5 rounded-xl transition border border-rose-200 cursor-pointer w-full">
                                    Dilewati
                                </button>
                            </form>

                            <form action="{{ route('kader.queue.complete', $activeQueue->id) }}" method="POST" class="w-full sm:w-auto">
                                @csrf
                                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs px-4 py-2.5 rounded-xl transition shadow-xs cursor-pointer w-full">
                                    Selesai Layani
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="text-center py-10 bg-slate-50 border border-slate-100 rounded-2xl">
                        <div class="inline-flex bg-slate-100 p-3 rounded-full text-slate-400 mb-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <h4 class="font-bold text-slate-600 text-sm">Tidak ada antrean yang sedang dilayani</h4>
                        <p class="text-xs text-slate-400 mt-1">Gunakan tombol "Panggil" pada daftar tunggu antrean di bawah untuk memulai.</p>
                    </div>
                @endif
            </div>

            <!-- Wait time estimations & Next ticket -->
            <div class="grid grid-cols-2 gap-4 mb-6 text-xs">
                <div class="bg-slate-50 p-4 border border-slate-150 rounded-2xl">
                    <span class="text-slate-400 font-semibold uppercase tracking-wider block">Estimasi Waktu Tunggu</span>
                    <span class="text-slate-700 font-extrabold text-sm block mt-1" id="wait-time-est">
                        {{ $todayQueues->where('status', 'menunggu')->count() * 10 }} Menit
                    </span>
                </div>
                <div class="bg-slate-50 p-4 border border-slate-150 rounded-2xl">
                    <span class="text-slate-400 font-semibold uppercase tracking-wider block">Nomor Berikutnya</span>
                    <span class="text-slate-700 font-extrabold text-sm block mt-1" id="next-ticket-code">
                        {{ $todayQueues->where('status', 'menunggu')->first() ? $todayQueues->where('status', 'menunggu')->first()->kode_antrean : '-' }}
                    </span>
                </div>
            </div>

            <!-- Table Wait Queue -->
            <div class="space-y-4">
                <h4 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                    <span>Daftar Tunggu Antrean Hari Ini</span>
                    <span id="waiting-count-badge" class="text-xs font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">
                        {{ $todayQueues->where('status', 'menunggu')->count() }} Menunggu
                    </span>
                </h4>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-[11px]">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 font-semibold">
                                <th class="pb-2.5">No. Antrean</th>
                                <th class="pb-2.5">Kepala Keluarga</th>
                                <th class="pb-2.5">Jam Daftar</th>
                                <th class="pb-2.5">Status</th>
                                <th class="pb-2.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="queue-table-body" class="divide-y divide-slate-100">
                            @forelse($todayQueues as $q)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-3 font-extrabold text-slate-800 whitespace-nowrap">{{ $q->kode_antrean }}</td>
                                    <td class="py-3 font-medium whitespace-nowrap">
                                        <a href="{{ route('kader.families.show', $q->pengguna_id) }}" class="hover:text-emerald-600 font-semibold text-slate-700">
                                            {{ $q->pengguna->kepala_keluarga ?? '-' }}
                                        </a>
                                    </td>
                                    <td class="py-3 text-slate-500 whitespace-nowrap">{{ $q->created_at->format('H:i') }} WIB</td>
                                    <td class="py-3 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $q->badge_class }}">
                                            {{ $q->status_label }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right whitespace-nowrap">
                                        @if($q->status === 'menunggu')
                                            <form action="{{ route('kader.queue.call', $q->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" onclick="speakQueue('{{ $q->kode_antrean }}', '{{ addslashes($q->pengguna->kepala_keluarga ?? 'Umum') }}')" 
                                                    class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold text-[10px] px-2.5 py-1 rounded-lg transition shadow-xs cursor-pointer">
                                                    Panggil
                                                </button>
                                            </form>
                                        @elseif($q->status === 'dilayani')
                                            <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg animate-pulse border border-emerald-100">Sedang Dilayani</span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-400 text-xs">Belum ada antrean masuk hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    
    <div class="space-y-6">

        
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs">
            <h3 class="text-base font-extrabold text-slate-800 mb-4 flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Verifikasi Anggota Baru
                </span>
                @if($pendingVerifications->count() > 0)
                    <span class="bg-amber-100 text-amber-800 text-[10px] font-extrabold px-2 py-0.5 rounded-full uppercase shrink-0">
                        {{ $pendingVerifications->count() }} Baru
                    </span>
                @endif
            </h3>
            
            <div class="space-y-3">
                @forelse($pendingVerifications as $pMember)
                    <div class="p-3 bg-slate-50 border border-slate-150 rounded-2xl flex flex-col gap-2.5">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <h4 class="font-extrabold text-slate-800 text-xs truncate">{{ $pMember->nama }}</h4>
                                <p class="text-[9px] text-slate-400 mt-0.5 font-semibold">KK: Bpk. {{ $pMember->pengguna->kepala_keluarga ?? '-' }}</p>
                                <span class="inline-block text-[8px] font-black text-slate-500 bg-slate-200/60 px-1.5 py-0.5 rounded-md mt-1 uppercase">
                                    {{ $pMember->kategori_formatted }}
                                </span>
                            </div>
                            <span class="text-[9px] text-slate-400 whitespace-nowrap">{{ $pMember->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2 border-t border-slate-100/50 pt-2">
                            <form action="{{ route('kader.members.verify', [$pMember->id, 'disetujui']) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-[10px] py-1.5 rounded-xl transition cursor-pointer">
                                    Setujui
                                </button>
                            </form>
                            <form action="{{ route('kader.members.verify', [$pMember->id, 'ditolak']) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menolak pendaftaran ini?')" class="w-full bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-[10px] py-1.5 rounded-xl border border-rose-100 transition cursor-pointer">
                                    Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 border border-dashed border-slate-200 rounded-2xl">
                        <div class="inline-flex bg-emerald-50 text-emerald-500 p-2.5 rounded-full mb-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h5 class="font-bold text-slate-700 text-[11px]">Semua data terverifikasi</h5>
                        <p class="text-[9px] text-slate-450 mt-0.5">Tidak ada permintaan verifikasi anggota baru.</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Grafik Pelayanan -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs">
            <h3 class="text-base font-extrabold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Grafik Pelayanan (6 Bulan)
            </h3>
            <div class="h-64 w-full">
                <canvas id="visitationTrendChart"></canvas>
            </div>
        </div>

        <!-- Jadwal Kegiatan Posyandu & Mini Calendar -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs space-y-6">
            <div class="flex items-center gap-2.5 border-b border-slate-100 pb-4">
                <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-800">Kalender Posyandu</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Jadwal imunisasi dan pos pelayanan desa</p>
                </div>
            </div>

            <!-- Mini Calendar Visual -->
            <div class="bg-slate-50 border border-slate-150 rounded-2xl p-4 text-center">
                <span class="text-xs font-black text-slate-700 block mb-3 uppercase tracking-wider">{{ now()->translatedFormat('F Y') }}</span>
                <div class="mini-calendar-grid text-[9px] font-extrabold text-slate-400 mb-2">
                    <span class="calendar-day-cell text-slate-400">SE</span>
                    <span class="calendar-day-cell text-slate-400">SE</span>
                    <span class="calendar-day-cell text-slate-400">RA</span>
                    <span class="calendar-day-cell text-slate-400">KA</span>
                    <span class="calendar-day-cell text-slate-400">JU</span>
                    <span class="calendar-day-cell text-slate-400">SA</span>
                    <span class="calendar-day-cell text-slate-400">MI</span>
                </div>
                <div class="mini-calendar-grid text-[10px] font-bold text-slate-650">
                    <!-- Basic calendar offset rendering -->
                    @php
                        $startOfMonth = now()->startOfMonth();
                        $daysInMonth = now()->daysInMonth;
                        $dayOfWeek = ($startOfMonth->dayOfWeekIso - 1) % 7; // ISO day offset
                    @endphp
                    
                    @for($i = 0; $i < $dayOfWeek; $i++)
                        <span class="calendar-day-cell text-transparent">-</span>
                    @endfor
                    
                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $isToday = $day == now()->day;
                            $hasEvent = $schedules->contains(fn($s) => $s->tanggal_kegiatan->day == $day);
                        @endphp
                        <span class="calendar-day-cell transition-all 
                            {{ $isToday ? 'bg-emerald-500 text-white font-extrabold shadow-sm' : '' }} 
                            {{ $hasEvent && !$isToday ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : '' }}">
                            {{ $day }}
                        </span>
                    @endfor
                </div>
            </div>

            <!-- Schedules Details List -->
            <div class="space-y-4">
                <h4 class="font-extrabold text-slate-800 text-xs flex items-center justify-between">
                    <span>Detail Agenda Terdekat</span>
                    <a href="{{ route('kader.schedules.index') }}" class="text-[10px] font-bold text-emerald-600 hover:underline">Kelola &rarr;</a>
                </h4>
                <div class="space-y-3">
                    @forelse($schedules as $sch)
                        <div onclick="openKaderScheduleModal({{ $sch->id }})" class="flex items-start bg-slate-50/70 hover:bg-emerald-50/40 p-3 rounded-2xl border border-slate-150 hover:border-emerald-300 transition duration-200 cursor-pointer group">
                            <div class="bg-emerald-500 text-white font-black rounded-xl flex flex-col items-center justify-center shrink-0 shadow-2xs" style="min-width: 44px; width: 44px; height: 44px; flex-shrink: 0; margin-right: 16px;">
                                <span class="text-xs font-black leading-none" style="line-height: 1;">{{ $sch->tanggal_kegiatan->format('d') }}</span>
                                <span class="text-[8px] uppercase tracking-wider font-extrabold mt-1 leading-none" style="line-height: 1;">{{ $sch->tanggal_kegiatan->format('M') }}</span>
                            </div>
                            <div class="min-w-0 flex-1 space-y-0.5">
                                <h5 class="font-extrabold text-slate-800 text-xs leading-snug group-hover:text-emerald-700 transition">{{ $sch->judul }}</h5>
                                <p class="text-[10px] text-slate-500 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span class="line-clamp-1">{{ $sch->tempat }}</span>
                                </p>
                                <p class="text-[10px] text-emerald-650 font-bold flex items-center gap-1">
                                    <svg class="w-3 h-3 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>{{ substr($sch->jam_mulai, 0, 5) }} - {{ substr($sch->jam_selesai, 0, 5) }} WIB</span>
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-xs text-slate-400">Belum ada agenda terdekat.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Row 2: Recent Logs & Announcements -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Recent Logs (2/3 width) -->
    <div class="lg:col-span-2 bg-white border border-slate-100 rounded-3xl p-6 shadow-xs">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-base font-extrabold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Log Aktivitas Posyandu Command Center
            </h3>
            <a href="{{ route('kader.audit-logs') }}" class="text-xs font-semibold text-emerald-600 hover:underline">Lihat Semua Log</a>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($recentChecks as $check)
                <div class="py-3.5 first:pt-0 last:pb-0 flex items-start justify-between gap-4">
                    <div class="space-y-0.5">
                        <span class="text-[10px] text-slate-400 font-bold block">{{ $check->tanggal_periksa->format('d M Y') }}</span>
                        <span class="font-bold text-slate-700 text-xs flex items-center gap-2">
                            {{ $check->anggotaKeluarga->nama }}
                            <span class="text-[9px] font-semibold text-emerald-600 bg-emerald-50 px-1.5 py-0.2 rounded-md">
                                {{ $check->anggotaKeluarga->kategori_formatted }}
                            </span>
                        </span>
                        <p class="text-[11px] text-slate-500 line-clamp-1 leading-relaxed">
                            BB {{ $check->berat_badan }} kg
                            @if($check->tinggi_badan), TB {{ $check->tinggi_badan }} cm @endif
                            @if($check->tekanan_darah), TD {{ $check->tekanan_darah }} @endif
                            @if($check->keluhan). Keluhan: {{ $check->keluhan }} @endif
                        </p>
                    </div>
                    <span class="text-[9px] font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full shrink-0 border border-slate-200">
                        KK: {{ $check->anggotaKeluarga->pengguna->username }}
                    </span>
                </div>
            @empty
                <div class="text-center py-6 text-slate-400 text-xs">Belum ada aktivitas medis terekam.</div>
            @endforelse
        </div>
    </div>

    <!-- Announcements -->
    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-extrabold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M18.364 5.636a9 9 0 010 12.728M12 18a6 6 0 100-12 6 6 0 000 12z"></path></svg>
                Papan Pengumuman
            </h3>
            <a href="{{ route('kader.announcements.index') }}" class="text-xs font-semibold text-emerald-600 hover:underline">Kelola</a>
        </div>
        
        <div class="space-y-3.5">
            @php
                $announcements = App\Models\Pengumuman::orderBy('tanggal_terbit', 'desc')->take(4)->get();
            @endphp
            @forelse($announcements as $ann)
                <div onclick="openKaderAnnouncementModal({{ $ann->id }})" class="p-3 bg-slate-50/70 hover:bg-emerald-50/40 rounded-2xl border border-slate-150 hover:border-emerald-300 transition duration-200 cursor-pointer group text-xs">
                    <div class="flex items-center justify-between gap-2 mb-1">
                        <span class="text-[9.5px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">
                            {{ $ann->tanggal_terbit ? $ann->tanggal_terbit->format('d M Y') : $ann->created_at->format('d M Y') }}
                        </span>
                        <span class="text-[9.5px] font-bold text-slate-400 group-hover:text-emerald-600">Detail &rarr;</span>
                    </div>
                    <h4 class="font-extrabold text-slate-800 leading-snug group-hover:text-emerald-700 transition">
                        {{ $ann->judul }}
                    </h4>
                    <p class="text-[10.5px] text-slate-500 mt-1 leading-relaxed line-clamp-2">
                        {{ $ann->konten }}
                    </p>
                </div>
            @empty
                <div class="text-center py-4 text-xs text-slate-400">Belum ada pengumuman.</div>
            @endforelse
        </div>
    </div>

<!-- Kader Schedule Details Modal Layer -->
<div id="kaderScheduleModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-3xl w-full max-w-md max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-100 p-6 animate-scaleUp space-y-4">
        <!-- Top Bar -->
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100">
                Detail Agenda Posyandu
            </span>
            <button onclick="closeKaderScheduleModal()" class="text-slate-400 hover:text-slate-700 hover:bg-slate-100 p-1.5 rounded-full transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div>
            <h3 class="text-base sm:text-lg font-black text-slate-900 leading-snug" id="kader-modal-schedule-title">Judul Agenda</h3>
        </div>

        <div class="bg-slate-50 p-4 rounded-2xl space-y-3 border border-slate-100/80 text-xs">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-white text-emerald-600 rounded-xl shadow-2xs shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <span class="text-[9px] uppercase font-bold text-slate-400 block tracking-wider">Tanggal Kegiatan</span>
                    <span class="font-extrabold text-slate-800 block" id="kader-modal-schedule-date">-</span>
                </div>
            </div>
            <div class="flex items-center gap-3 border-t border-slate-200/60 pt-2.5">
                <div class="p-2 bg-white text-emerald-600 rounded-xl shadow-2xs shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <span class="text-[9px] uppercase font-bold text-slate-400 block tracking-wider">Waktu Pelaksanaan</span>
                    <span class="font-extrabold text-slate-800 block" id="kader-modal-schedule-time">-</span>
                </div>
            </div>
            <div class="flex items-center gap-3 border-t border-slate-200/60 pt-2.5">
                <div class="p-2 bg-white text-emerald-600 rounded-xl shadow-2xs shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <div>
                    <span class="text-[9px] uppercase font-bold text-slate-400 block tracking-wider">Lokasi Pelayanan</span>
                    <span class="font-extrabold text-slate-800 block" id="kader-modal-schedule-location">-</span>
                </div>
            </div>
        </div>

        <div class="space-y-1.5">
            <span class="text-[10px] uppercase font-extrabold text-slate-400 tracking-wider block">Deskripsi & Catatan Petugas</span>
            <div class="bg-emerald-50/40 border-l-3 border-emerald-500 p-3.5 rounded-r-2xl text-xs text-slate-700 leading-relaxed whitespace-pre-line max-h-40 overflow-y-auto" id="kader-modal-schedule-description">
                -
            </div>
        </div>

        <div class="flex items-center gap-2 pt-1">
            <a href="{{ route('kader.schedules.index') }}" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-extrabold transition text-center shadow-xs">
                Kelola Jadwal Ini
            </a>
            <button onclick="closeKaderScheduleModal()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Kader Announcement Details Modal Layer -->
<div id="kaderAnnouncementModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-3xl w-full max-w-md max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-100 p-6 animate-scaleUp space-y-4">
        <!-- Top Bar -->
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-teal-700 bg-teal-50 px-2.5 py-1 rounded-full border border-teal-100">
                Pengumuman Posyandu
            </span>
            <button onclick="closeKaderAnnouncementModal()" class="text-slate-400 hover:text-slate-700 hover:bg-slate-100 p-1.5 rounded-full transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div>
            <h3 class="text-base sm:text-lg font-black text-slate-900 leading-snug" id="kader-modal-announcement-title">Judul Pengumuman</h3>
        </div>

        <div class="flex items-center justify-between gap-2 text-xs text-slate-500 bg-slate-50 px-3.5 py-2 rounded-xl border border-slate-100/80">
            <span class="font-medium flex items-center gap-1" id="kader-modal-announcement-date">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Diterbitkan: -
            </span>
            <span class="font-bold text-emerald-700 flex items-center gap-1" id="kader-modal-announcement-author">
                Oleh: Kader
            </span>
        </div>

        <div class="space-y-1.5">
            <span class="text-[10px] uppercase font-extrabold text-slate-400 tracking-wider block">Isi Pengumuman Lengkap</span>
            <div class="bg-slate-50 border border-slate-100/80 p-3.5 rounded-2xl text-xs text-slate-700 leading-relaxed whitespace-pre-line max-h-52 overflow-y-auto" id="kader-modal-announcement-content">
                -
            </div>
        </div>

        <div class="flex items-center gap-2 pt-1">
            <a href="{{ route('kader.announcements.index') }}" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-extrabold transition text-center shadow-xs">
                Kelola Pengumuman
            </a>
            <button onclick="closeKaderAnnouncementModal()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // 1. Dynamic Greetings & Live Clock widget
    function updateGreetingsClock() {
        const greetingsEl = document.getElementById('hero-greetings');
        const clockEl = document.getElementById('live-clock');
        const now = new Date();
        
        // Hour-based greeting
        const hour = now.getHours();
        let greeting = "Selamat Malam";
        if (hour >= 5 && hour < 11) greeting = "Selamat Pagi";
        else if (hour >= 11 && hour < 15) greeting = "Selamat Siang";
        else if (hour >= 15 && hour < 18) greeting = "Selamat Sore";

        if (greetingsEl) {
            greetingsEl.innerText = greeting + ", " + {!! json_encode(auth()->user()->name) !!} + "!";
        }

        // Format date and time
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        const dayName = days[now.getDay()];
        const day = now.getDate();
        const monthName = months[now.getMonth()];
        const year = now.getFullYear();
        const time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
        
        if (clockEl) {
            clockEl.innerText = `${dayName}, ${day} ${monthName} ${year} - ${time}`;
        }
    }
    setInterval(updateGreetingsClock, 1000);
    updateGreetingsClock();

    // 2. Real-time Global Search Logic
    const searchInput = document.getElementById('global-search-input');
    const resultsBox = document.getElementById('search-results-box');
    const resultsList = document.getElementById('search-results-list');

    searchInput.addEventListener('input', function () {
        const query = searchInput.value.trim();
        if (query.length === 0) {
            resultsBox.classList.add('hidden');
            return;
        }

        fetch(`/kader/global-search?search=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                resultsList.innerHTML = '';
                if (data.length === 0) {
                    resultsList.innerHTML = `<div class="p-4 text-center text-slate-400 text-xs">Pencarian tidak menemukan hasil.</div>`;
                } else {
                    data.forEach(item => {
                        resultsList.innerHTML += `
                            <a href="${item.url}" class="block p-3.5 hover:bg-slate-50 transition-colors">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <h4 class="font-extrabold text-slate-800 text-xs">${item.title}</h4>
                                        <p class="text-[10px] text-slate-500 mt-0.5">${item.subtitle}</p>
                                    </div>
                                    <span class="text-[9px] font-extrabold text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-full shrink-0">
                                        ${item.type}
                                    </span>
                                </div>
                            </a>
                        `;
                    });
                }
                resultsBox.classList.remove('hidden');
            })
            .catch(err => console.error("Error global searching:", err));
    });

    // Close search box on click outside
    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
            resultsBox.classList.add('hidden');
        }
    });

    function focusSearch() {
        searchInput.focus();
    }

    // 3. Speech Synthesis Queue Announcer
    function speakQueue(code, name) {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
            let codeClean = code.replace('-', ' ');
            let speakText = `Nomor antrean ${codeClean}. Keluarga Bapak ${name}. Silakan menuju ruang pelayanan.`;
            
            let utterance = new SpeechSynthesisUtterance(speakText);
            utterance.lang = 'id-ID';
            utterance.rate = 0.9;
            
            let voices = window.speechSynthesis.getVoices();
            let idVoice = voices.find(voice => voice.lang.includes('id') || voice.lang.includes('ID'));
            if (idVoice) utterance.voice = idVoice;
            
            window.speechSynthesis.speak(utterance);
        } else {
            console.warn("Web Speech API is not supported on this browser.");
        }
    }

    // 4. Live Queue Polling Logic (with tab-inactive optimization)
    function pollKaderQueues() {
        if (document.hidden) return; // Skip fetch if tab is hidden / in background

        fetch('{{ route("kader.queue.poll-data") }}')
            .then(res => res.json())
            .then(data => {
                // 4.1 Update Active Queue Card
                const wrapper = document.getElementById('active-queue-card-wrapper');
                if (wrapper) {
                    if (data.active_queue) {
                        wrapper.innerHTML = `
                            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-6 text-center space-y-4">
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Sedang Dilayani</span>
                                <div class="text-6xl font-black text-emerald-600 tracking-tight" id="active-num">
                                    ${data.active_queue.kode_antrean}
                                </div>
                                <div class="space-y-1">
                                    <h4 class="font-bold text-slate-700 text-sm">Keluarga: ${data.active_queue.kepala_keluarga}</h4>
                                    <p class="text-xs text-slate-400">No. KK: ${data.active_queue.username}</p>
                                </div>
                                <div class="flex flex-col sm:flex-row justify-center gap-2.5 pt-2">
                                    <button onclick="speakQueue('${data.active_queue.kode_antrean}', '${data.active_queue.kepala_keluarga}')" 
                                        class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold text-xs px-4 py-2.5 rounded-xl transition duration-150 shadow-xs flex items-center justify-center gap-1.5 cursor-pointer w-full sm:w-auto">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M18.364 5.636a9 9 0 010 12.728M12 18a6 6 0 100-12 6 6 0 000 12z"></path></svg>
                                        Panggil Suara
                                    </button>
                                    
                                    <form action="/kader/queue/${data.active_queue.id}/skip" method="POST" class="w-full sm:w-auto">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 font-semibold text-xs px-4 py-2.5 rounded-xl transition border border-rose-200 cursor-pointer w-full">
                                            Dilewati
                                        </button>
                                    </form>

                                    <form action="/kader/queue/${data.active_queue.id}/complete" method="POST" class="w-full sm:w-auto">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs px-4 py-2.5 rounded-xl transition shadow-xs cursor-pointer w-full">
                                            Selesai Layani
                                        </button>
                                    </form>
                                </div>
                            </div>
                        `;
                    } else {
                        wrapper.innerHTML = `
                            <div class="text-center py-10 bg-slate-50 border border-slate-100 rounded-2xl">
                                <div class="inline-flex bg-slate-100 p-3 rounded-full text-slate-400 mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <h4 class="font-bold text-slate-600 text-sm">Tidak ada antrean yang sedang dilayani</h4>
                                <p class="text-xs text-slate-400 mt-1">Gunakan tombol "Panggil" pada daftar tunggu antrean di bawah untuk memulai.</p>
                            </div>
                        `;
                    }
                }

                // 4.2 Update Table Waiting Rows
                const tbody = document.getElementById('queue-table-body');
                if (tbody) {
                    tbody.innerHTML = '';
                    if (data.queues.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="5" class="py-6 text-center text-slate-400 text-xs">Belum ada antrean masuk hari ini.</td></tr>`;
                    } else {
                        data.queues.forEach(q => {
                            let actionHtml = '';
                            if (q.status === 'menunggu') {
                                actionHtml = `
                                    <form action="/kader/queue/${q.id}/call" method="POST" class="inline-block">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" onclick="speakQueue('${q.kode_antrean}', '${q.kepala_keluarga}')" 
                                            class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold text-[10px] px-2.5 py-1 rounded-lg transition shadow-xs cursor-pointer">
                                            Panggil
                                        </button>
                                    </form>
                                `;
                            } else if (q.status === 'dilayani') {
                                actionHtml = `<span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg animate-pulse border border-emerald-100">Sedang Dilayani</span>`;
                            } else {
                                actionHtml = `<span class="text-slate-400">-</span>`;
                            }

                             tbody.innerHTML += `
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-3 font-extrabold text-slate-800 whitespace-nowrap">${q.kode_antrean}</td>
                                    <td class="py-3 font-medium whitespace-nowrap">
                                        <a href="/kader/families/${q.pengguna_id}" class="hover:text-emerald-600 font-semibold text-slate-700">
                                            ${q.kepala_keluarga}
                                        </a>
                                    </td>
                                    <td class="py-3 text-slate-500 whitespace-nowrap">${q.jam_daftar} WIB</td>
                                    <td class="py-3 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border ${q.badge_class}">
                                            ${q.status_label}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right whitespace-nowrap">${actionHtml}</td>
                                </tr>
                            `;
                        });
                    }
                }

                // 4.3 Update Waiting Stats
                const waitingCount = data.queues.filter(q => q.status === 'menunggu').length;
                
                const badge = document.getElementById('waiting-count-badge');
                if (badge) badge.innerText = waitingCount + ' Menunggu';
                
                const waitEst = document.getElementById('wait-time-est');
                if (waitEst) waitEst.innerText = (waitingCount * 10) + ' Menit';

                const nextTicket = document.getElementById('next-ticket-code');
                if (nextTicket) {
                    const firstWaiting = data.queues.find(q => q.status === 'menunggu');
                    nextTicket.innerText = firstWaiting ? firstWaiting.kode_antrean : '-';
                }

                const liveAttendance = document.getElementById('today-attendance-count');
                if (liveAttendance) liveAttendance.innerText = data.queues.length + ' KK';
            })
            .catch(err => console.error("Error polling command queues:", err));
    }
    setInterval(pollKaderQueues, 3000);

    // Immediately fetch fresh queue data when Kader switches back to this tab
    document.addEventListener("visibilitychange", function() {
        if (!document.hidden) {
            pollKaderQueues();
        }
    });

    // 5. Chart.js Monthly Visitation Trend Chart Setup
    document.addEventListener("DOMContentLoaded", function () {
        const trendCanvas = document.getElementById('visitationTrendChart');
        if (trendCanvas) {
            const trendCtx = trendCanvas.getContext('2d');
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthlyLabels) !!},
                    datasets: [{
                        label: 'Jumlah Kunjungan / Rekap Medis',
                        data: {!! json_encode($monthlyVisits) !!},
                        borderColor: '#0d9488', // teal-600
                        backgroundColor: 'rgba(13, 148, 136, 0.08)',
                        borderWidth: 3.5,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#0d9488',
                        pointHoverRadius: 7,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            padding: 10,
                            cornerRadius: 12,
                            titleFont: { family: 'Plus Jakarta Sans', weight: 'bold' },
                            bodyFont: { family: 'Plus Jakarta Sans' }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Plus Jakarta Sans', size: 9, weight: 'bold' } }
                        },
                        y: {
                            ticks: { stepSize: 1, font: { family: 'Plus Jakarta Sans', size: 9 } }
                        }
                    }
                }
            });
        }
    });

    // 6. Schedule & Announcement Detail Modals for Kader
    const kaderSchedulesList = {!! json_encode($schedules) !!};
    const kaderAnnouncementsList = {!! json_encode($announcements) !!};

    const kaderScheduleModal = document.getElementById('kaderScheduleModal');
    function openKaderScheduleModal(schId) {
        const sch = kaderSchedulesList.find(s => s.id === schId);
        if (!sch) return;

        document.getElementById('kader-modal-schedule-title').innerText = sch.judul;
        
        let formattedDate = sch.tanggal_kegiatan;
        try {
            const d = new Date(sch.tanggal_kegiatan);
            formattedDate = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        } catch(e) {}
        
        document.getElementById('kader-modal-schedule-date').innerText = formattedDate;
        
        const startTime = sch.jam_mulai ? sch.jam_mulai.substring(0, 5) : '08:00';
        const endTime = sch.jam_selesai ? sch.jam_selesai.substring(0, 5) : '12:00';
        document.getElementById('kader-modal-schedule-time').innerText = startTime + ' - ' + endTime + ' WIB';
        
        document.getElementById('kader-modal-schedule-location').innerText = sch.tempat;
        document.getElementById('kader-modal-schedule-description').innerText = sch.deskripsi ? sch.deskripsi : 'Tidak ada catatan tambahan untuk kegiatan ini.';

        if (kaderScheduleModal) kaderScheduleModal.classList.remove('hidden');
    }
    function closeKaderScheduleModal() {
        if (kaderScheduleModal) kaderScheduleModal.classList.add('hidden');
    }

    const kaderAnnouncementModal = document.getElementById('kaderAnnouncementModal');
    function openKaderAnnouncementModal(annId) {
        const ann = kaderAnnouncementsList.find(a => a.id === annId);
        if (!ann) return;

        document.getElementById('kader-modal-announcement-title').innerText = ann.judul;
        
        let pubDate = ann.tanggal_terbit || ann.created_at;
        try {
            const d = new Date(pubDate);
            pubDate = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        } catch(e) {}
        
        document.getElementById('kader-modal-announcement-date').innerText = 'Diterbitkan: ' + pubDate;
        document.getElementById('kader-modal-announcement-author').innerText = ann.pembuat ? 'Oleh: ' + ann.pembuat.name : 'Oleh: Kader Posyandu';
        document.getElementById('kader-modal-announcement-content').innerText = ann.konten;

        if (kaderAnnouncementModal) kaderAnnouncementModal.classList.remove('hidden');
    }
    function closeKaderAnnouncementModal() {
        if (kaderAnnouncementModal) kaderAnnouncementModal.classList.add('hidden');
    }

    window.addEventListener('click', function (e) {
        if (e.target === kaderScheduleModal) closeKaderScheduleModal();
        if (e.target === kaderAnnouncementModal) closeKaderAnnouncementModal();
    });
</script>
@endsection
