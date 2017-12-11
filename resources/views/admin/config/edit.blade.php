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
    <form action="/admin/config/handle" method="post" class="am-form" id="form-admin-add" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">微信公众平台appid</div>
            <div class="am-u-sm-8 am-u-md-9">
                <input type="text" class="am-input-sm" name="appid" placeholder="请输入微信公众平台appid" value="{{$config['0']['value']}}" readonly>
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">微信公众平台secret</div>
            <div class="am-u-sm-8 am-u-md-9">
                <input type="password" class="am-input-sm" name="secret" placeholder="请输入微信公众平台secret" value="{{$config['1']['value']}}" readonly>
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">微信公众平台token</div>
            <div class="am-u-sm-8 am-u-md-9">
                <input type="text" class="am-input-sm" name="token" placeholder="请输入微信公众平台token" value="{{$config['2']['value']}}" readonly>
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">微信公众平台欢迎词</div>
            <div class="am-u-sm-8 am-u-md-9">
                <input type="text" class="am-input-sm" name="keyword" placeholder="请输入微信公众平台欢迎词" value="{{$config['3']['value']}}">
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">微信支付key</div>
            <div class="am-u-sm-8 am-u-md-9">
                <input type="password" class="am-input-sm" name="key" placeholder="请输入微信支付key" value="{{$config['4']['value']}}" readonly>
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">微信支付商户号</div>
            <div class="am-u-sm-8 am-u-md-9">
                <input type="password" class="am-input-sm" name="merchant_id" placeholder="请输入微信支付商户号" value="{{$config['5']['value']}}" readonly>
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">店铺名称</div>
            <div class="am-u-sm-8 am-u-md-9">
                <input type="text" class="am-input-sm" name="site_name" placeholder="请输入店铺名称" value="{{$config['8']['value']}}">
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>
        {{--<div class="am-g am-margin-top">--}}
            {{--<div class="am-u-sm-3 am-u-md-2 am-text-right">店铺Logo</div>--}}
            {{--<div class="am-u-sm-8 am-u-md-9">--}}
                {{--<img src="{{$config['9']['value']}}" width="80">--}}
                {{--<input type="file" class="am-input-sm" multiple name="site_logo[]">--}}
            {{--</div>--}}
            {{--<div class="am-hide-sm-only am-u-md-6"></div>--}}
        {{--</div>--}}
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">店铺公告</div>
            <div class="am-u-sm-8 am-u-md-9">
                <input type="text" class="am-input-sm" name="notice" placeholder="请输入店铺公告" value="{{$config['10']['value']}}">
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">客服电话</div>
            <div class="am-u-sm-8 am-u-md-9">
                <input type="text" class="am-input-sm" name="phone" placeholder="请输入客服电话" value="{{$config['11']['value']}}">
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>


        <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-8 am-u-sm-offset-2">
                <button type="submit" class="am-btn am-btn-primary">修改配置</button>
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
