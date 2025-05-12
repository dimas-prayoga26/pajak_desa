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
            $table->foreignId('wajib_pajak_id')->constrained()->onDelete('cascade'); 
            $table->year('tahun'); 
            $table->decimal('jumlah', 12, 2);
            $table->enum('status_bayar', ['belum', 'dibayar', 'dikonfirmasi'])->default('belum');
            $table->date('jatuh_tempo'); 
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('tagihans');
    }
};
