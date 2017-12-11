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
                        <li class="am-active">定制列表</li>
                    </ol>
                </div>
                <div class="am-fr am-cr">
                    <a class="am-btn am-btn-warning hw-shuaxin" href="javascript:location.replace(location.href);">
                        <i class="am-icon-refresh"></i>
                    </a>
                    <a class="am-btn am-btn-secondary hw-shuaxin tianjia" @click="add()">
                        <i class="am-icon-plus"></i>
                    </a>
                </div>
            </div>
            <div class="am-panel am-panel-default">
                <form class="am-form" action="/admin/design/lists" method="get" id="from1">
                    <div class="am-panel-bd">
                        <div class="am-form-group">
                            <label for="doc-select-2">定制用户：</label>
                            <input type="text" name="nickname" id="doc-select-2">
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
                                <th width="240">定制编号</th>
                                <th>定制用户</th>
                                <th>定制分类</th>
                                {{--<th>定制标题</th>--}}
                                <th>定制类型</th>
                                <th>申请时间</th>
                                <th>处理状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $v)
                                <tr>
                                    <td>{{$v->designid or ''}}</td>
                                    <td><img src="{{$v->user->img or ''}}" width="40"> {{ $v->user->nickname or '' }}</td>
{{--                                    <td>{{ $v->title or '' }}</td>--}}
                                    <td>
                                        {{$v->cate_id}}
                                    </td>
                                    <td>
                                        @if($v->is_qiye==1)
                                            私人定制
                                        @else
                                            企业定制
                                        @endif
                                    </td>
                                    <td>{{ $v->created_at or '' }}</td>
                                    <td>
                                        @if($v->status==1)
                                            处理中
                                            @elseif($v->status==2)
                                            已同意
                                            @else
                                            已拒绝
                                        @endif
                                    </td>
                                    <td width="240">
                                        @if($v->status==2)
                                            <a type="button" class="am-btn am-btn-success am-btn-sm" @click="details({{$v->designid or ''}},{{$v->is_qiye or ''}})">编辑</a>
                                            <a type="button" class="am-btn am-btn-secondary am-btn-sm" @click="detail({{$v->designid or ''}},{{$v->is_qiye or ''}})">定制详情</a>
                                            <a type="button" class="am-btn am-btn-danger am-btn-sm" @click="del({{$v->designid or ''}},{{$v->is_qiye or ''}})">删除</a>
                                            @elseif($v->status==1)
                                            <a type="button" class="am-btn am-btn-success am-btn-sm" @click="agree({{$v->designid or ''}},mykey,this)">同意</a>
                                            <a type="button" class="am-btn am-btn-danger am-btn-sm" @click="refuse({{$v->designid or ''}},mykey,this)">拒绝</a>
                                            <a type="button" class="am-btn am-btn-secondary am-btn-sm" @click="detail({{$v->designid or ''}},{{$v->is_qiye or ''}})">定制详情</a>
                                            {{--<a type="button" class="am-btn am-btn-danger am-btn-sm" @click="del({{$v->designid or ''}},{{$v->is_qiye or ''}})">删除</a>--}}
                                            @elseif($v->status==3)
                                            <a type="button" class="am-btn am-btn-secondary am-btn-sm" @click="detail({{$v->designid or ''}},{{$v->is_qiye or ''}})">定制详情</a>
                                            <a type="button" class="am-btn am-btn-danger am-btn-sm" @click="del({{$v->designid or ''}},{{$v->is_qiye or ''}})">删除</a>
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
                layer.confirm('确定同意该用户定制吗？', {
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
            detail: function (id,is_qiye) {
                layer_full("查看定制详情",'/admin/design/detail/?id='+id+"&is_qiye="+is_qiye)
            },
            details: function (id,is_qiye) {
                layer_full("修改定制信息",'/admin/design/details/?id='+id+"&is_qiye="+is_qiye)
            },
            add:function(){
                layer_full("添加定制信息",'/admin/design/details')
            },
            del:function(id,is_qiye){
//                alert(12345);return false;
                layer.confirm('确定删除定制信息吗？', {
                    btn: ['确定','取消'] //按钮
                },function(){
                    $.get('/admin/design/del?id='+id,function(data){
                        if(data['status'] == "200"){
                            layer.msg('操作成功！',{icon:1},function () {
                                window.location.reload();
                            });
                        }else{
                            layer.msg(data['msg'],{icon:2});
                        }
                    })
                });

            },
            refuse: function (id,key,event) {
                layer.confirm('确定拒绝该用户定制吗？', {
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