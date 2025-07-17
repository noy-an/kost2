<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('balasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kontak_id')->constrained('kontak')->onDelete('cascade');
            $table->string('subjek');
            $table->text('pesan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('balasan');
    }
};
