<?php

namespace Database\Seeders;

use App\Models\Layanan;
use Illuminate\Database\Seeder;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        Layanan::insert([
            [
                'kategori_layanan_id' => 1,
                'nama_layanan' => 'Print Dokumen',
                'slug' => 'print-dokumen',
                'harga_dasar' => 500,
                'satuan' => 'lembar',
                'butuh_upload_file' => true,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'kategori_layanan_id' => 1,
                'nama_layanan' => 'Fotokopi',
                'slug' => 'fotokopi',
                'harga_dasar' => 500,
                'satuan' => 'lembar',
                'butuh_upload_file' => false,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'kategori_layanan_id' => 1,
                'nama_layanan' => 'Jilid Biasa',
                'slug' => 'jilid-biasa',
                'harga_dasar' => 5000,
                'satuan' => 'paket',
                'butuh_upload_file' => false,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'kategori_layanan_id' => 1,
                'nama_layanan' => 'Laminating',
                'slug' => 'laminating',
                'harga_dasar' => 3000,
                'satuan' => 'lembar',
                'butuh_upload_file' => false,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'kategori_layanan_id' => 2,
                'nama_layanan' => 'Rapihin Tugas',
                'slug' => 'rapihin-tugas',
                'harga_dasar' => 10000,
                'satuan' => 'layanan',
                'butuh_upload_file' => true,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'kategori_layanan_id' => 2,
                'nama_layanan' => 'Belajar Bareng',
                'slug' => 'belajar-bareng',
                'harga_dasar' => 15000,
                'satuan' => 'sesi',
                'butuh_upload_file' => false,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}