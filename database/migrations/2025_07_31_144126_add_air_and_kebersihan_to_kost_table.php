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
        Schema::table('kost', function (Blueprint $table) {
            $table->integer('air')->default(0)->after('fasilitas');
            $table->integer('kebersihan')->default(0)->after('air');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kost', function (Blueprint $table) {
            $table->dropColumn(['air', 'kebersihan']);
        });
    }
};