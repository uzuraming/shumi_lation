@extends('layouts.app')

@section('content')
    <div class="row">
        <ul>
            <div class="">
                名前：{{ $user->name }}
            </div>
            <div class="">
                プロフィール：{{ $user->profile }}
            </div>
            <div class="">
                興味のある分野：{{ $user->interest }}
            </div>
            <div class="">
                求める人材：{{ $user->wanted }}
        </ul>
        
        </div>
    </div>
    <div class="col-sm-8">
        {{-- タブ --}}
        @include('users.navtabs')
    
    </div>
    @include('user_follow.follow_button')
    @include('timelines.timelines')


    <div class="row">
        <div>
            @if (Auth::user()->id == $user->id)
                {{-- 編集ボタン --}}
                {!! link_to_route('users.edit', '編集',['user' => $user->id], ['class'=> 'btn btn-light' ] ) !!}
                
                {{-- 削除ボタン --}}
                {!! link_to_route('users.destroy', '削除',['user' => $user->id], ['class'=> 'btn btn-danger' ] ) !!}
                
            @endif 
        </div>
         
    </div>
@endsection

