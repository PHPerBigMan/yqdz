@extends('mobile/layout', ['title' => '收货地址'])

@section('content')
    <div class="m-address">
        @foreach($address as $item)
            <div class="address-item">
                <div class="address-title">收货地址</div>
                <div class="address-wrap">
                    <div class="left-area" onclick="set_address_select({{$item->addressid}})">
                        <div class="address-head">
                            <span class="address-name">{{$item->name}}</span>
                            <span class="address-mobile">{{$item->phone}}</span>
                        </div>
                        <div class="address-content">
                            {{$item->province}} {{$item->city}} {{$item->district}} {{$item->address}}
                        </div>
                    </div>
                    <i addressid="{{$item->addressid}}"  class="icon-right-arrow"></i>
                </div>

                <div class="address-option">
                    <div class="p-checkbox">
                        <input type="checkbox" @if($item->is_default==1) checked @endif hidden id="{{'input' . $item}}" class="checkbox-input">
                        <label class="checkbox-inner" addressid="{{$item->addressid}}" for="{{'input' . $item}}"></label>
                        <span>默认地址</span>
                    </div>

                    <div class="btn-group">
                        <a href="/address/edit?id={{$id}}&money={{$money}}&num={{$num}}&addressid={{$item->addressid}}&orderid={{$orderid}}&type={{$type}}">
                            <button class="edit"><i class="icon-edit"></i>编辑</button>
                        </a>
                            <button class="delete" addressid="{{$item->addressid}}"><i class="icon-delete"></i>删除</button>

                    </div>
                </div>
            </div>
        @endforeach

        <a href="/address/new?id={{$id}}&money={{$money}}&num={{$num}}&orderid={{$orderid}}&type={{$type}}">
            <div class="new-address">新增地址</div>
        </a>
    </div>

    <script>
        $('.icon-right-arrow').click(function(){
                var addressid=$(this).attr('addressid');
                // alert(addressid);
            if({{ $type }} != 1){
                window.location.href="/address/edit?id={{$id}}&money={{$money}}&num={{$num}}&addressid="+addressid;
            }

        });
        $('.checkbox-inner').click(function(){
                var addressid=$(this).attr('addressid');
                // alert(addressid);return;
                window.location.href='/home/address/setdefault?id={{$id}}&money={{$money}}&num={{$num}}&addressid='+addressid+"&type={{$type}}";
        })
        $('.delete').click(function(){
            var addressid=$(this).attr('addressid');
//            alert(addressid);
//            return;
            layer.open({
                content: '确定要删除该收货地址吗？'
                ,btn: ['确定', '不要']
                ,yes: function(index){
                    window.location.href='/home/address/del?id={{$id}}&money={{$money}}&num={{$num}}&addressid='+addressid;
//                    location.reload();
                }
            });
        });

        function set_address_select(addressid){
            if({{ $type }} != 1){
                window.location.href='/home/address/set_address_select?id={{$id}}&money={{$money}}&num={{$num}}&addressid='+addressid+"&orderid="+{{$orderid}};
            }
        }
    </script>
@endsection