<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->unsignedBigInteger('user_id')->nullable(); // ユーザーのId
            $table->unsignedBigInteger('chat_room_id');  // チャットルームのId

            $table->string('message'); // メッセージ


            // 外部キー制約
            //ユーザーが削除されたらNULLにする
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null'); 
            // チャットルームが削除されたらチャットも削除する
            $table->foreign('chat_room_id')->references('id')->on('chat_rooms')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
