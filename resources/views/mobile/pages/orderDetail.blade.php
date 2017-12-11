@extends('mobile/layout', ['title' => '订单详情'])

@section('content')
    <div class="m-order order-detail">
        <div class="detail-top-section">
            <h2 class="detail-section-title">订单信息</h2>
            <div class="order-item">
                <div class="order-head">
                    <div>
                        <div class="order-date">下单日期 {{$order->created_at}}</div>
                        <div class="order-number">订单编号：{{$order->transaction}}</div>
                    </div>
                    <div class="order-status">@if($order->order_state == 0)
                            已取消
                        @elseif($order->order_state == 10)
                            待付款
                        @elseif($order->order_state == 20)
                            待发货
                        @elseif($order->order_state == 30)
                            待收货
                        @elseif($order->order_state == 50)
                            已完成
                        @elseif($order->order_state == 60)
                            退货中
                        @elseif($order->order_state == 70)
                            退货完成
                        @elseif($order->order_state == 80)
                            拒绝退货
                        @endif
                    </div>
                </div>

                @foreach($data['snop'] as $val)
                    <div class="order-goods" onclick="godetail({{$order->orderid}})">
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
                    <div class="order-carriage">运费：¥{{$order->carriage}}</div>
                    <div class="order-total">合计：¥{{$order->money}}</div>
                </div>
            </div>
        </div>
        {{--<div class="detail-section">--}}
            {{--<h2 class="detail-section-title">物流信息</h2>--}}
            {{--<div class="order-logistics">--}}
                {{--<div class="logistics-way">配送方式：{{$data['extend']->express or ''}}</div>--}}
                {{--<div class="logitics-number">运单号：{{$data['extend']->couriernumber or ''}}</div>--}}
                {{--<div class="logitics-number">发货时间：{{$data['extend']->created_at or ''}}</div>--}}
            {{--</div>--}}
        {{--</div>--}}

        <div class="detail-section">
            <h2 class="detail-section-title">收货地址</h2>
            <div class="order-address">
                <div class="address-contact">
                    <span>{{$address->name or ''}}</span>
                    <span>{{$address->phone or ''}}</span>
                </div>
                <div class="address-detail">{{$address->province or ''}} {{$address->city or ''}} {{$address->district or ''}} {{$address->address or ''}}</div>
            </div>
        </div>
        <div class="detail-section">
            <h2 class="detail-section-title">备注信息</h2>
            <div class="order-address">
                <div class="address-contact">
                    <span>{{$order->label or ''}}</span>
                </div>
                <br>
                <div>
                    <span>{{$order->beizhu or ''}}</span>
                </div>
            </div>
        </div>

        <div class="bottom-btn">
        @if($order->order_state == 0)
                            {{--已取消--}}
                        @elseif($order->order_state == 10)
                            {{--<button onclick="quxiao({{$order->orderid}})">取消订单</button>--}}
            {{--<button class="orange">去付款</button>--}}
                        @elseif($order->order_state == 20)
                            {{--<button onclick="quxiao({{$order->orderid}})">取消订单</button>--}}
            {{--<button class="orange">等待发货</button>--}}
                        @elseif($order->order_state == 30)
                            {{--<button onclick="quxiao({{$order->orderid}})">取消订单</button>--}}
            {{--<button class="orange">等待收货</button>--}}
                        @elseif($order->order_state == 50)
                            {{--<button onclick="quxiao({{$order->orderid}})">取消订单</button>--}}
            {{--<button class="orange">已完成</button>--}}
                        @elseif($order->order_state == 60)
                            {{--<button onclick="quxiao({{$order->orderid}})">取消订单</button>--}}
            {{--<button class="orange">退款中</button>--}}
                        @elseif($order->order_state == 70)
{{--                            <button onclick="quxiao({{$order->orderid}})">取消订单</button>--}}
            {{--<button class="orange">退款完成</button>--}}
                        @endif
            
        </div>
    </div>
<script type="text/javascript">
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
</script>
@endsection