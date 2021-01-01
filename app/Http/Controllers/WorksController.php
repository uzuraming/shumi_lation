<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Work;

class WorksController extends Controller
{

    
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
        return redirect('/');
        
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
        
        $title = $request->title;
        $content = $request->content;



        // 作品を作成
        $work->update([
            'title' => $title,
            'content' => $content
        ]);

        // トップページにリダイレクト
        return redirect('/');


        
    }

    public function destroy($id){

        $work = Work::findOrFail($id);
        $its_my_work = $work->isMyWork(); // 自分の作品かどうか判別

        if($its_my_work){
            $work->delete();
        }

        

        // トップページにリダイレクト
        return redirect('/');

        
    }
}
