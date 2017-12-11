<?php

namespace App\Http\Controllers\admin;

use App\Models\AdminGroup;
use App\Models\AdminRule;
use App\Models\AdminRuleCat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminGroupController extends Controller
{
    public function lists(){
        $list=AdminGroup::paginate(10);

        return view('admin.adminGroup.lists',['list' =>$list,]);
    }

    public function add(){
        $rule=AdminRule::all();
        $cat=AdminRuleCat::all();
        return view('admin.adminGroup.add',['rule'=>$rule,'cat'=>$cat]);
    }

    public function edit(Request $request){
        $data=AdminGroup::where('groupid',$request->id)->first();
        $data['ruleid']=explode(",",$data['ruleid']);

        $rule=AdminRule::all();
        $cat=AdminRuleCat::all();
        foreach ($rule as $k => $v){
            if(in_array($v['ruleid'],$data['ruleid'])){
                $rule[$k]['isselected'] = 1;
            }else{
                $rule[$k]['isselected'] = 0;
            }
        }
        return view('admin.adminGroup.add',['rule'=>$rule,'cat'=>$cat,'data'=>$data]);
    }

    public function handle(Request $request){
        if (empty($request->groupid)){
            $list=AdminGroup::create([
               'gname'=>$request->gname,
                'miaoshu'=>$request->miaoshu,
                'ruleid'=>$request->ruleid
            ]);
            if ($list){
                return response()->json(['status'=>200,'msg'=>"添加成功"]);
            }else{
                return response()->json(['status'=>404,'msg'=>"添加失败"]);
            }
        }else{
            $list=AdminGroup::where('groupid',$request->groupid)->update([
                'gname'=>$request->gname,
                'miaoshu'=>$request->miaoshu,
                'ruleid'=>$request->ruleid
            ]);
            if ($list !==false){
                return response()->json(['status'=>200,'msg'=>"修改成功"]);
            }else{
                return response()->json(['status'=>404,'msg'=>"修改失败"]);
            }
        }
    }

    public function del(Request $request){
        if ($request->id == 2){
            return response()->json(['status' => '404', 'msg' =>'开发者角色无法删除!',]);
        }

        if (AdminGroup::where('groupid',$request->id)->delete()){
            return response()->json(['status' => '200', 'msg' =>'删除成功!',]);
        }else{
            return response()->json(['status' => '404', 'msg' =>'删除失败!',]);
        }
    }
}
