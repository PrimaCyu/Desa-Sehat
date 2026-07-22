@extends('layouts.app')

@section('title', 'Kelola Data Keluarga')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Daftar Akun Keluarga (KK)</h2>
        <p class="text-xs text-slate-400">Total keluarga terdaftar di Posyandu DesaSehat</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- List Families Panel -->
    <div class="lg:col-span-2 space-y-4">
        
        <!-- Search bar -->
        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-xs">
            <form action="{{ route('kader.families.index') }}" method="GET" class="flex gap-2">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="block w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition" 
                        placeholder="Cari berdasarkan No. KK, nama kepala keluarga, atau alamat...">
                </div>
                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs px-4 py-2 rounded-xl transition cursor-pointer">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('kader.families.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs px-3 py-2 rounded-xl transition flex items-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 font-semibold">
                            <th class="pb-3">No. KK</th>
                            <th class="pb-3">Kepala Keluarga</th>
                            <th class="pb-3">Alamat</th>
                            <th class="pb-3">Kontak</th>
                            <th class="pb-3">Anggota</th>
                            <th class="pb-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($families as $family)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 font-bold text-slate-700">{{ $family->username }}</td>
                                <td class="py-3.5 font-semibold text-slate-850">{{ $family->kepala_keluarga }}</td>
                                <td class="py-3.5 text-slate-500 max-w-[150px] truncate" title="{{ $family->alamat }}">{{ $family->alamat }}</td>
                                <td class="py-3.5 text-slate-500 font-medium">{{ $family->nomor_telepon }}</td>
                                <td class="py-3.5">
                                    <span class="bg-emerald-50 text-emerald-700 font-bold px-2 py-0.5 rounded-full">
                                        {{ $family->anggota_keluarga_count }} orang
                                    </span>
                                </td>
                                <td class="py-3.5 text-right">
                                    <a href="{{ route('kader.families.show', $family->id) }}" class="inline-flex items-center gap-1 text-emerald-600 hover:text-emerald-700 font-bold">
                                        <span>Kelola</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400 text-xs">Keluarga tidak ditemukan.</td>
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
        <div id="add-family-form" class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs sticky top-22">
            <div class="flex items-center gap-2.5 mb-6">
                <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Registrasi Keluarga Baru</h3>
            </div>

            <form action="{{ route('kader.families.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="no_kk" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor Kartu Keluarga (KK)</label>
                    <input type="text" id="no_kk" name="no_kk" required maxlength="16" minlength="16" value="{{ old('no_kk') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition" 
                        placeholder="16 digit angka KK">
                </div>
                <div>
                    <label for="head_of_family" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Kepala Keluarga</label>
                    <input type="text" id="head_of_family" name="head_of_family" required value="{{ old('head_of_family') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition" 
                        placeholder="Nama Bapak / Kepala Keluarga">
                </div>
                <div>
                    <label for="phone" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor HP / WhatsApp</label>
                    <input type="text" id="phone" name="phone" required value="{{ old('phone') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition" 
                        placeholder="Contoh: 0812xxxxxxxx">
                </div>
                <div>
                    <label for="address" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Alamat Lengkap</label>
                    <textarea id="address" name="address" required rows="3"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition" 
                        placeholder="Alamat rumah tinggal...">{{ old('address') }}</textarea>
                </div>
                <div>
                    <label for="password" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Password Login Warga</label>
                    <input type="text" id="password" name="password" required value="123456"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition" 
                        placeholder="Min. 6 karakter">
                    <span class="text-[10px] text-slate-400 mt-1 block">Default password diset `123456` agar mudah diingat.</span>
                </div>

                <button type="submit" class="w-full py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl text-xs font-semibold shadow-md shadow-emerald-100 hover:shadow-lg transition cursor-pointer">
                    Daftarkan Akun
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
