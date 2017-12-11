@extends('mobile/layout', ['title' => '定制广场'])

@section('content')
    <div class="m-square">
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

        <div>
            <div class="p-section-title">
                <div class="title-left">定制精选</div>
                <a href="/goods-list">
                    <div class="title-right">查看更多></div>
                </a>
            </div>

            <div class="quality-item-wrap">
                @foreach($commodity as $v)
                    <a href="/goods/{{$v->commodityid}}/0">
                        <div class="quality-item">
                            <div class="item-img-wrap">
                                <img src="{{ empty($v->dz_thumbnail) ? $v->thumbnail : $v->dz_thumbnail}}" alt="">
                            </div>
                            <h3 class="item-title">{{$v->title}}</h3>
                            <div class="item-price">¥{{$v->money}}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <div>
            <div class="p-section-title">
                <div class="title-left">发起定制，你想支持谁？</div>
                <a href="/fund-list">
                    <div class="title-right">查看更多></div>
                </a>
            </div>
            <div class="small-item-wrap" style="justify-content: flex-start;">
                @foreach($hotesgoods as $v)
                    <a href="/fund/{{$v->designid}}">
                        <div class="small-item" style="margin-right: 5px">
                            <img src="{{$v->suolve}}" alt="" class="item-img">
                            <div class="item-title">{{$v->title}}</div>
                            <div class="item-number"><span>{{$v->hotes}}人</span>支持</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        <a href="/cart">
            <div class="m-cart-icon"></div>
        </a>
        @include('mobile.components.navbar',['active' => 'square'])
    </div>

    <script>
        $(function () {
            new Swiper('#swiper', {
                autoplay: 5000,
                loop: true,
                pagination: '.swiper-pagination',
            });
        });
    </script>
@endsection