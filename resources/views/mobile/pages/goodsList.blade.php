@extends('mobile/layout', ['title' => '定制商品列表'])

@section('content')
    <div class="m-list">
        <div class="scroll-nav-bar">
            <a href="/goods-list/0/{{$past}}">
                <div class="scroll-item active">
                    <a href="/goods-list/0/{{$past}}">全部</a>
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

        @foreach($goodsList as $val)
            <a href="/goods/{{$val->commodityid}}/{{$past}}">
                <div class="p-goods-item">
                    <img src="{{$val->thumbnail}}" alt="" class="item-left">

                    <div class="item-right">
                        <div class="item-title">{{$val->title}}</div>
                        <div class="item-number">
                            <span class="text-red mr">¥{{$val->money}}</span>
                            {{--<span class="text-gray">数量{{$val->number}}个</span>--}}
                        </div>
                    </div>
                </div>
            </a>

        @endforeach
    </div>

    <script>
        // 如果分类（包括全部）长度大于5并选中了i > 4的标签 设置上面滚动条位移
        function moveScroll(arr, id) {
            var l = arr.length;
//            if (l < 4) {
//                return;
//            }

            for (var i = 0; i < l; i++) {
                if (arr[i].classifyid == id) {
                    var pos = 0;
                    if (i > l - 3) {
                        // 倒数两个
                        pos = l - 5;
                    } else if (i > 2) {
                        // 后面还有三个移动到中间
                        pos = i - 2;
                    }
                    $('.scroll-item').removeClass('active').eq(i+1).addClass('active');
                    var width = $('body').width();
                    $('.scroll-nav-bar').scrollLeft(width / 5 * pos);
                }
            }
        }
        $(function () {
             {{--alert({{$id}})--}}
            $('.scroll-item').each(function (k,v) {
                if({{$id}}==$(this).attr('classifyid')){
                     $('.scroll-item').eq(k).addClass('active');
                     $('.scroll-item').eq(0).removeClass('active');
                }
            })

            moveScroll({!! json_encode($sort) !!}, {{$id}});
            {{--alert({{$id}});--}}
            if({{$type}}==0){
                $('.tab-item').eq(0).addClass('active');
            }else if({{$type}}==1){
                $('.tab-item').eq(1).addClass('active');
            }else{
                $('.tab-item').eq(2).addClass('active');
            }
//            $('.scroll-item').click(function () {
//                alert($(this).attr('classifyid'));
//            })


        });



    </script>
@endsection