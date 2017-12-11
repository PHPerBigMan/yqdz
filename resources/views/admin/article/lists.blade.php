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
                        <li class="am-active">文章列表</li>
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
            <div class="am-g am-animation-slide-right">
                <div class="am-u-sm-12">
                    <form class="am-form">
                        <table class="am-table am-table-striped am-table-hover table-main">
                            <thead>
                            <tr>
                                <th width="120">ID</th>
                                <th>文章标题</th>
                                <th class="table-set" width="100">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $v)
                                <tr>
                                    <td>{{ $v->articleid }}</td>
                                    <td>{{ $v->title }}</td>
                                    <td>
                                        <div class="am-btn-toolbar">
                                            <div class="am-btn-group am-btn-group-xs">
                                                <a type="button" class="am-btn am-btn-secondary am-btn-sm" v-on:click.stop="edit({{ $v->articleid }})"><span class="am-icon-pencil-square-o"></span>  编辑</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
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
            edit: function (id) {
                layer_full("编辑文章信息",'edit/?id='+id)
            },
            add:function(){
                layer_full("编辑文章信息",'add')
            }
        }
    })

</script>
</body>
</html>