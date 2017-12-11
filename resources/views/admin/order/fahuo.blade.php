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
    <form action="/admin/order/handle" method="post" class="am-form" id="form-admin-add" enctype="multipart/form-data" >
        {{ csrf_field() }}
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">收货人：</div>
            <div class="am-u-sm-8 am-u-md-10">
                <input type="hidden" name="extendid" value="{{$data->extend->extendid or 0}}">
                <input type="hidden" name="orderid" value="{{$data->orderid or ''}}">
{{--                <input type="hidden" name="extendid" value="{{$data->extend->extendid}}"/>--}}
                <input type="hidden" name="addjson" value="{{json_encode($data->address or '')}}">
                {{$data->address->name or ''}}
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">联系电话：</div>
            <div class="am-u-sm-8 am-u-md-10">
                {{$data->address->phone or ''}}
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">详细地址：</div>
            <div class="am-u-sm-8 am-u-md-10">
                {{$data->address->province  or ''}}{{$data->address->address or ''}}
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>

        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">物流公司：</div>
            <div class="am-u-sm-8 am-u-md-10">
                <select name="express" class="form-control valid" aria-invalid="false" id="gongsi">
                    @if($data->extend)
                    <option @if($data->extend->express=='申通快递') selected @endif value="申通快递" >申通快递</option>
                    <option @if($data->extend->express=='EMS') selected @endif value="EMS" >EMS</option>
                    <option @if($data->extend->express=='顺丰快递') selected @endif value="顺丰快递" >顺丰快递</option>
                    <option @if($data->extend->express=='圆通快递') selected @endif value="圆通快递" >圆通快递</option>
                    <option @if($data->extend->express=='中通快递') selected @endif value="中通快递" >中通快递</option>
                    <option @if($data->extend->express=='天天快递') selected @endif value="天天快递" >天天快递</option>
                    <option @if($data->extend->express=='韵达快递') selected @endif value="韵达快递" >韵达快递</option>
                    <option @if($data->extend->express=='快捷快递') selected @endif value="快捷快递" >快捷快递</option>
                    <option @if($data->extend->express=='百世快递') selected @endif value="百世快递" >百世快递</option>
                    @else
                        <option value="0">请选择物流公司</option>
                        @foreach($express as $v)
                            <option value="{{ $v->title }}">{{ $v->title }}</option>
                            @endforeach
                    @endif
                </select>
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>

        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">物流单号：</div>
            <div class="am-u-sm-8 am-u-md-10">
                <input type="text" class="am-input-sm" name="couriernumber" placeholder="请输入物流单号" value="{{$data->extend->couriernumber or ""}}">
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>

        <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-8 am-u-sm-offset-3">
                <button type="submit" class="am-btn am-btn-primary">确认发货</button>
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
<script type="text/javascript">
    $(function(){
        $("#form-admin-add").validate({
            onkeyup:false,
            focusCleanup:true,
            success:"valid",
            submitHandler:function(form){
                $(form).ajaxSubmit(function (data) {
                    if($("#gongsi").val() == 0){
                        layer.msg("请先选择物流公司！",function () {
                            return
                        });
                        return 0;
                    }
                    var index2 = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
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
