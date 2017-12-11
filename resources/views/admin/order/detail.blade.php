<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="//cdn.bootcss.com/jquery/2.0.2/jquery.min.js"></script>
    <link rel="stylesheet" href="/assets/css/amazeui.min.css"/>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <script src="http://cdn.bootcss.com/vue/1.0.26/vue.min.js"></script>
    <style>
        body{font-size: 13px;overflow-y: scroll}
        .am-panel{border: none;box-shadow: none;-webkit-box-shadow: none;margin-bottom:5px;}
        .am-table>tbody>tr>td, .am-table>tbody>tr>th, .am-table>tfoot>tr>td, .am-table>tfoot>tr>th, .am-table>thead>tr>td, .am-table>thead>tr>th{border: none}
    </style>
</head>
<body>
<div class="hw-content" style="padding: 20px;">
    <form class="am-form am-form-horizontal" action="" method="post" id="from1">
        <div class="am-g am-margin-top">
            <div class="am-panel am-panel-default">
                <div class="am-panel-hd">商品信息</div>
                <div class="am-panel-bd">
                    <table class="am-table am-table-striped am-table-hover">
                        <thead>
                        <tr>
                            <th>商品名称</th>
                            <th>数量</th>
                            <th>单价</th>
                            <th>一级分成</th>
                            <th>二级分成</th>
                            <th>三级分成</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><img :src="{{$data['snop']['snopjson']->thumbnail}} width="60" style="margin-right: 10px">{{$data['snop']['snopjson']->title}}</td>
                            <td>{{$data['snop']['nums']}}</td>
                            <td>{{$data['snop']['snopjson']->money}}</td>
                            <td>{{$data['snop']['snopjson']->firstgraded}} %</td>
                            <td>{{$data['snop']['snopjson']->secondgraded}} %</td>
                            <td>{{$data['snop']['snopjson']->threegraded}} %</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="am-panel am-panel-default">
                <div class="am-panel-hd">基本信息</div>
                <div class="am-panel-bd">
                    <table class="am-table">
                        <tbody>
                        <tr>
                            <td width="120">订单状态：</td>
                            <td>
                                <span class="am-badge am-badge-secondary am-radius mr0">
                                    @if($data['order_state']== 0)
                                        已关闭
                                    @elseif($data['order_state'] == 10)
                                        待付款
                                    @elseif($data['order_state'] == 20)
                                        待发货
                                    @elseif($data['order_state'] == 30)
                                        待收货
                                    @elseif($data['order_state'] == 40)
                                        待评价
                                    @elseif($data['order_state'] == 50)
                                        已完成
                                    @elseif($data['order_state'] == 60)
                                        退货中
                                    @elseif($data['order_state'] == 70)
                                        退货完成
                                    @elseif($data['order_state'] == 80)
                                        拒绝退货
                                    @endif
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>订单号：</td>
                            <td>{{$order->transaction}}</td>
                        </tr>
                        <tr>
                            <td>用户ID：</td>
                            <td><img src="{{$data['user']->img}}" width="40" style="margin-right: 10px" @click="gouser({{$data['user']['id']}})">{{$data['user']['id']}}</td>
                        </tr>
                        <tr>
                            <td width="120">微信支付交易号：</td>
                            <td>{{$order->commercial}}</td>
                        </tr>
                        <tr>
                            <td width="120">下单时间：</td>
                            <td>{{$order->created_at}}</td>
                        </tr>
                        <tr>
                            <td width="120">支付时间：</td>
                            <td>{{$order->pay_time}}</td>
                        </tr>
                        <if condition="$data.type eq 1 ">
                            <tr>
                                <td width="120">订单总金额：</td>
                                <td>{{$order->money}}</td>
                            </tr>
                            <else />

                        </if>
                        <tr>
                            <td width="120">运费：</td>
                            <td>{{$order->carriage}}</td>
                        </tr>
                        <tr>
                            <td width="120">备注：</td>
                            <td>{{$order->label}} : {{$order->beizhu}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="am-panel am-panel-default">
                <div class="am-panel-hd am-cf">发货信息
                <!--  @if(!empty($data->extend))
                    <a type="button" class="am-btn am-btn-warning am-btn-xs am-fr" style="margin-left: 10px;" @click="wuliu({{$data->orderid}})">查看物流信息</a>
                    @endif -->
                    @if($data['order_state']== 30)
                        <a type="button" class="am-btn am-btn-primary am-btn-xs am-fr" @click="edit({{$data['snop']['snopid']}})">修改发货信息</a>
                    @endif
                </div>
                <div class="am-panel-bd">
                    <table class="am-table">
                        <tbody>
                        <tr>
                            <td width="120">收货人：</td>
                            <td>{{$data['address']->name or ''}} </td>
                        </tr>
                        <tr>
                            <td>省市区：</td>
                            <td>{{$data['address']->province or ''}} {{$data['address']->city or ''}} {{$data['address']->district or ''}}</td>
                        </tr>
                        <tr>
                            <td>详细地址：</td>
                            <td>{{$data['address']->address or ''}}</td>
                        </tr>
                        <tr>
                            <td>联系电话：</td>
                            <td>{{$data['address']->phone or ''}}</td>
                        </tr>
                        <tr>
                            <td>配送方式：</td>
                            <td>{{$data['extend']->express or ''}}</td>
                        </tr>
                        <tr>
                            <td>物流单号：</td>
                            <td>{{$data['extend']->couriernumber or ''}}</td>
                        </tr>
                        <tr>
                            <td>发货时间：</td>
                            <td>{{$data['extend']->created_at or ''}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            {{--<div class="am-panel am-panel-default">--}}
            {{--<div class="am-panel-bd">--}}
            {{--<if condition="$data.status eq 1 ">--}}
            {{--<a type="button" class="am-btn am-btn-success am-btn-sm" @click="agree({$data.returnid})">同意退货</a>--}}
            {{--<a type="button" class="am-btn am-btn-danger am-btn-sm" @click="refuse({$data.returnid})">拒绝退货</a>--}}
            {{--</if>--}}
            {{--<if condition="$data.status eq 4 ">--}}
            {{--<a type="button" class="am-btn am-btn-warning am-btn-sm" @click="shouhuo({$data.returnid})">确认收货</a>--}}
            {{--</if>--}}
            {{--</div>--}}
            {{--</div>--}}
        </div>
    </form>
    <div style="clear: both"></div>
</div>

<!--[if lt IE 9]>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="/assets/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->
<script src="/layer/layer.js"></script>
<script src="/assets/js/amazeui.min.js"></script>
<script src="/assets/js/hw-layer.js"></script>
<script src="/assets/js/app.js"></script>
<script>
    var vm = new Vue({
        el: 'body',
        data: {
        },
        methods: {
            edit:function (id) {
                layer_show("修改发货信息",'/admin/order/fahuo?id='+id,400,400)
            },
            wuliu:function(id){
                layer_show("查看物流信息",'/Home/order/wuliu?id='+id,300,500)
            },
            gouser:function (id) {
                layer_full("搜索用户","/admin/user/lists?type=2&keyword="+id+"&hw=2")
            },
        }
    })

</script>
</body>
</html>
