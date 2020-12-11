<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'profile', 'interest', 'wanted'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ユーザーに属するタイムライン

    public function timelines()
    {
        return $this->hasMany(Timeline::class);
    }


    // ユーザーがフォローしているユーザー
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    
    
    // ユーザーをフォローしているユーザー
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }


    public function is_following($userId)
    {
        // フォロー中ユーザの中に $userIdのものが存在するか
        return $this->followings()->where('follow_id', $userId)->exists();
    }

    // フォローする関数
    public function follow($userId){
        // フォロー済かどうか確認し、その結果をこの変数に入れる
        $exist = $this->is_following($userId);
        // 対象とするユーザーが自分自身か否かを確認し、その結果をこの変数に入れる
        $its_me = $this->id == $userId;

        if($exist || $its_me){
            // すでにフォローしているか、自分自身なら何もしない
            return false;
        }else{
            // 未フォローかつ自分自身でないならフォロー

            $this->followings()->attach($userId);
            return true;
        }
    }

    // アンフォローする関数
    public function unfollow($userId){
        // フォロー済かどうか確認し、その結果をこの変数に入れる
        $exist = $this->is_following($userId);
        // 対象とするユーザーが自分自身か否かを確認し、その結果をこの変数に入れる
        $its_me = $this->id == $userId;

        if($exist && !$its_me){
            // すでにフォローしていて、自分自身でないならフォローを外す

            $this->followings()->detach($userId);
            return true;
            
        }else{
            // 未フォローか,自分自身ならなにもしない
            return false;
        }
    }

    // タイムライン、フォロワー、フォローを計測する関数
    public function loadRelationshipCounts()
    {
        // この関数により、リレーション名_countというインスタンスが追加される
        $this->loadCount(['timelines', 'followings', 'followers']); 
    }

    // フォローしているユーザーのタイムラインを取得する関数
    public function feed_timelines()
    {
        // このユーザがフォロー中のユーザのidを取得して配列にする
        $userIds = $this->followings()->pluck('users.id')->toArray();
        // このユーザのidもその配列に追加
        $userIds[] = $this->id;
        // それらのユーザが所有する投稿に絞り込む
        return Timeline::whereIn('user_id', $userIds);
    }

}
