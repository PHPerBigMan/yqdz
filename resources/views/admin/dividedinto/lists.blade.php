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
        label{font-weight: normal;margin-right: 10px;}
        .am-form-group{margin-bottom: 0px;display: inline-block;margin-right: 10px;}
        .am-panel{margin-right: 30px;margin-left: 15px}
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
                            <li class="am-active">订单分成记录</li>
                        </ol>
                    </div>
                    <div class="am-fr am-cr">
                        <a class="am-btn am-btn-warning hw-shuaxin" href="javascript:location.replace(location.href);">
                            <i class="am-icon-refresh"></i>
                        </a>
                    </div>
                </div>
                <div class="am-panel am-panel-default">
                    <form class="am-form" action="/admin/dividedinto/lists" method="get" id="from1">
                        <div class="am-panel-bd">
                            <div class="am-form-group">
                                <label for="doc-select-1">状态：</label>
                                <select id="doc-select-1" name="status">
                                    <option value="1">未处理</option>
                                    <option value="2">已放款</option>
                                </select>
                            </div>
                            <div class="am-form-group">
                                <label for="doc-select-2">用户ID：</label>
                                <input type="text" name="uid">
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
            </if>

            <if condition="$_GET['type'] eq 2 ">
                <div class="am-alert am-alert-success" data-am-alert="" style="background: #f9edbe;border: 1px solid #f0c36d;color: #333;font-size: 12px;margin: 10px 30px 0px 10px">
                    <button type="button" class="am-close">×</button>
                    <p>分成金额低于1元的请使用批量合并放款</p>
                </div>
            </if>

            <div class="am-g am-animation-slide-right">
                <div class="am-u-sm-12">
                    <form class="am-form">
                        <table class="am-table am-table-striped am-table-hover table-main">
                            <thead>
                            <tr>
                                {{--<th class="table-check"><input type="checkbox" v-model="is_xuan" @click="chose"></th>--}}
                                <th width="80">ID</th>
                                <th>获佣用户</th>
                                <th>订单编号</th>
                                <th>分成金额</th>
                                <th>分成级别</th>
                                <th>分成时间</th>
                                <th>状态</th>
                                <if condition="$_GET['type'] eq 2 ">
                                    <th class="table-set" width="220">操作</th>
                                    <else />
                                    {{--<th class="table-set" width="120">操作</th>--}}
                                </if>
                            </tr>
                            </thead>
                            <tbody>
                            {{--@foreach($data as $v)--}}
                                {{--<tr>--}}
                                    {{--<td v-if="{{$v->status == 1}}"><input type="checkbox" id="id:{{ $v->intoid }}" value="{{ $v->intoid }}" v-model="checkedNames"></td>--}}
                                    {{--<td v-else><input type="checkbox" id="id:{{ $v->intoid }}" value="{{ $v->intoid }}" v-model="checkedNames" disabled></td>--}}
                                    {{--<td>{{ $v->intoid }}</td>--}}
                                    {{--<td @click="gouser({{$v->uid}})"><img :src="{{$v->img}}" width="40" style="margin-right: 10px;">{{ $v->nickname }}</td>--}}
                                    {{--<td>{{ $v->uniqueid }} </td>--}}
                                    {{--<td>{{ $v->money }}元</td>--}}
                                    {{--<td>{{ $v->level }}级</td>--}}
                                    {{--<td>{{ $v->created_at }}</td>--}}
                                    {{--<td v-if="{{$v->status == 1}}">未处理</td>--}}
                                    {{--<td v-if="{{$v->status == 2}}">已放款</td>--}}
                                    {{--<td>--}}
                                        {{--<div class="am-btn-toolbar">--}}
                                            {{--<a type="button" class="am-btn am-btn-secondary am-btn-sm" @click="detail({{ $v->uniqueid }})">订单详情</a>--}}
                                            {{--<if condition="$_GET['type'] eq 2 ">--}}
                                                {{--<a type="button" class="am-btn am-btn-success am-btn-sm" @click="fangkuan({{ $v->intoid }},mykey)" v-if="{{$v->status == 1}}">单独放款</a>--}}
                                            {{--</if>--}}
                                        {{--</div>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                            {{--@endforeach--}}
                            <template v-for="(mykey,item) in data">
                                <tr>
                                    {{--<td v-if="item.status == 1"><input type="checkbox" id="id:@{{ item.intoid }}" value="@{{ item.intoid }}" v-model="checkedNames"></td>--}}
                                    {{--<td v-else><input type="checkbox" id="id:@{{ item.intoid }}" value="@{{ item.intoid }}" v-model="checkedNames" disabled></td>--}}
                                    <td>@{{ item.intoid }}</td>
                                    <td @click="detail(item.orderid)"><img :src="item.img" width="40" style="margin-right: 10px;">@{{ item.nickname }}</td>
                                    <td>@{{ item.uniqueid }} </td>
                                    <td>@{{ item.money }}元</td>
                                    <td>@{{ item.level }}级</td>
                                    <td>@{{ item.created_at }}</td>
                                    <td v-if="item.status == 1">未处理</td>
                                    <td v-if="item.status == 2">已放款</td>
                                    <td>
                                        <div class="am-btn-toolbar">
                                            <a type="button" class="am-btn am-btn-secondary am-btn-sm" @click="detail(item.orderid)">订单详情</a>
                                            <if condition="$_GET['type'] eq 2 ">
                                                <a type="button" class="am-btn am-btn-success am-btn-sm" @click="fangkuan(item.intoid,mykey)" v-if="item.status == 1">单独放款</a>
                                            </if>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                        <if condition="$_GET['type'] eq 2 ">
                            {{--<button type="button" class="am-btn am-btn-warning am-btn-sm" @click="plfk(checkedNames)">批量放款</button>--}}
                        </if>
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
<if condition="$_GET['type'] neq 2 ">
    <script>
        var start = {
            elem: '#start',
            format: 'YYYY/MM/DD hh:mm:ss',
            max: '2099-06-16 23:59:59', //最大日期
            istime: true,
            istoday: true,
            choose: function(datas){
                end.min = datas;
            }
        };
        var end = {
            elem: '#end',
            format: 'YYYY/MM/DD hh:mm:ss',
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
</if>
<script>
    var vm = new Vue({
            el: 'body',
            data: {
                data:{!! $test !!},
                checkedNames: [],
                is_xuan:false,
        },
        methods: {
        detail: function (id) {
            layer_full("查看订单详情",'/admin/order/detail/?id='+id)
        },
        shaixuan:function(){
            $("#from1").submit();
        },
        gouser:function (id) {
            layer_full("搜索用户","admin/user/lists?type=2&keyword="+id+"&hw=2")
        },
        fangkuan: function (id,key) {
            layer.confirm('确定要放款吗？', {
                title:"打款操作",
                btn: ['确定','取消'] //按钮
            }, function(){
                $.get('fangkuan/?id='+id,function(data){
                    if(data['status'] == "200"){
                        vm.data[key].status = "2"
                        layer.msg(data['msg'], {icon: 1});
                    }else{
                        layer.msg(data['msg'], {icon: 2});
                    }
                })
            }, function(){

            });
        },
        chose:function () {
            vm.data.forEach(function (v,i) {
                console.log(vm.is_xuan)
                if(vm.is_xuan){
                    vm.checkedNames = []
                }else{
                    vm.checkedNames.push(v.intoid)
                }
            })
        },
        plfk:function(id){
            if(id ==""){
                layer.msg("请先选择要打款的记录!",{icon:2});
                return
            }
            layer.msg('确定要放款吗？', {
                time:0,
                btn: ['确定', '取消'],
                yes: function(){
                    $.get('fangkuan/?id='+id,function (data) {
                        if($temp['status'] == "200"){
                            layer.msg(data['msg'], {icon: 1},function(){
                                location.reload()
                            });
                        }else{
                            layer.msg(data['msg'], {icon: 2});
                        }
                    })
                }
            });
        },
        shaixuan:function(){
            $("#from1").submit();
        }
    }
    })

</script>
</body>
</html>