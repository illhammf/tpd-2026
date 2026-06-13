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
        Schema::create('pengaturan_websites', function (Blueprint $table) {
            $table->id();

            $table->string('nama_website')->default('Tukang Print Dadakan');
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();

            $table->string('judul_hero')->nullable();
            $table->text('deskripsi_hero')->nullable();
            $table->string('gambar_hero')->nullable();

            $table->string('nomor_whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();

            $table->string('qris')->nullable();
            $table->string('nomor_dana')->nullable();
            $table->string('nomor_bri')->nullable();
            $table->string('atas_nama_bri')->nullable();

            $table->string('jam_operasional')->nullable();
            $table->text('teks_footer')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_websites');
    }
};
