<?php

namespace App\Http\Controllers;
use Storage;
use Illuminate\Http\Request;


// Userモデルを追加
use App\User;
use App\Comment;

// Authを追加
use Auth;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    // ユーザー詳細画面
    public function show(Request $request, $id)
    {
        // idでユーザーの情報を表示
        $user = User::findOrFail($id);

        $user->loadRelationshipCounts();


        if(Auth::check()){
            $is_following = Auth::user()->is_following($id);
            $is_accepted_request_by_you = $user->is_accept_request(Auth::user()->id);
            $is_accepted_request = Auth::user()->is_accept_request($user->id);
            $is_send_request =  Auth::user()->is_requesting($id);
            $is_sent_request_by_you = $user->is_requesting(Auth::user()->id);
            $its_me = Auth::user()->id == $id;
        }else{
            $is_following = null;
            $its_me = false;
            $is_accepted_request_by_you = false;
            $is_accepted_request = false;
            $is_send_request = false;
            $is_sent_request_by_you =false;
      
        }

        

        // ユーザの投稿の一覧を作成日時の降順で取得
        $timelines = $user->timelines()->orderBy('created_at', 'desc')->paginate(5, ["*"], 'timelinepage')->appends(["workpage" => $request->input('workpage')]);
        $works = $user->works()->orderBy('created_at', 'desc')->paginate(5, ["*"], 'workpage')->appends(["timelinepage" => $request->input('timelinepage')]);;

        return (['user' => $user,
            'timelines' => $timelines, 
            'works' => $works, 
            'is_following' => $is_following, 
            'its_me' => $its_me,
            'is_accepted_request_by_you' => $is_accepted_request_by_you,
            'is_send_request' => $is_send_request,
            'is_sent_request_by_you' => $is_sent_request_by_you,
            'is_accepted_request' => $is_accepted_request,

        ]);

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    // ユーザー編集画面
    public function edit($id)
    {
        // 自分のユーザーか識別する。違えば、リダイレクトする
        if(Auth::user()->id == $id){
            // ユーザーを取得
            $user = User::findOrFail($id);
            // ユーザー編集ビューでそれを表示
            return view('users.edit', [
                'user' => $user,
            ]);
        }else{
            // そうじゃなければ戻る
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    // 編集を実行する関数
    public function update(Request $request, $id)
    {   
        if(Auth::check()){
            // 自分のユーザーか識別する。違えば、リダイレクトする
            if(Auth::user()->id == $id){

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
                // ユーザーを取得
                $user = User::findOrFail($id);
                // ユーザー情報を上書き

                if($request->img_path==="default"){
                    // 作品を作成
                    $user->name = $request->name;
                    $user->profile = $request->profile;
                    $user->interest = $request->interest;
                    $user->wanted = $request->wanted;
                    $user->save();
                }else{
                    // ストレージ上の過去のファイルを削除
                    $s3_delete = Storage::disk('s3')->delete($user->img_path);
                    // 作品を作成
                    $user->name = $request->name;
                    $user->profile = $request->profile;
                    $user->interest = $request->interest;
                    $user->wanted = $request->wanted;
                    $user->img_path = $fileName;
                    $user->save();
   
    
                }
           

            }else{
                // そうじゃなければ戻る
                return false;
            }

        }else{
            return false;
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    // 削除する関数
    public function destroy($id)
    {
        // 自分のユーザーか識別する。違えば、リダイレクトする
        if(Auth::user()->id == $id){
            // ユーザーを取得
            $user = User::findOrFail($id);
            // ユーザーを削除
            $user->delete();
        }else{
            // そうじゃなければ戻る
            return back();
        }
    }

    // フォローしている人を取得する処理
    public function followings($id){
        $user = User::findOrFail($id);

        // フォローしている人の人数を計測
        $user->loadRelationshipCounts();

        // ユーザーのフォローしているユーザーを取得
        $followings = $user->followings()->paginate(10);

        // 表示
        return view('users.followings', ([
            'user' => $user,
            'users' => $followings,
        ]));
        
    }
    
    // フォロワーを取得する処理
    public function followers($id){

        $user = User::findOrFail($id);

        // フォローされている人の人数を計測
        $user->loadRelationshipCounts();

        // ユーザーのフォローされているユーザーを取得
        $followers = $user->followers()->paginate(10);

        // 表示
        return view('users.followers', ([
            'user' => $user,
            'users' => $followers,
        ]));
        
    }

    // 作品一覧
    public function works($id){

        $user = User::findOrFail($id);
        $works = $user->works()->paginate(10);

        // 表示
        return view('users.works',([
            'user' => $user,
            'works' => $works,
        ]));
        
    }

    // 作品一覧
    public function worksShow($id, $workId){

        $user = User::findOrFail($id);
        $work = $user->works()->findOrFail($workId);
        $comments = $work->comments()->paginate(10);


        // 表示
        return view('users.works_show',([
            'user' => $user,
            'work' => $work,
            'comments' => $comments,
        ]));
        
    }

    public function commentStore(Request $request, $id, $workId){
        $from_user = \Auth::user();

        $user = User::findOrFail($id);
        $work = $user->works()->findOrFail($workId);

        $new_comment = new Comment;
        $new_comment->comment = $request->comment;
        // コメントしたユーザーに関連付ける

        $new_comment->user()->associate($from_user);
        $new_comment->work()->associate($work);

        $new_comment->save();



        return back();

    }

    public function commentDestroy($id, $workId, $commentId){
        // ユーザー、作品、コメントの変数
        $user = User::findOrFail($id);
        $work = $user->works()->findOrFail($workId);
        $comment = $work->comments()->findOrFail($commentId);

        // ログイン中のユーザーがコメントの持ち主、あるいはコメントがついてる作品の持ち主か判断する
        $its_my_work = $work->isMywork();
        $its_my_comment = $comment->isMyComment($commentId);

        if($its_my_work || $its_my_comment ){
            $comment->delete();
        }

        return back();

    }

        
    
}
