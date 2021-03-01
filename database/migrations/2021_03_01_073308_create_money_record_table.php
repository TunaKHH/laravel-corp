<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoneyRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('money_record', function (Blueprint $table) {

            $table->id();
            $table->foreignId('user_id')->constrained();

            $table->integer('amount')->comment('花費金額');
            $table->string('remark')->comment('備註');
            $table->softDeletes($column = 'deleted_at', $precision = 0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('money_record');
    }
}
