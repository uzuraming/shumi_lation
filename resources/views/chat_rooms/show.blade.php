@extends('layouts.app')

@section('content')
    <div class="row">
        @if(count($messages))
        <div class="">
            {{-- 作品一覧 --}}
                @foreach ($messages as $message)
                    <div>{{$message->pivot->message}}</div>
                @endforeach   
        </div>
        @endif
    </div>

    {!! Form::open(['route' => ['chat_rooms.send_message', $chat_room->id],'method' => 'post']) !!}
        <div class="form-group">
            {!! Form::label('message', 'message') !!}
            {!! Form::text('message', null,['class' => 'form-control']) !!}
        </div>

        {!! Form::submit('送信', ['class' => 'btn btn-primary']) !!}

    {!! Form::close() !!}
@endsection
