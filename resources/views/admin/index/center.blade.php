<!doctype html>
<html class="no-js fixed-layout">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>后台管理系统</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/assets/css/amazeui.min.css"/>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body style="overflow: auto;overflow-x: hidden">
<!--[if lte IE 9]>
<p class="browsehappy">你正在使用<strong>过时</strong>的浏览器，本系统 暂不支持。 请 <a href="http://browsehappy.com/" target="_blank">升级浏览器</a>
    以获得更好的体验！</p>
<![endif]-->

<header class="am-topbar am-topbar-inverse admin-header">
    <div class="am-topbar-brand">
        <a href="center"><small>一起定制 后台管理系统</small></a>
    </div>

    <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only" data-am-collapse="{target: '#topbar-collapse'}"><span class="am-sr-only">导航切换</span> <span class="am-icon-bars"></span></button>

    <div class="am-collapse am-topbar-collapse" id="topbar-collapse">

        <ul class="am-nav am-nav-pills am-topbar-nav am-topbar-right admin-header-list">
            <li class="am-dropdown" data-am-dropdown>
                <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
                    <span class="am-icon-users"></span> 管理员 <span class="am-icon-caret-down"></span>
                </a>
                <ul class="am-dropdown-content">
                    <li><a href="{{ url('admin/index/logout') }}"><span class="am-icon-power-off"></span> 退出</a></li>
                </ul>
            </li>
            <li class="am-hide-sm-only"><a href="javascript:;" id="admin-fullscreen"><span class="am-icon-arrows-alt"></span> <span class="admin-fullText">开启全屏</span></a></li>
        </ul>
    </div>
</header>

