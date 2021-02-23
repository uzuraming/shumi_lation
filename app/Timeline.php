<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    
    // create関数で必須の項目をここに記述
    protected $fillable = ['content'];


    /** JSONに含めるアクセサ */
    protected $appends = [
        'favorited_by_user', 'favorite_count'
    ];

    public function getFavoritedByUserAttribute()
    {
        if (\Auth::guest()) {
            return false;
        }
        return \Auth::user()->is_favoriting($this->id);
        
    }

    public function getFavoriteCountAttribute()
    {
        return $this->favoriting_user->count();
        
    }





    /**
     * この投稿を所有するユーザ。（ Userモデルとの関係を定義）
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //このタイムラインがお気に入りしているタイムライン
    public function favoriting_user(){
        return $this->belongsToMany(User::class, 'favorites', 'favorite_id', 'user_id');
    }
 
}
