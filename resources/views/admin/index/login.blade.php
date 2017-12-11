<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>后台管理系统登录</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="/assets/css/amazeui.min.css"/>
    <link rel="stylesheet" href="/assets/css/login.css"/>
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="/layer/layer.js"></script>
</head>
<body>
<h1>后台管理系统</h1>
<div class="login am-animation-slide-top">
    <form class="am-form" action="/admin/index/postlogin" method="post" id="form1">
        {{ csrf_field() }}
        <div class="am-form-group ">
            <div class="am-input-group">
                <span class="am-input-group-label"><i class="am-icon-user am-icon-fw"></i></span>
                <input type="text" class="am-form-field" placeholder="帐号" name="account">
            </div>
        </div>
        <div class="am-form-group">
            <div class="am-input-group">
                <span class="am-input-group-label"><i class="am-icon-lock am-icon-fw"></i></span>
                <input type="password" class="am-form-field" placeholder="密码" name="password">
            </div>
        </div>
        <input type="button" class="am-btn am-btn-primary am-btn-block" value="登录">
    </form>
</div>
<div class="copyright">
    © 2016 杭州百米贩网络科技有限公司 版权所有, Inc.
</div>
<!--[if lt IE 9]>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="/assets/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->
<script src="/assets/js/amazeui.min.js"></script>
<script src="/assets/js/hw-layer.js"></script>
<script src="/assets/js/app.js"></script>
<script>
    $(".am-btn-primary").click(function () {
//        console.log($("#from1").serialize())
        $.post("/admin/index/postlogin",$("#form1").serialize(),function (data) {
            console.log(data)
            if(data['status'] == "200"){
                layer.msg(data['text'],{icon:1,time:1000},function () {
                    window.location.href=data['url'];
                });
            }else{
                layer.msg(data['text'],{icon:2,time:1000});
            }
        })
    })
</script>
</body>
</html>