<?php

namespace Database\Seeders;

use App\Models\Pengiriman;
use Illuminate\Database\Seeder;

class PengirimanSeeder extends Seeder
{
    public function run(): void
    {
        Pengiriman::create([
            'pesanan_id' => 1,
            'metode_pengiriman' => 'COD Kampus',
            'alamat_pengiriman' => 'Universitas Esa Unggul Tangerang',
            'biaya_pengiriman' => 0,
            'status_pengiriman' => 'Belum Siap',
            'catatan_pengiriman' => 'Diambil atau COD sekitar jam istirahat.',
        ]);
    }
}