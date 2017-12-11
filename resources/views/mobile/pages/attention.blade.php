@extends('mobile/layout', ['title' => '我的关注'])

@section('content')
    <div class="m-attention">
        @foreach($attention as $item)
        <a href="/goods/{{$item->commodityid}}/{{$item->past}}">
            <div class="attention-item">
                <img class="item-left" src="{{$item->thumbnail}}">
                <div class="item-right" style="width: 100%">
                    <h3 class="item-title">{{$item->title}}</h3>
                    <div class="item-info">
                        <div class="item-price">¥{{$item->money}}</div>
                        @if($item->endtime>date('Y-m-d'))
                            <div class="item-date">{{$item->endtime}}</div>
                        @else
                            <div class="icon-finished"></div>
                        @endif
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
@endsection