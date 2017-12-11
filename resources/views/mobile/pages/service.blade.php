<!doctype html>
<html lang="cn_ZH">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>联系客服</title>
    <link href="/frontend/layui/css/layui.mobile.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>
    <script src="/frontend/layui/layui.js"></script>
    <style>
        .layim-chat-title {display: none}
        .layim-chat-friend .layim-chat-main {top: 0}
    </style>
</head>
<script type='text/javascript'>
    (function(m, ei, q, i, a, j, s) {
        m[i] = m[i] || function() {
            (m[i].a = m[i].a || []).push(arguments)
        };
        j = ei.createElement(q),
            s = ei.getElementsByTagName(q)[0];
        j.async = true;
        j.charset = 'UTF-8';
        j.src = 'https://static.meiqia.com/dist/meiqia.js?_=t';
        s.parentNode.insertBefore(j, s);
    })(window, document, 'script', '_MEIQIA');
    _MEIQIA('entId', 77521);
</script>
<body>
<div style="font-size: 25px;padding-top: 63%;padding-left: 6%;">请点击左下角的蓝色按钮</div>
<script>
    {{--(function () {--}}
        {{--var userData = {--}}
            {{--username: "{{$user['nickname']}}", //我的昵称--}}
                {{--id: "{{$user['id']}}", //我的ID--}}
            {{--avatar: "{{$user['img']}}", //我的头像--}}
        {{--};--}}


        {{--layui.use('mobile', function () {--}}
            {{--var mobile = layui.mobile--}}
                {{--, layim = mobile.layim--}}
                {{--, layer = mobile.layer;--}}

            {{--layim.config({--}}
                {{--init: {--}}
                    {{--mine: userData--}}
                {{--}--}}
            {{--});--}}

            {{--var socket = new WebSocket("ws://121.42.235.170:7272");--}}

            {{--socket.onopen = function () {--}}
                {{--// 登录--}}
                {{--var login_data = JSON.stringify($.extend({type: 'login'}, userData));--}}
                {{--socket.send(login_data);--}}
                {{--console.log("websocket握手成功!");--}}
            {{--};--}}

            {{--//监听收到的消息--}}
            {{--socket.onmessage = function (res) {--}}
                {{--//console.log(res.data);--}}
                {{--var data = eval("(" + res.data + ")");--}}
                {{--switch (data['message_type']) {--}}
                    {{--// 服务端ping客户端--}}
                    {{--case 'ping':--}}
                        {{--socket.send('{"type":"ping"}');--}}
                        {{--break;--}}
                    {{--// 登录 更新用户列表--}}
                    {{--case 'login':--}}
                        {{--console.log(data['username'] + "登录成功");--}}
                        {{--//layim.getMessage(res.data); //res.data即你发送消息传递的数据（阅读：监听发送的消息）--}}
                        {{--break;--}}
                    {{--case 'chatMessage':--}}
                        {{--//console.log(data.data);--}}
                        {{--layim.getMessage(data.data);--}}
                        {{--break;--}}
                    {{--// 离线消息推送--}}
                    {{--case 'logMessage':--}}
                        {{--setTimeout(function () {--}}
                            {{--layim.getMessage(data.data)--}}
                        {{--}, 1000);--}}
                        {{--break;--}}
                    {{--// 用户退出 更新用户列表--}}
                    {{--case 'logout':--}}
                        {{--break;--}}
                    {{--//聊天还有不在线--}}
                    {{--case 'ctUserOutline':--}}
                        {{--console.log('11111');--}}
                        {{--layer.msg('好友不在线', {'time' : 1000});--}}
                        {{--break;--}}

                {{--}--}}
            {{--};--}}

            {{--//创建一个会话--}}
            {{--layim.chat({--}}
                {{--id: 520318,--}}
                {{--name: "客服",--}}
                {{--type: 'friend', //friend、group等字符，如果是group，则创建的是群聊--}}
                {{--avatar: "http://hcfl.sunday.so/Public/resource/images/timg.jpg"--}}
            {{--});--}}


            {{--layim.on('sendMessage', function (res) {--}}
                {{--var mine = JSON.stringify(res.mine);--}}
                {{--console.log(mine);--}}
                {{--var to = JSON.stringify(res.to);--}}
                {{--var login_data = '{"type":"chatMessage","data":{"mine":' + mine + ', "to":' + to + '}}';--}}
                {{--socket.send(login_data);--}}
            {{--});--}}
        {{--});--}}
    {{--})();--}}
</script>
</body>
</html>