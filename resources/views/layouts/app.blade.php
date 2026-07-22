<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DesaSehat') – Posyandu Digital Terintegrasi</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (Vite) -->
    @vite(['resources/css/app.css'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(241, 245, 249, 0.9);
        }
        .text-glow {
            text-shadow: 0 0 12px rgba(16, 185, 129, 0.2);
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @yield('styles')
</head>
<body class="min-h-screen text-slate-800 flex flex-col">

    <!-- Mobile Sidebar Backdrop -->
    @auth
        <div id="sidebar-backdrop" onclick="closeSidebar()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-40 hidden transition-opacity duration-300 opacity-0 cursor-pointer"></div>
    @endauth

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-slate-100 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center gap-3">
                    @auth
                        <!-- Hamburger mobile button -->
                        <button id="mobile-sidebar-toggle" onclick="openSidebar()" class="md:hidden p-1.5 -ml-1 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition duration-150 cursor-pointer">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    @endauth

                    <div class="bg-gradient-to-tr from-emerald-400 to-teal-500 text-white p-2 rounded-xl shadow-md shadow-emerald-200 animate-pulse">
                        <!-- Health Symbol SVG -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-700 text-glow">
                            DesaSehat
                        </h1>
                        <span class="text-[10px] uppercase font-semibold tracking-wider text-slate-400 block -mt-1">
                            Posyandu Digital
                        </span>
                    </div>
                </div>

                <!-- Right Menu: User Info & Logout -->
                <div class="flex items-center gap-4">
                    @auth
                        <div class="hidden md:flex flex-col text-right">
                            <span class="text-sm font-semibold text-slate-700">{{ auth()->user()->name }}</span>
                            <span class="text-[11px] font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full self-end border border-emerald-100">
                                {{ auth()->user()->peran->display_peran }}
                            </span>
                        </div>
                        
                        <div class="h-8 w-px bg-slate-200 hidden md:block"></div>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 bg-slate-50 hover:bg-rose-50 text-slate-600 hover:text-rose-600 px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-200 border border-slate-100 hover:border-rose-100 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span class="hidden sm:inline">Keluar</span>
                            </button>
                        </form>
                    @else
                        <span class="text-xs text-slate-400">Belum masuk</span>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <div class="flex-1 flex flex-col md:flex-row max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 gap-6">
        
        <!-- Sidebar Navigation (Only for Authenticated Users) -->
        @auth
            <aside id="sidebar-menu" class="fixed inset-y-0 left-0 z-50 w-64 bg-white p-5 border-r border-slate-100 transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:w-64 md:shrink-0 md:shadow-none md:border-none md:p-0">
                <div class="flex items-center justify-between md:hidden pb-4 mb-4 border-b border-slate-100">
                    <span class="text-sm font-bold text-slate-800">Menu DesaSehat</span>
                    <button id="mobile-sidebar-close" onclick="closeSidebar()" class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="sticky top-22 bg-white md:rounded-2xl md:border md:border-slate-100 md:shadow-sm md:p-4 flex flex-col gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider px-3 mb-2 block">Menu Layanan</span>

                    @if(auth()->user()->isKader())
                        <!-- Kader Sidebar Menu -->
                        <a href="{{ route('kader.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('kader.dashboard') ? 'bg-emerald-500 text-white shadow-md shadow-emerald-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
                            Panel Dashboard
                        </a>
                        <a href="{{ route('kader.families.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('kader.families.*') ? 'bg-emerald-500 text-white shadow-md shadow-emerald-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Data Keluarga
                        </a>
                        <a href="{{ route('kader.reports') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('kader.reports') ? 'bg-emerald-500 text-white shadow-md shadow-emerald-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Laporan & Ekspor
                        </a>
                        <a href="{{ route('kader.schedules.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('kader.schedules.index') ? 'bg-emerald-500 text-white shadow-md shadow-emerald-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Jadwal Kegiatan
                        </a>
                        <a href="{{ route('kader.announcements.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('kader.announcements.index') ? 'bg-emerald-500 text-white shadow-md shadow-emerald-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                            Pengumuman
                        </a>
                        <a href="{{ route('kader.notifications.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('kader.notifications.index') ? 'bg-emerald-500 text-white shadow-md shadow-emerald-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            Kirim Notifikasi
                        </a>
                        <a href="{{ route('kader.audit-logs') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('kader.audit-logs') ? 'bg-emerald-500 text-white shadow-md shadow-emerald-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path></svg>
                            Log Aktivitas
                        </a>
                    @else
                        <!-- Warga Sidebar Menu -->
                        <a href="{{ route('warga.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('warga.dashboard') ? 'bg-emerald-500 text-white shadow-md shadow-emerald-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            Dashboard Keluarga
                        </a>
                    @endif
                </div>
            </aside>
        @endauth

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col gap-6 min-w-0">
            
            <!-- Session Alert Banner -->
            @if(session('success'))
                <div id="alert-banner-success" class="flex items-start gap-3 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-2xl p-4 shadow-xs transition-all duration-300 transform scale-100">
                    <svg class="w-5 h-5 shrink-0 text-emerald-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-semibold">Berhasil</p>
                        <p class="text-xs text-emerald-700/90 mt-0.5">{{ session('success') }}</p>
                    </div>
                    <button onclick="document.getElementById('alert-banner-success').remove()" class="text-emerald-400 hover:text-emerald-700 transition-colors shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div id="alert-banner-error" class="flex items-start gap-3 bg-rose-50 text-rose-800 border border-rose-200 rounded-2xl p-4 shadow-xs transition-all duration-300 transform scale-100">
                    <svg class="w-5 h-5 shrink-0 text-rose-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-semibold">Terjadi Kesalahan</p>
                        <p class="text-xs text-rose-700/90 mt-0.5">{{ session('error') }}</p>
                    </div>
                    <button onclick="document.getElementById('alert-banner-error').remove()" class="text-rose-400 hover:text-rose-700 transition-colors shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            <!-- Custom validation errors -->
            @if ($errors->any())
                <div id="alert-banner-validation" class="bg-rose-50 text-rose-800 border border-rose-200 rounded-2xl p-4 shadow-xs">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 shrink-0 text-rose-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-semibold">Tolong perbaiki data berikut:</p>
                            <ul class="list-disc list-inside text-xs text-rose-700 mt-1.5 space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button onclick="document.getElementById('alert-banner-validation').remove()" class="text-rose-400 hover:text-rose-700 transition-colors shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Core Page Content -->
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="mt-auto border-t border-slate-100 bg-white py-6">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-slate-400">
            &copy; 2026 DesaSehat – Sistem Informasi Posyandu Digital Terintegrasi. Seluruh Hak Cipta Dilindungi.
        </div>
    </footer>

    <!-- ChartJS Support (Loaded dynamically when needed) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @yield('scripts')

    <!-- Auto Dismiss Success Alerts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const successAlert = document.getElementById('alert-banner-success');
            if (successAlert) {
                setTimeout(function () {
                    successAlert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    successAlert.style.opacity = '0';
                    successAlert.style.transform = 'scale(0.95)';
                    setTimeout(function () {
                        successAlert.remove();
                    }, 500);
                }, 5000);
            }
        });
    </script>

    <!-- Script for mobile menu toggle -->
    @auth
    <script>
        function openSidebar() {
            const sidebar = document.getElementById('sidebar-menu');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (sidebar) sidebar.classList.remove('-translate-x-full');
            if (backdrop) {
                backdrop.classList.remove('hidden');
                setTimeout(() => {
                    backdrop.classList.add('opacity-100');
                }, 10);
            }
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar-menu');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (sidebar) sidebar.classList.add('-translate-x-full');
            if (backdrop) {
                backdrop.classList.remove('opacity-100');
                setTimeout(() => {
                    backdrop.classList.add('hidden');
                }, 300);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('mobile-sidebar-toggle');
            const closeBtn = document.getElementById('mobile-sidebar-close');
            const backdrop = document.getElementById('sidebar-backdrop');

            if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);
        });
    </script>
    @endauth
</body>
</html>
