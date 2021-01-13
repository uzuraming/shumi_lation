@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="">
            {{-- 作品一覧 --}}
            @foreach ($requesters as $requester)
                <div>{{ $requester->name }}</div>
            @endforeach
            
        </div>
    </div>
@endsection