@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-3xl border border-slate-100 shadow-xl transition-all duration-300 hover:shadow-2xl relative overflow-hidden">
        
        <!-- Subtle background glowing shapes -->
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-teal-500/10 rounded-full blur-3xl"></div>

        <div class="text-center relative z-10">
            <div class="inline-flex bg-gradient-to-tr from-emerald-400 to-teal-500 text-white p-3.5 rounded-2xl shadow-lg shadow-emerald-100 mb-4 animate-bounce">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Selamat Datang</h2>
            <p class="mt-2 text-sm text-slate-500">
                Silakan login untuk mengakses layanan Posyandu Digital
            </p>
        </div>

        <form class="mt-8 space-y-6 relative z-10" action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <!-- Username / Email / No KK Input -->
                <div>
                    <label for="login_input" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">No. KK / Username / Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input id="login_input" name="login_input" type="text" required value="{{ old('login_input') }}"
                            class="block w-full pl-11 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition-all duration-200" 
                            placeholder="Tulis No. KK atau Username Anda">
                    </div>
                </div>

                
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" required 
                            class="block w-full pl-11 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition-all duration-200" 
                            placeholder="••••••••">
                    </div>
                </div>
            </div>

            
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" 
                        class="h-4.5 w-4.5 text-emerald-600 focus:ring-emerald-500 border-slate-300 rounded-md cursor-pointer transition-all duration-150">
                    <label for="remember" class="ml-2 block text-xs font-medium text-slate-500 cursor-pointer">
                        Ingat saya di perangkat ini
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 shadow-md shadow-emerald-100 hover:shadow-lg cursor-pointer">
                    Masuk ke Akun
                </button>
            </div>

            <!-- Registration & Kader Guidelines Options -->
            <div class="space-y-3 pt-2 text-center text-xs">
                <div>
                    <span class="text-slate-400">Belum punya akun keluarga?</span>
                    <a href="{{ route('register') }}" class="font-bold text-emerald-600 hover:text-emerald-700 hover:underline ml-1">
                        Daftar Baru di Sini
                    </a>
                </div>
                <div>
                    <button type="button" onclick="toggleKaderGuideline()" class="text-slate-500 hover:text-slate-700 font-medium underline inline-flex items-center gap-1 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Saya adalah Kader baru
                    </button>
                </div>
            </div>

            <!-- Toggleable Kader Guideline Message -->
            <div id="kader-guideline-box" class="hidden bg-emerald-50 border border-emerald-100 rounded-2xl p-4 text-left space-y-2 mt-4 transition-all duration-200">
                <span class="font-bold text-emerald-800 text-xs flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path></svg>
                    Pendaftaran Akun Kader Baru
                </span>
                <p class="text-[11px] text-emerald-700/90 leading-relaxed">
                    Demi keamanan rekam medis kesehatan desa, pendaftaran akun **Kader Posyandu** harus melalui persetujuan Ketua Kader Posyandu atau Admin Desa.
                </p>
                <div class="pt-1.5 border-t border-emerald-250/50 text-[11px]">
                    <span class="font-bold text-emerald-800">Silakan Hubungi Ketua Kader:</span>
                    <p class="mt-0.5 text-slate-650">Buk Marni (Ketua Kader) - <a href="https://wa.me/6281234567890" target="_blank" class="font-bold text-emerald-700 hover:underline">0812-3456-7890</a></p>
                </div>
            </div>
        </form>


    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleKaderGuideline() {
        const box = document.getElementById('kader-guideline-box');
        box.classList.toggle('hidden');
    }
</script>
@endsection
