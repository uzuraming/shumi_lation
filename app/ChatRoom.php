<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    // messagesテーブルとの関係を定義
    public function messages(){
        return $this->belongsToMany(User::class, 'messages', 'chat_room_id', 'user_id')->withPivot('message')->withTimestamps();
    }


    // chat_usersテーブルとの関係を定義
    public function chat_room_mate(){
        return $this->belongsToMany(User::class, 'chat_user', 'chat_room_id', 'user_id')->withTimestamps();
    }



}
