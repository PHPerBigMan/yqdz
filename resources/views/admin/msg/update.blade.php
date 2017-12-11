<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="//cdn.bootcss.com/jquery/2.0.2/jquery.min.js"></script>
    <link rel="stylesheet" href="/assets/css/amazeui.min.css"/>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <style>
        body{overflow-y: auto;font-size: 13px;}
    </style>
</head>
<body>
<div class="hw-content" style="padding: 20px;">
    <form action="/admin/msg/handle" method="post" class="am-form" id="form-admin-add" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">消息标题</div>
            <div class="am-u-sm-8 am-u-md-10">
                <input type="hidden" name="msg_id" value="{{$data->msg_id or ''}}">
                <input type="text" class="am-input-sm" required name="title" placeholder="" value="{{$data->title or ''}}">
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">消息类型</div>
            <div class="am-u-sm-8 am-u-md-10">
                <input type="radio" name="type" @if(@$data->type==1) checked @endif value="1">退货通知&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="type" @if(@$data->type==2) checked @endif value="2">发货通知&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="type" @if(@$data->type==3) checked @endif value="3">定制信息被采纳通知&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">结果通知</div>
            <div class="am-u-sm-8 am-u-md-10">
                <input type="radio" name="result" @if(@$data->result==1) checked @endif value="1">成功&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="result" @if(@$data->result==2) checked @endif value="2">失败&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">内容</div>
            <div class="am-u-sm-8 am-u-md-10">
                <textarea class="textarea" id="content" name="content" style="width:100%;height:200px;">{{$data->content or ''}}</textarea>
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>
        <div class="am-g am-margin-top-sm">
        <div class="am-u-sm-8 am-u-sm-offset-2">
        <button type="submit" class="am-btn am-btn-primary">{{$msg}}</button>
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
<script type="text/javascript" src="/ueditor/1.4.3/ueditor.config.js"></script>
<script type="text/javascript" src="/ueditor/1.4.3/ueditor.all.min.js"> </script>
<script type="text/javascript" src="/ueditor/1.4.3/ueditor.parse.min.js"> </script>
<script type="text/javascript" src="/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript" src="http://lib.h-ui.net/jquery.validation/1.14.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="http://lib.h-ui.net/jquery.validation/1.14.0/validate-methods.js"></script>
<script src="//cdn.bootcss.com/jquery.form/3.51/jquery.form.min.js"></script>
<script type="text/javascript">
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
//                                var index = parent.layer.getFrameIndex(window.name);
                                window.location.href="/admin/msg/lists";
//                                parent.location.reload()
//                                parent.layer.close(index);
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
