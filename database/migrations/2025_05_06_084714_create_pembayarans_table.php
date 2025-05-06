<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tagihan_id')->constrained()->onDelete('cascade'); // relasi ke tagihan
            $table->year('tahun_pembayaran'); // tahun dibayarkan
            $table->enum('status', ['belum', 'dibayar', 'dikonfirmasi'])->default('belum'); // status pembayaran
            $table->decimal('jumlah_dibayar', 12, 2); // jumlah yang dibayar
            $table->string('bukti_bayar'); // file bukti transfer
            $table->date('tanggal_bayar'); // tanggal melakukan pembayaran
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayarans');
    }
};
