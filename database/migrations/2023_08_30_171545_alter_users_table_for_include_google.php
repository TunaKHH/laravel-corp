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
        Schema::table('users', function (Blueprint $table) {
            // 密碼設為可空
            $table->string('password')->nullable()->change();
            // google
            $table->string('google_id')->nullable()->comment('google id');
            $table->string('google_token')->nullable()->comment('google token');
            $table->string('google_refresh_token')->nullable()->comment('google refresh token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
