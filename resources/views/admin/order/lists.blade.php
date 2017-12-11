<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="/assets/css/amazeui.min.css"/>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <script src="//cdn.bootcss.com/vue/1.0.24/vue.min.js"></script>
    <style>
        .am-btn-default{background: none}
        .am-dropdown-content{z-index: 9999;background: #fff}
        .am-nav-tabs>li>a{font-size: 14px}
        .am-table span{font-size: 14px;font-weight: normal;margin-right: 50px;}
        .am-table span em{font-style: normal}
        .am-table{font-size: 13px}
        .w70{width: 500px}
        .bdl{text-align: center;width: 230px;}
        .am-table-bordered>thead+tbody>tr:first-child>td, .am-table-bordered>thead+tbody>tr:first-child>th{border-top: none}
        address, blockquote, dl, fieldset, figure, hr, ol, p, pre, ul{margin: 0px;}
        .ncm-goods-thumb img{float: left;margin-right: 15px}
        .am-table{margin-bottom: 20px}
        .am-table button{display: block;margin-bottom: 10px;}
        .am-table thead{background: #FAFAFA}
        .mr0{margin-right: 0px !important;}
        .am-btn-default{background: none}
        .am-dropdown-content{z-index: 9999;background: #fff}
        label{font-weight: normal;margin-right: 10px;}
        .am-form-group{margin-bottom: 0px;display: inline-block;margin-right: 10px;}
        .am-panel{margin-right: 30px;margin-left: 15px;border: none;-webkit-box-shadow: none;box-shadow: none}
        .am-panel-bd{padding: .5rem;}
        .am-form select{width: 100px;display: inline-block;margin-right: 10px;}
        .am-form input[type='text']{width: 200px;display: inline-block}
        .am-form input[type=number], .am-form input[type=search], .am-form input[type=text], .am-form input[type=password], .am-form input[type=datetime], .am-form input[type=datetime-local], .am-form input[type=date], .am-form input[type=month], .am-form input[type=time], .am-form input[type=week], .am-form input[type=email], .am-form input[type=url], .am-form input[type=tel], .am-form input[type=color], .am-form select, .am-form textarea, .am-form-field{padding: .4rem;border-radius: 0px;}
        .laydate-icon, .laydate-icon-default, .laydate-icon-danlan, .laydate-icon-dahong, .laydate-icon-molv{height: 32px !important;padding-left: 10px!important;}
    </style>
</head>
<body>
<div class="am-cf admin-main2">
    <!-- content start -->
    <div class="admin-content">
        <div class="admin-content-body">
            <if condition="$_GET['type'] neq 2 ">
                <div class="am-cf am-padding am-padding-bottom-0 am-animation-slide-top hw-nav" >
                    <div class="am-fl am-cf">
                        <ol class="am-breadcrumb">
                            <li class="am-active">订单列表</li>
                        </ol>
                    </div>
                    <div class="am-fr am-cr">
                        <a class="am-btn am-btn-warning hw-shuaxin" href="javascript:location.replace(location.href);">
                            <i class="am-icon-refresh"></i>
                        </a>
                    </div>
                </div>
                <div class="am-panel am-panel-default">
                    <form class="am-form" action="/admin/order/lists" method="get" id="from1">
                        <div class="am-panel-bd">
                            <div class="am-form-group">
                                <label for="doc-select-1">状态：</label>
                                <select id="doc-select-1" name="status">
                                    <option value="0" @if($status==0) selected @endif>全部</option>
                                    <option value="10" @if($status==10) selected @endif >待付款</option>
                                    <option value="20" @if($status==20) selected @endif >待发货</option>
                                    <option value="30" @if($status==30) selected @endif >待收货</option>
                                    <option value="50" @if($status==50) selected @endif >已完成</option>
                                    <option value="100" @if($status==100) selected @endif >微信名</option>
                                    <option value="110" @if($status==110) selected @endif >商品名称</option>
                                    <option value="120" @if($status==120) selected @endif >商品分类</option>
                                    <option value="130" @if($status==130) selected @endif >订单号</option>
                                </select>
                            </div>
                            <div class="am-form-group">
                                <label for="doc-select-2">关键词：</label>
                                <input type="text" name="transaction" id="doc-select-2">
                            </div>
                            <label for="doc-select-1">开始日期：</label>
                            <div class="am-form-group am-form-icon">
                                <input id="start" class="laydate-icon" value="{{$start}}" readonly name="start">
                            </div>
                            <label for="doc-select-1"> 结束日期：</label>
                            <div class="am-form-group am-form-icon">
                                <input id="end" class="laydate-icon" value="{{$end}}" readonly name="end">
                            </div>
                            <a class="am-btn am-btn-sm am-btn-primary" @click="shaixuan">
                                <i class="am-icon-search"></i>
                                筛选
                            </a>
                            <a class="am-btn am-btn-sm am-btn-secondary" @click="export">
                                <i class="am-icon-cloud-download"></i>
                                导出订单
                            </a>
                            <a class="am-btn am-btn-sm am-btn-primary" @click="shaixuanwx">
                                <i class="am-icon-search"></i>
                                按微信昵称排序
                            </a>
                            <a class="am-btn am-btn-sm am-btn-primary" @click="shaixuantime">
                                <i class="am-icon-search"></i>
                                按默认顺序排序
                            </a>
                        </div>
                    </form>
                </div>
            </if>
            <div class="am-g am-animation-slide-right">
                <div class="am-u-sm-12">
                    <form class="am-form">
                        <table class="am-table am-table-bordered">
                            @foreach($data as $v)
                                <thead>
                                <tr>
                                    <th colspan="20">
                                        <span class="ml10">订单编号：<em>{{$v->transaction}}</em></span>
                                        <span>下单时间：<em class="goods-time">{{  $v->created_at }}</em></span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($v['snop'] as $key=>$val)
                                    <tr>
                                        <td class="w70">
                                            <div class="ncm-goods-thumb">
                                                <img src="{{$val['snopjson']->thumbnail}}" width="60" height="60">
                                                {{$val['snopjson']->title}}
                                            </div>
                                        </td>
                                        <td width="200" class="bdl">
                                            <p>{{$val['snopjson']->money}}</p>
                                        </td>
                                        <td width="50">x{{$val['nums']}}</td>
                                        <td class="bdl">
                                            <p @click="gouser({{$v->user->uid or ''}})"> <img src="{{$v->user->img or ''}}" width="50" style="border-radius: 50%"><br>{{$v->user->nickname or ''}} </p>
                                        </td>

                                        <td class="bdl" rowspan="0" >
                                            @if($key==0)
                                                <p class=""><strong style="color:red">{{ $v->money }}</strong></p>
                                                <p class="goods-freight" >
                                                    @if($v->carriage == 0)
                                                        （免运费）
                                                    @else
                                                        （运费：{{ $v->carriage }}）
                                                    @endif
                                                </p>
                                                <p title="支付方式：在线付款">微信支付</p>
                                            @endif
                                        </td>
                                        {{--@if($key==0)--}}
                                        <td class="bdl" rowspan="0">

                                            <p>
                                        <span class="am-badge am-badge-secondary am-radius mr0">
                                            @if($v->order_state == 0)
                                                已关闭
                                            @elseif($v->order_state == 10)
                                                待付款
                                            @elseif($v->order_state == 20 && $val['expressed'] == "" )
                                                待发货
                                            @elseif($val['expressed'] != "null" && $v->order_state == 30)
                                                待收货
                                            @elseif(!empty($val['expressed'])  && $v->order_state < 30)
                                                待收货
                                            @elseif($v->order_state == 40)
                                                待评价
                                            @elseif($v->order_state == 50)
                                                已完成
                                            @elseif($v->order_state == 60)
                                                退货中
                                            @elseif($v->order_state == 70)
                                                退货完成
                                            @elseif($v->order_state == 80)
                                                拒绝退货
                                            @endif
                                        </span>
                                            </p>
                                            <p><a @click="detail({{$val['snopid']}})" style="cursor: pointer">订单详情</a></p>
                                            {{--@else--}}
                                            {{--<td class="bdl" rowspan="0"></td>--}}


                                        </td>
                                        {{--@endif--}}
                                        <td class="bdl" rowspan="0" style="width: 120px">
                                            {{--@if($key==0)--}}
                                            @if($v->order_state == 10)
                                                <button type="button" class="am-btn am-btn-danger am-btn-sm" @click="cancle_order({{$v->orderid}},this,i)">取消订单</button>
                                            @elseif($val['expressed'] == "" && $v->order_state == 20)

                                                <a class="am-btn am-btn-success am-btn-sm" @click="fahuo( {{$val['snopid']}},this,i)">
                                                    <i class="am-icon-truck"></i>
                                                    设置发货
                                                </a>
                                            @endif
                                            {{--<template v-if="item.order_state == 30 && item.peisong == 1">--}}
                                            {{--<button type="button" class="am-btn am-btn-primary am-btn-sm" @click="dy(item.orderid,this,i)">打印订单</button>--}}
                                            {{--</template>--}}
                                            {{--@endif--}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            @endforeach
                        </table>
                        <div class="am-cf" style="padding:0px 30px 30px 30px;margin-bottom: 30px;">
                            <div class="am-fl hw-jilu">共 {!! $data->count() !!} 条记录</div>
                            <div class="am-fr">
                                {{ $data->appends(['status' =>$status,'transaction'=>$transaction,'start'=>$start,'end'=>$end])->links() }}
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- content end -->
</div>

<!--[if lt IE 9]>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="/assets/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->
<script src="//cdn.bootcss.com/jquery/2.0.2/jquery.min.js"></script>
<script src="/layer/layer.js"></script>
<script src="/layer/laydate.js"></script>
<script src="/assets/js/amazeui.min.js"></script>
<script src="/assets/js/hw-layer.js"></script>
<script src="/assets/js/app.js"></script>
{{--<script src="/hw/CLodopfuncs.js"></script>--}}
<script>
    var start = {
        elem: '#start',
        format: 'YYYY-MM-DD hh:mm:ss',
        max: '2099-06-16 23:59:59', //最大日期
        istime: true,
        istoday: true,
        choose: function(datas){
            end.min = datas;
        }
    };
    var end = {
        elem: '#end',
        format: 'YYYY-MM-DD hh:mm:ss',
        max: '2099-06-16 23:59:59',
        istime: true,
        istoday: false,
        choose: function(datas){
            start.max = datas; //结束日选好后，重置开始日的最大日期
        }
    };
    laydate(start);
    laydate(end);
</script>
<script>
    var vm = new Vue({
        el: 'body',
        data: {
            nub:2,
        },
        methods: {
            detail: function (id) {
                layer_full("查看订单详情",'detail/?id='+id)
            },
//            dy:function (id){
//                $.get('__CONTROLLER__/dayin?id='+id,function ($dat) {
//                    console.log($dat);
//                    console.log($dat.address.province);
//                    LODOP.ADD_PRINT_RECT(50, 5, 243, 79, 0, 1);
//                    LODOP.ADD_PRINT_RECT(50, 248, 125, 79, 0, 1);
//                    LODOP.ADD_PRINT_BARCODE(70, 25, 224, 50, "128Auto", ""+ $dat.extend.couriernumber +"");
//                    LODOP.ADD_PRINT_TEXT(70, 260, 348, 25, "标准快递");
//                    LODOP.ADD_PRINT_TEXT(90, 260, 348, 25, "目的地：010");
//
//                    //申通是EAN128C
//
//
//                    LODOP.ADD_PRINT_RECT(129, 5, 368, 56, 0, 1);//收件人
//                    LODOP.ADD_PRINT_TEXT(145, 20, 348, 25, "收件方："+ $dat.address.province +" "+ $dat.address.city +" "+ $dat.address.district +" "+ $dat.address.address +" "+ $dat.address.name +" "+ $dat.address.phone +"");
//
//                    LODOP.ADD_PRINT_RECT(184, 5, 165, 83, 0, 1);//结款
//                    LODOP.ADD_PRINT_TEXT(202, 20, 175, 83, "微信昵称："+ $dat.user.nickname +"");
//
//                    LODOP.ADD_PRINT_RECT(184, 5, 368, 83, 0, 1);//结款
//                    LODOP.ADD_PRINT_RECT(184, 169, 204, 49, 0, 1);//结款
//                    LODOP.ADD_PRINT_TEXT(205, 180, 175, 50, "订单号："+ $dat.uniqueid +"");
//                    LODOP.ADD_PRINT_TEXT(245, 180, 175, 35, "运费："+ $dat.carriage +"");
//
//                    LODOP.ADD_PRINT_RECT(266, 5, 368, 75, 0, 1);//寄件方
//                    LODOP.ADD_PRINT_TEXT(276, 20, 175, 50, "寄件方：浙江省 杭州市 萧山区 启迪路467号C幢3A02  主力超  15858254555");
//                    LODOP.ADD_PRINT_RECT(266, 5, 184, 75, 0, 1);//寄件方
//                    LODOP.ADD_PRINT_TEXT(276, 200, 90, 50, "收件员：");
//                    LODOP.ADD_PRINT_TEXT(305, 200, 90, 50, "寄件日期："+ $dat.extend.settime +"");
//                    LODOP.ADD_PRINT_RECT(266, 5, 276, 75, 0, 1);//寄件方
//                    LODOP.ADD_PRINT_TEXT(276, 290, 90, 50, "签名：");
//                    LODOP.ADD_PRINT_TEXT(305, 290, 90, 50, "日期：");
//
//
//                    LODOP.ADD_PRINT_RECT(357, 5, 368, 64, 0, 1);//二维码
//                    LODOP.ADD_PRINT_IMAGE(350, 20, 150, 64, "<img border='0' src='https://ss2.baidu.com/6ONYsjip0QIZ8tyhnq/it/u=2082048896,4137516249&fm=58&s=4298EA2B9CB4CE905CF43DD6010080B3'width='119' height='75'/>");
//                    LODOP.ADD_PRINT_RECT(357, 5, 164, 64, 0, 1);//二维码
//                    LODOP.ADD_PRINT_BARCODE(367, 175, 224, 50, "128Auto", ""+ $dat.extend.couriernumber +"");
//
//                    LODOP.ADD_PRINT_RECT(421, 5, 368, 64, 0, 1);//信息
//                    LODOP.ADD_PRINT_TEXT(428, 20, 175, 50, "寄件方：浙江省 杭州市 萧山区 启迪路467号C幢3A02  主力超  15858254555");
//                    LODOP.ADD_PRINT_RECT(421, 5, 184, 64, 0, 1);//信息
//                    LODOP.ADD_PRINT_TEXT(428, 200, 175, 50, "收件方："+ $dat.address.province +" "+ $dat.address.city +" "+ $dat.address.district +" "+ $dat.address.address +" "+ $dat.address.name +" "+ $dat.address.phone +"");
//
//
//                    LODOP.ADD_PRINT_RECT(485, 5, 368, 20, 0, 1);//信息
//                    LODOP.ADD_PRINT_TEXT(490, 20, 175, 50, "数量");
//                    LODOP.ADD_PRINT_RECT(485, 5, 50, 20, 0, 1);//信息
//                    LODOP.ADD_PRINT_TEXT(490, 150, 175, 50, "拖寄物");
//                    LODOP.ADD_PRINT_RECT(485, 5, 300, 20, 0, 1);//信息
//                    LODOP.ADD_PRINT_TEXT(490, 320, 175, 50, "备注"+ $dat.beizhu +"");
//
//
//                    LODOP.ADD_PRINT_RECT(505, 5, 368, 30, 0, 1);//信息
//                    LODOP.ADD_PRINT_TEXT(515, 20, 175, 50, "x"+ $dat.snop[0]['nums'] +"");
//                    LODOP.ADD_PRINT_RECT(505, 5, 50, 30, 0, 1);//信息
//                    LODOP.ADD_PRINT_TEXT(507, 80, 175, 50, ""+ $dat.snop[0]['snopjson']['title'] +"");
//                    LODOP.ADD_PRINT_RECT(505, 5, 300, 30, 0, 1);//信息
//
//
//                    LODOP.ADD_PRINT_RECT(534, 5, 368, 20, 0, 1);//信息
//                    LODOP.ADD_PRINT_TEXT(540, 10, 250, 20, "订单号："+ $dat.uniqueid +" ");
//                    LODOP.ADD_PRINT_RECT(534, 5, 250, 20, 0, 1);//信息
//                    LODOP.ADD_PRINT_TEXT(540, 260, 100, 20, "合计："+ $dat.money +"元");
//
//
//                    LODOP.PREVIEW();
//                    console.log("ss")
//                })
//            },
            export:function () {
                var start = $('#start').val();
                var end = $('#end').val();
                var status = $('#doc-select-1 option:selected').val();
                window.location.href = "export?start_time="+start+"&end_time="+end+"&status="+status;
            },
            shaixuan:function(){
                $("#from1").submit();
            },
            shaixuanwx:function () {
                location.href = '/admin/order/listsOrder/0';
            },
            shaixuantime:function () {
                location.href = '/admin/order/listsOrder/1';
            },
            cancle_order:function (id,event,aid) {
                layer.confirm('确定要取消该订单吗？', {
                    btn: ['确定','取消'] //按钮
                }, function(){
                    $.get('cancelorder',{id:id},function (data) {
                        if(data['status'] == "200"){
                            layer.msg('取消订单成功！',function () {
                                window.location.reload();
                            });
                        }else{
                            layer.msg(data['msg']);
                        }
                    })
                }, function(){
                });
            },
            gouser:function (id) {
                layer_full("搜索用户","/admin/user/lists?type=2&keyword="+id+"&hw=2")
            },
            change_price:function (id,event,aid) {
                layer.prompt({
                    title: '输入新的订单价格',
                    formType: 0 //prompt风格，支持0-2
                }, function(pass){
                    $.get('__CONTROLLER__/changeorder',{id:id,money:pass},function (data) {
                        console.log(aid)
                        $temp = $.parseJSON(data);
                        if($temp['status'] == "200"){
                            vm.order[aid].money = pass
                            layer.msg('修改成功！');
                        }else{
                            layer.msg($temp['text']);
                        }
                    })
                });
            },
            fahuo:function (id) {
                layer_show('订单发货','fahuo/?id='+id,500,390)
//                layer.msg(id)
            },
            fahuos:function (id,peisong) {
//                console.log(peisong);return;
                $.get('__CONTROLLER__/handles',{id:id,peisong:peisong},function (data) {
                    $temp = $.parseJSON(data);
                    if($temp['status'] == "200"){
                        layer.msg("发货成功",function () {
                            window.location.reload()
                        });
                    }else{
                        layer.msg("发货失败");
                    }
                })
            },
        }
    })

</script>

</body>
</html>