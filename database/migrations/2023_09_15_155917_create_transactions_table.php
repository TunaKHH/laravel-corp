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
        Schema::create('transactions', function (Blueprint $table) {
            // 訂單資訊包含(操作者, 金額, 訂單編號, 訂單狀態(未付款, 已付款, 已取消), 訂單建立時間, 訂單更新時間)
            $table->id();
            $table->foreignId('user_id')->constrained()->comment('操作者');
            $table->string('transaction_number')->unique()->comment('訂單編號');
            $table->integer('amount')->comment('金額');
            $table->tinyInteger('status')->comment('訂單狀態, 0:未付款, 1:已付款, 2:已取消');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
