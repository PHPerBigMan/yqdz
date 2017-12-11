<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="/assets/css/amazeui.min.css"/>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <script src="//cdn.bootcss.com/vue/1.0.24/vue.min.js"></script>
    <style>
        .am-btn-sm{margin-right: 10px;}
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
                        <li class="am-active">会员列表</li>
                    </ol>
                </div>
                <div class="am-fr am-cr">
                    <a class="am-btn am-btn-warning hw-shuaxin" href="javascript:location.replace(location.href);">
                        <i class="am-icon-refresh"></i>
                    </a>
                </div>
            </div>
            <div class="am-alert am-alert-secondary am-animation-scale-up" style="margin: 0px 20px;">
                <form class="am-form" action="/admin/user/lists" method="get"  id="from1">
                    搜索类型：
                    <select name="type" class="form-control" style="width: 120px;display: inline-block;height: 35px;font-size: 14px" id="type">
                        <option value="1">搜昵称</option>
                        <option value="2">搜ID</option>
                    </select>
                    <input type="text" class="am-input-sm" name="keyword" value="" style="width: 200px;display: inline-block;height: 35px;" id="keyword">
                    <button type="submit" class="am-btn am-btn-secondary" style="height: 34px;line-height: 14px">筛选</button>
                    <a class="am-btn am-btn-sm am-btn-secondary" @click="export">
                        <i class="am-icon-cloud-download"></i>
                        导出用户信息
                    </a>
                </form>
            </div>

            <div class="am-g am-animation-slide-right">
                <div class="am-u-sm-12">
                    <form class="am-form">
                        <table class="am-table am-table-striped am-table-hover table-main">
                            <thead>
                            <tr>
                                <th width="120">ID</th>
                                <th>微信昵称</th>
                                <th>头像</th>
                                <th>注册时间</th>
                                <th>是否购买</th>
                                <th>状态</th>
                                <th class="table-set" width="400">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($list as $v)
                                <tr>
                                    <td>{{ $v->uid }}</td>
                                    <td>{{ $v->nickname }}</td>
                                    <td><img src="{{ $v->img }}" width="40"> </td>
                                    <td>{{ $v->created_at }}</td>
                                    <td v-if="{{ $v->isbuy == 0 }}"><span class="am-badge am-badge-danger">未购买</span></td>
                                    <td v-else><span class="am-badge am-badge-success">已购买</span></td>
                                    <td v-if="{{ $v->is_delete == 2 }}"><span class="am-badge am-badge-danger">禁止登录</span></td>
                                    <td v-if="{{ $v->is_delete == 1 }}"><span class="am-badge am-badge-success">正常</span></td>
                                    <td>
                                        <div class="am-btn-toolbar">
                                            <div class="am-btn-group am-btn-group-xs">
                                                <a type="button" class="am-btn am-btn-secondary am-btn-sm" @click="fenxiao({{$v->uid}})"><span class="am-icon-sitemap"></span>  分销关系</a>
                                                <a type="button" class="am-btn am-btn-secondary am-btn-sm" @click="info({{$v->uid}})"><span class="am-icon-sitemap"></span>  用户信息</a>
                                                {{--<a type="button" class="am-btn am-btn-warning am-btn-sm" @click="jilu({{$v->uid}})"><span class="am-icon-shopping-bag"></span>  购物记录</a>--}}
                                                <a type="button" class="am-btn am-btn-danger am-btn-sm" @click="shanchu({{$v->uid}},key)" v-if="{{$v->is_delete}} == 1"><span class="am-icon-trash-o"></span>  删除</a>
                                                <a type="button" class="am-btn am-btn-success am-btn-sm" @click="huifu({{$v->uid}},key)" v-if="{{$v->is_delete}} == 2"><span class="am-icon-trash-o"></span>  恢复</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="am-cf" style="padding:0px 30px 30px 30px;margin-bottom: 30px;">
                            <div class="am-fl hw-jilu">共 {!! $list->count() !!} 条记录</div>
                            <div class="am-fr">
                                {!! $list->links() !!}
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
<script src="/assets/js/amazeui.min.js"></script>
<script src="/assets/js/hw-layer.js"></script>
<script src="/assets/js/app.js"></script>
<script>
    var vm = new Vue({
        el: 'body',
        data: {
        },
        methods: {
            shaixuan:function () {
                $("#from1").submit()
            },
            fenxiao:function (id) {
                layer_full("查看分销关系",'fenxiao/?id='+id)
            },
            info:function (id) {
                layer_full("用户信息",'info/?id='+id)
            },
            shanchu: function (id,key) {
                layer.msg('确定要删除吗？', {
                    time:0,
                    btn: ['确定', '取消'],
                    yes: function(){
                        $.get('del/?id='+id+"&type=1",function (data) {
                            if(data['status'] == "200"){
                                layer.msg('删除成功！',function () {
                                    window.location.reload();
                                });
                            }else{
                                layer.msg('删除失败！');
                            }
                        })
                    }
                });
            },
            huifu: function (id,key) {
                layer.msg('确定要恢复帐号吗？', {
                    time:0,
                    btn: ['确定', '取消'],
                    yes: function(){
                        $.get('del/?id='+id+"&type=2",function (data) {
                            if(data['status'] == "200"){
                                layer.msg('恢复成功！',function () {
                                    window.location.reload();
                                });
                            }else{
                                layer.msg('恢复失败');
                            }
                        })
                    }
                });
            },
            export:function () {
                var type = $('#type option:selected').val();
                var keyword = $('#keyword').val();
                window.location.href = "export?type="+type+"&keyword="+keyword;
            },
        }
    })

</script>
</body>
</html>