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
        Schema::create('detail_pesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanans')->cascadeOnDelete();
            $table->foreignId('layanan_id')->constrained('layanans')->cascadeOnDelete();

            $table->string('nama_file')->nullable();
            $table->string('file_path')->nullable();

            $table->enum('jenis_print', ['Hitam Putih', 'Warna'])->nullable();
            $table->enum('ukuran_kertas', ['A4', 'F4'])->default('A4');

            $table->integer('jumlah_halaman')->default(0);
            $table->integer('jumlah_copy')->default(1);
            $table->integer('harga_satuan')->default(0);
            $table->integer('subtotal')->default(0);

            $table->boolean('pakai_jilid')->default(false);
            $table->boolean('pakai_laminating')->default(false);

            $table->text('catatan_detail')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanans');
    }
};
