@extends('mobile/layout')

@section('content')
    <div class="m-message">
        @foreach($message as $item)
            <a href="/message/{{$item->msg_id}}">
                <div class="message-item">
                    <div class="message-content">
                        {{$item->title}}
                    </div>
                    @if($item->is_view==0)
                    <div style=" width:25px; height:25px; background-color:#f74c31; border-radius:25px;margin-left: 90%;margin-top: -5%">
                        <span style="height:25px; line-height:25px; display:block; color:#FFF; text-align:center;font-size: 10px">未读</span>
                    </div>
                    @endif
                    <br>
                    <div class="message-date">{{$item->create_time}}</div>

                </div>

            </a>

        @endforeach
    </div>
@endsection