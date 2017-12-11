<?php

namespace App\Http\Controllers\home;

use App\Models\Design;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApplyController extends Controller
{
    // 提交私人定制建议方法
    public function appdo(Request $request){
        $data=Design::create($request);
        if ($data){
            return response()->json(['status'=>200,'msg'=>"提交成功"]);
        }else{
            return response()->json(['status'=>404,'msg'=>"提交失败"]);
        }
    }
}
