@extends('mobile/layout', ['title' => '企业定制'])

@section('content')
    <div class="m-enterprise-order">
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

        <div class="enterprise-step-img">
            <img src="{{imgurl('img-qiyedingzhi.png')}}" alt="">
        </div>
        <form class="am-form am-form-horizontal" action="" method="post" id="from1">
            {{ csrf_field() }}
            <div class="enterprise-form">
                <div class="form-key">您的联系方式</div>
                <input type="text" placeholder="请输入您的联系电话" name="phone">
                <div class="form-key">您的定制需求</div>
                <textarea placeholder="请输入您的定制需求" name="content"></textarea>
                <div class="btn-wrap">
                    <button class="btn-left">在线咨询</button>
                    <button type="button" class="btn-right">提交</button>
                </div>
            </div>
        </form>
        @include('mobile.components.navbar',['active' => 'enterprise'])
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
    <script>
        $(".btn-right").click(function () {
            var phone=$("input[name='phone']").val();
//            alert(phone);
//            return false;
            if(phone==''){
                layer.open({
                    content: '手机号码不能为空'
                    ,btn: '我知道了'
                });
                return false;
            }
            if(!phone.match(/^(((13[0-9]{1})|(14[0-9]{1})|(17[0]{1})|(15[0-3]{1})|(15[5-9]{1})|(18[0-9]{1}))+\d{8})$/)){
                layer.open({
                    content: '手机号码格式不正确'
                    ,btn: '我知道了'
                });
                return false;
            }
            $.post("/home/design/handle",$("#from1").serialize(),function (data) {
                if(data['status'] == "200"){
                    layer.open({
                        content: data['msg']
                        ,btn: '我知道了'
                        ,yes: function(){
                            window.location.reload()
                        }
                    });
                }else{
                    layer.open({
                        content: data['msg']
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                }
            })
        })
        $('.btn-left').click(function(){
            window.location.href='/service';
            return false;
        })
    </script>
@endsection