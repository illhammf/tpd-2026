<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('kode_pesanan')->unique();
            $table->string('nama_pelanggan');
            $table->string('email')->nullable();
            $table->string('nomor_whatsapp');

            $table->date('tanggal_pesan')->nullable();
            $table->date('tanggal_pengambilan');
            $table->time('jam_pengambilan')->nullable();

            $table->enum('lokasi_pengambilan', [
                'Kampus Esa Unggul Tangerang',
                'Lokasi Lain'
            ])->default('Kampus Esa Unggul Tangerang');

            $table->text('detail_lokasi')->nullable();
            $table->text('catatan')->nullable();

            $table->integer('subtotal')->default(0);
            $table->integer('biaya_tambahan')->default(0);
            $table->integer('biaya_pengiriman')->default(0);
            $table->integer('total_harga')->default(0);

            $table->enum('status_pesanan', [
                'Menunggu Pembayaran',
                'Menunggu Konfirmasi',
                'Diproses',
                'Sudah Dicetak',
                'Siap Diantar',
                'Dalam Pengiriman',
                'Selesai',
                'Dibatalkan'
            ])->default('Menunggu Konfirmasi');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
