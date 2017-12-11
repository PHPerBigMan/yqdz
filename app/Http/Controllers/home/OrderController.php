<?php

namespace App\Http\Controllers\home;

use App\Models\Address;
use App\Models\Commodity_comment;
use App\Models\Dividedinto;
use App\Models\Order;
use App\Models\Commodity;
use App\Models\Order_commodity_snop;
use App\Models\Order_refunds;
use App\Models\Order_return_goods;
use App\Models\User;
use App\Models\Cart;
use function file_put_contents;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    //取消订单
    public function cancle(Request $request){
//        dd(1111);
//        $uid=session('user.id');
        $uid=$_COOKIE['uid'];
        $status=Order::where('uid',$uid)->where('orderid',$request->id)->value('order_state');

        if($status != "10"){
            return response()->json(["status"=>404,"msg"=>"切勿非法操作数据！"]);
        }
        if (Order::where('uid',$uid)->where('orderid',$request->id)->update(['order_state'=>0])){
            return response()->json(["status"=>200,"msg"=>"取消成功！"]);
        }else{
            return response()->json(["status"=>404,"msg"=>"取消失败！"]);
        }
    }

    //申请退款接口，状态为20可申请
    public function refund(Request $request){
//            dd($request);
//        $status=Order::where('orderid',$request->id)->value('order_state');
//        if($status != 20){
//            return response()->json(["status"=>404,"msg"=>"订单状态异常！"]);
//        }
//        dd($request);
        $money=Order::where('orderid',$request->id)->value('money');
        if($request->money>$money){
            return response()->json(["status"=>404,"msg"=>"退款金额不能大于订单总额"]);
        }
        if (Order_return_goods::create([
            'orderid'=>$request->id,
            'created_at'=>date('Y-m-d H:i:s'),
            'snopid'=>$request->snopid,
            'money'=>$request->money,
            'content'=>$request->content,
            'status'=>1,
            'express'=>$request->express,
            'logistics'=>$request->couriernumber,
            'uid'=>$_COOKIE['uid']
        ])){
            Order::where('orderid',$request->id)->update(['order_state'=>60,'refund_state'=>1]);
            Order_commodity_snop::where('orderid',$request->id)->update(['is_refunds'=>1]);
            return response()->json(["status"=>200,"msg"=>"申请成功，请耐心等待！"]);
        }else{
            return response()->json(["status"=>404,"msg"=>"申请失败"]);
        }
    }

    //用户确认收货操作,状态为30可操作
    public function shouhuo(Request $request){

        $order=Order::where('orderid',$request->id)->select('uid','order_state','return_status','orderid')->first();

        if($order['order_state'] != "30" || $order['return_status'] != "0"){
//            dd(123456);
            return response()->json(["status"=>404,"msg"=>"订单状态异常！"]);
        }
//        dd(11111);
//
        if (Order::where('orderid',$request->id)->update(['order_state'=>50,'endtime'=>date("Y-m-d H:i:s")])){
            $snop=Order_commodity_snop::where('orderid',$request->id)->select('snopjson','snopid','money','nums')->first();//获取商品列表

            $user=array_values(User::where('uid',$order['uid'])->select('first','second','three')->get()->toArray());
            //计算收益
//            dd($user);
            $data=Order_commodity_snop::where('orderid',$order['orderid'])->where('is_refunds',0)->get();
//            dd($data);
            $one = $two = $three = 0;
            $nickname=User::where('uid',$order['uid'])->value('nickname');
//            dd($nickname);
            foreach ($data as $k => $v){
                $data[$k]['snopjson'] = json_decode($v['snopjson'],true);
                $one += ($v['money']*$v['nums']*$data[$k]['snopjson']['firstgraded']*0.01);
                $two += ($v['money']*$v['nums']*$data[$k]['snopjson']['secondgraded']*0.01);
                // $three += ($v['money']*$v['nums']*$data[$k]['snopjson']['threegraded']*0.01);
            }
                    //查询当前用户的1,2,3级推荐人
                    $user=User::where('uid',$order['uid'])->select('first','second','three')->first();
                    if($user->first==0 && $user->second==0 && $user->three==0){
                        if (!Order::where('orderid',$order['orderid'])->update(['is_fencheng'=>2])){
                            return response()->json(["status"=>404,"msg"=>"操作失败！"]);
                        }
                        return response()->json(["status"=>200,"msg"=>"操作成功！"]);

                    }else{
                        //查询上级一级推荐
                        $first=User::where('uid',$user['first'])->value('uid');
                        //查询当前用户的1,2,3级推荐人

                        //查询上级二级推荐
                        $second=User::where('uid',$user['second'])->value('uid');
                        //如果有一级推荐人,增加分成记录
                        if ($first) {
                            $divide=Dividedinto::create(array(
                                'uid'=>$first,
                                'orderid'=>$order['orderid'],
                                'level'=>1,
                                'fromuid'=>$order['uid'],
                                'money'=>$one,
                                'status'=>1,
                                'created_at'=>date('Y-m-d H:i:s')
                            ));
                            if ($second) {
                                //如果有二级推荐人,增加分成记录
                                $divide2=Dividedinto::create(array(
                                    'uid'=>$second,
                                    'orderid'=>$order['orderid'],
                                    'level'=>2,
                                    'fromuid'=>$order['uid'],
                                    'money'=>$two,
                                    'status'=>1,
                                    'created_at'=>date('Y-m-d H:i:s')
                                ));
                            }
                        }
                        if (!$divide || !$divide2){
                            return response()->json(["status"=>404,"msg"=>"操作失败！"]);
                        }


//            dd(132456);
                        if (!Order::where('orderid',$order['orderid'])->update(['is_fencheng'=>2])){
                            return response()->json(["status"=>404,"msg"=>"操作失败！"]);
                        }
                        return response()->json(["status"=>200,"msg"=>"操作成功！"]);
                    }
        }else{
            return response()->json(["status"=>404,"msg"=>"操作失败！"]);
        }
    }

    public function confirm(Request $request)
    {
        if($request->test==1){
            $uid=$_COOKIE['uid'];
            $uniqueid = time() . rand(1111, 9999);
//        return response()->json(["status" => 200, "msg" => $uniqueid]);
            $transaction = '15068315243442';
            $commercial = 'wx' . time() . rand(111, 999);
            $snopjson = DB::table('commodity')->where('commodityid', 38)->first();
            $snopjson->thumbnail = json_decode($snopjson->thumbnail, true);
            $snopjson->thumbnail = $snopjson->thumbnail[0];
            $snopjson = json_encode($snopjson);
            $address=Address::where(array('uid'=>$uid,'addressid'=>23))->first();
            $addressJson=json_encode($address);
            $orderid = Order::insertGetId(array(
                'uniqueid' => $uniqueid,
                'addressid' => 23,
                'address_json'=>$addressJson,
                'money' => 173,
                'beizhu' => $_POST['beizhu'],
                'label'=>$_POST['label'],
                'transaction' => $transaction,
                'commercial' => $commercial,
                'order_state' => 20,
                'created_at' => date('Y-m-d H:i:s'),
                'uid' => $uid,
                'carriage' => 0,
                'evaluation_state' => 0,
                'refund_amount' => 0,
                'is_fencheng' => 1,
//                'endtime' => date('Y-m-d H:i:s')
            ));
//                \Log::info($orderid.'生成的订单');
            // $log = DB::getQueryLog();
            // $log=json_encode($log);
            // return response()->json(["status" => 200,'msg'=>$log]);
            $bool = Order_commodity_snop::insertGetId(array(
                'snopjson' => $snopjson,
                'money' => 173,
                'nums' => 1,
                'orderid' => $orderid
            ));
            User::where('uid',$uid)->update(['isbuy'=>1]);
            $good = DB::table('commodity')->where('commodityid', 38)->first();

            if ($good->sales < $good->number) {
                DB::table('commodity')->where('commodityid', 38)->increment('sales', $_POST['nums']);
            }
            die;
        }
//        return response()->json(["status" => 200, "msg" => $_REQUEST]);

        $uid=$_COOKIE['uid'];

//        return response()->json(["status" => 200, "msg" => $uid]);
        if($request->orderid){

            //如果 再一次传递了订单则修改已存在的订单信息
            //如果没有订单号,说明直接购买,新增订单
            $address=Address::where(array('uid'=>$uid,'addressid'=>$_POST['addressid']))->first();
            $addressJson=json_encode($address);
//            return response()->json(["status" => 200, "msg" => "下单成功",'orderid'=>$request->orderid]);
            $orderid = Order::where([
                'orderid'=>$request->orderid
            ])->update(array(
                'addressid' => $_POST['addressid'],
                'money' => $request->money,
                'address_json'=>$addressJson,
                'beizhu' => $_POST['beizhu'],
                'label'=>$_POST['label'],
//                'order_state' => 10,
//                'created_at' => date('Y-m-d H:i:s'),
                'uid' => $uid,
//                'carriage' => $request->carriage,
                'evaluation_state' => 0,
                'refund_amount' => 0,
                'is_fencheng' => 1,
//                'endtime' => date('Y-m-d H:i:s')
            ));

            if($orderid){
                return response()->json(["status" => 200, "msg" => "下单成功",'orderid'=>$request->orderid]);
            }else{
                return response()->json(["status" => 404, "msg" => "系统下单失败",'orderid'=>$request->orderid]);
            }
        }else{
            //如果没有订单号,说明直接购买,新增订单
            $uniqueid = time() . rand(1111, 9999);
//        return response()->json(["status" => 200, "msg" => $uniqueid]);
            $transaction = $_POST['transaction'];
            $commercial = 'wx' . time() . rand(111, 999);
            //商品ID切割字符串,并去空
            $commodityid=explode(',',$request->commodityid);
            foreach ($commodityid as $key => $val) {
                if(empty($val)){
                    unset($commodityid[$key]);
                }
            }
            $num=explode(',',$request->nums);
            foreach ($num as $key => $val) {
                if(empty($val)){
                    unset($num[$key]);
                }
            }
//            dd($num);
//            dd($commodityid);

//            dd($snopjson);
            $address=Address::where(array('uid'=>$uid,'addressid'=>$_POST['addressid']))->first();
            $addressJson=json_encode($address);
            if($request->status==0){
                \Log::info('刚刚下的订单');
                $orderid = Order::insertGetId(array(
                    'uniqueid' => $uniqueid,
                    'addressid' => $_POST['addressid'],
                    'address_json'=>$addressJson,
                    'money' => $request->money,
                    'beizhu' => $_POST['beizhu'],
                    'label'=>$_POST['label'],
                    'transaction' => $transaction,
                    'commercial' => $commercial,
                    'order_state' => 10,
                    'created_at' => date('Y-m-d H:i:s'),
                    'uid' => $uid,
                    'carriage' => 0,
                    'evaluation_state' => 0,
                    'refund_amount' => 0,
                    'is_fencheng' => 1,
//                    'endtime' => date('Y-m-d H:i:s')
                ));
//                return response()->json(["status" => 200, "msg" => "下单成功",'orderid'=>$orderid]);
                //循环添加订单商品
                foreach($commodityid as $key=>$val){

                    $goods = DB::table('commodity')->where('commodityid', $val)->first();

                    $goods->thumbnail = json_decode($goods->thumbnail, true);
                    $goods->thumbnail = $goods->thumbnail[0];

                    $snopjson = json_encode($goods);

                    $bool = Order_commodity_snop::insertGetId(array(
                        'snopjson' => $snopjson,
                        'money' => $goods->money,
                        'nums' => $num[$key],
                        'orderid' => $orderid
                    ));

                    if ($goods->sales < $goods->number) {
                        DB::table('commodity')->where('commodityid', $_POST['commodityid'])->increment('sales', $num[$key]);
                    }
                    if($goods->stock > 0){
                        DB::table('commodity')->where('commodityid', $_POST['commodityid'])->decrement('stock', $num[$key]);
                    }
                }

                User::where('uid',$uid)->update(['isbuy'=>1]);
                if($request->upid){
                    $user=User::where('uid',$uid)->select('first','second','three')->first()->toArray();
                    //如果查询id的二级推荐人不为空,那么就是说n 当前用户的三级都有了
                    if(!empty($user['second'])){
                        User::where('uid',$uid)->update(['first'=>$request->upid,'second'=>$user['first'],'three'=>$user['second']]);
                    }else if(empty($user['second']) && !empty($user['first'])){//如果二级推荐人为空但是一级推荐人不为空,那么就只有一级跟二级
                        User::where('uid',$uid)->update(['first'=>$request->upid,'second'=>$user['first']]);
                    }else{
                        User::where('uid',$uid)->update(['first'=>$request->upid]);
                    }
                }
                if($orderid && $bool){
                    //下单成功后,删除购物车
                    $cart=Cart::where('uid',$uid)->delete();
                    if($cart){
                        return response()->json(["status" => 200, "msg" => "下单成功",'orderid'=>$orderid]);
                    }

                }else{
                    return response()->json(["status" => 404, "msg" => "系统下单失败",'orderid'=>$orderid]);
                }




//                return response()->json(["status" => 200,'orderid'=>$orderid]);

            }
//            if ($orderid) {
//                $bool = Order_commodity_snop::insertGetId(array(
//                    'snopjson' => $snopjson,
//                    'money' => $_POST['money'],
//                    'nums' => $_POST['nums'],
//                    'orderid' => $orderid
//                ));
//                User::where('uid',$uid)->update(['isbuy'=>1]);
//                $good = DB::table('commodity')->where('commodityid', $_POST['commodityid'])->first();
//                if ($good->sales < $good->number) {
//
//                    DB::table('commodity')->where('commodityid', $_POST['commodityid'])->increment('sales', $_POST['nums']);
//                }
//                //如果有订单号,就把订单状态修改为已付款
//
//                if ($bool) {
//                    //如果有上级ID 就把子用户的first 改成上级ID
//                    if($request->upid){
//                        $user=User::where('uid',$uid)->select('first','second','three')->first()->toArray();
//                        //如果查询id的二级推荐人不为空,那么就是说当前用户的三级都有了
//                        if(!empty($user['second'])){
//                            User::where('uid',$uid)->update(['first'=>$request->upid,'second'=>$user['first'],'three'=>$user['second']]);
//                        }else if(empty($user['second']) && !empty($user['first'])){//如果二级推荐人为空但是一级推荐人不为空,那么就只有一级跟二级
//                            User::where('uid',$uid)->update(['first'=>$request->upid,'second'=>$user['first']]);
//                        }else{
//                            User::where('uid',$uid)->update(['first'=>$request->upid]);
//                        }
//                    }
//                    return response()->json(["status" => 200, "msg" => "下单成功",'orderid'=>$orderid]);
//                } else {
//                    return response()->json(["status" => 404, "msg" => "下单失败"]);
//                }
//            }
        }
    }
        //评价
    public function evaluate(Request $request){
//        dd($_POST['content);
        $uid=$_COOKIE['uid'];
        $order = Order::where('uid', $uid)->where('orderid',$request->id)->select('uniqueid', 'order_state', 'addressid', 'orderid', 'money', 'carriage',
            'evaluation_state', 'refund_state', 'return_status')->with('snop')->orderBy('created_at', 'desc')->get()->toArray();
        $commodity_id=array();
        foreach ($order as $key => $val) {

            foreach($val['snop'] as $k=>$v){
                $order[$key]['snop'][$k]['snopjson']=json_decode($v['snopjson']);
                $commodity_id[]=$order[$key]['snop'][$k]['snopjson']->commodityid;
//                $commodity_id=123456;
            }
//            dump($val->snop);
        }
        $content=explode(',',$request->content);
        foreach ($commodity_id as $key => $val) {
            $id=Commodity_comment::create(array(
                'uid'=>$uid,
                'content'=>$content[$key],
                'created_at'=>date('Y-m-d'),
                'commodityid'=>$val,
                'order_id'=>$request->id
            ));
        }
//        $commodity_id=$commodity->commodityid;
        if ($id){
            Order::where('uid', $uid)->where('orderid',$request->id)->update(['evaluation_state'=>1]);
            return response()->json(["status"=>200,"msg"=>"评价成功"]);
        }else{
            return response()->json(["status"=>404,"msg"=>"评价失败"]);
        }
    }
}
