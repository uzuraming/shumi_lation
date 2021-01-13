@extends('layouts.app')

@section('content')
    <div class="row">
        <ul>
            <div class="">
                名前：{{ $requester->name }}
            </div>
            <div class="">
                メッセージ：{{ $requester->pivot->message }}
            </div>
        </ul>
        
        </div>
    </div>


    <div class="row">
        <div>

                {{-- 許可ボタン --}}
                {{-- {!! link_to_route('chat_requests.accept_request', '許可',['id' => $requester->id], ['class'=> 'btn btn-light' ] ) !!} --}}
                {!! Form::open(['route' => ['chat_requests.accept_request', $requester->id],'method' => 'put']) !!}
                

                    {!! Form::submit('送信', ['class' => 'btn btn-primary']) !!}

                {!! Form::close() !!}
                    
                {{-- 拒否ボタン --}}
                {{-- {!! link_to_route('chat_requests.destroy', '拒否',['id' => $requester->id], ['class'=> 'btn btn-danger' ] ) !!} --}}
                {{-- {!! Form::open(['route' => ['chat_requests.destroy', $requester->id, 'method' => 'delete']]) !!} --}}
                {!! Form::open(['route' => ['chat_requests.destroy', $requester->id], 'method' => 'delete']) !!}
                    {!! Form::submit('削除', ['class' => 'btn btn-danger']) !!}
                {!! Form::close() !!}
        </div>
         
    </div>
@endsection
