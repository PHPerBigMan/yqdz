<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="//cdn.bootcss.com/jquery/2.0.2/jquery.min.js"></script>
    <link rel="stylesheet" href="/assets/css/amazeui.min.css"/>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <style>
        html{overflow-y: scroll}
        body{overflow-y: scroll;font-size: 13px;}
        .lbimg{margin-bottom:20px}
        .valbox{width: 100%;height: 110px;background: #f8f8f8;padding: 20px;margin: 10px 0px;}
        .valbox span {
            display: inline-block;
            border: 1px solid #e5e5e5;
            padding: 2px 8px;
            margin-top: 10px;
            border-radius: 4px;
            background: #fff;
            margin-right: 10px;
            position: relative;
        }
        .valbox span i.closed2 {
            font-style: normal;
            position: absolute;
            top: -5px;
            right: -5px;
            background: #000;
            color: #fff;
            border-radius: 50%;
            width: 10px;
            height: 10px;
            font-size: 10px;
            text-align: center;
            line-height: 10px;
            cursor: pointer;
        }
        .tjjbox a{cursor: pointer}
        .closed {
            width: 30px;
            height: 30px;
            text-align: center;
            line-height: 30px;
            display: inline-block;
            float: right;
            font-style: normal;
            font-size: 12px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="hw-content" style="padding: 20px;padding-bottom: 50px;">
    <form action="/admin/commodity/handle" method="post" class="am-form" id="form-admin-add" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="am-tabs am-margin" data-am-tabs="">
            <ul class="am-tabs-nav am-nav am-nav-tabs">
                <li class="am-active"><a href="#tab1">基本信息</a></li>
                <li class=""><a href="#tab2">图文详情</a></li>
                <li class=""><a href="#tab3">机构测评</a></li>
                <li class=""><a href="#tab4">轮播图</a></li>
                <li class=""><a href="#tab5">商品标签</a></li>
                <li class=""><a href="#tab6">产品动态</a></li>
            </ul>

            <div class="am-tabs-bd" style="touch-action: pan-y; -webkit-user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                <div class="am-tab-panel am-fade am-active am-in" id="tab1">
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            所属分类：
                        </div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <select name="classifyid" class="form-control valid" aria-invalid="false" style="height: 35px;font-size: 13px" v-model="isclass">
                                <option value="0">请选择分类</option>
                                @foreach($classify as $v)
                                    @if(isset($commodity))
                                        <option value="{{$v->classifyid}}" @if($v->classifyid == $commodity->classifyid) selected @endif>{{$v->name}}</option>
                                    @else
                                        <option value="{{$v->classifyid}}">{{$v->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">*必填，不可重复</div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            发起用户：
                        </div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input type="text" class="am-input-sm" name="nickname" value="{{$commodity->nickname or ''}}">
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">*必填</div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            商品标题：
                        </div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input type="hidden" value="{{$commodity->commodityid or ''}}" name="commodityid">
                            <input type="text" class="am-input-sm" name="title" value="{{$commodity->title or ''}}">
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">*必填，不可重复</div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            商品描述：
                        </div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input type="text" class="am-input-sm" name="describes" value="{{$commodity->describes or ''}}">
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">*简要描述</div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            商品价格：
                        </div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input type="number" class="am-input-sm" name="money" value="{{$commodity->money or ''}}">
                        </div>
                        <div class="am-hide-sm-only am-u-md-6"></div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            目标数量：
                        </div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input type="number" class="am-input-sm" name="number" value="{{$commodity->number or ''}}">
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">注：该商品的众筹数量</div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            库存数量：
                        </div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input type="number" class="am-input-sm" name="stock" value="{{$commodity->stock or ''}}">
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">注：该商品的库存数量</div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            商品重量：
                        </div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input type="number" class="am-input-sm" name="weight" value="{{$commodity->weight or ''}}">
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">注：单位为kg</div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            专家说：
                        </div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input type="text" class="am-input-sm" name="experts" value="{{$commodity->experts or ''}}">
                        </div>
                        <div class="am-hide-sm-only am-u-md-6"></div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            开始时间：
                        </div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input type="text" class=" am-input-sm" name="starttime"  placeholder="请选择开始时间" value="{{$commodity->starttime or ''}}" data-am-datepicker  style="cursor: pointer;" required/>
                        </div>
                        <div class="am-hide-sm-only am-u-md-6"></div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            结束时间：
                        </div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input type="text" class=" am-input-sm" name="endtime"  placeholder="请选择结束时间" value="{{$commodity->endtime or ''}}" data-am-datepicker   style="cursor: pointer;" required/>
                        </div>
                        <div class="am-hide-sm-only am-u-md-6"></div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            一级提成比例：
                        </div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input type="number" class="am-input-sm" name="firstgraded" value="{{$commodity->firstgraded or ''}}">
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">注：百分比%（不需要带%）</div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            二级提成比例：
                        </div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input type="number" class="am-input-sm" name="secondgraded" value="{{$commodity->secondgraded or ''}}">
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">注：百分比%（不需要带%）</div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            三级提成比例：
                        </div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input type="number" class="am-input-sm" name="threegraded" value="{{$commodity->threegraded or ''}}">
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">注：百分比%（不需要带%）</div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            是否精选：
                        </div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <select name="recommended" class="form-control">
                                @if(isset($commodity))
                                    @if($commodity->recommended ==1)
                                        <option value="1" selected>是</option>
                                        <option value="0">否</option>
                                    @endif

                                    @if($commodity->recommended ==0)
                                        <option value="1">是</option>
                                        <option value="0" selected>否</option>
                                    @endif
                                @else
                                    <option value="1">是</option>
                                    <option value="0">否</option>
                                @endif
                            </select>
                        </div>
                        <div class="am-hide-sm-only am-u-md-6"></div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            精选排序：
                        </div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input type="number" class="am-input-sm" name="recom_order" value="{{$commodity->recom_order or ''}}">
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">注：1为第一位</div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            是否推荐：
                        </div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <select name="is_hot" class="form-control">
                                @if(isset($commodity))
                                    @if($commodity->is_hot ==1)
                                        <option value="1" selected>是</option>
                                        <option value="0">否</option>
                                    @endif

                                    @if($commodity->is_hot ==0)
                                        <option value="1">是</option>
                                        <option value="0" selected>否</option>
                                    @endif
                                @else
                                    <option value="1">是</option>
                                    <option value="0">否</option>
                                @endif
                            </select>
                        </div>
                        <div class="am-hide-sm-only am-u-md-6"></div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            推荐排序：
                        </div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input type="number" class="am-input-sm" name="hot_order" value="{{$commodity->hot_order or ''}}">
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">注：1为第一位</div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            运费模版：
                        </div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <select name="carriageid" class="form-control">
                                <option value="0">请选择运费模板</option>
                                @foreach($carriage as $v)
                                        <option @if(isset($commodity) && $v->carriageid == $commodity->carriageid) selected @endif value="{{$v->carriageid}}">{{$v->title}}</option>
                                @endforeach
                            </select>
                        </div> 
                        <div class="am-hide-sm-only am-u-md-6">注：该商品的运费将根据该模版自动计算运费</div>
                    </div>
                    <div class="am-g am-margin-top-sm">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            首页产品缩略图：
                        </div>
                        <div class="am-u-sm-8 am-u-md-4 am-u-end">
                            <div class="am-form-group am-form-file">
                                <button type="button" class="am-btn am-btn-danger am-btn-sm">
                                    <i class="am-icon-cloud-upload"></i> 选择要上传的文件</button>
                                <input id="doc-form-file-1" type="file" multiple name="suolvetu[]">
                            </div><div id="file-list-1"><img src="{{ !empty($commodity->thumbnail[0]) ? $commodity->thumbnail[0] :""}}" width="80"></div>
                            <input type="hidden" value="{{ !empty($commodity->thumbnail[0]) ? $commodity->thumbnail[0] :"" }}" name="suo">
                            <script>
                                var result1 = document.getElementById("file-list-1");
                                var input1 = document.getElementById("doc-form-file-1");

                                if(typeof FileReader==='undefined'){
                                    result1.innerHTML = "抱歉，你的浏览器不支持 FileReader";
                                    input1.setAttribute('disabled','disabled');
                                }else{
                                    input1.addEventListener('change',readFile,false);
                                }
                                function readFile(){
                                    var file1 = this.files[0];
                                    if(!/image\/\w+/.test(file1.type)){
                                        alert("文件必须为图片！");
                                        return false;
                                    }
                                    var reader1 = new FileReader();
                                    reader1.readAsDataURL(file1);
                                    reader1.onload = function(e){
                                        result1.innerHTML = '<img src="'+this.result+'" alt="" width="90px" height="90px"/>'
                                    }
                                }
                                $(function() {
                                    $('#doc-form-file-1').on('change', function() {
                                        var fileNames1 = '';
                                        $.each(this.files, function() {
                                            fileNames1 += '<span class="am-badge">' + this.name + '</span> ';
                                        });
                                        $('#file-list-1').html(fileNames1);
                                    });
                                });
                            </script>
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">建议上传图片大小 :375px 180px</div>
                    </div>
                    <div class="am-g am-margin-top-sm">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            定制广场产品缩略图：
                        </div>
                        <div class="am-u-sm-8 am-u-md-4 am-u-end">
                            <div class="am-form-group am-form-file">
                                <button type="button" class="am-btn am-btn-danger am-btn-sm">
                                    <i class="am-icon-cloud-upload"></i> 选择要上传的文件</button>
                                <input id="doc-form-file-2" type="file" multiple name="dz_suolvetu[]">
                            </div>
                            <div id="file-list-2"><img src="{{ !empty($commodity->dz_thumbnail[0]) ? $commodity->dz_thumbnail[0] : "" }}" width="80"></div>
                            <input type="hidden" value="{{ !empty($commodity->dz_thumbnail[0]) ? $commodity->dz_thumbnail[0] : "" }}" name="dz_suo">
                            <script>
                                var result = document.getElementById("file-list-2");
                                var input = document.getElementById("doc-form-file-2");

                                if(typeof FileReader==='undefined'){
                                    result.innerHTML = "抱歉，你的浏览器不支持 FileReader";
                                    input.setAttribute('disabled','disabled');
                                }else{
                                    input.addEventListener('change',readFile,false);
                                }
                                function readFile(){
                                    var file = this.files[0];
                                    if(!/image\/\w+/.test(file.type)){
                                        alert("文件必须为图片！");
                                        return false;
                                    }
                                    var reader = new FileReader();
                                    reader.readAsDataURL(file);
                                    reader.onload = function(e){
                                        result.innerHTML = '<img src="'+this.result+'" alt="" width="90px" height="90px"/>'
                                    }
                                }
                                $(function() {
                                    $('#doc-form-file-2').on('change', function() {
                                        var fileNames = '';
                                        $.each(this.files, function() {
                                            fileNames += '<span class="am-badge">' + this.name + '</span> ';
                                        });
                                        $('#file-list-2').html(fileNames);
                                    });
                                });
                            </script>
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">建议上传图片大小 :540px 580px</div>
                    </div>
                    <div class="am-g am-margin-top-sm">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            分享缩略图：
                        </div>
                        <div class="am-u-sm-8 am-u-md-4 am-u-end">
                            <div class="am-form-group am-form-file">
                                <button type="button" class="am-btn am-btn-danger am-btn-sm">
                                    <i class="am-icon-cloud-upload"></i> 选择要上传的文件</button>
                                <input id="doc-form-file-3" type="file" multiple name="fx_thumb[]">
                            </div>
                            <div id="file-list-3"><img src="{{ !empty($commodity->fx_thumb[0]) ? $commodity->fx_thumb[0] :"" }}" width="80"></div>
                            <input type="hidden" value="{{ !empty($commodity->fx_thumb[0]) ? $commodity->fx_thumb[0] :"" }}" name="dz_suo">
                            <script>
                                var result_2 = document.getElementById("file-list-3");
                                var input_2 = document.getElementById("doc-form-file-3");

                                if(typeof FileReader==='undefined'){
                                    result_2.innerHTML = "抱歉，你的浏览器不支持 FileReader";
                                    input_2.setAttribute('disabled','disabled');
                                }else{
                                    input_2.addEventListener('change',readFile,false);
                                }
                                function readFile(){
                                    var file_2 = this.files[0];
                                    if(!/image\/\w+/.test(file_2.type)){
                                        alert("文件必须为图片！");
                                        return false;
                                    }
                                    var reader_2 = new FileReader();
                                    reader_2.readAsDataURL(file_2);
                                    reader_2.onload = function(e){
                                        result_2.innerHTML = '<img src="'+this.result+'" alt="" width="90px" height="90px"/>'
                                    }
                                }
                                $(function() {
                                    $('#doc-form-file-3').on('change', function() {
                                        var fileNames_2 = '';
                                        $.each(this.files, function() {
                                            fileNames_2 += '<span class="am-badge">' + this.name + '</span> ';
                                        });
                                        $('#file-list-3').html(fileNames_2);
                                    });
                                });
                            </script>
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">建议上传图片大小 :64px 64px</div>
                    </div>
                </div>
                <div class="am-tab-panel am-fade" id="tab2">
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-12 am-u-md-12">
                            <script id="content" type="text/plain" style="width:100%;height:400px;" name="content">{!! $commodity->content or '' !!}</script>
                        </div>
                        <style>
                            .edui-container,#content{width: 100% !important;}
                        </style>
                        <div class="am-hide-sm-only am-u-md-6"></div>
                    </div>
                </div>
                <div class="am-tab-panel am-fade" id="tab3">
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-12 am-u-md-12">
                            <script id="appraisal" type="text/plain" style="width:100%;height:400px;" name="appraisal">{!! $commodity->appraisal or '' !!}</script>
                        </div>
                        <style>
                            .edui-container,#appraisal{width: 100% !important;}
                        </style>
                        <div class="am-hide-sm-only am-u-md-6"></div>
                    </div>
                </div>
                <div class="am-tab-panel am-fade" id="tab4">
                    <div class="am-g am-margin-top-sm">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            <button type="button" class="am-btn am-btn-success" @click="addimg">添加相册</button>
                        </div>
                        <div class="am-u-sm-8 am-u-md-4 am-u-end">
                            <p class="cp1" v-for="item in carousel">
                                <img :src="item" width="80">
                                <input type="hidden" value="@{{ item }}" name="xcimg[]">
                                <span class="am-badge am-badge-primary am-radius" @click="up(this)">上移</span>
                                <span class="am-badge am-badge-secondary am-radius" @click="down(this)">下移</span>
                                <span class="am-badge am-badge-danger am-radius" @click="del(this)">删除</span>
                            <p>
                                <template v-for="input in inputimg">
                                    <input type="file" class="lbimg" name="lunbo[]">
                                </template>
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">建议上传图片大小 :375px 180px</div>
                    </div>
                </div>
                <div class="am-tab-panel am-fade" id="tab5">
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">选择标签</div>
                        <div class="am-u-sm-8 am-u-md-10">
                            <button type="button" class="am-btn am-btn-warning" @click="addval(mykey,this)">添加标签</button>
                            <div style="clear: both"></div>
                            <div class="valbox">
                                <span v-for="(mykey,item) in data"><i class="closed2" @click="delval(this)">X</i><b>@{{ item }}</b>
                                <input type="hidden" name="labelid[]" value="@{{ item }}"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="am-tab-panel am-fade am-active am-in" id="tab6">


                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            动态内容：
                        </div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <textarea name="d_content">{{$dt->content or ''}}</textarea>
                        </div>
                        <div class="am-hide-sm-only am-u-md-6"></div>
                    </div>
                    <div class="am-g am-margin-top-sm">

                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            <button type="button" class="am-btn am-btn-success" @click="addimg">添加相册</button>
                        </div>
                        <div class="am-u-sm-8 am-u-md-4 am-u-end">
                            <p class="cp1" v-for="d_item in d_carousel">
                                <img :src="d_item" width="80">
                                <input type="hidden" value="@{{ d_item }}" name="d_xcimg[]">
                                <span class="am-badge am-badge-primary am-radius" @click="up(this)">上移</span>
                                <span class="am-badge am-badge-secondary am-radius" @click="down(this)">下移</span>
                                <span class="am-badge am-badge-danger am-radius" @click="d_del(this)">删除</span>
                            <p>
                                <template v-for="input in inputimg">
                                    <input type="file" class="lbimg" name="d_lunbo[]">
                                </template>
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">建议上传图片大小 :69px 69px</div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            开始时间：
                        </div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input type="text" class=" am-input-sm" name="d_starttime"  placeholder="请选择开始时间" value="{{$dt->create_time or ''}}" data-am-datepicker  style="cursor: pointer;" />
                        </div>
                        <div class="am-hide-sm-only am-u-md-6"></div>
                    </div>
                </div>
            </div>
            <div class="am-margin">
                <button type="submit" class="am-btn am-btn-primary am-btn-xs">提交保存</button>
            </div>
    </form>
</div>
<script src="http://cdn.bootcss.com/vue/1.0.26/vue.min.js"></script>
<!--[if lt IE 9]>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="/assets/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->
<script src="/layer/layer.js"></script>
<script src="/layer/laydate.js"></script>
<script src="/assets/js/amazeui.min.js"></script>
<script src="/assets/js/hw-layer.js"></script>
<script src="/assets/js/app.js"></script>
<script type="text/javascript" src="/ueditor/1.4.3/ueditor.config.js"></script>
<script type="text/javascript" src="/ueditor/1.4.3/ueditor.all.min.js"> </script>
<script type="text/javascript" src="/ueditor/1.4.3/ueditor.parse.min.js"> </script>
<script type="text/javascript" src="/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript" src="http://lib.h-ui.net/jquery.validation/1.14.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="http://lib.h-ui.net/jquery.validation/1.14.0/validate-methods.js"></script>
<script src="http://cdn.bootcss.com/jquery.form/3.51/jquery.form.min.js"></script>
<script type="text/javascript">
    $(function(){
        var ue = UE.getEditor('content');

        $("#form-admin-add").validate({
            onkeyup:false,
            focusCleanup:true,
            success:"valid",
            submitHandler:function(form){
                var index2 = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
                $(form).ajaxSubmit(function (data) {
                    layer.close(index2);
                    if(data['status'] == "200"){
                        layer.msg(data['msg'],function () {
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.location.reload()
                            parent.layer.close(index);
                        });
                    }else{
                        layer.msg(data['msg']);
                    }
                });
            }
        });
    });

    $(function(){
        var ue = UE.getEditor('appraisal');
    });
</script>
<script>
    var swapItems = function(arr, index1, index2) {
        arr[index1] = arr.splice(index2, 1, arr[index1])[0];
        return arr;
    };

    /**
     * 上移数组
     * @param arr
     * @param $index
     */
    var upRecord = function(arr, $index) {
        if($index == 0) {
            return;
        }
        swapItems(arr, $index, $index - 1);
    };

    /**
     * 下移数组
     * @param arr
     * @param $index
     */
    var downRecord = function(arr, $index) {
        if($index == arr.length -1) {
            return;
        }
        swapItems(arr, $index, $index + 1);
    };

    var vm = new Vue({
            el: 'body',
            data: {
            isclass:{{ isset($commodity) ? $commodity->classifyid : '0' }},
            jsonstr : '',
            inputimg : 0,
            goodsnub :0,
            carousel : [],
            goods : [],
            arr : [],
            data:{!! isset($commodity->labelid) ? $commodity->labelid : '[]' !!},
            carousel:{!! isset($commodity->carrousel) ? $commodity->carrousel : '[]' !!},
            d_carousel:{!! isset($dt->img) ? $dt->img : '[]' !!}
{{--            carousel:{{ $commodity->carrousel == NULL ?  '[]': $commodity->carrousel }},--}}
        },
        methods:{
            up:function(event){
                upRecord(this.carousel,event.$index)
            },
            down:function(event){
                downRecord(this.carousel,event.$index)
            },
            addval:function (id,event) {
                layer.prompt({
                    title: '输入标签值',
                    formType: 0 //prompt风格，支持0-2
                }, function(pass){
                    if(vm.data==null){
                        vm.data=[];
                    }
//                    alert(pass);
//                    return false;
                    vm.data.push(pass);
                    layer.msg('添加成功！');
                });
            },
            delval:function (event) {
//                console.log(event);
                var id={{$commodity->commodityid or 0}};
                $.get("/admin/commodity/ajaxDelLabel?label="+event.item+"&id="+id,function (data) {
                    vm.data.$remove(vm.data[event.$index])
                })


            },
            addimg: function (event) {
                vm.inputimg = vm.inputimg + 1;
            },
            del: function (event) {
//                console.log(event);
                var id={{$commodity->commodityid or 0}};
                $.get("/admin/commodity/ajaxDelPics?pic="+event.item+"&id="+id,function (data) {
                    vm.carousel.$remove(vm.carousel[event.$index])
                })

            },
            d_del: function (event) {
                var id={{$commodity->commodityid or 0}};
                $.get("/admin/commodity/d_ajaxDelPics?pic="+event.d_item+"&id="+id,function (data) {
                    vm.d_carousel.$remove(vm.d_carousel[event.$index])
                })

            },
         }
    })
    function doset() {
        var temparr = []
        vm.arr = []
        vm.editdata = []
        for($i= 0 ; $i<vm.data.length;$i++){
            if(vm.data[$i].val.length>0){
                temparr.push(vm.data[$i].val)
            }
        }
        var ret = doExchange(temparr);
        for (var i = 0; i < ret.length; i++) {
            vm.arr.push(ret[i].split(","))
        }
        vm.jsonstr = JSON.stringify(transform(vm.data));
    }



    /**
     * 对象转数组
     * @param obj
     * @returns {Array}
     */
    function transform(obj){
        var arr = [];
        for(var item in obj){
            arr.push(obj[item]);
        }
        return arr;
    }


    function doExchange(doubleArrays){
        var len=doubleArrays.length;
        if(len>=2){
            var len1=doubleArrays[0].length;
            var len2=doubleArrays[1].length;
            var newlen=len1*len2;
            var temp=new Array(newlen);
            var index=0;
            for(var i=0;i<len1;i++){
                for(var j=0;j<len2;j++){
                    temp[index]=doubleArrays[0][i]+","+
                        doubleArrays[1][j];
                    index++;
                }
            }
            var newArray=new Array(len-1);
            for(var i=2;i<len;i++){
                newArray[i-1]= doubleArrays[i];
            }
            newArray[0]=temp;
            return doExchange(newArray);
        }
        else{
            return doubleArrays[0];
        }
    }
</script>
</body>
</html>
