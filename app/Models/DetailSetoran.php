<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailSetoran extends Model
{
    use HasFactory;

    protected $table = 'detail_setoran';

    protected $fillable = [
        'setoran_id',
        'toko_id',
        'jumlah_terjual',
        'harga_satuan',
        'total_uang',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'total_uang'   => 'decimal:2',
    ];

    // ── RELASI ──────────────────────────────

    // Detail ini bagian dari satu setoran
    public function setoran()
    {
        return $this->belongsTo(Setoran::class, 'setoran_id');
    }

    // Detail ini untuk satu toko
    public function toko()
    {
        return $this->belongsTo(Toko::class, 'toko_id');
    }
}