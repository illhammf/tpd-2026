<?php

namespace Database\Seeders;

use App\Models\Testimoni;
use Illuminate\Database\Seeder;

class TestimoniSeeder extends Seeder
{
    public function run(): void
    {
        Testimoni::insert([
            [
                'nama' => 'Luqman',
                'jurusan' => 'Teknik Informatika',
                'rating' => 5,
                'komentar' => 'Print cepat, harga mahasiswa, dan bisa COD di kampus.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Adelia',
                'jurusan' => 'Sistem Informasi',
                'rating' => 5,
                'komentar' => 'Sangat membantu kalau lagi banyak tugas dan malas ke fotokopian.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}