<div class="am-cf admin-main">
    <!-- sidebar start -->
    <style>
        .admin-parent li span{margin-right: 10px;}
    </style>
    <div class="admin-sidebar am-offcanvas" id="admin-offcanvas">
        <div class="am-offcanvas-bar admin-offcanvas-bar">
            <ul class="am-list admin-sidebar-list">
                <li class="admin-parent">
                    <a class="am-cf" data-am-collapse="{target: '#collapse-admin'}"><span class="am-icon-user"></span> 管理员管理 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                    <ul class="am-list am-collapse admin-sidebar-sub" id="collapse-admin">
                        <li><a href="{{ url('admin/adminGroup/list') }}" target="myiframe"><span class="am-icon-angle-right"></span> 角色管理</a></li>
                        <li><a href="{{ url('admin/admin/list') }}" target="myiframe"><span class="am-icon-angle-right"></span> 帐号管理</a></li>
                    </ul>
                </li>
                <li class="admin-parent">
                    <a class="am-cf" data-am-collapse="{target: '#collapse-site'}"><span class="am-icon-internet-explorer"></span> 网站管理 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                    <ul class="am-list am-collapse admin-sidebar-sub" id="collapse-site">
                        <li><a href="{{ url('admin/article/lists') }}" target="myiframe"><span class="am-icon-angle-right"></span> 文章管理</a></li>
                        <li><a href="{{ url('admin/carousel/lists') }}" target="myiframe"><span class="am-icon-angle-right"></span> 广告管理</a></li>
                    </ul>
                </li>
                <li class="admin-parent">
                    <a class="am-cf" data-am-collapse="{target: '#collapse-user'}"><span class="am-icon-users"></span> 会员管理 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                    <ul class="am-list am-collapse admin-sidebar-sub" id="collapse-user">
                        <li><a href="{{ url('admin/user/lists') }}" target="myiframe"><span class="am-icon-angle-right"></span>会员管理</a></li>
                    </ul>
                </li>
                <li class="admin-parent">
                    <a class="am-cf" data-am-collapse="{target: '#collapse-order'}"><span class="am-icon-desktop"></span> 运营管理 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                    <ul class="am-list am-collapse admin-sidebar-sub" id="collapse-order">
                        <li><a href="{{ url('admin/carriage/lists') }}" target="myiframe"><span class="am-icon-angle-right"></span>运费模版</a></li>
                        <li><a href="{{ url('admin/carriage/express') }}" target="myiframe"><span class="am-icon-angle-right"></span>快递管理</a></li>
                    </ul>
                </li>
                <li class="admin-parent">
                    <a class="am-cf" data-am-collapse="{target: '#collapse-fanxian'}"><span class="am-icon-file-text-o"></span> 订单管理 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                    <ul class="am-list am-collapse admin-sidebar-sub" id="collapse-fanxian">
                        <li><a href="{{ url('admin/order/lists') }}" target="myiframe"><span class="am-icon-angle-right"></span>订单列表 </a></li>
                        {{--<li><a href="{{ url('admin/orderrefunds/lists') }}" target="myiframe"><span class="am-icon-angle-right"></span>退款管理</a></li>--}}
                        <li><a href="{{ url('admin/orderreturngoods/lists') }}" target="myiframe"><span class="am-icon-angle-right"></span>退货管理</a></li>
                    </ul>
                </li>
                <li class="admin-parent">
                    <a class="am-cf" data-am-collapse="{target: '#collapse-fenxiao'}"><span class="am-icon-sitemap"></span> 分销管理 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                    <ul class="am-list am-collapse admin-sidebar-sub" id="collapse-fenxiao">
                        <li><a href="{{ url('admin/dividedinto/lists') }}" target="myiframe"><span class="am-icon-angle-right"></span>分成记录</a></li>
                        <li><a href="{{ url('admin/dividedinto/lists2') }}" target="myiframe"><span class="am-icon-angle-right"></span>待打款记录</a></li>
                    </ul>
                </li>
                <li class="admin-parent">
                    <a class="am-cf" data-am-collapse="{target: '#collapse-goods'}"><span class="am-icon-shopping-bag"></span> 商品管理 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                    <ul class="am-list am-collapse admin-sidebar-sub" id="collapse-goods">
                        <li><a href="{{ url('admin/commodity/lists') }}" target="myiframe"><span class="am-icon-angle-right"></span>商品列表</a></li>
                        <li><a href="{{ url('admin/classify/lists') }}" target="myiframe"><span class="am-icon-angle-right"></span>分类管理</a></li>
                        <li><a href="{{ url('admin/commoditycomment/lists') }}" target="myiframe"><span class="am-icon-angle-right"></span>评价管理</a></li>
                    </ul>
                </li>
                <li class="admin-parent">
                    <a class="am-cf" data-am-collapse="{target: '#collapse-design'}"><span class="am-icon-shopping-bag"></span> 定制管理 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                    <ul class="am-list am-collapse admin-sidebar-sub" id="collapse-design">
                        <li><a href="{{ url('admin/design/lists') }}" target="myiframe"><span class="am-icon-angle-right"></span>定制列表</a></li>
                        <li><a href="{{ url('admin/dclassify/lists') }}" target="myiframe"><span class="am-icon-angle-right"></span>分类管理</a></li>
                        {{--<li><a href="{{ url('admin/commoditycomment/lists') }}" target="myiframe"><span class="am-icon-angle-right"></span>评价管理</a></li>--}}
                    </ul>
                </li>
                <li class="admin-parent">
                    <a class="am-cf" data-am-collapse="{target: '#collapse-msg'}"><span class="am-icon-shopping-bag"></span> 消息管理 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                    <ul class="am-list am-collapse admin-sidebar-sub" id="collapse-msg">
                        <li><a href="{{ url('admin/msg/lists') }}" target="myiframe"><span class="am-icon-angle-right"></span>消息列表</a></li>
{{--                        <li><a href="{{ url('admin/dclassify/lists') }}" target="myiframe"><span class="am-icon-angle-right"></span>分类管理</a></li>--}}
                        {{--<li><a href="{{ url('admin/commoditycomment/lists') }}" target="myiframe"><span class="am-icon-angle-right"></span>评价管理</a></li>--}}
                    </ul>
                </li>
                <li class="admin-parent">
                    <a class="am-cf" data-am-collapse="{target: '#collapse-sys'}"><span class="am-icon-cog"></span> 系统设置 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                    <ul class="am-list am-collapse admin-sidebar-sub" id="collapse-sys">
                        <li><a href="{{ url('admin/config/edit') }}" target="myiframe"><span class="am-icon-angle-right"></span>系统设置</a></li>
                        {{--<li><a href="{{ url('admin/menu/edit') }}" target="myiframe"><span class="am-icon-angle-right"></span>微信自定义菜单</a></li>--}}
                    </ul>
                </li>
                <li class="admin-parent">
                    <a class="am-cf" data-am-collapse="{target: '#collapse-baobiao'}"><span class="am-icon-bar-chart"></span> 数据报表 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                    <ul class="am-list am-collapse admin-sidebar-sub" id="collapse-baobiao">
                        <li><a href="{{ url('admin/baobiao/user') }}" target="myiframe"><span class="am-icon-angle-right"></span>会员统计</a></li>
                        <li><a href="{{ url('admin/baobiao/money') }}" target="myiframe"><span class="am-icon-angle-right"></span>打款统计</a></li>
                        <li><a href="{{ url('admin/baobiao/xiaoshou') }}" target="myiframe"><span class="am-icon-angle-right"></span>销售统计</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <!-- sidebar end -->

    <!-- content start -->
    <div class="admin-content" id="admin-content">
        <div class="admin-content-body">
            <iframe scrolling="yes" frameborder="0" src="{{ url('admin/index/welcome') }}" style="width: 100%;height: 100%;position:absolute;padding-right: 185px" name="myiframe"></iframe>
        </div>
    </div>
    <!-- content end -->
</div>

<a href="#" class="am-icon-btn am-icon-th-list am-show-sm-only admin-menu" data-am-offcanvas="{target: '#admin-offcanvas'}"></a>

<!--[if lt IE 9]>
<script src="http://libs.baidu.com/jquery/1.11.1/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="/assets/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<script src="/assets/js/jquery.min.js"></script>
<!--<![endif]-->
<script src="/assets/js/amazeui.min.js"></script>
<script src="/assets/js/app.js"></script>
</body>
</html>