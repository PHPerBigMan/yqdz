@extends('mobile/layout', ['title' => '订单评价'])

@section('content')
    <div class="m-order-comment">
        @foreach($orderList['snop'] as $val)
        <div class="p-goods-item">
            <img src="{{$val['snopjson']->thumbnail}}" alt="" class="item-left">

            <div class="item-right">
                <div class="item-title">{{$val['snopjson']->title}}</div>
                <div class="item-number">
                    <span class="text-red mr">¥{{$val['snopjson']->money}}</span>
                    <span class="text-gray">{{$val['nums']}}个</span>
                </div>
            </div>
        </div>

        <div class="comment-section">
            <h2 class="section-title">请输入您的评价</h2>
            <textarea class="comment-box" placeholder="请输入您的评价"></textarea>
        </div>
        @endforeach

        <div class="bottom-btn">发表评论</div>
    </div>
    <script>
        $('.bottom-btn').click(function () {
            var content='';
            $('.comment-box').each(function (n,v) {
                content+=$(v).val()+',';

            })
            content=(content.substring(content.length-1)==',')?content.substring(0,content.length-1):content;
//            if(content==''){
//                layer.open({content: '请输入评价内容',skin: 'msg',time: 2 //2秒后自动关闭
//                });
//            }
            var id={{$order->orderid}};
            var data={content:content,id,id}
            $.post("/home/order/evaluate/",data,function (data) {
                if (data['status'] == 200){
                    layer.open({content: data['msg'],btn: '好的！',yes:function (index) {
                        window.location.href="/user";
                    }})
                }else {
                    layer.open({content: data['msg'],skin: 'msg',time: 2 //2秒后自动关闭
                    });
                }
            })
        })
    </script>
@endsection