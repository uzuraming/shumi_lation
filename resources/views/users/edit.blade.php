@extends('layouts.app')

@section('content')

    <h1>id: {{ $user->id }} のプロフィール編集ページ</h1>

    <div class="row">
        <div class="col-6">
            {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'put']) !!}

                <div class="form-group">
                    {!! Form::label('name', 'ユーザー名:') !!}
                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('profile', 'プロフィール:') !!}
                    {!! Form::text('profile', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('interest', '興味のある分野:') !!}
                    {!! Form::text('interest', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('wanted', '求める人材:') !!}
                    {!! Form::text('wanted', null, ['class' => 'form-control']) !!}
                </div>

                {!! Form::submit('更新', ['class' => 'btn btn-primary']) !!}

            {!! Form::close() !!}
        </div>
    </div>

@endsection