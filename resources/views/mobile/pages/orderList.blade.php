@extends('mobile/layout', ['title' => '我的订单'])

@section('content')
    <div class="m-order">
        <div class="p-tab-nav">
            <div class="tab-item @if($type == "all") active @endif "><a href="/order-list/">全部</a></div>
            <div class="tab-item @if($type == "10") active  @endif"><a href="/order-list/10" >待付款</a></div>
            <div class="tab-item @if($type == "20") active @endif"><a href="/order-list/20" @if($type == "20")  @endif>待发货</a></div>
            <div class="tab-item @if($type == "30") active @endif"><a href="/order-list/30" @if($type == "30")  @endif>待收货</a></div>
            <div class="tab-item @if($type == "50") active @endif"><a href="/order-list/50" @if($type == "50")  @endif>待评价</a></div>
        </div>
        {{--橙色按钮--}}
        @foreach($data as $v)
            <div class="order-item">
                <div class="order-head">
                    <div>
                        <div class="order-date">下单日期:{{$v['created_at']}}</div>
                        <div class="order-number">订单编号：{{$v['transaction']}}</div>
                    </div>
                    <div class="order-status">
                        @if($v['order_state']== 0)
                            已取消
                        @elseif($v['order_state'] == 10)
                            待付款
                        @elseif($v['order_state'] == 20)
                            待发货
                        @elseif($v['order_state'] == 30)
                            待收货
                        @elseif($v['order_state'] == 40)
                            待评价
                        @elseif($v['order_state'] == 50)
                            已完成
                        @elseif($v['order_state'] == 60)
                            退货中
                        @elseif($v['order_state'] == 70)
                            退货完成
                        @elseif($v['order_state'] == 80)
                            拒绝退货
                        @endif
                    </div>
                </div>
                @foreach($v['snop'] as $val)
                    <div class="order-goods" num="{{$val['nums']}}" commodityid="{{$val['snopjson']->commodityid}}" onclick="godetail({{$v['orderid']}})">
                        <div class="left-area">
                            <img src="{{$val['snopjson']->thumbnail}}" alt="" class="goods-img">
                            <div class="goods-name">{{$val['snopjson']->title}}</div>
                        </div>

                        <div class="right-area">
                            <div class="goods-price">价格：{{$val['snopjson']->money}}</div>
                            <div class="goods-count">数量：{{$val['nums']}}个</div>
                        </div>
                    </div>
                @endforeach

                <div class="order-money">
                    <div class="order-carriage">运费：¥{{$v['carriage']}}</div>
                    <div class="order-total">合计：¥{{$v['money']}}</div>
                </div>

                <div class="order-btn">
                    @if($v['order_state']==10)
                        <button onclick="quxiao({{$v['orderid']}})">取消订单</button>
                        <button class="orange" orderid="{{$v['orderid']}}"><a style="color:#ffffff;">去付款</a></button>
                    @elseif($v['order_state']==20)
                        <button >待发货</button>
                    @elseif($v['order_state']== 30 && $v['return_status']== 0)
                        <button style="margin-left: 0px" onclick="tuihuo({{$v['orderid']}},{{ $v['money'] }})">退货</button>
                        <button><a href="/wuliuList/?id={{$v['orderid']}}">查看物流</a></button>
                        <button onclick="shouhuo({{$v['orderid']}})">确认收货</button>
                    @elseif($v['order_state']== 40 && $v['evaluation_state'] ==0)

                        <button class="evaluate" evaluate="{{$v['orderid']}}">评价</button>
                    @elseif($v['order_state']== 50 && $v['evaluation_state']== 0)


                    @endif
                </div>
            </div>
        @endforeach
    </div>
    <script>
        function quxiao(id) {
            layer.open({
                content: '确定要取消该订单吗？'
                ,btn: ['确定', '不要']
                ,yes: function(index){
                    $.get("/home/order/cancle/?id="+id,function (data) {
                        if (data['status'] == 200){
                            layer.open({content: data['msg'],btn: '好的！',yes:function (index) {
                                location.reload();
                            }})
                        }else {
                            layer.open({content: data['msg'],skin: 'msg',time: 2 //2秒后自动关闭
                            });
                        }
                    })
                }
            });
        }
        $('.orange').click(function () {
            var commodityid='';
            var num='';
            var orderid=$(this).attr('orderid');
            $(this).parent().parent().find('.order-goods').each(function (l,v) {
                commodityid+=$(v).attr('commodityid')+',';
                num+=$(v).attr('num')+',';
            })
            commodityid=(commodityid.substring(commodityid.length-1)==',')?commodityid.substring(0,commodityid.length-1):commodityid;
            num=(num.substring(num.length-1)==',')?num.substring(0,num.length-1):num;
            window.location.href='/goods-order/?id='+commodityid+'&num='+num+'&status=1'+'&orderid='+orderid;
        })
        {{--function pay(){--}}
        {{--$(this).$('.order-goods').each(function (k,v) {--}}
        {{--console.log($(v).attr('commodityid'));--}}
        {{--});--}}
        {{--//        alert(123456);--}}
        {{--href="/goods-order/?orderid={{$v['orderid']}}&status=1"--}}
        {{--}--}}
        function tuihuo(id,money) {
            layer.open({
                content: '确定要申请退货吗？'
                ,btn: ['确定', '不要']
                ,yes: function(index){
//                    $.get("/home/order/refund/?id="+id,function (data) {
//                        if (data['status'] == 200){
//                            layer.open({content: data['msg'],btn: '好的！',yes:function (index) {
//                                location.reload();
//                            }})
//                        }else {
//                            layer.open({content: data['msg'],skin: 'msg',time: 2 //2秒后自动关闭
//                            });
//                        }
//                    })
//                    alert(123456);
                    window.location.href = '/order/'+id+'/refund?get='+money;
                }
            });
        }
        function shouhuo(id) {
            layer.open({
                content: '确定已经收到货物了？'
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.get("/home/order/shouhuo/?id="+id,function (data) {
                        if (data['status'] == 200){
                            layer.open({content: data['msg'],btn: '好的！',yes:function (index) {
                                location.reload();
                            }})
                        }else {
                            layer.open({content: data['msg'],skin: 'msg',time: 2 //2秒后自动关闭
                            });
                        }
                    })
                }
            });
        }
        $('.evaluate').click(function(){
            var evaluate=$(this).attr('evaluate');
            window.location.href = '/order/'+evaluate+'/comment';

        })
        function godetail(id) {
            window.location.href = '/order/'+id;
        }

    </script>
@endsection
{{--绿色按钮--}}
{{--@foreach(range(1, 2) as $item)--}}
{{--<div class="order-item">--}}
{{--<div class="order-head">--}}
{{--<div>--}}
{{--<div class="order-date">下单日期 2017/02/02</div>--}}
{{--<div class="order-number">订单编号：1992883930873747</div>--}}
{{--</div>--}}
{{--<div class="order-status">待付款</div>--}}
{{--</div>--}}

{{--<div class="order-goods">--}}
{{--<div class="left-area">--}}
{{--<img src="{{imgurl('pic-chanpin-xianmgudongtai@2x.png')}}" alt="" class="goods-img">--}}
{{--<div class="goods-name">特级橄榄 新鲜青橄榄特级橄榄 新鲜青橄榄特级橄榄</div>--}}
{{--</div>--}}

{{--<div class="right-area">--}}
{{--<div class="goods-price">价格：¥156.00</div>--}}
{{--<div class="goods-count">数量：1个</div>--}}
{{--</div>--}}
{{--</div>--}}

{{--<div class="order-money">--}}
{{--<div class="order-carriage">运费：¥5.00</div>--}}
{{--<div class="order-total">合计：¥161.00</div>--}}
{{--</div>--}}

{{--<div class="order-btn">--}}
{{--<button>取消订单</button>--}}
{{--<button class="green">去付款</button>--}}
{{--</div>--}}
{{--</div>--}}
{{--@endforeach--}}
{{--黑色按钮--}}
{{--@foreach(range(1, 2) as $item)--}}
{{--<div class="order-item">--}}
{{--<div class="order-head">--}}
{{--<div>--}}
{{--<div class="order-date">下单日期 2017/02/02</div>--}}
{{--<div class="order-number">订单编号：1992883930873747</div>--}}
{{--</div>--}}
{{--<div class="order-status">待付款</div>--}}
{{--</div>--}}

{{--<div class="order-goods">--}}
{{--<div class="left-area">--}}
{{--<img src="{{imgurl('pic-chanpin-xianmgudongtai@2x.png')}}" alt="" class="goods-img">--}}
{{--<div class="goods-name">特级橄榄 新鲜青橄榄特级橄榄 新鲜青橄榄特级橄榄</div>--}}
{{--</div>--}}

{{--<div class="right-area">--}}
{{--<div class="goods-price">价格：¥156.00</div>--}}
{{--<div class="goods-count">数量：1个</div>--}}
{{--</div>--}}
{{--</div>--}}

{{--<div class="order-money">--}}
{{--<div class="order-carriage">运费：¥5.00</div>--}}
{{--<div class="order-total">合计：¥161.00</div>--}}
{{--</div>--}}

{{--<div class="order-btn">--}}
{{--<button>取消订单</button>--}}
{{--<button class="black">去付款</button>--}}
{{--</div>--}}
{{--</div>--}}
{{--@endforeach--}}