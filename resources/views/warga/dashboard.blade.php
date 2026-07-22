@extends('layouts.app')

@section('title', 'Family Health Home')

@section('content')
<!-- CSS for dynamic layout items -->
<style>
    .avatar-wrapper {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        flex-shrink: 0;
    }
    .timeline-badge {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        flex-shrink: 0;
    }
</style>

<!-- Hero Section -->
<div class="bg-gradient-to-tr from-emerald-500 via-teal-600 to-emerald-700 text-white rounded-3xl p-6 md:p-8 shadow-lg relative overflow-hidden mb-6">
    <div class="absolute right-0 bottom-0 opacity-10 translate-x-8 translate-y-8">
        <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z"></path>
            <path d="M19 19c0 1.1-.9 2-2 2H7c-1.1 0-2-.9-2-2v-5.09L12 18l7-4.09V19z"></path>
        </svg>
    </div>
    
    <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
        <div class="md:col-span-2 space-y-3.5">
            <span class="bg-white/20 text-white px-3.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider backdrop-blur-xs w-fit block">
                Rumah Sehat Keluarga &bull; Family Health Home
            </span>
            <h2 class="text-3xl font-black tracking-tight">Selamat Datang, Bapak {{ $user->kepala_keluarga }}!</h2>
            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-emerald-100 font-medium">
                <span>Nama Keluarga: Bpk. {{ $user->kepala_keluarga }}</span>
                <span class="hidden sm:inline">&bull;</span>
                <span>No. KK: {{ $user->username }}</span>
                <span class="hidden sm:inline">&bull;</span>
                <span>{{ $members->count() }} Anggota Keluarga</span>
            </div>
            
            <!-- Posyandu Open/Closed status today -->
            @php
                $hasScheduleToday = $schedules->contains(fn($s) => $s->tanggal_kegiatan->isToday());
            @endphp
            <div class="flex items-center gap-2 text-xs w-fit">
                @if($hasScheduleToday)
                    <span class="bg-emerald-400/25 border border-emerald-350 text-white px-3 py-1.5 rounded-xl font-bold flex items-center gap-1.5 animate-pulse">
                        <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                        Posyandu Buka Hari Ini!
                    </span>
                @else
                    @php
                        $nextEvent = $schedules->first();
                    @endphp
                    <span class="bg-white/10 text-emerald-100 px-3 py-1.5 rounded-xl border border-white/10 font-semibold">
                        Posyandu Berikutnya: {{ $nextEvent ? $nextEvent->tanggal_kegiatan->format('d M Y') : 'Belum Terjadwal' }}
                    </span>
                @endif
            </div>
        </div>
        
        <!-- Today date widget -->
        <div class="bg-white/10 border border-white/15 p-5 rounded-2xl backdrop-blur-xs text-center md:text-left">
            <span class="text-[9px] uppercase font-bold tracking-wider text-emerald-200">Tanggal Hari Ini</span>
            <h4 class="text-xl font-black mt-1">{{ now()->translatedFormat('d F Y') }}</h4>
            <span id="live-time" class="text-xs text-emerald-100/90 font-medium">Memuat waktu...</span>
        </div>
    </div>
</div>

