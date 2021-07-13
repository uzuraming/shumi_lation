<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{

    
    // messagesテーブルとの関係を定義
    public function messages(){
        return $this->belongsToMany(User::class, 'messages', 'chat_room_id', 'user_id')->withPivot('message')->withTimestamps();
    }

    // 
    // chat_usersテーブルとの関係を定義

    public function chat_room_mate(){
        return $this->belongsToMany(User::class, 'chat_user', 'chat_room_id', 'user_id')->withTimestamps();
    }
    // JSONに含めるアクセサ 
    protected $appends = [
        'recent_message'
    ];


    // 画像にURLをつける
    public function getRecentMessageAttribute()
    {
        if($this->messages()->count()>0){
            return $this->messages()->orderBy('messages.created_at', 'desc')->first();
        }else{
            return null;
        }
        
    }

    

    // タイムライン、フォロワー、フォローを計測する関数
    public function loadRelationshipCounts()
    {
        // この関数により、リレーション名_countというインスタンスが追加される
        $this->loadCount(['timelines', 'followings', 'followers']); 
    }


}
