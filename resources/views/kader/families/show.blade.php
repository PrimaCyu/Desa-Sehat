@extends('layouts.app')

@section('title', 'Keluarga Bapak ' . $family->kepala_keluarga)

@section('content')
<!-- Header Back Button -->
<div class="flex items-center justify-between mb-6">
    <a href="{{ route('kader.families.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 px-3.5 py-2 rounded-xl transition shadow-xs">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Daftar Keluarga
    </a>
</div>

<!-- Family Profile Card -->
<div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs relative overflow-hidden mb-6">
    <div class="absolute -top-12 -right-12 w-32 h-32 bg-emerald-50 rounded-full blur-2xl"></div>
    <div class="relative z-10 grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
        <div class="flex items-center gap-4 md:col-span-2">
            <div class="bg-emerald-50 text-emerald-600 h-14 w-14 rounded-2xl flex items-center justify-center font-bold text-lg border border-emerald-100 shrink-0">
                {{ substr($family->kepala_keluarga, 0, 2) }}
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">Keluarga Bapak {{ $family->kepala_keluarga }}</h2>
                <p class="text-xs text-slate-400 mt-0.5">No. KK: <span class="text-slate-600 font-semibold">{{ $family->username }}</span></p>
                <p class="text-xs text-slate-400 mt-0.5">HP/WhatsApp: <span class="text-slate-600 font-semibold">{{ $family->nomor_telepon }}</span></p>
            </div>
        </div>
        <div class="md:col-span-2 text-left md:text-right">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Alamat Rumah</span>
            <span class="text-xs text-slate-605 font-medium mt-1 inline-block">{{ $family->alamat }}</span>
        </div>
    </div>
</div>

