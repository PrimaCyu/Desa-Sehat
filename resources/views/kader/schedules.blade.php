@extends('layouts.app')

@section('title', 'Kelola Jadwal Posyandu')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Jadwal Kegiatan & Pelayanan Posyandu</h2>
        <p class="text-xs text-slate-400">Atur kalender kegiatan Posyandu DesaSehat</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- List Schedules Panel (2/3 width) -->
    <div class="lg:col-span-2 space-y-4">
        
        <!-- Table Card -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 font-semibold">
                            <th class="pb-3">Tanggal</th>
                            <th class="pb-3">Judul Kegiatan</th>
                            <th class="pb-3">Tempat</th>
                            <th class="pb-3">Waktu</th>
                            <th class="pb-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($schedules as $s)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 font-bold text-slate-500 whitespace-nowrap">
                                    {{ $s->tanggal_kegiatan->format('d M Y') }}
                                </td>
                                <td class="py-3.5">
                                    <span class="font-bold text-slate-700 block">{{ $s->judul }}</span>
                                    @if($s->deskripsi)
                                        <span class="text-[10px] text-slate-400 block mt-0.5 max-w-[200px] truncate" title="{{ $s->deskripsi }}">{{ $s->deskripsi }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 text-slate-600 font-medium">{{ $s->tempat }}</td>
                                <td class="py-3.5 text-slate-500 font-bold whitespace-nowrap">
                                    {{ substr($s->jam_mulai, 0, 5) }} - {{ substr($s->jam_selesai, 0, 5) }} WIB
                                </td>
                                <td class="py-3.5 text-right">
                                    <form action="{{ route('kader.schedules.delete', $s->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal kegiatan ini?')">
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
                                <td colspan="5" class="py-8 text-center text-slate-400 text-xs">Belum ada agenda jadwal kegiatan yang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100">
                {{ $schedules->links() }}
            </div>
        </div>

    </div>

    <!-- Create Schedule Panel -->
    <div>
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs sticky top-22">
            <div class="flex items-center gap-2.5 mb-6">
                <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Buat Jadwal Baru</h3>
            </div>

            <form action="{{ route('kader.schedules.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="title" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Kegiatan</label>
                    <input type="text" id="title" name="title" required value="{{ old('title') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition" 
                        placeholder="Contoh: Posyandu Balita & Imunisasi">
                </div>
                <div>
                    <label for="event_date" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Kegiatan</label>
                    <input type="date" id="event_date" name="event_date" required value="{{ old('event_date') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="start_time" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Jam Mulai</label>
                        <input type="time" id="start_time" name="start_time" required value="{{ old('start_time', '08:00') }}"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
                    </div>
                    <div>
                        <label for="end_time" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Jam Selesai</label>
                        <input type="time" id="end_time" name="end_time" required value="{{ old('end_time', '12:00') }}"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
                    </div>
                </div>
                <div>
                    <label for="location" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tempat Kegiatan</label>
                    <input type="text" id="location" name="location" required value="{{ old('location') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition" 
                        placeholder="Contoh: Balai Desa RW 02">
                </div>
                <div>
                    <label for="description" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Deskripsi Tambahan</label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition" 
                        placeholder="Keterangan pendukung jika ada...">{{ old('description') }}</textarea>
                </div>

                <button type="submit" class="w-full py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl text-xs font-semibold shadow-md shadow-emerald-100 hover:shadow-lg transition cursor-pointer">
                    Simpan Jadwal Kegiatan
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
