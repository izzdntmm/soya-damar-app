<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sales') — Soya Damar</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }
    </style>

    @stack('styles')

    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#3b82f6">

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Service Worker terdaftar!', reg))
                    .catch(err => console.log('Service Worker gagal:', err));
            });
        }
    </script>
    <script>
        // 1. Pastikan Service Worker didukung
        if ('serviceWorker' in navigator && 'PushManager' in window) {
            navigator.serviceWorker.ready.then(reg => {
                console.log('Service Worker siap untuk Web Push.');
                // Panggil fungsi untuk memeriksa status atau mendaftarkan subskripsi
                autoSubscribeUser(reg);
            });
        }

        // 2. Fungsi otomatis mendaftarkan perangkat user ke server Laravel
        function autoSubscribeUser(registration) {
            const applicationServerKey = urlBase64ToUint8Array('{{ env('VAPID_PUBLIC_KEY') }}');

            registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: applicationServerKey
            })
                .then(subscription => {
                    // PERBAIKAN: Mengarah langsung ke URL web /push-subscriptions
                    fetch('/push-subscriptions', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            // Sangat krusial agar tidak error 419 (Page Expired)
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(subscription)
                    })
                        .then(res => {
                            if (!res.ok) {
                                // Jika error, lempar ke catch agar terbaca di console log
                                throw new Error('Server merespon dengan status ' + res.status);
                            }
                            return res.json();
                        })
                        .then(data => console.log('Berhasil terhubung ke database:', data))
                        .catch(err => console.error('Gagal mengirim token ke server:', err));
                })
                .catch(err => {
                    console.log('Gagal subscribe ke Push Manager:', err);
                });
        }

        // Helper untuk mengubah string VAPID Key menjadi Uint8Array yang dipahami browser
        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);
            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }
    </script>
</head>

