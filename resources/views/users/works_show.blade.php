@extends('layouts.app')

@section('content')
    <div class="row">
        <ul>
            <div class="">
                タイトル：{{ $work->title }}
            </div>
            <div class="">
                作品{{ $work->content }}
            </div>

        </ul>
        
        </div>
    </div>

    <div class="row">
        <div>
            @if (Auth::user()->id == $user->id)
                {{-- 編集ボタン --}}
                {!! link_to_route('works.edit', '編集',['work' => $work->id], ['class'=> 'btn btn-light' ] ) !!}
                
                {{-- 削除ボタン --}}
                {!! link_to_route('works.destroy', '削除',['work' => $work->id], ['class'=> 'btn btn-danger' ] ) !!}
                
            @endif 
        </div>
         
    </div>
@endsection