<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\ChatRoom;


class ChatRequestController extends Controller
{
    // リクエストを送る関数
    public function store(Request $request, $id){
        
        
        // ログイン中のユーザー
        $auth = \Auth::user();

        if(User::findOrFail($id)->is_requesting($auth->id)){
            return false;
        }else{
            // メッセージの内容
            $message = $request->message;
            $auth->send_request($id, $message);

        }
        


    }

    // リクエスト一覧
    public function index(){
        if(\Auth::check()){
            // ログイン中のユーザー
            $auth = \Auth::user();
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

    public function accept_request($id){
  
        $auth = \Auth::user();
        $requester = $auth->chat_requester()->findOrFail($id);

        $auth->accept_request($id);

        // chatRoomを作成して、参加する
        $chat_room = new ChatRoom;
        $chat_room->save();
        $auth->join_chat_room($chat_room->id);
        $requester->join_chat_room($chat_room->id);
        
       
    }

    public function refuse_request($id){
  
        $auth = \Auth::user();
        
        $auth->refuse_request($id);        
       
    }

    


    public function remove_request($id){
        $auth = \Auth::user();
        $requesting = $auth->remove_request($id);     
    }

    


}

