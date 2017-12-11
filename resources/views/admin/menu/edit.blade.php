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
        body{overflow-y: scroll;font-size: 13px;}
    </style>
</head>
<body>
<div class="hw-content" style="padding: 20px;padding-bottom: 80px;">

    <div class="am-alert am-alert-success" data-am-alert style="background: #f9edbe;border: 1px solid #f0c36d;color: #333;font-size: 12px">
        <button type="button" class="am-close">&times;</button>
        <p>注意：1级菜单最多只能开启3个，2级子菜单最多开启5个!</p>

        <p>只有保存主菜单后才可以添加子菜单</p>

        <p>生成自定义菜单,必须在已经保存的基础上进行,临时勾选启用点击生成是无效的! 第一步必须先修改保存状态！第二步点击生成!</p>

        <p>当您为自定义菜单填写链接地址时请填写以"http://"开头，这样可以保证用户手机浏览的兼容性更好</p>

        <p>撤销自定义菜单：撤销后，您的微信公众帐号上的自定义菜单将不存在；如果您想继续在微信公众帐号上使用自定义菜单，请点击“生成自定义菜单”按钮，将重新启用！</p>
    </div>
    <a type="button" class="am-btn am-btn-warning am-btn-sm" style="margin-bottom: 10px;" @click="addone"><i class="am-icon-plus"></i> 添加一级菜单</a>
    <a type="button" class="am-btn am-btn-primary am-btn-sm" style="margin-bottom: 10px;" @click="update"><i class="am-icon-refresh"></i> 更新微信菜单</a>
    <form action="" method="post" class="am-form" id="form-admin-add" enctype="multipart/form-data">
        <table class="am-table am-table-bordered am-table-radius am-table-striped">
            <thead>
            <tr>
                <th width="90">显示顺序</th>
                <th>主菜单名称</th>
                <th>触发动作</th>
                <th>响应动作</th>
                <th width="180">操作</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in data">
                <td><input type="text" class="am-input-sm valid" name="original" value="@{{ item.sort }}" v-model="item.sort"></td>
                <td>
                    <template v-if="item.level == 2">
                        <i class="board"></i>
                    </template>
                    <input type="text" class="am-input-sm valid" name="original" value="@{{ item.name }}" style="width: 300px;display: inline-block" v-model="item.name">
                    <a class="am-btn am-btn-success am-btn-sm" v-if="item.level == 1" @click="addsecond(this)">
                    <i class="am-icon-plus"></i>
                    </a>
                </td>
                <td>
                    <select name="carriageid" class="form-control valid" aria-invalid="false" style="height: 35px;line-height: 15px;">
                        <option value="0">跳转链接</option>
                    </select>
                </td>
                <td><input type="text" class="am-input-sm valid" name="original" value="@{{ item.value }}" v-model="item.value"></td>
                <td>
                    <a type="button" class="am-btn am-btn-danger am-btn-sm" @click="del(item.menuid,this)"><span class="am-icon-trash-o"></span>  删除</a>
                    <a type="button" class="am-btn am-btn-success am-btn-sm" @click="save(this)"><span class="am-icon-pencil-square-o"></span>  保存</a>
                </td>
            </tr>
            </tbody>
        </table>
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
<script type="text/javascript">
    var vm = new Vue({
        el: 'body',
        data: {
            data:{!! $data !!}
    },methods: {
        addone: function (event) {
            $n = 0
            $.each(vm.data,function (index,element) {
                if(vm.data[index]['level'] == "1"){
                    $n = $n + 1;
                }
            })
            console.log($n);
            if($n>=3){
                layer.msg("一级菜单最多只能添加3个");
                return;
            }
            vm.data.splice(vm.data.length,0,{menuid:0, level:"1", name: '',sort:"0",value:"",pid:0 ,nub:0,id:""})
        },
        addsecond:function (event) {
            if(vm.data[event.$index].menuid == 0){
                layer.msg("请先保存一级菜单再添加二级！");
                return;
            }
            $n = 0
            $.each(vm.data,function (index,element) {
                if(vm.data[index]['pid'] == vm.data[event.$index].menuid){
                    $n = $n + 1;
                }
            })

            if($n>=5){
                layer.msg("二级菜单最多只能添加5个");
                return;
            }
            vm.data.splice(event.$index+1,0,{menuid:0, level:"2", name: '',sort:"0",value:"" ,pid:vm.data[event.$index].menuid,id:""})
            vm.data[event.$index].nub = vm.data[event.$index].nub + 1
        },
        save:function (event) {
            $.post('/admin/menu/save',{data:vm.data[event.$index]},function (data) {
                if(data['status'] == "200"){
                    layer.msg("保存成功",function () {
                        vm.data[event.$index].menuid = data['msg'];
                        console.log(vm.data)
                    });
                }else{
                    layer.msg(data['msg']);
                }
            })
        },
        update:function(){
            //更新微信菜单
            $.get('update',function(data){
                if(data['status'] == "200"){
                    layer.msg(data['msg'], {icon: 1});
                }else{
                    layer.msg(data['msg'],{icon: 2});
                }
            })
        },
        del:function (id,event) {
            if(id!=''){
                $.get('del/?id='+id,function(data){
                    if(data['status'] == "200"){
                        layer.msg('删除成功！',function () {
                            window.location.reload();
                        });
                    }else{
                        layer.msg(data['msg']);
                    }
                })
            }else{
                vm.data.$remove(vm.data[event.$index])
            }

        }
    }
    })
</script>
</body>
</html>
