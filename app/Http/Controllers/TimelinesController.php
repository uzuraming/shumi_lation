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
        $timeline = new Timeline;

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

    // フォローしているtimelineを表示
    public function index()
    {
        $data = [];
        if (\Auth::check()) {
            // 認証済みユーザ（閲覧者）を取得
            $user = \Auth::user();
            // ユーザとフォロー中ユーザの投稿の一覧を作成日時の降順で取得
            $timelines = $user->feed_timelines()->orderBy('created_at', 'desc')->paginate(5);

            $data = [
                'user' => $user,
                'timelines' => $timelines,
            ];
            
        }
        // Welcomeビューでそれらを表示
        return view('welcome',$data);

        
    }


}
