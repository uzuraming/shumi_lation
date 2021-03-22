<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookmarksController extends Controller
{
    // お気に入りする関数
    public function store($id){
        \Auth::user()->bookmark($id);

    }

    // お気に入り解除する関数
    public function destroy($id){
        \Auth::user()->unbookmark($id);

    }

    // お気に入り一覧関数
    public function index(){
        $works = \Auth::user()->bookmarks()->with(['user'])->orderBy('created_at', 'desc')->paginate(5);

        return $works;
    }
}
