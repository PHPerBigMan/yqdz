@extends('mobile/layout', ['title' => '发起定制'])

@section('content')
    <div class="m-order-submit">
        <div class="submit-section">
            <div class="p-select-container">
                @foreach($customCate as $val)
                <div class="select-label" cateid="{{$val->classifyid}}">{{$val->name}}</div>
                @endforeach
                <div class="select-label" cateid="0" id="other">其他</div>
            </div>
            <div class="input-area">
                <input class="user-input" id="input" placeholder="自定义类别">
                <input type="hidden" name="_token" value="{csrf_token()}"/>
            </div>
        </div>

        <hr class="section-hr">

        <div class="submit-section">
            <h2 class="section-title">告诉我们您的需求</h2>
            <input type="text" style="width: 100%" placeholder="请输入您的定制标题" name="title" id="title">
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <div class="weui-uploader">
                            <div class="weui-uploader__hd">
                                <p class="weui-uploader__title">图片上传</p>
                                <div class="weui-uploader__info">0/6</div>
                            </div>
                            <div class="weui-uploader__bd">
                                <ul class="weui-uploader__files" id="uploaderFiles">
                                </ul>
                                <div class="weui-uploader__input-box">
                                    <input id="uploaderInput" class="weui-uploader__input" type="file" accept="image/*" multiple="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="images_urls" id="images_urls">
            <input type="text" style="width: 100%" placeholder="请输入您的联系电话" name="phone">
            <textarea style="margin-top: 20px" class="user-textarea" id="textarea" placeholder="请描述您的需求"></textarea>
        </div>

        <div class="p-fb-btn" id="submit">发布</div>
    </div>

    <script>
        $(function() {
            // 选择标签
            $('.select-label').click(function () {
                $('.select-label').removeClass('active');
                $(this).addClass('active');

                if ($(this).is('#other')) {
                    $('#input').val('').slideDown(100);
                } else {
                    $('#input').slideUp(100);
                }
            });

            // 提交
            $('#submit').click(function () {
                var cate = $('.select-label.active').attr('cateid');
//                alert(cate);
                var content = $('#textarea').val().trim();
                var phone=$("input[name='phone']").val();
                var imgs = $('#images_urls').val();
                var title = $('#title').val();
                if(!phone.match(/^(((13[0-9]{1})|(14[0-9]{1})|(17[0]{1})|(15[0-3]{1})|(15[5-9]{1})|(18[0-9]{1}))+\d{8})$/)){
                    layer.open({
                        content: '手机号码格式不正确'
                        ,btn: '我知道了'
                    });
                    return false;
                }
                if (cate == 0) {
                    cate = $('#input').val();
//                    alert(cate);
                }

                if (cate && content) {
                    var url='/home/design/custom';
                    var data={cate:cate,content:content,phone:phone,imgs:imgs,title:title};
                    $.post(url,data,function(res){
                        if (res.status==200) {
                            layer.open({content: res['msg'],btn: '好的！',yes:function (index) {
                                location.reload();
                            }})

                        }else{
                            layer.open({content: res['msg'],btn: '好的！',yes:function (index) {
                                location.reload();
                            }})
                        }
                    })
                    
                } else {
                    weui.alert('请完善信息！');
                }
            });


            var imgs = "";
            // 允许上传的图片类型
            var allowTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
            // 1024KB，也就是 1MB
            var maxSize = 2048 * 2048;
            // 图片最大宽度
            var maxWidth = 10000;
            // 最大上传图片数量
            var maxCount = 6;
            $('#uploaderInput').on('change', function (event) {
                var files = event.target.files;
                //console.log(files);return false;
                // 如果没有选中文件，直接返回
                if (files.length === 0) {
                    return;
                }

                for (var i = 0, len = files.length; i < len; i++) {
                    var file = files[i];
                    var reader = new FileReader();

                    // 如果类型不在允许的类型范围内
                    if (allowTypes.indexOf(file.type) === -1) {

                        $.alert("该类型不允许上传！", "警告！");
                        continue;
                    }

                    if (file.size > maxSize) {
                        //$.weui.alert({text: '图片太大，不允许上传'});
                        $.alert("图片太大，不允许上传", "警告！");
                        continue;
                    }

                    if ($('.weui-uploader__file').length >= maxCount) {
                        $.weui.alert({text: '最多只能上传' + maxCount + '张图片'});
                        return;
                    }
                    reader.readAsDataURL(file);
                    reader.onload = function (e) {
                        //console.log(e);
                        var img = new Image();
                        img.src = e.target.result;
                        img.onload = function () {
                            // 不要超出最大宽度
                            var w = Math.min(maxWidth, img.width);
                            // 高度按比例计算
                            var h = img.height * (w / img.width);
                            var canvas = document.createElement('canvas');
                            var ctx = canvas.getContext('2d');
                            // 设置 canvas 的宽度和高度
                            canvas.width = w;
                            canvas.height = h;
                            ctx.drawImage(img, 0, 0, w, h);

                            var base64 = canvas.toDataURL('image/jpeg',0.8);
                            //console.log(base64);
                            // 插入到预览区
                            var $preview = $('<li class="weui-uploader__file weui-uploader__file_status" style="background-image:url(' + img.src + ')"><div class="weui-uploader__file-content">0%</div></li>');
                            $('#uploaderFiles').append($preview);
                            var num = $('.weui-uploader__file').length;
                            $('.weui-uploader__info').text(num + '/' + maxCount);


                            var formData = new FormData();

                            formData.append("images", base64);
                            //console.log(img.src);
                            $.ajax({

                                url: "/savetofile",

                                type: 'POST',

                                data: formData,

                                contentType:false,

                                processData:false,

                                success: function(data){


                                    $preview.removeClass('weui-uploader__file_status');

                                    // 将图片存储在 input hidden 中
                                    imgs = imgs +","+data;
                                    $('#images_urls').val(imgs);
                                },
                                error: function(xhr, type){
                                    alert('Ajax error!')
                                }
                            });

                        };
                    };
                }
            });
        });
    </script>
@endsection