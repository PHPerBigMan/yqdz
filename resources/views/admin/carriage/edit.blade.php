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
        html{overflow-y: scroll}
        body{font-size: 13px;overflow-y: scroll}
        .am-radio-inline:nth-child(1),.am-checkbox-inline:nth-child(1){margin-left: 10px;}
        .am-radio-inline,.am-checkbox-inline{width: 110px}
        .yunfei{width: 200px !important;margin-right: 10px;height: 25px;}
    </style>
</head>
<body>
<div class="hw-content" style="padding: 20px;padding-bottom: 60px;">
    <form class="am-form am-form-horizontal" action="" method="post" id="from1">
        {{ csrf_field() }}
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">
                模版标题
            </div>
            <div class="am-u-sm-8 am-u-md-4">
                <input type="hidden" class="am-input-sm" name="carriageid" value="{{$data->carriageid or ''}}">
                <input type="text" class="am-input-sm" name="title" placeholder="请输入规格名称" value="{{$data->title or ''}}">
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>

        <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">
                发货省份
            </div>
            <div class="am-u-sm-8 am-u-md-8">
                <template v-for="item in province">
                    <label class="am-radio-inline">
                        <template v-if="item.name == '{{$data->province}}'">
                            <input type="radio"  value="@{{ item.name }}" name="province" checked> @{{ item.name }}
                        </template>
                        <template v-else>
                            <input type="radio"  value="@{{ item.name }}" name="province"> @{{ item.name }}
                        </template>
                    </label>
                </template>
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>

        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">
                默认价格
            </div>
            <div class="am-u-sm-8 am-u-md-4">
                <input type="text" class="am-input-sm" name="price" placeholder="单元:元(不单独设置价格将采用默认价格)" value="{{$data->price or ''}}">
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>

        <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">
                收货省份
            </div>
            <div class="am-u-sm-8 am-u-md-8">
                <template v-for="item in province">
                    <label class="am-checkbox-inline">
                        <input type="checkbox"  value="" name="yunfei" @click="add(item.name,this)" v-model="item.is"> @{{ item.name }}
                    </label>
                </template>
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>

        <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-9 am-u-sm-offset-2">
                <div class="am-panel am-panel-default">
                    <div class="am-panel-hd">运费设置</div>
                    <div class="am-panel-bd">
                        <table class="am-table am-table-striped am-table-hover">
                            <thead>
                            <tr>
                                <th>配送省份</th>
                                <th>首重价格</th>
                                <th>续重价格</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="item in data">
                                <td>@{{ item.takeprovince }}</td>
                                <td>
                                    <input type="hidden" name="takeprovince[]" value="@{{ item.takeprovince }}">
                                    {{--<input type="text" class="am-input-sm yunfei am-fl" value="@{{ item.yunfei }}" name="price2[]">元--}}
                                    <input type="text" class="am-input-sm yunfei am-fl" value="@{{ item.first_price }}" name="first_price[]">元
                                </td>
                                <td>
                                    <input type="text" class="am-input-sm yunfei am-fl" value="@{{ item.extra_price }}" name="extra_price[]">元
                                </td>
                                <td><a class="am-btn am-btn-danger am-btn-xs" @click="del(this)">删除</a></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-9 am-u-sm-offset-2">
                @if (isset($data))
                    <button type="button" class="am-btn am-btn-primary">修改运费模版</button>
                @else
                    <button type="button" class="am-btn am-btn-primary">添加运费模版</button>
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
        $.post("/admin/carriage/handle",$("#from1").serialize(),function (data) {
            if(data['status'] == "200"){
                layer.msg(data['msg'],{icon:1,time:1000},function () {
                    var index = parent.layer.getFrameIndex(window.name);
                    parent.location.reload()
                    parent.layer.close(index);
                })
            }else{
                layer.msg(data['msg'],{icon:2,time:1000});
            }
        })
    })
</script>
<script>
    var vm = new Vue({
        el: 'body',
        data: {
            province: {!! $province !!},
            data : {!! $extend !!},
    },
    ready: function () {
        console.log(this)
    },
    methods: {
        add: function(title,event) {
            $nub = event.$index;
            if(this.province[event.$index]['is'] != true){
                this.data.splice(this.data.length,0,{ takeprovince:title, yunfei: ''})
            }else{
                $.each(vm.data,function (index,element) {
                    if(vm.province[event.$index]['name'] == element['takeprovince']){
                        vm.data.$remove(vm.data[index])
                        return true
                    }
                })
            }
        },
        del:function(event){
            $nub = event.$index;
            $.each(vm.province,function (index,element) {
                if(vm.data[$nub]['takeprovince'] == element['name']){
                    vm.data.$remove(vm.data[$nub])
                    vm.province[index]['is'] = false
                    return false;
                }
            })
        },
    }
    });
</script>
</body>
</html>
