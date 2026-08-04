@extends('layouts.app')

@section('title', 'Kelola Jadwal Posyandu')

@section('content')
<!-- Hero Header Card (Ultra-Clean Responsive SaaS Banner) -->
<div class="bg-white border border-slate-100 rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-xs mb-5 sm:mb-6 relative overflow-hidden">
    <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-50 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="flex items-center justify-between gap-3 relative z-10">
        <!-- Icon & Titles -->
        <div class="flex items-center gap-3 min-w-0">
            <div class="p-2.5 sm:p-3 bg-emerald-500 text-white rounded-xl sm:rounded-2xl shadow-xs shrink-0">
                <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2">
                    <h2 class="text-base sm:text-xl font-extrabold text-slate-800 tracking-tight truncate">Jadwal Kegiatan Posyandu</h2>
                    <span class="hidden sm:inline-block text-[10px] font-extrabold text-emerald-700 bg-emerald-50 border border-emerald-100 px-2.5 py-0.5 rounded-full uppercase">Kalender</span>
                </div>
                <p class="text-[11px] sm:text-xs text-slate-500 font-medium mt-0.5 truncate sm:whitespace-normal">Atur agenda pemeriksaan, imunisasi, dan kegiatan kesehatan desa</p>
            </div>
        </div>

        <!-- Quick Right Stat Badge -->
        <div class="shrink-0">
            <div class="bg-slate-50 border border-slate-150 px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl sm:rounded-2xl text-right">
                <span class="hidden sm:block text-[10px] text-slate-400 font-bold uppercase">Total Agenda</span>
                <span class="text-xs sm:text-sm font-extrabold text-emerald-600 leading-none whitespace-nowrap">{{ $schedules->total() }} <span class="hidden sm:inline">Kegiatan</span><span class="sm:hidden">Agenda</span></span>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- List Schedules Panel (2/3 width) -->
    <div class="lg:col-span-2 space-y-4">
        
        <!-- List Schedule Cards (Modern Feed Layout, 100% Mobile & Desktop Responsive) -->
        <div class="bg-white border border-slate-100 rounded-3xl p-4 sm:p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                <h3 class="text-xs sm:text-sm font-extrabold text-slate-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Daftar Agenda Terjadwal
                </h3>
                <span class="text-[10px] sm:text-[11px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 sm:px-2.5 rounded-full shrink-0">
                    Total: {{ $schedules->total() }}
                </span>
            </div>

            <div class="space-y-3">
                @forelse($schedules as $s)
                    <div class="p-3.5 sm:p-4 bg-slate-50/70 hover:bg-emerald-50/40 rounded-2xl border border-slate-150 hover:border-emerald-200 transition-all duration-200 space-y-3 sm:space-y-0 sm:flex sm:items-center sm:justify-between sm:gap-4 group">
                        <!-- Main Content Info -->
                        <div class="min-w-0 flex-1 space-y-1">
                            <div class="flex items-center gap-1.5 flex-wrap text-[10px] sm:text-[10.5px]">
                                <!-- Date Badge -->
                                <span class="font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-md flex items-center gap-1">
                                    <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $s->tanggal_kegiatan->format('d M Y') }}
                                </span>

                                <!-- Time Badge -->
                                <span class="font-semibold text-slate-600 bg-slate-100 border border-slate-200/70 px-2 py-0.5 rounded-md flex items-center gap-1">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ substr($s->jam_mulai, 0, 5) }} - {{ substr($s->jam_selesai, 0, 5) }} WIB
                                </span>

                                <!-- Location Badge -->
                                <span class="font-medium text-slate-500 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $s->tempat }}
                                </span>
                            </div>

                            <h4 class="font-extrabold text-slate-800 text-xs sm:text-sm leading-snug group-hover:text-emerald-700 transition">
                                {{ $s->judul }}
                            </h4>
                        </div>

                        <!-- Action Buttons Row -->
                        <div class="flex items-center justify-end gap-2 pt-2.5 sm:pt-0 border-t sm:border-t-0 border-slate-200/60 shrink-0">
                            <button type="button" onclick="openScheduleDetailModal({{ $s->id }})" class="bg-white hover:bg-slate-100 text-slate-700 font-bold px-3 py-1.5 rounded-xl border border-slate-200 text-xs shadow-2xs transition cursor-pointer flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Detail
                            </button>
                            <form action="{{ route('kader.schedules.delete', $s->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal kegiatan ini?')" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold px-3 py-1.5 rounded-xl border border-rose-200 text-xs transition cursor-pointer">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 border border-dashed border-slate-200 rounded-2xl">
                        <div class="inline-flex bg-slate-100 text-slate-400 p-3 rounded-full mb-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h5 class="font-bold text-slate-600 text-xs">Belum ada agenda jadwal kegiatan yang terdaftar</h5>
                        <p class="text-[10px] text-slate-400 mt-0.5">Gunakan formulir di sebelah kanan untuk menambahkan jadwal baru.</p>
                    </div>
                @endforelse
            </div>

            @if($schedules->hasPages())
                <div class="mt-4 pt-4 border-t border-slate-100">
                    {{ $schedules->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- Create Schedule Panel -->
    <div>
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs relative lg:sticky lg:top-20">
            <div class="flex items-center gap-2.5 mb-6">
                <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-100/80">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Buat Jadwal Baru</h3>
                    <p class="text-[11px] text-slate-400 font-medium">Isi detail agenda pelayanan</p>
                </div>
            </div>

            <form action="{{ route('kader.schedules.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="title" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nama Kegiatan</label>
                    <input type="text" id="title" name="title" required value="{{ old('title') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-hidden transition placeholder:text-slate-400" 
                        placeholder="Contoh: Posyandu Balita & Imunisasi">
                </div>
                <div>
                    <label for="event_date" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal Kegiatan</label>
                    <input type="date" id="event_date" name="event_date" required value="{{ old('event_date') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-hidden transition">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="start_time" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Jam Mulai</label>
                        <input type="time" id="start_time" name="start_time" required value="{{ old('start_time', '08:00') }}"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-hidden transition">
                    </div>
                    <div>
                        <label for="end_time" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Jam Selesai</label>
                        <input type="time" id="end_time" name="end_time" required value="{{ old('end_time', '12:00') }}"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-hidden transition">
                    </div>
                </div>
                <div>
                    <label for="location" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Tempat Kegiatan</label>
                    <input type="text" id="location" name="location" required value="{{ old('location') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-hidden transition placeholder:text-slate-400" 
                        placeholder="Contoh: Balai Desa RW 02">
                </div>
                <div>
                    <label for="description" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Deskripsi Tambahan</label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-hidden transition leading-relaxed placeholder:text-slate-400" 
                        placeholder="Keterangan pendukung jika ada...">{{ old('description') }}</textarea>
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl text-xs font-semibold shadow-md shadow-emerald-100 hover:shadow-lg transition cursor-pointer">
                    Simpan Jadwal Kegiatan
                </button>
            </form>
        </div>
    </div>

</div>

<!-- Modal Detail Jadwal Kegiatan -->
<div id="scheduleDetailModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full max-h-[85vh] flex flex-col p-6 shadow-2xl border border-slate-100 transform transition-all">
        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Detail Agenda Posyandu</h3>
            </div>
            <button onclick="closeScheduleDetailModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Scrollable Modal Body -->
        <div class="space-y-4 overflow-y-auto flex-1 my-3 pr-1">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Nama Kegiatan</span>
                <h4 id="detail-schedule-title" class="text-base font-extrabold text-slate-800 leading-snug break-words"></h4>
            </div>

            <div class="grid grid-cols-2 gap-4 bg-slate-50/80 p-3.5 rounded-2xl border border-slate-100 text-xs">
                <div>
                    <span class="text-[10px] font-semibold text-slate-400 uppercase block mb-0.5">Tanggal</span>
                    <span id="detail-schedule-date" class="font-bold text-slate-700 block"></span>
                </div>
                <div>
                    <span class="text-[10px] font-semibold text-slate-400 uppercase block mb-0.5">Waktu</span>
                    <span id="detail-schedule-time" class="font-bold text-emerald-600 block"></span>
                </div>
            </div>

            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Tempat Kegiatan</span>
                <p id="detail-schedule-location" class="text-xs font-semibold text-slate-700 bg-slate-50/50 p-3 rounded-xl border border-slate-100 break-words"></p>
            </div>

            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Deskripsi Tambahan</span>
                <div id="detail-schedule-description" class="text-xs text-slate-600 leading-relaxed bg-slate-50/50 p-3.5 rounded-2xl border border-slate-100 whitespace-pre-line break-words max-h-48 overflow-y-auto"></div>
            </div>
        </div>

        <!-- Fixed Footer -->
        <div class="pt-3 border-t border-slate-100 flex justify-end shrink-0">
            <button onclick="closeScheduleDetailModal()" class="bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs px-5 py-2.5 rounded-xl transition cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const schedulesList = {!! json_encode($schedules->items()) !!};
    const modal = document.getElementById('scheduleDetailModal');

    function openScheduleDetailModal(id) {
        const sch = schedulesList.find(s => Number(s.id) === Number(id));
        if (!sch) return;

        document.getElementById('detail-schedule-title').innerText = sch.judul;
        
        let formattedDate = sch.tanggal_kegiatan;
        try {
            const d = new Date(sch.tanggal_kegiatan);
            formattedDate = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        } catch(e) {}

        document.getElementById('detail-schedule-date').innerText = formattedDate;
        
        const startTime = sch.jam_mulai ? sch.jam_mulai.substring(0, 5) : '08:00';
        const endTime = sch.jam_selesai ? sch.jam_selesai.substring(0, 5) : '12:00';
        document.getElementById('detail-schedule-time').innerText = startTime + ' - ' + endTime + ' WIB';
        
        document.getElementById('detail-schedule-location').innerText = sch.tempat;
        document.getElementById('detail-schedule-description').innerText = sch.deskripsi ? sch.deskripsi : 'Tidak ada deskripsi tambahan.';

        if (modal) modal.classList.remove('hidden');
    }

    function closeScheduleDetailModal() {
        if (modal) modal.classList.add('hidden');
    }

    window.addEventListener('click', function(e) {
        if (e.target === modal) closeScheduleDetailModal();
    });
</script>
@endsection
