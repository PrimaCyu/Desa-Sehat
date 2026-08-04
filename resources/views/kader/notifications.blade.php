@extends('layouts.app')

@section('title', 'Kirim Notifikasi')

@section('content')
<!-- Hero Header Card (Ultra-Clean Responsive SaaS Banner) -->
<div class="bg-white border border-slate-100 rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-xs mb-5 sm:mb-6 relative overflow-hidden">
    <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-50 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="flex items-center justify-between gap-3 relative z-10">
        <!-- Icon & Titles -->
        <div class="flex items-center gap-3 min-w-0">
            <div class="p-2.5 sm:p-3 bg-emerald-500 text-white rounded-xl sm:rounded-2xl shadow-xs shrink-0">
                <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M18.364 5.636a9 9 0 010 12.728M12 18a6 6 0 100-12 6 6 0 000 12z"></path></svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2">
                    <h2 class="text-base sm:text-xl font-extrabold text-slate-800 tracking-tight truncate">Kirim Notifikasi Acara & Pesan</h2>
                    <span class="hidden sm:inline-block text-[10px] font-extrabold text-emerald-700 bg-emerald-50 border border-emerald-100 px-2.5 py-0.5 rounded-full uppercase">Pusat Pesan</span>
                </div>
                <p class="text-[11px] sm:text-xs text-slate-500 font-medium mt-0.5 truncate sm:whitespace-normal">Kirim pesan langsung ke keluarga terdaftar atau buat pengumuman terjadwal se-desa</p>
            </div>
        </div>

        <!-- Quick Right Stat Badge -->
        <div class="shrink-0">
            <div class="bg-slate-50 border border-slate-150 px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl sm:rounded-2xl text-right">
                <span class="hidden sm:block text-[10px] text-slate-400 font-bold uppercase">Total Pesan</span>
                <span class="text-xs sm:text-sm font-extrabold text-emerald-600 leading-none whitespace-nowrap">{{ $notifications->total() }} <span class="hidden sm:inline">Notifikasi</span><span class="sm:hidden">Pesan</span></span>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- List Notifications (2/3 width) -->
    <div class="lg:col-span-2 space-y-4">
        
        <!-- Main Card Container -->
        <div class="bg-white border border-slate-100 rounded-3xl p-4 sm:p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                <div class="flex items-center gap-2 min-w-0">
                    <div class="p-1.5 sm:p-2 bg-emerald-50 text-emerald-600 rounded-xl shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-xs sm:text-sm font-extrabold text-slate-800 truncate">Riwayat Notifikasi Terkirim</h3>
                        <p class="text-[9.5px] sm:text-[10px] text-slate-400 truncate">Daftar pemberitahuan langsung maupun terjadwal</p>
                    </div>
                </div>
                <span class="text-[10px] sm:text-[11px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 sm:px-2.5 rounded-full shrink-0">
                    Total: {{ $notifications->total() }}
                </span>
            </div>

            <!-- List Notifications (Feed Style) -->
            <div class="space-y-3">
                @forelse($notifications as $n)
                    @php
                        $isScheduled = $n->waktu_kirim && $n->waktu_kirim->isFuture();
                    @endphp
                    <div class="p-3.5 sm:p-4 {{ $isScheduled ? 'bg-amber-50/40 border-amber-200/80 hover:bg-amber-50/70' : 'bg-slate-50/70 hover:bg-emerald-50/30 border-slate-150 hover:border-emerald-200' }} rounded-2xl border transition-all duration-200 space-y-3 sm:space-y-0 sm:flex sm:items-center sm:justify-between sm:gap-4 group">
                        <!-- Main Content Info -->
                        <div class="min-w-0 flex-1 space-y-1">
                            <div class="flex items-center gap-1.5 flex-wrap text-[10px] sm:text-[10.5px]">
                                <!-- Target Badge -->
                                @if($n->penerima)
                                    <span class="font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-md flex items-center gap-1">
                                        <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        Personal: Bpk. {{ $n->penerima->kepala_keluarga }}
                                    </span>
                                @else
                                    <span class="font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md flex items-center gap-1">
                                        <svg class="w-3 h-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                        Broadcast (Semua Warga)
                                    </span>
                                @endif

                                <!-- Status Pengiriman (Langsung vs Terjadwal) -->
                                @if($isScheduled)
                                    <span class="font-bold text-amber-700 bg-amber-100/80 border border-amber-200 px-2 py-0.5 rounded-md flex items-center gap-1 animate-pulse">
                                        <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        ⏰ Terjadwal: {{ $n->waktu_kirim->format('d M Y H:i') }} WIB
                                    </span>
                                @else
                                    <span class="font-semibold text-slate-400 flex items-center gap-1">
                                        <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        {{ $n->waktu_kirim ? $n->waktu_kirim->format('d M Y H:i') : $n->created_at->format('d M Y H:i') }} WIB
                                    </span>
                                @endif
                            </div>

                            <h4 class="font-extrabold text-slate-800 text-xs sm:text-sm leading-snug group-hover:text-emerald-700 transition">
                                {{ $n->judul }}
                            </h4>

                            <p class="text-[11px] sm:text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                {{ $n->pesan }}
                            </p>
                        </div>

                        <!-- Action Buttons Row -->
                        <div class="flex items-center justify-end gap-2 pt-2.5 sm:pt-0 border-t sm:border-t-0 border-slate-200/60 shrink-0">
                            <button type="button" 
                                onclick="openNotificationDetailModal('{{ addslashes($n->judul) }}', '{{ addslashes($n->penerima ? 'Bpk. ' . $n->penerima->kepala_keluarga : 'Semua Warga (Broadcast)') }}', '{{ $isScheduled ? 'Terjadwal: ' . $n->waktu_kirim->format('d M Y H:i') . ' WIB' : ($n->waktu_kirim ? $n->waktu_kirim->format('d M Y H:i') : $n->created_at->format('d M Y H:i')) . ' WIB' }}', '{{ addslashes($n->pesan) }}')" 
                                class="bg-white hover:bg-slate-100 text-slate-700 font-bold px-3 py-1.5 rounded-xl border border-slate-200 text-xs shadow-2xs transition cursor-pointer flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Detail Pesan
                            </button>

                            <form action="{{ route('kader.notifications.delete', $n->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus/membatalkan notifikasi ini?')" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold px-3 py-1.5 rounded-xl border border-rose-200 text-xs transition cursor-pointer" title="Hapus / Batal">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 border border-dashed border-slate-200 rounded-2xl">
                        <div class="inline-flex bg-slate-100 text-slate-400 p-3 rounded-full mb-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <h5 class="font-bold text-slate-600 text-xs">Belum ada notifikasi yang terkirim / terjadwal</h5>
                        <p class="text-[10px] text-slate-400 mt-0.5">Gunakan formulir di sebelah kanan untuk membuat notifikasi baru.</p>
                    </div>
                @endforelse
            </div>

            @if($notifications->hasPages())
                <div class="mt-4 pt-4 border-t border-slate-100">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- Send Notification Panel (Form Card) -->
    <div>
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs relative lg:sticky lg:top-20 space-y-4">
            <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-800">Buat Notifikasi Baru</h3>
                    <p class="text-[10.5px] text-slate-400 font-medium">Kirim langsung atau atur jadwal otomatis</p>
                </div>
            </div>

            <!-- Quick Preset Template Chips -->
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Template Pesan Cepat (1-Klik):</span>
                <div class="flex flex-wrap gap-1.5">
                    <button type="button" onclick="applyTemplate('Pengingat Imunisasi Rutin', 'Dihimbau kepada seluruh keluarga yang memiliki Bayi/Balita untuk membawa anak imunisasi dan timbang bulanan di Posyandu esok hari.')" 
                        class="text-[10px] font-bold bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 border border-slate-200 px-2.5 py-1 rounded-lg transition cursor-pointer">
                        💉 Imunisasi Rutin
                    </button>
                    <button type="button" onclick="applyTemplate('Kontrol Kesehatan Lansia', 'Diberitahukan bagi warga lansia untuk hadir mengikuti pemeriksaan tensi darah dan gula darah gratis di Posyandu.')" 
                        class="text-[10px] font-bold bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 border border-slate-200 px-2.5 py-1 rounded-lg transition cursor-pointer">
                        🩺 Kontrol Lansia
                    </button>
                    <button type="button" onclick="applyTemplate('Pemeriksaan Ibu Hamil', 'Diingatkan bagi Ibu Hamil untuk rutin memeriksakan lingkar lengan dan meminum Tablet Tambah Darah (TTD).')" 
                        class="text-[10px] font-bold bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 border border-slate-200 px-2.5 py-1 rounded-lg transition cursor-pointer">
                        🤰 Ibu Hamil
                    </button>
                </div>
            </div>

            <form action="{{ route('kader.notifications.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="family_id" class="block text-[10.5px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Target Penerima</label>
                    <select id="family_id" name="family_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-hidden transition cursor-pointer">
                        <option value="">Semua Warga (Kirim Massal / Broadcast)</option>
                        @foreach($families as $family)
                            <option value="{{ $family->id }}">
                                Keluarga {{ $family->kepala_keluarga }} (KK: {{ $family->username }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Mode Pengiriman: Langsung vs Terjadwal -->
                <div>
                    <label class="block text-[10.5px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Waktu Pengiriman</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center gap-2 p-2.5 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer hover:bg-emerald-50/50 transition text-xs font-bold text-slate-700">
                            <input type="radio" name="send_type" value="now" checked onclick="toggleScheduleInput(false)" class="text-emerald-600 focus:ring-emerald-500">
                            <span>⚡ Langsung</span>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer hover:bg-emerald-50/50 transition text-xs font-bold text-slate-700">
                            <input type="radio" name="send_type" value="scheduled" onclick="toggleScheduleInput(true)" class="text-emerald-600 focus:ring-emerald-500">
                            <span>⏰ Terjadwal</span>
                        </label>
                    </div>

                    <!-- Hidden Datetime Input -->
                    <div id="schedule_datetime_container" class="hidden mt-2.5">
                        <label for="send_at" class="block text-[10px] font-bold text-amber-700 mb-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Pilih Tanggal & Jam Kirim Otomatis:
                        </label>
                        <input type="datetime-local" id="send_at" name="send_at" 
                            class="w-full px-3.5 py-2.5 bg-amber-50/60 border border-amber-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-amber-500 focus:bg-white transition font-medium">
                    </div>
                </div>

                <div>
                    <label for="title" class="block text-[10.5px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Judul Notifikasi</label>
                    <input type="text" id="title" name="title" required value="{{ old('title') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-hidden transition placeholder:text-slate-400" 
                        placeholder="Contoh: Pengingat Imunisasi Rutin">
                </div>
                <div>
                    <label for="message" class="block text-[10.5px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Isi Pesan Notifikasi</label>
                    <textarea id="message" name="message" required rows="4"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-hidden transition leading-relaxed placeholder:text-slate-400" 
                        placeholder="Tulis pesan lengkap yang ingin dikirimkan..."></textarea>
                </div>

                <button type="submit" class="w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold shadow-xs transition cursor-pointer flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Simpan & Proses Notifikasi
                </button>
            </form>
        </div>
    </div>

</div>

<!-- Notification Detail Modal Layer -->
<div id="notificationDetailModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-slate-100 p-6 space-y-4 animate-scaleUp">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <span id="modalNotifRecipient" class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100">
                Broadcast (Semua Warga)
            </span>
            <button onclick="closeNotificationDetailModal()" class="text-slate-400 hover:text-slate-700 hover:bg-slate-100 p-1.5 rounded-full transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="space-y-2">
            <span id="modalNotifDate" class="text-[10px] text-slate-400 font-bold block">04 Aug 2026 09:54 WIB</span>
            <h3 id="modalNotifTitle" class="text-base font-extrabold text-slate-800 leading-snug">Judul Notifikasi</h3>
        </div>

        <div class="p-4 bg-slate-50 border border-slate-150 rounded-2xl">
            <p id="modalNotifMessage" class="text-xs text-slate-700 leading-relaxed whitespace-pre-line"></p>
        </div>

        <div class="pt-2">
            <button onclick="closeNotificationDetailModal()" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs py-2.5 rounded-xl transition cursor-pointer">
                Tutup Detail Pesan
            </button>
        </div>
    </div>
</div>

<script>
    function openNotificationDetailModal(title, recipient, date, message) {
        document.getElementById('modalNotifTitle').innerText = title;
        document.getElementById('modalNotifRecipient').innerText = recipient;
        document.getElementById('modalNotifDate').innerText = date;
        document.getElementById('modalNotifMessage').innerText = message;
        document.getElementById('notificationDetailModal').classList.remove('hidden');
    }

    function closeNotificationDetailModal() {
        document.getElementById('notificationDetailModal').classList.add('hidden');
    }

    function applyTemplate(title, message) {
        document.getElementById('title').value = title;
        document.getElementById('message').value = message;
    }

    function toggleScheduleInput(show) {
        const container = document.getElementById('schedule_datetime_container');
        const sendAtInput = document.getElementById('send_at');
        if (show) {
            container.classList.remove('hidden');
            sendAtInput.required = true;
            // Set default to tomorrow at 07:00 AM
            const now = new Date();
            now.setDate(now.getDate() + 1);
            now.setHours(7, 0, 0, 0);
            
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            
            sendAtInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
        } else {
            container.classList.add('hidden');
            sendAtInput.required = false;
            sendAtInput.value = '';
        }
    }
</script>
@endsection
