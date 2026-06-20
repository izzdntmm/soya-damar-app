<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>404 — Halaman Tidak Ditemukan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="text-center p-10">
        <div class="text-8xl mb-6">🔍</div>
        <h1 class="text-4xl font-bold text-gray-800 mb-3">404</h1>
        <p class="text-gray-500 mb-6">Halaman yang kamu cari tidak ditemukan.</p>
        <a href="{{ url()->previous() }}"
           class="px-6 py-3 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 transition">
            ← Kembali
        </a>
    </div>
</body>
</html>