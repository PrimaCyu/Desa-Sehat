@extends('layouts.app')

@section('title', 'Kelola Pengumuman Posyandu')

@section('content')
<!-- Hero Header Card (Ultra-Clean Responsive SaaS Banner) -->
<div class="bg-white border border-slate-100 rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-xs mb-5 sm:mb-6 relative overflow-hidden">
    <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-50 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="flex items-center justify-between gap-3 relative z-10">
        <!-- Icon & Titles -->
        <div class="flex items-center gap-3 min-w-0">
            <div class="p-2.5 sm:p-3 bg-emerald-500 text-white rounded-xl sm:rounded-2xl shadow-xs shrink-0">
                <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2">
                    <h2 class="text-base sm:text-xl font-extrabold text-slate-800 tracking-tight truncate">Papan Pengumuman Posyandu</h2>
                    <span class="hidden sm:inline-block text-[10px] font-extrabold text-emerald-700 bg-emerald-50 border border-emerald-100 px-2.5 py-0.5 rounded-full uppercase">Publik</span>
                </div>
                <p class="text-[11px] sm:text-xs text-slate-500 font-medium mt-0.5 truncate sm:whitespace-normal">Publikasikan berita resmi & informasi kegiatan bagi seluruh warga desa</p>
            </div>
        </div>

        <!-- Quick Right Stat Badge -->
        <div class="shrink-0">
            <div class="bg-slate-50 border border-slate-150 px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl sm:rounded-2xl text-right">
                <span class="hidden sm:block text-[10px] text-slate-400 font-bold uppercase">Total Berita</span>
                <span class="text-xs sm:text-sm font-extrabold text-emerald-600 leading-none whitespace-nowrap">{{ $announcements->total() }} <span class="hidden sm:inline">Pengumuman</span><span class="sm:hidden">Berita</span></span>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- List Announcements (2/3 width) -->
    <div class="lg:col-span-2 space-y-4">
        
        <!-- List Announcement Cards (Modern Feed Layout, Zero Overlaps) -->
        <div class="bg-white border border-slate-100 rounded-3xl p-4 sm:p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                <h3 class="text-xs sm:text-sm font-extrabold text-slate-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    Daftar Pengumuman Aktif
                </h3>
                <span class="text-[10px] sm:text-[11px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 sm:px-2.5 rounded-full shrink-0">
                    Total: {{ $announcements->total() }}
                </span>
            </div>

            <div class="space-y-3">
                @forelse($announcements as $a)
                    <div class="p-3.5 sm:p-4 bg-slate-50/70 hover:bg-emerald-50/40 rounded-2xl border border-slate-150 hover:border-emerald-200 transition-all duration-200 space-y-3 sm:space-y-0 sm:flex sm:items-center sm:justify-between sm:gap-4 group">
                        
                        <div class="min-w-0 flex-1 space-y-1">
                            <div class="flex items-center gap-1.5 flex-wrap text-[10px] sm:text-[10.5px]">
                                <span class="font-bold text-emerald-700 bg-emerald-50 border border-emerald-100/80 px-2 py-0.5 rounded-md flex items-center gap-1">
                                    <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $a->tanggal_terbit ? $a->tanggal_terbit->format('d M Y') : $a->created_at->format('d M Y') }}
                                </span>
                                <span class="font-semibold text-slate-400 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Oleh: {{ $a->pembuat ? $a->pembuat->name : 'Sistem' }}
                                </span>
                            </div>

                            <h4 class="font-extrabold text-slate-800 text-xs sm:text-sm leading-snug group-hover:text-emerald-700 transition">
                                {{ $a->judul }}
                            </h4>

                            <p class="text-[11px] sm:text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                {{ Str::limit($a->konten, 130) }}
                            </p>
                        </div>

                        
                        <div class="flex items-center justify-end gap-2 pt-2.5 sm:pt-0 border-t sm:border-t-0 border-slate-200/60 shrink-0">
                            <button type="button" onclick="openAnnouncementDetailModal({{ $a->id }})" class="bg-white hover:bg-slate-100 text-slate-700 font-bold px-3 py-1.5 rounded-xl border border-slate-200 text-xs shadow-2xs transition cursor-pointer flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Detail
                            </button>
                            <form action="{{ route('kader.announcements.delete', $a->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')" class="inline-block">
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
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        </div>
                        <h5 class="font-bold text-slate-600 text-xs">Belum ada pengumuman yang diterbitkan</h5>
                        <p class="text-[10px] text-slate-400 mt-0.5">Gunakan formulir di sebelah kanan untuk menambahkan pengumuman baru.</p>
                    </div>
                @endforelse
            </div>

            @if($announcements->hasPages())
                <div class="mt-4 pt-4 border-t border-slate-100">
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- Create Announcement Panel -->
    <div>
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs relative lg:sticky lg:top-20">
            <div class="flex items-center gap-2.5 mb-6">
                <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-100/80">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Tulis Pengumuman Baru</h3>
                    <p class="text-[11px] text-slate-400 font-medium">Informasi resmi bagi seluruh warga</p>
                </div>
            </div>

            <form action="{{ route('kader.announcements.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="title" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Judul Pengumuman</label>
                    <input type="text" id="title" name="title" required value="{{ old('title') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-hidden transition placeholder:text-slate-400" 
                        placeholder="Contoh: Pemberian Imunisasi Polio Tambahan">
                </div>
                <div>
                    <label for="content" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Isi Pengumuman</label>
                    <textarea id="content" name="content" required rows="6"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-hidden transition leading-relaxed placeholder:text-slate-400" 
                        placeholder="Tuliskan berita lengkap pengumuman untuk warga...">{{ old('content') }}</textarea>
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl text-xs font-semibold shadow-md shadow-emerald-100 hover:shadow-lg transition cursor-pointer">
                    Publikasikan Pengumuman
                </button>
            </form>
        </div>
    </div>

