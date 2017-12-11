<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="//cdn.bootcss.com/jquery/2.0.2/jquery.min.js"></script>
    <link rel="stylesheet" href="/assets/css/amazeui.min.css"/>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <script src="http://cdn.bootcss.com/vue/1.0.26/vue.min.js"></script>
    <style>
        body{font-size: 13px;overflow-y: scroll}
        .am-panel{border: none;box-shadow: none;-webkit-box-shadow: none;margin-bottom:5px;}
        .am-table>tbody>tr>td, .am-table>tbody>tr>th, .am-table>tfoot>tr>td, .am-table>tfoot>tr>th, .am-table>thead>tr>td, .am-table>thead>tr>th{border: none}
        .am-table>caption+thead>tr:first-child>td, .am-table>caption+thead>tr:first-child>th, .am-table>colgroup+thead>tr:first-child>td, .am-table>colgroup+thead>tr:first-child>th, .am-table>thead:first-child>tr:first-child>td, .am-table>thead:first-child>tr:first-child>th{font-weight: normal}
    </style>
</head>
<body>
<div class="am-g am-animation-slide-right">
    <div class="am-u-sm-12">
        <form class="am-form">
            <table class="am-table am-table-striped am-table-hover table-main" border="1">
                <thead>
                <tr>
                    <th>微信昵称 :{{$user['nickname']}}</th>
                    <th>注册时间 :{{$user['created_at']}}</th>
                    <th>是否购买 :@if($user['isbuy']==1)是@else否@endif</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>收货人姓名</td>
                        <td>手机号</td>
                        <td>地址</td>
                        <td>默认地址</td>
                    </tr>
                    @foreach($addressList as $val)
                    <tr>
                        <td>{{$val['name']}}</td>
                        <td>{{$val['phone']}}</td>
                        <td>{{$val['province']}}-{{$val['city']}}-{{$val['district']}}-{{$val['address']}}</td>
                        <td>@if($val['is_default']==1)是@else否@endif</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{--<div class="am-cf" style="padding:0px 30px 30px 30px;margin-bottom: 30px;">--}}
                {{--<div class="am-fl hw-jilu">共 1111 条记录</div>--}}
                {{--<div class="am-fr">--}}

                {{--</div>--}}
            {{--</div>--}}
        </form>
    </div>

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
    var vm = new Vue({
            el: 'body',
            data: {
             },
            methods: {

            }
    })

</script>
</body>
</html>
