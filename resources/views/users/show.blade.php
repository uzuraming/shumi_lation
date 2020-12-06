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
                興味のある分野：{{ $user->interst }}
            </div>
            <div class="">
                求める人材：{{ $user->wanted }}
        </ul>
        
        </div>
    </div>
          
    
    <div class="row">
        @if (Auth::user()->id == $user->id)
            {{-- 編集ボタン --}}
            {!! link_to_route('users.edit', '編集',['user' => $user->id], ['class'=> 'btn btn-light' ] ) !!}
            
            {{-- 削除ボタン --}}
            {!! link_to_route('users.destroy', '削除',['user' => $user->id], ['class'=> 'btn btn-danger' ] ) !!}
            
        @endif  
    </div>
@endsection