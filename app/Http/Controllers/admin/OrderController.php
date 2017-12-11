<?php

namespace App\Http\Controllers\admin;

use App\Models\Address;
use App\Models\Carriage;
use App\Models\Classify;
use App\Models\Commodity;
use App\Models\Express;
use App\Models\Order;
use App\Models\Order_commodity_snop;
use App\Models\Order_extend;
use App\Models\User;
use App\Models\User_msg;
use App\Models\User_msglist;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PHPExcel;
use PHPExcel_Writer_Excel5;

class OrderController extends Controller
{
    public function lists(Request $request){
        $page=$request->page;
        if(empty($page)){
            $page=1;
        }

        $start = $request->start ? $request->start : date('Y-m-01 H:i:s', strtotime(date("Y-m-d")));
        $end = $request->end ? $request->end : date('Y-m-d H:i:s', strtotime("$start +1 month -1 day -1seconds"));
//        dump($request->status);
        if($request->type != "2" || $request->type == null){

            if($request->transaction != "" && $request->status == 130 ){
                // 订单号查询
                $data=Order::where('transaction',$request->transaction)->with('snop')->orderBy('orderid', 'desc')->paginate(10);
            }else if($request->transaction != "" && ($request->status == 100)){
                // 筛选用户的昵称
                $userID = User::where('nickname','like',"%".$request->transaction."%")->select('uid')->get();
                if(!$userID->isEmpty()){
                    $arrayUserId = [];
                    foreach($userID as $k=>$v){
                        $arrayUserId[$k] = $v->uid;
                    }
                    $data = Order::whereIn('uid',$arrayUserId)->with('snop')->with('user')->orderBy('orderid', 'desc')->paginate(10);
                }else{
                    // 如果没有找到 订单则 uid使用0 返回空
                    $data = Order::whereIn('uid',[0])->with('snop')->with('user')->orderBy('orderid', 'desc')->paginate(10);
                }
            }else if($request->transaction != "" && ($request->status == 110)){

                // 筛选商品的名称  先查询数据
                $CommidyId = Commodity::where('title','like',"%".$request->transaction."%")->select('commodityid')->get();

                if(!$CommidyId->isEmpty()){
                    $arrayCommidyId = [];
                    foreach($CommidyId as $k=>$v){
                        // 将查询的商品id数据放在空数组中
                        $arrayCommidyId[$k] = $v->commodityid;
                    }

                    // 查询所有的订单
                    $AllOrderData = Order_commodity_snop::select('snopjson','orderid')->get();
                    if(!$AllOrderData->isEmpty()){
                        $AllOrderDataList = [];
                        foreach($AllOrderData as $k=>$v){
                            // 将查询到的结果 放置于一个空数组中
                            $commodityid  = json_decode($v->snopjson);
                            $AllOrderDataList[$k]['commodityid'] = $commodityid->commodityid;
                            $AllOrderDataList[$k]['orderid'] = $v->orderid;
                        }

                        $GetAllDataList = [];
                        foreach($AllOrderDataList as $k=>$v){
                            // 判断是否存在关键词查询到的数据
                            if(in_array($v['commodityid'],$arrayCommidyId)){
                                $GetAllDataList[$k] = $v['orderid'];
                            }
                        }

                        if(empty($GetAllDataList)){
                            // 没有查询到匹配的数据
                            $data = Order::whereIn('uid',[0])->with('snop')->with('user')->orderBy('orderid', 'desc')->paginate(10);
                        }else{
                            $data = Order::whereIn('orderid',$GetAllDataList)->with('snop')->with('user')->orderBy('orderid', 'desc')->paginate(10);
                        }
                    }else{
                        // 没有订单
                        $data = Order::whereIn('orderid',[0])->with('snop')->with('user')->orderBy('orderid', 'desc')->paginate(10);
                    }

                }else{
                    // 如果没有找到 订单则 uid使用0 返回空
                    $data = Order::whereIn('uid',[0])->with('snop')->with('user')->orderBy('orderid', 'desc')->paginate(10);
                }
            }else if($request->transaction != "" && ($request->status == 120)){

                // 商品分类查询
                $classify = Classify::where('name','like',"%".$request->transaction."%")->get();
                if(!$classify->isEmpty()) {
                    $classifyId = [];
                    foreach ($classify as $k => $v) {
                        $classifyId[$k] = $v->classifyid;
                    }
                    $CommidyId = Commodity::whereIn('classifyid', $classifyId)->select('commodityid')->get();
                    $arrayCommidyId = [];
                    foreach ($CommidyId as $k => $v) {
                        // 将查询的商品id数据放在空数组中
                        $arrayCommidyId[$k] = $v->commodityid;
                    }

                    // 查询所有的订单
                    $AllOrderData = Order_commodity_snop::select('snopjson', 'orderid')->get();
                    if (!$AllOrderData->isEmpty()) {
                        $AllOrderDataList = [];
                        foreach ($AllOrderData as $k => $v) {
                            // 将查询到的结果 放置于一个空数组中
                            $commodityid = json_decode($v->snopjson);
                            $AllOrderDataList[$k]['commodityid'] = $commodityid->commodityid;
                            $AllOrderDataList[$k]['orderid'] = $v->orderid;
                        }

                        $GetAllDataList = [];
                        foreach ($AllOrderDataList as $k => $v) {
                            // 判断是否存在关键词查询到的数据
                            if (in_array($v['commodityid'], $arrayCommidyId)) {
                                $GetAllDataList[$k] = $v['orderid'];
                            }
                        }

                        if (empty($GetAllDataList)) {
                            // 没有查询到匹配的数据
                            $data = Order::whereIn('uid', [0])->with('snop')->with('user')->orderBy('orderid', 'desc')->paginate(10);
                        } else {
                            $data = Order::whereIn('orderid', $GetAllDataList)->with('snop')->with('user')->orderBy('orderid', 'desc')->paginate(10);
                        }
                    }
                }else {

                    // 如果没有找到 订单则 uid使用0 返回空
                    $data = Order::whereIn('uid', [0])->with('snop')->with('user')->orderBy('orderid', 'desc')->paginate(10);
                }

            }elseif($request->status != "0" && $request->status != null){

                $data=Order::where('order_state',$request->status)->with('snop')->orderBy('orderid', 'desc')->paginate(10);

            }else{
//                dump(3);
                $data=Order::where('created_at','>',$start)->with('snop')->where('created_at','<',$end)->orderBy('orderid', 'desc')->paginate(10);
//                dd($data);
            }
        }
//        dd($data);
        foreach ($data as $key => $val) {
            foreach($val['snop'] as $k=>$v){
                $data[$key]['snop'][$k]['snopjson']=json_decode($v['snopjson']);
                $data[$key]['snop'][$k]['expressed']=  0;
                $data[$key]['snop'][$k]['expressed']=  Order_extend::where([
                    'snopid'=>$v['snopid'],
                    'orderid'=>$v['orderid']
                ])->where('extendid','>',214)->value('extendid');
                if(!$data[$key]['snop'][$k]['expressed']){
                    $data[$key]['snop'][$k]['expressed'] = Order_extend::where('orderid',$v['orderid'])->where('extendid','<',214)->value('extendid');
                }
            }
//            dump($val->snop);
        }
//        foreach ($data as $key => $val) {
//            foreach($val['snop'] as $k=>$v){
//                $data[$key]['snop'][$k]['snopjson']=json_decode($v['snopjson']);
//            }
////            dump($val->snop);
//        }
//        dump($data);
//        dd($data);
        return view('admin.order.lists',['start'=>$start,'end'=>$end,'data'=>$data,'status'=>$request->status,'transaction'=>$request->transaction,'page'=>$page]);
    }
    /**
     * @param Request $request
     * @param $type
     * @return mixed
     * author hongwenyang
     * method description : 根据微信昵称/默认排序
     */
    public function listsOrder(Request $request,$type){
        $page=$request->page;
        if(empty($page)){
            $page=1;
        }
        $start = $request->start ? $request->start : date('Y-m-01 H:i:s', strtotime(date("Y-m-d")));
        $end = $request->end ? $request->end : date('Y-m-d H:i:s', strtotime("$start +1 month -1 day -1seconds"));
        if(!$type){
            // 根据微信排序
            $data=Order::with('snop')->orderBy('uid', 'asc')->paginate(10);

        }else if($type == 1){
            // 按时间排序
            $data=Order::with('snop')->orderBy('orderid', 'desc')->paginate(10);
        }

        foreach ($data as $key => $val) {
            foreach($val['snop'] as $k=>$v){
                $data[$key]['snop'][$k]['snopjson']=json_decode($v['snopjson']);
                $data[$key]['snop'][$k]['expressed']=  0;
                $data[$key]['snop'][$k]['expressed']=  Order_extend::where([
                    'snopid'=>$v['snopid'],
                    'orderid'=>$v['orderid']
                ])->where('extendid','>',214)->value('extendid');
                if(!$data[$key]['snop'][$k]['expressed']){
                    $data[$key]['snop'][$k]['expressed'] = Order_extend::where('orderid',$v['orderid'])->where('extendid','<',214)->value('extendid');
                }
            }
//            dump($val->snop);
        }

        return view('admin.order.lists',['start'=>$start,'end'=>$end,'data'=>$data,'status'=>$request->status,'transaction'=>$request->transaction,'page'=>$page]);
    }
    //订单详情
    public function detail(Request $request){
        $orderId = Order_commodity_snop::where('snopid',$request->id)->value('orderid');

        $order=Order::where('orderid',$orderId)->first();


        $data = Order::where('orderid', $orderId)->select('uniqueid', 'order_state', 'addressid', 'orderid', 'money', 'carriage',
            'evaluation_state', 'refund_state', 'return_status')->orderBy('created_at', 'desc')->get()->toArray();

        foreach ($data as $key => $val) {

            $data[$key]['user']=User::where('uid',$order->uid)->first();

            $address=$order->address_json;

            $address=json_decode($address);

            $data[$key]['address']=$address;

            $data[$key]['extend']=Order_extend::where('snopid',$request->id)->first();

            if(!$data[$key]['extend'] && $orderId<=498){
                // 因为后面更改过发货的逻辑，所以针对老订单 进行查询数据的处理
                $data[$key]['extend']=Order_extend::where('orderid',$orderId)->first();
            }

            $data[$key]['snop']= Order_commodity_snop::where('snopid',$request->id)->first();
            $data[$key]['snop']->snopjson= json_decode($data[$key]['snop']->snopjson);
//            dump($val->snop);
        }
//        dd($data);
        return view('admin.order.detail',['data'=>$data[0],'order'=>$order]);
    }


