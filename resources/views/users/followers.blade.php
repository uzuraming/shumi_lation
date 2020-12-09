@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="">
            {{-- タブ --}}
            @include('users.navtabs')
            {{-- ユーザ一覧 --}}
            @include('users.users')
        </div>
    </div>
@endsection