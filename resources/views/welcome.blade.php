<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Soya Damar</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-title.png') }}">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .fade-in {
            animation: fadeIn 1.2s ease-in-out both;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-bounce {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }
    </style>
</head>
<body class="bg-[#f2f4f7] min-h-screen flex flex-col">

    <!-- Konten Utama -->
    <main class="flex-grow flex items-center justify-center px-4">
        <div class="text-center bg-white p-8 sm:p-10 rounded-xl shadow-lg w-full max-w-md fade-in">
            <!-- Logo -->
            <div class="mb-4">
                <img src="{{ asset('images/logo-soya.png') }}" alt="Logo UMKM" class="mx-auto h-20 logo-bounce">
            </div>

            <h1 class="text-2xl sm:text-3xl font-bold text-[#2563eb] mb-6 leading-snug">
                Selamat Datang di <br>
                Aplikasi Manajemen Penyetoran <br>
            </h1>

            <div class="flex flex-col sm:flex-row justify-center gap-4 mt-6">
                @if (Route::has('login'))
                    <a href="{{ route('login') }}"
                       class="bg-[#2563eb] hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-300">
                        Login
                    </a>
                @endif

                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="bg-white border border-[#2563eb] text-[#2563eb] hover:bg-blue-50 font-semibold py-2 px-6 rounded-lg transition duration-300">
                        Register
                    </a>
                @endif
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="text-center text-sm text-gray-500 py-4">
        ©2025 Soya Damar. All rights reserved.
    </footer>

</body>
</html>
