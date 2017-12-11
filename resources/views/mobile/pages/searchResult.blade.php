@extends('mobile/layout', ['title' => '搜索结果'])

@section('content')
    <div class="m-search-result">
        <div class="top-nav">
            <div class="nav-item @if($active == 'latest') active @elseif($active == 'oldest') active reverse @endif">
                最新发布<i class="p-icon icon-sort"></i>
            </div>
            <div class="nav-item @if($active == 'most') active @elseif($active == 'few') active reverse @endif">
                最多支持<i class="p-icon icon-sort"></i>
            </div>
            <div class="nav-item @if($active == 'cheap') active @elseif($active == 'expensive') active reverse @endif">
                价格<i class="p-icon icon-sort"></i>
            </div>
        </div>

        @foreach($searchResult as $item)
            <a href="/goods/{{$item->commodityid}}">
                <div class="p-goods-item">
                    <img src="{{$item->thumbnail}}" alt="" class="item-left">

                    <div class="item-right">
                        <div class="item-title">{{$item->title}}</div>
                        <div class="item-number">
                            <span class="text-red mr">¥{{$item->money}}</span>
                            <span class="text-gray">{{$item->starttime}}</span>
                        </div>
                    </div>
                </div>
            </a>
            
        @endforeach
    </div>

    <script>
        $(function () {
            $('.nav-item').click(function () {
                var type;
                var index = $(this).index();
                switch (index) {
                    case 0:
                        type = ['latest', 'oldest'];
                        break;
                    case 1:
                        type = ['most', 'few'];
                        break;
                    case 2:
                        type = ['cheap', 'expensive'];
                        break;
                }

                if ($(this).is('.reverse')) {
                    $(this).find('icon-sort').addClass('.reverse');
                    window.location.href = '/search-result?keyword='+'{{$keyword}}'+'&type=' + type[0];
                } else {
                    window.location.href = '/search-result?keyword='+'{{$keyword}}'+'&&type=' + type[1];
                }
            });
        });
    </script>
@endsection