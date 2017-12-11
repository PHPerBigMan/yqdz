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
        html{overflow-y: scroll}
        body{overflow-y: scroll;font-size: 13px;}
        .am-icon-times-circle{font-size: 18px;color: red}
        .am-icon-check-circle{font-size: 18px;color: green}
    </style>
</head>
<body>
<div class="hw-content" style="padding: 20px;padding-bottom: 80px;width: 800px">
    {{--<div class="am-alert am-alert-success" data-am-alert style="background: #f9edbe;border: 1px solid #f0c36d;color: #333;font-size: 12px">--}}
        {{--<button type="button" class="am-close">&times;</button>--}}
        {{--<p>注意：菜单最多只能有三层，不能多于三级</p>--}}
    {{--</div>--}}
    <a type="button" class="am-btn am-btn-warning am-btn-sm" style="margin-bottom: 10px;" @click="addone"><i class="am-icon-plus"></i>添加分类</a>
    <form action="/admin/classify/handle" method="post" class="am-form" id="form-admin-add" enctype="multipart/form-data">
        <table class="am-table am-table-bordered am-table-radius am-table-striped">
            <thead>
            <tr>
                <th width="90">ID</th>
                <th>分类名称</th>
                <th>分类类型</th>
                <th width="180">操作</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in data">
                <td>@{{ item.classifyid }}</td>
                <td>
                    @{{ item.name }}
                </td>
                <td>
                    @{{ item.type }}
                </td>
                <td>
                    <a type="button" class="am-btn am-btn-success am-btn-sm" @click="edit(item.classifyid,this)"><span class="am-icon-pencil-square-o"></span>  编辑</a>
                    <a type="button" class="am-btn am-btn-danger am-btn-sm" @click="del(item.classifyid,this)"><span class="am-icon-trash-o"></span>  删除</a>
                </td>
            </tr>
            </tbody>
        </table>
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
<script type="text/javascript">
    var vm = new Vue({
        el: 'body',
        data: {
            data:{!! $data !!},
    },methods: {
        addone: function (event) {
            layer_show('添加分类','add',500,250)
        },
        edit:function(id,event){
            layer_show('修改分类','edit/?id='+id,500,250)
        },
        del:function (id,event) {
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
        }
    }
    })
</script>
</body>
</html>
