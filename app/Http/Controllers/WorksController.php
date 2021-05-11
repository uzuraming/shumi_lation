<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Work;
use App\Comment;

use Storage;


class WorksController extends Controller
{ 
     public function index(Request $request)
     {  
        
        if($request->genre == 'all'){
            $works = Work::with(['user'])->withCount('bookmarking_user')->orderBy($request->is_rank, 'desc')->paginate(6);

        }else{
            $works = Work::with(['user'])->withCount('bookmarking_user')->where('genre', $request->input('genre'))->orderBy($request->is_rank, 'desc')->paginate(6);

        }
         // Welcomeビューでそれらを表示
        return $works;
    }

    public function search_word(Request $request)
     {  
        
        $works = Work::with(['user'])->where('title', 'like', '%'.$request->input('search_word').'%')->orderBy('created_at', 'desc')->paginate(6);

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

  
        \Log::Debug($request->img_path);
            
        if ($request->img_path) {
            $fileName = time() . $request->img_path->getClientOriginalName();
            Storage::cloud()
            ->putFileAs('', $request->img_path, $fileName, 'public');
            // 画像のURL取得
            // $filePath= Storage::disk('s3')->url($fileName);
        } else {
            // 画像がない場合は何もしない
            $fileName  = null;
        }



            $user = \Auth::user();
            $title = $request->title;
            $genre = $request->genre;
            $content = $request->content;
   

            // 作品を作成
            $user->works()->create([
                'title' => $title,
                'genre' => $genre,
                'content' => $content,
                'img_path' => $fileName 
            ]);


        // todo:エラー時に画像を削除するトランザクション処理を書く
        

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

        \Log::Debug(strval($request->img_path));   
        $tmp_str = strval($request->img_path); // 受け取った値を文字列に変換
        if($tmp_str  == "default"){
            // 画像データが”default”の文字列だった場合、画像関連の処理はしない
            $fileName  = null;
        }elseif ($request->img_path) {
            $fileName = time() . $request->img_path->getClientOriginalName();
            Storage::cloud()
            ->putFileAs('', $request->img_path, $fileName, 'public');
 
        }else{
            // 画像がない場合は何もしない
            $fileName  = null;
        }

    

        $work = Work::findOrFail($id);

        if($work->user->id == \Auth::user()->id){
            $title = $request->title;
            $genre = $request->genre;
            $content = $request->content;

            if($request->img_path==="default"){
                // 作品を作成
                $work->title = $title;
                $work->genre = $genre;
                $work->content = $content;
                $work->save();
            }else{
                // ストレージ上の過去のファイルを削除
                $s3_delete = Storage::disk('s3')->delete($work->img_path);
                // 作品を作成
                $work->title = $title;
                $work->genre = $genre;
                $work->content = $content;
                $work->img_path = $fileName;
                $work->save();

                

            }
            
        }   
    }

    public function destroy($id){

        $work = Work::findOrFail($id);
        $its_my_work = $work->isMyWork(); // 自分の作品かどうか判別

        if($its_my_work){
            // ストレージ上の過去のファイルを削除
            $s3_delete = Storage::disk('s3')->delete($work->img_path);
            $work->delete();

        }

        
    }
}
