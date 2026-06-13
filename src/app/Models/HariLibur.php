<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HariLibur extends Model
{
    protected $fillable = [
        'tanggal',
        'nama_libur',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'status' => 'boolean',
    ];

    public function getLabelAttribute() // Biar bisa dipanggil dengan $hariLibur->label
    {
        return $this->nama_libur . ' (' . $this->tanggal->format('d-m-Y') . ')';
    }
}