<!-- Large Quick Actions -->
<div class="bg-white border border-slate-100 rounded-3xl p-5 shadow-xs mb-6">
    <span class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-3">Menu Layanan Cepat</span>
    <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
        <a href="#queue-section" class="flex flex-col items-center justify-center p-3.5 bg-slate-50 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 rounded-2xl border border-slate-100 hover:border-emerald-200 transition duration-200 text-center space-y-2 group">
            <div class="p-2.5 bg-emerald-50 group-hover:bg-emerald-500 group-hover:text-white rounded-xl text-emerald-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
            </div>
            <span class="text-[10px] font-extrabold uppercase tracking-wide">Ambil Antrean</span>
        </a>
        <a href="#schedules-section" class="flex flex-col items-center justify-center p-3.5 bg-slate-50 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 rounded-2xl border border-slate-100 hover:border-emerald-200 transition duration-200 text-center space-y-2 group">
            <div class="p-2.5 bg-emerald-50 group-hover:bg-emerald-500 group-hover:text-white rounded-xl text-emerald-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <span class="text-[10px] font-extrabold uppercase tracking-wide">Jadwal Posyandu</span>
        </a>
        <a href="#members-section" class="flex flex-col items-center justify-center p-3.5 bg-slate-50 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 rounded-2xl border border-slate-100 hover:border-emerald-200 transition duration-200 text-center space-y-2 group">
            <div class="p-2.5 bg-emerald-50 group-hover:bg-emerald-500 group-hover:text-white rounded-xl text-emerald-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <span class="text-[10px] font-extrabold uppercase tracking-wide">Anggota Keluarga</span>
        </a>
        <a href="#timeline-section" class="flex flex-col items-center justify-center p-3.5 bg-slate-50 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 rounded-2xl border border-slate-100 hover:border-emerald-200 transition duration-200 text-center space-y-2 group">
            <div class="p-2.5 bg-emerald-50 group-hover:bg-emerald-500 group-hover:text-white rounded-xl text-emerald-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
            <span class="text-[10px] font-extrabold uppercase tracking-wide">Riwayat Medis</span>
        </a>
        <a href="#announcements-section" class="flex flex-col items-center justify-center p-3.5 bg-slate-50 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 rounded-2xl border border-slate-100 hover:border-emerald-200 transition duration-200 text-center space-y-2 group">
            <div class="p-2.5 bg-emerald-50 group-hover:bg-emerald-500 group-hover:text-white rounded-xl text-emerald-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M18.364 5.636a9 9 0 010 12.728M12 18a6 6 0 100-12 6 6 0 000 12z"></path></svg>
            </div>
            <span class="text-[10px] font-extrabold uppercase tracking-wide">Pengumuman</span>
        </a>
        <button onclick="openKaderModal()" class="flex flex-col items-center justify-center p-3.5 bg-slate-50 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 rounded-2xl border border-slate-100 hover:border-emerald-200 transition duration-200 text-center space-y-2 group cursor-pointer">
            <div class="p-2.5 bg-emerald-50 group-hover:bg-emerald-500 group-hover:text-white rounded-xl text-emerald-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            </div>
            <span class="text-[10px] font-extrabold uppercase tracking-wide">Hubungi Kader</span>
        </button>
    </div>
</div>

<!-- Digital Queue Section (Primary Focus) -->
<div id="queue-section" class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs relative overflow-hidden mb-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2.5">
            <div class="p-2 bg-amber-50 text-amber-500 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-extrabold text-slate-800">Antrean Posyandu Hari Ini</h3>
                <p class="text-[10px] text-slate-400 mt-0.5">Ambil dan pantau nomor antrean pelayanan Anda</p>
            </div>
        </div>
        <span class="text-xs text-slate-400 bg-slate-50 px-3 py-1 rounded-full border border-slate-100">
            {{ date('d M Y') }}
        </span>
    </div>

    <!-- Active Ticket Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
        <!-- Left Column: User ticket status -->
        <div id="your-ticket-section" class="bg-gradient-to-tr from-slate-50 to-slate-100/50 border border-slate-200/60 rounded-2xl p-6 text-center shadow-xs">
            @if($todayQueue)
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Tiket Antrean Anda</span>
                <div class="text-5xl font-black text-slate-800 my-2 tracking-tight" id="user-queue-code">
                    {{ $todayQueue->kode_antrean }}
                </div>
                <span class="px-3.5 py-1 rounded-full text-xs font-semibold border {{ $todayQueue->badge_class }}" id="user-queue-status">
                    {{ $todayQueue->status_label }}
                </span>
                
                <!-- Live Queue Progress Bar (For active tickets) -->
                <div class="mt-5 space-y-1.5" id="queue-progress-bar-container">
                    @php
                        $progress = 10;
                        if($todayQueue->status === 'menunggu') $progress = 35;
                        else if($todayQueue->status === 'dilayani') $progress = 70;
                        else if($todayQueue->status === 'selesai') $progress = 100;
                        else if($todayQueue->status === 'dilewati') $progress = 100;
                    @endphp
                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                        <div id="queue-progress-fill" class="bg-emerald-500 h-2 rounded-full transition-all duration-550" style="width: {{ $progress }}%"></div>
                    </div>
                    <span id="queue-progress-text" class="text-[9px] uppercase font-bold text-slate-400 block tracking-wider">Progress: {{ $progress }}%</span>
                </div>
            @else
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">Tiket Antrean Anda</span>
                <p class="text-[11px] text-slate-400 mb-4">Anda belum mengambil antrean hari ini.</p>
                <form action="{{ route('warga.queue.take') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-semibold text-xs px-6 py-2.5 rounded-xl transition duration-200 shadow-sm cursor-pointer w-full sm:w-auto">
                        Ambil Antrean
                    </button>
                </form>
            @endif
        </div>

        <!-- Right Column: Currently serving and wait estimations -->
        <div class="space-y-4">
            <div class="bg-emerald-50/60 border border-emerald-100 rounded-2xl p-4 flex items-center gap-4">
                <div class="p-3 bg-emerald-500 text-white rounded-xl">
                    <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M18.364 5.636a9 9 0 010 12.728M12 18a6 6 0 100-12 6 6 0 000 12z"></path>
                    </svg>
                </div>
                <div>
                    <span class="text-xs text-emerald-600 font-semibold block">Sedang Dilayani</span>
                    <span class="text-xl font-bold text-slate-800" id="active-queue-code">
                        {{ $activeQueue ? $activeQueue->kode_antrean : 'Belum dimulai' }}
                    </span>
                </div>
            </div>

            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 flex items-center gap-4">
                <div class="p-3 bg-slate-400 text-white rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <span class="text-xs text-slate-500 font-semibold block">Status Antrean Anda</span>
                    <span class="text-xs font-semibold text-slate-700" id="waiting-behind-count">
                        @if($todayQueue)
                            Mengecek antrean...
                        @else
                            Silakan ambil antrean terlebih dahulu.
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Live status monitor ticker -->
    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center gap-2 text-[10px] text-slate-400 font-medium">
        <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
        </span>
        <span>Terhubung ke live monitor. Status diperbarui secara otomatis setiap 3 detik.</span>
    </div>
