<?php

namespace App\Http\Controllers\admin;

use App\Models\Order;
use App\Models\User_msg;
use App\Models\User_msglist;
use App\Models\Order_commodity_snop;
use App\Models\Order_return_goods;
use App\User;
use function explode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat\Foundation\Application;

class OrderReturnGoodsController extends Controller
{
    public function lists(Request $request){
        $start = $request->start ? $request->start : date('Y-m-01 H:i:s', strtotime(date("Y-m-d")));
        $end = $request->end ? $request->end : date('Y-m-d H:i:s', strtotime("$start +1 month -1 day -1seconds"));
        if ($request->uniqueid != ""){
            $orderid=Order::where('uniqueid',$request->uniqueid)->value('orderid');
            $data=Order_return_goods::where('orderid',$orderid)->orderBy('created_at', 'desc')->paginate(10);
        }else{
            $data=Order_return_goods::where('created_at','>',$start)->where('created_at','<',$end)->orderBy('created_at', 'desc')->paginate(10);
        }
//        dd($data);
        return view('admin.orderreturngoods.lists',['start'=>$start,'end'=>$end,'data'=>$data]);
    }

    public function detail(Request $request){
        $data=Order_return_goods::where('returnid',$request->id)->with('user')->first()->toArray();
//        dd($data);
        $snopid=explode(',',$data['snopid']);

        $snop=Order_commodity_snop::whereIn('snopid',$snopid)->get()->toArray();
        foreach ($snop as $key => $val) {
            $data['snop']['snopjson'][]=json_decode($val['snopjson']);
            $nums[]=$val['nums'];
        }
//        dd($data);
//        $data['snop']['snopjson']=json_decode($data['snop']['snopjson']);
        return view('admin.orderreturngoods.detail',['data'=>$data,'nums'=>$nums]);
    }

    public function shouhuo(Request $request){
        /************************************微信退款接口******************************************/
//        dd(132456);
        $data=Order_return_goods::where('returnid',$request->id)->select('status','snopid','orderid','money','uid')->first();
        $order=Order::where('orderid',$data['orderid'])->first();
        $options = [
            // 前面的appid什么的也得保留哦
//            'debug'  => true,

            /**
             * 账号基本信息，请从微信公众平台/开放平台获取
             */
            'app_id' => 'wxaeae5b0ab20a1524',         // AppID
            'secret' => '8f67908ced38352c197caa3e8cdc6392',     // AppSecret
            'payment' => [
                'merchant_id' => '1488580162',   //商户号 1488580162
                'key' => '8f67908ced38352c197caa3e8cdc6392',
                'cert_path' => base_path()."/libs/cert/apiclient_cert.pem", // XXX: 绝对路径！！！！
                'key_path' => base_path()."/libs/cert/apiclient_key.pem",      // XXX: 绝对路径！！！！
                'notify_url' => 'http://http://yqdz.xs.sunday.so/home/WxPay/WxPay3',       // 你也可以在下单时单独设置来想覆盖它
                // 'device_info'     => '013467007045764',
                // 'sub_app_id'      => '',
                // 'sub_merchant_id' => '',
                // ...
            ],
        ];
//        dd($options);
        $app = new Application($options);
//        dd($app);
        $payment = $app->payment;
//        dd($order->transaction);
        $refundNo=time().rand(11111,99999);
        $result = $payment->refund($order->transaction, $refundNo,$order->money*100, $data->money*100); // 总金额 100 退款 100，操作员：商户号
//        dd($result);
        /************************************微信退款接口结束******************************************/
        if(empty($data) || $data['status'] != 4){
            return response()->json(['status' => '404', 'msg' =>'数据异常!',]);
        }
        if (Order_return_goods::where('returnid',$request->id)->update(['status'=>5])){
            if(Order_commodity_snop::where('snopid',$data['snopid'])->update(['is_refunds'=>5])){
                if(Order::where('orderid',$data['orderid'])->update(['order_state'=>70,'endtime'=>date("Y-m-d H:i:s"),'refund_amount'=>$data['money']])){
                    $msg_id=User_msg::where(array('type'=>1,'result'=>1))->value('msg_id');
//                    dd($msg_id);
                    User_msglist::create([
                        'uid'=>$order->uid,
                        'msgid'=>$msg_id
                    ]);
                    // 退款成功 发送模板消息
                    // 获取退款用户 openId
                    $orderdata = Order::with('user')->where('orderid',$data['orderid'])->first()->toArray();
                    $openId = $orderdata['user']['openid'];
                    $dataMsg = [
                        'transaction'=>$orderdata['transaction'],
                        'money'=>$orderdata['money']
                    ];
                    $templateId = "yjvhntrfF81dqgwtjCYia7ewROD6tJfgnSpr9EB9zeg";
                    sendmessage($openId,$dataMsg,$templateId,1);
//                    A('Home/weixin')->refund($order['transaction'],date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8),$money,$money);
                    return response()->json(['status' => '200', 'msg' =>'操作成功,系统将会在20分钟内给顾客退款',]);
                }else{
                    $msg_id=User_msg::where(array('type'=>1,'result'=>2))->value('msg_id');
                    User_msglist::create([
                        'uid'=>$order->uid,
                        'msgid'=>$msg_id
                    ]);
                    return response()->json(['status' => '404', 'msg' =>'操作失败!',]);
                }
            }else{
                $msg_id=User_msg::where(array('type'=>1,'result'=>2))->value('msg_id');
                User_msglist::create([
                    'uid'=>$order->uid,
                    'msgid'=>$msg_id
                ]);
                return response()->json(['status' => '404', 'msg' =>'操作失败!',]);
            }
        }else{
            $msg_id=User_msg::where(array('type'=>1,'result'=>2))->value('msg_id');
            User_msglist::create([
                'uid'=>$order->uid,
                'msgid'=>$msg_id
            ]);
            return response()->json(['status' => '404', 'msg' =>'操作失败!',]);
        }
    }

