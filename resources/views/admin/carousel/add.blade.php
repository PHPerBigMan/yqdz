<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script type="text/javascript" src="http://lib.h-ui.net/jquery/1.9.1/jquery.min.js"></script>
    <link rel="stylesheet" href="/assets/css/amazeui.min.css"/>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <style>
        body{overflow-y: auto;font-size: 13px;}
    </style>
</head>
<body>
<div class="hw-content" style="padding: 20px;">
    <form action="/admin/carousel/handle" method="post" class="am-form" id="form-admin-add" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">轮播位置：</div>
            <div class="am-u-sm-9 am-u-md-3 am-u-end col-end">
                <select name="position" style="width: 240px">
                    @foreach($extend as $v)
                        @if(isset($data))
                            <option value="{{$v->extendid}}" @if($v->extendid == $data->position) selected @endif>{{$v->name}}</option>
                        @else
                            <option value="{{$v->extendid}}">{{$v->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">轮播标题</div>
            <div class="am-u-sm-8 am-u-md-8">
                <input type="hidden" name="carouselid" value="{{$data->carouselid or ''}}">
                <input type="text" class="am-input-sm" name="title" placeholder="请输入轮播标题" value="{{$data->title or ''}}">
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>

        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">链接地址</div>
            <div class="am-u-sm-8 am-u-md-8 am-u-end col-end">
                <input type="text" class="am-input-sm" name="url" placeholder="请输入链接地址" value="{{$data->url or ''}}">
            </div>
        </div>

        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">轮播图</div>
            <div class="am-u-sm-8 am-u-md-8 am-u-end col-end">
                <div class="am-form-group am-form-file">
                    <button type="button" class="am-btn am-btn-danger am-btn-sm">
                        <i class="am-icon-cloud-upload"></i> 选择要上传的文件</button>
                    <span>建议上传图片大小 :375px 200px</span>
                    <input id="doc-form-file" type="file" multiple name="suolvetu[]">
                </div>


                <div id="file-list"></div>
                {{--<input type="hidden" value="{{$data->carouselimg or ''}}" name="suo2">--}}
                {{--@if(isset($data->carouselimg))--}}
                    {{--<img src="{{$data->carouselimg or ''}}" width="80">--}}
                {{--@endif--}}
                {{--<script>--}}
                    {{--$(function() {--}}
                        {{--$('#doc-form-file').on('change', function() {--}}
                            {{--var fileNames = '';--}}
                            {{--$.each(this.files, function() {--}}
                                {{--fileNames += '<span class="am-badge">' + this.name + '</span> ';--}}
                            {{--});--}}
                            {{--$('#file-list').html(fileNames);--}}
                        {{--});--}}
                    {{--});--}}
                {{--</script>--}}
            </div>
        </div>


        <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-8 am-u-sm-offset-3">
                @if(isset($data))
                    <button type="submit" class="am-btn am-btn-primary">修改轮播</button>
                @else
                    <button type="submit" class="am-btn am-btn-primary">添加轮播</button>
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
