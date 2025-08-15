<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewDeliverySubmitted extends Notification
{
    use Queueable;

    public $user;
    public $tanggal;

    public function __construct($user, $tanggal)
    {
        $this->user = $user;
        $this->tanggal = $tanggal;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'tanggal' => $this->tanggal,
            'message' =>'' . $this->user->name . ' mengirim setoran pada ' . $this->tanggal,
        ];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Setoran Baru dari ' . $this->user->name,
            'body' => 'Setoran tanggal ' . $this->tanggal . ' telah dikirim.',
            'url' => route('admin.reports.index'),
        ];
    }
}
