@extends('layouts.app')

@section('content')

    <h1>作品の編集</h1>

    <div class="row">
        <div class="col-6">
            {!! Form::model($work, ['route' => ['works.update', $work->id], 'method' => 'put']) !!}

                <div class="form-group">
                    {!! Form::label('title', 'タイトル') !!}
                    {!! Form::text('title', null,['class' => 'form-control']) !!}
                    {!! Form::label('content', '内容：') !!}
                    {!! Form::textarea('content', null, ['class' => 'form-control']) !!}
                </div>

                {!! Form::submit('投稿', ['class' => 'btn btn-primary']) !!}

            {!! Form::close() !!}
        </div>
    </div>

@endsection