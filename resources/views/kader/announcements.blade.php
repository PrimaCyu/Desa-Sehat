@extends('layouts.app')

@section('title', 'Kelola Pengumuman Posyandu')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Papan Pengumuman Posyandu</h2>
        <p class="text-xs text-slate-400">Publikasikan pengumuman penting bagi warga</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- List Announcements (2/3 width) -->
    <div class="lg:col-span-2 space-y-4">
        
        <!-- Table Card -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 font-semibold">
                            <th class="pb-3">Tanggal Terbit</th>
                            <th class="pb-3">Judul Pengumuman</th>
                            <th class="pb-3">Pembuat</th>
                            <th class="pb-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($announcements as $a)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 font-bold text-slate-500 whitespace-nowrap">
                                    {{ $a->tanggal_terbit ? $a->tanggal_terbit->format('d M Y') : $a->created_at->format('d M Y') }}
                                </td>
                                <td class="py-3.5 pr-4">
                                    <span class="font-bold text-slate-700 block mb-1">{{ $a->judul }}</span>
                                    <p class="text-[10px] text-slate-500 font-medium leading-relaxed bg-slate-50/30 p-2.5 border border-slate-100 rounded-xl max-w-md">
                                        {{ Str::limit($a->konten, 150) }}
                                    </p>
                                </td>
                                <td class="py-3.5 text-slate-600 font-semibold whitespace-nowrap">
                                    {{ $a->pembuat ? $a->pembuat->name : 'Sistem' }}
                                </td>
                                <td class="py-3.5 text-right whitespace-nowrap">
                                    <form action="{{ route('kader.announcements.delete', $a->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-white hover:bg-rose-50 border border-slate-200 hover:border-rose-200 text-rose-500 hover:text-rose-600 font-bold px-2.5 py-1.5 rounded-lg transition cursor-pointer">
                                            Hapus
                                        </button>
                                    </form>
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
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs sticky top-22">
            <div class="flex items-center gap-2.5 mb-6">
                <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Tulis Pengumuman Baru</h3>
            </div>

            <form action="{{ route('kader.announcements.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="title" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Judul Pengumuman</label>
                    <input type="text" id="title" name="title" required value="{{ old('title') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition" 
                        placeholder="Contoh: Pemberian Imunisasi Polio Tambahan">
                </div>
                <div>
                    <label for="content" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Isi Pengumuman</label>
                    <textarea id="content" name="content" required rows="6"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition leading-relaxed" 
                        placeholder="Tuliskan berita lengkap pengumuman untuk warga...">{{ old('content') }}</textarea>
                </div>

                <button type="submit" class="w-full py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl text-xs font-semibold shadow-md shadow-emerald-100 hover:shadow-lg transition cursor-pointer">
                    Publikasikan Pengumuman
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
