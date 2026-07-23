@extends('layouts.app')

@section('title', 'Kelola Pengumuman Posyandu')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-slate-800 tracking-tight">Papan Pengumuman Posyandu</h2>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Publikasikan pengumuman penting bagi warga</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- List Announcements (2/3 width) -->
    <div class="lg:col-span-2 space-y-4">
        
        <!-- Table Card (Clean & Minimalist Layout, Multi-Device Responsive) -->
        <div class="bg-white border border-slate-100 rounded-3xl p-4 sm:p-6 shadow-xs overflow-hidden">
            <div class="overflow-x-auto overflow-y-hidden">
                <table class="w-full table-fixed min-w-[640px] md:min-w-0 text-left border-collapse align-middle text-xs">
                    <thead>
                        <tr class="border-b border-slate-200/80 text-slate-500 font-semibold text-[11px] uppercase tracking-wider">
                            <th class="py-3 px-4 w-[18%] min-w-[110px] whitespace-nowrap">Tanggal Terbit</th>
                            <th class="py-3 px-4 w-[42%]">Judul & Ringkasan</th>
                            <th class="py-3 px-4 w-[20%] whitespace-nowrap">Pembuat</th>
                            <th class="py-3 px-4 w-[20%] text-right whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($announcements as $a)
                            <tr class="hover:bg-slate-50/60 transition-colors align-middle">
                                <!-- Tanggal Terbit -->
                                <td class="py-3.5 px-4 font-bold text-slate-600 align-middle whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span>{{ $a->tanggal_terbit ? $a->tanggal_terbit->format('d M Y') : $a->created_at->format('d M Y') }}</span>
                                    </div>
                                </td>

                                <!-- Judul & Ringkasan (Ringkas 1-2 baris tanpa box berlebihan) -->
                                <td class="py-3.5 px-4 align-middle">
                                    <span class="font-semibold text-slate-800 text-xs block truncate" title="{{ $a->judul }}">{{ $a->judul }}</span>
                                    <span class="text-[11px] text-slate-400 block mt-0.5 truncate" title="{{ Str::limit($a->konten, 150) }}">{{ Str::limit($a->konten, 90) }}</span>
                                </td>

                                <!-- Pembuat -->
                                <td class="py-3.5 px-4 text-slate-700 font-semibold align-middle whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200/70 text-[11px]">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        {{ $a->pembuat ? $a->pembuat->name : 'Sistem' }}
                                    </span>
                                </td>

                                <!-- Aksi (Detail & Hapus) -->
                                <td class="py-3.5 px-4 text-right align-middle whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5 justify-end">
                                        <button type="button" onclick="openAnnouncementDetailModal({{ $a->id }})" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-2.5 py-1.5 rounded-lg transition cursor-pointer text-xs border border-slate-200/70 inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            Detail
                                        </button>
                                        <form action="{{ route('kader.announcements.delete', $a->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-600 hover:text-rose-700 font-semibold px-2.5 py-1.5 rounded-lg transition cursor-pointer text-xs">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400 text-xs">Belum ada pengumuman yang diterbitkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100">
                {{ $announcements->links() }}
            </div>
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
