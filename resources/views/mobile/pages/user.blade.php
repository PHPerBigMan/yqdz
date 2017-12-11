@extends('mobile/layout', ['title' => '个人中心'])

@section('content')
    <div class="m-user">
        <div class="user-head">
            <img src="{{$userInfo->img}}" class="user-avatar">
            <div class="user-info">
                <div class="user-name">{{$userInfo->nickname}}</div>
{{--                <div class="user-id">id{{$userInfo->uid}}</div>--}}
            </div>
        </div>

        <div class="user-order">
            <div class="head-title">
                <div class="title-left">我的订单</div>
                <a href="/order-list" class="title-right">全部订单<i class="p-icon icon-right-arrow"></i></a>
            </div>

            <div class="order-entry">
                <a href="/order-list/10" class="order-item">
                    {{--<i class="order-icon icon-payment"><span style="position: relative;top: -10px;left:10px;background-color: #f74c31;border-radius:25px;color: #ffffff">12</span></i>--}}
                    <i class="order-icon icon-payment">
                        @if(isset($waitOrderCount) && $waitOrderCount!=0)
                        <div style=" width:18px; height:18px; background-color:#f74c31; border-radius:25px;margin-left: 60%">
                            <span style="height:18px; line-height:18px; display:block; color:#FFF; text-align:center">{{$waitOrderCount}}</span>
                        </div>
                        @endif
                    </i>
                    <span>待付款</span>


                </a>
                <a href="/order-list/20" class="order-item">
                    <i class="order-icon icon-deliver">
                        @if(isset($waitSendCount) && $waitSendCount!=0)
                            <div style=" width:18px; height:18px; background-color:#f74c31; border-radius:25px;margin-left: 60%">
                                <span style="height:18px; line-height:18px; display:block; color:#FFF; text-align:center">{{$waitSendCount}}</span>
                            </div>
                        @endif
                    </i>
                    <span>待发货</span>
                </a>
                <a href="/order-list/30" class="order-item">
                    <i class="order-icon icon-receiving">
                        @if(isset($waitReceiptCount) && $waitReceiptCount!=0)
                            <div style=" width:18px; height:18px; background-color:#f74c31; border-radius:25px;margin-left: 60%">
                                <span style="height:18px; line-height:18px; display:block; color:#FFF; text-align:center">{{$waitReceiptCount}}</span>
                            </div>
                        @endif
                    </i>
                    <span>待收货</span>
                </a>
                <a href="/order-list/50" class="order-item">
                    <i class="order-icon icon-evaluate">
                        @if(isset($waitEvalCount) && $waitEvalCount!=0)
                            <div style=" width:18px; height:18px; background-color:#f74c31; border-radius:25px;margin-left: 60%">
                                <span style="height:18px; line-height:18px; display:block; color:#FFF; text-align:center">{{$waitEvalCount}}</span>
                            </div>
                        @endif
                    </i>
                    <span>待评价</span>
                </a>
            </div>
        </div>

        <div class="user-entry">
            <a href="/my-fund" class="entry-item">
                <div class="item-left">
                    <i class="user-entry-icon icon-my-custom"></i>
                    我的定制
                </div>
                <i class="p-icon icon-right-arrow"></i>
            </a>

            <a href="/award" class="entry-item">
                <div class="item-left">
                    <i class="user-entry-icon icon-award"></i>
                    推荐有奖
                </div>
                <i class="p-icon icon-right-arrow"></i>
            </a>

            <a href="/attention" class="entry-item">
                <div class="item-left">
                    <i class="user-entry-icon icon-attention"></i>
                    我的关注
                </div>
                <i class="p-icon icon-right-arrow"></i>
            </a>
            <a href="/message" class="entry-item">
                <div class="item-left">
                    <i class="user-entry-icon icon-explain"></i>
                    我的消息
                </div>
                @if(isset($msgCount) && $msgCount!=0)
                <div style=" width:18px; height:18px; background-color:#f74c31; border-radius:25px;margin-left: 60%">
                    <span style="height:18px; line-height:18px; display:block; color:#FFF; text-align:center">{{$msgCount or 0}}</span>
                </div>
                @endif
                <i class="p-icon icon-right-arrow"></i>

            </a>
            <a href="/address?type=1" class="entry-item">
                <div class="item-left">
                    <i class="user-entry-icon icon-address"></i>
                    地址管理
                </div>
                <i class="p-icon icon-right-arrow"></i>
            </a>

            <a href="/explain" class="entry-item">
                <div class="item-left">
                    <i class="user-entry-icon icon-explain"></i>
                    平台说明
                </div>
                <i class="p-icon icon-right-arrow"></i>
            </a>


            <a href="tel:{{$phone or ''}}" class="entry-item">
                <div class="item-left">
                    <i class="user-entry-icon icon-server"></i>
                    联系客服
                </div>
                <i class="p-icon icon-right-arrow"></i>
            </a>
        </div>

        <div class="footer-tips">如有问题，请联系我们的客服，有我们在，请您放心</div>
        <a href="/cart">
            <div class="m-cart-icon"></div>
        </a>
        @include('mobile.components.navbar',['active' => 'user'])
    </div>
@endsection