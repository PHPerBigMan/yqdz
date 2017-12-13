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
    <form action="/admin/design/handle" method="post" class="am-form" id="form-admin-add" enctype="multipart/form-data">
        {{ csrf_field() }}
            <input type="hidden" name="designid" value="{{$data->designid or ''}}">
            <div class="am-g am-margin-top">
                <div class="am-u-sm-3 am-u-md-2 am-text-right">标题</div>
                <div class="am-u-sm-8 am-u-md-10">
                    <input type="text" class="am-input-sm" name="title" placeholder="" value="{{$data->title or ''}}">
                </div>
                <div class="am-hide-sm-only am-u-md-6"></div>
            </div>
            <div class="am-g am-margin-top">
                <div class="am-u-sm-3 am-u-md-2 am-text-right">手机号</div>
                <div class="am-u-sm-8 am-u-md-10">
                    {{--<input type="hidden" name="articleid" value="{{$data->articleid}}">--}}
                    <input type="text" class="am-input-sm" name="phone" placeholder="" value="{{$data->phone or ''}}">
                </div>
                <div class="am-hide-sm-only am-u-md-6"></div>
            </div>
            <div class="am-g am-margin-top">
                <div class="am-u-sm-3 am-u-md-2 am-text-right">定制类型</div>
                <div class="am-u-sm-8 am-u-md-10">
                    <select name="is_qiye" id="">
                        <option value="1" <?php if($data->is_qiye == 1) echo "selected";?>>私人定制</option>
                        <option value="2" <?php if($data->is_qiye == 2) echo "selected";?>>企业定制</option>
                    </select>
                </div>
                <div class="am-hide-sm-only am-u-md-6"></div>
            </div>
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">定制分类</div>
            <div class="am-u-sm-8 am-u-md-10">
                <select name="cat_id" id="">
                    @foreach($cat as $v)
                        <option value="{{ $v->classifyid }}" <?php if($data->cat_id == $v->classifyid) echo "selected";?>>{{ $v->name }}</option>
                        @endforeach
                </select>
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">内容</div>
            <div style="margin: 50px 260px">
                <div class="am-u-sm-8 am-u-md-10">
                    @if(isset($data->backcontent) && !empty($data->backcontent))
                        <script id="editor" type="text/plain"  name="backcontent">

                            {!! $data->backcontent !!}

                        </script>
                    @else
                        <script id="editor" type="text/plain"  name="backcontent">
                            @if(!empty($data->img))
                                @foreach($data->img as $v)
                                    <img src="{{ $v }}" alt="" class="design-img">
                                @endforeach
                              @endif
                            @if(isset($data->content))
                                {!! $data->content !!}
                                @endif

                        </script>
                    @endif
                </div>
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
    var ue = UE.getEditor('editor',{
        initialFrameWidth:1020,
        initialFrameHeight:320
    });
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
