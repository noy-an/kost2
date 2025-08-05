<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `tagihan` MODIFY `keterangan` ENUM('paid', 'unpaid', 'cicilan') DEFAULT 'unpaid'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `tagihan` MODIFY `keterangan` ENUM('paid', 'unpaid') DEFAULT 'unpaid'");
    }
};
