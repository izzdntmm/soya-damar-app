<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PwaWebPushNotification extends Notification
{
    use Queueable;

    protected $judul;
    protected $pesan;
    protected $url;

    public function __construct($judul, $pesan, $url = null)
    {
        $this->judul = $judul;
        $this->pesan = $pesan;
        $this->url = $url;
    }

    // Tentukan bahwa channel yang digunakan adalah WebPush
    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    // Format struktur data data yang akan dikirim ke HP / service worker (sw.js)
    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title($this->judul)
            ->icon('/icon-pwa.png') // samakan dengan ikon di sw.js
            ->body($this->pesan)
            ->data(['url' => $this->url]);
    }
}