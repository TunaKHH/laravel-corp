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
        // 刪除users table的google相關欄位, 和 line 相關欄位
        Schema::table('users', function (Blueprint $table) {
            $table->removeColumn('google_id');
            $table->removeColumn('google_token');
            $table->removeColumn('google_refresh_token');
            $table->removeColumn('line_id');
        });
        Schema::create('user_social_accounts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->comment('使用者id');
            $table->string('provider')->comment('第三方登入提供者');
            $table->string('provider_id')->comment('第三方登入提供者id');
            $table->string('token')->nullable()->comment('第三方登入提供者token');
            $table->string('refresh_token')->nullable()->comment('第三方登入提供者refresh token');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['provider', 'provider_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // google
            $table->string('google_id')->nullable()->comment('google id');
            $table->string('google_token')->nullable()->comment('google token');
            $table->string('google_refresh_token')->nullable()->comment('google refresh token');
            // line
            $table->string('line_id')->nullable()->comment('line id');
        });
        Schema::dropIfExists('user_social_accounts');

    }
};
