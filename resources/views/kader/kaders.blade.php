@extends('layouts.app')

@section('title', 'Kelola Akun Kader Posyandu')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-emerald-800 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden" style="background: linear-gradient(135deg, #047857 0%, #0f766e 50%, #0e7490 100%);">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
        
        <div class="relative z-10 space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-bold text-emerald-100 tracking-wide uppercase">
                <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Akses Khusus Kader Utama
            </div>
            <h1 class="text-2xl sm:text-3xl font-black">Kelola Akun Kader Posyandu</h1>
            <p class="text-xs sm:text-sm text-emerald-100/90 max-w-xl">
                Tambah, atur, dan pantau akun kader yang bertugas di wilayah Posyandu DesaSehat.
            </p>
        </div>

        <div class="relative z-10">
            <button onclick="openModalTambahKader()" class="inline-flex items-center gap-2 bg-emerald-400 hover:bg-emerald-300 text-slate-950 font-extrabold text-xs sm:text-sm px-5 py-3 rounded-2xl shadow-lg hover:shadow-emerald-400/30 transition-all duration-200 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Kader Baru
            </button>
        </div>
    </div>

    <!-- Error Validation Alert -->
    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 shadow-xs">
            <div class="flex items-center gap-2 font-bold text-sm mb-1">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Gagal Menambahkan Akun Kader:
            </div>
            <ul class="list-disc list-inside text-xs space-y-0.5 text-rose-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Cards Grid & Table Section -->
    <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-xs space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Daftar Kader Bertugas</h2>
                <p class="text-xs text-slate-500">Total terdaftar: <span class="font-bold text-emerald-600">{{ $kaders->count() }} Kader</span></p>
            </div>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-100">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-4">Kader / Nama Lengkap</th>
                        <th class="px-5 py-4">Username Login</th>
                        <th class="px-5 py-4">Kontak / Email</th>
                        <th class="px-5 py-4">Status & Hak Akses</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @foreach($kaders as $kader)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-xs {{ $kader->isKaderUtama() ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-emerald-100 text-emerald-800 border border-emerald-200' }}">
                                        {{ strtoupper(substr($kader->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 leading-snug">{{ $kader->name }}</p>
                                        <p class="text-[11px] text-slate-400">Terdaftar: {{ $kader->created_at ? $kader->created_at->format('d M Y') : '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 font-mono font-bold text-xs text-emerald-700 bg-emerald-50/50 px-2.5 py-1 rounded-lg inline-block my-3">
                                {{ $kader->username }}
                            </td>
                            <td class="px-5 py-4 text-xs space-y-0.5">
                                <p class="font-semibold text-slate-700">{{ $kader->nomor_telepon ?? '-' }}</p>
                                <p class="text-slate-400">{{ $kader->email ?? '-' }}</p>
                            </td>
                            <td class="px-5 py-4">
                                @if($kader->isKaderUtama())
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200/80 rounded-full text-xs font-extrabold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Kader Utama / Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200/80 rounded-full text-xs font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Kader Posyandu
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if(!$kader->isKaderUtama() && $kader->id !== auth()->id())
                                    <form action="{{ route('kader.kaders.delete', $kader->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun kader {{ $kader->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-xl transition cursor-pointer" title="Hapus Akun Kader">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-300 italic">Utama</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Kader Baru -->
<div id="modalTambahKader" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-slate-100 p-6 sm:p-8 animate-scaleUp space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-lg font-black text-slate-900">Tambah Akun Kader Baru</h3>
                <p class="text-xs text-slate-500">Buatkan akun resmi untuk kader bertugas.</p>
            </div>
            <button onclick="closeModalTambahKader()" class="text-slate-400 hover:text-slate-700 hover:bg-slate-100 p-2 rounded-full transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('kader.kaders.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Nama Lengkap & Gelar *</label>
                <input type="text" name="name" required placeholder="Contoh: Kader Ibu Tutik, A.Md.Keb"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Username Login *</label>
                    <input type="text" name="username" required placeholder="Contoh: kader_tutik"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Password Login *</label>
                    <input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Email *</label>
                <input type="email" name="email" required placeholder="Contoh: tutik@desasehat.go.id"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">No. WhatsApp / Telepon</label>
                <input type="text" name="nomor_telepon" placeholder="Contoh: 081234567890"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="closeModalTambahKader()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-extrabold shadow-md shadow-emerald-200 transition cursor-pointer">
                    Simpan & Buat Akun
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModalTambahKader() {
        document.getElementById('modalTambahKader').classList.remove('hidden');
    }
    function closeModalTambahKader() {
        document.getElementById('modalTambahKader').classList.add('hidden');
    }
</script>
@endsection
