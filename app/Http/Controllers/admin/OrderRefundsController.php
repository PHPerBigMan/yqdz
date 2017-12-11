<?php

namespace App\Http\Controllers\admin;

use App\Models\Order;
use App\Models\Order_refunds;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderRefundsController extends Controller
{
    public function lists(Request $request){
        $start = $request->start ? $request->start : date('Y-m-01 H:i:s', strtotime(date("Y-m-d")));
        $end = $request->end ? $request->end : date('Y-m-d H:i:s', strtotime("$start +1 month -1 day -1seconds"));

        if ($request->uniqueid != ""){
            $orderid=Order::where('uniqueid',$request->uniqueid)->value('orderid');
            $data=Order_refunds::where('orderid',$orderid)->orderBy('returnid', 'desc')->paginate(10);
        }else{
            $data=Order_refunds::where('created_at','>',$start)->where('created_at','<',$end)->orderBy('returnid', 'desc')->paginate(10);
        }
//        dd($data);
        return view('admin.orderrefunds.lists',['start'=>$start,'end'=>$end,'data'=>$data]);
    }

    public function agree(Request $request){
        $data=Order_refunds::where('refundsid',$request->id)->first();
        $order=Order::where('orderid',$data['orderid'])->first();

        if(empty($data) || $data['status'] != 1){
            return response()->json(['status' => '404', 'msg' =>'数据异常!',]);
        }

        if(Order_refunds::where('refundsid',$request->id)->update(['status'=>2])){
            $money = ($order['money']+$order['carriage'])*100;
            Order::where('orderid',$data['orderid'])->update(['order_state'=>70]);
            return response()->json(['status' => '200', 'msg' =>'操作成功!',]);
        }else{
            return response()->json(['status' => '404', 'msg' =>'操作失败!',]);
        }
    }

    public function refuse(Request $request){
        $data=Order_refunds::where('refundsid',$request->id)->select('status','orderid')->first();

        if(empty($data) || $data['status'] != 1){
            return response()->json(['status' => '404', 'msg' =>'数据异常!',]);
        }

        if(Order_refunds::where('refundsid',$request->id)->update(['status'=>3])){
            Order::where('orderid',$data['orderid'])->update(['order_state'=>20]);
            return response()->json(['status' => '200', 'msg' =>'操作成功!',]);
        }else{
            return response()->json(['status' => '404', 'msg' =>'操作失败!',]);
        }
    }
}
