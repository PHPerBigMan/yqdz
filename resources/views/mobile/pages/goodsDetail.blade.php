@extends('mobile/layout', ['title' => '商品详情'])

@section('content')
    <div class="m-goods-detail" style="overflow-x: hidden">
        <div id="swiper" class="swiper-container">
            <div class="swiper-wrapper">
                @foreach(json_decode($data->carrousel) as $k)
                    <div class="swiper-slide">
                        <img src="{{$k}}" alt="">
                    </div>
                @endforeach
            </div>

            <div class="swiper-pagination"></div>
        </div>
        <div class="section-block">
            <h2 class="goods-detail-title">{{$data->title or ''}}</h2>
            <h3 class="goods-detail-subtit">{{$data->describes or ''}}</h3>

            <div class="goods-detail-info">
                <div class="left-area">
                    <div class="p-promise-label">

                        @foreach($data->labelid as $v)
                        <span class="promise-item"><i class="p-icon icon-promise-1"></i>{{$v}}</span>
                        {{--<span class="promise-item"><i class="p-icon icon-promise-1"></i>全流程追溯</span>--}}
                        {{--<span class="promise-item"><i class="p-icon icon-promise-1"></i>全流程追溯</span>--}}
                        @endforeach
                    </div>
                </div>

                <div class="right-area">
                    <div class="item-price">¥{{$data->money or ''}}</div>
                    <div class="item-postage">快递费：<span>免费</span></div>
                </div>
            </div>

            {{--<div class="goods-detail-progress">--}}
                {{--<div class="cur-progress" style="width: {{$data->sales/$data->number*100}}%"></div>--}}
            {{--</div>--}}
            @if($data->number!=0)
            <div class="goods-detail-number">
                <div class="number-item">
                    <div class="item-key">目标数量</div>
                    <div class="item-value">{{$data->number or 0}}件</div>
                </div>
                <div class="number-item">
                    <div class="item-key">已筹数量</div>
                    <div class="item-value">{{$data->sales or 0}}件</div>
                </div>
                <div class="number-item">
                    <div class="item-key">支持人数</div>
                    <div class="item-value">{{$data->sales or 0}}人</div>
                </div>
                <div class="number-item">
                    <div class="item-key">剩余天数</div>
                    <div class="item-value">{{$times or ''}}天</div>
                </div>
            </div>
            @endif
        </div>

        <div class="section-block">
            <h3 class="professor-key">专家说</h3>
            <div class="professor-value">
                {{$data->experts or ''}}
            </div>
        </div>

        <div class="tab-area">

            <div class="tab-navbar">
                @if($data->content!='')
                <div class="nav-item active">产品详情</div>
                @endif
                    <div class="nav-item">机构评测</div>
                    <div class="nav-item">项目动态</div>
                    <div class="nav-item">大家说</div>
                @if($data->appraisal!='')
                {{--<div class="nav-item">机构评测</div>--}}
                {{--@endif--}}
                {{--@if(count($dt)!=0)--}}
                {{--<div class="nav-item">项目动态</div>--}}
                {{--@endif--}}
                {{--@if(count($comment)!=0)--}}
                {{--<div class="nav-item">大家说</div>--}}
                @endif
            </div>

            <div class="tab-content">
                <div class="tab-title">产品详情</div>
                <div class="html-content">
{{--                    <img src="{{imgurl('pic-chanpintu@2x.png')}}" alt="">--}}
                    {!! $data->content !!}
                </div>
            </div>

            <div class="tab-content">
                <div class="tab-title">机构评测</div>
                <div class="html-content">
                    {!! $data->appraisal or '' !!}
                </div>
            </div>

            <div class="tab-content">
                <div class="tab-title">产品动态</div>
                <div class="project-news">
                    @foreach($dt as $val)
                        <div class="news-item">
                            <div class="news-item-left">{{$val->create_time}}</div>
                            <div class="news-item-right">
                                <div class="news-content">
                                   {{$val->content}}
                                </div>
                                <div class="news-img">
                                    @foreach($val['img'] as $k)
                                    <img src="{{$k}}" alt="">
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="tab-content">
                <div class="tab-title">大家说</div>
                <div class="user-comment">
                    @foreach($comment as $v)
                        <div class="comment-item">
                            <div class="comment-head">
                                <img class="comment-avatar" src="{{$v->img}}">
                                <div class="comment-name">{{$v->nickname}}</div>
                            </div>
                            <div class="comment-body">
                                {{$v->content}}
                            </div>
                            <div class="comment-date">{{$v->created_at}}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bottom-btn">
            <a href="/" class="small-btn">
                <i class="p-icon icon-home"></i>
            </a>
            <div class="small-btn">
                <i id="follow" class="p-icon icon-follow" style="background-size: 0.8rem;" onclick="setstyle()"></i>
            </div>
            <div class="big-btn green cart">加入购物车</div>
            <div class="big-btn buy">一起买</div>
        </div>

        <div class="p-popup">
            <div class="purchase-choose">
                <i class="p-icon icon-close"></i>

                <div class="choose-wrap">
                    <div class="choose-head">
                        <img class="choose-avatar" src="{{$data->thumbnail}}">
                        <div class="choose-price">¥{{$data->money or 0}}</div>
                    </div>
                    <h2 class="choose-title">{{$data->title or ''}}</h2>
                    <div class="choose-foot">
                        <div class="choose-label">购买数量</div>
                        <div class="choose-count">
                            <span class="btn" id="minus">-</span>
                            <input type="number" style="width: 25%;text-align:center;height:0.85333333rem" class="count" value="1"/>
                            <span class="btn" id="plus">+</span>
                        </div>
                    </div>
                </div>
                    <div class="p-btn">确认</div>
            </div>

        </div>
        {{--购物车--}}
        <a href="/cart">
            <div class="m-cart-icon"></div>
        </a>
    </div>

    <script> 
        var love={{$love}};
        if (love!==0) {
            $('#follow').css('background-image','url("/frontend/img/icon-guanzhu-01@2x.png")');
             $('#follow').removeClass('icon-follow');
        }else{
             $('#follow').addClass('icon-follow');
             $('#follow').css('display','');
        }
        function setstyle()
        {           
            if($('#follow').attr('class') == 'p-icon icon-follow')
            {
                
                var url='/home/goods/love';
                var data={commodity:{{$data->commodityid}}}
                $.get(url,data,function(res){
                    if (res.status==200) {
                            layer.open({
                            content: res['msg']
                            ,btn: '我知道了'
                        });
                        $('#follow').css('background-image','url("/frontend/img/icon-guanzhu-01@2x.png")');
                        $('#follow').removeClass('icon-follow');    
                    }
                })
            }else{
                var url='/home/goods/cancel-love';
                var data={commodity:{{$data->commodityid}}}
                $.get(url,data,function(res){
                    if (res.status==200) {
                            layer.open({
                            content: res['msg']
                            ,btn: '我知道了'
                        });
                        $('#follow').addClass('icon-follow');
                        $('#follow').css('background-image','');
                    }
                })
                
            }
            
                
            
        }
        $(function () {
            new Swiper('#swiper', {
                autoplay: 5000,
                loop: true,
                pagination: '.swiper-pagination',
            });

            $('.nav-item').click(function () {

                var index = $(this).index();
                $('.nav-item').removeClass('active');
                $(this).addClass('active');
                $('.tab-content').hide().eq(index).show();
            });
            if({{$past}}==1){
                $('.big-btn').css("background","#ccc");
            }
            $('.buy').click(function() {
                $('.cart').attr('status','');
                if({{$times}}==0){
//                    alert(123456);
                    layer.open({content: '该商品已过期',btn: '好的！',yes:function (index) {
                        location.reload();
                    }})
                    return false;
                }
                if({{$past}}==1){
                    return false;
                }
                $('.p-popup').fadeIn(100);
                $('.purchase-choose').animate({bottom: 0}, 300);
            });
            $('.cart').click(function() {
                $(this).attr('status','cart');
                if({{$times}}==0){
//                    alert(123456);
                    layer.open({content: '该商品已过期',btn: '好的！',yes:function (index) {
                        location.reload();
                    }})
                    return false;
                }
                if({{$past}}==1){
                    return false;
                }
                $('.p-popup').fadeIn(100);
                $('.purchase-choose').animate({bottom: 0}, 300);
            });

            $('.icon-close').click(function() {
                $('.p-popup').fadeOut(100);
                $('.purchase-choose').animate({bottom: '-8rem'}, 300);
            });
//ajax post 提交表单
            $('.p-btn').click(function(){
                var status=$('.cart').attr('status');

                {{--if({{$data->number}}=={{$data->sales}}){--}}
{{--//                    alert(123456);--}}
                    {{--layer.open({content: '已经达到目标数量',btn: '好的！',yes:function (index) {--}}
                        {{--location.reload();--}}
                    {{--}})--}}
                    {{--return false;--}}
                {{--}--}}
                var num=parseInt($('.count').val());
                if (num>{{$data->stock-$data->sales}}) {
                    layer.open({content: '购买数量不能大于商品库存',skin: 'msg',time: 2 //2秒后自动关闭
                    });
                    return false;
                }
                {{--if (num>{{$data->number}}) {--}}
                    {{--layer.open({content: '购买数量不能大于目标数量',skin: 'msg',time: 2 //2秒后自动关闭--}}
                    {{--});--}}
                    {{--return false;--}}
                {{--}--}}
//                var num=parseInt($('.count').val());
//                alert('下单成功');
                var data={id:{{$data->commodityid}},num:num,money:{{$data->money}}};
                $.post("/home/cart/ajaxAddCart",data,function (res) {
                    if (res['status'] == 200){
                        if(status=='cart'){
                            weui.alert('加入购物车成功!');
                            $('.p-popup').fadeOut(100);
                        }else{
                            window.location.href="/cart";
                        }

                    }else {
                        layer.open({content: res['msg'],skin: 'msg',time: 2 //2秒后自动关闭
                        });
                    }
                })
{{--                window.location.href="/?id="+{{$data->commodityid}}+'&num='+num+'&money='+{{$data->money}};--}}
                return false;
            });
            $('#plus').click(function(){
                var num=parseInt($('.count').val());
                if (num>{{$data->stock-$data->sales}}) {
                    layer.open({content: '购买数量不能大于商品库存',skin: 'msg',time: 2 //2秒后自动关闭
                            });
                    return false;
                }
                {{--if (num>{{$data->number}}) {--}}
                    {{--layer.open({content: '购买数量不能大于目标数量',skin: 'msg',time: 2 //2秒后自动关闭--}}
                    {{--});--}}
                    {{--return false;--}}
                {{--}--}}
                num+=1;
                $('.count').val(num);
            })
            $('#minus').click(function(){
                var num=parseInt($('.count').val());
                if (num<=1) {
                    layer.open({content: '购买数量不能小于1',skin: 'msg',time: 2 //2秒后自动关闭
                            });
                    return false;
                }
                num-=1;
                $('.count').val(num);
            })
        });
    </script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">

        wx.config(<?php echo $config?>);
        wx.ready(function () {
            wx.onMenuShareAppMessage({
                title: '{{$data->title}}', // 分享标题
                desc: '{{$data->experts or ""}}', // 分享描述
                link: 'http://yqdz.xs.sunday.so/goods/'+{{$data->commodityid}}+'/0',// 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: 'http://yqdz.xs.sunday.so{{$data->fx_thumb}}', // 分享图标
                trigger:function(res){
//                console.log(res);
//                alert('用户点击发送给朋友');
                },
                success: function (res) {
//                console.log(res);
                    // 用户确认分享后执行的回调函数
                    alert('分享成功!');
                },
                cancel: function (res) {
//                console.log(res);
                    // 用户取消分享后执行的回调函数
                    alert('取消分享成功')
                }
            });
            wx.onMenuShareTimeline({
                title: '{{$data->title}}', // 分享标题
                desc: '{{$data->experts or ""}}', // 分享描述
                link: 'http://yqdz.xs.sunday.so/goods/'+{{$data->commodityid}}+'/0',// 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: 'http://yqdz.xs.sunday.so{{$data->fx_thumb}}', // 分享图标
                trigger:function(res){
//                console.log(res);
//                alert('用户点击发送给朋友');
                },
                success: function (res) {
//                console.log(res);
                    // 用户确认分享后执行的回调函数
                    alert('分享成功!');
                },
                cancel: function (res) {
//                console.log(res);
                    // 用户取消分享后执行的回调函数
                    alert('取消分享成功')
                }
            });
        })

    </script>
@endsection