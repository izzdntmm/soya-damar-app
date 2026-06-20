import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],

    // Tambahkan blok server ini:
    server: {
        host: '0.0.0.0', // Mengizinkan akses dari jaringan luar
        hmr: {
            host: '192.168.10.109' // ⚠️ GANTI dengan IP Address laptop kamu (hasil ipconfig tadi)
        },
    },
});
