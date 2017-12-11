<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="/assets/css/amazeui.min.css"/>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <script src="//cdn.bootcss.com/vue/1.0.24/vue.min.js"></script>
    <style>
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
            <div class="am-cf am-padding am-padding-bottom-0 am-animation-slide-top hw-nav" >
                <div class="am-fl am-cf">
                    <ol class="am-breadcrumb">
                        <li class="am-active">退款申请列表</li>
                    </ol>
                </div>
                <div class="am-fr am-cr">
                    <a class="am-btn am-btn-warning hw-shuaxin" href="javascript:location.replace(location.href);">
                        <i class="am-icon-refresh"></i>
                    </a>
                </div>
            </div>
            <div class="am-panel am-panel-default">
                <form class="am-form" action="/admin/orderrefunds/lists" method="get" id="from1">
                    <div class="am-panel-bd">
                        <div class="am-form-group">
                            <label for="doc-select-2">订单编号：</label>
                            <input type="text" name="uniqueid" id="doc-select-2">
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
                    </div>
                </form>
            </div>
            <div class="am-g am-animation-slide-right">
                <div class="am-u-sm-12">
                    <form class="am-form">
                        <table class="am-table am-table-striped am-table-hover table-main">
                            <thead>
                            <tr>
                                <th width="240">退款编号</th>
                                <th>订单编号</th>
                                <th>退款金额</th>
                                <th>申请时间</th>
                                <th>处理状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $v)
                                <tr>
                                    <td>{{$v->refundsid}}</td>
                                    <td>{{$v->order->uniqueid}}</td>
                                    <td>{{ $v->order->money }}</td>
                                    <td>{{ $v->created_at }}</td>
                                    <td>
                                        @if($v->status ==1)
                                        处理中
                                        @elseif($v->status ==2)
                                        已退款
                                        @elseif($v->status ==3)
                                        已拒绝
                                        @endif
                                    </td>
                                    <td width="240">
                                        <a type="button" class="am-btn am-btn-secondary am-btn-sm" @click="detail({{$v->order->orderid}})">订单详情</a>
                                        @if($v->status ==1)
                                            <a type="button" class="am-btn am-btn-success am-btn-sm" @click="agree({{$v->refundsid}},mykey,this)">同意</a>
                                            <a type="button" class="am-btn am-btn-danger am-btn-sm" @click="refuse({{$v->refundsid}},mykey,this)">拒绝</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="am-cf" style="padding:0px 30px 30px 30px;margin-bottom: 30px;">
                            <div class="am-fl hw-jilu">共 {!! $data->count() !!} 条记录</div>
                            <div class="am-fr">
                                {!! $data->links() !!}
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
        },
        methods: {
            agree: function (id,key,event) {
                layer.confirm('确定已经退款给用户了吗？', {
                    btn: ['确定','取消'] //按钮
                }, function(){
                    $.get('agree/?id='+id,function(data){
                        if(data['status'] == "200"){
                            layer.msg('操作成功！',{icon:1},function () {
                                window.location.reload();
                            });
                        }else{
                            layer.msg(data['msg'],{icon:2});
                        }
                    })
                }, function(){

                });
            },
            shaixuan:function(){
                $("#from1").submit();
            },
            detail: function (id) {
                layer_full("查看订单详情",'/admin/order/detail/?id='+id)
            },
            refuse: function (id,key,event) {
                layer.confirm('确定已经拒绝该退款申请吗？', {
                    btn: ['确定','取消'] //按钮
                }, function(){
                    $.get('refuse/?id='+id,function(data){
                        if(data['status'] == "200"){
                            layer.msg('操作成功！',{icon:1},function () {
                                window.location.reload();
                            });
                        }else{
                            layer.msg(data['msg'],{icon:2});
                        }
                    })
                }, function(){

                });
            },
        }
    })

</script>
</body>
</html>