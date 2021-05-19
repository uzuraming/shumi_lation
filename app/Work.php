<?php

namespace App;
use Storage; // 追加

use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    // タイトルと内容は入力必須にしておく
    protected $fillable = [
        'title',
        'content',
        'genre',
        'img_path'
    ];


    /** JSONに含めるアクセサ */
    protected $appends = [
        'bookmarked_by_user', 'bookmark_count', 'url'
    ];


    // 作品をブックマークしたか
    public function getBookmarkedByUserAttribute()
    {
        if (\Auth::guest()) {
            return false;
        }
        return \Auth::user()->is_bookmarking($this->id);
        
    }

    // ブックマークの数を返す関数
    public function getBookmarkCountAttribute()
    {
        return $this->bookmarking_user->count();
        
    }

    // 画像にURLをつける
    public function getUrlAttribute()
    {
        // 画像があれば、AWSS3のURLを返す
        if($this->img_path){
            return Storage::cloud()->url($this->img_path);
        }else{
            return null;
        }
        
    }
    

    // この作品を所有するユーザー
    public function user(){
        return $this->belongsTo(User::class);
    }

    // この作品につくコメント
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    // コメント数を計測する関数
    public function loadRelationshipCounts()
    {
        // この関数により、リレーション名_countというインスタンスが追加される
        $this->loadCount(['comments']); 
    }

    // ログイン中のユーザーがこの作品を所有するユーザーであるか判別する関数
    public function isMyWork(){

        // ログイン中のユーザーID
        $loginUser = \Auth::user()->id;
        // 作品を所有するユーザーID
        $workOwner = $this->user->id;

        if( $loginUser == $workOwner ){
            return true;
        }else{
            return false;
        }

    }


    //このタイムラインがお気に入りしている作品
    public function bookmarking_user(){
        return $this->belongsToMany(User::class, 'bookmarks', 'bookmark_id', 'user_id');
    }
}
