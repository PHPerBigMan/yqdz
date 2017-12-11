@extends('mobile/layout', ['title' => '平台说明'])

@section('content')
    <div class="m-explain">
        <div class="explain-title">平台说明</div>
        <div class="explain-content">
            <p>{!! $explain->content !!}</p>
        </div>
    </div>
@endsection