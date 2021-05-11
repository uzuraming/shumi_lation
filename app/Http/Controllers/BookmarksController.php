<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookmarksController extends Controller
{
    // ブックマークする関数
    public function store($id){
        \Auth::user()->bookmark($id);

    }

    // ブックマーク解除する関数
    public function destroy($id){
        \Auth::user()->unbookmark($id);

    }

    // ブックマーク一覧関数
    public function index(){
        $works = \Auth::user()->bookmarks()->with(['user'])->orderBy('created_at', 'desc')->paginate(6);

        return $works;
    }
}
