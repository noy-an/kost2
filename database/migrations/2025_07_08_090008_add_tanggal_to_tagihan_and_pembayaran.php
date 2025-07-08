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
        $table->date('tanggal_tagihan')->after('jumlah_bayar')->nullable();
    });

    Schema::table('pembayaran', function (Blueprint $table) {
        $table->date('tanggal_bayar')->after('bukti_bayar')->nullable();
    });
}

public function down()
{
    Schema::table('tagihan', function (Blueprint $table) {
        $table->dropColumn('tanggal_tagihan');
    });

    Schema::table('pembayaran', function (Blueprint $table) {
        $table->dropColumn('tanggal_bayar');
    });
}

    /**
     * Reverse the migrations.
     */
};
