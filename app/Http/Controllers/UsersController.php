<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Userモデルを追加
use App\User;

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
    public function show($id)
    {
        // idでユーザーの情報を表示
        $user = User::findOrfail($id);

        // ユーザの投稿の一覧を作成日時の降順で取得
        $timelines = $user->timelines()->orderBy('created_at', 'desc')->paginate(10);

        return view('users.show', [
            'user' => $user,
            'timelines' => $timelines
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
        // 自分のユーザーか識別する。違えば、リダイレクトする
        if(Auth::user()->id == $id){
            // ユーザーを取得
            $user = User::findOrFail($id);
            // ユーザー情報を上書き
            $user->name = $request->name;
            $user->profile = $request->profile;
            $user->interest = $request->interest;
            $user->wanted = $request->wanted;
            $user->save();

            // リダイレクト
            return redirect('/users/'.$id);
        }else{
            // そうじゃなければ戻る
            return back();
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

    
}
