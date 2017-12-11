<?php

namespace App\Http\Controllers\admin;

use App\Models\Admin;
use App\Models\Order;
use App\Models\Statistics;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function login(Request $request){

        header("Access-Control-Allow-Origin:*");
        $res=Admin::where('account',$request->account)->where('password',md5($request->password))->first();
        if($res){
            //登录成功，设置session
            session([
                'admin' => $res
            ]);
            return response(array('status'=>'200','text'=>'登录成功','url'=>'center'));
        }else{
            return response(array('status'=>'404','text'=>'账号或密码错误'));
        }
    }

    public function logout(Request $request){
        $data = $request->session()->all();
        if ($request->session()->exists('admin')) {
            $value = $request->session()->pull('admin', 'default');
            $request->session()->regenerate();
        }
        return redirect('admin/index/login');
    }

    public function welcome(){
        $data=Statistics::all();
        $order_nub=Order::count();
        $order_money=Order::sum('money');
        $browse_nub=Statistics::sum('browse_nub');
        $visitor_nub=Statistics::count('visitor_nub');
        $all=array(
            $order_nub,
            $order_money,
            $browse_nub,
            $visitor_nub
        );
//        dd($order_money);

//        $order_nub = $order_money = $browse_nub = $visitor_nub = 0;

        foreach ($data as $k => $v){
//            $order_nub+=$v['order_nub'];
//            $order_money+=$v['order_money'];
//            $browse_nub+=$v['browse_nub'];
//            $visitor_nub+=$v['visitor_nub'];
//
            if($v['created_at'] == date("Y-m-d")){
                $order_nub=Order::where('created_at',date('Y-m-d'))->count();
//                dd($order_nub);
                $order_money=Order::where('created_at',date('Y-m-d'))->sum('money');
                $browse_nub=Statistics::where('created_at',date('Y-m-d'))->sum('browse_nub');
                $visitor_nub=Statistics::where('created_at',date('Y-m-d'))->count('visitor_nub');
                $today = array(
                    $order_nub,
                    $order_money,
                    $browse_nub,
                    $visitor_nub
                );
            }
                $order_nub2=Order::where('created_at',date("Y-m-d",strtotime('-1 days')))->count();
                $order_money2=Order::where('created_at',date("Y-m-d",strtotime('-1 days')))->sum('money');
                $browse_nub2=Statistics::where('created_at',date("Y-m-d",strtotime('-1 days')))->sum('browse_nub');
                $visitor_nub2=Statistics::where('created_at',date("Y-m-d",strtotime('-1 days')))->count('visitor_nub');
                $Yesterday = array(
                    $order_nub2,
                    $order_money2,
                    $browse_nub2,
                    $visitor_nub2
                );
//                dd($Yesterday);
//            $all = array(
//                $order_nub,
//                $order_money,
//                $browse_nub,
//                $visitor_nub
//            );
        }

        return view('admin.index.welcome',['all'=>$all,'today'=>@$today,'yesterday'=>@$Yesterday]);
    }


}
