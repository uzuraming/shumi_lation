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
        $user = \Auth::user();
        // メッセージの内容
        $message = $request->message;

        $user->send_request($id, $message);

        return back();


    }

    // リクエスト一覧
    public function index(){
        // ログイン中のユーザー
        $auth = \Auth::user();
        
        $requesters = $auth->chat_requester()->orderBy('created_at', 'desc')->paginate(5);
        
        return view('chat_requests.index', [
            'auth' => $auth,
            'requesters' => $requesters
        ]);


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
        $auth->join_chat_room($chat_room->id);
        $requester->join_chat_room($chat_room->id);
        
        return back();
    }
    public function destroy($id){
        $auth = \Auth::user();
        $requester = $auth->chat_requester()->findOrFail($id);
        $auth->refuse_request($id);
        
        return back();
        
    }

    


}

