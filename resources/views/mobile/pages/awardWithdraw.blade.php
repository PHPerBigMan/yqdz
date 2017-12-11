@extends('mobile/layout', ['title' => '提现'])

@section('content')
    <div class="m-award-withdraw">
        <div class="input-wrap">
            <div class="input-label">提现金额</div>
            <div class="money-input">
                <input type="number" name="money">
            </div>
            <div class="input-tips">当前可提现金额¥{{$myAward}}，<span class="withdraw-all">全部提现</span></div>
        </div>
        <div class="withdraw-tips">系统将在3到5个工作日内把提现金额达到您的微信账户</div>

        <div class="withdraw-btn">提现</div>
    </div>
    <script>
        $('.withdraw-all').click(function () {
            $("input[name='money']").val({{$myAward}});

        })
        $('.withdraw-btn').click(function () {
            var money=$("input[name='money']").val();
            if(money==0){
                layer.open({content: '请输入提现金额',btn: '好的！',yes:function (index) {
                    location.reload();
                }})
            }
            var url='/home/WxPay/withDraw';
            data={money:money};
            $.get(url,data,function(res){
                layer.open({content: res.msg,btn: '好的！',yes:function (index) {
                    location.reload();
                }})
            })
            return false;
        })
    </script>
@endsection