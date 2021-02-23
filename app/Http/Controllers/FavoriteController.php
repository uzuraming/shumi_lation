<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    // お気に入りする関数
    public function store($id){
        \Auth::user()->favorite($id);

    }

    // お気に入り解除する関数
    public function destroy($id){
        \Auth::user()->unfavorite($id);

    }

    // お気に入り一覧関数
    public function index(){
        $timelines = \Auth::user()->favorites()->with(['user'])->orderBy('created_at', 'desc')->paginate(5);

        return $timelines;
    }


}
