<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    // 内容とユーザー名は入力必須にしておく
    protected $fillable = [
        'user_id',
        'comment',
    ];
    /** JSONに含めるアクセサ */
    protected $appends = [
        'is_your_comment'
    ];

    public function getIsYourCommentAttribute()
    {
        if (\Auth::guest()) {
            return false;
        }else if(\Auth::user()->id == $this->user->id){
            return true;
        }else{
            return false;
        }
        
    }




    // このコメントが属する作品
    public function work(){
        return $this->belongsTo(Work::class);
    }

    // このコメントを書いたユーザー
    public function user(){
        return $this->belongsTo(User::class);
    }

    // ログイン中のユーザーがこの作品を所有するユーザーであるか判別する関数
    public function isMyComment(){

        // ログイン中のユーザーID
        $loginUser = \Auth::user()->id;
        // 作品を所有するユーザーID
        $commentOwner = $this->user->id;

        if( $loginUser == $commentOwner ){
            return true;
        }else{
            return false;
        }

    }
}
