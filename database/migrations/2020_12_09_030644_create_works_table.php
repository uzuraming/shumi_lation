<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('works', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id'); // ユーザーID
            $table->string('title'); // タイトル
            $table->string('content', 10000); // コンテンツ
            $table->string('img_path', 1024)->nullable(); // 画像
            $table->timestamps();

            // 外部キー制約
            $table->foreign('user_id')->references('id')->on('users'); // ユーザーIDと紐づけ
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('works');
    }
}
