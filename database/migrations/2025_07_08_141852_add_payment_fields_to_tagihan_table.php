<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tagihan', function (Blueprint $table) {
            $table->string('status_pembayaran')->default('pending')->after('tanggal_tagihan');
            $table->string('payment_token')->nullable()->after('status_pembayaran');
            $table->string('payment_url')->nullable()->after('payment_token');
        });
    }

    public function down()
    {
        Schema::table('tagihan', function (Blueprint $table) {
            $table->dropColumn(['status_pembayaran', 'payment_token', 'payment_url']);
        });
    }
};