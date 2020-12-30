<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    // フォローする関数
    public function store($id){
        \Auth::user()->favorite($id);

        return back();
    }

    // アンフォローする関数
    public function destroy($id){
        \Auth::user()->unfavorite($id);

        return back();
    }


}
