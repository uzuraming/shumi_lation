<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    // タイトルと内容は入力必須にしておく
    protected $fillable = [
        'title',
        'content',
    ];

    // この作品を所有するユーザー
    public function user(){
        return $this->belongsTo(User::class);
    }

    // ログイン中のユーザーがこの作品を所有するユーザーであるか判別する関数
    public function isMyWork(){

        // ログイン中のユーザーID
        $loginUser = \Auth::user()->id;
        // 作品を所有するユーザーID
        $workOwner = $this->user()->id;

        if( $loginUser == $workOwner ){
            return true;
        }else{
            return false;
        }

    }
}
