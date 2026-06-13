<?php

namespace Database\Seeders;

use App\Models\KontakMasuk;
use Illuminate\Database\Seeder;

class KontakMasukSeeder extends Seeder
{
    public function run(): void
    {
        KontakMasuk::create([
            'nama' => 'Jefry Sunupurwa Asri',
            'email' => 'djambred@gmail.com',
            'nomor_whatsapp' => '089512345678',
            'subjek' => 'Tanya layanan print',
            'pesan' => 'Kak, kalau print warna untuk laporan bisa ambil besok di kampus?',
            'status_pesan' => 'Baru',
        ]);
    }
}