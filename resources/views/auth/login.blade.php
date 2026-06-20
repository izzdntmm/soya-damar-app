<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Soya Damar App</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#3b82f6">

    {{-- Import Lucide Icons untuk icon unduhan dan tombol silang --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Service Worker terdaftar!', reg))
                    .catch(err => console.log('Service Worker gagal:', err));
            });
        }
    </script>
</head>

<body class="bg-gradient-to-br from-white via-blue-50 to-white min-h-screen relative">

    <div class="min-h-screen flex items-center justify-center px-4 py-6">

        <div class="w-full max-w-sm">

            <div class="bg-white border border-blue-100 rounded-2xl shadow-lg p-6 sm:p-7">

                <div class="text-center mb-6">

                    <div class="w-14 h-14 mx-auto mb-3 flex items-center justify-center">
                        <div
                            class="w-11 h-11 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                            <i data-lucide="milk" class="w-5 h-5 text-white"></i>
                        </div>
                    </div>

                    <h1 class="text-xl font-bold text-gray-800">
                        Soya Damar
                    </h1>

                    <p class="text-xs text-gray-500 mt-1">
                        Sistem Manajemen Penyetoran Sales
                    </p>

                </div>

                @if (session('status'))
                    <div class="mb-4 rounded-lg bg-green-50 p-3 text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email
                        </label>

                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="Masukkan email"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none">

                        @error('email')
                            <p class="mt-1 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Password
                        </label>

                        <input type="password" name="password" required placeholder="Masukkan password"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none">

                        @error('password')
                            <p class="mt-1 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between mb-5">

                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-500">
                            <span class="text-gray-600">
                                Ingat saya
                            </span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-blue-500 hover:text-blue-600">
                                Lupa?
                            </a>
                        @endif

                    </div>

                    <button type="submit"
                        class="w-full rounded-xl bg-blue-500 py-2.5 text-sm font-semibold text-white hover:bg-blue-600 transition">
                        Login
                    </button>

                </form>
            </div>

            <p class="text-center text-xs text-gray-400 mt-4">
                © {{ date('Y') }} Soya Damar
            </p>

        </div>

    </div>

    {{-- ══ FLOATING BANNER INSTALL PWA ═══════════════ --}}
    <div id="pwa-install-banner"
        class="fixed bottom-6 left-4 right-4 md:left-auto md:right-6 md:max-w-sm bg-white rounded-2xl border border-blue-50 shadow-xl p-4 z-50 transform translate-y-20 opacity-0 transition-all duration-500 hidden">

        <div class="flex items-start justify-between gap-3">
            <div class="flex items-start gap-3">
                {{-- Icon Lingkaran Hijau --}}
                <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="download-cloud" class="w-5 h-5 text-emerald-600"></i>
                </div>

                {{-- Teks Info --}}
                <div>
                    <h4 class="font-bold text-gray-800 text-sm">Install Soya Damar</h4>
                    <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">
                        Akses lebih cepat langsung dari HP, tanpa buka browser.
                    </p>
                </div>
            </div>

            {{-- Tombol Close (X) --}}
            <button onclick="dismissPwaBanner()" class="text-gray-400 hover:text-gray-600 transition p-1">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        {{-- Tombol Aksi Utama --}}
        <button id="btn-pwa-install"
            class="w-full mt-4 flex items-center justify-center gap-2 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 transition shadow-sm shadow-emerald-200">
            <i data-lucide="download" class="w-4 h-4"></i>
            Install Sekarang
        </button>
    </div>

    {{-- LOGIKA SCRIPT PEMICU BANNER PWA --}}
    <script>
        let deferredPrompt;
        const pwaBanner = document.getElementById('pwa-install-banner');
        const btnInstall = document.getElementById('btn-pwa-install');

        // Deteksi apakah web lolos syarat PWA dan siap diinstall
        window.addEventListener('beforeinstallprompt', (e) => {
            // Sembunyikan pop-up bawaan browser asli agar tidak bentrok
            e.preventDefault();

            // Simpan datanya ke variabel global
            deferredPrompt = e;

            // Tampilkan banner kustom buatan kita
            pwaBanner.classList.remove('hidden');
            setTimeout(() => {
                pwaBanner.classList.remove('translate-y-20', 'opacity-0');
            }, 100);
        });

        // Ketika tombol "Install Sekarang" diklik
        btnInstall.addEventListener('click', async () => {
            if (!deferredPrompt) return;

            // Picu modal instalasi bawaan browser OS (Android/Chrome)
            deferredPrompt.prompt();

            // Ambil keputusan user
            const { outcome } = await deferredPrompt.userChoice;
            console.log(`Pilihan instalasi user: ${outcome}`);

            deferredPrompt = null;
            dismissPwaBanner();
        });

        // Fungsi menyembunyikan banner jika ditutup atau selesai install
        function dismissPwaBanner() {
            pwaBanner.classList.add('translate-y-20', 'opacity-0');
            setTimeout(() => {
                pwaBanner.classList.add('hidden');
            }, 500);
        }

        // Inisialisasi icon Lucide yang digunakan pada banner
        lucide.createIcons();
    </script>

</body>

</html>