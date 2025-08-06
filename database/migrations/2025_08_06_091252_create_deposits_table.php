<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Explicitly reference the 'booking' table (singular)
            $table->foreignId('booking_id')->constrained('booking')->onDelete('cascade');
            
            $table->decimal('amount', 12, 2);
            $table->string('payment_token');
            $table->string('status_pembayaran')->default('success');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deposits');
    }
};