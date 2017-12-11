@extends('mobile/layout', ['title' => '我的定制'])

@section('content')
    <div class="m-my-fund">
        @foreach($myFund as $item)
            <a href="/fund/{{$item->designid}}">
                <div class="p-goods-item">
                    <img src="/uploads/whGBSTRT4UcB8YaOHhaQQbhkCd77uHPdpLt6ytnq.png" alt="" class="item-left">

                    <div class="item-right" style="width: 100%">
                        <div class="item-title">
                            {{$item->content}}
                            @if($item->status==1)<span class="text-red" style="float: right;color: #ef000b">审核中</span>@endif

                        </div>
                        <div class="item-number" style="width: 100%">
                            <span class="text-red">{{$item->hotes}}</span>人支持&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <i class="p-icon icon-delete" fundid="{{$item->designid}}" style="width: 0.53333333rem;height: 0.53333333rem;background-size: 0.53333333rem;float: right"></i>
                        </div>
                    </div>
                </div>
            </a>
            
        @endforeach

        <a href="/custom-submit">
            <div class="bottom-btn">我要发起</div>
        </a>
            <script>
                $('.icon-delete').click(function(){
                    var fundid=$(this).attr('fundid');
//            alert(fundid);
//            return;
                    layer.open({
                        content: '确定要删除该定制信息吗？'
                        ,btn: ['确定', '不要']
                        ,yes: function(index){
                            window.location.href='/home/design/del/'+fundid;
                        }
                    });
                })
            </script>
@endsection