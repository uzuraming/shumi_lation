<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\ChatRoom;


class ChatRequestController extends Controller
{
    // リクエストを送る関数
    public function store(Request $request, $id){
        
        // バリデーション
        $request->validate([
            'message' => 'required',
        ]);
        
        // ログイン中のユーザー
        $auth = \Auth::user();

        // リクエストを送られていたらfalseを返す
        if(User::findOrFail($id)->is_requesting($auth->id)){
            return false;
        }else{
            // メッセージの内容
            $message = $request->message;
            // リクエストをを送信
            $auth->send_request($id, $message);

        }
        


    }

    // リクエスト一覧
    public function index(){
        if(\Auth::check()){
            // ログイン中のユーザー
            $auth = \Auth::user();
            // リクエスト一覧
            $requesters = $auth->chat_requester()->wherePivot('approval', false)->orderBy('created_at', 'desc')->paginate(5);
        }else{
            $requesters = [];
        }
        
        return $requesters;


    }

    public function show($id){

        // ログイン中のユーザー
        $auth = \Auth::user();     
        $requester = $auth->chat_requester()->findOrFail($id);
        
        return view('chat_requests.show', [
            'requester' => $requester
        ]);

    }

    // リクエストを承認する
    public function accept_request($id){
  
        // ログイン中のユーザー
        $auth = \Auth::user();
        // リクエストしてきたユーザー
        $requester = $auth->chat_requester()->findOrFail($id);

        // リクエストを承認
        $auth->accept_request($id);

        // chatRoomを作成して、参加する
        $chat_room = new ChatRoom;
        $chat_room->save();
        $auth->join_chat_room($chat_room->id);
        $requester->join_chat_room($chat_room->id);
        
       
    }

    // リクエストを却下する関数
    public function refuse_request($id){
  
        $auth = \Auth::user();
        
        $auth->refuse_request($id);        
       
    }

    


    // リクエストを外す
    public function remove_request($id){
        $auth = \Auth::user();
        $requesting = $auth->remove_request($id);     
    }

}

