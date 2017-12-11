@extends('mobile/layout')

@section('content')
    <div class="m-home">
        <div class="top-search">
            <a href="/search" class="p-search">
                <input placeholder="搜索" disabled>
            </a>
            <div class="follow-btn"></div>
        </div>

        <div id="swiper" class="swiper-container">
            <div class="swiper-wrapper">
                @foreach($lunbo as $v)

                        <div class="swiper-slide">
                            <a href="{{$v->url}}">
                            <img src="{{$v->carouselimg}}" alt="">
                            </a>
                        </div>


                @endforeach
            </div>

            <div class="swiper-pagination"></div>
        </div>

        <div class="m-list">
            <div class="scroll-nav-bar">
                <a href="/goods-list/0/0">
                    <div class="scroll-item active">
                        <a href="/goods-list/0/0">全部</a>
                    </div>
                </a>
                @foreach($sort as $item)
                    <a href="/goods-list/{{$item->classifyid}}/{{$past}}">
                        <div class="scroll-item" classifyid="{{$item->classifyid}}">
                            <a href="/goods-list/{{$item->classifyid}}/{{$past}}">{{$item->name}}</a>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="p-tab-nav">
                <div class="tab-item">
                    <a href="/goods-list/{{$id}}/{{$past}}/0">最新发布</a>
                </div>
                <div class="tab-item">
                    <a href="/goods-list/{{$id}}/{{$past}}/1">最多支持</a>
                </div>
                <div class="tab-item">
                    <a href="/goods-list/{{$id}}/{{$past}}/2">价格</a>
                </div>
            </div>
        </div>

        <div class="index-notice">
            <div class="notice-key">一起说</div>
            <div class="notice-value">{{$notice}}</div>
        </div>

        <div>
            @foreach($commodity as $v)
                <a href="/goods/{{$v->commodityid}}/0">
                    <div class="index-big-item">
                        <img class="big-item-img" src="{{$v->thumbnail}}" alt="">
                        <div class="big-item-intro">
                            <div class="big-item-title">{{$v->title}}</div>
                            <div class="big-item-subtit" style="border-bottom: none;">{{$v->describes}}</div>
                            <div class="p-promise-label">
                                @foreach($v->labelid as $k)
                                    <span class="promise-item"><i class="p-icon icon-promise-1"></i>{{$k}}</span>
                                    {{--<span class="promise-item"><i class="p-icon icon-promise-1"></i>全流程追溯</span>--}}
                                    {{--<span class="promise-item"><i class="p-icon icon-promise-1"></i>全流程追溯</span>--}}
                                @endforeach
                            </div>
                            <div class="big-item-info" style="border-bottom: dashed 1px #999;padding-bottom: 6%;">
                                <div class="info-left">
                                    <div class="item-count">
                                        <span class="item-price">¥{{$v->money}}</span>
                                        {{--<span class="item-count">已定制{{$v->sales}}份</span>--}}
                                    </div>
                                </div>
                                <div class="buy-item-btn">一起买</div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>


           

        <div>
            <div class="p-section-title">
                <div class="title-left">一起定制</div>
                <a href="/fund-list">
                    <div class="title-right">查看更多></div>
                </a>
            </div>

            @foreach($hotesgoods as $v)
                <a href="/fund/{{$v->designid}}">
                    <div class="index-middle-item">
                        <img src="{{$v->suolve}}" alt="" class="item-left">

                        <div class="item-right">
                            <div class="item-title">{{$v->title}}</div>
                            <div class="item-people"><span>{{$v->hotes}}</span>人支持</div>
                        </div>
                    </div>
                </a>

            @endforeach
        </div>

        <div>
            <div class="p-section-title">
                <div class="title-left">往期回顾</div>
                <a href="/goods-list/0/1">
                    <div class="title-right">查看更多></div>
                </a>

            </div>
            <div class="index-small-section" style="justify-content: flex-start;">
                @foreach($oldgoods as $v)
                    <a href="/goods/{{$v->commodityid}}/1">
                        <div class="index-small-item">
                            <img src="{{$v->thumbnail}}" alt="" class="item-img">
                            <div class="item-title">{{$v->title}}</div>
                            <div class="item-number">
                                <div class="item-price">¥{{$v->money}}</div>
                                <div class="item-date">{{$v->endtime}}</div>
                            </div>
                        </div>
                    </a>

                @endforeach
            </div>
        </div>
        <a href="/cart">
            <div class="m-cart-icon index"></div>
        </a>
        <a href="/custom-submit">
            <div class="index-fix-btn"><img src="{{imgurl('btn-wangqihuigu@2x.png')}}" alt="发起定制"></div>
        </a>

        @include('mobile.components.navbar',['active' => 'home'])

        <div class="p-popup">
            <div class="qrcode-container">
                <div class="qrcode-title">长按下图二维码</div>
                <div class="qrcode-title">关注我们</div>
                <img class="qrcode-img" src="{{imgurl('pic-erweima@2x.jpg')}}">
                <div class="qrcode-cancel">取消</div>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            new Swiper('#swiper', {
                autoplay: 3000,
                loop: true,
                pagination: '.swiper-pagination',
            });

            $('.follow-btn').click(function () {
                $('.p-popup').fadeIn();
            });

            $('.qrcode-cancel').click(function () {
                $('.p-popup').fadeOut();
            });
        });
    </script>
@endsection