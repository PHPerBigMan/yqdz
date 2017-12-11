@extends('mobile/layout', ['title' => '退货申请'])

@section('content')
    <div class="m-order-refund">
        <div class="refund-section">
            <div class="section-title">退款金额</div>
            <input type="number" name="money" placeholder="请输入退款金额">
        </div>

        <div class="refund-section">
            <div class="section-title">退款说明</div>
            <textarea class="content" placeholder="请输入您的退款理由"></textarea>
        </div>

        <div class="bottom-btn">提交退货申请</div>
    </div> 
    <script> 
        $('.bottom-btn').click(function () {

            var money=$("input[name='money']").val();
            var content=$(".content").val();
            if(money==0){
                layer.open({content: '请输入退款金额',btn: '好的！',yes:function (index) {
                    layer.closeAll();
                }})
            }
            else if(content==''){
                layer.open({content: '请输入退款说明',btn: '好的！',yes:function (index) {
                    layer.closeAll();
                }})
            } else if(money > {{ $money }}){
                layer.open({content: '您的退款金额大于订单总额',btn: '好的！',yes:function (index) {
//                    location.reload();
                    layer.closeAll();
                }})
                return false;
            }else{

//            'snop_id'=>$snop_id,'express'=>$express,'couriernumber'=>$couriernumber
                var data={
                    id:{{$id}},
                    snopid:'{{$snop_id or ''}}',
                    money:money,
                    content:content,
                    express:'{{$express}}',
                    couriernumber:{{$couriernumber}}
                };
                $.get("/home/order/refund",data,function (data) {
                    if (data['status'] == 200){
                        layer.open({content: data['msg'],btn: '好的！',yes:function (index) {
                            window.location.href="/order-list";
                        }})
                    }else {
                        layer.open({content: data['msg'],skin: 'msg',time: 2 //2秒后自动关闭
                        });
                    }
                })
            }
        })
    </script>
@endsection