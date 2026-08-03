@extends('layouts.app')

@section('title', 'Detail Keluarga Bapak ' . $family->kepala_keluarga)

@section('content')
<div class="space-y-6">
    <!-- Header Back Button & Page Path -->
    <div class="flex items-center justify-between">
        <a href="{{ route('kader.families.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 px-4 py-2.5 rounded-2xl transition shadow-xs hover:border-slate-300">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Keluarga
        </a>
    </div>

    <!-- Compact Formal Family Profile Card Header -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3.5 mb-3.5">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
                <div class="min-w-0">
                    <span class="text-[9px] font-extrabold uppercase tracking-widest text-emerald-700 block mb-0.5">Profil Akun Keluarga</span>
                    <h2 class="text-sm sm:text-base font-extrabold text-slate-800 leading-tight truncate sm:whitespace-normal">Keluarga Bapak {{ $family->kepala_keluarga }}</h2>
                </div>
            </div>
            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100 self-start sm:self-center shrink-0">
                Terverifikasi Aktif
            </span>
        </div>

        <!-- Compact Info Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 text-xs">
            <div class="bg-slate-50/80 border border-slate-100 rounded-xl p-2.5">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">No. KK (Username)</span>
                <span class="font-bold text-slate-800 font-mono text-xs block mt-0.5">{{ $family->username }}</span>
            </div>
            <div class="bg-slate-50/80 border border-slate-100 rounded-xl p-2.5">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">No. HP / WhatsApp</span>
                <span class="font-bold text-slate-800 font-mono text-xs block mt-0.5">{{ $family->nomor_telepon ?? '-' }}</span>
            </div>
            <div class="bg-slate-50/80 border border-slate-100 rounded-xl p-2.5">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Alamat Rumah</span>
                <span class="font-semibold text-slate-700 text-xs block leading-snug mt-0.5">{{ $family->alamat }}</span>
            </div>
        </div>
    </div>

    <!-- Main Content Area Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Column Left: Members List Panel (2/3 width) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- List Members Card -->
            <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-xs space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-800">Daftar Anggota Keluarga</h3>
                            <p class="text-xs text-slate-400">Total terdaftar: <strong class="text-slate-600 font-bold">{{ $members->count() }} Anggota</strong></p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($members as $m)
                        <div class="bg-slate-50/70 border border-slate-200/60 hover:border-emerald-300 rounded-2xl p-5 transition-all duration-200 space-y-4">
                            <!-- Top Row: Identitas & Badges -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200/50 pb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200 flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($m->nama, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-extrabold text-slate-900 text-sm tracking-tight">{{ $m->nama }}</h4>
                                            <span class="text-[10px] font-bold text-slate-600 bg-white border border-slate-200 px-2 py-0.5 rounded-full">
                                                {{ $m->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                            </span>
                                        </div>
                                        <span class="text-[10px] font-extrabold text-emerald-700 bg-emerald-50 border border-emerald-100 px-2.5 py-0.5 rounded-full mt-1 inline-block uppercase tracking-wider">
                                            {{ $m->kategori_formatted }}
                                        </span>
                                    </div>
                                </div>

                                <div>
                                    @if($m->status_verifikasi === 'pending')
                                        <span class="bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold px-3 py-1 rounded-full uppercase inline-flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Menunggu Verifikasi
                                        </span>
                                    @elseif($m->status_verifikasi === 'ditolak')
                                        <span class="bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold px-3 py-1 rounded-full uppercase inline-flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Pendaftaran Ditolak
                                        </span>
                                    @else
                                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold px-3 py-1 rounded-full uppercase inline-flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Terverifikasi Aktif
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Bottom Row: Metadata & Formal Action Bar -->
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <!-- Key-Value Metadata Grid -->
                                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-xs text-slate-600">
                                    <div>
                                        <span class="text-slate-400 font-medium block text-[10px] uppercase tracking-wider">NIK Kependudukan</span>
                                        <span class="font-bold font-mono text-slate-800">{{ $m->nik }}</span>
                                    </div>
                                    <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>
                                    <div>
                                        <span class="text-slate-400 font-medium block text-[10px] uppercase tracking-wider">Tanggal Lahir & Usia</span>
                                        <span class="font-semibold text-slate-800">{{ $m->tanggal_lahir->format('d/m/Y') }} <span class="text-slate-500">({{ $m->umur }})</span></span>
                                    </div>
                                </div>

                                <!-- Action Buttons Bar -->
                                <div class="flex items-center gap-2 shrink-0 pt-2 md:pt-0">
                                    @if($m->status_verifikasi === 'pending')
                                        <form action="{{ route('kader.members.verify', [$m->id, 'disetujui']) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition shadow-xs cursor-pointer">
                                                Setujui
                                            </button>
                                        </form>
                                        <form action="{{ route('kader.members.verify', [$m->id, 'ditolak']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak pendaftaran ini?')">
                                            @csrf
                                            <button type="submit" class="bg-white hover:bg-rose-50 border border-slate-200 text-rose-600 font-bold text-xs px-4 py-2 rounded-xl transition cursor-pointer">
                                                Tolak
                                            </button>
                                        </form>
                                    @else
                                        @if($m->status_verifikasi === 'disetujui')
                                            <a href="{{ route('kader.health.check', $m->id) }}" 
                                                class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition shadow-sm shadow-emerald-100 flex items-center gap-1.5 cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-9-4h18"></path></svg>
                                                <span>Catat Pemeriksaan</span>
                                            </a>
                                        @endif

                                        <button onclick="openEditModal({{ $m->id }}, '{{ addslashes($m->nama) }}', '{{ $m->jenis_kelamin }}', '{{ $m->tanggal_lahir->format('Y-m-d') }}', '{{ $m->kategori }}')" 
                                            class="bg-white hover:bg-slate-100 border border-slate-200 text-slate-700 font-bold text-xs px-3.5 py-2.5 rounded-xl transition cursor-pointer flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            <span>Edit</span>
                                        </button>

                                        <form action="{{ route('kader.members.delete', $m->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota keluarga ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-white hover:bg-rose-50 border border-slate-200 text-rose-600 hover:text-rose-700 font-bold text-xs px-3.5 py-2.5 rounded-xl transition cursor-pointer flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-slate-400 text-xs">
                            Belum ada anggota keluarga terdaftar.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Column Right: Add Member Panel (1/3 width) -->
        <div>
            <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-xs sticky top-20 space-y-6">
                <div class="flex items-center gap-2.5 border-b border-slate-100 pb-4">
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    </div>
                    <h3 class="text-base font-black text-slate-800">Tambah Anggota</h3>
                </div>

                <form action="{{ route('kader.members.store', $family->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="nik" class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">NIK (Nomor Induk Kependudukan)</label>
                        <input type="text" id="nik" name="nik" required maxlength="16" minlength="16" value="{{ old('nik') }}"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition" 
                            placeholder="16 digit angka NIK">
                    </div>
                    <div>
                        <label for="name" class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Nama Anggota Keluarga</label>
                        <input type="text" id="name" name="name" required value="{{ old('name') }}"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition" 
                            placeholder="Nama Lengkap sesuai KTP/KIA">
                    </div>
                    <div>
                        <label for="gender" class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Jenis Kelamin</label>
                        <select id="gender" name="gender" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition cursor-pointer">
                            <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label for="birth_date" class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Tanggal Lahir</label>
                        <input type="date" id="birth_date" name="birth_date" required value="{{ old('birth_date') }}"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
                    </div>
                    <div>
                        <label for="category" class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Kategori Kesehatan</label>
                        <select id="category" name="category" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition cursor-pointer">
                            <option value="bayi" {{ old('category') === 'bayi' ? 'selected' : '' }}>Bayi (&lt; 12 Bulan)</option>
                            <option value="balita" {{ old('category') === 'balita' ? 'selected' : '' }}>Balita (1 - 5 Tahun)</option>
                            <option value="anak" {{ old('category') === 'anak' ? 'selected' : '' }}>Anak-Anak</option>
                            <option value="remaja" {{ old('category') === 'remaja' ? 'selected' : '' }}>Remaja</option>
                            <option value="dewasa" {{ old('category') === 'dewasa' ? 'selected' : '' }}>Dewasa</option>
                            <option value="ibu_hamil" {{ old('category') === 'ibu_hamil' ? 'selected' : '' }}>Ibu Hamil</option>
                            <option value="lansia" {{ old('category') === 'lansia' ? 'selected' : '' }}>Lansia</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl text-xs font-extrabold shadow-md shadow-emerald-100 hover:shadow-lg transition cursor-pointer">
                        Simpan Anggota
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<!-- Modal Form Edit Member -->
<div id="edit-member-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity duration-300" onclick="closeEditModal()"></div>
    
    <div class="bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-md border border-slate-100 relative z-10 p-6 sm:p-8 animate-scaleUp">
        <button onclick="closeEditModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 p-1 bg-slate-50 hover:bg-slate-100 rounded-full transition cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <h3 class="text-base font-black text-slate-800 flex items-center gap-2 mb-6">
            <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <span>Ubah Profil Anggota</span>
        </h3>

        <form id="edit-member-form" action="" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="edit_name" class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Nama Anggota</label>
                <input type="text" id="edit_name" name="name" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
            </div>
            <div>
                <label for="edit_gender" class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Jenis Kelamin</label>
                <select id="edit_gender" name="gender" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition cursor-pointer">
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
            <div>
                <label for="edit_birth_date" class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Tanggal Lahir</label>
                <input type="date" id="edit_birth_date" name="birth_date" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
            </div>
            <div>
                <label for="edit_category" class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Kategori Kesehatan</label>
                <select id="edit_category" name="category" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition cursor-pointer">
                    <option value="bayi">Bayi (&lt; 12 Bulan)</option>
                    <option value="balita">Balita (1 - 5 Tahun)</option>
                    <option value="anak">Anak-Anak</option>
                    <option value="remaja">Remaja</option>
                    <option value="dewasa">Dewasa</option>
                    <option value="ibu_hamil">Ibu Hamil</option>
                    <option value="lansia">Lansia</option>
                </select>
            </div>

            <div class="flex flex-col-reverse sm:flex-row gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()" class="w-full sm:w-1/3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition cursor-pointer">
                    Batalkan
                </button>
                <button type="submit" class="w-full sm:w-2/3 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl transition shadow-md shadow-emerald-100 cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openEditModal(id, name, gender, birthDate, category) {
        const modal = document.getElementById('edit-member-modal');
        const form = document.getElementById('edit-member-form');
        
        form.action = `/kader/members/${id}/update`;
        
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_gender').value = gender;
        document.getElementById('edit_birth_date').value = birthDate;
        document.getElementById('edit_category').value = category;

        modal.classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('edit-member-modal').classList.add('hidden');
    }
</script>
@endsection
