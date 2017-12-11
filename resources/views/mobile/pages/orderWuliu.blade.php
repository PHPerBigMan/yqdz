@extends('mobile/layout', ['title' => '物流查询'])

@section('content')
    <div class="m-order order-detail">
        <div class="detail-top-section">
            <h2 class="detail-section-title">物流信息</h2>

            <div class="order-item">
                <div class="order-head">
                    <div>
                        <div class="order-date">运单号:{{$order_goods->couriernumber or ''}}</div>
                        <div class="order-number">信息来源：{{$order_goods->express or ''}}</div>
                    </div>
                </div>
            </div>

        </div>
        <div class="detail-section" style="font-size: 12px;color: #999999" >
            <h2 class="detail-section-title">物流跟踪</h2>
            @foreach($wuliu as $val)
            <div class="order-logistics" style="margin-left: 20px;">
                <div class="logitics-number">{{$val['datetime']}}</div>
                <div class="logitics-number">{{$val['remark']}}</div><br>
            </div>
                @endforeach
        </div>
    </div>
<script type="text/javascript">
</script>
@endsection