    public function agree(Request $request){
        $data=Order_return_goods::where('returnid',$request->id)->select('status','snopid')->first();
        if (empty($data) || $data['status'] != 1){
            return response()->json(['status' => '404', 'msg' =>'数据异常!',]);
        }

        if(Order_commodity_snop::where('snopid',$data['snopid'])->update(['is_refunds'=>4])){
            if (Order_return_goods::where('returnid',$request->id)->update(['status'=>4])){

                return response()->json(['status' => '200', 'msg' =>'操作成功!',]);
            }else{

                return response()->json(['status' => '404', 'msg' =>'操作失败!',]);
            }
        }else{
            return response()->json(['status' => '404', 'msg' =>'操作失败!',]);
        }
    }

    public function refuse(Request $request){

        $data=Order_return_goods::where('returnid',$request->id)->select('status','snopid','orderid')->first();
        $order=Order::where('orderid',$data['orderid'])->first();
        if (empty($data) || $data['status'] != 1){
            return response()->json(['status' => '404', 'msg' =>'数据异常!',]);
        }

        if(Order_commodity_snop::where('snopid',$data['snopid'])->update(['is_refunds'=>3])){
            if (Order_return_goods::where('returnid',$request->id)->update(['status'=>3])){
                Order::where('orderid',$data['orderid'])->update(['return_status'=>0]);
                Order::where('orderid',$data['orderid'])->update(['order_state'=>80]);
                $msg_id=User_msg::where(array('type'=>1,'result'=>2))->value('msg_id');
                User_msglist::create([
                    'uid'=>$order->uid,
                    'msgid'=>$msg_id
                ]);
                return response()->json(['status' => '200', 'msg' =>'操作成功!',]);
            }else{
                return response()->json(['status' => '404', 'msg' =>'操作失败!',]);
            }
        }else{
            return response()->json(['status' => '404', 'msg' =>'操作失败!',]);
        }
    }
}
