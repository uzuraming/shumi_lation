<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


//追記
class User extends Authenticatable implements MustVerifyEmail
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


    // ユーザーに属する作品
    public function works()
    {
        return $this->hasMany(Work::class);
    }

    // このユーザーが書いたコメント
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    // ユーザーに属するタイムライン

    public function timelines()
    {
        return $this->hasMany(Timeline::class);
    }

    // タイムライン、フォロワー、フォローを計測する関数
    public function loadRelationshipCounts()
    {
        // この関数により、リレーション名_countというインスタンスが追加される
        $this->loadCount(['timelines', 'followings', 'followers']); 
    }


    // --------------------------------------------
    // 以下フォローに関する関数
    // --------------------------------------------

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


    // --------------------------------------------
    // 以下お気に入りに関する関数
    // --------------------------------------------
    
    //このユーザーがお気に入りしているタイムライン
    public function favorites(){
        return $this->belongsToMany(Timeline::class, 'favorites', 'user_id', 'favorite_id');
    }

    // お気に入りしているか否かを判断する関数
    public function is_favoriting($timelineId){
        // フォロー中ユーザの中に $userIdのものが存在するか判断する関数
        return $this->favorites()->where('favorite_id', $timelineId)->exists();      
    }


    // お気に入りする関数
    public function favorite($timelineId){
        // お気に入り済かどうか確認し、その結果をこの変数に入れる
        $exist = $this->is_favoriting($timelineId);

        if($exist){
            // すでにお気に入り済みなら何もしない
            return false;
        }else{
            // お気に入りしていなかったらお気に入りする
            $this->favorites()->attach($timelineId);
            return true;
        }
    }

    // お気に入りを外す関数
    public function unfavorite($timelineId){
        // お気に入り済かどうか確認し、その結果をこの変数に入れる
        $exist = $this->is_favoriting($timelineId);

        if($exist){
            // すでにお気に入りしている場合は外す

            $this->favorites()->detach($timelineId);
            return true;
            
        }else{
            // お気に入りしていないならなにもしない
            return false;
        }
    }


    // チャットリクエストを送ってきている人
    public function chat_requester(){
        return $this->belongsToMany(User::class, 'reaction', 'to_user_id', 'from_user_id')->withPivot('message', 'approval')->withTimestamps();
    }

    // チャットリクエストを送った人
    public function chat_requesting(){
        return $this->belongsToMany(User::class, 'reaction', 'from_user_id','to_user_id')->withPivot('message', 'approval')->withTimestamps();
    }

    // リクエストを送ったか否かを判断する関数
    public function is_requesting($userId){
        return $this->chat_requesting()->where('to_user_id', $userId)->exists();      
    }

    


    // リクエストを作成する関数
    public function send_request($userId, $message){

        $exist = $this->is_requesting($userId);

        if($exist){
            // なにもしない
            return false; 
        }else{
            $this->chat_requesting()->attach($userId,[
                'message' => $message,
            ]);
            return true;
        };
    }

    // リクエストを作成する関数
    public function remove_request($userId){

        $exist = $this->is_requesting($userId);

        if($exist){
            $this->chat_requesting()->detach($userId);
            return true;
            
        }else{
            // なにもしない
            return false; 
        };
    }
    



    // messagesテーブルとの関係を定義
    public function messages(){
        return $this->belongsToMany(ChatRoom::class, 'messages', 'user_id','chat_room_id')->withPivot('message')->withTimestamps();
    }

    // chatテーブルとの関係を定義
    public function chat_rooms(){
        return $this->belongsToMany(ChatRoom::class, 'chat_user', 'user_id','chat_room_id')->withTimestamps();
    }


    public function is_join_chat_room($chatRoomId)
    {
        // ユーザが参加中のチャットに、引数のチャットルームIDがあるか
        return $this->chat_rooms()->where('chat_room_id', $chatRoomId)->exists();
    }

    // チャットルームに自分と指定のユーザーを招待する関数
    public function join_chat_room($chatRoomId){
        $exist = $this->is_join_chat_room($chatRoomId);
        if($exist){
            // なにもしない
            return false; 
        }else{
            $this->chat_rooms()->attach($chatRoomId);
            return true;
        };

    }


    // リクエストを承認すみか判定する関数

    public function is_accept_request($userId){
        if($this->chat_requester()->where('from_user_id', $userId)->exists()){
            return $this->chat_requester()->where('from_user_id', $userId)->first()->pivot->approval == true;
        }else{
            return false;
        }
        
    }

    // リクエストを拒否する関数
    public function refuse_request($userId){
        if(!$this->is_accept_request($userId)){
            $this->chat_requester()->detach($userId);
            return true;

        }else{
            return false;
        };
        
           
    }

    // リクエストを許可する関数
    public function accept_request($userId){
        if(!$this->is_accept_request($userId)){
            $this->chat_requester()->updateExistingPivot($userId, ['approval' => true]);
            return true;
        }else{
            return false;
        };
        

    }

    // チャットを送信する関数

    public function send_message($chatRoomId, $message){

        $is_join_room = $this->is_join_chat_room($chatRoomId);
        if($is_join_room){
            $this->messages()->attach($chatRoomId,[
                'message' => $message,
            ]);
            return true;

        }else{
            return false;
        }
        
    }



    

    // TODO:チャットを送信する関数
    // public function send_message($userId) {
    //     $is_accecpt = $this->chat_requester()
    //                         ->where('from_user_id', $userId)
    //                         ->firstOrFail()
    //                         ->approval
    //     if($is_accecpt){

    //     }

        
        
    // }

    // TODO:画像を送信する関数

    

}
