<?php

namespace Database\Seeders;

use App\Models\KategoriLayanan;
use Illuminate\Database\Seeder;

class KategoriLayananSeeder extends Seeder
{
    public function run(): void
    {
        KategoriLayanan::insert([
            [
                'nama_kategori' => 'Cetak Dokumen',
                'slug' => 'cetak-dokumen',
                'deskripsi' => 'Layanan print, fotokopi, jilid dan laminating',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Bantuan Akademik',
                'slug' => 'bantuan-akademik',
                'deskripsi' => 'Layanan rapihin tugas dan belajar bareng',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}