<?php

namespace App\Http\Controllers\home;

use App\Models\Dividedinto;
use App\Models\Order;
use App\Models\Commodity;
use App\Models\Order_commodity_snop;
use App\Models\Order_refunds;
use App\Models\User;
use function dd;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function EasyWeChat\Payment\get_client_ip;
use Illuminate\Support\Facades\DB;
use function strtoupper;


class WxPayController extends Controller
{

    public function WxPay2(request $request)
    {
        $options = [
            // 前面的appid什么的也得保留哦
            //            'debug'  => true,

            /**
             * 账号基本信息，请从微信公众平台/开放平台获取
             */
            'app_id' => 'wxaeae5b0ab20a1524',         // AppID
            'secret' => '8f67908ced38352c197caa3e8cdc6392',     // AppSecret
            // ...
            // payment
            'payment' => [
                'merchant_id' => '1488580162',   //商户号 1488580162
                'key' => '8f67908ced38352c197caa3e8cdc6392',
                'cert_path' => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
                'key_path' => 'path/to/your/key',      // XXX: 绝对路径！！！！
                'notify_url' => 'http://yqdz.xs.sunday.so/home/WxPay/WxPay2',       // 你也可以在下单时单独设置来想覆盖它
                // 'device_info'     => '013467007045764',
                // 'sub_app_id'      => '',
                // 'sub_merchant_id' => '',
                // ...
            ],
        ];
        $app = new Application($options);
        $response = $app->payment->handleNotify(function($notify, $successful){

//                \Log::info('谢帅下的订单是否成功'.$successful);
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = Order::where('transaction',$notify->out_trade_no)->first();
            if (!$order) { // 如果订单不存在
                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }
            if ($successful) {
                // 不是已经支付状态则修改为已经支付状态
                Order::where('transaction',$notify->out_trade_no)->update(['pay_time'=>date('Y-m-d H:i:s'),'order_state'=>20]);
            } else { // 用户支付失败
                Order::where('transaction',$notify->out_trade_no)->update(['order_state'=>10]);
            }
            // 如果订单存在
            // 检查订单是否已经更新过支付状态
            if ($order->pay_time) { // 假设订单字段“支付时间”不为空代表已经支付
                return true; // 已经支付成功了就不再更新了
            }


            return true; // 返回处理完成
        });
        return $response;
    }

    public function WxPay(Request $request)
    {
        $attributes = [
            'trade_type' => 'JSAPI', // JSAPI，NATIVE，APP...
            'body' => $request->body,
            'detail' => $request->body,
            'out_trade_no' => $request->transaction,
            'total_fee' => $request->money*100, // 单位：分
            'notify_url' => 'http://yqdz.xs.sunday.so/home/WxPay/WxPay2', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'openid' => $_COOKIE['openid'], // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            // ...
        ];
//        dd($attributes);
//        $sign=strtoupper(MD5($attributes)); //注：MD5签名方式
        $options = [
            // 前面的appid什么的也得保留哦
//            'debug'  => true,

            /**
             * 账号基本信息，请从微信公众平台/开放平台获取
             */
            'app_id' => 'wxaeae5b0ab20a1524',         // AppID
            'secret' => '8f67908ced38352c197caa3e8cdc6392',     // AppSecret
            // ...
            // payment
            'payment' => [
                'merchant_id' => '1488580162',   //商户号 1488580162
                'key' => '8f67908ced38352c197caa3e8cdc6392',
                'cert_path' => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
                'key_path' => 'path/to/your/key',      // XXX: 绝对路径！！！！
                'notify_url' => 'http://yqdz.xs.sunday.so/home/WxPay/WxPay2',       // 你也可以在下单时单独设置来想覆盖它
                // 'device_info'     => '013467007045764',
                // 'sub_app_id'      => '',
                // 'sub_merchant_id' => '',
                // ...
            ],
        ];

        $order = new \EasyWeChat\Payment\Order($attributes);
//        dd($order);
        $app = new Application($options);
//        dd($app);
        $payment = $app->payment;
//        dd($payment);
        $result = $payment->prepare($order);
//        dd($result);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {
            $prepayId = $result->prepay_id;
            $json = $payment->configForPayment($prepayId);
            $arr=json_decode($json,true);
            return $arr;
        }
    }
    public function getOrder(Request $request){
        $options = [
            // 前面的appid什么的也得保留哦
//            'debug'  => true,

            /**
             * 账号基本信息，请从微信公众平台/开放平台获取
             */
            'app_id' => 'wxaeae5b0ab20a1524',         // AppID
            'secret' => '8f67908ced38352c197caa3e8cdc6392',     // AppSecret
            // ...
            // payment
            'payment' => [
                'merchant_id' => '1488580162',   //商户号 1488580162
                'key' => '8f67908ced38352c197caa3e8cdc6392',
                'cert_path' => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
                'key_path' => 'path/to/your/key',      // XXX: 绝对路径！！！！
                'notify_url' => 'http://yqdz.xs.sunday.so/home/WxPay/WxPay2',       // 你也可以在下单时单独设置来想覆盖它
                // 'device_info'     => '013467007045764',
                // 'sub_app_id'      => '',
                // 'sub_merchant_id' => '',
                // ...
            ],
        ];
        $app = new Application($options);
//        dd($app);
        $payment = $app->payment;
        $orderNo = "15080730766115668000";
        $orderInfo=$payment->query($orderNo);
        dd($orderInfo);
    }
    //微信提现接口
    public function withDraw(Request $request){
//        $options = [
//            // 前面的appid什么的也得保留哦
////            'debug'  => true,
//
//            /**
//             * 账号基本信息，请从微信公众平台/开放平台获取
//             */
//            'app_id' => 'wxaeae5b0ab20a1524',         // AppID
//            'secret' => '8f67908ced38352c197caa3e8cdc6392',     // AppSecret
//            'payment' => [
//                'merchant_id' => '1488580162',   //商户号 1488580162
//                'key' => '8f67908ced38352c197caa3e8cdc6392',
//                'cert_path' => base_path()."/libs/cert/apiclient_cert.pem", // XXX: 绝对路径！！！！
//                'key_path' => base_path()."/libs/cert/apiclient_key.pem",      // XXX: 绝对路径！！！！
//                'notify_url' => 'http://http://yqdz.xs.sunday.so/home/WxPay/WxPay3',       // 你也可以在下单时单独设置来想覆盖它
//                // 'device_info'     => '013467007045764',
//                // 'sub_app_id'      => '',
//                // 'sub_merchant_id' => '',
//                // ...
//            ],
//        ];
//        $app = new Application($options);
//        $ip= get_client_ip();
//        $merchantPay = $app->merchant_pay;
////        dd($merchantPay);
//        $merchantPayData = [
//            'partner_trade_no' => str_random(16), //随机字符串作为订单号，跟红包和支付一个概念。
//            'openid' => $_COOKIE['openid'], //收款人的openid
//            'check_name' => 'NO_CHECK',  //文档中有三种校验实名的方法 NO_CHECK OPTION_CHECK FORCE_CHECK
//            're_user_name'=>'张三',     //OPTION_CHECK FORCE_CHECK 校验实名的时候必须提交
//            'amount' => 100,  //单位为分
//            'desc' => '奖励金提现',
//            'spbill_create_ip' => $ip,  //发起交易的IP地址
//        ];
//        $result = $merchantPay->send($merchantPayData);
//        dd($result);
        return response()->json(['status'=>404,'msg'=>"提现功能目前尚未开通,敬请期待"]);
//
    }
}
//    public function WxPay(){
//
//    }
//}
