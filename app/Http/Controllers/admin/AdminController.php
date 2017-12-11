<?php

namespace App\Http\Controllers\admin;

use App\Models\Admin;
use App\Models\AdminGroup;
use App\Models\Statistics;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function lists(){

        $list=Admin::paginate(10);
        foreach ($list as $k => $v){
            $data=AdminGroup::where('groupid',$v['groupid'])->first();
            $list[$k]['groupid']=$data['gname'];
        }
        return view('admin.admin.lists',['user' =>$list,]);
    }

    public function add(){
        $list=AdminGroup::all();
        $data = [];
        return view('admin.admin.add',['list' =>$list,'data'=>$data]);
    }

    public function edit(Request $request){
        $id=$request->id;
        $data=Admin::where('id',$id)->first();
        $list=AdminGroup::all();
        return view('admin.admin.add',['data' =>$data,'list'=>$list]);
    }

    public function handle(Request $request){
        if(empty($request->id)){
            $res=Admin::create([
                'name'=> $request->name,
                'account' => $request->account,
                'password' => md5($request->password),
                'groupid' =>$request->group
            ]);
            if ($res){
                return response()->json([
                    'status' => '200',
                    'text' =>'添加成功',
                ]);
            }else{
                return response()->json([
                    'status' => '400',
                    'text' =>'添加失败',
                ]);
            }
        }else{
            $lis=Admin::where('id',$request->id)->update([
                'name'=> $request->name,
                'account' => $request->account,
                'password' => md5($request->password),
                'groupid' =>$request->group
            ]);

            if ($lis !==false){
                return response()->json([
                    'status' => '200',
                    'text' =>'修改成功',
                ]);
            }else{
                return response()->json([
                    'status' => '400',
                    'text' =>'修改失败',
                ]);
            }
        }
    }

    public function del(Request $request){
        $data = Admin::where('id',$request->id)->first();
        if($data->id == session('admin.id')){
            return response()->json([
                'status' => '404',
                'text' =>'不能删除自己的帐号!',
            ]);
        }
        if($data->account == 'admin'){
            return response()->json([
                'status' => '404',
                'text' =>'超级管理员不可以删除!',
            ]);
        }

        if (Admin::where('id',$request->id)->delete()){
            return response()->json([
                'status' => '200',
                'text' =>'删除成功！!',
            ]);
        }else{
            return response()->json([
                'status' => '404',
                'text' =>'删除失败!',
            ]);
        }
    }
}
