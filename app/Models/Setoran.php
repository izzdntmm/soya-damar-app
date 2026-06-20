<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setoran extends Model
{
    use HasFactory;

    protected $table = 'setoran';

    protected $fillable = [
        'sales_id',
        'tanggal',
        'status',
        'dikirim_at',
        'acc_at',
    ];

    protected $casts = [
        'tanggal'     => 'date',
        'dikirim_at'  => 'datetime',
        'acc_at'      => 'datetime',
    ];

    // ── RELASI ──────────────────────────────

    // Setoran ini milik satu sales
    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    // Satu setoran punya banyak detail
    public function detail()
    {
        return $this->hasMany(DetailSetoran::class, 'setoran_id');
    }

    // ── HELPER ──────────────────────────────

    // Total uang dari semua detail setoran ini
    public function totalUang()
    {
        return $this->detail->sum('total_uang');
    }

    // Total item terjual dari semua detail
    public function totalTerjual()
    {
        return $this->detail->sum('jumlah_terjual');
    }
}