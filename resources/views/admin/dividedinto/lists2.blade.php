<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="/assets/css/amazeui.min.css"/>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <script src="//cdn.bootcss.com/vue/1.0.24/vue.min.js"></script>
    <style>
        .am-btn-default{background: none}
        .am-dropdown-content{z-index: 9999;background: #fff}
        label{font-weight: normal;margin-right: 10px;}
        .am-form-group{margin-bottom: 0px;display: inline-block;margin-right: 10px;}
        .am-panel{margin-right: 30px;margin-left: 15px}
        .am-panel-bd{padding: .5rem;}
        .am-form select{width: 100px;display: inline-block;margin-right: 10px;}
        .am-form input[type='text']{width: 200px;display: inline-block}
        .am-form input[type=number], .am-form input[type=search], .am-form input[type=text], .am-form input[type=password], .am-form input[type=datetime], .am-form input[type=datetime-local], .am-form input[type=date], .am-form input[type=month], .am-form input[type=time], .am-form input[type=week], .am-form input[type=email], .am-form input[type=url], .am-form input[type=tel], .am-form input[type=color], .am-form select, .am-form textarea, .am-form-field{padding: .4rem;border-radius: 0px;}
        .laydate-icon, .laydate-icon-default, .laydate-icon-danlan, .laydate-icon-dahong, .laydate-icon-molv{height: 32px !important;padding-left: 10px!important;}
    </style>
</head>
<body>
<div class="am-cf admin-main2">
    <!-- content start -->
    <div class="admin-content">
        <div class="admin-content-body">
            <div class="am-cf am-padding am-padding-bottom-0 am-animation-slide-top hw-nav" >
                <div class="am-fl am-cf">
                    <ol class="am-breadcrumb">
                        <li class="am-active">订单分成记录</li>
                    </ol>
                </div>
                <div class="am-fr am-cr">
                    <a class="am-btn am-btn-warning hw-shuaxin" href="javascript:location.replace(location.href);">
                        <i class="am-icon-refresh"></i>
                    </a>
                </div>
            </div>
            <div class="am-g am-animation-slide-right">
                <div class="am-u-sm-12">
                    <form class="am-form">
                        <table class="am-table am-table-striped am-table-hover table-main">
                            <thead>
                            <tr>
                                {{--<th class="table-check"><input type="checkbox" v-model="checkedNames"></th>--}}
                                <th width="80">ID</th>
                                <th>获佣用户</th>
                                <th>分成金额</th>
                                <th class="table-set" width="250">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $v)
                                <tr>
                                    {{--<td><input type="checkbox" id="id:{{ $v->intoid }}" value="{{ $v->intoid }}" v-model="checkedNames"></td>--}}
                                    <td>{{ $v->intoid }}</td>
                                    <td><img :src="{{$v->img}}" width="40" style="margin-right: 10px;">{{ $v->nickname }}</td>
                                    <td>{{ $v->money }}元</td>
                                    <td>
                                        <div class="am-btn-toolbar">
                                            <a type="button" class="am-btn am-btn-secondary am-btn-sm" @click="detail({{$v->uid}})">查看涉及订单</a>
                                            <a type="button" class="am-btn am-btn-success am-btn-sm" @click="fangkuan({{$v->uid}},this)">全部放款</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="am-cf" style="padding:0px 30px 30px 30px;margin-bottom: 30px;">
                            <div class="am-fl hw-jilu">共 {!! $list->count() !!} 条记录</div>
                            <div class="am-fr">
                                {!! $list->links() !!}
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- content end -->
</div>

<!--[if lt IE 9]>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="/assets/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->
<script src="//cdn.bootcss.com/jquery/2.0.2/jquery.min.js"></script>
<script src="/layer/layer.js"></script>
<script src="/layer/laydate.js"></script>
<script src="/assets/js/amazeui.min.js"></script>
<script src="/assets/js/hw-layer.js"></script>
<script src="/assets/js/app.js"></script>
<script>
    var vm = new Vue({
            el: 'body',
            data: {
            checkedNames: []
        },
        methods: {
        detail: function (id) {
            layer_full("查看涉及订单",'lists/?uid='+id+'&type=2')
        },
        fangkuan: function (id) {
            layer.confirm('确定将该用户所涉及分销订单全部放款吗？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                $.get('fangkuan/?uid='+id,function(data){
                    if(data['status'] == "200"){
                        layer.msg('操作成功！',{icon:1},function(){
                            location.reload()
                        });
                    }else{
                        layer.msg(data['msg'],{icon:2});
                    }
                })
            }, function(){

            });
        },
    }
    })

</script>
</body>
</html>