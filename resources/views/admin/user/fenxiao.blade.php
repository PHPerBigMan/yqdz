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
<div class="hw-content" style="padding: 20px;">
    <form class="am-form am-form-horizontal" action="" method="post" id="from1">
        <div class="am-g am-margin-top">
            <div class="am-panel am-panel-default">
                <div class="am-panel-hd">上级信息</div>
                <div class="am-panel-bd">
                    <table class="am-table am-table-striped am-table-hover">
                        <thead>
                        <tr>
                            <th>一级推荐人</th>
                            <th>二级推荐人</th>
                            <th>三级推荐人</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><img src="{{$first->img or ''}}" width="40" style="margin-right: 10px;">{{$first->nickname or '空'}}</td>
                            <td><img src="{{$second->img or ''}}" width="40" style="margin-right: 10px;" >{{$second->nickname or '空'}}</td>
                            <td><img src="{{$three->img or ''}}" width="40" style="margin-right: 10px;">{{$three->nickname or '空'}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="am-panel am-panel-default">
                <div class="am-panel-hd">下级信息</div>
                <div class="am-panel-bd">
                    <div class="am-g">
                        <div class="am-u-sm-4 am-u-md-4 am-u-lg-4">
                            <table class="am-table am-table-striped am-table-hover">
                                <thead>
                                <tr>
                                    <th>一级推荐人</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($firstList as $val)
                                    <tr>
                                        <td><img src="{{$val->img or ''}}" width="40" style="margin-right: 10px;">{{$val->nickname or '空'}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="am-u-sm-4 am-u-md-4 am-u-lg-4">
                            <table class="am-table am-table-striped am-table-hover">
                                <thead>
                                <tr>
                                    <th>二级推荐人</th>
                                </tr>
                                </thead>
                                <tbody>
                                     @foreach($secondList as $val)
                                    <tr>
                                        <td><img src="{{$val->img or ''}}" width="40" style="margin-right: 10px;">{{$val->nickname or '空'}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="am-u-sm-4 am-u-md-4 am-u-lg-4">
                            <table class="am-table am-table-striped am-table-hover">
                                <thead>
                                <tr>
                                    <th>三级推荐人</th>
                                </tr>
                                </thead>
                                <tbody>
                                
                                     @foreach($threeList as $val)
                                    <tr>
                                        <td><img src="{{$val->img or ''}}" width="40" style="margin-right: 10px;">{{$val->nickname or '空'}}</td>
                                    </tr>
                                    @endforeach
                                 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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
