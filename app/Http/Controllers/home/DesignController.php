<?php

namespace App\Http\Controllers\home;

use App\Models\Design;
use App\Models\Design_hotes;
use App\Models\Classify;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DesignController extends Controller
{
    public function designHandle(request $request){
        if (empty($request->content) || empty($request->phone)){
            return response()->json(['status'=>404,'msg'=>"请填写定制信息"]);
        }
//        $uid=session('user.id');
        $nowtime=date('Y-m-d');
        $uid=$_COOKIE['uid'];
//        dd($uid);
        $data=Design::create([
           'uid'=>$uid,
            'content'=>$request->content,
            'phone'=>$request->phone,
            'is_qiye'=>2,
            'created_at'=>$nowtime,
            'cate_id'=>0
        ]);
        if ($data){
            return response()->json(['status'=>200,'msg'=>"提交成功"]);
        }else{
            return response()->json(['status'=>404,'msg'=>"添加失败"]);
        }
    }
    public function custom(request $request){
        if (empty($request->content) || empty($request->cate)){
            return response()->json(['status'=>404,'msg'=>"请填写定制信息"]);
        }
//        $uid=session('user.id');
        $uid=$_COOKIE['uid'];
//        dd($uid);
        $nowtime=date('Y-m-d');
        $time=DB::table('design')->where(array('uid'=>$uid,'created_at'=>$nowtime,'is_qiye'=>1))->count();
//        dd($time);
        if($time>=3){
            return response()->json(['status'=>404,'msg'=>"私人定制每人每天最多三次"]);
        }
        /**
         * hongwenyang
         */

        $imgs = "";

        if(!empty($request->input('imgs'))){
            $imgs = explode(',',$request->input('imgs'));
            unset($imgs[0]);
            sort($imgs);
            $imgs = json_encode($imgs);
        }

        $data=Design::create([
           'uid'=>$uid,
            'content'=>$request->content,
            'cate_id'=>$request->cate,
            'is_qiye'=>1,
            'phone'=>$request->phone,
            'created_at'=>$nowtime,
            'img'=>$imgs,
            'title'=>$request->title
        ]);
        if ($data){
            return response()->json(['status'=>200,'msg'=>"提交成功"]);
        }else{
            return response()->json(['status'=>404,'msg'=>"添加失败"]);
        }
    }
    public function hotes(request $request){
        $uid=$_COOKIE['uid'];
//        dd($uid);
        $is_hotes=Design_hotes::where(array('designid'=>$request->designid,'uid'=>$uid))->value('uid');
//        dd($is_hotes);
        if(empty($is_hotes)){
            $data=Design::where('designid',$request->designid)->increment('hotes',1);
            Design_hotes::create([
                'uid'=>$uid,
                'designid'=>$request->designid
            ]);
        }else{
            return response()->json(['status'=>200,'msg'=>"您已经支持过了"]);
        }
        if ($data){
            return response()->json(['status'=>200,'msg'=>"支持成功"]);
        }else{
            return response()->json(['status'=>404,'msg'=>"支持失败"]);
        }
    }
    //删除定制信息
    public function del($fundid){
        // echo "123";die;
        $uid=$_COOKIE['uid'];
        if (Design::where(array('designid'=>$fundid,'uid'=>$uid))->delete()){
            return redirect('/my-fund')->with('message', '删除成功!');
        }else{
            return redirect('/my-fund')->with('message', '删除失败!');
        }
    }
}
