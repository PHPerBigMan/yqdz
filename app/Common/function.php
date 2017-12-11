<?php
use EasyWeChat\Foundation\Application;

/**
 * Created by PhpStorm.
 * User: baimifan-pc
 * Date: 2017/11/15
 * Time: 11:54
 */

function sendmessage($opendId="",$dataMsg="",$templateId="",$type=0){
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
        ],
    ];
    $app = new Application($options);
    $notice = $app->notice;
    $userId = $opendId;
    $templateId  = $templateId;
    if($type){
        $url = 'http://yqdz.xs.sunday.so/order-list';
        $data = array(
            "first"  => "您好，您的订单".$dataMsg['transaction'].",已退款",
            "reason"   => "退款",
            "refund"  => $dataMsg['money'],
//            "remark" => "",
        );
    }else{

        $url = 'http://yqdz.xs.sunday.so/order-list/30';
        $data = array(
            "first"  => "亲，宝贝已经启程了，好想快点来到你身边",
            "delivername"   => $dataMsg['express'],
            "ordername"  => $dataMsg['couriernumber'],
            "remark" => "商品信息：".$dataMsg['title'],
        );

    }

    $result = $notice->uses($templateId)->withUrl($url)->andData($data)->andReceiver($userId)->send();

}