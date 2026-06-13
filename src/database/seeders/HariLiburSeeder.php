<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HariLibur;

class HariLiburSeeder extends Seeder
{
    public function run(): void
    {
        HariLibur::insert([
            [
                'tanggal' => '2026-01-01',
                'nama_libur' => 'Tahun Baru',
                'keterangan' => 'Libur Nasional',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'tanggal' => '2026-08-17',
                'nama_libur' => 'Hari Kemerdekaan RI',
                'keterangan' => 'Libur Nasional',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'tanggal' => '2026-12-25',
                'nama_libur' => 'Hari Natal',
                'keterangan' => 'Libur Nasional',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}