<?php

namespace App\Http\Controllers\admin;

use App\Models\Classify;
use App\Models\Design;
use App\Models\User;
use App\Models\User_msg;
use App\Models\User_msglist;
use function dd;
use function dump;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DesignController extends Controller
{
    public function lists(Request $request){
        $start = $request->start ? $request->start : date('Y-m-01 H:i:s', strtotime(date("Y-m-d")));
        $end = $request->end ? $request->end : date('Y-m-d H:i:s', strtotime("$start +1 month +1 day -1seconds"));
//        dd($start);
//        dd($end);
        if($request->nickname != ""){
            $uid=User::where('nickname','like','%'.$request->nickname.'%')->value('uid');
            $data=Design::where('uid',$uid)->paginate(10);
            foreach ($data as $key => $val) {
                $cate_name=Classify::where(array('classifyid'=>$val['cate_id'],'type'=>1))->value('name');
                $data->cate_id=$cate_name;

                $data[$key]=$val;
            }
        }else{
//            $data=Design::where('created_at','>',$start)->where('created_at','<',$end)->paginate(10);
            $data = DB::table('design')->where('created_at','>',$start)->where('created_at','<',$end)->paginate(10);
//            dd($data[0]->cate_id);

            foreach ($data as $key => $val) {
//                $cate_name=Classify::where(array('classifyid'=>$val['cate_id'],'type'=>1))->value('name');
                $cate_name=Classify::where(array('classifyid'=>$val->cate_id,'type'=>0))->value('name');
                $val->user=User::where('uid',$val->uid)->first();
                if($cate_name){
                    $val->cate_id = $cate_name;
                }else{
                    $val->cate_id='其他 :'.$val->cate_id;
                }

            }
        }
       // dd($data);
        return view('admin.design.lists',['start'=>$start,'end'=>$end,'data'=>$data]);
    }

    public function agree(Request $request){
        $data=Design::where('designid',$request->id)->first();
        if(empty($data) || $data['status'] != 1){
            return response()->json(['status' => '404', 'msg' =>'数据异常!',]);
        }

        if(Design::where('designid',$request->id)->update(['status'=>2])){
            $msg_id=User_msg::where(array('type'=>3,'result'=>1))->value('msg_id');
            User_msglist::create([
                'uid'=>$data->uid,
                'msgid'=>$msg_id
            ]);
            return response()->json(['status' => '200', 'msg' =>'操作成功!',]);
        }else{
            return response()->json(['status' => '404', 'msg' =>'操作失败!',]);
        }
    }

    public function refuse(Request $request){
        $data=Design::where('designid',$request->id)->first();
        if(empty($data) || $data['status'] != 1){
            return response()->json(['status' => '404', 'msg' =>'数据异常!',]);
        }

        if(Design::where('designid',$request->id)->update(['status'=>3])){
            $msg_id=User_msg::where(array('type'=>3,'result'=>2))->value('msg_id');
            User_msglist::create([
                'uid'=>$data->uid,
                'msgid'=>$msg_id
            ]);
            return response()->json(['status' => '200', 'msg' =>'操作成功!',]);
        }else{
            return response()->json(['status' => '404', 'msg' =>'操作失败!',]);
        }
    }

    public function detail(Request $request){
        $data=Design::where('designid',$request->id)->first();
//        $data['content']=html_entity_decode($data['content']);

//        dd($data);
        return view('admin.design.detail',['data'=>$data]);
    }

    public function details(Request $request){
        if(empty($request->id)){
            $msg='添加定制信息';
        }else{
            $msg='修改定制信息';
        }

        $data=Design::where('designid',$request->id)->first();

//        $data['content']=html_entity_decode($data['content']);
        return view('admin.design.details',['data'=>$data,'msg'=>$msg]);
    }
    public function del(Request $request){
        if(empty($request->id)){
            $msg='删除定制信息';
        }else{
            $msg='删除定制信息';
        }
        $data=Design::where('designid',$request->id)->delete();
        return response()->json(['status'=>200,'msg'=>$msg]);
//        $data['content']=html_entity_decode($data['content']);
//        return view('admin.design.details',['data'=>$data,'msg'=>$msg]);
    }

    public function handle(Request $request){
        // if(empty($request->title)){
        //     return response()->json(['status'=>200,'msg'=>"请填写标题"]);
        // }
        if(empty($request->backcontent)){
            return response()->json(['status'=>200,'msg'=>"请填写内容"]);
        }
        // if(empty($request->title)){
        //     return response()->json(['status'=>200,'msg'=>"请填写标题"]);
        // }
        if(empty($request->phone)){
            return response()->json(['status'=>200,'msg'=>"请填写手机号"]);
        }
        // dd($request->designid);
        if($request->designid){
            $path=$request->file('img');
            if (!empty($path)){
                foreach ($path as $k => $v){
                    $pic[$k]='/'.$v->store('uploads','uploads');
                }
                $request->img=json_encode($pic);
            }else{
                $res=json_decode(Design::where('designid',$request->id)->value('img'));
                $request->img=json_encode($res);
            }

            $data=Design::where('designid',$request->designid)->update([
                'title'=>$request->title,
                'backcontent'=>$_POST['backcontent'],
                'phone'=>$request->phone
            ]);
//        dd($data);
            if ($data !==false){
                return response()->json(['status'=>200,'msg'=>"修改成功"]);
            }else{
                return response()->json(['status'=>404,'msg'=>"修改失败"]);
            }
        }else{
            $path=$request->file('img');
            if (!empty($path)){
                foreach ($path as $k => $v){
                    $pic[$k]='/'.$v->store('uploads','uploads');
                }
                $request->img=json_encode($pic);
            }else{
                $res=json_decode(Design::where('designid',$request->id)->value('img'));
                $request->img=json_encode($res);
            }
            if($request->phone){
                $is_qiye=2;
            }else{
                $is_qiye=1;
            }
            $data=Design::create([
                'title'=>$request->title,
                'content'=>$request->content,
                'img'=>$request->img,
                'phone'=>$request->phone,
                'is_qiye'=>$is_qiye,
                'created_at'=>date('Y-m-d')
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

