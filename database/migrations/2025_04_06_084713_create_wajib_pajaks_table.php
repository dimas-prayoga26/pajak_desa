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
        Schema::create('wajib_pajaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // relasi ke users
            $table->string('nop')->unique();     // Nomor Objek Pajak
            $table->text('alamat');              // Alamat lengkap
            $table->decimal('luas_bumi', 10, 2); // Luas tanah
            $table->decimal('luas_bangunan', 10, 2); // Luas bangunan
            // $table->year('tahun');
            $table->enum('status_bayar', ['belum', 'dibayar'])->default('belum'); // status tagihan
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wajib_pajaks');
    }
};
