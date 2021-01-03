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
    <div class="row">
        {{--コメント一覧 --}}
        @foreach ($comments as $comment)
        <ul>
            <li>
                <div>{{ $comment->user->name }}|</div>
                <div>{{ $comment->comment }}</div>
                @if ($work->isMywork() || $comment->isMyComment())
                    {{-- 削除ボタン --}}
         
                    {!! Form::open(['route' => ['users.comment_destroy', ['id' => $user->id,'workId' => $work->id, 'commentId'=> $comment->id]], 'method' => 'delete']) !!}
                        {!! Form::submit('削除', ['class' => "btn btn-danger"]) !!}
                    {!! Form::close() !!}
                    
                @endif 
            </li>
        </ul>
            
            
        @endforeach
        <h1>コメントの投稿</h1>

        <div class="row">
            <div class="">
                {!! Form::open(['route' => ['users.comment_store', 'id' => $user->id, 'workId' => $work->id, 'method' => 'post']]) !!}

                        {!! Form::label('comment', '内容：') !!}
                        {!! Form::text('comment', null, ['class' => 'form-control']) !!}

                    {!! Form::submit('投稿', ['class' => 'btn btn-primary']) !!}

                {!! Form::close() !!}
            </div>
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