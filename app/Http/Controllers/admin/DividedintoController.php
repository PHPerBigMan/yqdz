<?php

namespace App\Http\Controllers\admin;

use App\Models\Dividedinto;
use App\Models\Order;
use App\Models\User;
use App\Models\WeiXin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DividedintoController extends Controller
{
    public function lists(Request $request){
//        dd($request);
            $start = $request->start ? $request->start : date('Y-m-01 H:i:s', strtotime(date("Y-m-d")));
            $end = $request->end ? $request->end : date('Y-m-d H:i:s', strtotime("$start +1 month -1 day -1seconds"));

            if($request->uid != ''){
                if ($request->status == '1'){
                    $data=Dividedinto::where('uid',$request->uid)->where('status',$request->status)->where('created_at','>',$start)->where('created_at','<',$end)->paginate(10);
                }elseif($request->status == '2'){
                    $data=Dividedinto::where('uid',$request->uid)->where('status',$request->status)->where('created_at','>',$start)->where('created_at','<',$end)->paginate(10);
                }else{
                    $data=Dividedinto::where('uid',$request->uid)->where('created_at','>',$start)->where('created_at','<',$end)->paginate(10);
                }
            }else{
                if ($request->status == '1'){
                    $data=Dividedinto::where('status',$request->status)->where('created_at','>',$start)->where('created_at','<',$end)->paginate(10);
                }elseif($request->status == '2'){
                    $data=Dividedinto::where('status',$request->status)->where('created_at','>',$start)->where('created_at','<',$end)->paginate(10);
                }else{
                    $data=Dividedinto::where('created_at','>',$start)->where('created_at','<',$end)->paginate(10);
                }
            }
            foreach ($data as $k => $v){
                $data[$k]['isselected'] = 0;
                $data[$k]['nickname']=User::where('uid',$v['uid'])->value('nickname');
                $data[$k]['img']=User::where('uid',$v['uid'])->value('img');
                $data[$k]['uniqueid']=Order::where('orderid',$v['orderid'])->value('uniqueid');
            }

        return view('admin.dividedinto.lists',['start'=>$start,'end'=>$end,'data'=>$data,'test'=>json_encode($data->toArray()['data'])]);
    }

    public function lists2(){
//        dd(123456);
//        $list = DB::select("select * from dividedinto where status=1 GROUP BY fromuid");
//        $list = DB::table('dividedinto') ->groupBy('fromuid') ->get();
        $list=Dividedinto::where('status',1)->distinct()->paginate(10);
//        dd(count($list));
        if(count($list)){
            $temp=array();
            foreach ($list as $k => $v){
                $data=User::where('uid',$v['uid'])->first();
                $list[$k]['nickname']=$data['nickname'];
                $list[$k]['id']=$data['uid'];
                $list[$k]['img']=$data['img'];
                if(!in_array($v['uid'],$temp)){
                    $temp[] = $v['uid'];
                    $temparr[] = $v;
                }else{
                    $t = array_search($v['uid'],$temp);
                    $temparr[$t]['money'] = $temparr[$t]['money'] + $v['money'];
                }
            }
            return view('admin.dividedinto.lists2',['data'=>$temparr,'list'=>$list]);
        }else {
            $list=Dividedinto::where('status',1)->distinct()->paginate(10);
            return view('admin.dividedinto.lists2',['data'=>array(),'list'=>$list]);
        }

    }

    public function fangkuan(Request $request){

        if (!is_null($request->uid)){

            $data=Dividedinto::where('status','1')->where('uid',$request->uid)->select('uid','money','created_at')->get();
        }else{
            $temp = explode(",",$request->id);

            foreach ($temp as $k=>$v){
                $data[]=Dividedinto::where('status','1')->where('intoid',$v)->select('uid','money','created_at')->first()->toArray();
            }
//            $request->intoid  = array('in',$temp);
//            $data=Dividedinto::where('status','1')->where('intoid',$request->intoid['1'])->select('uid','money','created_at')->get();
        }

        if(count($data)<=0){
            return response()->json(['status'=>404,'msg'=>"非法数据源！"]);
        }

        $send['money'] = 0;
        foreach ($data as $k => $v){
            $send['money'] += $v['money'];
        }

        if($send['money'] < 1){
            return response()->json(['status'=>404,'msg'=>"放款失败，必须大于1元才能发红包！"]);
        }

        $send['openid'] = User::where('uid',$data[0]['uid'])->value('openid');
//        $ret = WeiXin::pay($send,$data[0]['uid'],"hb".date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8));//将钱打入用户账户
        if (!is_null($request->uid)){
            if(Dividedinto::where('uid',$request->uid)->update(['status'=>'2'])){
                return response()->json(['status'=>200,'msg'=>"放款成功！"]);
            }else{
                return response()->json(['status'=>200,'msg'=>"放款失败！"]);
            }
        }else{
            foreach ($temp as $k=>$v){
                $data[]=Dividedinto::where('intoid',$v)->update(['status'=>'2']);
                if($data){
                    return response()->json(['status'=>200,'msg'=>"放款成功！"]);
                }else{
                    return response()->json(['status'=>200,'msg'=>"放款失败！"]);
                }
            }
        }
    }
}
