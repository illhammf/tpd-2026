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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanans')->cascadeOnDelete();

            $table->enum('metode_pembayaran', ['Cash', 'Online'])->default('Cash');
            $table->enum('channel_pembayaran', ['Cash', 'QRIS', 'DANA', 'BRI'])->default('Cash');

            $table->integer('jumlah_bayar')->default(0);
            $table->string('bukti_transfer')->nullable();

            $table->enum('status_pembayaran', [
                'Belum Bayar',
                'Menunggu Validasi',
                'Lunas',
                'Ditolak',
                'Cash Saat COD'
            ])->default('Belum Bayar');

            $table->timestamp('tanggal_bayar')->nullable();
            $table->text('catatan_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
