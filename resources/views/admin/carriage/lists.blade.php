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
        .am-btn-sm{margin-right: 10px;}
    </style>
</head>
<body>
<div class="am-cf admin-main2">
    <!-- content start -->
    <div class="admin-content">
        <div class="admin-content-body">
            <div class="am-cf am-padding am-padding-bottom-0 am-animation-slide-top">
                <div class="am-fl am-cf">
                    <ol class="am-breadcrumb">
                        <li class="am-active">运费模版</li>
                    </ol>
                </div>
                <div class="am-fr am-cr">
                    <a class="am-btn am-btn-warning hw-shuaxin" href="javascript:location.replace(location.href);">
                        <i class="am-icon-refresh"></i>
                    </a>
                    <a class="am-btn am-btn-secondary hw-shuaxin tianjia" v-on:click="add">
                        <i class="am-icon-plus"></i>
                    </a>
                </div>
            </div>
            <hr>

            <div class="am-g am-animation-slide-right">
                <div class="am-u-sm-12">
                    <form class="am-form">
                        <table class="am-table am-table-striped am-table-hover table-main">
                            <thead>
                            <tr>
                                <th width="120">模版ID</th>
                                <th>模版标题</th>
                                <th>发货省份</th>
                                <th>默认价格</th>
                                <th class="table-set" width="180">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($list as $v)
                                <tr>
                                    <td>{{ $v->carriageid }}</td>
                                    <td>{{ $v->title }}</td>
                                    <td>{{ $v->province }}</td>
                                    <td>{{ $v->price }}元</td>
                                    <td>
                                        <div class="am-btn-toolbar">
                                            <div class="am-btn-group am-btn-group-xs">
                                                <a type="button" class="am-btn am-btn-success am-btn-sm" @click="edit({{$v->carriageid}})"><span class="am-icon-pencil-square-o"></span>  编辑</a>
                                                <a type="button" class="am-btn am-btn-danger am-btn-sm" @click="shanchu({{$v->carriageid}},this)"><span class="am-icon-trash-o"></span>  删除</a>
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
            add: function (id) {
                layer_full("新增运费模版",'add/')
            },
            edit: function (id) {
                layer_full("编辑运费模版",'edit/?id='+id)
            },
            shanchu: function (id,event) {
                layer.msg('确定要删除吗？', {
                    time:0,
                    btn: ['确定', '取消'],
                    yes: function(){
                        $.get('del/?id='+id,function (data) {
                            if(data['status'] == "200"){
                                layer.msg('删除成功！',function () {
                                    window.location.reload();
                                });
                            }else{
                                layer.msg(data['msg']);
                            }
                        })
                    }
                });
            },
        }
    })

</script>
</body>
</html>