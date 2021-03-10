<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(){
        $auth = \Auth::user();
        $chat_rooms = $auth->chat_rooms()->with(['chat_room_mate' => function ($query) {
            $auth = \Auth::user();
            $query->whereNotIn('user_id', [$auth->id]);
        }])->orderBy('created_at', 'desc')->paginate(5);

        return $chat_rooms;

    }

    public function show($id){
        $auth = \Auth::user();
        $chat_room = $auth->chat_rooms()->findOrFail($id);
        $messages = $chat_room->messages()->orderBy('messages.created_at', 'asc')
        ->paginate(10);

        return (['messages' => $messages, 'your_id' => $auth->id]);

      
    }

    public function send_message(Request $request, $id){
        $auth = \Auth::user();
        $message = $request->message;
        $auth->send_message($id, $message);
    }


}
