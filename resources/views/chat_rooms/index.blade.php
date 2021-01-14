@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="">
            {{-- 作品一覧 --}}
            @foreach ($chat_rooms as $chat_room)
                @if($chat_room->messages()->latest()->first())
                    <div>{{ $chat_room->messages()->latest()->first()->pivot->message }}</div>
                @else
                <div>新しいチャット</div>
                @endif
            @endforeach            
        </div>
    </div>  
@endsection