<?php

namespace Database\Seeders;

use App\Models\Pembayaran;
use Illuminate\Database\Seeder;

class PembayaranSeeder extends Seeder
{
    public function run(): void
    {
        Pembayaran::create([
            'pesanan_id' => 1,
            'metode_pembayaran' => 'Cash',
            'channel_pembayaran' => 'Cash',
            'jumlah_bayar' => 5000,
            'status_pembayaran' => 'Cash Saat COD',
            'catatan_pembayaran' => 'Pembayaran dilakukan saat COD di kampus.',
        ]);
    }
}