@extends('mobile/layout', ['title' => '搜索'])

@section('content')
    <div class="m-search">
       
        <form class="am-form" action="/search-result" method="get">
            <div href="/search" class="p-search">
                <input name="keyword" placeholder="搜索">
                <input type="hidden" name="type" value="latest">    
            </div>
        </form>

        <div class="search-head">
            <div class="search-title">历史搜索</div>
            <i class="p-icon icon-delete"></i>
        </div>

        <div class="search-history">
            @foreach($history as $val)
            <a href="/search-result/latest/{{$val->keyword}}">
                <div class="search-item" >{{$val->keyword}}</div>
            </a>
            @endforeach


        </div>
    </div>
    <script>
        $('.icon-delete').click(function () {
            layer.open({
                content: '确定要清空历史记录么？'
                ,btn: ['确定', '不要']
                ,yes: function(index){
                    $.get("/searchDelete",function (data) {
                        if (data['status'] == 200){
                            layer.open({content: data['msg'],btn: '好的！',yes:function (index) {
                                location.reload();
                            }})
                        }else {
                            layer.open({content: data['msg'],skin: 'msg',time: 2 //2秒后自动关闭
                            });
                        }
                    })
                }
            });
        })
    </script>
@endsection