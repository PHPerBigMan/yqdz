<?php

namespace App\Http\Controllers\admin;

use App\Models\Carriage;
use App\Models\Carriage_extend;
use App\Models\Commodity;
use App\Models\Express;
use App\Models\Province;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarriageController extends Controller
{
    public function lists(){
        $list=Carriage::paginate(10);

        return view('admin.carriage.lists',['list'=>$list]);
    }

    public function add(){
        $province=Province::all();

        return view('admin.carriage.add',['province'=>$province]);
    }

    public function edit(Request $request){
        $province=Province::all();
        $data=Carriage::where('carriageid',$request->id)->first();
        $extend=Carriage_extend::where('carriageid',$request->id)->get();
        foreach ($extend as $k => $v){
            foreach ($province as $k1 => $v1){
                if($v1['name'] == $v['takeprovince']){
                    $province[$k1]['is'] = true;
                    break;
                }
            }
        }
        return view('admin.carriage.edit',['province'=>$province,'data'=>$data,'extend'=>$extend]);
    }

    public function handle(Request $request){
        if (empty($request->carriageid)){
            $list=Carriage::create([
                'title'=> $request->title,
                'province' => $request->province,
                'price' => $request->price
            ]);
            if ($list){
                $province = $request->takeprovince;
                $price =$request->price2;
                $first_price =$request->first_price;
                $extra_price =$request->extra_price;
                foreach ($province as $k=>$v){
                    $res=Carriage_extend::create([
                        'carriageid'=> $list['id'],
                        'takeprovince'=> $province[$k],
                        'first_price'=>$first_price[$k],
                        'extra_price'=>$extra_price[$k],
                        'price'=>0
                    ]);
                }
                return response()->json(['status'=>200,'msg'=>"添加成功"]);
            }else{
                return response()->json(['status'=>404,'msg'=>"添加失败"]);
            }
        }else{
            $list=Carriage::where('carriageid',$request->carriageid)->update([
                'title'=>$request->title,
                'province'=>$request->province,
                'price'=>$request->price
            ]);
            if ($list !== false){
               Carriage_extend::where('carriageid',$request->carriageid)->delete();
                $province = $request->takeprovince;
                $price = $request->price2;
                $first_price =$request->first_price;
                $extra_price =$request->extra_price;
                if(!empty($province)){
                    foreach ($province as $k=>$v){
                        $res=Carriage_extend::create([
                            'carriageid'=> $request->carriageid,
                            'takeprovince'=> $province[$k],
                            'first_price'=>$first_price[$k],
                            'extra_price'=>$extra_price[$k],
                            'price'=>0
                        ]);
                    }
                }
                return response()->json(['status'=>200,'msg'=>"修改成功"]);
            }else{
                return response()->json(['status'=>404,'msg'=>"修改失败"]);
            }
        }
    }

    public function del(Request $request){
        if(Commodity::where('carriageid',$request->id)->first()){
            return response()->json(['status' => '404', 'msg' =>'该运费模版还有商品在使用中，不能删除！']);
        }

        if (Carriage::where('carriageid',$request->id)->delete()){
            return response()->json(['status' => '200', 'msg' =>'删除成功!',]);
        }else{
            return response()->json(['status' => '404', 'msg' =>'删除失败!',]);
        }
    }


    /**
     * @return mixed
     * author hongwenyang
     * method description : 快递列表
     */

    public function express(){
        $express = Express::paginate(10);
        return view('admin.carriage.express',compact('express'));
    }

    /**
     * @param Request $request
     * @return mixed
     * author hongwenyang
     * method description : 快递修改
     */

    public function express_edit(Request $request){
        $id = $request->input('id');
        $title = $request->input('title');
        $s = Express::where('id',$id)->update([
            'title'=>$title
        ]);
        if($s){
            return response()->json(['status' => '200', 'msg' =>'修改成功!',]);
        }else{
            return response()->json(['status' => '404', 'msg' =>'修改失败!',]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * author hongwenyang
     * method description : 新增快递
     */

    public function express_add(Request $request){
        $title = $request->input('title');
        $s = Express::insert([
            'title'=>$title
        ]);
        if($s){
            return response()->json(['status' => '200', 'msg' =>'修改成功!',]);
        }else{
            return response()->json(['status' => '404', 'msg' =>'修改失败!',]);
        }
    }


    /**
     * @param Request $request
     * @return mixed
     * author hongwenyang
     * method description : 删除快递
     */

    public function express_del(Request $request){

        if (Express::where('id',$request->id)->delete()){
            return response()->json(['status' => '200', 'msg' =>'删除成功!',]);
        }else{
            return response()->json(['status' => '404', 'msg' =>'删除失败!',]);
        }
    }
}
