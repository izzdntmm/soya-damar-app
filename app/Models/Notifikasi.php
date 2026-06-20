<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'icon',
        'url',
        'dibaca_at',
    ];

    protected $casts = [
        'dibaca_at' => 'datetime',
    ];

    // Relasi ke user penerima
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope: belum dibaca
    public function scopeBelumDibaca($query)
    {
        return $query->whereNull('dibaca_at');
    }

    // Helper: apakah sudah dibaca?
    public function sudahDibaca(): bool
    {
        return $this->dibaca_at !== null;
    }
}