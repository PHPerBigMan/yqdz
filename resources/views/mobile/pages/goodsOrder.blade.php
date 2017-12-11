@extends('mobile/layout', ['title' => '立即付款'])
@section('content')
    <div class="m-goods-order p-wrap">
        @foreach($cart as $val)
        <a href="/goods/{{$val['goods']['commodityid']}}/0">
        <div class="order-head">
            <img class="left-area" src="{{$val['goods']['thumbnail']}}">
            <div class="right-area" style="width: 100%">
                <h2 class="goods-title">{{$val['goods']['title']}}</h2>
                <div class="goods-number">
                    <span>¥{{$val['money']}}</span>
                    <span>X{{$val['buy_num']}}</span>
                </div>
            </div>
        </div>
        </a>
        @endforeach

        <div class="order-body">
            <h2 class="body-title">收货地址</h2>
            <div class="choose-address">
                <div class="address-left">
                    @if(count($address)==0)
                        <div>请点击添加默认收货地址</div>
                    @else
                        <div>
                            <span class="address-name">{{$address['name']}}</span>
                            <span class="address-phone">{{$address['phone']}}</span>
                        </div>
                        <div class="address-detail">{{$address['province']}} {{$address['city']}} {{$address['district']}} {{$address['address']}}</div>
                    @endif

                </div>
                <i class="p-icon icon-right-arrow"></i>
            </div>

            <hr style="margin: 20px 0px;width: 100%">
            <h3 class="body-title">配送方式</h3>
            <div class="choose-address">
                <div class="address-left carriageList">

                    {{--<div class="address-detail">2</div>--}}
                    {{--<div class="address-detail">2</div>--}}

                </div>
            </div>

            <hr style="margin: 20px 0px;width: 100%">

            <div class="remark">
                <h3 class="remark-key">备注</h3>
                <div class="p-select-container">
                    <div class="select-label active">送给自己</div>
                    <div class="select-label">送给朋友</div>
                    <div class="select-label">送给家人</div>
                </div>
                <textarea class="remark-content" id="textarea" placeholder="礼品寄语"></textarea>
            </div>
        </div>

        <div class="bottom-btn">
            <div class="total-money">合计：<span id="totalPrice">{{$totalPrice}}</span></div>
            <div class="submit-btn" id="submit" url="/home/order/confirm">立即付款</div>
            <input type="hidden" id="orderid" value="{{ $orderid }}">
        </div>

        <div class="p-popup">
            <div class="share-tips">
                <i class="p-icon icon-close"></i>
                <h2 class="share-title">点击右上角<i class="p-icon icon-three-dot"></i>分享订单，看看有多少人愿意与你一起生活～</h2>
                <img src="{{imgurl('pic-banner-02@2x.png')}}" alt="">
            </div>
        </div>
    </div>
    <script>
        $(function() {
            // 选择标签
            $('.select-label').click(function () {
                $('.select-label').removeClass('active');
                $(this).addClass('active');
            });
            var carriageList = "";
            var data={addressid:{{$address['addressid'] or 0}},commodityid:'{{$commodityid or 0}}',num:'{{$num or 0}}'};
            $.get('/home/address/cale',data,function (res) {
                if(res.status==200){

                    if(res.money!=0){

//                        alert(1)
                        $('.hidden-express').text('￥'+res.money);
                        $('.express-key').text('配送价格');
                        var totalPrice=$('#totalPrice').text();
                        totalPrice=parseFloat(totalPrice)+parseFloat(res.money);
                        $('#totalPrice').text(totalPrice);
                        for(var i=0;i<res.carriageList.length;i++){
                            carriageList += res.carriageList[i]
                        }
                        $('.carriageList').append(carriageList);

                    }else{

                        $('.hidden-express').text('￥'+res.money);
                        $('.express-key').text('配送价格');
//                        $('#submit').attr('status',0);
                        var  total = parseInt(res.money) + parseInt({{$totalPrice}});
                        $('#totalPrice').text({{ $totalPrice }});
                        for(var i=0;i<res.carriageList.length;i++){
                            carriageList += res.carriageList[i]
                        }
                        $('.carriageList').append(carriageList);
                    }

                }else{
                    var html = "<h3>以下产品超出配送范围</h3>";
                    for(var i=0;i<res.title.length;i++){
                        html += "<p>"+res.title[i]+"</p>";
                    }
                    $('#submit').attr('status',0);
                    layer.open({
                        content: html
                        ,btn: '我知道了'
                    });
                }
            })
            // 提交
            $('#submit').click(function () {
                if({{$address['addressid'] or 0}}==0){
                    layer.open({content: '请选择收货地址',btn: '好的！',yes:function (index) {
                        location.reload();
                    }})
                    return false;
                }
                if($(this).attr('status')==0){
                    weui.alert('您选择的收货地址不在商家配送的范围');
                    return;
                }
                var label = $('.select-label.active').html();

                // alert(label);
                var content = $('#textarea').val().trim();

                // alert(content);
//                                    var url='/home/WxPay/WxPay';
                //先生成订单,之后再调微信支付接口,来回调
//                var url=$(this).attr('url');
                $(this).attr('url','');

                //订单总价
                var getorderid = $('#orderid').val();
                var totalMoney = $('#totalPrice').text();
//                weui.alert(totalMoney);
//                return false;
                var data={
                    commodityid:'{{$commodityid or 0}}',
                    addressid:{{$address['addressid'] or 0}},
                    body:'一起生活联盟',
                    money:totalMoney,
                    beizhu:content,
                    nums:'{{$num or 0}}',
                    label:label,
                    upid:{{$upid or 0}},
                    transaction:{{$transaction}},
                    status:{{$status or 0}},
                    orderid:getorderid,
                    test:{{$test or 0}}
                };
                if({{$totalPrice}}==0){
                    weui.alert('没有商品');
                    return false;
                }
                var url = '/home/order/confirm';
                $.post(url,data,function(data2){
//                    weui.alert( data2.orderid);
                    if (data2.status==200) {

                        var url1='/home/WxPay/WxPay';
                        $('#orderid').val(data2.orderid);
//                var url='/home/order/confirm';
                        var data1={
                            body:'一起生活联盟',
                            money:totalMoney,
                            transaction:{{$transaction}},
                            status:{{$status or 2}},
                        };
        //                        alert(JSON.stringify(data));
                        $.post(url1,data1,function(data){
        //                        alert(data);
                            WeixinJSBridge.invoke(
                                'getBrandWCPayRequest',data,
                                function(res){
                                    if(res.err_msg == "get_brand_wcpay_request:ok" ) {
                                        weui.alert('下单成功!');
//                                        $('.p-popup').show();
//
//                                       $('.icon-close').click(function() {
//                //                                    alert(123465);
//                //                                    return false;
//                                           $('.p-popup').hide();
                                           window.location.href = '/order/'+data2.orderid;

//                                       });
                                        // 使用以上方式判断前端返回,微信团队郑重提示：
                                        // res.err_msg将在用户支付成功后返回
                                        // ok，但并不保证它绝对可靠。

                                    }
                                }
                            );
                        })
//                        weui.alert(data1.msg);

                    }else{
                        weui.alert('不要重复下单');
                    }
                })




            });
//            $('.icon-close').click(function() {
            $('.p-popup').hide();
//            });
            $('.choose-address').click(function(){
                // hwy
                var getorderid = $('#orderid').val();
                window.location.href="/address?id={{$commodityid or 0}}&num={{$num}}&orderid="+getorderid;

            })
        });
    </script>
@endsection