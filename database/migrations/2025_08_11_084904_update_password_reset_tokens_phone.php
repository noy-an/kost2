<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            // Hapus kolom email kalau udah nggak dipake
            if (Schema::hasColumn('password_reset_tokens', 'email')) {
                $table->dropColumn('email');
            }

            // Tambah kolom phone
            $table->string('phone')->index();
        });
    }

    public function down()
    {
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->string('email')->index();
        });
    }
};
