@extends('mobile/layout', ['title' => '定制信息列表'])

@section('content')
    <div class="m-list">
        <div class="scroll-nav-bar">
           <a href="/fund-list/0">
                <div class="scroll-item active">
                    <a href="/fund-list/0">全部</a>
                </div>
            </a>
            @foreach($sort as $item)
                <a href="/fund-list/{{$item->classifyid}}">
                    <div class="scroll-item active" classifyid="{{$item->classifyid}}">
                        <a href="/fund-list/{{$item->classifyid}}">{{$item->name}}</a>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="p-tab-nav">
            <div class="tab-item">
                <a href="/fund-list/{{$id}}/0/0">最新发布</a>
            </div>
            <div class="tab-item">
                <a href="/fund-list/{{$id}}/0/1">最多支持</a>
            </div>
        </div>

        @foreach($fundList as $val)
            <a href="/fund/{{$val->designid}}">
                <div class="p-goods-item">
                    <img src="{{$val->suolve}}" alt="" class="item-left">
                    <div class="item-right">
                        <div class="item-title">{{$val->title}}</div>
                        <div class="item-number" style="margin-top: 24px;">
                            <span class="item-date"><span>{{$val->hotes}}</span>人支持</span>
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
            // if (l < 6) {
            //     return;
            // }

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
            $('.scroll-item').removeClass('active');
            $('.scroll-item').eq(0).addClass('active');
            moveScroll({!! json_encode($sort) !!}, {{$id}});
            if({{$type}}==0){
                $('.tab-item').removeClass('active');
                $('.tab-item').eq(0).addClass('active');
            }else if({{$type}}==1){
                $('.tab-item').removeClass('active');
                $('.tab-item').eq(1).addClass('active');
            }else{
                $('.tab-item').removeClass('active');
                $('.tab-item').eq(2).addClass('active');
            }


        });
    </script>
@endsection