<body class="bg-gray-50">

    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

        {{-- SIDEBAR (Khusus Desktop: md ke atas) --}}
        <aside class="fixed lg:static inset-y-0 left-0 z-50
               w-72 sm:w-64
               bg-white border-r border-gray-200
               hidden md:flex flex-col
               transform transition-transform duration-300 ease-in-out
               lg:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            {{-- Logo --}}
            <div class="px-5 py-5 border-b border-gray-100 flex items-center justify-between">

                <div class="flex items-center gap-3">

                    <div
                        class="w-11 h-11 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                        <i data-lucide="milk" class="w-5 h-5 text-white"></i>
                    </div>

                    <div>
                        <h2 class="text-gray-800 font-bold text-sm">
                            Soya Damar
                        </h2>

                        <p class="text-gray-400 text-xs">
                            Sales Panel
                        </p>
                    </div>

                </div>

                {{-- Close Mobile --}}
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>

            </div>

            {{-- MENU SIDEBAR --}}
            <nav class="flex-1 overflow-y-auto px-4 py-6">

                <p class="text-gray-400 text-[11px] font-semibold uppercase tracking-widest px-3 mb-3">
                    Menu Utama
                </p>

                {{-- Dashboard --}}
                <a href="{{ route('sales.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl mb-2 text-sm font-medium transition-all duration-200
               {{ request()->routeIs('sales.dashboard')
    ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20'
    : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600' }}">

                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span>Dashboard</span>
                </a>

                {{-- Toko --}}
                <a href="{{ route('sales.toko.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl mb-2 text-sm font-medium transition-all duration-200
               {{ request()->routeIs('sales.toko.*')
    ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20'
    : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600' }}">

                    <i data-lucide="store" class="w-5 h-5"></i>
                    <span>Toko Saya</span>
                </a>

                {{-- Setoran --}}
                <a href="{{ route('sales.setoran.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl mb-2 text-sm font-medium transition-all duration-200
               {{ request()->routeIs('sales.setoran.*')
    ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20'
    : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600' }}">

                    <i data-lucide="wallet" class="w-5 h-5"></i>
                    <span>Setoran Harian</span>
                </a>

            </nav>

        </aside>

        {{-- BOTTOM NAVIGATION (Khusus Mobile: md ke bawah) --}}
        <div
            class="fixed bottom-0 left-0 z-50 w-full h-20 bg-white border-t border-gray-200 md:hidden shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] pb-3">
            <div class="grid h-full grid-cols-3 mx-auto">

                {{-- Nav: Dashboard --}}
                <a href="{{ route('sales.dashboard') }}"
                    class="inline-flex flex-col items-center justify-center px-2 transition-colors duration-150
                    {{ request()->routeIs('sales.dashboard') ? 'text-blue-600' : 'text-gray-400 hover:text-gray-600' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 mb-0.5"></i>
                    <span class="text-[10px] font-medium">Dashboard</span>
                </a>

                {{-- Nav: Toko Saya --}}
                <a href="{{ route('sales.toko.index') }}" class="inline-flex flex-col items-center justify-center px-2 transition-colors duration-150
                    {{ request()->routeIs('sales.toko.*') ? 'text-blue-600' : 'text-gray-400 hover:text-gray-600' }}">
                    <i data-lucide="store" class="w-5 h-5 mb-0.5"></i>
                    <span class="text-[10px] font-medium">Toko Saya</span>
                </a>

                {{-- Nav: Setoran --}}
                <a href="{{ route('sales.setoran.index') }}"
                    class="inline-flex flex-col items-center justify-center px-2 transition-colors duration-150
                    {{ request()->routeIs('sales.setoran.*') ? 'text-blue-600' : 'text-gray-400 hover:text-gray-600' }}">
                    <i data-lucide="wallet" class="w-5 h-5 mb-0.5"></i>
                    <span class="text-[10px] font-medium">Setoran</span>
                </a>

            </div>
        </div>

        {{-- CONTENT AREA --}}
        <div class="flex-1 flex flex-col overflow-hidden min-w-0">

            {{-- TOPBAR --}}
            <header class="bg-white border-b border-gray-200 px-4 sm:px-6 py-4 flex items-center justify-between">

                <div class="flex items-center gap-3 min-w-0">

                    {{-- Tombol Hamburger Mobile Sudah Dihapus --}}

                    <div class="min-w-0">

                        <h1 class="text-lg sm:text-xl font-bold text-gray-800 truncate">
                            @yield('page-title', 'Dashboard')
                        </h1>

                        <p class="text-xs sm:text-sm text-gray-400 truncate">
                            @yield('page-subtitle', '')
                        </p>

                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="flex items-center gap-2 sm:gap-3">

                    {{-- Notif --}}
                    <x-notif-bell />

                    {{-- Date --}}
                    <div
                        class="hidden xl:flex items-center gap-2 bg-gray-100 px-4 py-2 rounded-2xl text-sm text-gray-500">
                        <i data-lucide="calendar-days" class="w-4 h-4"></i>

                        <span>
                            {{ now()->translatedFormat('d F Y') }}
                        </span>
                    </div>

                    {{-- USER MENU --}}
                    <div x-data="{ open: false }" class="relative">

                        {{-- Trigger --}}
                        <button @click="open = !open" class="flex items-center gap-2 sm:gap-3
                               bg-white border border-gray-200
                               hover:border-blue-300 hover:bg-blue-50
                               px-2 sm:px-3 py-2 rounded-2xl
                               transition-all duration-200 shadow-sm">

                            {{-- Avatar --}}
                            <div class="w-10 h-10 rounded-full bg-blue-600
                                    flex items-center justify-center
                                    text-white font-bold text-sm shadow">

                                {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}

                            </div>

                            {{-- Info --}}
                            <div class="hidden md:block text-left leading-tight">

                                <p class="text-sm font-semibold text-gray-800 max-w-[130px] truncate">
                                    {{ Auth::user()->nama }}
                                </p>

                                <p class="text-xs text-gray-400">
                                    Sales
                                </p>

                            </div>

                            <i data-lucide="chevron-down" class="hidden sm:block w-4 h-4 text-gray-400"></i>

                        </button>

                        {{-- Dropdown --}}
                        <div x-show="open" @click.away="open = false" x-transition x-cloak class="absolute right-0 mt-3 w-64 bg-white border border-gray-100
                               rounded-3xl shadow-2xl overflow-hidden z-50">

                            {{-- Header --}}
                            <div class="p-5 border-b border-gray-100 bg-gray-50">

                                <div class="flex items-center gap-3">

                                    <div class="w-12 h-12 rounded-2xl bg-blue-600
                                            flex items-center justify-center
                                            text-white font-bold shadow">

                                        {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}

                                    </div>

                                    <div class="min-w-0">

                                        <p class="font-semibold text-gray-800 truncate">
                                            {{ Auth::user()->nama }}
                                        </p>

                                        <p class="text-sm text-gray-400 truncate">
                                            {{ Auth::user()->email }}
                                        </p>

                                    </div>

                                </div>

                            </div>

                            {{-- Menu --}}
                            <div class="p-2">

                                <a href="{{ route('sales.profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl
                                      text-gray-700 hover:bg-blue-50
                                      hover:text-blue-700 transition">

                                    <i data-lucide="user-cog" class="w-4 h-4"></i>

                                    <span class="text-sm font-medium">
                                        Edit Profile
                                    </span>

                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3
                                           rounded-2xl text-red-600
                                           hover:bg-red-50 transition">

                                        <i data-lucide="log-out" class="w-4 h-4"></i>

                                        <span class="text-sm font-medium">
                                            Logout
                                        </span>

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </header>

            {{-- FLASH MESSAGE --}}
            @if(session('success') || session('error'))
                <div class="px-4 sm:px-6 pt-4">

                    @if(session('success'))
                        <div
                            class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-2xl text-sm flex items-center gap-2 mb-3">
                            <i data-lucide="circle-check-big" class="w-5 h-5"></i>

                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div
                            class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-sm flex items-center gap-2 mb-3">
                            <i data-lucide="circle-x" class="w-5 h-5"></i>

                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                </div>
            @endif

            {{-- CONTENT (Diberi pb-24 agar tidak mepet atau terpotong menu bawah) --}}
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 pb-24 md:pb-6">
                @yield('content')
            </main>

        </div>

    </div>

    @stack('scripts')

    <script>
        lucide.createIcons();
    </script>

</body>

</html>