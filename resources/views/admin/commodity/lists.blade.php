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
        .am-btn-sm{margin-right: 10px;}
        .myclass{width: 400px;height: 400px;}
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
                        <li class="am-active">商品列表</li>
                    </ol>
                </div>
                <div class="am-fr am-cr">
                    <a class="am-btn am-btn-warning hw-shuaxin" href="javascript:location.replace(location.href);">
                        <i class="am-icon-refresh"></i>
                    </a>
                    <a class="am-btn am-btn-secondary hw-shuaxin tianjia" v-on:click="add">
                        <i class="am-icon-plus"></i>
                    </a>
                </div>
            </div>
            <div class="am-alert am-alert-secondary am-animation-scale-up" style="margin: 0px 20px;">
                <form class="am-form" action="/admin/commodity/lists" method="get">
                    搜索类型：
                    <select name="type" class="form-control" style="width: 120px;display: inline-block;height: 35px;font-size: 14px">
                        <option value="2" @if($type==2) selected @endif>搜分类</option>
                        <option value="1" @if($type==1) selected @endif>搜标题</option>
                    </select>
                    关键词：
                    <input type="text" class="am-input-sm" name="keyword" value="{{$keyword}}" style="width: 200px;display: inline-block">
                    <button type="submit" class="am-btn am-btn-secondary" style="height: 34px;line-height: 14px">筛选</button>
                </form>
            </div>
            <div class="am-g am-animation-slide-right">
                <div class="am-u-sm-12">
                    <form class="am-form">
                        <table class="am-table am-table-striped am-table-hover table-main">
                            <thead>
                            <tr>
                                <th width="120">ID</th>
                                <th>用户</th>
                                <th>缩略图</th>
                                <th>商品标题</th>
                                <th>价格</th>
                                <th>目标数量</th>
                                <th>支持人数</th>
                                <th>是否推荐</th>
                                <th>推荐排序</th>
                                <th>是否精选</th>
                                <th>精选排序</th>
                                <th>状态</th>
                                <th>是否往期</th>
                                <th class="table-set" width="280">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $v)
                                <tr>
                                    <td>{{ $v->commodityid }}</td>
                                    <td>{{ $v->nickname }}</td>
                                    <td><img :src="{{ $v->thumbnail }}" width="50" height="50"> </td>
                                    <td>{{ $v->title }}</td>
                                    <td>{{ $v->money }}元</td>
                                    <td>{{ $v->number }}件</td>
                                    <td>{{ $v->sales }}人</td>
                                    <td v-if="{{ $v->is_hot }} == 1">是</td>
                                    <td v-else>否</td>
                                    <td>{{ $v->hot_order }}</td>
                                    <td v-if="{{ $v->recommended }} == 1">是</td>
                                    <td v-else>否</td>
                                    <td>{{ $v->recom_order }}</td>
                                    <td v-if="{{ $v->status }} == 0"><span class="am-badge am-badge-danger">下架</span></td>
                                    <td v-else><span class="am-badge am-badge-success">正常</span></td>
                                    <td v-if="{{ $v->past }} == 1"><span class="am-badge am-badge-danger">往期</span></td>
                                    <td v-else><span class="am-badge am-badge-success">正常</span></td>
                                    <td>
                                        <div class="am-btn-toolbar">
                                            <div class="am-btn-group am-btn-group-xs">
                                                {{--<a type="button" class="am-btn am-btn-secondary am-btn-sm" @click="code(item.commodityid)">二维码</a>--}}
                                                <a type="button" class="am-btn am-btn-success am-btn-sm" @click="edit({{ $v->commodityid }})">编辑</a>
                                                <a type="button" class="am-btn am-btn-warning am-btn-sm" @click="caozuo({{ $v->commodityid }},{{ $v->status }},this)">
                                                    @if($v->status == 1)
                                                        下架
                                                    @else
                                                        上架
                                                    @endif
                                                </a>
                                                <a type="button" class="am-btn am-btn-warning am-btn-sm" @click="set({{ $v->commodityid }},{{ $v->past }},this)">
                                                    @if($v->past == 1)
                                                        恢复
                                                    @else
                                                        往期
                                                    @endif
                                                </a>
                                                <a type="button" class="am-btn am-btn-danger am-btn-sm" @click="shanchu({{ $v->commodityid }},this)">删除</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="am-cf" style="padding:0px 30px 30px 30px;margin-bottom: 30px;">
                            <div class="am-fl hw-jilu">共 {!! $data->count() !!} 条记录</div>
                            <div class="am-fr">
                                {{ $data->appends(['type' =>$type,'keyword'=>$keyword])->links() }}
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
<script src="/assets/js/amazeui.min.js"></script>
<script src="/assets/js/hw-layer.js"></script>
<script src="/assets/js/app.js"></script>

<script>
    var vm = new Vue({
        el: 'body',
        data: {
        },
        methods: {
            code:function (id) {
                layer.open({
                    type: 2,
                    title: false,
                    shadeClose: true,
                    shade: 0.7,
                    closeBtn:0,
                    area: ['210px', '210px'],
                    content: '__CONTROLLER__/code/?id='+id
                });
            },
            add: function (id) {
                layer_full("添加普通商品",'add/')
            },
            xsqg: function (id,status,event) {
                layer_full("限时抢购",'__CONTROLLER__/xsqg/?id='+id)

            },
            edit: function (id) {
                layer_full("修改普通商品",'edit/?id='+id)
            },
            shanchu: function (id,event) {
                layer.msg('确定要删除吗？', {
                    time:0,
                    btn: ['确定', '取消'],
                    yes: function(){
                        $.get('del/?id='+id,function (data) {
                            if(data['status'] == "200"){
                                layer.msg('删除成功！',function () {
                                    window.location.reload();
                                });
                            }else{
                                layer.msg(data['msg']);
                            }
                        })
                    }
                });
            },
            caozuo: function (id,status,event) {
                if(status == 0){
                    $temp = "上架"
                    $s = 1
                }else{
                    $temp = "下架"
                    $s = 0
                }
                layer.msg('确定要'+$temp+'吗？', {
                    time:0,
                    btn: ['确定', '取消'],
                    yes: function(){
                        $.get('remove/?id='+id+"&status="+status,function (data) {
                            if(data['status'] == "200"){
                                layer.msg(data['msg'],function () {
                                    window.location.reload();
                                });
                                vm.data[event.$index].status = $s
                            }else{
                                layer.msg(data['msg']);
                            }
                        })
                    }
                });
            },
            set: function (id,past,event) {
                if(past == 0){
                    $temp = "往期"
                    $s = 1
                }else{
                    $temp = "恢复"
                    $s = 0
                }
                layer.msg('确定要'+$temp+'吗？', {
                    time:0,
                    btn: ['确定', '取消'],
                    yes: function(){
                        $.get('removes/?id='+id+"&past="+past,function (data) {
                            if(data['status'] == "200"){
                                layer.msg(data['msg'],function () {
                                    window.location.reload();
                                });

                            }else{
                                layer.msg(data['msg']);
                            }
                        })
                    }
                });
            },
        }
    })

</script>
</body>
</html>