<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soya Damar App</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-title.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script defer>
        function toggleUserDropdown() {
            const dropdown = document.getElementById('user-dropdown');
            dropdown.classList.toggle('hidden');
        }

        document.addEventListener('click', function (e) {
            const dropdown = document.getElementById('user-dropdown');
            const button = document.getElementById('menu-button');
            if (dropdown && button && !button.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    @php
        $user = Auth::user();
        $photoUrl = $user->profile_photo_path
            ? asset('storage/' . $user->profile_photo_path)
            : 'https://ui-avatars.com/api/?name=' . urlencode($user->name);
    @endphp

    <!-- Navbar -->
    <!-- Navbar -->
    <nav class="bg-white shadow-md fixed top-0 left-0 w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Kiri: Logo + Menu -->
                <div class="flex items-center space-x-8">
                    <!-- Logo -->
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('images/logo-soya.png') }}" alt="Soya Damar Logo" class="h-10 w-auto">
                    </a>

                    <!-- Menu Navigasi -->
                    <div class="flex space-x-6">
                        @if($user->role === 'admin')
                            <a href="{{ route('admin.reports.index') }}"
                                class="{{ request()->routeIs('admin.reports.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Laporan Harian
                            </a>
                            <a href="{{ route('admin.reports.monthly') }}"
                                class="{{ request()->routeIs('admin.reports.monthly') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Laporan Bulanan
                            </a>
                        @elseif($user->role === 'sales')
                            <a href="{{ route('deliveries.index') }}"
                                class="{{ request()->routeIs('deliveries.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Setoran
                            </a>
                            <a href="{{ route('stores.index') }}"
                                class="{{ request()->routeIs('stores.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Toko
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Kanan: Foto Profil -->
                <div class="flex items-center">
                    <div class="relative">
                        <button onclick="toggleUserDropdown()" type="button"
                            class="inline-flex justify-center items-center rounded-full focus:outline-none"
                            id="menu-button" aria-expanded="true" aria-haspopup="true">
                            <img src="{{ $photoUrl }}" alt="Foto Profil"
                                class="w-10 h-10 rounded-full object-cover border border-gray-300 shadow">
                        </button>

                        <div id="user-dropdown"
                            class="hidden absolute right-0 z-10 mt-2 w-44 origin-top-right bg-white border border-gray-200 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                            <div class="py-1" role="none">
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </nav>


    <!-- Konten -->
    <main class="pt-20 sm:pt-24 flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white text-center py-4 text-sm text-gray-500 shadow-inner">
        ©2025 Soya Damar. All rights reserved.
    </footer>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="https://unpkg.com/@web-push-libs/notification"></script>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            if ('serviceWorker' in navigator) {
                const registration = await navigator.serviceWorker.register('/sw.js');

                const permission = await Notification.requestPermission();
                if (permission === 'granted') {
                    const vapidPublicKey = '{{ config("webpush.vapid.public_key") }}';
                    const convertedVapidKey = urlBase64ToUint8Array(vapidPublicKey);

                    const subscription = await registration.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: convertedVapidKey
                    });

                    await fetch('/push/subscribe', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(subscription)
                    });
                }
            }

            function urlBase64ToUint8Array(base64String) {
                const padding = '='.repeat((4 - base64String.length % 4) % 4);
                const base64 = (base64String + padding)
                    .replace(/-/g, '+')
                    .replace(/_/g, '/');
                const rawData = window.atob(base64);
                const outputArray = new Uint8Array(rawData.length);
                for (let i = 0; i < rawData.length; ++i) {
                    outputArray[i] = rawData.charCodeAt(i);
                }
                return outputArray;
            }
        });
    </script>

</body>

</html>