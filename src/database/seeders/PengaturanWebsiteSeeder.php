<?php

namespace Database\Seeders;

use App\Models\PengaturanWebsite;
use Illuminate\Database\Seeder;

class PengaturanWebsiteSeeder extends Seeder
{
    public function run(): void
    {
        PengaturanWebsite::create([
            'nama_website' => 'Tukang Print Dadakan',
            'judul_hero' => 'Print Cepat, Antar ke Kampus',
            'deskripsi_hero' => 'Layanan print mahasiswa untuk tugas, laporan, modul, proposal dan skripsi.',
            'nomor_whatsapp' => '0895336900466',
            'nomor_dana' => '0895336900466',
            'nomor_bri' => '1234567890',
            'atas_nama_bri' => 'Ilham Firmansyah',
            'jam_operasional' => 'Senin - Jumat',
            'alamat' => 'Universitas Esa Unggul Tangerang',
            'teks_footer' => 'Copyright © 2026 Tukang Print Dadakan',
        ]);
    }
}