<?php

namespace Database\Seeders;

use App\Models\StokBarang;
use Illuminate\Database\Seeder;

class StokBarangSeeder extends Seeder
{
    public function run(): void
    {
        StokBarang::insert([
            [
                'nama_barang' => 'Kertas A4',
                'kategori' => 'Kertas',
                'jumlah' => 10,
                'satuan' => 'rim',
                'status_stok' => 'Ready',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nama_barang' => 'Tinta Hitam',
                'kategori' => 'Tinta',
                'jumlah' => 2,
                'satuan' => 'botol',
                'status_stok' => 'Ready',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nama_barang' => 'Tinta Warna',
                'kategori' => 'Tinta',
                'jumlah' => 1,
                'satuan' => 'botol',
                'status_stok' => 'Menipis',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nama_barang' => 'Cover Jilid',
                'kategori' => 'Jilid',
                'jumlah' => 20,
                'satuan' => 'pcs',
                'status_stok' => 'Ready',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}