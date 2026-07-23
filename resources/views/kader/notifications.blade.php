@extends('layouts.app')

@section('title', 'Kirim Notifikasi')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Kirim Notifikasi Acara & Pesan</h2>
        <p class="text-xs text-slate-400">Kirim pesan langsung ke warga terpilih atau broadcast ke semua warga</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- List Notifications (2/3 width) -->
    <div class="lg:col-span-2 space-y-4">
        
        <!-- Table Card -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs">
            <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-4">Riwayat Notifikasi Terkirim</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 font-semibold">
                            <th class="pb-3">Waktu Kirim</th>
                            <th class="pb-3">Penerima</th>
                            <th class="pb-3">Judul Notifikasi</th>
                            <th class="pb-3">Pesan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($notifications as $n)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 font-bold text-slate-500 whitespace-nowrap">
                                    {{ $n->created_at->format('d M Y H:i') }} WIB
                                </td>
                                <td class="py-3.5 font-bold whitespace-nowrap text-slate-700">
                                    {{ $n->penerima ? 'Bpk. ' . $n->penerima->kepala_keluarga : 'Semua Keluarga (Broadcast)' }}
                                </td>
                                <td class="py-3.5 font-semibold text-slate-800 pr-4">{{ $n->judul }}</td>
                                <td class="py-3.5 text-slate-500 leading-relaxed max-w-[200px] truncate" title="{{ $n->pesan }}">
                                    {{ $n->pesan }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400 text-xs">Belum ada notifikasi yang terkirim.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100">
                {{ $notifications->links() }}
            </div>
        </div>

    </div>

    <!-- Send Notification Panel -->
    <div>
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs sticky top-20">
            <div class="flex items-center gap-2.5 mb-6">
                <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Kirim Notifikasi Baru</h3>
            </div>

            <form action="{{ route('kader.notifications.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="family_id" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Target Penerima</label>
                    <select id="family_id" name="family_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition cursor-pointer">
                        <option value="">Semua Warga (Kirim Massal / Broadcast)</option>
                        @foreach($families as $family)
                            <option value="{{ $family->id }}">
                                Keluarga {{ $family->kepala_keluarga }} (KK: {{ $family->username }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="title" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Judul Notifikasi</label>
                    <input type="text" id="title" name="title" required value="{{ old('title') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition" 
                        placeholder="Contoh: Pengingat Imunisasi Rutin">
                </div>
                <div>
                    <label for="message" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Isi Pesan Notifikasi</label>
                    <textarea id="message" name="message" required rows="5"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition leading-relaxed" 
                        placeholder="Tulis pesan lengkap yang ingin dikirimkan..."></textarea>
                </div>

                <button type="submit" class="w-full py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl text-xs font-semibold shadow-md shadow-emerald-100 hover:shadow-lg transition cursor-pointer">
                    Kirim Notifikasi
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
