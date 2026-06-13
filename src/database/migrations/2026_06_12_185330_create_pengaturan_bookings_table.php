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
        Schema::create('pengaturan_bookings', function (Blueprint $table) {
            $table->id();

            $table->string('nama_pengaturan')->default('Pengaturan Booking Utama');

            $table->boolean('wajib_h_minus_satu')->default(true);
            $table->time('batas_jam_booking')->default('22:00');

            $table->boolean('tutup_sabtu')->default(true);
            $table->boolean('tutup_minggu')->default(true);
            $table->boolean('tutup_tanggal_merah')->default(true);

            $table->integer('maksimal_lembar_per_hari')->default(500);
            $table->integer('maksimal_lembar_per_order')->default(150);

            $table->integer('maksimal_jadwal_belajar_per_jam')->default(1);
            $table->integer('minimal_hari_rapihin_tugas')->default(2);

            $table->integer('biaya_jilid')->default(5000);
            $table->integer('biaya_laminating')->default(3000);
            $table->integer('biaya_prioritas')->default(5000);

            $table->boolean('aktifkan_order_prioritas')->default(true);
            $table->boolean('wajib_upload_bukti_online')->default(true);

            $table->integer('ongkir_kampus')->default(0);
            $table->boolean('lokasi_luar_kampus_perlu_konfirmasi')->default(true);
            $table->boolean('ojek_online_perlu_konfirmasi')->default(true);

            $table->text('catatan_booking')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_bookings');
    }
};