</div>

<!-- Modal Detail Pengumuman -->
<div id="announcementDetailModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full max-h-[85vh] flex flex-col p-6 shadow-2xl border border-slate-100 transform transition-all">
        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Detail Pengumuman Posyandu</h3>
            </div>
            <button onclick="closeAnnouncementDetailModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Scrollable Modal Body -->
        <div class="space-y-4 overflow-y-auto flex-1 my-3 pr-1">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Judul Pengumuman</span>
                <h4 id="detail-announcement-title" class="text-base font-extrabold text-slate-800 leading-snug break-words"></h4>
            </div>

            <div class="grid grid-cols-2 gap-4 bg-slate-50/80 p-3.5 rounded-2xl border border-slate-100 text-xs">
                <div>
                    <span class="text-[10px] font-semibold text-slate-400 uppercase block mb-0.5">Tanggal Terbit</span>
                    <span id="detail-announcement-date" class="font-bold text-slate-700 block"></span>
                </div>
                <div>
                    <span class="text-[10px] font-semibold text-slate-400 uppercase block mb-0.5">Pembuat</span>
                    <span id="detail-announcement-creator" class="font-bold text-emerald-600 block"></span>
                </div>
            </div>

            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Isi Pengumuman Lengkap</span>
                <div id="detail-announcement-content" class="text-xs text-slate-600 leading-relaxed bg-slate-50/50 p-4 rounded-2xl border border-slate-100 whitespace-pre-line break-words max-h-52 overflow-y-auto"></div>
            </div>
        </div>

        <!-- Fixed Footer -->
        <div class="pt-3 border-t border-slate-100 flex justify-end shrink-0">
            <button onclick="closeAnnouncementDetailModal()" class="bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs px-5 py-2.5 rounded-xl transition cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const announcementsList = {!! json_encode($announcements->items()) !!};
    const modal = document.getElementById('announcementDetailModal');

    function openAnnouncementDetailModal(id) {
        const ann = announcementsList.find(a => Number(a.id) === Number(id));
        if (!ann) return;

        document.getElementById('detail-announcement-title').innerText = ann.judul;
        
        let formattedDate = ann.tanggal_terbit || ann.created_at;
        try {
            const d = new Date(formattedDate);
            formattedDate = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        } catch(e) {}

        document.getElementById('detail-announcement-date').innerText = formattedDate;
        document.getElementById('detail-announcement-creator').innerText = ann.pembuat ? ann.pembuat.name : 'Sistem Posyandu';
        document.getElementById('detail-announcement-content').innerText = ann.konten;

        if (modal) modal.classList.remove('hidden');
    }

    function closeAnnouncementDetailModal() {
        if (modal) modal.classList.add('hidden');
    }

    window.addEventListener('click', function(e) {
        if (e.target === modal) closeAnnouncementDetailModal();
    });
</script>
@endsection
