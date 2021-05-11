<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Events\MessageCreated;

class ChatController extends Controller
{
    // チャットルーム一覧
    public function index(){
        // ログイン中のユーザー
        $auth = \Auth::user();

        $chat_rooms = $auth->chat_rooms()->with(['chat_room_mate' => function ($query) {
            $auth = \Auth::user(); // ログイン中のユーザー
            $query->whereNotIn('user_id', [$auth->id]); // 自分以外のユーザーを添付
        }])->orderBy('created_at', 'desc')->paginate(5);
    
    

        // データを送信
        return $chat_rooms;

    }

    // チャットルーム詳細
    public function show($id){
        $auth = \Auth::user(); // ログイン中のユーザー
        $chat_room = $auth->chat_rooms()->findOrFail($id); // 詳細表示するチャットルーム
        $messages = $chat_room->messages()->orderBy('messages.created_at', 'asc')->get(); // メッセージを取得


        // データを送信
        return (['messages' => $messages, 'your_id' => $auth->id, 'chat_user' => $chat_room->chat_room_mate->whereNotIn('id', $auth->id)->first()]);

      
    }
    
    // メッセージを贈る
    public function send_message(Request $request, $id){

        // バリデーション
        $request->validate([
            'message' => 'required',
        ]);
        $auth = \Auth::user(); // ログイン中のユーザー

        // メッセージ送信
        $message = $request->message;
        $auth->send_message($id, $message);

        // メッセージを送信ともにイベント発火
        event(new MessageCreated($message, $id));
    }


}
