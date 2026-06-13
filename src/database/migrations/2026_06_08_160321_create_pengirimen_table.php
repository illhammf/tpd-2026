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
        Schema::create('pengirimans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanans')->cascadeOnDelete();

            $table->enum('metode_pengiriman', [
                'COD Kampus',
                'Diantar Ilham',
                'Ojek Online',
                'Ambil Sendiri'
            ])->default('COD Kampus');

            $table->text('alamat_pengiriman')->nullable();
            $table->integer('biaya_pengiriman')->default(0);

            $table->enum('status_pengiriman', [
                'Belum Siap',
                'Siap Diantar',
                'Dalam Perjalanan',
                'Terkirim',
                'Dibatalkan'
            ])->default('Belum Siap');

            $table->text('catatan_pengiriman')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengirimen');
    }
};
