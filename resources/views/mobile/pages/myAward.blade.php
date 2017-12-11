@extends('mobile/layout', ['title' => '推荐有奖'])

@section('content')
    <div class="m-my-award">
        <img src="{{imgurl('img-award-money.png')}}" alt="" class="award-img">
        <h3 class="award-money">￥{{$myAward}}</h3>
        <div class="award-label">奖励金</div>
        <a href="/award/withdraw"><button class="withdraw-btn">提现</button></a>
        <a href="/award/list"><button class="detail-btn">明细</button></a>

        <p class="guize-box-title">佣金规则说明</p>
        <input type="hidden" id="content" value="{{ $content }}">
    </div>
    <script>
        $('.guize-box-title').click(function () {
            layer.open({
                content: $('#content').val()
                ,btn: '好的'
            });
        })
    </script>
@endsection