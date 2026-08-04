@extends('layouts.app')

@section('title', 'Kelola Data Keluarga')

@section('content')
<!-- Hero Header Card (Ultra-Clean Responsive SaaS Banner) -->
<div class="bg-white border border-slate-100 rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-xs mb-5 sm:mb-6 relative overflow-hidden">
    <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-50 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="flex items-center justify-between gap-3 relative z-10">
        <!-- Icon & Titles -->
        <div class="flex items-center gap-3 min-w-0">
            <div class="p-2.5 sm:p-3 bg-emerald-500 text-white rounded-xl sm:rounded-2xl shadow-xs shrink-0">
                <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2">
                    <h2 class="text-base sm:text-xl font-extrabold text-slate-800 tracking-tight truncate">Daftar Akun Keluarga (KK)</h2>
                    <span class="hidden sm:inline-block text-[10px] font-extrabold text-emerald-700 bg-emerald-50 border border-emerald-100 px-2.5 py-0.5 rounded-full uppercase">Database KK</span>
                </div>
                <p class="text-[11px] sm:text-xs text-slate-500 font-medium mt-0.5 truncate sm:whitespace-normal">Kelola data kepala keluarga, registrasi akun warga, & pendaftaran anggota baru</p>
            </div>
        </div>

        <!-- Quick Right Stat Badge -->
        <div class="shrink-0">
            <div class="bg-slate-50 border border-slate-150 px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl sm:rounded-2xl text-right">
                <span class="hidden sm:block text-[10px] text-slate-400 font-bold uppercase">Total Terdaftar</span>
                <span class="text-xs sm:text-sm font-extrabold text-emerald-600 leading-none whitespace-nowrap">{{ $families->total() }} <span class="hidden sm:inline">Keluarga</span><span class="sm:hidden">KK</span></span>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- List Families Panel (2/3 width) -->
    <div class="lg:col-span-2 space-y-4">
        
        <!-- Search bar -->
        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-xs">
            <form action="{{ route('kader.families.index') }}" method="GET" class="flex gap-2">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="block w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-hidden transition placeholder:text-slate-400" 
                        placeholder="Cari berdasarkan No. KK, nama kepala keluarga, atau alamat...">
                </div>
                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs px-4 py-2.5 rounded-xl transition cursor-pointer">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('kader.families.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs px-3 py-2.5 rounded-xl transition flex items-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table Card (Clean & Minimalist Layout, Multi-Device Responsive) -->
        <div class="bg-white border border-slate-100 rounded-3xl p-4 sm:p-6 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse align-middle text-xs min-w-[620px]">
                    <thead>
                        <tr class="border-b border-slate-200/80 text-slate-500 font-semibold text-[11px] uppercase tracking-wider">
                            <th class="py-3 px-4 whitespace-nowrap">No. KK</th>
                            <th class="py-3 px-4">Kepala Keluarga</th>
                            <th class="py-3 px-4 w-full">Alamat</th>
                            <th class="py-3 px-4 whitespace-nowrap">Kontak</th>
                            <th class="py-3 px-4 text-right whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($families as $family)
                            <tr class="hover:bg-slate-50/60 transition-colors align-middle">
                                <!-- 1. No. KK -->
                                <td class="py-3.5 px-4 font-bold text-slate-700 align-middle whitespace-nowrap">
                                    <span class="truncate block" title="{{ $family->username }}">{{ $family->username }}</span>
                                </td>

                                <!-- 2. Kepala Keluarga -->
                                <td class="py-3.5 px-4 font-semibold text-slate-800 align-middle">
                                    <span class="truncate block" title="{{ $family->kepala_keluarga }}">{{ $family->kepala_keluarga }}</span>
                                    <span class="text-[10px] text-slate-400 block font-normal">{{ $family->anggota_keluarga_count }} anggota</span>
                                </td>

                                <!-- 3. Alamat -->
                                <td class="py-3.5 px-4 text-slate-500 font-medium align-middle">
                                    <span class="truncate block" title="{{ $family->alamat }}">{{ $family->alamat }}</span>
                                </td>

                                <!-- 4. Kontak -->
                                <td class="py-3.5 px-4 text-slate-600 font-medium align-middle whitespace-nowrap">
                                    <span class="truncate block">{{ $family->nomor_telepon }}</span>
                                </td>

                                <!-- 5. Aksi -->
                                <td class="py-3.5 px-4 text-right align-middle whitespace-nowrap">
                                    <a href="{{ route('kader.families.show', $family->id) }}" class="inline-flex items-center gap-1 text-emerald-600 hover:text-emerald-700 font-bold bg-emerald-50 hover:bg-emerald-100 border border-emerald-100/80 px-3 py-1.5 rounded-lg text-xs transition">
                                        <span>Kelola</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400 text-xs">Keluarga tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100">
                {{ $families->links() }}
            </div>
        </div>

    </div>

    <!-- Create Family Panel -->
    <div>
        <div id="add-family-form" class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs relative lg:sticky lg:top-20">
            <div class="flex items-center gap-2.5 mb-6">
                <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-100/80">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 0112 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Registrasi Keluarga Baru</h3>
                    <p class="text-[11px] text-slate-400 font-medium">Buat akun untuk keluarga baru</p>
                </div>
            </div>

            <form action="{{ route('kader.families.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="no_kk" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nomor Kartu Keluarga (KK)</label>
                    <input type="text" id="no_kk" name="no_kk" required maxlength="16" minlength="16" value="{{ old('no_kk') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-hidden transition placeholder:text-slate-400" 
                        placeholder="16 digit angka KK">
                </div>
                <div>
                    <label for="head_of_family" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nama Kepala Keluarga</label>
                    <input type="text" id="head_of_family" name="head_of_family" required value="{{ old('head_of_family') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-hidden transition placeholder:text-slate-400" 
                        placeholder="Nama Bapak / Kepala Keluarga">
                </div>
                <div>
                    <label for="phone" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nomor HP / WhatsApp</label>
                    <input type="text" id="phone" name="phone" required value="{{ old('phone') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-hidden transition placeholder:text-slate-400" 
                        placeholder="Contoh: 0812xxxxxxxx">
                </div>
                <div>
                    <label for="address" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Alamat Lengkap</label>
                    <textarea id="address" name="address" required rows="3"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-hidden transition leading-relaxed placeholder:text-slate-400" 
                        placeholder="Alamat rumah tinggal...">{{ old('address') }}</textarea>
                </div>
                <div>
                    <label for="password" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Password Login Warga</label>
                    <input type="text" id="password" name="password" required value="123456"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-hidden transition" 
                        placeholder="Min. 6 karakter">
                    <span class="text-[10px] text-slate-400 mt-1 block">Default password diset `123456` agar mudah diingat.</span>
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl text-xs font-semibold shadow-md shadow-emerald-100 hover:shadow-lg transition cursor-pointer">
                    Daftarkan Akun
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
