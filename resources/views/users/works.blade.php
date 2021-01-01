@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="">
            {{-- タブ --}}
            @include('users.navtabs')

            {{-- 作品一覧 --}}
            @foreach ($works as $work)
                <div>{{ $work->title }}</div>
            @endforeach
            
        </div>
    </div>
@endsection