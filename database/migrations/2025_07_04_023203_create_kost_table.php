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
        Schema::create('kost', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor_kamar')->unique();
            $table->integer('nomor_lantai');
            $table->integer('harga');
            $table->string('deskripsi')->nullable();
            $table->enum('status',['kosong', 'terisi', 'dibooking']);
            $table->string('fasilitas')->nullable();
            $table->string('foto1');
            $table->string('foto2');
            $table->string('foto3');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kost');
    }
};
