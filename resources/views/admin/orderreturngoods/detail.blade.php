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
        .am-table>tbody>tr>td, .am-table>tbody>tr>th, .am-table>tfoot>tr>td, .am-table>tfoot>tr>th, .am-table>thead>tr>td, .am-table>thead>tr>th{border: none;font-weight: normal}
    </style>
</head>
<body>
<div class="hw-content" style="padding: 20px;">
    <form class="am-form am-form-horizontal" action="" method="post" id="from1">
        <div class="am-g am-margin-top">
            <div class="am-panel am-panel-default">
                <div class="am-panel-hd">退货商品信息</div>
                <div class="am-panel-bd">
                    <table class="am-table am-table-striped am-table-hover">
                        <tbody>
                        <tr>
                            <th>商品名称</th>
                            <th>数量</th>
                            <th>单价</th>
                            <th>一级分成</th>
                            <th>二级分成</th>
                            <th>三级分成</th>
                        </tr>
                        @foreach($data['snop']['snopjson'] as $key=>$val)
                        <tr>

                            <td><img :src="{{$val->thumbnail}}" width="40"> {{$val->title}}</td>
                            <td>{{$nums[$key]}}</td>
                            <td>{{$val->money}}</td>
                            <td>{{ $val->firstgraded }}%</td>
                            <td>{{ $val->secondgraded }}%</td>
                            <td>{{ $val->threegraded }}%</td>
                        </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="am-panel am-panel-default">
                <div class="am-panel-hd">买家退货申请</div>
                <div class="am-panel-bd">
                    <table class="am-table">
                        <tbody>
                        <tr>
                            <td width="110">当前状态：</td>
                            <td>
                                @if($data['status']== 1)
                                    等待商户处理
                                @elseif($data['status'] == 2)
                                    等待用户上传物流信息
                                @elseif($data['status'] == 3)
                                    拒绝退货
                                @elseif($data['status'] == 4)
                                    等待商户收货
                                @elseif($data['status'] == 5)
                                    退货完成
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td width="110">退货编号：</td>
                            <td>{{$data['returnid']}}</td>
                        </tr>
                        <tr>
                            <td>申请人：</td>
                            <td><img src="{{$data['user']['img']}}" width="40" style="margin-right: 10px">{{$data['user']['nickname']}} </td>
                        </tr>
                        <tr>
                            <td>退货原因：</td>
                            <td>{{$data['content']}}</td>
                        </tr>
                        <tr>
                            <td>退货金额：</td>
                            <td>{{$data['money']}}</td>
                        </tr>
                        <tr>
                            <td>退货时间：</td>
                            <td>{{$data['created_at']}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="am-panel am-panel-default">
                <div class="am-panel-hd">回寄商品信息</div>
                <div class="am-panel-bd">
                    <table class="am-table">
                        <tbody>
                        <tr>
                            <td width="110">物流公司：</td>
                            <td>{{$data['express']}}</td>
                        </tr>
                        <tr>
                            <td>物流单号：</td>
                            <td>{{$data['logistics']}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="am-panel am-panel-default">
                <div class="am-panel-bd">
                    @if($data['status']==1)
                        <a type="button" class="am-btn am-btn-success am-btn-sm" @click="agree({{$data['returnid']}})">同意退货</a>
                        <a type="button" class="am-btn am-btn-danger am-btn-sm" @click="refuse({{$data['returnid']}})">拒绝退货</a>
                    @endif
                    @if($data['status']==4)
                        <a type="button" class="am-btn am-btn-warning am-btn-sm" @click="shouhuo({{$data['returnid']}})">确认收货</a>
                    @endif
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
        agree: function (id,key,event) {
            layer.confirm('确定同意用户退货请求吗？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                $.get('/admin/orderreturngoods/agree/?id='+id,function(data){
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
        refuse: function (id,key,event) {
            layer.confirm('确定拒绝该用户退货申请吗？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                $.get('/admin/orderreturngoods/refuse/?id='+id,function(data){
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
        shouhuo: function (id,key,event) {
            layer.confirm('确定仓库已经收到用户回寄的商品且已经退款吗？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                $.get('/admin/orderreturngoods/shouhuo/?id='+id,function(data){
                    if(data['status'] == "200"){
                        layer.msg(data['msg'],{icon:1},function () {
                            parent.location.reload()
                            parent.layer.close(index);
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
