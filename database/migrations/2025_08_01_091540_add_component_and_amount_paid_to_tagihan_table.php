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
        Schema::table('tagihan', function (Blueprint $table) {
            $table->string('component')->nullable()->after('booking_id');
            $table->decimal('amount_paid', 15, 2)->default(0)->after('component');
            
            // If you want to make the existing columns nullable (optional)
            $table->integer('bayar_per_bulan')->nullable()->change();
            $table->integer('jumlah_bayar')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('tagihan', function (Blueprint $table) {
            $table->dropColumn('component');
            $table->dropColumn('amount_paid');
            
            // Revert the nullable changes if you made them
            $table->integer('bayar_per_bulan')->nullable(false)->change();
            $table->integer('jumlah_bayar')->nullable(false)->change();
        });
    }
};