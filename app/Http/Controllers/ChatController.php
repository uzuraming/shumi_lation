<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(){
        $auth = \Auth::user();
        $chat_rooms = $auth->chat_rooms;

        return view('chat_rooms.index', [
            'auth' => $auth,
            'chat_rooms' => $chat_rooms
        ]);

    }

    public function show($id){
        $auth = \Auth::user();
        $chat_room = $auth->chat_rooms()->findOrFail($id);
        $messages = $chat_room->messages;

        return view('chat_rooms.show', [
            'auth' => $auth,
            'chat_room' => $chat_room,
            'messages' => $messages
        ]);
      
    }

    public function send_message(Request $request, $id){
        $auth = \Auth::user();
        $message = $request->message;
        $auth->send_message($id, $message);

        return back();

    }


}
