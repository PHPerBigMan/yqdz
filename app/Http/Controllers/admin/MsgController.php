<?php

namespace App\Http\Controllers\admin;

use App\Models\Design;
use App\Models\User;
use App\Models\User_msg;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MsgController extends Controller
{
    public function lists(Request $request){
        $start = $request->start ? $request->start : date('Y-m-01 H:i:s', strtotime(date("Y-m-d")));
        $end = $request->end ? $request->end : date('Y-m-d H:i:s', strtotime("$start +1 month -1 day -1seconds"));
//        dd($start);
//        dd($end);
        if($request->nickname != ""){
            $uid=User::where('nickname','like','%'.$request->nickname.'%')->value('uid');
            $data=User_msg::where('uid',$uid)->paginate(10);
        }else{
            $data=User_msg::where('create_time','>',$start)->where('create_time','<',$end)->paginate(10);
        }
//        dd($data);

        return view('admin.msg.lists',['start'=>$start,'end'=>$end,'data'=>$data]);
    }
    public function update($id){
        if(empty($id)){
            $msg='添加消息';
        }else{
            $msg='修改消息';
        }
        $data=User_msg::where('msg_id',$id)->first();
        return view('admin.msg.update',['msg'=>$msg,'data'=>@$data]);
    }
    public function add($id){
//        dd($id);
        if(empty($id)){
            $msg='添加消息';
        }else{
            $msg='修改消息';
        }
//        $data=User_msg::where('msg_id',$id)->first();
        return view('admin.msg.update',['msg'=>$msg]);
    }
    public function handle(Request $request){
        if($request->msg_id){
            if(empty($request->type)){
                return response()->json(['status'=>404,'msg'=>"消息类型不能为空"]);
            }
            if(empty($request->result)){
                return response()->json(['status'=>404,'msg'=>"结果通知不能为空"]);
            }
            $data=User_msg::where('msg_id',$request->msg_id)->update([
                'title'=>$request->title,
                'result'=>$request->result,
                'content'=>$request->content,
                'type'=>$request->type,
                'create_time'=>date('Y-m-d')
            ]);
//        dd($data);
            if ($data !==false){
                return response()->json(['status'=>200,'msg'=>"修改成功"]);
            }else{
                return response()->json(['status'=>404,'msg'=>"修改失败"]);
            }
        }else{
            if(empty($request->type)){
                return response()->json(['status'=>404,'msg'=>"消息类型不能为空"]);
            }
            if(empty($request->result)){
                return response()->json(['status'=>404,'msg'=>"结果通知不能为空"]);
            }
            $data=User_msg::create([
                'title'=>$request->title,
                'result'=>$request->result,
                'content'=>$request->content,
                'type'=>$request->type,
                'create_time'=>date('Y-m-d')
            ]);
//        dd($data);
            if ($data !==false){
                return response()->json(['status'=>200,'msg'=>"添加成功"]);
            }else{
                return response()->json(['status'=>404,'msg'=>"添加失败"]);
            }
        }

    }

}
