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
    <form class="am-form am-form-horizontal" action="" method="post" id="from1">
        {{ csrf_field() }}
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">管理员类型：</div>
            <div class="am-u-sm-9 am-u-md-3 am-u-end col-end">
                <select data-am-selected="{btnSize: 'sm'}" style="display: none;" name="group">
                    @foreach($list as $v)
                            @if(count($data) == 0)
                                <option value="{{$v->groupid}}">{{$v->gname}}</option>
                                @else
                            <option value="{{$v->groupid}}" @if($v->groupid == $data->groupid) selected @endif>{{$v->gname}}</option>
                            @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">
                管理员昵称
            </div>
            <div class="am-u-sm-8 am-u-md-4">
                <input type="text" class="am-input-sm" name="name" placeholder="请输入管理员昵称" value="{{$data->name or ''}}">
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">登录账号</div>
            <div class="am-u-sm-8 am-u-md-4 am-u-end col-end">
                <input type="hidden" name="id" value="{{$data->id or ''}}">
                <input type="text" class="am-input-sm" name="account" placeholder="请输入登录账号" value="{{ $data->account or ''}}">
            </div>
        </div>

        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">登录密码</div>
            <div class="am-u-sm-8 am-u-md-4 am-u-end col-end">
                <input type="text" class="am-input-sm" name="password" placeholder="请输入登录密码" value="{{ ''}}">
            </div>
        </div>

        <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-8 am-u-sm-offset-3">
                @if (count($data) == 0)
                    <button type="button" class="am-btn am-btn-primary">添加管理员</button>
                @else
                    <button type="button" class="am-btn am-btn-primary">修改管理员</button>
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
<script>
    $(".am-btn-primary").click(function () {
        var name=$("input[name='name']").val();
        var account=$("input[name='account']").val();
        var password=$("input[name='password']").val();
        if (name==''){
            layer.msg("管理员昵称不能为空!",{icon:2});
            return false;
        }
        if (account==''){
            layer.msg("账号不能为空!",{icon:2});
            return false;
        }
        if (password==''){
            layer.msg("密码不能为空!",{icon:2});
            return false;
        }

        $.post("/admin/admin/handle",$("#from1").serialize(),function (data) {
            console.log(data);
            if(data['status'] == "200"){
                layer.msg(data['text'],{icon:1,time:1000},function () {
                    var index = parent.layer.getFrameIndex(window.name);
                    parent.location.reload()
                    parent.layer.close(index);
                })
            }else{
                layer.msg(data['text'],{icon:2,time:1000});
            }
        })
    })
</script>
</body>
</html>
