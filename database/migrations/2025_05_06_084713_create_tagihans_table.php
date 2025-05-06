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
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wajib_pajak_id')->constrained()->onDelete('cascade'); // relasi ke wajib_pajaks
            $table->year('tahun'); // tahun tagihan
            $table->decimal('jumlah', 12, 2); // total tagihan PBB
            $table->enum('status_bayar', ['belum', 'dibayar', 'dikonfirmasi'])->default('belum'); // status tagihan
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tagihans');
    }
};
