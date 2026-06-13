<?php

namespace Database\Seeders;

use App\Models\Pesanan;
use Illuminate\Database\Seeder;

class PesananSeeder extends Seeder
{
    public function run(): void
    {
        Pesanan::create([
            'user_id' => 2,
            'kode_pesanan' => 'TPD-0001',
            'nama_pelanggan' => 'User Account',
            'email' => 'user@admin.com',
            'nomor_whatsapp' => '0895000000000',
            'tanggal_pesan' => now()->toDateString(),
            'tanggal_pengambilan' => now()->addDay()->toDateString(),
            'jam_pengambilan' => '12:30',
            'lokasi_pengambilan' => 'Kampus Esa Unggul Tangerang',
            'detail_lokasi' => 'Lobby Kampus Esa Unggul Tangerang',
            'catatan' => 'Tolong print rapi untuk tugas kuliah.',
            'subtotal' => 5000,
            'biaya_tambahan' => 0,
            'biaya_pengiriman' => 0,
            'total_harga' => 5000,
            'status_pesanan' => 'Menunggu Konfirmasi',
        ]);
    }
}