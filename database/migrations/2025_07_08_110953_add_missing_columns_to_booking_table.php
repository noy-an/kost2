<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->decimal('persentase_pembayaran', 5, 2)->after('dp');
            $table->string('jenis_pembayaran', 20)->after('persentase_pembayaran');
            $table->string('payment_token')->nullable()->after('status_booking');
            $table->string('payment_url')->nullable()->after('payment_token');
        });
    }

    public function down()
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->dropColumn([
                'persentase_pembayaran',
                'jenis_pembayaran',
                'payment_token',
                'payment_url'
            ]);
        });
    }
};