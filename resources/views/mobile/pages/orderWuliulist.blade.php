@extends('mobile/layout', ['title' => '物流查询'])

@section('content')
    <div class="m-order order-detail">
        <div class="detail-top-section">
            <h2 class="detail-section-title">已发货产品</h2>

            <div class="order-item">
                <div class="order-head">
                    <div>
                        @foreach($orderlist as $v)
                            <div class="order-goods" onclick="href({{ $v->snopid }})">
                                <div class="left-area">
                                    <img src="{{ $v->snopjson->thumbnail }}" alt="" class="goods-img">
                                    <div class="goods-name" style="width: 7.266667rem">{{ $v->snopjson->title }}</div>
                                </div>
                            </div>
                            @endforeach
                    </div>
                </div>
            </div>

        </div>

    </div>
<script type="text/javascript">
    function href(snopid) {
       location.href = "/wuliu?id="+snopid;
    }
</script>
@endsection