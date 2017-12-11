<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script type="text/javascript" src="http://lib.h-ui.net/jquery/1.9.1/jquery.min.js"></script>
    <link rel="stylesheet" href="/assets/css/amazeui.min.css"/>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <script src="http://cdn.bootcss.com/vue/1.0.26/vue.min.js"></script>
    <style>
        body{overflow-y: auto;font-size: 13px;}
    </style>
</head>
<body>
<div class="hw-content" style="padding: 20px;">
    <form action="/admin/dclassify/handle" method="post" class="am-form" id="form-admin-add" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">分类名称</div>
            <div class="am-u-sm-8 am-u-md-8">
                <input type="hidden" name="classifyid" value="{{$data->classifyid or ''}}">
                <input type="text" class="am-input-sm" name="name" placeholder="请输入分类名称" value="{{$data->name or ''}}">
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>

        <div class="am-g am-margin-top-sm" style="margin-top:25px">
            <div class="am-u-sm-8 am-u-sm-offset-3">
                @if (isset($data))
                    <button type="submit" class="am-btn am-btn-primary">修改分类</button>
                @else
                    <button type="submit" class="am-btn am-btn-primary">添加分类</button>
                @endif
            </div>
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
<script type="text/javascript" src="http://lib.h-ui.net/jquery.validation/1.14.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="http://lib.h-ui.net/jquery.validation/1.14.0/validate-methods.js"></script>
<script src="http://cdn.bootcss.com/jquery.form/3.51/jquery.form.min.js"></script>
<script>
    var vm = new Vue({
            el: 'body',
            data: {
            }
        })

    $(function(){
        $("#form-admin-add").validate({
            onkeyup:false,
            focusCleanup:true,
            success:"valid",
            submitHandler:function(form){
                var index2 = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
                $(form).ajaxSubmit(function (data) {
                    layer.close(index2);
                    if(data['status'] == "200"){
                        layer.msg(data['msg'],function () {
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.location.reload()
                            parent.layer.close(index);
                        });
                    }else{
                        layer.msg(data['msg']);
                    }
                });
            }
        });
    });
</script>
</body>
</html>
