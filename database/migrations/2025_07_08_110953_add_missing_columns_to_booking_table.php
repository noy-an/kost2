<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('booking', function (Blueprint $table) {
          
            $table->string('jenis_pembayaran', 20);
            $table->string('payment_token')->nullable();
            $table->string('payment_url')->nullable();
        });
    }

    public function down()
    {
       
    }
};