</div>

<!-- Grid Layout: Left (Members & Timeline), Right (Schedules & Info) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Column Left: Members & Health Timeline (2/3 width) -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Family Members Grid -->
        <div id="members-section" class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-extrabold text-slate-800">Anggota Keluarga Terdaftar</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Pilih salah satu kartu untuk riwayat kesehatan detail</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100">
                        {{ $members->count() }} Orang
                    </span>
                    <button onclick="openAddMemberModal()" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-[10px] px-3 py-1.5 rounded-xl transition shadow-xs flex items-center gap-1 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-9-4h18"></path></svg>
                        Tambah Anggota
                    </button>
                </div>
            </div>

            <!-- Members Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($members as $m)
                    @php
                        // Set colors and avatars depending on category
                        $bgColor = 'bg-slate-50 text-slate-600';
                        $kategoriLabel = $m->kategori_formatted;
                        
                        if($m->kategori === 'ibu_hamil') $bgColor = 'bg-rose-50 text-rose-600 border border-rose-100';
                        else if($m->kategori === 'bayi') $bgColor = 'bg-cyan-50 text-cyan-600 border border-cyan-100';
                        else if($m->kategori === 'balita') $bgColor = 'bg-indigo-50 text-indigo-600 border border-indigo-100';
                        else if($m->kategori === 'lansia') $bgColor = 'bg-amber-50 text-amber-600 border border-amber-100';
                    @endphp
                    <div @if($m->status_verifikasi === 'disetujui') onclick="openMemberModal({{ $m->id }})" @elseif($m->status_verifikasi === 'pending') onclick="alert('Anggota keluarga ini sedang menunggu verifikasi dari Kader Posyandu.')" @else onclick="alert('Pendaftaran anggota ini ditolak. Silakan hubungi Kader Posyandu.')" @endif class="p-4 bg-white border border-slate-150 hover:border-emerald-300 rounded-2xl shadow-xs transition duration-200 cursor-pointer flex items-start gap-4 {{ $m->status_verifikasi !== 'disetujui' ? 'opacity-70 bg-slate-50/50' : '' }}">
                        <div class="avatar-wrapper {{ $bgColor }}">
                            @if($m->kategori === 'ibu_hamil')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            @elseif($m->kategori === 'lansia')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            @endif
                        </div>
                        <div class="min-w-0 space-y-1">
                            <h4 class="font-extrabold text-slate-800 text-xs truncate flex items-center gap-1.5">
                                {{ $m->nama }}
                                @if($m->status_verifikasi === 'pending')
                                    <span class="bg-amber-100 text-amber-800 text-[8px] font-bold px-1.5 py-0.5 rounded-full uppercase shrink-0">Pending</span>
                                @elseif($m->status_verifikasi === 'ditolak')
                                    <span class="bg-rose-100 text-rose-800 text-[8px] font-bold px-1.5 py-0.5 rounded-full uppercase shrink-0">Ditolak</span>
                                @endif
                            </h4>
                            <p class="text-[10px] text-slate-400 font-semibold">{{ $m->hubungan_keluarga }} &bull; {{ $m->umur }}</p>
                            <span class="inline-block text-[9px] font-extrabold px-2 py-0.5 rounded-full {{ $bgColor }} uppercase tracking-wider">
                                {{ $kategoriLabel }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Family Health Timeline -->
        <div id="timeline-section" class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs relative">
            <h3 class="text-base font-extrabold text-slate-800 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Family Health Timeline (Perjalanan Kesehatan Keluarga)
            </h3>

            @if($healthHistories->count() > 0)
                <div class="relative border-l border-slate-150 ml-4 pl-6 space-y-6">
                    @foreach($healthHistories as $hist)
                        @php
                            $badgeColor = 'bg-slate-50 text-slate-500';
                            if($hist->anggotaKeluarga->kategori === 'ibu_hamil') $badgeColor = 'bg-rose-50 text-rose-500';
                            else if($hist->anggotaKeluarga->kategori === 'bayi') $badgeColor = 'bg-cyan-50 text-cyan-500';
                            else if($hist->anggotaKeluarga->kategori === 'balita') $badgeColor = 'bg-indigo-50 text-indigo-500';
                            else if($hist->anggotaKeluarga->kategori === 'lansia') $badgeColor = 'bg-amber-50 text-amber-500';
                        @endphp
                        <div class="relative text-xs">
                            <!-- Bullet point pointer -->
                            <div class="absolute -left-10 mt-1.5 timeline-badge {{ $badgeColor }} border border-white shadow-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            
                            <!-- Date & Member Name -->
                            <span class="text-[10px] text-slate-400 font-bold block mb-0.5">{{ $hist->tanggal_periksa->format('d M Y') }}</span>
                            <h4 class="font-extrabold text-slate-700">{{ $hist->anggotaKeluarga->nama }}</h4>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">{{ $hist->anggotaKeluarga->kategori_formatted }}</p>
                            
                            <!-- Checkup Parameters List -->
                            <div class="bg-slate-50/70 border border-slate-100 rounded-xl p-3 mt-1.5 space-y-1">
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-[10px] text-slate-500 font-semibold">
                                    <span>Berat: <strong class="text-slate-700">{{ $hist->berat_badan }} kg</strong></span>
                                    @if($hist->tinggi_badan)
                                        <span>Tinggi: <strong class="text-slate-700">{{ $hist->tinggi_badan }} cm</strong></span>
                                    @endif
                                    @if($hist->tekanan_darah)
                                        <span>Tensi: <strong class="text-slate-700">{{ $hist->tekanan_darah }}</strong></span>
                                    @endif
                                    @if($hist->lingkar_kepala)
                                        <span>L. Kepala: <strong class="text-slate-700">{{ $hist->lingkar_kepala }} cm</strong></span>
                                    @endif
                                </div>
                                @if($hist->keluhan)
                                    <p class="text-[10.5px] text-slate-600 mt-1 leading-relaxed"><span class="font-bold text-slate-500">Keluhan:</span> {{ $hist->keluhan }}</p>
                                @endif
                                @if($hist->tindakan)
                                    <p class="text-[10.5px] text-emerald-700 mt-1 leading-relaxed"><span class="font-bold text-emerald-600/80">Tindakan/Resep:</span> {{ $hist->tindakan }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-slate-400 text-xs bg-slate-50 border border-slate-100 rounded-2xl">
                    Belum ada riwayat pelayanan atau kunjungan posyandu terdaftar.
                </div>
            @endif
        </div>

    </div>

    <!-- Column Right: Schedules, Reminders, Announcements (1/3 width) -->
    <div class="space-y-6">

        <!-- Live Notifications Panel -->
        <div id="notifications-panel" class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs relative">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-extrabold text-slate-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    Notifikasi Terbaru
                </h3>
                @if($notifications->contains(fn($n) => !$n->is_read))
                    <button onclick="markAllNotificationsAsRead()" id="btn-read-all" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-700 underline cursor-pointer">
                        Tandai Semua Dibaca
                    </button>
                @endif
            </div>
            
            <div class="space-y-3" id="notifications-container">
                @forelse($notifications as $n)
                    <div class="p-3 rounded-xl border transition-all duration-150 relative {{ $n->is_read ? 'bg-slate-50/50 border-slate-100 text-slate-500' : 'bg-emerald-50/40 border-emerald-100 text-slate-800' }}" data-notification-id="{{ $n->id }}">
                        <div class="flex items-start justify-between gap-2">
                            <span class="text-[9px] font-bold {{ $n->is_read ? 'text-slate-400' : 'text-emerald-650' }} block">
                                {{ $n->created_at->diffForHumans() }}
                            </span>
                            @if(!$n->is_read)
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0 mt-1"></span>
                            @endif
                        </div>
                        <h4 class="font-extrabold text-xs leading-snug mt-0.5 {{ $n->is_read ? 'text-slate-550' : 'text-slate-800' }}">{{ $n->judul }}</h4>
                        <p class="text-[10.5px] leading-relaxed mt-1 {{ $n->is_read ? 'text-slate-400' : 'text-slate-650' }}">{{ $n->pesan }}</p>
                    </div>
                @empty
                    <div class="text-center py-4 text-xs text-slate-400">Belum ada notifikasi baru.</div>
                @endforelse
            </div>
        </div>
        
        <!-- Smart Reminders & Tips -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-24 h-24 bg-amber-50 rounded-full blur-2xl"></div>
            <h3 class="text-base font-extrabold text-slate-800 mb-4 flex items-center gap-2 relative z-10">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Pengingat Penting (Health Reminders)
            </h3>
            
            <div class="space-y-3.5 relative z-10">
                @foreach($reminders as $rem)
                    <div class="flex items-start gap-2.5 text-xs text-slate-650 bg-amber-50/50 p-3 rounded-xl border border-amber-100/50 leading-relaxed">
                        <span class="text-amber-500 mt-0.5 shrink-0">&bull;</span>
                        <span>{{ $rem }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Schedules List -->
        <div id="schedules-section" class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs">
            <h3 class="text-base font-extrabold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Agenda Posyandu Terdekat
            </h3>
            
            <div class="space-y-4">
                @forelse($schedules as $sch)
                    <div class="flex gap-3 bg-slate-50/50 p-3 rounded-xl border border-slate-100">
                        <div class="bg-emerald-50 text-emerald-600 font-extrabold w-12 h-12 rounded-lg flex flex-col items-center justify-center shrink-0 border border-emerald-100">
                            <span class="text-xs leading-none">{{ $sch->tanggal_kegiatan->format('d') }}</span>
                            <span class="text-[9px] uppercase tracking-wider leading-none mt-0.5">{{ $sch->tanggal_kegiatan->format('M') }}</span>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-extrabold text-slate-700 text-xs truncate">{{ $sch->judul }}</h4>
                            <p class="text-[10px] text-slate-450 mt-0.5 truncate">{{ $sch->tempat }}</p>
                            <p class="text-[10px] text-emerald-650 mt-1 font-bold">{{ substr($sch->jam_mulai, 0, 5) }} - {{ substr($sch->jam_selesai, 0, 5) }} WIB</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-xs text-slate-400">Belum ada agenda terdekat.</div>
                @endforelse
            </div>
        </div>

        <!-- Announcements Card -->
        <div id="announcements-section" class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs">
            <h3 class="text-base font-extrabold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M18.364 5.636a9 9 0 010 12.728M12 18a6 6 0 100-12 6 6 0 000 12z"></path></svg>
                Papan Pengumuman
            </h3>
            
            <div class="space-y-4">
                @forelse($announcements as $ann)
                    <div class="border-b border-slate-100 last:border-0 pb-3.5 last:pb-0 text-xs">
                        <span class="text-[9px] font-bold text-slate-400 block mb-0.5">{{ $ann->tanggal_terbit ? $ann->tanggal_terbit->format('d M Y') : $ann->created_at->format('d M Y') }}</span>
                        <h4 class="font-extrabold text-slate-700 leading-snug">{{ $ann->judul }}</h4>
                        <p class="text-[10.5px] text-slate-500 mt-1 leading-relaxed">{{ $ann->konten }}</p>
                    </div>
                @empty
                    <div class="text-center py-4 text-xs text-slate-400">Belum ada pengumuman.</div>
                @endforelse
            </div>
        </div>

    </div>

</div>

<!-- Member Details Modal Layer (Overlay) -->
<div id="memberDetailModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-3xl w-full max-w-2xl max-h-[85vh] overflow-y-auto shadow-2xl border border-slate-100 flex flex-col p-6 animate-scaleUp">
        
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
            <div>
                <h3 class="text-base font-extrabold text-slate-800" id="modal-member-name">Nama Anggota</h3>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400" id="modal-member-relation">Hubungan</span>
            </div>
            <button onclick="closeMemberModal()" class="text-slate-400 hover:text-slate-700 transition cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Profile metadata -->
        <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-2xl text-xs mb-5 text-slate-650">
            <div>
                <span class="text-[9px] uppercase font-bold text-slate-400 block tracking-wider">Nomor NIK</span>
                <span class="font-bold text-slate-800 block mt-0.5" id="modal-member-nik">-</span>
            </div>
            <div>
                <span class="text-[9px] uppercase font-bold text-slate-400 block tracking-wider">Kategori Warga</span>
                <span class="font-bold text-slate-800 block mt-0.5" id="modal-member-kategori">-</span>
            </div>
            <div>
                <span class="text-[9px] uppercase font-bold text-slate-400 block tracking-wider">Tanggal Lahir</span>
                <span class="font-bold text-slate-800 block mt-0.5" id="modal-member-birthdate">-</span>
            </div>
            <div>
                <span class="text-[9px] uppercase font-bold text-slate-400 block tracking-wider">Umur Saat Ini</span>
                <span class="font-bold text-slate-800 block mt-0.5" id="modal-member-age">-</span>
            </div>
        </div>

        <!-- Health Records Table -->
        <div class="space-y-3 flex-1">
            <h4 class="font-extrabold text-slate-800 text-xs">Daftar Rekam Medis / Riwayat Pemeriksaan</h4>
            <div class="overflow-x-auto border border-slate-100 rounded-2xl">
                <table class="w-full text-left border-collapse text-[10px]">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Berat (kg)</th>
                            <th class="p-3">Tinggi (cm)</th>
                            <th class="p-3">Tensi/L.Kepala</th>
                            <th class="p-3">Keluhan & Tindakan</th>
                        </tr>
                    </thead>
                    <tbody id="modal-health-table-body" class="divide-y divide-slate-100">
                        <!-- Injected via JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Kader WhatsApp Modal Layer (Contact Hub) -->
<div id="kaderModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-slate-100 p-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
            <div>
                <h3 class="text-base font-extrabold text-slate-800">Daftar Kader Aktif</h3>
                <p class="text-[9px] text-slate-400 mt-0.5">Hubungi kader posyandu jika ada keluhan atau pertanyaan</p>
            </div>
            <button onclick="closeKaderModal()" class="text-slate-400 hover:text-slate-700 transition cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="space-y-3.5">
            @forelse($kaders as $kd)
                <div class="flex items-center justify-between gap-4 p-3 bg-slate-50 border border-slate-100 rounded-2xl">
                    <div class="min-w-0">
                        <h4 class="font-extrabold text-slate-850 text-xs truncate">{{ $kd->name }}</h4>
                        <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Petugas Posyandu Desa</p>
                    </div>
                    @php
                        // Normalize phone format for WhatsApp links
                        $waNum = preg_replace('/[^0-9]/', '', $kd->nomor_telepon);
                        if(strpos($waNum, '08') === 0) {
                            $waNum = '628' . substr($waNum, 2);
                        }
                    @endphp
                    <a href="https://wa.me/{{ $waNum }}?text=Halo%20Kader%20{{ urlencode($kd->name) }}%2C%20saya%20warga%20dari%20Keluarga%20Bapak%20{{ urlencode($user->kepala_keluarga) }}%20ingin%20berkonsultasi%20mengenai%20pelayanan%20Posyandu..." 
                        target="_blank" 
                        class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-[10px] px-3.5 py-2 rounded-xl transition flex items-center gap-1 shrink-0 shadow-sm cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.73-1.45L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.42 9.864-9.858.002-2.634-1.023-5.11-2.884-6.974C16.568 1.909 14.1 .882 11.995.882c-5.442 0-9.867 4.42-9.87 9.86-.001 1.748.47 3.456 1.365 4.965L2.553 21.05l5.094-1.896z"></path></svg>
                        Hubungi
                    </a>
                </div>
            @empty
                <div class="text-center py-6 text-slate-400 text-xs">Belum ada kader terdaftar.</div>
            @endforelse
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // 1. Live Time Update
    function updateLiveTime() {
        const timeEl = document.getElementById('live-time');
        if (timeEl) {
            const now = new Date();
            timeEl.innerText = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
        }
    }
    setInterval(updateLiveTime, 1000);
    updateLiveTime();

    // 2. WhatsApp Modal Controls
    const kaderModal = document.getElementById('kaderModal');
    function openKaderModal() {
        kaderModal.classList.remove('hidden');
    }
    function closeKaderModal() {
        kaderModal.classList.add('hidden');
    }

    // 3. Family Member Detailed Modal & JSON records fetching
    const memberModal = document.getElementById('memberDetailModal');
    const modalName = document.getElementById('modal-member-name');
    const modalRelation = document.getElementById('modal-member-relation');
    const modalNik = document.getElementById('modal-member-nik');
    const modalKategori = document.getElementById('modal-member-kategori');
    const modalBirthdate = document.getElementById('modal-member-birthdate');
    const modalAge = document.getElementById('modal-member-age');
    const modalTableBody = document.getElementById('modal-health-table-body');

    // Pluck members list locally using Laravel's json_encode
    const familyMembersList = {!! json_encode($members) !!};
    const healthChecksList = {!! json_encode($healthHistories) !!};

    function openMemberModal(memberId) {
        const member = familyMembersList.find(m => m.id === memberId);
        if (!member) return;

        // Populate metadata
        modalName.innerText = member.nama;
        modalRelation.innerText = member.hubungan_keluarga;
        modalNik.innerText = member.nik;
        modalKategori.innerText = member.kategori_formatted;
        modalBirthdate.innerText = member.tanggal_lahir_formatted || member.tanggal_lahir;
        modalAge.innerText = member.umur;

        // Filter and inject health records
        const records = healthChecksList.filter(h => h.anggota_keluarga_id === memberId);
        modalTableBody.innerHTML = '';
        if (records.length === 0) {
            modalTableBody.innerHTML = `<tr><td colspan="5" class="p-6 text-center text-slate-400">Belum ada catatan medis untuk anggota keluarga ini.</td></tr>`;
        } else {
            records.forEach(r => {
                const formattedDate = new Date(r.tanggal_periksa).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                
                const tensiValue = r.tekanan_darah ? r.tekanan_darah : '-';
                const lKepalaValue = r.lingkar_kepala ? r.lingkar_kepala + ' cm' : '-';
                const detailsColumn = `Tensi: ${tensiValue} <br> L.Kepala: ${lKepalaValue}`;

                let complaintsActions = '-';
                if (r.keluhan || r.tindakan) {
                    complaintsActions = `
                        ${r.keluhan ? `<strong>Keluhan:</strong> ${r.keluhan}<br>` : ''}
                        ${r.tindakan ? `<strong class="text-emerald-600">Tindakan:</strong> ${r.tindakan}` : ''}
                    `;
                }

                modalTableBody.innerHTML += `
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-3 font-semibold text-slate-700 whitespace-nowrap">${formattedDate}</td>
                        <td class="p-3 text-slate-650 font-bold">${r.berat_badan} kg</td>
                        <td class="p-3 text-slate-650">${r.tinggi_badan ? r.tinggi_badan + ' cm' : '-'}</td>
                        <td class="p-3 text-slate-500 leading-normal">${detailsColumn}</td>
                        <td class="p-3 text-slate-600 max-w-[200px] break-words">${complaintsActions}</td>
                    </tr>
                `;
            });
        }

        // Show Modal Layer
        memberModal.classList.remove('hidden');
    }

    function closeMemberModal() {
        memberModal.classList.add('hidden');
    }

    // Close modals on clicking backdrop
    window.addEventListener('click', function (e) {
        if (e.target === memberModal) closeMemberModal();
        if (e.target === kaderModal) closeKaderModal();
    });

    // 4. Live Queue Status Polling (Every 3 seconds)
    function pollQueueStatus() {
        fetch('{{ route("warga.queue.status") }}')
            .then(response => response.json())
            .then(data => {
                const activeCodeEl = document.getElementById('active-queue-code');
                const queueStatusEl = document.getElementById('user-queue-status');
                const userCodeEl = document.getElementById('user-queue-code');
                const waitingBehindEl = document.getElementById('waiting-behind-count');
                const progressFill = document.getElementById('queue-progress-fill');
                const progressText = document.getElementById('queue-progress-text');
                
                if (activeCodeEl && data.active_queue_code) {
                    activeCodeEl.innerText = data.active_queue_code;
                }
                
                if (data.has_queue) {
                    if (userCodeEl) userCodeEl.innerText = data.queue_code;
                    
                    if (queueStatusEl && data.queue_status_label) {
                        queueStatusEl.innerText = data.queue_status_label;
                        queueStatusEl.className = 'px-3.5 py-1 rounded-full text-xs font-semibold border ';
                        
                        let progress = 10;
                        if (data.queue_status === 'menunggu') {
                            queueStatusEl.classList.add('bg-amber-100', 'text-amber-800', 'border-amber-200');
                            progress = 35;
                        } else if (data.queue_status === 'dilayani') {
                            queueStatusEl.classList.add('bg-emerald-100', 'text-emerald-800', 'border-emerald-200', 'animate-pulse');
                            progress = 70;
                        } else if (data.queue_status === 'selesai') {
                            queueStatusEl.classList.add('bg-slate-100', 'text-slate-800', 'border-slate-200');
                            progress = 100;
                        } else if (data.queue_status === 'dilewati') {
                            queueStatusEl.classList.add('bg-rose-100', 'text-rose-800', 'border-rose-200');
                            progress = 100;
                        }

                        // Update dynamic progress bar elements
                        if (progressFill) progressFill.style.width = progress + '%';
                        if (progressText) progressText.innerText = 'Progress: ' + progress + '%';
                    }
                    
                    if (waitingBehindEl) {
                        if (data.queue_status === 'selesai') {
                            waitingBehindEl.innerText = 'Selesai dilayani.';
                        } else if (data.queue_status === 'dilewati') {
                            waitingBehindEl.innerText = 'Dilewati, silakan temui Kader.';
                        } else {
                            waitingBehindEl.innerText = data.waiting_behind + ' keluarga lagi';
                        }
                    }
                } else {
                    if (waitingBehindEl) {
                        waitingBehindEl.innerText = 'Silakan ambil antrean terlebih dahulu.';
                    }
                }
            })
            .catch(err => console.error("Error polling queue status:", err));
    }

    // Run polling
    setInterval(pollQueueStatus, 3000);
    pollQueueStatus();

    // 5. Mark All Notifications as Read dynamically
    function markAllNotificationsAsRead() {
        fetch('{{ route("warga.notifications.read") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const btn = document.getElementById('btn-read-all');
                if (btn) btn.remove();
                
                const containers = document.querySelectorAll('#notifications-container > div');
                containers.forEach(div => {
                    div.className = 'p-3 rounded-xl border transition-all duration-150 relative bg-slate-50/50 border-slate-100 text-slate-500';
                    
                    const spanTime = div.querySelector('span.text-emerald-650');
                    if (spanTime) {
                        spanTime.className = 'text-[9px] font-bold text-slate-400 block';
                    }
                    
                    const title = div.querySelector('h4');
                    if (title) {
                        title.className = 'font-extrabold text-xs leading-snug mt-0.5 text-slate-550';
                    }

                    const desc = div.querySelector('p');
                    if (desc) {
                        desc.className = 'text-[10.5px] leading-relaxed mt-1 text-slate-400';
                    }

                    const dot = div.querySelector('span.bg-emerald-500');
                    if (dot) dot.remove();
                });
            }
        })
        .catch(err => console.error("Error marking notifications as read:", err));
    }

    // 6. Modal Tambah Anggota Keluarga
    function openAddMemberModal() {
        const modal = document.getElementById('add-member-modal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeAddMemberModal() {
        const modal = document.getElementById('add-member-modal');
        if (modal) modal.classList.add('hidden');
    }

    @if($errors->any() && (old('nik') || old('name')))
        document.addEventListener('DOMContentLoaded', function() {
            openAddMemberModal();
        });
    @endif
</script>

<!-- Modal Tambah Anggota Keluarga -->
<div id="add-member-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <!-- Backdrop -->
    <div onclick="closeAddMemberModal()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity duration-300"></div>
    
    <!-- Modal Content -->
    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-2xl relative z-10 w-full max-w-md mx-4 animate-in fade-in zoom-in-95 duration-200">
        <button onclick="closeAddMemberModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-1 bg-slate-50 hover:bg-slate-100 rounded-full transition cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        
        <div class="flex items-center gap-2.5 mb-6">
            <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            </div>
            <h3 class="text-base font-extrabold text-slate-800">Tambah Anggota Keluarga</h3>
        </div>
        
        <form action="{{ route('warga.member.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="warga_nik" class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">NIK (Nomor Induk Kependudukan)</label>
                <input type="text" id="warga_nik" name="nik" required maxlength="16" minlength="16" value="{{ old('nik') }}"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition" 
                    placeholder="16 digit angka NIK">
            </div>
            <div>
                <label for="warga_name" class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Nama Anggota Keluarga</label>
                <input type="text" id="warga_name" name="name" required value="{{ old('name') }}"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition" 
                    placeholder="Nama Lengkap sesuai KTP/KIA">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="warga_gender" class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Jenis Kelamin</label>
                    <select id="warga_gender" name="gender" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition cursor-pointer">
                        <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label for="warga_category" class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Kategori Kesehatan</label>
                    <select id="warga_category" name="category" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition cursor-pointer">
                        <option value="bayi" {{ old('category') === 'bayi' ? 'selected' : '' }}>Bayi (&lt; 12 Bulan)</option>
                        <option value="balita" {{ old('category') === 'balita' ? 'selected' : '' }}>Balita (1 - 5 Tahun)</option>
                        <option value="anak" {{ old('category') === 'anak' ? 'selected' : '' }}>Anak-Anak</option>
                        <option value="remaja" {{ old('category') === 'remaja' ? 'selected' : '' }}>Remaja</option>
                        <option value="dewasa" {{ old('category') === 'dewasa' ? 'selected' : '' }}>Dewasa</option>
                        <option value="ibu_hamil" {{ old('category') === 'ibu_hamil' ? 'selected' : '' }}>Ibu Hamil</option>
                        <option value="lansia" {{ old('category') === 'lansia' ? 'selected' : '' }}>Lansia</option>
                    </select>
                </div>
            </div>
            <div>
                <label for="warga_birth_date" class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Tanggal Lahir</label>
                <input type="date" id="warga_birth_date" name="birth_date" required value="{{ old('birth_date') }}"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
            </div>
            
            <div class="pt-2">
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl text-xs font-semibold shadow-md shadow-emerald-100 hover:shadow-lg transition cursor-pointer">
                    Ajukan Anggota Keluarga
                </button>
                <span class="text-[9px] text-slate-400 mt-2 block text-center">Status pendaftaran akan ditandai pending hingga disetujui Kader Posyandu.</span>
            </div>
        </form>
    </div>
</div>
@endsection
