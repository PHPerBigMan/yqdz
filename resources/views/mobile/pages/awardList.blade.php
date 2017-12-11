@extends('mobile/layout', ['title' => '奖励明细'])

@section('content')
    <div class="m-award-list">
        @foreach($awardList as $item)
            <div class="award-item">
                <div class="item-left">
                    <div class="goods-name">{{$item->snop->snopArray->title}}</div>
                    <div class="award-type">{{$item->level}}级推广佣金</div>
                </div>
                <div class="item-right">
                    <div class="item-date">{{$item->created_at}}</div>
                    <div class="money-add">+{{$item->money}}</div>
                    {{--<div class="money-minus">-1000</div>--}}
                </div>
            </div>
        @endforeach
    </div>
@endsection