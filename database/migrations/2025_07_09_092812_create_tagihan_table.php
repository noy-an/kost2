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
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id();
             $table->foreignId('booking_id')->constrained('booking')->onDelete('cascade');
             $table->integer('bayar_per_bulan')->default();
             $table->integer('jumlah_bayar')->default();
             $table->date('tanggal_tagihan');
             $table->string('status_pembayaran')->default('success');
            $table->string('payment_token')->nullable();
            $table->string('payment_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan');
    }
};
