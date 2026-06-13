<?php

namespace Database\Seeders;

use App\Models\DetailPesanan;
use Illuminate\Database\Seeder;

class DetailPesananSeeder extends Seeder
{
    public function run(): void
    {
        DetailPesanan::create([
            'pesanan_id' => 1,
            'layanan_id' => 1,
            'nama_file' => 'tugas-pemrograman-web.pdf',
            'file_path' => 'pesanan/tugas-pemrograman-web.pdf',
            'jenis_print' => 'Hitam Putih',
            'ukuran_kertas' => 'A4',
            'jumlah_halaman' => 10,
            'jumlah_copy' => 1,
            'harga_satuan' => 500,
            'subtotal' => 5000,
            'pakai_jilid' => false,
            'pakai_laminating' => false,
            'catatan_detail' => 'Print satu sisi saja.',
        ]);
    }
}