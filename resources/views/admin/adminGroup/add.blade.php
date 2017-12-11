<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="//cdn.bootcss.com/jquery/2.0.2/jquery.min.js"></script>
    <link rel="stylesheet" href="/assets/css/amazeui.min.css"/>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <script src="http://cdn.bootcss.com/vue/1.0.22/vue.js"></script>
    <style>
        body{overflow-y: auto;font-size: 13px;}
        .am-checkbox-inline{width: 150px !important}
        .am-checkbox-inline:nth-child(1){margin-left:10px}
        .am-checkbox-inline label{font-weight: normal}
    </style>
</head>
<body>
<div class="hw-content" style="padding: 20px;padding-bottom: 70px">
    <form class="am-form am-form-horizontal" action="" method="post" id="from1">
        {{ csrf_field() }}
        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">权限组名：</div>
            <div class="am-u-sm-8 am-u-md-4">
                <input type="text" class="am-input-sm" v-model="gname" name="gname" value="{{$data->gname or ''}}">
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
        </div>

        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">描述：</div>
            <div class="am-u-sm-8 am-u-md-4 am-u-end col-end">
                <input type="hidden" name="groupid" value="{{$data->groupid or ''}}">
                <input type="hidden" class="input-text" value="@{{rule_val}}" name="ruleid">
                <input type="text" class="am-input-sm" v-model="desc" name="miaoshu" placeholder="请输入简短描述" value="{{$data->miaoshu or ''}}">
            </div>
        </div>

        <div class="am-g am-margin-top">
            <div class="am-u-sm-3 am-u-md-2 am-text-right">拥有权限：</div>
            <div class="am-u-sm-9 am-u-md-10 am-u-end col-end">
                <div class="am-form-group" v-for="item in cat">
                    <label>@{{ item.name }}</label>
                    <div class="am-form-group">
                        <template v-for="rule in data" v-if="item.cid == rule.cid">
                            <label class="am-checkbox-inline">
                                <input type="checkbox" id="rule@{{rule.ruleid}}" value="@{{rule.ruleid}}" :onclick="setrule" v-model="rule.isselected"><label for="rule@{{rule.ruleid}}">@{{ rule.desc }}</label>
                            </label>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-8 am-u-sm-offset-2">
                @if (isset($data))
                    <button type="button" class="am-btn am-btn-primary">修改用户组</button>
                @else
                    <button type="button" class="am-btn am-btn-primary">添加用户组</button>
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
    var vm = new Vue({
            el: 'body',
            data: {
                data:{!! $rule !!},
                cat:{!! $cat !!},
                rule_val:[],
                gname:'',
                desc:''
        },
        methods:{
            init:function(){

            }
        },
        computed: {
        rule_val: function () {
            var temp_arr = new Array();
            $.each(this.data,function (index,element) {
//                console.log(this.data)
                if(element.isselected){
                    temp_arr.push(element.ruleid)
                }
            })
            return temp_arr.join()
        }
    },
    })
    $(".am-btn-primary").click(function () {
        if(vm.gname==''){
            layer.msg("权限组名不能为空!",{icon:2});
            return false;
        }
        if(vm.desc==''){
            layer.msg("描述不能为空!",{icon:2});
            return false;
        }
        $.post("/admin/adminGroup/handle",$("#from1").serialize(),function (data) {
            console.log(data)
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
</body>
</html>