    //取消订单
    public function cancelorder(Request $request){
        $data=Order::where('orderid',$request->id)->update(['order_state'=>0]);
        if ($data){
            return response()->json(['status' => '200', 'msg' =>'取消订单成功!',]);
        }else{
            return response()->json(['status' => '200', 'msg' =>'取消订单失败!',]);
        }
    }

    //发货
//    public function fahuo(Request $request){
//        $data=Order::where('orderid',$request->id)->first();
////        dd($data);
//        $express = Express::get();
//        $data->address=$data->address_json;
//        $data->address=json_decode($data->address);
//        $data->extend=Order_extend::where('orderid',$request->id)->first();
//        return view('admin.order.fahuo',['data'=>$data,'express'=>$express]);
//    }

    public function fahuo(Request $request){

        // snopid
        $snopid = Order_commodity_snop::where('snopid',$request->id)->value('orderid');

        $data=Order::where('orderid',$snopid)->first();

        $data->address=$data->address_json;
        $data->address=json_decode($data->address);
        $data->orderid=$request->id;
        // 第一个 extend 针对的是 一订单多产品的发货
        $data->extend=Order_extend::where([
            'snopid'=>$request->id,
            'orderid'=>$snopid,
        ])->first();
        // 因为 发货逻辑更改，所以老订单的发货在 if判断内
        if(empty($data->extend)){
            $data->extend=Order_extend::where([
                'orderid'=>$snopid,
            ])->where('created_at','<','2017-11-16 12:41:05')->first();
        }
        $express = Express::get();
        return view('admin.order.fahuo',['data'=>$data,'express'=>$express]);
    }
    //发货方法
    public function handle(Request $request){
        $time=date("Y-m-d H:i:s");
//        dd($request->extendid);
        $orderId = Order_commodity_snop::where([
            'snopid'=>$request->orderid,
            'is_refunds'=>0
        ])->value('orderid');
        if (empty($request->extendid)){

            $list=Order_extend::create([
                'snopid'=>$request->orderid,
                'orderid'=>$orderId,
                'express'=>$request->express,
                'couriernumber'=>$request->couriernumber,
                'addjson'=>$request->addjson,
                'created_at'=>$time
            ]);
            //商品标题
            $title = json_decode(Order_commodity_snop::where([
                'snopid'=>$request->orderid,
                'is_refunds'=>0
            ])->value('snopjson'));
            //下单用户 openId
            $openId = Order::with('user')->where([
                'orderid'=>$orderId
            ])->first()->toArray();
            // 进行公众号模板消息的操作
            $dataMsg = [
                'express'=>$request->express,
                'couriernumber'=>$request->couriernumber,
                'title'=>$title->title
            ];
            if ($list){
                $isexpress = Order::where('orderid',$orderId)->value('order_state');
                if($isexpress == 30){
                    $res = 1;
                }else{
                    $res=Order::where('orderid',$orderId)->update(['order_state'=>30]);
                    //判断 产品是否全部发出
                    $isAllExtend = Order_commodity_snop::where('orderid',$orderId)->where('is_extend','=',0)->value('snopid');

                    if($isAllExtend){
                        //如果全部发出则改变订单状态
                        $res=Order::where('orderid',$orderId)->update(['order_state'=>20]);
                        //在进行一次判断 是否全部发出

                    }else{
                        $res = 1;
                    }
                }
                $order=Order::where('orderid',$orderId)->first();
                if($res){
                    Order_commodity_snop::where('snopid',$request->orderid)->update(['is_extend'=>1]);
                    $isAllExtend_1 = Order_commodity_snop::where('orderid',$orderId)->where('is_extend','=',0)->value('snopid');
                    if($isAllExtend_1){
                        Order::where('orderid',$orderId)->update(['order_state'=>20]);
                    }else{
                        Order::where('orderid',$orderId)->update(['order_state'=>30]);
                    }
                    $msg_id=User_msg::where(array('type'=>2,'result'=>1))->value('msg_id');
                    //发货成功 模板模板消息
                    $templateId = "mTPcZEKRhz7MpEKd1OPkaNe5ZoNbGc_xxKQDMiMg0I4";
                    $openId = $openId['user']['openid'];
                    sendmessage($openId,$dataMsg,$templateId,0);
                    User_msglist::create([
                        'uid'=>$order->uid,
                        'msgid'=>$msg_id
                    ]);
                    return response()->json(['status' => '200', 'msg' =>'发货成功!',]);
                }else{
                    $msg_id=User_msg::where(array('type'=>2,'result'=>2))->value('msg_id');
                    User_msglist::create([
                        'uid'=>$order->uid,
                        'msgid'=>$msg_id
                    ]);
                    return response()->json(['status' => '404', 'msg' =>'发货失败!',]);
                }
            }else{
                $msg_id=User_msg::where(array('type'=>2,'result'=>2))->value('msg_id');
                User_msglist::create([
                    'uid'=>$order->uid,
                    'msgid'=>$msg_id
                ]);
                return response()->json(['status' => '404', 'msg' =>'发货失败!',]);
            }
        }else{
            $list=Order_extend::where('extendid',$request->extendid)->update(['express'=>$request->express,'couriernumber'=>$request->couriernumber]);
            if ($list !==false){
                $res=Order::where('orderid',$request->orderid)->update(['order_state'=>30]);
                if($res !==false){
                    return response()->json(['status' => '200', 'msg' =>'修改发货成功!',]);
                }else{
                    return response()->json(['status' => '404', 'msg' =>'修改发货失败!',]);
                }
            }else{
                return response()->json(['status' => '404', 'msg' =>'修改发货失败!',]);
            }
        }
    }
    //导出订单
    public function export(Request $request){

        $excel = new PHPExcel();
        $start = $request->start_time ? $request->start_time : date('Y-m-01 H:i:s', strtotime(date("Y-m-d")));
//        $start =  "2017-10-10 08:51:07";
        $end = $request->end_time ? $request->end_time : date('Y-m-d H:i:s', strtotime("$start +1 month -1 day -1seconds"));
//        $end = "2017-10-10 22:54:17";
        $status = $request->status;
        $map=array();
        if($status){
            $map['order_state']=$status;
        }
        //Excel表格式,这里简略写了8列
        ini_set("memory_limit", "1024M"); // 设置php可使用内存
        $letter = array('A','B','C','D','E','F','F','G','H','I','J','K','L','M','N',
            'O','P','Q','R','S','T','U','V');
        //表头数组
        $tableheader = array(
            '商品编码','产品类别','产品名称','产品单价','购买数量',
            '运费','订单总额','订单备注','用户昵称','订单编号',
            '订单状态','下单时间','支付时间','发货时间','确认收货时间',
            '省','市','区','收货人姓名','收货人电话','收货地址','物流公司','物流单号');
        //填充表头信息
        for($i = 0;$i < count($tableheader);$i++) {
            $excel->getActiveSheet()->setCellValue("$letter[$i]1","$tableheader[$i]");
        }
        //表格数组
        //查出所有订单方面的信息
//        $order1=Order::with('snop')->with('extend')->with('user')->where($map)->where('created_at','>',$start)->get()->toArray();
//        $order2=Order::with('snop')->with('extend')->with('user')->where($map)->where('created_at','<',$end)->get()->toArray();
//        $order = array_merge($order2,$order1);


        $order=Order::with('snop')->with('extend')->with('user')->where($map)->where('created_at','>',$start)->where('created_at','<',$end)->get()->toArray();

        $data=array();

        foreach ($order as $key => $val) {
//            dump($key);
            //查出所有商品信息
            foreach ($val['snop'] as $k => $v) {
                $snop[$k]=json_decode($v['snopjson'],true);

                //查找商品分类
                $cate=Classify::where(array('classifyid'=>$snop[$k]['classifyid'],'type'=>0))->first();
                $isextend = Order_extend::where('snopid',$v['snopid'])->value('extendid');
                if($val['order_state']=='0'){
                    $status='已取消';
                }elseif($val['order_state']=='10'){
                    $status='待付款';
                }elseif($val['order_state']=='20' && empty($isextend)){
                    $status='待发货';
                }elseif($val['order_state']=='20' && !empty($isextend)){
                    $status='待收货';
                }elseif($val['order_state']=='30'){
                    $status='待收货';
                }elseif($val['order_state']=='50'){
                    $status='完成';
                }elseif($val['order_state']=='60'){
                    $status='退款中';
                }elseif($val['order_state']=='70'){
                    $status='退款完成';
                }
//                dump($status);
//                //查出所有地址信息
                $address=json_decode($val['address_json'],true);
//               //查出所有用户信息
                $user=$val['user'];
                //重新组合数组


                $data[$key][$k][0]=$snop[$k]['commodityid'];
                if($cate){
                    $cate_name=$cate->name;

                    $data[$key][$k][1]=$cate_name;

                }else{

                    $data[$key][$k][1]='暂无分类';

                }
                $data[$key][$k][21]= Order_extend::where('snopid',$v['snopid'])->value('express');

                $data[$key][$k][22]=Order_extend::where('snopid',$v['snopid'])->value('couriernumber');

                $data[$key][$k][13]= Order_extend::where('snopid',$v['snopid'])->value('created_at');


                if($v['orderid'] <= 498 &&!$data[$key][$k][21]){
                    $data[$key][$k][21]= Order_extend::where('orderid',$v['orderid'])->value('express');
                }
                if($v['orderid'] <= 498 &&!$data[$key][$k][22]){
                    $data[$key][$k][22]=Order_extend::where('orderid',$v['orderid'])->value('couriernumber');
                }
                if($v['orderid'] <= 498 && empty($data[$key][$k][13])){
                    $data[$key][$k][13]=Order_extend::where('orderid',$v['orderid'])->value('created_at');
                }
                $data[$key][$k][2]=$snop[$k]['title'];

                $data[$key][$k][3]=$snop[$k]['money'];

                $data[$key][$k][4]=$v['nums'];

                $data[$key][$k][5]=$val['carriage'];

                $data[$key][$k][6]=$val['money'];

                $data[$key][$k][7]=$val['beizhu'];

                $data[$key][$k][8]=$user['nickname'];

                $data[$key][$k][9]=$val['transaction'].' ';

                $data[$key][$k][10]=$status;

                $data[$key][$k][11]=$val['created_at'];
//                $data[$key][$k][11]=1;
//                dump($data[$key][11]);
//                $orderdata = Order::where('orderid',$val['orderid'])->first();
                $data[$key][$k][12]=$val['pay_time'];

                if($isextend){
                    $data[$key][$k][14]=$val['endtime'];
                }else{
                    if($val['order_state'] == 50){
                        $data[$key][$k][14]= $val['endtime'];
                    }else{
                        $data[$key][$k][14]= "";
                    }
                }
                $data[$key][$k][15]=$address['province'];

                $data[$key][$k][16]=$address['city'];

                $data[$key][$k][17]=$address['district'];

                $data[$key][$k][18]=$address['name'];

                $data[$key][$k][19]=$address['phone'].' ';

                $data[$key][$k][20]=$address['address'].' ';


            }

        }
        foreach($data as $v){
            foreach ($v as $v1){
                $newData[] = $v1;
            }
        }
        $strTable ='<table width="100%" border="1">';
        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">商品编码</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">产品类别</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">产品名称</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">产品单价</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">购买数量</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">运费</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">订单总额</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">订单备注</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">用户昵称</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="180">订单编号</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">订单状态</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="180">下单时间</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="180">支付时间</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="180">发货时间</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="180">确认收货时间</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">省</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">市</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">区</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">收货人姓名</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="180">收货人电话</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">收货地址</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">物流公司</td>';
        $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="180">物流单号</td>';
        $strTable .= '</tr>';
        foreach($newData as $k=>$val){
            $strTable .= '<tr>';
            $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">'.$val[0].'</td>';
            $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">'.$val[1].'</td>';
            $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">'.$val[2].'</td>';
            $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">'.$val[3].'</td>';
            $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">'.$val[4].'</td>';
            $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">'.$val[5].'</td>';
            $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">'.$val[6].'</td>';
            $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">'.$val[7].'</td>';
            $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">'.$val[8].'</td>';
            $strTable .= '<td style="text-align:center;font-size:14px;mso-number-format:\'\@\'" height="30" width="180">'.$val[9].'</td>';
            $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">'.$val[10].'</td>';
            $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="180">'.$val[11].'</td>';
            $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="180">'.$val[12].'</td>';
            $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="180">'.$val[13].'</td>';
            $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="180">'.$val[14].'</td>';
            $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">'.$val[15].'</td>';
            $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">'.$val[16].'</td>';
            $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">'.$val[17].'</td>';
            $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">'.$val[18].'</td>';
            $strTable .= '<td style="text-align:center;font-size:14px;mso-number-format:\'\@\'" height="30" width="180">'.$val[19].'</td>';
            $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">'.$val[20].'</td>';
            $strTable .= '<td style="text-align:center;font-size:14px;" height="30" width="80">'.$val[21].'</td>';
            $strTable .= '<td style="text-align:center;font-size:14px;mso-number-format:\'\@\'" height="30" width="180">'.$val[22].'</td>';
            $strTable .= '</tr>';
        }
        $strTable .='</table>';

        header("Content-type: application/vnd.ms-excel");
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=订单列表.xls");
        header('Expires:0');
        header('Pragma:public');
        echo $strTable;
        exit();
//        dd($newData);
//        for ($i = 2;$i <= count($newData) + 1;$i++) {
//            $j = 0;
//            foreach ($newData[$i - 2] as $key=>$value) {
//                $a[$key] = $value;
//                $excel->getActiveSheet()->setCellValue("$letter[$j]$i","$value");
//                $j++;
//            }
//        }
//        //创建Excel输入对象
//        $write = new PHPExcel_Writer_Excel5($excel);
//        header("Pragma: public");
//        header("Expires: 0");
//        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
//        header("Content-Type:application/force-download");
//        header("Content-Type:application/vnd.ms-execl");
//        header("Content-Type:application/octet-stream");
//        header("Content-Type:application/download");;
//        header('Content-Disposition:attachment;filename="订单列表.xls"');
//        header("Content-Transfer-Encoding:binary");
//        $write->save('php://output');
    }


    public function  message(){
        sendmessage();
    }

}
