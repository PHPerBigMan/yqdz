@extends('mobile/layout', ['title' => '定制详情'])

@section('content')
    <div class="m-fund-detail">
        <div class="detail-title">{{$data->title or ''}}</div>
        <div class="detail-info">
            <div class="detail-author">{{$data->nickname or ''}}</div>
            <div class="detail-count"><span class="red">{{$data->hotes or ''}}</span>人支持</div>
            <div class="detail-date">{{$data->created_at or ''}}</div>
        </div>
        <div class="detail-content">{!! $data->backcontent or '' !!}</div>

        {{--<button class="p-fb-btn" style="text-align: center;"><center>我要支持</center></button>--}}
        <button class="fund" style="
                    width: 100%;
                    height: 1.33333333rem;
                    background: #FF6804;
                    font-size: 0.48rem;
                    color: #fff;    z-index: 10;
                    position: fixed;
                    bottom: 0;
                    left: 50%;
                    width: 100%;
                    max-width: 750px;
                    -webkit-transform: translateX(-50%);
    transform: translateX(-50%);">我要支持</button>
    </div>
<script type="text/javascript">
    $('.fund').click(function(){
        var url='/home/design/hotes';
        var data={designid:{{$data->designid}}}
        $.get(url,data,function(res){
            if (res.status==200) {
                layer.open({
                        content: res['msg']
                        ,btn: '我知道了'
                        ,yes: function(){
                            window.location.reload()
                        }
                    });
            }
        })

    });
</script>
@endsection