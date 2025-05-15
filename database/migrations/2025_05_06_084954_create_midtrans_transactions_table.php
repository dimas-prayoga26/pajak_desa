<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::create('midtrans_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tagihan_id')->constrained('tagihans')->onDelete('cascade');
            $table->string('order_id')->unique();
            $table->enum('transaction_status', ['pending', 'settlement', 'capture', 'cancelled', 'deny'])->default('pending');
            $table->decimal('gross_amount', 12, 2);
            $table->string('payment_type');
            $table->string('fraud_status')->nullable();
            $table->timestamps();
        });

    }

    
    public function down()
    {
        Schema::dropIfExists('midtrans_transactions');
    }
};
