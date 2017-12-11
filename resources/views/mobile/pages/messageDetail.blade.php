@extends('mobile/layout')

@section('content')
    <div class="m-message-detail">
        <div class="message-title">{{$msgDetail->title}}</div>
        <div class="message-date">{{$msgDetail->create_time}}</div>
        <div class="message-content">
            {{$msgDetail->content}}
        </div>
    </div>
@endsection