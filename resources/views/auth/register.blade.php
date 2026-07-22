@extends('layouts.app')

@section('title', 'Registrasi Keluarga Baru')

@section('content')
<div class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-3xl border border-slate-100 shadow-xl transition-all duration-300 hover:shadow-2xl relative overflow-hidden">
        
        <!-- Background glowing blobs -->
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-teal-500/10 rounded-full blur-3xl"></div>

        <div class="text-center relative z-10">
            <div class="inline-flex bg-gradient-to-tr from-emerald-400 to-teal-500 text-white p-3.5 rounded-2xl shadow-lg shadow-emerald-100 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Daftar Akun Warga</h2>
            <p class="mt-2 text-sm text-slate-500 font-medium">
                Pendaftaran mandiri akun perwakilan keluarga (Kartu Keluarga)
            </p>
        </div>

        <form class="mt-8 space-y-5 relative z-10" action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <!-- No. KK Input -->
                <div>
                    <label for="no_kk" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nomor Kartu Keluarga (KK) *</label>
                    <div class="relative">
                        <input id="no_kk" name="no_kk" type="text" required maxlength="16" minlength="16" value="{{ old('no_kk') }}"
                            class="block w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition-all duration-200" 
                            placeholder="Tulis 16 digit nomor KK Anda">
                    </div>
                </div>

                <!-- Head of Family Input -->
                <div>
                    <label for="head_of_family" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nama Kepala Keluarga *</label>
                    <div class="relative">
                        <input id="head_of_family" name="head_of_family" type="text" required value="{{ old('head_of_family') }}"
                            class="block w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition-all duration-200" 
                            placeholder="Nama Bapak / Kepala Keluarga">
                    </div>
                </div>

                <!-- Phone Input -->
                <div>
                    <label for="phone" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">No. HP / WhatsApp aktif *</label>
                    <div class="relative">
                        <input id="phone" name="phone" type="text" required value="{{ old('phone') }}"
                            class="block w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition-all duration-200" 
                            placeholder="Contoh: 08123456789">
                    </div>
                </div>

                <!-- Address Input -->
                <div>
                    <label for="address" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Alamat Tempat Tinggal *</label>
                    <div class="relative">
                        <textarea id="address" name="address" required rows="2"
                            class="block w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition-all duration-200" 
                            placeholder="Tulis alamat rumah lengkap...">{{ old('address') }}</textarea>
                    </div>
                </div>

                <!-- Passwords -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Password *</label>
                        <input id="password" name="password" type="password" required 
                            class="block w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition-all duration-200" 
                            placeholder="Min. 6 karakter">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Konfirmasi *</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required 
                            class="block w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition-all duration-200" 
                            placeholder="Ulangi password">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" 
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 shadow-md shadow-emerald-100 hover:shadow-lg cursor-pointer">
                    Daftar Akun Keluarga
                </button>
            </div>
        </form>

        <div class="mt-6 border-t border-slate-100 pt-4 text-center">
            <span class="text-slate-400 text-xs">Sudah memiliki akun keluarga?</span>
            <a href="{{ route('login') }}" class="font-bold text-emerald-600 hover:text-emerald-700 hover:underline text-xs ml-1">
                Masuk di Sini
            </a>
        </div>

    </div>
</div>
@endsection
