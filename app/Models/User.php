<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasPushSubscriptions;

    protected $fillable = [
        'nama',
        'email',
        'no_hp',
        'alamat',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ── RELASI ──────────────────────────────

    // Satu sales punya banyak toko
    public function toko()
    {
        return $this->hasMany(Toko::class, 'sales_id');
    }

    // Satu sales punya banyak setoran
    public function setoran()
    {
        return $this->hasMany(Setoran::class, 'sales_id');
    }

    // Helper: cek apakah user adalah admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Helper: cek apakah user adalah sales
    public function isSales(): bool
    {
        return $this->role === 'sales';
    }

    
}