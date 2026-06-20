<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\Setoran;
use App\Models\User;
use App\Notifications\PwaWebPushNotification; // Tambahkan import class notification kita

class NotifikasiService
{
    // ── Kirim notif ke semua admin ─────────────────
    public static function keAdmin(
        string $judul,
        string $pesan,
        string $icon = '🔔',
        ?string $url = null
    ): void {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            // 1. Tetap simpan ke database untuk kebutuhan bell-dropdown web
            Notifikasi::create([
                'user_id' => $admin->id,
                'judul' => $judul,
                'pesan' => $pesan,
                'icon' => $icon,
                'url' => $url,
            ]);

            // 2. Tambahan: Kirim push notification real-time ke HP/Perangkat Admin
            try {
                $admin->notify(new PwaWebPushNotification($judul, $pesan, $url));
            } catch (\Exception $e) {
                // Log jika error (misal token expired/tidak valid) agar tidak merusak alur aplikasi utama
                \Log::error("Gagal mengirim push ke admin {$admin->id}: " . $e->getMessage());
            }
        }
    }

    // ── Kirim notif ke satu sales ──────────────────
    public static function keSales(
        int $salesId,
        string $judul,
        string $pesan,
        string $icon = '🔔',
        ?string $url = null
    ): void {
        // 1. Tetap simpan ke database
        Notifikasi::create([
            'user_id' => $salesId,
            'judul' => $judul,
            'pesan' => $pesan,
            'icon' => $icon,
            'url' => $url,
        ]);

        // 2. Tambahan: Kirim push notification real-time ke HP Sales yang bersangkutan
        $sales = User::find($salesId);
        if ($sales) {
            try {
                $sales->notify(new PwaWebPushNotification($judul, $pesan, $url));
            } catch (\Exception $e) {
                \Log::error("Gagal mengirim push ke sales {$salesId}: " . $e->getMessage());
            }
        }
    }

    // ── Preset: Sales kirim laporan ────────────────
    public static function salesKirimLaporan(Setoran $setoran): void
    {
        $url = route('admin.setoran.show', $setoran);

        self::keAdmin(
            judul: 'Laporan Baru Masuk',
            pesan: "{$setoran->sales->nama} mengirim laporan setoran tanggal {$setoran->tanggal->format('d M Y')} — " .
            "Rp " . number_format($setoran->totalUang(), 0, ',', '.'),
            icon: '📤',
            url: $url
        );
    }

    // ── Preset: Admin ACC setoran ──────────────────
    public static function adminAccSetoran(Setoran $setoran): void
    {
        $url = route('sales.setoran.index');

        self::keSales(
            salesId: $setoran->sales_id,
            judul: 'Laporan Disetujui!',
            pesan: "Laporan setoran kamu tanggal {$setoran->tanggal->format('d M Y')} " .
            "senilai Rp " . number_format($setoran->totalUang(), 0, ',', '.') . " telah disetujui.",
            icon: '✅',
            url: $url
        );
    }

    // ── Preset: Admin tolak setoran ────────────────
    public static function adminTolakSetoran(Setoran $setoran): void
    {
        $url = route('sales.setoran.index');

        self::keSales(
            salesId: $setoran->sales_id,
            judul: 'Laporan Dikembalikan',
            pesan: "Laporan setoran kamu tanggal {$setoran->tanggal->format('d M Y')} " .
            "dikembalikan ke draft. Silakan periksa dan kirim ulang.",
            icon: '↩️',
            url: $url
        );
    }
}