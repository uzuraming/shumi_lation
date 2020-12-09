@extends('layouts.app')

@section('content')

    <h1>投稿</h1>

    <div class="row">
        <div class="col-6">
            {!! Form::model($timeline, ['route' => ['timeline.store', 'method' => 'post']]) !!}

                <div class="form-group">
                    {!! Form::label('content', '内容：') !!}
                    {!! Form::textarea('content', null, ['class' => 'form-control']) !!}
                </div>

                {!! Form::submit('投稿', ['class' => 'btn btn-primary']) !!}

            {!! Form::close() !!}
        </div>
    </div>

@endsection