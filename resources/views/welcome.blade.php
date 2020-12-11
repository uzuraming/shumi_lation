@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="row">
            <div class="">
                {{-- 投稿一覧 --}}
                @include('timelines.timelines')
            </div>
        </div>
    @else
        <div class="center jumbotron">
            <div class="text-center">
                <h1>WELCOME</h1>
                {{-- ユーザ登録ページへのリンク --}}
                {!! link_to_route('signup.get', 'Sign up now!', [], ['class' => 'btn btn-lg btn-primary']) !!}
            </div>
        </div>
    @endif
@endsection