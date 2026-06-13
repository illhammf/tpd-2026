<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatStatusPesanan extends Model
{
    protected $fillable = [
        'pesanan_id',
        'status',
        'catatan',
        'waktu_status',
    ];

    protected $casts = [
        'waktu_status' => 'datetime',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}