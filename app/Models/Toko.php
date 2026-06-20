<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    use HasFactory;

    protected $table = 'toko';

    protected $fillable = [
        'sales_id',
        'nama_toko',
        'no_hp',
        'alamat',
        'latitude',
        'longitude',
    ];

    // ── RELASI ──────────────────────────────

    // Toko ini milik satu sales
    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    // Satu toko bisa punya banyak detail setoran
    public function detailSetoran()
    {
        return $this->hasMany(DetailSetoran::class, 'toko_id');
    }
}