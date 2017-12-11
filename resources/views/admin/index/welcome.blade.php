<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="/assets/css/amazeui.min.css"/>
    <link rel="stylesheet" href="/assets/css/admin.css"/>
    <script src="//cdn.bootcss.com/jquery/2.0.2/jquery.min.js"></script>
    <script src="/layer/layer.js"></script>
    <style>
        body{
            overflow-y: scroll;
            background: rgb(247,247,247);
        }
        ul{
            list-style: none;
            padding: 0px;
            margin: 0px;
        }
        .box:after,.box2:after{
            clear: both;
            display: table;
            content: ''
        }
        .box ul li,.box2 ul li{
            width: 22%;
            height: 180px;
            float: left;
            margin-left: 2%;
            background: #fff;
        }
        .box ul li{
            box-shadow: 0px 3px 6px 0px rgba(213,213,213,0.50);
        }
        .box2 ul li{
            border: 1px solid #fff;
            position: relative;
            color: #fff;
        }
        .box2 ul li:nth-child(1){
            background: #00AA7C;
        }
        .box2 ul li:nth-child(2){
            background: #5FA4CF;
        }
        .box2 ul li:nth-child(3){
            background: #D76939;
        }
        .box2 ul li:nth-child(4){
            background: #EA9736;
        }

        .box ul li p,.box2 ul li p{
            text-align: center;
            margin: 0px;
        }
        .box ul li p:nth-child(1){
            height: 140px;
            line-height: 140px;
        }
        .box ul li p:nth-child(2){
            height: 40px;
            line-height: 40px;
            border-top: 1px solid #E0E0E0;
        }

        .box2 ul li p:nth-child(1){
            height: 40px;
            line-height: 40px;
            color: #fff;
            border-bottom: 1px solid #fff;
        }
        .box2 ul li p:nth-child(2){
            height: 80px;
            line-height: 80px;
            font-size: 32px;
        }
        .box2 ul li p:nth-child(2) i{
            font-size: 33px;
        }
        .box2 ul li p:nth-child(3){
            height: 40px;
            line-height: 40px;
        }
        .hwbag{
            height: 30px;width: 100%;position:absolute;left: 0px;bottom: 0px;background:rgba(0,0,0,0.2);text-align: center;color: #fff;font-size: 13px;line-height: 30px;
        }
    </style>
</head>
<body style="padding: 20px;padding-bottom: 90px">
<h1>数据总览</h1>
<div class="box2">
    <ul>
        <li>
            <p>总订单数</p>
            <p><i class="iconfont icon-dingdan"></i>{{$all[0]}}</p>
            <p>单位（单）</p>
        </li>
        <li>
            <p>总销售额</p>
            <p><i class="iconfont icon-daifukuan"></i>{{$all[1]}}</p>
            <p>单位（元）</p>
        </li>
        <li>
            <p>累计浏览量</p>
            <p><i class="iconfont icon-liulanliang"></i>{{$all[2]}}</p>
            <p>单位（次）</p>
        </li>
        <li>
            <p>累计访客数</p>
            <p><i class="iconfont icon-ren"></i>{{$all[3]}}</p>
            <p>单位（次）</p>
        </li>
    </ul>
</div>
<h1>昨日数据</h1>
<div class="box">
    <ul>
        <li>
            <p>昨日订单数</p>
            <p>{{$yesterday[0] or 0}}</p>
        </li>
        <li>
            <p>昨日销售额</p>
            <p>{{$yesterday[1] or 0}}</p>
        </li>
        <li>
            <p>昨日浏览量</p>
            <p>{{$yesterday[2] or 0}}</p>
        </li>
        <li>
            <p>昨日访客数</p>
            <p>{{$yesterday[3] or 0}}</p>
        </li>
    </ul>
</div>
<h1>今日数据</h1>
<div class="box">
    <ul>
        <li>
            <p>今日订单数</p>
            <p>{{$today[0] or 0}}</p>
        </li>
        <li>
            <p>今日销售额</p>
            <p>{{$today[1] or 0}}</p>
        </li>
        <li>
            <p>今日浏览量</p>
            <p>{{$today[2] or 0}}</p>
        </li>
        <li>
            <p>今日访客数</p>
            <p>{{$today[3] or 0}}</p>
        </li>
    </ul>
</div>
</body>
</html>