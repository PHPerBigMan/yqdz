@extends('mobile/layout', ['title' => '购物车'])

@section('content')
    <div class="m-cart">
        @if($cartList)
        <div class="cart-group">
            @foreach($cartList as $k => $item)

                <div class="group-item">
                    <div class="item-left">
                        <div class="p-checkbox">
                            <input type="checkbox" hidden id="group-0-{{$k}}" class="checkbox-input">
                            <label class="checkbox-inner" for="group-0-{{$k}}"></label>
                        </div>
 
                        <div class="goods-ctn">
                            <a style="flex: none" href="/goods/{{$item['commodityid']}}/0"><img src="{{$item['goods']['thumbnail']}}" alt="" class="goods-img"></a>
                            <div class="goods-info">
                                <a style="flex: none" href="/goods/{{$item['commodityid']}}/0"><div class="goods-name">{{ str_limit($item['goods']['title'], 10, '...') }}</div></a>
                                <div class="goods-sepc">{{str_limit($item['goods']['describes'],30,'...')}}</div>
                                <div class="goods-count">
                                    <div class="count-minus" cartid="{{$item['cartid']}}">-</div>
                                    <div class="count-value" cartid="{{$item['cartid']}}">{{$item['nums']}}</div>
                                    <div class="count-add" cartid="{{$item['cartid']}}">+</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="item-right">
                        <div class="item-price" price="{{$item['goods']['money']}}" commodityid="{{$item['goods']['commodityid']}}">￥<span class="s-item-price{{$item['cartid']}}" price="{{$item['money']}}">{{$item['money']*$item['nums']}}</span></div>
                        <div class="btn-wrap" onclick="ajaxDelCart({{$item['cartid']}})"><i class="delete-btn"></i>删除</div>
                    </div>
                </div>

            @endforeach
        </div>
        @else
            <img src="{{imgurl('image_wsp.png')}}" alt="" class="cart-empty">
        @endif
        @if($totalPrice)
        <div class="cart-sum">
            <div class="p-checkbox">
                <input type="checkbox" hidden id="total" class="checkbox-input">
                <label class="checkbox-inner" for="total"></label>
                <span>全选</span>
            </div>

            <div class="sum-right">
                <div>合计￥<span id="total-money">{{$totalPrice}}</span></div>
                <div class="submit-btn">结算</div>
            </div>
        </div>
            @endif
    </div>

    <script>
        //只要check值改变就遍历
        var calculatePrice = function () {
            var total = 0;

            $('.group-item').each(function (i, el) {
                if ($(el).find('.checkbox-input').is(':checked')) {
                    total += Number($(el).find('.item-price').text().replace('￥', '')) * 100;
                }
            });

            $('#total-money').text((total / 100).toFixed(2));
        };
        // 点击总开关
        $('#total').prop('checked',true);
        $('.checkbox-input').prop('checked',true);
        $('.cart-sum .p-checkbox').click(function () {
            var $childCheckBox = $('.cart-group').find('.group-item .checkbox-input');
            if ($(this).find('.checkbox-input').is(':checked')) {
                $childCheckBox.prop('checked', true);
            } else {
                $childCheckBox.prop('checked', false);
            }
//            $('#total-money').text(0);
            calculatePrice();
        });

        // 点击子开关
        $('.group-item .p-checkbox').click(function () {
            var $childCheckBox = $(this).parents('.cart-group').find('.group-item .checkbox-input');
            var $headCheckBox = $('.cart-sum .checkbox-input')
            var flag = true;

            $childCheckBox.each(function (i, el) {
                if (!$(el).prop('checked')) {
                    flag = false;
                    return false;
                }
            });
            $headCheckBox.prop('checked', flag);
            calculatePrice();
        });
        // 计数器操作
        function changeCount(type, $dom,cartid) {
            var $value = $dom.siblings('.count-value');
            var count = parseInt($value.text());
            var price=$('.s-item-price'+cartid).attr('price');

            if (type) {
                count++;
                var data={cartid:cartid};
                //ajax增加购物车数量
                $.get('/home/cart/ajaxPlusCount',data,function(res){
                    if (res.status==200) {
                        var tmoney=price*res.nums;
//                        alert(tmoney);
                        $('.s-item-price'+cartid).text(tmoney);
                        changeMoney();
                    }
                })
            } else {
//                alert(0);
                if (count <= 1) {
                    return;
                }
                count--;
//                alert(cartid);
                var data={cartid:cartid};
                //ajax减少购物车数量
                $.get('/home/cart/ajaxMinusCount',data,function(res){
                    if (res.status==200) {

                        var tmoney=price*res.nums;
//                        alert(tmoney);
                        $('.s-item-price'+cartid).text(tmoney);

                        var totalPrice=$('.s-item-price').text();
                        changeMoney();
                    }
                })
            }
            $value.text(count);
        }
        //点击加号操作
        $('.count-add').click(function () {
            var cartid=$(this).attr('cartid');

            changeCount(1, $(this),cartid);
        });
        //点击减号操作
        $('.count-minus').click(function () {
            var cartid=$(this).attr('cartid');
            changeCount(0, $(this),cartid);
        });
        //ajax删除购物车
        function ajaxDelCart(cartid){

            layer.open({
                content: '确定要删除吗？'
                ,btn: ['确定', '不要']
                ,yes: function(index){
                    $.get("/home/cart/ajaxDelCart/?cartid="+cartid,function (data) {
                        if (data['status'] == 200){
//                            layer.open({content: data['msg'],btn: '好的！',yes:function (index) {
                                location.reload();
//                            }})
                        }else {
                            layer.open({content: data['msg'],skin: 'msg',time: 2 //2秒后自动关闭
                            });
                        }
                    })
//                    alert(123456);

                }
            });
        }
        $('.submit-btn').click(function () {
            var commodityid='';
            var nums='';
            $('.group-item').each(function (i, el) {
                if ($(el).find('.checkbox-input').is(':checked')) {
                    commodityid+=($(el).find('.item-price').attr('commodityid'))+',';
                    nums+=($(el).find('.count-value').text())+',';
                }
            });
//            console.log(money);
//            return false;
            if(commodityid){
                //跳转到下单页面

                window.location.href="/goods-order?id="+commodityid+'&num='+nums;
            }else{
                layer.open({content: '请选择购买的商品',skin: 'msg',time: 2 //2秒后自动关闭
                });
            }

        })
        function changeMoney() {
            var total = 0;
            $('.group-item').each(function (i, el) {
                if ($(el).find('.checkbox-input').is(':checked')) {
                    total += Number($(el).find('.item-price').text().replace('￥', '')) * 100;
                }
            });
            $('#total-money').text((total / 100).toFixed(2));
        }
    </script>
@endsection