<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Work;
use App\Comment;

class WorksController extends Controller
{

     // フォローしているtimelineを表示
     public function index()
     {    
        $works = Work::with(['user'])->orderBy('created_at', 'desc')->paginate(5);
 
         // Welcomeビューでそれらを表示
        return $works;
    }

    // workの詳細
    public function show($id){
        $work = Work::with(['user'])->findOrFail($id);
        if(\Auth::check()){
            $its_mine = \Auth::user()->id == $work->user->id;
        }else{
            $its_mine = false;
        };

        $comments = $work->comments();
        $work->its_mine = $its_mine;
        $work->loadRelationshipCounts();

        return $work;
    }

    // コメント一覧API
    public function comments($id){
        $work = Work::findOrFail($id);
        $comments = $work->comments()->with(['user'])->paginate(5);
        if(\Auth::check()){
            $its_mine = \Auth::user()->id == $work->user->id;
        }else{
            $its_mine = false;
        };

        $comments->its_mine = $its_mine;
        return (['comments' => $comments, 'its_mine' => $its_mine]);
    }

    // コメント削除API
    public function comment_store(Request $request, $id){
        $work = Work::findOrFail($id);
    
        if(\Auth::check()){
            $comments = new Comment;
            $comments->comment = $request->comment;
            $comments->user()->associate(\Auth::user());
            $comments->work()->associate($work);
            $comments->save();
        }else{
            return false;
        };

        
    }
    public function comment_destroy($id,$commentId){
        // ユーザー、作品、コメントの変数
        $user = \Auth::user();
        $work = Work::findOrFail($id);
        $comment = $work->comments()->findOrFail($commentId);

        // ログイン中のユーザーがコメントの持ち主、あるいはコメントがついてる作品の持ち主か判断する
        $its_my_work = $work->isMywork();
        $its_my_comment = $comment->isMyComment($commentId);

        if($its_my_work || $its_my_comment ){
            $comment->delete();
        }else{
            return false;
        };

    }

    

    // 作品作成画面
    public function create(){
        $work = new Work;

        // 画面に表示
        return view('works.create',([
            'work' => $work,
        ]));
    }


    public function store(Request $request){
        // バリデーション
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required|max:10000'
        ]);
        
        // 現在ログイン中のユーザー
        $user = \Auth::user();
        $title = $request->title;
        $content = $request->content;



        // 作品を作成
        $user->works()->create([
            'title' => $title,
            'content' => $content
        ]);

        // トップページにリダイレクト
        // return redirect('/');
        
    }

    public function edit($id){
        $work = Work::findOrFail($id);

        // 画面に表示
        return view('works.edit',([
            'work' => $work,
        ]));

        
    }

    public function update(Request $request, $id){        
        
        // バリデーション
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required|max:10000'
        ]);

        $work = Work::findOrFail($id);
        if($work->user->id == \Auth::user()->id){
            $title = $request->title;
            $content = $request->content;

            // 作品を作成
            $work->update([
                'title' => $title,
                'content' => $content
            ]);
        }
        

    


        
    }

    public function destroy($id){

        $work = Work::findOrFail($id);
        $its_my_work = $work->isMyWork(); // 自分の作品かどうか判別

        if($its_my_work){
            $work->delete();
        }

        
    }
}
