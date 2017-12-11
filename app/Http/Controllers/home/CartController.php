<?php

namespace App\Http\Controllers\home;

use App\Models\Carriage;
use App\Models\Cart;
use App\Models\Commodity;
use App\Models\Love;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{ 
    /*ajax增加购物车数量*/
    public function ajaxPlusCount(Request $request){
//        dd($request->cartid);
        if($request->cartid){
            //购物车数量先增加1
            $uid=$_COOKIE['uid'];
            $nums=Cart::where(array('uid'=>$uid,'cartid'=>$request->cartid))->increment('nums',1);
            //查询购物车信息
            $cartInfo=Cart::where(array('uid'=>$uid,'cartid'=>$request->cartid))->value('nums');
            $cart=Cart::where('uid',$uid)->get()->toArray();
            $totalPirce=0;
            foreach ($cart as $key => $val) {
                $totalPirce+=$val['money']*$val['nums'];
            }
            if($nums){
                return response()->json(['status'=>200,'msg'=>"增加购物车数量成功",'nums'=>$cartInfo,'totalPrice'=>$totalPirce]);
            }
        }else{
            return response()->json(['status'=>404,'msg'=>"缺少必要参数!"]);
        }
    }
    /*ajax减少购物车数量*/
    public function ajaxMinusCount(Request $request){
//        dd($request->cartid);
        $uid=$_COOKIE['uid'];
        if($request->cartid){
            //购物车数量先增加1
            $nums=Cart::where(array('uid'=>$uid,'cartid'=>$request->cartid))->decrement('nums',1);
            //查询购物车信息
            $cartInfo=Cart::where(array('uid'=>$uid,'cartid'=>$request->cartid))->value('nums');
            $cart=Cart::where('uid',$uid)->get()->toArray();
            $totalPirce=0;
            foreach ($cart as $key => $val) {
                $totalPirce+=$val['money']*$val['nums'];
            }
            if($nums){
                return response()->json(['status'=>200,'msg'=>"减少购物车数量成功",'nums'=>$cartInfo,'totalPrice'=>$totalPirce]);
            }else{
                return response()->json(['status'=>200,'msg'=>"减少购物车数量失败",'nums'=>$cartInfo,'totalPrice'=>$totalPirce]);
            }
        }else{
            return response()->json(['status'=>404,'msg'=>"缺少必要参数!"]);
        }
    }
    /*ajax删除购物车*/
    public function ajaxDelCart(Request $request){
        $cartid=$request->cartid;
        //如果有购物车ID 直接进行删除操作
        if($request->cartid){
            $id=Cart::where('cartid',$cartid)->delete();
            if($id){
                return response()->json(['status'=>200,'msg'=>"删除成功!"]);
            }else{
                return response()->json(['status'=>404,'msg'=>"删除失败!"]);
            }
        }else{
            return response()->json(['status'=>404,'msg'=>"缺少必要参数!"]);
        }
    }
    /*ajax添加购物车*/
    public function ajaxAddCart(Request $request){
        $uid=$_COOKIE['uid'];
//        if (empty(@$_COOKIE['openid'])) {
//            echo "<script>alert('首次购买,正在自动登录中...');</script>";
//            header("Location:http://yqdz.xs.sunday.so/");
//            die;
//        }
        $id=$request->id;

        $num=$request->num;

        $money=$request->money;
        //先查询购物车里是否有该商品
        $cart=Cart::where(array('uid'=>$uid,'commodityid'=>$id))->count();
        //如果有该商品,把该商品的数量加上用户要购买的数量
        if($cart){
            Cart::where(array('uid'=>$uid,'commodityid'=>$id))->increment('nums',$num);
            return response()->json(['status'=>200,'msg'=>"加入购物车成功"]);
        }else{
            //否则如果参数满足进行增加操作
            if($id && $num && $money){
                $cartid=Cart::where(array('uid'=>$uid))->insert([
                    'uid'=>$uid,
                    'commodityid'=>$id,
                    'nums'=>$num,
                    'money'=>$money
                ]);
                if($cartid){
                    return response()->json(['status'=>200,'msg'=>"加入购物车成功"]);
                }else{
                    return response()->json(['status'=>404,'msg'=>"加入购物车失败"]);
                }
            }else{
                return response()->json(['status'=>404,'msg'=>"缺少必要参数!"]);
            }
        }



    }
    
}
