<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// タイムラインモデルの名前空間
use App\Timeline;

// Authをインポート
use Auth;

class TimelinesController extends Controller
{   

    // timelineを作成フォーム
    public function create(){
        $timeline = new TImeline;

        return view('timelines.create',[
            'timeline' => $timeline
        ]);
    }


    // timelineを作成
    public function store(Request $request){
        // バリデーション
        $request->validate([
            'content' => 'required|max:255',
        ]);
        
        // 現在ログイン中のユーザー
        $user = Auth::user();
        $content = $request->content;


        // ログイン状態に属するタイムラインを作成
        $user->timelines()->create([
            'content' => $content
        ]);

        // 現在ログイン中のユーザーのid
        $id = $user->id;

        // ユーザー詳細画面へリダイレクト
        return redirect('/users/'. $id);



    }

    // timelineを削除
    public function destroy($id){

        // 現在ログイン中のユーザー
         $user = Auth::user();

        // 現在ログイン中のユーザーのid
        $id = $user->id;

         // 選択されているタイムライン
        $timeline = Timeline::findOrFail($id);


        // このタイムラインがユーザーのものか判別
        if($user->id == $timeline->user()->id){
            $timeline->delete();

            // ユーザー詳細画面へリダイレクト
            return redirect('/users/'. $id);
        }else{
            return back();
        }

    }


}