<!-- Main Area Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Members Panel (2/3 width) -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- List Members Card -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs">
            <h3 class="text-base font-bold text-slate-800 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Daftar Anggota Keluarga
            </h3>

            <div class="space-y-4">
                @forelse($members as $m)
                    <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-4 space-y-4 transition hover:border-emerald-100">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="bg-emerald-50 text-emerald-600 h-10 w-10 rounded-full flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ substr($m->nama, 0, 2) }}
                                </div>
                                <div class="space-y-0.5">
                                    <h4 class="font-bold text-slate-800 text-xs flex items-center gap-2">
                                        {{ $m->nama }}
                                        <span class="text-[9px] font-semibold text-slate-400 bg-slate-200/50 px-1.5 py-0.2 rounded-full">
                                            {{ $m->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </span>
                                        @if($m->status_verifikasi === 'pending')
                                            <span class="bg-amber-100 text-amber-800 text-[8px] font-bold px-1.5 py-0.5 rounded-full uppercase">Pending</span>
                                        @elseif($m->status_verifikasi === 'ditolak')
                                            <span class="bg-rose-100 text-rose-800 text-[8px] font-bold px-1.5 py-0.5 rounded-full uppercase">Ditolak</span>
                                        @endif
                                    </h4>
                                    <p class="text-[10px] text-slate-400">
                                        NIK: <span class="text-slate-600 font-semibold">{{ $m->nik }}</span> &bull; Lahir: <span class="text-slate-600">{{ $m->tanggal_lahir->format('d/m/Y') }}</span> ({{ $m->umur }})
                                    </p>
                                    <span class="inline-block text-[9px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-full mt-1">
                                        {{ $m->kategori_formatted }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                @if($m->status_verifikasi === 'pending')
                                    <!-- Verify Actions -->
                                    <form action="{{ route('kader.members.verify', [$m->id, 'disetujui']) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-[10px] px-3.5 py-2 rounded-xl transition shadow-xs cursor-pointer">
                                            Setujui
                                        </button>
                                    </form>
                                    <form action="{{ route('kader.members.verify', [$m->id, 'ditolak']) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menolak pendaftaran ini?')">
                                        @csrf
                                        <button type="submit" class="bg-white hover:bg-rose-50 border border-slate-200 hover:border-rose-250 text-rose-550 text-[10px] font-bold px-3.5 py-2 rounded-xl transition cursor-pointer">
                                            Tolak
                                        </button>
                                    </form>
                                @else
                                    @if($m->status_verifikasi === 'disetujui')
                                        <!-- Record Health Checkup Button -->
                                        <a href="{{ route('kader.health.check', $m->id) }}" 
                                            class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-[10px] px-3.5 py-2 rounded-xl transition shadow-xs flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-9-4h18"></path></svg>
                                            Catat Pemeriksaan
                                        </a>
                                    @endif

                                    <!-- Edit Category Action -->
                                    <button onclick="openEditModal({{ $m->id }}, '{{ addslashes($m->nama) }}', '{{ $m->jenis_kelamin }}', '{{ $m->tanggal_lahir->format('Y-m-d') }}', '{{ $m->kategori }}')" 
                                        class="bg-white hover:bg-slate-50 border border-slate-200 hover:border-slate-350 text-slate-600 text-[10px] font-bold px-3 py-2 rounded-xl transition cursor-pointer">
                                        Edit
                                    </button>

                                    <!-- Delete Action -->
                                    <form action="{{ route('kader.members.delete', $m->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota keluarga ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-white hover:bg-rose-50 border border-slate-200 hover:border-rose-200 text-rose-500 hover:text-rose-600 text-[10px] font-bold px-3 py-2 rounded-xl transition cursor-pointer">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-slate-400 text-xs">Belum ada anggota keluarga terdaftar.</div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Add Member Panel -->
    <div>
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs sticky top-20">
            <div class="flex items-center gap-2.5 mb-6">
                <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Tambah Anggota</h3>
            </div>

            <form action="{{ route('kader.members.store', $family->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="nik" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">NIK (Nomor Induk Kependudukan)</label>
                    <input type="text" id="nik" name="nik" required maxlength="16" minlength="16" value="{{ old('nik') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition" 
                        placeholder="16 digit angka NIK">
                </div>
                <div>
                    <label for="name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Anggota Keluarga</label>
                    <input type="text" id="name" name="name" required value="{{ old('name') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition" 
                        placeholder="Nama Lengkap">
                </div>
                <div>
                    <label for="gender" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Jenis Kelamin</label>
                    <select id="gender" name="gender" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition cursor-pointer">
                        <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label for="birth_date" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Lahir</label>
                    <input type="date" id="birth_date" name="birth_date" required value="{{ old('birth_date') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
                </div>
                <div>
                    <label for="category" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Kategori Kesehatan</label>
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

                <button type="submit" class="w-full py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl text-xs font-semibold shadow-md shadow-emerald-100 hover:shadow-lg transition cursor-pointer">
                    Simpan Anggota
                </button>
            </form>
        </div>
    </div>

</div>

<!-- Modal Form Edit Member -->
<div id="edit-member-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-xs" onclick="closeEditModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-100">
            <div class="bg-white p-6 relative">
                
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2 mb-6">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Ubah Profil Anggota
                </h3>

                <form id="edit-member-form" action="" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="edit_name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Anggota</label>
                        <input type="text" id="edit_name" name="name" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
                    </div>
                    <div>
                        <label for="edit_gender" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Jenis Kelamin</label>
                        <select id="edit_gender" name="gender" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label for="edit_birth_date" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Lahir</label>
                        <input type="date" id="edit_birth_date" name="birth_date" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
                    </div>
                    <div>
                        <label for="edit_category" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Kategori Kesehatan</label>
                        <select id="edit_category" name="category" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
                            <option value="bayi">Bayi (&lt; 12 Bulan)</option>
                            <option value="balita">Balita (1 - 5 Tahun)</option>
                            <option value="anak">Anak-Anak</option>
                            <option value="remaja">Remaja</option>
                            <option value="dewasa">Dewasa</option>
                            <option value="ibu_hamil">Ibu Hamil</option>
                            <option value="lansia">Lansia</option>
                        </select>
                    </div>

                    <div class="flex gap-2.5 pt-4">
                        <button type="button" onclick="closeEditModal()" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition cursor-pointer">
                            Batalkan
                        </button>
                        <button type="submit" class="flex-1 py-3 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs rounded-xl transition shadow-xs cursor-pointer">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
