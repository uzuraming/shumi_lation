<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserFollowController extends Controller
{
    // フォローする処理
    public function store($id){
        // フォローする
        \Auth::user()->follow($id);
    }

    // アンフォローする処理
    public function destroy($id){
        // アンフォローする
        \Auth::user()->unfollow($id);

        
    }


}
